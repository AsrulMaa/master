<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends MY_Model {

	protected $table ='menus';
	public function __construct()
	{
		parent::__construct();
		
	}

	public function getDefaultValues()
	{
		return [
			'category_id'		=> '',
			'icon'				=> '',
			'parent'			=> '',
			'label'				=> '',
			'link'				=> '',
			'group'				=> '',
		];
	}

	public function getValidationRules($rules = null)
	{
		if ($rules == 'menu') {
			$validationRules = [
				[
					'field' => 'category_id',
					'label' => 'Menu type',
					'rules' => 'required',
				],
				[
					'field' => 'icon',
					'label' => 'Icon',
					'rules' => 'required',
				],
				[
					'field' => 'label',
					'label' => 'Label',
					'rules' => 'trim|required',
				],
				[
					'field' => 'link',
					'label' => 'Link',
					'rules' => 'trim|required',
				],
				[
					'field' => 'group[]',
					'label' => 'group',
					'rules' => 'required',
					'errors' => array('required' => 'Group harus di isi')
				],
			];
		} else if($rules == 'landing') {
			$validationRules = [
				[
					'field' => 'category_id',
					'label' => 'Menu type',
					'rules' => 'required',
				],
				
				[
					'field' => 'label',
					'label' => 'Label',
					'rules' => 'trim|required',
				],
				[
					'field' => 'link',
					'label' => 'Link',
					'rules' => 'trim|required',
				]
			];
		} else if($rules == 'label') {
			$validationRules = [
				[
					'field' => 'category_id',
					'label' => 'Menu type',
					'rules' => 'required',
				],
				
				[
					'field' => 'label',
					'label' => 'Label',
					'rules' => 'trim|required',
				],
				[
					'field' => 'group[]',
					'label' => 'Group',
					'rules' => 'required',
				]
			];
		}
		return $validationRules;
		
	}

	public function run($data)
	{
		
			$save_data = [
				'type'					=> $data->category_id,
				'icon'					=> $data->icon,
				'parent'				=> $data->parent,
				'label'					=> $data->label,
				'url'					=> $data->link,
				'active'				=> 0,				
			];
			$insert_id = $this->create($save_data);
			if ($data->category_id != 'landing' && ($insert_id)) {
				foreach ($data->group as $row) {
					$group = $this->where('id_role', $row)->first('menu_access');
					if ($group->id_role == $row) {
						$group_access = explode(',', $group->access_right);
						array_push($group_access, $insert_id);
						$menu_access = implode(',', $group_access);

						$data_role_menu = [
							'access_right' => $menu_access
						];
					}
					//role di access menu harus tersedia terlebih dahulu
					$insert_role = $this->where('id_role', $row)->update($data_role_menu, 'menu_access');

				}
			}
		

			return $insert_id;
		
	}


}	 