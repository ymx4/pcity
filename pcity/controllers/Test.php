<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller {
	public function create()
	{
		$this->load->model(array('announcement_model', 'template_model'));
		$set = array();
		for ($i = 1; $i < 45; $i++) {
			$set[] = array('title' => $i);
		}
		$this->announcement_model->insert_batch($set);



		// $set = array();
		// for ($i = 1; $i < 45; $i++) {
		// 	$set[] = array('title' => $i);
		// }
		// $this->template_model->insert_batch($set);



		echo 'success';exit;
	}
}