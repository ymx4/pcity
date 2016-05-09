<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_lib
{
    public $CI;

    public function __construct($adapter = false)
    {
        $this->CI =& get_instance();
        $this->CI->load->database();

        $this->CI->load->model(array(
            'weixin_model'
        ));
    }

    public function is_logged_in()
    {
        $user = $this->CI->session->userdata('user_auth');
        return empty($user) ? false : true;
    }

    public function login($openid)
    {
        $user = $this->CI->weixin_model->get_user_by_openid($openid);
        if (!empty($user) && !empty($user['nickname'])) {
            $user['auth'] = $this->get_user_auth($user['id']);
            $this->CI->session->set_userdata(array(
                'user_auth' => $user
            ));
            return $user;
        } else {
            return false;
        }
    }

    public function update_user($userinfo)
    {
        $user = $this->CI->weixin_model->update_user($userinfo);
        $user['auth'] = $this->get_user_auth($user['id']);
        $this->CI->session->set_userdata(array(
            'user_auth' => $user
        ));
        return $user;
    }

    public function get_user_auth($uid)
    {
        $this->CI->load->model('user_auth_model');
        $auth = $this->CI->user_auth_model->find($uid, 'user_id');
        if (!empty($auth) && !empty($auth['auth'])) {
            return json_decode($auth['auth'], true);
        }
        return false;
    }

    public function logout()
    {
        $this->CI->session->unset_userdata('user_auth');
        $this->CI->session->sess_destroy();
    }

    public function get_current_user($key = '')
    {
        if (!$this->is_logged_in()) {
            return null;
        }

        $user = $this->CI->session->userdata('user_auth');

        if (!empty($key)) {
            $result = isset($user[$key]) ? $user[$key] : null;
        } else {
            $result = $user;
        }

        return $result;
    }
}
