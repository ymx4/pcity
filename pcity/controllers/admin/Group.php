<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Group
 */
class Group extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('group_model');
    }

    public function index()
    {
        $offset = intval($this->input->get('o'));
        $data   = array(
            'group_list' => array(),
        );

        $count = $this->group_model->get_count(array());
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['group_list'] = $this->group_model->get_list(array(), $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/group_list', $data);
    }

    public function edit($id = 0)
    {
        $data = array(
            'group' => array(
                'title' => '',
            ),
            'status' => 0,
        );

        if ($id) {
            $data['group'] = $this->group_model->get_one(array('id' => $id));
            if (empty($data['group'])) {
                show_404();
            }
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $data['group'] = array_merge($data['group'], $post);

            if (empty($post['title'])) {
                $data['error'] = '请填写标题';
            }

            if (empty($data['error'])) {
                if ($id) {
                    $post['update_time'] = time();
                    $this->group_model->update($post, array('id' => $id));
                } else {
                    $post['create_time'] = $post['update_time'] = time();
                    $data['group']['id'] = $this->group_model->insert($post);
                }

                $data['status'] = 1;
            }
        }
        $this->load->view('admin/group', $data);
    }

    public function delete($id = 0)
    {
        $data = array('code' => 0);
        $group = $this->group_model->get_one(array('id' => $id));
        if (!empty($group)) {
            $this->load->model('group_role_model');
            $this->group_role_model->delete(array(
                'group_id' => $id,
            ));
            $this->group_model->delete(array('id' => $id));
            $data['code'] = 1;
        }
        echo json_encode($data);
    }

    public function join($user_id = 0, $group_id = 0)
    {
        $data = array(
            'roles' => self::$group_role,
            'nickname' => '',
            'group' => '',
            'user_id' => $user_id,
        );
        $this->load->model('weixin_model');
        if ($user_id) {
            $member = $this->weixin_model->find($user_id);
            if (empty($member)) {
                show_error('用户不存在');
            }
            $data['nickname'] = $member['nickname'];
        }
        if ($group_id) {
            $group = $this->group_model->find($group_id);
            if (empty($group)) {
                show_error('分组不存在');
            }
            $data['group'] = $group['title'];
        }
        $post = $this->input->post();
        if (!empty($post)) {
            $data = array_merge($data, $post);
            $post['role'] = intval($post['role']);
            $roles = array_keys(self::$group_role);
            if (!in_array($post['role'], $roles)) {
                $data['error'] = '请选择权限';
            }
            if (empty($post['group'])) {
                $data['error'] = '请填写分组';
            }
            if (!$user_id && empty($post['nickname'])) {
                $data['error'] = '请填写用户昵称';
            }
            if (empty($data['error'])) {
                $this->load->model(array('group_role_model'));
                $group = $this->group_model->get_one(array('title' => $post['group']));
                if (!$user_id) {
                    $member = $this->weixin_model->get_one(array('nickname' => $post['nickname']));
                }
                if (empty($group)) {
                    $data['error'] = '分组不存在';
                } elseif (empty($member)) {
                    $data['error'] = '用户不存在';
                } else {
                    $exist = $this->group_role_model->get_count(array(
                        'group_id' => $group['id'],
                        'user_id' => $member['id'],
                    ));
                    if ($exist) {
                        $data['error'] = '用户已加入该分组';
                    } else {
                        $this->group_role_model->insert(array(
                            'group_id' => $group['id'],
                            'user_id' => $member['id'],
                            'role' => $post['role'],
                        ));
                        redirect(site_url('admin/group/detail/' . $group['id']));
                    }
                }
            }
        }
        $this->load->view('admin/group_join', $data);
    }

    public function detail($id)
    {
        $group = $this->group_model->find($id);
        if (empty($group)) {
            show_404();
        }
        $this->load->model('group_role_model');
        $group_role = $this->group_role_model->role_list(array(
            'group_id' => $id,
        ));
        $data = array(
            'group' => $group,
            'group_role' => array(),
            'roles' => self::$group_role,
        );
        foreach ($group_role as $key => $value) {
            $role = $value['role'];
            if (empty($data['group_role'][$role])) {
                $data['group_role'][$role] = array($value);
            } else {
                $data['group_role'][$role][] = $value;
            }
        }
        $this->load->view('admin/group_detail', $data);
    }

    public function delrole($group_id, $user_id)
    {
        $this->load->model('group_role_model');
        $data = array('code' => 0);
        $group = $this->group_role_model->get_one(array(
            'group_id' => $group_id,
            'user_id' => $user_id,
        ));
        if (!empty($group)) {
            $this->group_role_model->delete(array(
                'group_id' => $group_id,
                'user_id' => $user_id,
            ));
            $data['code'] = 1;
        }
        echo json_encode($data);
    }
}
