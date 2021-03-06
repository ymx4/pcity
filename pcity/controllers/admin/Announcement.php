<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Announcement
 */
class Announcement extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('announcement_model');
    }

    public function index()
    {
        $offset = intval($this->input->get('o'));
        $type = $this->input->get('type');
        $data   = array(
            'announcement_list' => array(),
            'announcement_type_list' => self::$announcement_type,
            'ptype' => $type,
        );
        $where = array();
        if (!empty($type) && $type != -1) {
            $where['type'] = $type;
        }

        $count = $this->announcement_model->get_count($where);
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['announcement_list'] = $this->announcement_model->get_list($where, $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/announcement_list', $data);
    }

    public function edit($id = 0)
    {
        $data = array(
            'announcement' => array(
                'title' => '',
                'content' => '',
            ),
            'announcement_type_list' => self::$announcement_type,
            'status' => 0,
        );

        if ($id) {
            $data['announcement'] = $announcementfile = $this->announcement_model->get_one(array('id' => $id));
            if (empty($announcementfile)) {
                show_404();
            }
            $data['announcement_file'] = '/announcement/download/' . $announcementfile['id'];
        }

        $post = $this->input->post();
        if (!empty($post)) {
            $data['announcement'] = array_merge($data['announcement'], $post);

            if (empty($post['title'])) {
                $data['error'] = '请填写标题';
            }
            if (empty($post['type']) || $post['type'] == -1) {
                $data['error'] = '请选择公告类型';
            }

            // if (empty($post['content'])) {
            //     $data['error'] = '请填写内容';
            // }

            if (empty($data['error'])) {
                if ($_FILES['up_file']['error'] != 4) {
                    $this->load->library('upload');

                    if (!is_dir(FCPATH . $this->config->item('announcement_file_path'))) {
                        mkdir(FCPATH . $this->config->item('announcement_file_path'), 0755, true);
                    }
                    $upConfig = array(
                        'upload_path'   => FCPATH . $this->config->item('announcement_file_path'),
                        'allowed_types' => 'doc|docx|pdf|jpg|jpeg|png',
                        'max_size'      => $this->config->item('size_limit'),
                    );
                    if (preg_match('/[^a-zA-Z0-9-_.]/', $_FILES['up_file']['name'])) {
                        $upConfig['file_name'] = time();
                    }

                    $this->upload->initialize($upConfig);
                    $uploaded = $this->upload->do_upload('up_file');

                    if (!$uploaded) {
                        $data['error'] = '上传出错：' . $this->upload->display_errors();
                    } else {
                        $updata = $this->upload->data();
                        $data['announcement'] = array_merge($data['announcement'], $post);

                        $data['announcement']['file_name'] = $post['file_name'] = $updata['file_name'];

                        if (empty($data['error']) && $id && $announcementfile['file_name'] && file_exists($upConfig['upload_path'] . $announcementfile['file_name'])) {
                            unlink($upConfig['upload_path'] . $announcementfile['file_name']);
                        }
                    }
                }
                if ($id) {
                    $post['update_time'] = time();
                    $this->announcement_model->update($post, array('id' => $id));
                } else {
                    $post['create_time'] = $post['update_time'] = time();
                    $data['announcement']['id'] = $id = $this->announcement_model->insert($post);
                }
                if (isset($uploaded) && $uploaded) {
                    $data['announcement_file'] = '/announcement/download/' . $id;
                }

                $data['status'] = 1;
            }
        }
        $this->load->view('admin/announcement', $data);
    }

    public function delete($id = 0)
    {
        $data = array('code' => 0);
        $announcement = $this->announcement_model->get_one(array('id' => $id));
        if (!empty($announcement)) {
            $this->announcement_model->delete(array('id' => $id));
            $data['code'] = 1;
        }
        echo json_encode($data);
    }
}
