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
        $is_complete = $this->input->post('comp');
        $page = intval($page);
        $page = $page ?: 1;
        $limit = $this->config->item('page_size');
        $offset = $limit * ($page - 1);

        $where = array();
        if ($is_complete) {
            $where['status'] = 9;
        }
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

        $materiel_list = $this->materiel_model->get_list($where, $limit, $offset, 'create_time');
        $data = array();
        foreach ($materiel_list as $value) {
            $data[] = array(
                'title' => $value['title'],
                'create_time' => date('Y-m-d H:i A', $value['create_time']),
                'url' => site_url('materiel/edit/' . $value['id']),
                'desc' => '等待监理单位验收',
            );
        }
        if (count($data) < $limit) {
            $this->response($data, array('stop' => true));
        } else {
            $this->response($data);
        }
    }

    public function edit($id = 0)
    {
        $this->load->helper('jhtml');
        $data = array(
            'materiel' => array(
                'id' => $id,
                'title' => '',
                'position' => '',
                'supervisor_id' => 0,
                'quantity' => '',
            ),
            'constructor_image' => '',
            'supervisor_image' => '',
            'status' => 0,
        );
        $base_path = $this->config->item('materiel_image_path') . date('Ym') . '/';

        if ($id) {
            $data['materiel'] = $materiel_ori = $this->materiel_model->get_one(array('id' => $id));
            if (empty($materiel_ori) || $materiel_ori['constructor_id'] != $this->_user['id']) {
                show_404();
            }
            $data['constructor_image'] = site_url($base_path . $materiel_ori['constructor_image']);
            $data['supervisor_image'] = site_url($base_path . $materiel_ori['supervisor_image']);
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $data['materiel'] = array_merge($data['materiel'], $post);

            if (empty($post['title'])) {
                $data['error'] = '请填写物料名称';
            } elseif (empty($post['position'])) {
                $data['error'] = '请填写使用部位';
            } elseif (empty($_FILES['up_constructor_image']) || $_FILES['up_constructor_image']['error'] == 4) {
                $data['error'] = '请上传物料相片';
            } elseif (empty($post['quantity'])) {
                $data['error'] = '请填写物料数量';
            } elseif (empty($post['supervisor_id'])) {
                $data['error'] = '请填写监理单位';
            }
            if (empty($data['error'])) {
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
                $uploaded = $this->upload->do_upload('up_constructor_image');

                if (!$uploaded) {
                    $data['error'] = '上传出错：' . $this->upload->display_errors();
                } else {
                    $c_time = time();
                    $updata = $this->upload->data();
                    $data['constructor_image'] = site_url($base_path . $updata['file_name']);
                    if ($id && $materiel_ori['constructor_image'] && file_exists($upConfig['upload_path'] . $materiel_ori['constructor_image'])) {
                        unlink($upConfig['upload_path'] . $materiel_ori['constructor_image']);
                    }

                    if ($id) {
                        $this->materiel_model->update(array(
                            'title' => $post['title'],
                            'position' => $post['position'],
                            'constructor_image' => $updata['file_name'],
                            'quantity' => $post['quantity'],
                            'supervisor_id' => $post['supervisor_id'],
                            'update_time' => $c_time,
                        ), array('id' => $id));
                    } else {
                        $data['materiel']['id'] = $this->materiel_model->insert(array(
                            'title' => $post['title'],
                            'position' => $post['position'],
                            'constructor_image' => $updata['file_name'],
                            'quantity' => $post['quantity'],
                            'constructor_id' => $this->_user['id'],
                            'supervisor_id' => $post['supervisor_id'],
                            'create_time' => $c_time,
                            'update_time' => $c_time,
                        ));
                    }

                    $data['status'] = 1;
                }
            }
        }
        $supervisor_list = $this->weixin_model->role_list(array('company.type' => 2));
        $data['supervisor_options'] = get_select_options($supervisor_list,'id','nickname',$data['materiel']['supervisor_id']);
        $this->view('materiel', $data);
    }
}
