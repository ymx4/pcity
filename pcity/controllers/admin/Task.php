<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Task
 */
class Task extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('task_model');
    }

    public function index()
    {
        $offset = intval($this->input->get('o'));
        $data   = array(
            'task_list' => array(),
        );

        $count = $this->task_model->get_count(array());
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['task_list'] = $this->task_model->get_list(array(), $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/task_list', $data);
    }

    public function edit($id = 0)
    {
        $data = array(
            'task' => array(
                'title' => '',
                'content' => '',
            ),
            'status' => 0,
        );

        if ($id) {
            $data['task'] = $taskfile = $this->task_model->get_one(array('id' => $id));
            if (empty($taskfile)) {
                show_404();
            }
            $data['task_file'] = '/task/download/' . $taskfile['id'];
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $data['task'] = array_merge($data['task'], $post);

            // if (empty($post['title'])) {
            //     $data['error'] = '请填写标题';
            // }

            if (empty($post['content'])) {
                $data['error'] = '请填写内容';
            }

            if (empty($data['error'])) {
                if ($id) {
                    $post['update_time'] = time();
                    $this->task_model->update($post, array('id' => $id));
                } else {
                    $post['create_time'] = $post['update_time'] = time();
                    $data['task']['id'] = $id = $this->task_model->insert($post);
                }

                $data['status'] = 1;
            }
        }
        $this->load->view('admin/task', $data);
    }

    public function delete($id = 0)
    {
        $data = array('code' => 0);
        $task = $this->task_model->get_one(array('id' => $id));
        if (!empty($task)) {
            $this->task_model->delete(array('id' => $id));
            $data['code'] = 1;
        }
        echo json_encode($data);
    }
}
