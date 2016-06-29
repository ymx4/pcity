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

    	$this->view('materiel_list');
    }

    public function complete()
    {
        $this->head_title('物料管控');

        $this->view('materiel_complete');
    }

    public function getlist($page = 1)
    {
        $q = $this->input->post('q');
        $q = $q ?: '';
        $is_complete = $this->input->post('comp');
        $page = intval($page);
        $page = $page ?: 1;
        $limit = $this->config->item('page_size');
        $offset = $limit * ($page - 1);
        $total = $this->input->post('total');

        if ($total) {
            $where = array('status' => 99);
        } else {
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
        }

        $materiel_list = $this->materiel_model->search_list($q, $where, $limit, $offset);
        $data = array();
        foreach ($materiel_list as $value) {
            $tmp = array(
                'title' => $value['title'],
                'create_time' => date('Y-m-d H:i A', $value['create_time']),
                'url' => site_url('materiel/detail/' . $value['id']),
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
                $where['status'] = 99;
                return;
        }
        $data['materiel'] = $this->materiel_model->get_one($where);
        if (empty($data['materiel'])) {
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
        $error = array();
        $date_dir = date('Ym') . '/';
        $base_path = $this->config->item('materiel_image_path') . $date_dir;
        $builder_list = $this->weixin_model->role_list(array('company.type' => 3));
        $post = $this->input->post();
        if (!empty($post)) {
            if ($this->_user['id'] == $data['materiel']['supervisor_id'] && $data['materiel']['status'] == 1) {
                $data['materiel'] = array_merge($data['materiel'], $post);

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
                        $data['materiel']['supervisor_image'] = $date_dir . $updata['file_name'];
                        $data['materiel']['status'] = $post['supervisor_check'];

                        $this->materiel_model->update(array(
                            'supervisor_image' => $date_dir . $updata['file_name'],
                            'supervisor_check' => $post['supervisor_check'],
                            'status' => $post['supervisor_check'],
                            'builder_id' => $post['builder_id'],
                            'update_time' => time(),
                        ), array('id' => $id));

                        $data['status'] = 1;
                    }
                }
            } elseif ($this->_user['id'] == $data['materiel']['builder_id'] && $data['materiel']['status'] == 2) {
                $data['materiel'] = array_merge($data['materiel'], $post);

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
                        $data['materiel']['builder_image'] = $date_dir . $updata['file_name'];
                        $data['materiel']['status'] = $post['builder_check'];

                        $this->materiel_model->update(array(
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
        $data['supervisor_check_options'] = get_select_optionsByArr($supervisor_check_options, $data['materiel']['supervisor_check']);
        $data['materiel']['supervisor_check'] = isset($supervisor_check_options[$data['materiel']['supervisor_check']]) ? $supervisor_check_options[$data['materiel']['supervisor_check']] : '';
        $data['materiel']['supervisor'] = $this->user_lib->get_user($data['materiel']['supervisor_id'], 'nickname');

        $data['builder_options'] = get_select_options($builder_list,'id','nickname',$data['materiel']['builder_id']);

        $data['builder_check_options'] = get_select_optionsByArr($builder_check_options, $data['materiel']['builder_check']);
        $data['materiel']['builder_check'] = isset($builder_check_options[$data['materiel']['builder_check']]) ? $builder_check_options[$data['materiel']['builder_check']] : '';
        $data['materiel']['builder'] = $data['materiel']['builder_id'] ? $this->user_lib->get_user($data['materiel']['builder_id'], 'nickname') : '';

        $data['materiel']['constructor'] = $this->user_lib->get_user($data['materiel']['constructor_id'], 'nickname');
        $data['materiel']['supervisor'] = $data['materiel']['supervisor_id'] ? $this->user_lib->get_user($data['materiel']['supervisor_id'], 'nickname') : '';
        $data['materiel']['builder'] = $data['materiel']['builder_id'] ? $this->user_lib->get_user($data['materiel']['builder_id'], 'nickname') : '';

        $data['materiel']['constructor_image'] = site_url($this->config->item('materiel_image_path') . $data['materiel']['constructor_image']);
        $data['materiel']['supervisor_image'] = $data['materiel']['supervisor_image'] ? site_url($this->config->item('materiel_image_path') . $data['materiel']['supervisor_image']) : '';
        $data['materiel']['builder_image'] = $data['materiel']['builder_image'] ? site_url($this->config->item('materiel_image_path') . $data['materiel']['builder_image']) : '';

        if (!empty($error)) {
            $data['error'] = implode('<br>', $error);
        }
        $this->view('materiel_detail', $data);
    }

    public function add()
    {
        $this->load->helper('jhtml');
        $data = array(
            'materiel' => array(
                'title' => '',
                'position' => '',
                'supervisor_id' => 0,
                'quantity' => '',
            ),
        );
        $date_dir = date('Ym') . '/';
        $base_path = $this->config->item('materiel_image_path') . $date_dir;

        $post = $this->input->post();
        $error = array();
        if (!empty($post)) {
            $data['materiel'] = array_merge($data['materiel'], $post);

            if (empty($post['title'])) {
                $error[] = '请填写物料名称';
            }
            if (empty($post['position'])) {
                $error[] = '请填写使用部位';
            }
            if (empty($_FILES['up_constructor_image']) || $_FILES['up_constructor_image']['error'] == 4) {
                $error[] = '请上传物料相片';
            }
            if (empty($post['quantity'])) {
                $error[] = '请填写物料数量';
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

                    $id = $this->materiel_model->insert(array(
                        'title' => $post['title'],
                        'position' => $post['position'],
                        'constructor_image' => $date_dir . $updata['file_name'],
                        'quantity' => $post['quantity'],
                        'constructor_id' => $this->_user['id'],
                        'supervisor_id' => $post['supervisor_id'],
                        'create_time' => $c_time,
                        'update_time' => $c_time,
                    ));

                    redirect('materiel/detail/' . $id);
                }
            }
        }
        if (!empty($error)) {
            $data['error'] = implode('<br>', $error);
        }
        $supervisor_list = $this->weixin_model->role_list(array('company.type' => 2));
        $data['supervisor_options'] = get_select_options($supervisor_list,'id','nickname',$data['materiel']['supervisor_id']);
        $this->view('materiel', $data);
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
        $date_dir = date('Ym') . '/';
        $base_path = $this->config->item('materiel_image_path') . $date_dir;

        if ($id) {
            $data['materiel'] = $materiel_ori = $this->materiel_model->get_one(array('id' => $id));
            if (empty($materiel_ori) || $materiel_ori['constructor_id'] != $this->_user['id']) {
                show_404();
            }
            $data['constructor_image'] = site_url($base_path . $materiel_ori['constructor_image']);
            $data['supervisor_image'] = site_url($base_path . $materiel_ori['supervisor_image']);
        }
        $error = array();

        $post = $this->input->post();
        if (!empty($post)) {
            $data['materiel'] = array_merge($data['materiel'], $post);

            if (empty($post['title'])) {
                $error[] = '请填写物料名称';
            }
            if (empty($post['position'])) {
                $error[] = '请填写使用部位';
            }
            if (empty($_FILES['up_constructor_image']) || $_FILES['up_constructor_image']['error'] == 4) {
                $error[] = '请上传物料相片';
            }
            if (empty($post['quantity'])) {
                $error[] = '请填写物料数量';
            }
            if ($post['supervisor_id'] == -1) {
                $error[] = '请填写监理单位';
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
                    $data['constructor_image'] = site_url($base_path . $updata['file_name']);
                    if ($id && $materiel_ori['constructor_image'] && file_exists($upConfig['upload_path'] . $materiel_ori['constructor_image'])) {
                        unlink($upConfig['upload_path'] . $materiel_ori['constructor_image']);
                    }

                    if ($id) {
                        $this->materiel_model->update(array(
                            'title' => $post['title'],
                            'position' => $post['position'],
                            'constructor_image' => $date_dir . $updata['file_name'],
                            'quantity' => $post['quantity'],
                            'supervisor_id' => $post['supervisor_id'],
                            'update_time' => $c_time,
                        ), array('id' => $id));
                    } else {
                        $data['materiel']['id'] = $this->materiel_model->insert(array(
                            'title' => $post['title'],
                            'position' => $post['position'],
                            'constructor_image' => $date_dir . $updata['file_name'],
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
        if (!empty($error)) {
            $data['error'] = implode('<br>', $error);
        }
        $supervisor_list = $this->weixin_model->role_list(array('company.type' => 2));
        $data['supervisor_options'] = get_select_options($supervisor_list,'id','nickname',$data['materiel']['supervisor_id']);
        $this->view('materiel', $data);
    }
}
