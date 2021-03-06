<?php

/**
 * The base controller which is used by the Front and the Admin controllers
 */
class Base_Controller extends CI_Controller
{
    protected static $company_type = array(
        1 => '施工单位',
        2 => '监理单位',
        3 => '建设单位',
        4 => '物业公司',
        // 5 => '地产公司',
    );
    protected static $group_role = array(
        1 => '业主',
        2 => '施工单位',
        3 => '监理单位',
        4 => '建设单位',
    );
    protected static $auth_list = array(
        'admin' => '管理员',
        'task/add' => '发布任务',
    );
    protected static $announcement_type = array(
        1 => '公司文件',
        2 => '图纸变更',
        3 => '其他文件',
    );
    protected static $template_fields = array(
        'process_requirement' => '工艺要求',
        'acceptance_criteria' => '验收标准',
        'manage_level' => '管控等级',
        'location' => '样板位置',
        'disclosure_criteria' => '交房标准',
        'materiel_name' => '物料名称',
        'materiel_mfr' => '生产厂家',
        'materiel_specificatio' => '材料规格',
        'materiel_range' => '使用范围',
        'host' => '主持人',
        'disclosure' => '交底内容',
        'attendee' => '参加人员',
        'adjustment' => '调整内容',
    );
    protected static $template_categories = array(
        1 => array(
            'name' => '工艺样板',
            'fields' => array(
                'process_requirement', 'acceptance_criteria', 'manage_level', 'location'
            ),
        ),
        2 => array(
            'name' => '物料封样',
            'fields' => array(
                'materiel_name', 'materiel_mfr', 'materiel_specificatio', 'materiel_range', 'acceptance_criteria', 'location'
            ),
        ),
        3 => array(
            'name' => '综合砌筑样板',
            'fields' => array(
                'disclosure_criteria', 'adjustment', 'location'
            ),
        ),
        4 => array(
            'name' => '交房样板',
            'fields' => array(
                'disclosure_criteria', 'location'
            ),
        ),
        5 => array(
            'name' => '样板交底',
            'fields' => array(
                'host', 'disclosure', 'attendee'
            ),
        ),
    );

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('url');

    }//end __construct()

    public function response($data = '', $extra = false)
    {
        if ($data === '') {
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
    public $_user = null;

    public function __construct()
    {
        parent::__construct();
        //load libraries
        $this->load->library(array('session','weixin','user_lib'));
        $this->wxlogin();
        $this->_user = $this->user_lib->get_current_user();
        $this->checkAuth();
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
            $vars['_user'] = $this->_user;
            $this->load->view($view, $vars);
        }
    }

    public function head_title($title)
    {
        $this->title = $title;
    }

    private function checkAuth()
    {
        $auth = $this->_user['auth'];
        $pageAuth = $this->router->class . '/' . $this->router->method;
        if (in_array($pageAuth, array_keys(self::$auth_list)) && (!$auth || (!in_array($pageAuth, $auth) && !in_array('admin', $auth)))) {
            show_error('无权限');
        }
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
