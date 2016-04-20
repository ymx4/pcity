<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wx extends CI_Controller {

    private static $WX_UPDATE_DURATION = 604800;// 1 week for update user info

    public function __construct(){
        parent::__construct();
        $this->load->library('weixin');
        $this->load->library('session', 'user_lib');
    }

    private function _showpage($redirect_type)
    {
        switch ($redirect_type) {
            case 1:
            
            default:
                $this->view('bind');
                break;
        }
        exit;
    }

    /**
    * base weixin oauth , no confirmation for user in weixin client
    */
    public function oauth2_base($redirect_type = 0) {
        $code = $this->input->get('code');
        $state = $this->input->get('state');

        if (empty($code)) {
            show_error("无效令牌", 500);
        }

        if (empty($state)) {
            show_error("无效State", 500);
        }

        $token = $this->weixin->get_oauth2_access_token($code);

        if ($token !== false) {
            $userinfo = $this->user_lib->login($token['openid']);

            if ($userinfo != false){
                if (($userinfo['update_time'] + self::$WX_UPDATE_DURATION ) > time()) {
                    $this->_showpage($redirect_type);
                }
            }
        }

        $url = $this->weixin->get_oauth_url(site_url('wx/oauth2/' . $redirect_type), $state, true);
        //echo $url;
        header("Location: $url", 301);
    }

    /**
    * full weixin oauth , popup confirmation dialog for user in weixin client
    * get user info 
    */
    public function oauth2($redirect_type = 0) {
        $code = $this->input->get('code');
        $state = $this->input->get('state');

        if (empty($code)) {
            show_error("无效令牌", 500);
        }

        if (empty($state)) {
            show_error("无效State", 500);
        }

        $token = $this->weixin->get_oauth2_access_token($code);

        if ($token !== false) {
            $userinfo = $this->weixin->get_userinfo($token['access_token'], $token['openid']);
            if ($userinfo != false) {
                $this->user_lib->update_user($userinfo);
                $this->_showpage($redirect_type);
            }
        }
        show_error('微信授权失败！');
    }
}