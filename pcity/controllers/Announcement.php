<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement extends Wx_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('announcement_model');
    }

	public function index()
	{
		$this->view('announcement_list');
	}

	public function getlist($page)
	{
		$q = $this->input->post('q');
		$q = $q ?: '';
		$page = intval($page);
		$page = $page ?: 1;
		$limit = $this->config->item('page_size');
		$offset = $limit * ($page - 1);
		$announcement_list = $this->announcement_model->search_list($q, $limit, $offset);
		$data = array();
		foreach ($announcement_list as $value) {
			$data[] = array(
				'url' => '/announcement/download/' . $value['id'],
				'title' => $value['title'],
				'create_time' => date('Y-m-d', $value['create_time']),
			);
		}
		if (count($data) < $limit) {
			$this->response($data, array('stop' => true));
		} else {
			$this->response($data);
		}
	}

	public function download($id)
	{
        $id = intval($id);
        $this->load->helper('download');
        $file = $this->announcement_model->find($id);
        if (!empty($file)) {
	        $file_path = FCPATH . $this->config->item('announcement_file_path') . $file['file_name'];
	        force_download($file_path, NULL);
	    }
        exit;
	}
}
