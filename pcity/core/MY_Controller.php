<?php

/**
 * The base controller which is used by the Front and the Admin controllers
 */
class Base_Controller extends CI_Controller
{
    protected static $group_role = array(
        1 => '业主',
        2 => '施工方',
        3 => '甲方',
    );

    public function __construct()
    {

        parent::__construct();

        $this->load->helper('url');

    }//end __construct()

}//end Base_Controller

class Wx_Controller extends Base_Controller
{
    private $meta = array();
    private $title = '';
    private $state = '99a0e32d0d214d53984b13a24ad9df12';

    public function __construct()
    {
        parent::__construct();
        //load libraries
        $this->load->library(array('session','weixin','user_lib'));
        $this->wxlogin();
    }

    public function wxlogin()
    {
        if (!$this->user_lib->is_logged_in()) {
            if ($this->input->get('wx')) {
                $redirect_uri = site_url('wx/oauth2_base');
                redirect($this->weixin->get_oauth2_url($redirect_uri, $this->state));
            }
            show_error('微信授权失败！');
        }
    }

    /**
     * This works exactly like the regular $this->load->view()
     * The difference is it automatically pulls in a header and footer.
     */
    public function view($view, $vars = array(), $string = false)
    {
        if ($string) {
            $result	= $this->load->view($view, $vars, true);
            return $result;
        } else {
            $this->load->view($view, $vars);
        }
    }

    public function head_meta($meta)
    {
        if (isset($meta['name'])) {
            $meta = array($meta);
        }
        foreach ($meta as $row) {
            $row['content'] = strip_tags($row['content']);
            if ('description' == $row['name'] && mb_strlen($row['content']) > 100) {
                $row['content'] = mb_substr($row['content'], 0, 100);
            }
            array_push($this->meta, $row);
        }
    }

    public function head_title($title)
    {
        $this->title = $title;
    }
}

class Admin_Controller extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('auth');
        $this->auth->is_logged_in(uri_string());
        $this->curUser = $this->auth->get_current_user();
        $this->load->vars(array(
            'username' => $this->curUser['username']
        ));
        $this->load->helper('admin');
    }

    public function response($data = '')
    {
        if (empty($data)) {
            echo json_encode(array('code' => 1));
            exit;
        } elseif (is_string($data)) {
            echo json_encode(array(
                'code' => 0,
                'msg' => $data,
            ));
            exit;
        } else {
            echo json_encode(array(
                'code' => 1,
                'data' => $data,
            ));
            exit;
        }
    }
}

// Api_Controller
require(APPPATH.'/libraries/REST_Controller.php');
class Api_Controller extends REST_Controller
{
    public function __construct()
    {

        parent::__construct();

        $this->load->library(array('session','user_lib'));

    }
}
