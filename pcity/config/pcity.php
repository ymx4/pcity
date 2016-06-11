<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
date_default_timezone_set('Asia/ShangHai');
$config['size_limit'] = intval(ini_get('upload_max_filesize')) * 1024;
$config['page_size'] = 10;

$config['template_image_path'] = 'uploads/images/template/';
$config['announcement_file_path'] = 'uploads/files/announcement/';
$config['materiel_image_path'] = 'uploads/images/materiel/';
$config['scene_image_path'] = 'uploads/images/scene/';
$config['property_image_path'] = 'uploads/images/property/';