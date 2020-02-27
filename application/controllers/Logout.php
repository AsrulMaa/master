<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$sess_data = ['id','name','email','role','is_login'];
		$this->session->unset_userdata($sess_data);
		$this->session->sess_destroy();
		redirect(base_url());
	}

}

/* End of file Logout.php */
/* Location: ./application/controllers/Logout.php */