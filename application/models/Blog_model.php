<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog_model extends MY_Model {

	protected $perPage = 5;
	protected $primary_key = 'id';
	protected $table = 'blog_post';
	public function __construct()
	{
		parent::__construct();
		
	}

	public function getDefaultValues()
	{
		return [
			'id'		=> '',
			'username'	=> '',
			'email'	=> '',
			'password'	=> '',
			'password'	=> '',
			'fullname'	=> '',
			'id_role'	=> '',
			'is_active'	=> '',
			'avatar'	=> '',
			'token'	=> '',
			'created_at'	=> '',
		];
	}

	public function getValidationRules()
	{
		$validationRules = [
			[
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'trim|required',
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|valid_email|is_unique[users.email]',
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required',
			],
			[
				'field' => 'role',
				'label' => 'Role',
				'rules' => 'required',
			],
		];
		return $validationRules;
		
	}

	public function run($data, $action = 'input')
	{
		if ($action == 'input') {
			$user_avatar_uuid = $data->user_avatar_uuid;
			$user_avatar_name = $data->user_avatar_name;

			$save_data = [
				'fullname' 	=> $data->fullname,
				'avatar' 		=> 'default.png',
				'created_at'	=> date('Y-m-d H:i:s'),
				'username'	=> $data->username,
				'email'		=> strtolower($data->email),
				'password'	=> hashEncrypt($data->password),
				'id_role'	=> $data->role,
				'is_active'	=> 1,
				'token'		=> '',
			];

			if (!empty($user_avatar_name)) {

				$user_avatar_name_copy = date('YmdHis') . '-' . $user_avatar_name;

				if (!is_dir(FCPATH . '/uploads/user')) {
					mkdir(FCPATH . '/uploads/user');
				}

				@rename(FCPATH . 'uploads/tmp/' . $user_avatar_uuid . '/' . $user_avatar_name, 
						FCPATH . 'uploads/user/' . $user_avatar_name_copy);

				$save_data['avatar'] = $user_avatar_name_copy;
			}

			return $this->create($save_data);
		} else {
		
			if (empty($data->password)) {
				$user_avatar_uuid = $data->user_avatar_uuid;
				$user_avatar_name = $data->user_avatar_name;

				$save_data = [
					'fullname' 	=> $data->fullname,
					'avatar' 		=> 'default.png',
					'username'	=> $data->username,
					'email'		=> strtolower($data->email),
					'id_role'	=> $data->role,
					'is_active'	=> 1,
					'token'		=> '',
					'update_at' => date('Y-m-d H:i:s'),
				];

				if (!empty($user_avatar_name)) {

					$user_avatar_name_copy = date('YmdHis') . '-' . $user_avatar_name;

					if (!is_dir(FCPATH . '/uploads/user')) {
						mkdir(FCPATH . '/uploads/user');
					}

					@rename(FCPATH . 'uploads/tmp/' . $user_avatar_uuid . '/' . $user_avatar_name, 
							FCPATH . 'uploads/user/' . $user_avatar_name_copy);

					$save_data['avatar'] = $user_avatar_name_copy;
				} 
			} else {
				$user_avatar_uuid = $data->user_avatar_uuid;
				$user_avatar_name = $data->user_avatar_name;

				$save_data = [
					'fullname' 	=> $data->fullname,
					'avatar' 		=> 'default.png',
					'username'	=> $data->username,
					'email'		=> strtolower($data->email),
					'password'	=> hashEncrypt($data->password),
					'id_role'	=> $data->role,
					'is_active'	=> 1,
					'token'		=> '',
					'update_at' => date('Y-m-d H:i:s'),
				];

				if (!empty($user_avatar_name)) {

					$user_avatar_name_copy = date('YmdHis') . '-' . $user_avatar_name;

					if (!is_dir(FCPATH . '/uploads/user')) {
						mkdir(FCPATH . '/uploads/user');
					}

					@rename(FCPATH . 'uploads/tmp/' . $user_avatar_uuid . '/' . $user_avatar_name, 
							FCPATH . 'uploads/user/' . $user_avatar_name_copy);

					$save_data['avatar'] = $user_avatar_name_copy;
				}
			}

			return $this->where('id', $data->id)->update($save_data);
		}
	}

	// public function run()
	// {
	// 	$user_avatar_uuid = $this->input->post('user_avatar_uuid');
	// 	$user_avatar_name = $this->input->post('user_avatar_name');

	// 	$save_data = [
	// 		'fullname' 	=> $this->input->post('fullname'),
	// 		'avatar' 		=> 'default.png',
	// 		'created_at'	=> date('Y-m-d H:i:s'),
	// 		'username'	=> $this->input->post('username', TRUE),
	// 		'email'		=> strtolower($this->input->post('email', TRUE)),
	// 		'password'	=> hashEncrypt($this->input->post('password', TRUE)),
	// 		'id_role'	=> $this->input->post('role', TRUE),
	// 		'is_active'	=> 1,
	// 		'token'		=> '',
	// 	];

	// 	if (!empty($user_avatar_name)) {

	// 		$user_avatar_name_copy = date('YmdHis') . '-' . $user_avatar_name;

	// 		if (!is_dir(FCPATH . '/uploads/user')) {
	// 			mkdir(FCPATH . '/uploads/user');
	// 		}

	// 		@rename(FCPATH . 'uploads/tmp/' . $user_avatar_uuid . '/' . $user_avatar_name, 
	// 				FCPATH . 'uploads/user/' . $user_avatar_name_copy);

	// 		$save_data['avatar'] = $user_avatar_name_copy;
	// 	}

	// 	return $this->create($save_data);

	// }

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */