<?php
class Wx extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('weixin');
    }

    public function create_menu()
    {
        if (!$this->input->is_cli_request()) {
            show_404();
        }
        $menu = array(
            'button' => array(array(
                'type' => 'view',
                'name' => '管理系统',
                'url' => 'http://www.pcity.cc/announcement?wx=1',
            ))
        );
        if ($this->weixin->create_menu($menu)) {
            $this->_message();
        } else {
            $this->_message('error');
        }
    }

    private function _message($message = 'success')
    {
        echo $message;
        exit;
    }
}
