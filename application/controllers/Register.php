<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends Auth {

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
			$input = (object) $this->register->getDefaultValues();
		} else {
			$input = (object) $this->input->post(null, true);
		}

		if (!$this->register->validate()) {
			$data['title']	= 'Register';
			$data['input']	= $input;
			$data['page']	= 'auth/register';

			$this->view($data);
			return;
		}

		if ($this->register->run($input)) {
			$this->session->set_flashdata('success', 'Berhasil melakukan registrasi');
			redirect(base_url());
		} else {
			$this->session->set_flashdata('error', 'Oops ! Terjadi suatu kesalahan !');
			redirect(base_url('/register'));
		}
	}

}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */