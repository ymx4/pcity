<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materiel extends Wx_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('materiel_model');
    }

    public function index()
    {
        $this->head_title('物料管控');
        $data = array('_user' => $this->_user);

    	$this->view('materiel_list', $data);
    }

    public function getlist($page)
    {
        $q = $this->input->post('q');
        $q = $q ?: '';
        $page = intval($page);
        $page = $page ?: 1;
        $limit = $this->config->item('page_size');
        $offset = $limit * ($page - 1);

        $where = array();
        switch ($this->_user['role']) {
            case 1:
                $where['constructor_id'] = $this->_user['id'];
                break;
            case 2:
                $where['supervisor_id'] = $this->_user['id'];
                break;
            case 3:
                $where['builder_id'] = $this->_user['id'];
                break;

            default:
                $this->response('无权限');
                return;
        }

        $materiel_list = $this->materiel_model->get_list($where, $limit, $offset);
        $data = array();
        foreach ($materiel_list as $value) {
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
        $this->materiel_model->insert(array(
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
