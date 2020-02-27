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
