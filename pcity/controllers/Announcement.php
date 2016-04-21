<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement extends Wx_Controller {

	public function index()
	{
		$this->load->view('demo');
	}
}
