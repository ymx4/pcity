<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Company
 */
class Company extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('company_model');
    }

    public function index()
    {
        $offset = intval($this->input->get('o'));
        $data   = array(
            'company_list' => array(),
        );

        $count = $this->company_model->get_count(array());
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['company_list'] = $this->company_model->get_list(array(), $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/company_list', $data);
    }

    public function edit($id = 0)
    {
        $data = array(
            'company' => array(
                'name' => '',
                'type' => 0,
            ),
            'company_type' => self::$company_type,
            'status' => 0,
        );

        if ($id) {
            $data['company'] = $this->company_model->get_one(array('id' => $id));
            if (empty($data['company'])) {
                show_404();
            }
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $data['company'] = array_merge($data['company'], $post);

            if (empty($post['name'])) {
                $data['error'] = '请填写公司名';
            }

            $post['type'] = intval($post['type']);
            $type_list = array_keys(self::$company_type);
            if (!in_array($post['type'], $type_list)) {
                $data['error'] = '请选择公司类型';
            }

            if (empty($data['error'])) {
                if ($id) {
                    $post['update_time'] = time();
                    $this->company_model->update($post, array('id' => $id));
                } else {
                    $post['create_time'] = $post['update_time'] = time();
                    $data['company']['id'] = $id = $this->company_model->insert($post);
                }

                $data['status'] = 1;
            }
        }
        $this->load->view('admin/company', $data);
    }

    public function delete($id = 0)
    {
        $data = array('code' => 0);
        $company = $this->company_model->get_one(array('id' => $id));
        if (!empty($company)) {
            $this->company_model->delete(array('id' => $id));
            $data['code'] = 1;
        }
        echo json_encode($data);
    }
}
