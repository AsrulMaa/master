<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menus extends Backend {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('menu_access');
		$this->load->model('menu_model', 'menu');
	}

	public function index($page = null)
	{
      
		
		$data['title']	= 'Menu List';
		$data['input']	= '';
		$data['page']	= 'menu/list_menu';
		
		$this->view($data);
	}

	public function create()
	{
		
		$data['title']	= 'Tambah : Menu';
		$data['input']	= '';
		$data['page']	= 'menu/add_menu';
		
		$this->view($data);
	}

	public function save_data()
	{
		if (!$_POST) {
			$input = (object) $this->menu->getDefaultValues();
		} else {
			$input = (object) $this->input->post(null, true);
		}


		if ($this->menu->validate($input->category_id)) {
			$save_user = $this->menu->run($input);
			if ($save_user) {
				if ($this->input->post('save_type') == 'stay') {
						$response['success'] = true;
						$response['message'] = 'Berhasil menyimpan data, klik link untuk mengedit menu'.
							anchor('admin/menus/edit/' . $save_user, ' Edit User'). ' atau klik'.
							anchor('admin/menus', ' kemabali ke list'). ' untuk melihat seluruh data';
				} else {
					// set_message('Berhasil menyimoan data '.anchor('admin/menu/edit/' . $save_user, 'Edit User'), 'success');
	        		$response['success'] = true;
					$response['redirect'] = site_url('admin/menus');
				} 

			} else {
				$response['success'] = false;
				$response['message'] = 'gagal menyimpan data menu';
			}
		}	else {
			$response['success'] = false;
			$response['message'] = validation_errors();
		}



		return $this->response($response);
	}


}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */