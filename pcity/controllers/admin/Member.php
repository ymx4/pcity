<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Member
 */
class Member extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('weixin_model');
    }

    public function index()
    {
        $offset = intval($this->input->get('o'));
        $data   = array(
            'member_list' => array(),
        );
        $nickname = $this->input->get('nickname');

        $count = $this->weixin_model->member_count($nickname);
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['member_list'] = $this->weixin_model->member_list($nickname, $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/member_list', $data);
    }

    public function edit($user_id)
    {
        $this->load->model(array('company_model', 'user_auth_model'));
        $data = array(
            'auth_list' => self::$auth_list,
            'status' => 0,
            'company_list' => $this->company_model->get_list(),
            'auth' => array(),
        );

        $member = $this->weixin_model->find($user_id);
        if (empty($member)) {
            show_error('用户不存在');
        }
        $data['member'] = $member;

        $authrow = $this->user_auth_model->find($user_id, 'user_id');
        if (!empty($authrow) && !empty($authrow['auth'])) {
            $data['auth'] = json_decode($authrow['auth'], true);
        }
        $post = $this->input->post();
        if (!empty($post)) {
            if (!empty($post['auth'])) {
                $data['auth'] = $post['auth'];
                $auth_ids = array_keys(self::$auth_list);
                foreach ($post['auth'] as $value) {
                    if (!in_array($value, $auth_ids)) {
                        $data['error'] = '权限选择出错';
                        break;
                    }
                }
                $post['auth'] = json_encode($post['auth']);
            } else {
                $data['auth'] = array();
                $post['auth'] = '';
            }

            if (!empty($post['company_id'])) {
                $exist = $this->company_model->find($post['company_id']);
                if (empty($exist)) {
                    $data['error'] = '所属公司选择出错';
                }
            } else {
                $post['company_id'] = 0;
            }
            $data['member']['company_id'] = $post['company_id'];

            if (empty($data['error'])) {
                if (empty($authrow)) {
                    $this->user_auth_model->insert(array(
                        'user_id' => $user_id,
                        'auth' => $post['auth'],
                    ));
                } else {
                    $this->user_auth_model->update(array(
                        'auth' => $post['auth'],
                    ), array('user_id' => $user_id));
                }
                $this->weixin_model->update(array(
                    'company_id' => $post['company_id'],
                ), array('id' => $user_id));
                $data['status'] = 1;
            }
        }
        $this->load->view('admin/member', $data);
    }
}
