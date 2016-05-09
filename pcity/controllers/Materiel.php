<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Materiel extends Wx_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('materiel_model');
    }

    public function add()
    {
    	
    }
}
