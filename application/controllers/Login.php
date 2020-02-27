<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Auth {

	public function __construct()
	{
		parent::__construct();
		$is_login = $this->session->userdata('is_login');

		if ($is_login) {
			redirect(base_url('admin/dashboard'));
			return;
		}
	}

	public function index()
	{
		if (!$_POST) {
			$input = (object) $this->login->getDefaultValues();
		} else {
			$input = (object) $this->input->post(null, true);
		}
		$data['token'] = $this->security->get_csrf_hash();
		if (!$this->login->validate()) {
			$data['title']	= 'Login';
			$data['input']	= $input;
			$data['page']	= 'auth/login';
			

			$this->set('title', 'Login Panel');
			$this->set('title', 'Login Panel');
			$this->view($data);
			return;
		}

		if ($this->login->run($input)) {
			$this->session->set_flashdata('success', 'Berhasil melakukan Login');
			redirect(base_url('admin/dashboard'));
		} else {
			$this->session->set_flashdata('error', 'Oops ! Email / Password salah');
			redirect(base_url('login'));
		}
	}

	public function in()
	{
		if ($this->login->run($input)) {
			$this->session->set_flashdata('success', 'Berhasil melakukan Login');
			redirect(base_url('admin/dashboard'));
		} else {
			$this->session->set_flashdata('error', 'Oops ! Email / Password salah');
			redirect(base_url('login'));
		} 
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */