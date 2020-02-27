<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_model extends MY_Model {

	protected $table = 'users';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function getDefaultValues()
	{
		return [
			'name'		=> '',
			'email'		=> '',
			'password'	=> '',
			'role'		=> '',
			'is_active'	=> '',
		];
	}

	public function getValidationRules()
	{
		$validationRules = [
			[
				'field'	=> 'name',
				'label'	=> 'Nama',
				'rules'	=> 'trim|required',
			],

			[
				'field'	=> 'email',
				'label'	=> 'E-Mail',
				'rules'	=> 'trim|required|valid_email|is_unique[users.email]',
				'errors' => [
					'is_unique' => 'This %s already exists'
				]
			],

			[
				'field'	=> 'password',
				'label'	=> 'Password',
				'rules'	=> 'required|min_length[8]',
			],

			[
				'field'	=> 'password_confirmation',
				'label'	=> 'Konfirmasi Password',
				'rules'	=> 'required|matches[password]',
			],

		];

		return $validationRules;
	}

	public function run($input)
	{
		$data = [
			'username'	=> $input->name,
			'email'	=> strtolower($input->email),
			'password'	=> hashEncrypt($input->password),
			'id_role'	=> 2
		];
		$user = $this->create($data);

		$sess_data = [
			'id'	=> $user,
			'name'	=> $data['name'],
			'email'	=> $data['email'],
			'role'	=> $data['role'],
			'is_login'	=> true
		];

		
		$this->session->set_userdata( $sess_data );
		return true;

	}


}

/* End of file Register_model.php */
/* Location: ./application/models/Register_model.php */