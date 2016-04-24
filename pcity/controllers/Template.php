<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends Wx_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('template_model');
    }

	public function index($cid = 1)
	{
        $this->head_title('样板库');
		$this->view('template_list', array('cid' => $cid));
	}

	public function getlist($cid, $page)
	{
		$q = $this->input->post('q');
		$q = $q ?: '';
		$page = intval($page);
		$page = $page ?: 1;
		$limit = $this->config->item('page_size');
		$offset = $limit * ($page - 1);
		$template_list = $this->template_model->search_list($cid, $q, $limit, $offset);
		$data = array();
		foreach ($template_list as $value) {
			$data[] = array(
				'url' => '/template/detail/' . $value['id'],
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

	public function detail($id)
	{
        $id = intval($id);
        $template = $this->template_model->find($id);
        if (empty($template)) {
	        show_404();
	    }
	    $t_images = array();
        if (!empty($template['image'])) {
            $t_images = json_decode($template['image'], true);

            foreach ($t_images as $key => $value) {
                $t_images[$key] = array(
                    'name' => $value,
                    'url' => site_url($this->config->item('template_image_path') . $value),
                );
            }
        }

        $category = $template['category'];
        foreach (self::$template_categories[$category]['fields'] as $field) {
	        	$tmp[] = array(
	        		'title' => self::$template_fields[$field],
	        		'value' => $template[$field],
	    		);
        }
	    $data = array(
	    	'id' => $template['id'],
	    	'create_time' => $template['create_time'],
	    	't_images' => $t_images,
	    	'template' => $tmp,
    	);
        $this->head_title($template['title']);
        $this->view('template', $data);
	}
}
