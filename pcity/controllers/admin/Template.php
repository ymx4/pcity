<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *
 * Template
 */
class Template extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('template_model');
    }

    public function index()
    {
        $offset = intval($this->input->get('o'));
        $data   = array(
            'template_list' => array(),
            'categories' => self::$template_categories,
        );
        $where = array('title <>' => '');
        $category = $this->input->get('category');
        if (!empty($category) && $category != -1) {
            $where['category'] = $category;
        }

        $count = $this->template_model->get_count($where);
        if ($count) {
            $limit = $this->config->item('page_size');
            $data['template_list'] = $this->template_model->get_list($where, $limit, $offset);

            $this->load->library('pagination');

            $this->pagination->initialize_admin(array(
                'base_url'    => preg_replace('/(.*)(\?|&)o=.*/', '$1', site_url($this->input->server('REQUEST_URI'))),
                'total_rows'  => $count,
                'per_page'    => $limit,
                'page_query_string'    => true,
                'query_string_segment' => 'o'
            ));
        }

        $this->load->view('admin/template_list', $data);
    }

    public function edit($cid, $id = 0)
    {
        $data = array(
            'load_upload' => true,
            'status' => 0,
            'cid' => $cid,
        );
        if (!isset(self::$template_categories[$cid])) {
            redirect('/admin/template');
        } else {
            $data['fields'] = array();
            foreach (self::$template_categories[$cid]['fields'] as $key) {
                $data['fields'][$key] = array(
                    'label' => self::$template_fields[$key],
                );
            }
            $data['all_fields'] = self::$template_fields;
        }

        if ($id) {
            $data['template'] = $this->template_model->get_one(array('id' => $id));
            if (empty($data['template'])) {
                show_404();
            }
        } else {
            $data['template'] = $this->template_model->get_one(array('title' => ''));
            if (!empty($data['template'])) {
                $id = $data['template']['id'];
            } else {
                $data['template'] = array(
                    'title' => '',
                    'image' => '',
                );
            }
        }
        if (!empty($data['template']['image'])) {
            $data['template']['image'] = json_decode($data['template']['image'], true);

            foreach ($data['template']['image'] as $key => $value) {
                $data['template']['image'][$key] = array(
                    'name' => $value,
                    'url' => site_url($this->config->item('template_image_path') . $value),
                );
            }
        }
        $data['id'] = $id;

        $post = $this->input->post();
        if (!empty($post)) {
            $data['template'] = array_merge($data['template'], $post);

            if (empty($post['title'])) {
                $data['error'] = '请填写标题';
            }

            if (empty($data['error'])) {
                $post['category'] = $cid;
                if ($id) {
                    $post['update_time'] = time();
                    $this->template_model->update($post, array('id' => $id));
                } else {
                    $post['create_time'] = $post['update_time'] = time();
                    $data['template']['id'] = $this->template_model->insert($post);
                }

                $data['status'] = 1;
            }
        }
        $this->load->view('admin/template', $data);
    }

    public function delete($id = 0)
    {
        $data = array('code' => 0);
        $template = $this->template_model->get_one(array('id' => $id));
        if (!empty($template)) {
            $this->template_model->delete(array('id' => $id));
            $data['code'] = 1;
        }
        echo json_encode($data);
    }

    public function do_upload($id = 0) {
        $template = $this->template_model->find($id);
        if (empty($template)) {
            $template = $this->template_model->get_one(array('title' => ''));
        }

        $this->load->library('upload');

        $upConfig = array(
            'file_name'     => time(),
            'upload_path'   => FCPATH . $this->config->item('template_image_path'),
            'allowed_types' => 'png|jpg|jpeg',
            'max_size'      => $this->config->item('size_limit'),
        );

        $this->upload->initialize($upConfig);
        $uploaded = $this->upload->do_upload('up_image');

        if (!$uploaded) {
            $this->response('上传图片出错：' . $this->upload->display_errors());
        } else {
            $imagedata = $this->upload->data();
            if (empty($template)) {
                $id = $this->template_model->insert(array(
                    'image' => json_encode(array($imagedata['file_name'])),
                ));
            } else {
                if (empty($template['image'])) {
                    $template_image = array();
                } else {
                    $template_image = json_decode($template['image'], true);
                }
                $template_image[] = $imagedata['file_name'];
                $this->template_model->update(array(
                    'image' => json_encode($template_image),
                ), array('id' => $template['id']));
                $id = $template['id'];
            }
            $this->response(array(
                'id' => $id,
                'name' => $imagedata['file_name'],
                'url' => site_url($this->config->item('template_image_path') . $imagedata['file_name']),
            ));
        }
    }

    public function delimg() {
        $id = $this->input->post('id');
        $imgname = $this->input->post('imgname');
        $template = $this->template_model->find($id);
        if (empty($template)) {
            $this->response('参数错误');
        }
        if (!empty($template['image'])) {
            $imglist = json_decode($template['image'], true);
            $key = array_search($imgname, $imglist);
            if ($key !== false) {
                unlink(FCPATH . $this->config->item('template_image_path') . $imgname);
                unset($imglist[$key]);
                $this->template_model->update(array('image' => json_encode($imglist)), array('id' => $id));
            }
        }
        $this->response();
    }
}
