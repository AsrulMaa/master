<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends Backend {

	public function __construct()
	{
		parent::__construct();
	}

	public function index($page = null)
	{
		$data['title']	= 'Blog';
		$data['page']	= 'blog/index';
		$data['content']	= $this->blog->select(
				[
					'blog_post.id', 'blog_post.title', 'blog_post.slug', 'blog_post.content', 'blog_post.images', 'blog_post.tags', 'blog_post.status', 'blog_post.viewers', 'blog_post.keywords', 'blog_post.publish_at', 'blog_post.created_at', 'blog_post.update_at', 'users.username', 'users.fullname', 'blog_categories.categories'
				])
			->join('users','left')
			->join('blog_categories','left')
			->paginate($page)
			->get();
		$data['total_rows']	= $this->blog->count();
		$data['keterangan']	= 'Menampilkan 1 hingga '. count($data['content']). ' dari '. $data['total_rows']. ' data (difilter dari 1 entri)';
		$data['pagination']	= $this->blog->makePagination(base_url('admin/blog'), 3, $data['total_rows']);
		$this->set($data);
		$this->view($data);
	}

	public function request()
	{
		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) {
			$data	= array();
			$datas	= $this->Blog->select(
				[
					'Blog.id', 'Blog.username', 'Blog.email', 'Blog.fullname', 'Blog.is_active', 'Blog.avatar', 'Blog.token', 'Blog.created_at', 'role.role'
				])
			->join('role','left')
			->search_item(
				['Blog.username', 'Blog.email', 'Blog.fullname', 'role.role'
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
					<div class="text-center"><a href="'.base_url("admin/Blog/edit/$r->id").'" class="btn btn-sm btn-warning edit" title="Edit" id="edit" data-id = "'.$r->id.'"><i class="fa fa-pencil"></i>Edit </a>
					<button type="button" class="btn btn-sm btn-danger delete" title="Delete" id="delete" data-id = "'.$r->id.'"><i class="fa fa-trash"></i>Delete</button></div>';
					$data[] = $row;
				}

				$json_data = [
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->Blog->count(),
					"recordsFiltered" => $this->Blog->count_filtered(),
					'data' => $data
				];

			return $this->response($json_data, 200);
		}
	}

	public function create()
	{
		$data['title']	= 'Add Article';
		$data['page']	= 'Blog/create';
		
		$this->set($data);
		$this->view($data);
	}

	public function add_save()
	{
		if (!$_POST) {
			$input = (object) $this->blog->getDefaultValues();
		} else {
			$input = (object) $this->input->post(null, true);
		}


		if ($this->blog->validate()) {
			$slug = url_title(substr($this->input->post('slug'), 0, 100));
			$save_data = [
				'title' => $this->input->post('title'),
				'slug' => $slug,
				'content' => $this->input->post('content'),
				'tags' => $this->input->post('tags'),
				'id_blog_categories' => $this->input->post('id_blog_categories'),
				'id_users' => user('id'),
				'status' => $this->input->post('status'),
				'created_at' => date('Y-m-d H:i:s'),
			];


			if (!is_dir(FCPATH . '/uploads/blog/')) {
				mkdir(FCPATH . '/uploads/blog/');
			}

			if (count((array) $this->input->post('blog_image_name'))) {
				foreach ((array) $_POST['blog_image_name'] as $idx => $file_name) {
					$blog_image_name_copy = date('YmdHis') . '-' . $file_name;

					rename(FCPATH . 'uploads/tmp/' . $_POST['blog_image_uuid'][$idx] . '/' .  $file_name, 
							FCPATH . 'uploads/blog/' . $blog_image_name_copy);

					$listed_image[] = $blog_image_name_copy;

					if (!is_file(FCPATH . '/uploads/blog/' . $blog_image_name_copy)) {
						echo json_encode([
							'success' => false,
							'message' => 'Error uploading file'
							]);
						exit;
					}
				}

				$save_data['images'] = implode($listed_image, ',');
			}


			$save_blog = $this->blog->create($save_data);
			if ($save_blog) {
				if ($this->input->post('save_type') == 'stay') {
						$response['success'] = true;
						$response['message'] = 'Berhasil menyimpan data, klik link untuk mengedit Blog'.
							anchor('admin/blog/edit/' . $save_blog, ' Edit User'). ' atau klik'.
							anchor('admin/blog', ' kemabali ke list'). ' untuk melihat seluruh data';
				} else {
					// set_message('Berhasil menyimoan data '.anchor('admin/Blog/edit/' . $save_user, 'Edit User'), 'success');
	        		$response['success'] = true;
					$response['redirect'] = site_url('admin/blog');
				} 

			} else {
				$response['success'] = false;
				$response['message'] = 'gagal menyimpan data blog';
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
		$data['page']		= 'Blog/form_edit';
		$data['input']		= $this->Blog->where('id', $id)->first();

		$this->set($data);
		$this->view($data);
	}

	public function edit_save($profile = null)
	{
		if (!$_POST) {
			$input = (object) $this->Blog->getDefaultValues();
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
			
			$save_user = $this->Blog->run($input,'update');
			if ($save_user) {
				if ($this->input->post('save_type') == 'stay') {
						$response['success'] = true;
						$response['message'] = 'Berhasil mengupdate data, klik link untuk mengedit Blog'.
							anchor('admin/Blog/edit/' . $save_user, ' Edit User'). ' atau klik'.
							anchor('admin/Blog', ' kemabali ke list'). ' untuk melihat seluruh data';
				} else {
					// set_message('Berhasil menyimpan data '.anchor('admin/Blog/edit/' . $save_user, 'Edit User'), 'success');
					if ($profile == null) {
						 
						$response['success'] = true;
						$response['redirect'] = site_url('admin/Blog/');
					} else {
		        		$response['success'] = true;
						$response['redirect'] = site_url('admin/Blog/profile');
					}
				} 

			} else {
				$response['success'] = false;
				$response['message'] = 'gagal menyimpan data Blog';
			}
		}	else {
			$response['success'] = false;
			$response['message'] = validation_errors();
		}

		return $this->response($response);
	}

	/**
	* delete Blog
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
					$response['redirect'] = site_url('admin/Blog/index');
					set_message('Data user berhasil di hapus', 'success');
				} else {
					$response['success'] = false;
					$response['message'] = 'Maaf gagal menghapus data';
				}
			}
		} else {
			if (! $this->Blog->where('id', $id['delete_id'])->first()) {
				$response['success'] = false;
				$response['message'] = 'Maaf data tidak ditemukan';
			} else {
				$remove = $this->_remove($id['delete_id']);
				if ($remove) {
					$response['success'] = true;
					$response['redirect'] = site_url('admin/Blog/index');
					set_message('Data user berhasil di hapus', 'success');
				} else {
					$response['success'] = false;
					$response['message'] = 'Maaf gagal menghapus data';
				}
			}				
		}
		
		return $this->response($response);
	}

	

	/**
	* Upload Image Blog	* 
	* @return JSON
	*/
	public function upload_image_file()
	{
		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'blog_post',
			'allowed_types' => 'jpg|jpeg|png',
		]);
	}

	/**
	* Delete Image Blog	* 
	* @return JSON
	*/
	public function delete_image_file($uuid)
	{

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'image', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'blog',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/blog/'
        ]);
	}

	/**
	* Get Image Blog	* 
	* @return JSON
	*/
	public function get_image_file($id)
	{
		if (!$this->is_allowed('blog_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$blog = $this->model_blog->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'image', 
            'table_name'        => 'blog',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/blog/',
            'delete_endpoint'   => 'administrator/blog/delete_image_file'
        ]);
	}




	



}

/* End of file Blog.php */
/* Location: ./application/controllers/Blog.php */

