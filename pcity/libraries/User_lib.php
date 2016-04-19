<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH . '/libraries/Abstract_user_lib.php');

/**
 *
 * User Libraries For Front
 */
class User_lib extends Abstract_user_lib
{
    public $CI;

    public function __construct($adapter = false)
    {
        $this->CI =& get_instance();
        $this->CI->load->database();

        $this->CI->load->model(array(
            'member_model'
        ));
    }

    public function is_logged_in()
    {
        $user = $this->CI->session->userdata('user_auth');
        return empty($user) ? false : true;
    }

    public function login($params)
    {
        $user = $this->CI->member_model->get_one(array(
            'email' => $params['email'],
            'password' => md5($params['password'] . $this->salt),
        ));
        if (!empty($user)) {
            $this->CI->session->set_userdata(array(
                'user_auth' => $user
            ));
            return $user;
        } else {
            return false;
        }
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
