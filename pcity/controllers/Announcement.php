<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Announcement extends Front_Controller {

	public function index()
	{
		$this->view('announcement');
	}
}
