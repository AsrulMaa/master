<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends Backend {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('menu_access');
		$this->load->model('Menu_model', 'menu');
	}

	public function index()
	{
      
		
	
		$data['title']	= 'Dashboard';
		$data['input']	= '';
		$data['page']	= 'dashboard';
		
		$this->view($data);
	}

	public function createTree($array, $parent)
	{
		$return = [];
		foreach ($array as $row) {
			foreach ($row as $key => $value) {
				if ($value['parent'] == $value['id']) {
				 	print_r($value);
				}
			}	 
		}
		die();
		return $return;
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */