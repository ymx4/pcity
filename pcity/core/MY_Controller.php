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
    protected static $announcement_type = array(
        1 => '公司文件',
        2 => '图纸变更',
        3 => '其他文件',
    );
    protected static $template_categories = array(
        1 => '工艺样板',
        2 => '物料封样',
        3 => '综合砌筑样板',
        4 => '交房样板',
    );

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

    }//end __construct()

    public function response($data = '', $extra = false)
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
            if ($extra) {
                echo json_encode(array_merge(array(
                    'code' => 1,
                    'data' => $data,
                ), $extra));
            } else {
                echo json_encode(array(
                    'code' => 1,
                    'data' => $data,
                ));
            }
            exit;
        }
    }

}//end Base_Controller

class Wx_Controller extends Base_Controller
{
    private $title = '孔雀城';
    private $state = '99a0e32d0d214d53984b13a24ad9df12';

    public function __construct()
    {
        parent::__construct();
        //load libraries
        $this->load->library(array('session','weixin','user_lib'));
        $this->session->set_userdata(array(
            'user_auth' => array('id' => 1, 'nickname' => 'Hypnos'),
        ));//TODO
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
            $vars['ptitle'] = $this->title;
            $vars['announcement_type'] = self::$announcement_type;
            $vars['template_categories'] = self::$template_categories;
            $this->load->view($view, $vars);
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
