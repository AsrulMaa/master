<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends Frontend {

	public function index()
	{
		$data['title'] = 'Landing Page';
		$data['page'] = 'landing/index';
		$this->view($data);
	}
}
