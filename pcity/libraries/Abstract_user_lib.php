<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
Abstract class Abstract_user_lib
{
    public $salt = 'oxg0qST&HL';

    public function generate_pwd($pwd)
    {
        return md5($pwd . $this->salt);
    }
}