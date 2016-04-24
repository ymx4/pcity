<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends Wx_Controller {
	public function create()
	{
		$this->load->model(array('announcement_model', 'template_model'));
		// $set = array();
		// for ($i = 1; $i < 45; $i++) {
		// 	$set[] = array('title' => $i);
		// }
		// $this->announcement_model->insert_batch($set);



		$set = array();
		for ($i = 1; $i < 45; $i++) {
			$set[] = array(
				'title' => $i,
				'category' => rand(1, 5),
				'image' => '["1461482630.jpg"]',
				'process_requirement' => '测试测试测试测试测试测试测试测试测试测试',
				'acceptance_criteria' => '测试测试测试测试测试测试测试测试测试测试',
				'manage_level' => '测试测试测试测试测试测试测试测试测试测试',
				'location' => '测试测试测试测试测试测试测试测试测试测试',
				'disclosure_criteria' => '测试测试测试测试测试测试测试测试测试测试',
				'materiel_name' => '测试测试测试测试测试测试测试测试测试测试',
				'materiel_mfr' => '测试测试测试测试测试测试测试测试测试测试',
				'materiel_specificatio' => '测试测试测试测试测试测试测试测试测试测试',
				'materiel_range' => '测试测试测试测试测试测试测试测试测试测试',
				'host' => '测试测试测试测试测试测试测试测试测试测试',
				'disclosure' => '测试测试测试测试测试测试测试测试测试测试',
				'attendee' => '测试测试测试测试测试测试测试测试测试测试',
				'adjustment' => '测试测试测试测试测试测试测试测试测试测试',
			);
		}
		$this->template_model->insert_batch($set);



		echo 'success';exit;
	}
}