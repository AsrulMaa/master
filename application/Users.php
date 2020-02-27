<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends Backend {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($page = null)
	{
		$data['title']	= 'Users';
		$data['page']	= 'users/index';
		$data['content']	= $this->users->select(
				[
					'users.id', 'users.username', 'users.email', 'users.fullname', 'users.is_active', 'users.avatar', 'users.token', 'users.created_at', 'role.role', 'users.alpa'
				])
			->join('role','left')
			->paginate($page)
			->get();
		$data['total_rows']	= $this->users->count();
		$data['keterangan']	= 'Menampilkan 1 hingga '. count($data['content']). ' dari '. $data['total_rows']. ' data (difilter dari 1 entri)';
		$data['pagination']	= $this->users->makePagination(base_url('admin/users'), 3, $data['total_rows']);
		$this->set($data);
		$this->view($data);
	}

	public function request()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
			$data	= array();
			$datas	= $this->users->select(
				[
					'users.id', 'users.username', 'users.email', 'users.fullname', 'users.is_active', 'users.avatar', 'users.token', 'users.created_at', 'role.role'
				])
			->join('role','left')
			->search_item(
				['users.username', 'users.email', 'users.fullname', 'role.role'
				])
			->column_order([null,'username', 'email', 'fullname', 'role', null])
			->datatables();

			$no = $_POST['start'];
				foreach ($datas as $r) {
					$no++;
					$row = array();
					$row[] = '<input type="checkbox" class="flat-red check" name="id[]" value="'.$r->id.'">';
					$row[] = $r->username;
					$row[] = $r->email;
					$row[] = '<span class="badge bg-green">'.anchor('#', $r->role, ['style' => 'color:#fff; text-decoration:none']).'</span>';
					//add html for action
					$row[] = '
					<div class="text-center"><a href="'.base_url("admin/users/edit/$r->id").'" class="btn btn-sm btn-warning edit" title="Edit" id="edit" data-id = "'.$r->id.'"><i class="fa fa-pencil"></i>Edit </a>
					<button type="button" class="btn btn-sm btn-danger delete" title="Delete" id="delete" data-id = "'.$r->id.'"><i class="fa fa-trash"></i>Delete</button></div>';
					$data[] = $row;
				}

				$json_data = [
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->users->count(),
					"recordsFiltered" => $this->users->count_filtered(),
					'data' => $data
				];

			return $this->response($json_data, 200);
		}
	}

	public function create()
	{
		$data['title']	= 'Add User';
		$data['page']	= 'users/form_user';
		
		$this->set($data);
		$this->view($data);
	}

	public function add_save()
	{
		if (!$_POST) {
			$input = (object) $this->users->getDefaultValues();
		} else {
			$input = (object) $this->input->post(null, true);
		}

		if ($this->users->validate()) {
			
			$save_user = $this->users->run($input);
			if ($save_user) {
				if ($this->input->post('save_type') == 'stay') {
						$response['success'] = true;
						$response['message'] = 'Berhasil menyimpan data, klik link untuk mengedit users'.
							anchor('admin/users/edit/' . $save_user, ' Edit User'). ' atau klik'.
							anchor('admin/users', ' kemabali ke list'). ' untuk melihat seluruh data';
				} else {
					// set_message('Berhasil menyimoan data '.anchor('admin/users/edit/' . $save_user, 'Edit User'), 'success');
	        		$response['success'] = true;
					$response['redirect'] = site_url('admin/users');
				} 

			} else {
				$response['success'] = false;
				$response['message'] = 'gagal menyimpan data users';
			}
		}	else {
			$response['success'] = false;
			$response['message'] = validation_errors();
		}

		return $this->response($response);
	}

	public function edit($id)
	{
		$data['title']		= 'Edit User';
		$data['page']		= 'users/form_edit';
		$data['input']		= $this->users->where('id', $id)->first();

		$this->set($data);
		$this->view($data);
	}

	public function edit_save($profile = null)
	{
		if (!$_POST) {
			$input = (object) $this->users->getDefaultValues();
		} else {
			$input = (object) $this->input->post(null, true);
		}
		$this->load->library('form_validation');
		$validationRules = [
			[
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'trim|required',
			],
			[
				'field' => 'email',
				'label' => 'Email',
				'rules' => 'trim|required|valid_email',
			],
			[
				'field' => 'role',
				'label' => 'Role',
				'rules' => 'required',
			],
		];
		$this->form_validation->set_rules($validationRules);
		if ($this->form_validation->run()) {
			
			$save_user = $this->users->run($input,'update');
			if ($save_user) {
				if ($this->input->post('save_type') == 'stay') {
						$response['success'] = true;
						$response['message'] = 'Berhasil mengupdate data, klik link untuk mengedit users'.
							anchor('admin/users/edit/' . $save_user, ' Edit User'). ' atau klik'.
							anchor('admin/users', ' kemabali ke list'). ' untuk melihat seluruh data';
				} else {
					// set_message('Berhasil menyimpan data '.anchor('admin/users/edit/' . $save_user, 'Edit User'), 'success');
					if ($profile == null) {
						$response['success'] = true;
						$response['redirect'] = site_url('admin/users');
					} else {
		        		$response['success'] = true;
						$response['redirect'] = site_url('admin/users/profile');
					}
				} 

			} else {
				$response['success'] = false;
				$response['message'] = 'gagal menyimpan data users';
			}
		}	else {
			$response['success'] = false;
			$response['message'] = validation_errors();
		}

		return $this->response($response);
	}

	/**
	* delete users
	*
	* @var $id String
	*/
	public function delete()
	{

		$id = $this->input->post(null, true);
		$remove = false;
		if (is_array($id['delete_id'])) {
			foreach ($id['delete_id'] as $i) {
				$remove = $this->_remove($i);
				//$response['success'] = $id['delete_id'];
				if ($remove) {
					$response['success'] = true;
					$response['redirect'] = site_url('admin/users/index');
					set_message('Data user berhasil di hapus', 'success');
				} else {
					$response['success'] = false;
					$response['message'] = 'Maaf gagal menghapus data0';
				}
			}
		} else if (! $this->users->where('id', $id['delete_id'])->first()) {
			$response['success'] = false;
			$response['message'] = 'Maaf data tidak ditemukan1';
		} else{
			$image = $this->users->where('id', $id['delete_id'])->first()->avatar;
			$delete = $this->users->where('id', $id['delete_id'])->delete();
			if ($delete) {
				$this->deleteImage($image);
				$response['success'] = true;
				$response['redirect'] = site_url('admin/users/index');
				set_message('Data user berhasil di hapus', 'success');
			} else {
				$response['success'] = false;
				$response['message'] = 'Maaf gagal menghapus data2';
			}
		}
		
		return $this->response($response);
	}

	

	/**
	* delete users
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$image = $this->users->find($id)->avatar;
		$this->load->helper('file');
		$delete_file = '';
		$path = FCPATH . 'uploads/user/'.$image;
		if (file_exists($path)) {
			if ($image != 'default.png') {
				$delete_file = unlink($path);
				$delete_files = delete_files($path);
			}
		}
			
		if ($delete_file) {
			return $this->users->where('id', $id)->delete();
			
		}	

		
	}



	public function deleteImage($image)
	{
		if (!empty($image)) {
			$this->load->helper('file');
			$delete_file = '';
			$path = FCPATH . 'uploads/user/'.$image;
			if (file_exists($path)) {
				if ($image != 'default.png') {
					$delete_file = unlink($path);
				}
			}
				
			if ($delete_file) {
				return true;
			}	
		}
	}



	/**
	* Upload Image User
	* 
	* @return JSON
	*/
	public function upload_avatar_file()
	{
		// if (!$this->is_allowed('user_add', false)) {
		// 	return $this->response([
		// 		'success' => false,
		// 		'message' => cclang('sorry_you_do_not_have_permission_to_access')
		// 		]);
		// }

		$uuid = $this->input->post('qquuid');

		mkdir(FCPATH . '/uploads/tmp/' . $uuid);

		$config = [
			'upload_path' 		=> './uploads/tmp/' . $uuid . '/',
			'allowed_types' 	=> 'png|jpeg|jpg|gif',
			'max_size'  		=> '1000'
		];
		
		$this->load->library('upload', $config);
		$this->load->helper('file');

		if ( ! $this->upload->do_upload('qqfile')){
			$result = [
				'success' 	=> false,
				'error' 	=>  $this->upload->display_errors()
			];

    		return $this->response($result);
		}
		else{
			$upload_data = $this->upload->data();

			$result = [
				'uploadName' 	=> $upload_data['file_name'],
				'success' 		=> true,
			];

    		return $this->response($result);
		}
	}

	/**
	* Delete Image User
	* 
	* @return JSON
	*/
	public function delete_avatar_file($uuid)
	{
		// if (!$this->is_allowed('user_delete', false)) {
		// 	return $this->response([
		// 		'success' => false,
		// 		'message' => cclang('sorry_you_do_not_have_permission_to_access')
		// 		]);
		// }

		if (!empty($uuid)) {
			$this->load->helper('file');

			$delete_by = $this->input->get('by');
			$delete_file = false;

			if ($delete_by == 'id') {
				$user = $this->users->where('id', $uuid)->first();
				$path = FCPATH . 'uploads/user/'.$user->avatar;
				if ($user->avatar != 'default.png') {
					if (isset($uuid)) {
						if (is_file($path)) {
							$delete_file = unlink($path);
							$this->users->where('id', $uuid)->update(['avatar' => '']);
						}
					}	
				}
				

				
			} else {
				$path = FCPATH . '/uploads/tmp/' . $uuid . '/';
				$delete_file = delete_files($path, true);
			}

			if (isset($uuid)) {
				if (is_dir($path)) {
					rmdir($path);
				}
			}

			if (!$delete_file) {
				$result = [
					'error' =>  'Error delete file'
				];

	    		return $this->response($result);
			} else {
				$result = [
					'success' => true,
				];

	    		return $this->response($result);
			}
		}
	}

	/**
	* Get Image User
	* 
	* @return JSON
	*/
	public function get_avatar_file($id)
	{
		// if (!$this->is_allowed('user_update', false)) {
		// 	return $this->response([
		// 		'success' => false,
		// 		'message' => cclang('sorry_you_do_not_have_permission_to_access')
		// 		]);
		// }

		$this->load->helper('file');
		
		$user = $this->users->where('id', $id)->first();

		if (!$user) {
			$result = [
				'error' =>  'Error getting file'
			];

    		return $this->response($result);
		} else {
			if (!empty($user->avatar)) {
				$result[] = [
					'success' 				=> true,
					'thumbnailUrl' 			=> base_url('uploads/user/'.$user->avatar),
					'id' 					=> 0,
					'name' 					=> $user->avatar,
					'uuid' 					=> $user->id,
					'deleteFileEndpoint' 	=> base_url('admin/users/delete_avatar_file'),
					'deleteFileParams'		=> ['by' => 'id']
				];

	    		return $this->response($result);
			}
		} 
	}


	public function profile()
	{
		$id = $this->session->userdata('id');
		if (!$_POST) {
			$input = (object) $this->users->getDefaultValues();
		} else {
			$input = (object) $this->input->post(null, true);
		}
		$data['input'] = ['password' => hashEncrypt($this->input->post('password'))];	

		$validationRules = [
			[
				'field'	=> 'password',
				'label'	=> 'Password',
				'rules'	=> 'required|min_length[5]',
				'errors'	=> array('required' => ' %s harus di isi', 'min_length' => '%s harus minimal 5 karakter')
			],

			[
				'field'	=> 'password_confirmation',
				'label'	=> 'Konfirmasi Password',
				'rules'	=> 'required|matches[password]',
				'errors'	=> array('required' => '%s harus di isi', 'matches' => 'Konfirmasi password tidak benar / tidak match')
			],
		];
		$this->load->library('form_validation');
		$validate = $this->form_validation->set_rules($validationRules);

		if (!$validate->run()) {
			$data['title']	= 'Profile';
			$data['page']	= 'users/profile';
			$data['form_action'] = base_url('admin/users/profile/').$this->session->userdata('id');
			$data['profile'] = $this->users->select(
					[
						'users.id', 'users.username', 'users.email', 'users.fullname', 'users.is_active', 'users.avatar', 'users.token', 'users.created_at', 'users.update_at', 'users.last_login','role.role'
					])
				->join('role','left')
				->where('users.id', $id)
				->first();
			$this->set($data);
			$this->view($data);
			return;
		}		

  		if ($this->users->where('id', $id)->update($data['input'])) {
			$this->session->set_flashdata('success', 'data berhasil di perbaharui');
		} else {
			$this->session->set_flashdata('error', 'gagal mengupdate data');
		}

		redirect(base_url('admin/users/profile'));
		
	}

	public function edit_profile()
	{
		$id = $this->session->userdata('id');
		$data['title']	= 'Profile';
		$data['page']	= 'users/edit_profile';
		$data['input'] = $this->users->select(
				[
					'users.id', 'users.username', 'users.email', 'users.fullname', 'users.is_active', 'users.avatar', 'users.token', 'users.created_at', 'users.update_at', 'users.last_login','role.role','users.id_role'
				])
			->join('role','left')
			->where('users.id', $id)
			->first();

  		
		$this->set($data);
		$this->view($data);
	}

	public function set_status()
	{

		$status = $this->input->post('status');
		$id = $this->input->post('id');
		$update_status = $this->users->where('id', $id)->update([
			'is_active' => $status == 'inactive' ? 0 : 1
		]);
		
		if ($update_status) {
			$this->response = [
				'success' => true,
				'message' => 'User status updated',
			];
		} else {
			$this->response = [
				'success' => false,
				'message' => 'Data not change.'
			];
		}
		return $this->response($this->response);
	}


	public function change($cat, $id)
	{
		@$i = $this->users->where('id', $id)->first()->$cat;	
		if (@$i) {
			if ($cat == 'alpa') {
			$data = ['alpa' => $i+1];
			} else if ($cat == 'izin') {
				$data = ['izin' => $i+1];
			} else if ($cat == 'sakit'){
				$data = ['sakit' => $i+1];
			} else {
				echo "error";
			}
		} else {
			echo "error";
		}
		
		$update = $this->users->where('id', $id)->update($data);
		if ($update) {
			
			redirect('admin/users');
		} else {
			redirect('admin/users');
			
		}
	}



}





/* End of file Users.php */
/* Location: ./application/controllers/Users.php */