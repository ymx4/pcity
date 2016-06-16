<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Property extends Wx_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('property_model');
    }

    public function index()
    {
        if ($this->_user['role'] != 5 && $this->_user['role'] != 4) {
            show_404();
        }
        $this->head_title('移交物业');

    	$this->view('property_list');
    }

    public function getlist($page)
    {
        $is_complete = $this->input->post('comp');
        $page = intval($page);
        $page = $page ?: 1;
        $limit = $this->config->item('page_size');
        $offset = $limit * ($page - 1);

        $where = array();
        if ($is_complete) {
            $where['status'] = 2;
        } else {
            $where['status <>'] = 2;
        }
        switch ($this->_user['role']) {
            case 5:
                $where['creator_id'] = $this->_user['id'];
                break;

            case 4:
                $where['property_id'] = $this->_user['id'];
                break;

            default:
                $this->response('无权限');
                return;
        }

        $property_list = $this->property_model->get_list($where, $limit, $offset, 'create_time');
        $data = array();
        foreach ($property_list as $value) {
            $tmp = array(
                'title' => $value['title'],
                'create_time' => date('Y-m-d H:i A', $value['create_time']),
                'url' => site_url('property/detail/' . $value['id']),
            );
            $data[] = $tmp;
        }
        if (count($data) < $limit) {
            $this->response($data, array('stop' => true));
        } else {
            $this->response($data);
        }
    }

    public function detail($id)
    {
        $data = array('status' => 0);
        $where = array('id' => $id);
        switch ($this->_user['role']) {
            case 5:
                $where['creator_id'] = $this->_user['id'];
                break;

            case 4:
                $where['property_id'] = $this->_user['id'];
                break;

            default:
                $this->response('无权限');
                return;
        }
        $data['property'] = $this->property_model->get_one($where);
        if (empty($data['property'])) {
            show_404();
        }
        $property_check_options = array(
            2 => '已完成整改,同意接收',
            3 => '未完成整改',
        );

        $date_dir = date('Ym') . '/';
        $base_path = $this->config->item('property_image_path') . $date_dir;
        $error = array();
        $post = $this->input->post();
        if (!empty($post)) {
            if ($this->_user['id'] == $data['property']['property_id'] && $data['property']['status'] == 1) {
                $data['property'] = array_merge($data['property'], $post);

                if (!isset($property_check_options[$post['status']])) {
                    $error[] = '请选择验收意见';
                }
                if (empty($error)) {
                    $this->property_model->update(array(
                        'status' => $post['status'],
                        'unqualified_num' => (int)$post['unqualified_num'],
                        'update_time' => time(),
                    ), array('id' => $id));

                    $data['status'] = 1;
                }
            }
        }
        $this->load->helper('jhtml');
        $data['property_check_options'] = get_select_optionsByArr($property_check_options, $data['property']['status']);
        $data['property']['property_user'] = $this->user_lib->get_user($data['property']['property_id'], 'nickname');

        $data['property']['description'] = nl2br($data['property']['description']);

        $data['property']['problem_image'] = site_url($this->config->item('property_image_path') . $data['property']['problem_image']);
        $data['property']['fix_image'] = $data['property']['fix_image'] ? site_url($this->config->item('property_image_path') . $data['property']['fix_image']) : '';

        if (!empty($error)) {
            $data['error'] = implode('<br>', $error);
        }
        $this->view('property_detail', $data);
    }

    public function add()
    {
        $this->load->helper('jhtml');
        $data = array(
            'property' => array(
                'title' => '',
                'description' => '',
                'property_id' => 0,
            ),
        );
        $error = array();
        $date_dir = date('Ym') . '/';
        $base_path = $this->config->item('property_image_path') . $date_dir;

        $post = $this->input->post();
        if (!empty($post)) {
            $data['property'] = array_merge($data['property'], $post);

            if (empty($post['title'])) {
                $error[] = '请填写标题';
            }
            if (empty($_FILES['up_problem_image']) || $_FILES['up_problem_image']['error'] == 4) {
                $error[] = '请上传问题图片';
            }
            if (empty($post['description'])) {
                $error[] = '请填写问题描述';
            }
            if (empty($_FILES['up_fix_image']) || $_FILES['up_fix_image']['error'] == 4) {
                $error[] = '请上传整改图片';
            }
            if ($post['property_id'] == -1) {
                $error[] = '请选择物业公司';
            }
            if (empty($error)) {
                $this->load->library('upload');

                if (!is_dir(FCPATH . $base_path)) {
                    mkdir(FCPATH . $base_path, 0755, true);
                }
                $upConfig = array(
                    'upload_path'   => FCPATH . $base_path,
                    'allowed_types' => 'jpg|jpeg|png|gif',
                    'max_size'      => $this->config->item('size_limit'),
                    'file_name'     => $this->_user['id'] . '-1-' . time(),
                );

                $this->upload->initialize($upConfig);
                $uploaded = $this->upload->do_upload('up_problem_image');

                if (!$uploaded) {
                    $error[] = '上传出错：' . $this->upload->display_errors();
                } else {
                    $updata = $this->upload->data();
                    $upConfig = array(
                        'upload_path'   => FCPATH . $base_path,
                        'allowed_types' => 'jpg|jpeg|png|gif',
                        'max_size'      => $this->config->item('size_limit'),
                        'file_name'     => $this->_user['id'] . '-2-' . time(),
                    );

                    $this->upload->initialize($upConfig);
                    $uploaded2 = $this->upload->do_upload('up_fix_image');
                    if (!$uploaded2) {
                        $error[] = '上传出错：' . $this->upload->display_errors();
                    } else {
                        $c_time = time();
                        $updata2 = $this->upload->data();

                        $id = $this->property_model->insert(array(
                            'title' => $post['title'],
                            'description' => $post['description'],
                            'problem_image' => $date_dir . $updata['file_name'],
                            'fix_image' => $date_dir . $updata2['file_name'],
                            'creator_id' => $this->_user['id'],
                            'property_id' => $post['property_id'],
                            'create_time' => $c_time,
                            'update_time' => $c_time,
                        ));

                        redirect('property/detail/' . $id);
                    }
                }
            }
        }
        if (!empty($error)) {
            $data['error'] = implode('<br>', $error);
        }
        $property_list = $this->weixin_model->role_list(array('company.type' => 4));
        $data['property_options'] = get_select_options($property_list,'id','nickname',$data['property']['property_id']);
        $this->view('property', $data);
    }
}
