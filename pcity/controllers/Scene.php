<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scene extends Wx_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('scene_model');
    }

    public function index()
    {
        $this->head_title('现场管控');

    	$this->view('scene_list');
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
            $where['status'] = 99;
        } else {
            $where['status <'] = 99;
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

        $scene_list = $this->scene_model->get_list($where, $limit, $offset, 'create_time');
        $data = array();
        foreach ($scene_list as $value) {
            $tmp = array(
                'title' => $value['title'],
                'create_time' => date('Y-m-d H:i A', $value['create_time']),
                'url' => site_url('scene/detail/' . $value['id']),
            );
            switch ($value['status']) {
                case 1:
                    switch ($this->_user['id']) {
                        case $value['constructor_id']:
                            $tmp['desc'] = '等待监理单位验收';
                            break;
                        case $value['supervisor_id']:
                            $tmp['desc'] = '自检合格，请验收';
                            break;

                        default:
                            $this->response('无权限');
                            return;
                    }
                    
                    break;
                case 2:
                    switch ($this->_user['id']) {
                        case $value['constructor_id']:
                            $tmp['desc'] = '等待建设单位验收';
                            break;
                        case $value['supervisor_id']:
                            $tmp['desc'] = '等待建设单位验收';
                            break;
                        case $value['builder_id']:
                            $tmp['desc'] = '监理单位验收合格，请复检';
                            break;

                        default:
                            $this->response('无权限');
                            return;
                    }
                    break;
                case 3:
                    $tmp['desc'] = '验收不合格，请施工单位返工';
                    break;
                case 4:
                    $tmp['desc'] = '验收不合格，请施工单位返工';
                    break;
                
                default:
                    $tmp['desc'] = '验收合格';
                    break;
            }
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
        $data['scene'] = $this->scene_model->get_one($where);
        if (empty($data['scene'])) {
            show_404();
        }
        $supervisor_check_options = array(
            3 => '验收不合格，请施工单位返工',
            99 => '验收合格，抄送建设单位',
            2 => '验收合格，请建设单位复检',
        );
        $builder_check_options = array(
            4 => '验收不合格，请施工单位返工',
            99 => '验收合格',
        );
        $date_dir = date('Ym') . '/';
        $base_path = $this->config->item('scene_image_path') . $date_dir;
        $builder_list = $this->weixin_model->role_list(array('company.type' => 3));
        $error = array();
        $post = $this->input->post();
        if (!empty($post)) {
            if ($this->_user['id'] == $data['scene']['supervisor_id'] && $data['scene']['status'] == 1) {
                $data['scene'] = array_merge($data['scene'], $post);

                if (empty($_FILES['up_supervisor_image']) || $_FILES['up_supervisor_image']['error'] == 4) {
                    $error[] = '请上传相关相片';
                }
                if (!isset($supervisor_check_options[$post['supervisor_check']])) {
                    $error[] = '请选择验收意见';
                }
                if ($post['builder_id'] == -1) {
                    $error[] = '请选择建设单位';
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
                        'file_name'     => $this->_user['id'] . '-2-' . time(),
                    );

                    $this->upload->initialize($upConfig);
                    $uploaded = $this->upload->do_upload('up_supervisor_image');

                    if (!$uploaded) {
                        $error[] = '上传出错：' . $this->upload->display_errors();
                    } else {
                        $updata = $this->upload->data();
                        $data['scene']['supervisor_image'] = $date_dir . $updata['file_name'];
                        $data['scene']['status'] = $post['supervisor_check'];

                        $this->scene_model->update(array(
                            'supervisor_image' => $date_dir . $updata['file_name'],
                            'supervisor_check' => $post['supervisor_check'],
                            'status' => $post['supervisor_check'],
                            'builder_id' => $post['builder_id'],
                            'update_time' => time(),
                        ), array('id' => $id));

                        $data['status'] = 1;
                    }
                }
            } elseif ($this->_user['id'] == $data['scene']['builder_id'] && $data['scene']['status'] == 2) {
                $data['scene'] = array_merge($data['scene'], $post);

                if (empty($_FILES['up_builder_image']) || $_FILES['up_builder_image']['error'] == 4) {
                    $error[] = '请上传相关相片';
                } elseif (!isset($builder_check_options[$post['builder_check']])) {
                    $error[] = '请选择验收意见';
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
                        'file_name'     => $this->_user['id'] . '-3-' . time(),
                    );

                    $this->upload->initialize($upConfig);
                    $uploaded = $this->upload->do_upload('up_builder_image');

                    if (!$uploaded) {
                        $error[] = '上传出错：' . $this->upload->display_errors();
                    } else {
                        $updata = $this->upload->data();
                        $data['scene']['builder_image'] = $date_dir . $updata['file_name'];
                        $data['scene']['status'] = $post['builder_check'];

                        $this->scene_model->update(array(
                            'builder_image' => $date_dir . $updata['file_name'],
                            'builder_check' => $post['builder_check'],
                            'status' => $post['builder_check'],
                            'update_time' => time(),
                        ), array('id' => $id));

                        $data['status'] = 1;
                    }
                }
            }
        }
        $this->load->helper('jhtml');
        $data['supervisor_check_options'] = get_select_optionsByArr($supervisor_check_options, $data['scene']['supervisor_check']);
        $data['scene']['supervisor_check'] = isset($supervisor_check_options[$data['scene']['supervisor_check']]) ? $supervisor_check_options[$data['scene']['supervisor_check']] : '';
        $data['scene']['supervisor'] = $this->user_lib->get_user($data['scene']['supervisor_id'], 'nickname');

        $data['builder_options'] = get_select_options($builder_list,'id','nickname',$data['scene']['builder_id']);

        $data['builder_check_options'] = get_select_optionsByArr($builder_check_options, $data['scene']['builder_check']);
        $data['scene']['builder_check'] = isset($builder_check_options[$data['scene']['builder_check']]) ? $builder_check_options[$data['scene']['builder_check']] : '';
        $data['scene']['builder'] = $data['scene']['builder_id'] ? $this->user_lib->get_user($data['scene']['builder_id'], 'nickname') : '';

        $data['scene']['constructor'] = $this->user_lib->get_user($data['scene']['constructor_id'], 'nickname');
        $data['scene']['supervisor'] = $data['scene']['supervisor_id'] ? $this->user_lib->get_user($data['scene']['supervisor_id'], 'nickname') : '';
        $data['scene']['builder'] = $data['scene']['builder_id'] ? $this->user_lib->get_user($data['scene']['builder_id'], 'nickname') : '';

        $data['scene']['constructor_image'] = site_url($this->config->item('scene_image_path') . $data['scene']['constructor_image']);
        $data['scene']['supervisor_image'] = $data['scene']['supervisor_image'] ? site_url($this->config->item('scene_image_path') . $data['scene']['supervisor_image']) : '';
        $data['scene']['builder_image'] = $data['scene']['builder_image'] ? site_url($this->config->item('scene_image_path') . $data['scene']['builder_image']) : '';

        if (!empty($error)) {
            $data['error'] = implode('<br>', $error);
        }
        $this->view('scene_detail', $data);
    }

    public function add()
    {
        $this->load->helper('jhtml');
        $data = array(
            'scene' => array(
                'title' => '',
                'address' => '',
                'supervisor_id' => 0,
            ),
        );
        $date_dir = date('Ym') . '/';
        $base_path = $this->config->item('scene_image_path') . $date_dir;

        $error = array();
        $post = $this->input->post();
        if (!empty($post)) {
            $data['scene'] = array_merge($data['scene'], $post);

            if (empty($post['title'])) {
                $error[] = '请填写报验内容';
            }
            if (empty($post['address'])) {
                $error[] = '请填写详细地址';
            }
            if (empty($_FILES['up_constructor_image']) || $_FILES['up_constructor_image']['error'] == 4) {
                $error[] = '请上传自检相片';
            }
            if ($post['supervisor_id'] == -1) {
                $error[] = '请选择监理单位';
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
                $uploaded = $this->upload->do_upload('up_constructor_image');

                if (!$uploaded) {
                    $error[] = '上传出错：' . $this->upload->display_errors();
                } else {
                    $c_time = time();
                    $updata = $this->upload->data();

                    $id = $this->scene_model->insert(array(
                        'title' => $post['title'],
                        'address' => $post['address'],
                        'constructor_image' => $date_dir . $updata['file_name'],
                        'constructor_id' => $this->_user['id'],
                        'supervisor_id' => $post['supervisor_id'],
                        'create_time' => $c_time,
                        'update_time' => $c_time,
                    ));

                    redirect('scene/detail/' . $id);
                }
            }
        }
        if (!empty($error)) {
            $data['error'] = implode('<br>', $error);
        }
        $supervisor_list = $this->weixin_model->role_list(array('company.type' => 2));
        $data['supervisor_options'] = get_select_options($supervisor_list,'id','nickname',$data['scene']['supervisor_id']);
        $this->view('scene', $data);
    }
}
