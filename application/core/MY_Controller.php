<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller 
{    

    public $core_template   = 'backend/layouts/app';
    public $page_dir        = "backend/pages/";
    public $template_data = array();
    public function __construct()
    {
        parent::__construct();
        $model = strtolower(get_class($this));
        if(file_exists(APPPATH . '/models/'. $model .'_model.php')) {
            $this->load->model($model . '_model', $model, true);
            
        }

        $this->load->library('user_agent');
    }

    public function response($response, $status = 200)
    {
        die(json_encode($response));
    }

    /**
     * Load View default Backend/layouts
     *
     * @param [type] $data
     * @return void
     */

    // public function view($data)
    // {
    //     $this->load->view('backend/layouts/app', $data);
    // }



   

        

    public function set($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->template_data[$key] = $val;  
                return;      
            }    
        }

        $this->template_data[$name] = $value;
    }



    public function load($template = '', $view = '' , $view_data = array(), $return = FALSE)
    {               

        $this->set('contents', $this->load->view($view, $view_data, TRUE));         
        return $this->load->view($template, $this->template_data, $return);

    }

    public function view($data = [])
    {
        $page = $this->page_dir.$data['page'];

        $this->load($this->core_template, $page, $data);
    }



    /**
    * Upload Files tmp
    * 
    * @param Array $data 
    *
    * @return JSON
    */
    public function upload_file($data = [])
    {
        $default = [
            'uuid'          => '', 
            'allowed_types' => '*', 
            'max_size'      => '', 
            'max_width'     => '', 
            'max_height'    => '', 
            'upload_path'   => './uploads/tmp/',
            'input_files'   => 'qqfile',
            'table_name'    => '',
        ];

        foreach ($data as $key => $value) {
            if (isset($default[$key])) {
                $default[$key] = $value;
            }
        }

        $dir = FCPATH . $default['upload_path'] . $default['uuid'];
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        if (empty($default['file_name'])) {
            $default['file_name'] = date('Y-m-d').$default['table_name'].date('His');
        }

        $config = [
            'upload_path'       => $default['upload_path'] . $default['uuid'] . '/',
            'allowed_types'     => $default['allowed_types'],
            'max_size'          => $default['max_size'],
            'max_width'         => $default['max_width'],
            'max_height'        => $default['max_height'],
            'file_name'         => $default['file_name']
        ];
        
        $this->load->library('upload', $config);
        $this->load->helper('file');

        if ( ! $this->upload->do_upload('qqfile')){
            $result = [
                'success'   => false,
                'error'     =>  $this->upload->display_errors()
            ];

            return json_encode($result);
        } else {
            $upload_data = $this->upload->data();

            $result = [
                'uploadName'    => $upload_data['file_name'],
                'previewLink'  => $dir.'/'.$upload_data['file_name'],
                'success'       => true,
            ];

            return json_encode($result);
        }
    }

    /**
    * Delete Files tmp
    * 
    * @param Array $data 
    *
    * @return JSON
    */
    public function delete_file($data = [])
    {
        $default = [
            'uuid'              => '', 
            'delete_by'         => '', 
            'field_name'        => 'image', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'test',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/blog/'
        ];

        foreach ($data as $key => $value) {
            if (isset($default[$key])) {
                $default[$key] = $value;
            }
        }

        if (!empty($default['uuid'])) {
            $this->load->helper('file');
            $delete_file = false;

            if ($default['delete_by'] == 'id') {
                $row = $this->db->get_where($default['table_name'], [$default['primary_key'] => $default['uuid']])->row();
                if ($row) {
                    $path = FCPATH . $default['upload_path'] . $row->{$default['field_name']};
                }

                if (isset($default['uuid'])) {
                    if (is_file($path)) {
                        $delete_file = unlink($path);
                        $this->db->where($default['primary_key'], $default['uuid']);
                        $this->db->update($default['table_name'], [$default['field_name'] => '']);
                    }
                }
            } else {
                $path = FCPATH . $default['upload_path_tmp'] . $default['uuid'] . '/';
                $delete_file = delete_files($path, true);
            }

            if (isset($default['uuid'])) {
                if (is_dir($path)) {
                    rmdir($path);
                }
            }

            if (!$delete_file) {
                $result = [
                    'error' =>  'Error delete file'
                ];

                return json_encode($result);
            } else {
                $result = [
                    'success' => true,
                ];

                return json_encode($result);
            }
        }
    }

    /**
    * Get Files
    * 
    * @param Array $data 
    *
    * @return JSON
    */
    public function get_file($data = [])
    {
        $default = [
            'uuid'              => '', 
            'delete_by'         => '', 
            'field_name'        => 'image', 
            'table_name'        => 'test',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/blog/',
            'delete_endpoint'   => 'administrator/blog/delete_image_file'
        ];

        foreach ($data as $key => $value) {
            if (isset($default[$key])) {
                $default[$key] = $value;
            }
        }
        
        $row = $this->db->get_where($default['table_name'], [$default['primary_key'] => $default['uuid']])->row();

        if (!$row) {
            $result = [
                'error' =>  'Error getting file'
            ];

            return json_encode($result);
        } else {
            if (!empty($row->{$default['field_name']})) {
                if (strpos($row->{$default['field_name']}, ',')) {
                    foreach (explode(',', $row->{$default['field_name']}) as $filename) {
                        $result[] = [
                            'success'               => true,
                            'thumbnailUrl'          => check_is_image_ext(base_url($default['upload_path'] . $filename)),
                            'id'                    => 0,
                            'name'                  => $row->{$default['field_name']},
                            'uuid'                  => $row->{$default['primary_key']},
                            'deleteFileEndpoint'    => base_url($default['delete_endpoint']),
                            'deleteFileParams'      => ['by' => $default['delete_by']]
                        ];
                    }
                } else {
                    $result[] = [
                        'success'               => true,
                        'thumbnailUrl'          => check_is_image_ext(base_url($default['upload_path'] . $row->{$default['field_name']})),
                        'id'                    => 0,
                        'name'                  => $row->{$default['field_name']},
                        'uuid'                  => $row->{$default['primary_key']},
                        'deleteFileEndpoint'    => base_url($default['delete_endpoint']),
                        'deleteFileParams'      => ['by' => $default['delete_by']]
                    ];
                }

                return json_encode($result);
            }
        }
    }





}

/**
 * Backen Core Controller
 * 
 */
class Backend extends MY_Controller
{
        
    public $core_template   = 'core_template/admin_template';
    public $page_dir        = "backend/pages/";


    function __construct()
    {
        parent::__construct();
        $is_login = $this->session->userdata('is_login');

        if (!$is_login) {
            redirect(base_url('login'));
            return;
        }

 
    }

  
   
}


/**
 * Backen Core Controller
 * 
 */
class Auth extends MY_Controller
{
        
    public $core_template = 'core_template/auth_template';
    public $page_dir     = "backend/pages/";


    function __construct()
    {
        parent::__construct();
        
    }


    


  
}


/**
 * ClassFrontend
 * @ Class for Frontend
 */
class Frontend extends MY_Controller
{
    /**
     * Load construct & filable
     */
    public function __construct()
    {
        parent::__construct();
    }
}

/* End of file core/MY_Controller.php */
