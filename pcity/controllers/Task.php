<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends Wx_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('task_model');
    }

	public function index()
	{
        $this->head_title('任务');
		$this->view('task_list');
	}

	public function getlist($page)
	{
		$q = $this->input->post('q');
		$q = $q ?: '';
		$page = intval($page);
		$page = $page ?: 1;
		$limit = $this->config->item('page_size');
		$offset = $limit * ($page - 1);
		$task_list = $this->task_model->search_list($q, $limit, $offset);
		$data = array();
		foreach ($task_list as $value) {
			$data[] = array(
				'title' => $value['title'],
				'content' => $value['content'],
				'create_time' => date('Y-m-d', $value['create_time']),
			);
		}
		if (count($data) < $limit) {
			$this->response($data, array('stop' => true));
		} else {
			$this->response($data);
		}
	}

	public function add()
	{
		$title = $this->input->post('title', true);
		$content = $this->input->post('content', true);
		if (empty($content)) {
			$this->response('请填写任务内容');
		}
		$c_time = time();
		$this->task_model->insert(array(
			'title' => $title,
			'content' => $content,
			'create_time' => $c_time,
			'update_time' => $c_time,
		));
		$this->response(array(
			'title' => $title,
			'content' => $content,
			'create_time' => date('Y-m-d', $c_time),
		));
	}
}
