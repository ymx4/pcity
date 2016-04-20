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
}
