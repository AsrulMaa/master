<?php

    function getDropdownList($table, $columns)
    {
        $ci =& get_instance();
        $query = $ci->db->select($columns)->from($table)->get();

        if ($query->num_rows() >= 1) {
            $options1 = ['' => '- Select -'];
            $option2= array_column($query->result(), $columns[1], $columns[0]);
            $options = $options1 + $option2;

            return $options;
        }
        return $options = ['' => '- Select -'];
    }

    function getCategories()
    {
        $ci =& get_instance();
        $query = $ci->db->get('category')->result();
        return $query;
    }

    function getCart()
    {
        $ci =& get_instance();
        $user_id = $ci->session->userdata('id');

        if ($user_id) {
            $query = $ci->db->where('id_user', $user_id)->count_all_results('cart');
            return $query;
        }

        return false;
    }

    function hashEncrypt($input)
    {
        $hash = password_hash($input, PASSWORD_DEFAULT);
        return $hash;
    }

    function hashEncryptVerify($input, $hash)
    {
        if (password_verify($input, $hash)) {
            return true;
        } else {
            return false;
        }

    }

    function url_image($side, $image)
    {
        if ($side = 'backend') {
            $base = BASE_URL.'uploads/user/'.$image;
        }

        return $base;
    }



    function display_menu_admin($parent, $level)
    {
        $ci =& get_instance();
        $ci->load->model('Menu_model', 'menu');
        $isRole = $ci->session->userdata('role');
        //1. Tampilkan data role
        $menuGroup = $ci->menu->where('id_role', $isRole)->get_result('menu_access');

        foreach ($menuGroup as $row) {
            $menuGroup = $row;
        }
        $userAccess = explode(",", $menuGroup['access_right']);

        $query = "SELECT * FROM menus";
        foreach($userAccess as $key=>$val){
            if($key == 0){
                $query .= " WHERE";
            } else{
                $query .= " OR";
            }
            $query .= " id = $val";
        }
        $query .= " AND active = 1  ";
        $query .= " order by `position` ASC";
        $query = $ci->db->query($query)->result_array();
        $refs = array();
        $list = array();

        foreach ($query as $row) {
            $data = &$refs[ $row['id']];
            $data['id']      = $row['id'];
            $data['label']      = $row['label'];
            $data['url']        = $row['url'];
            $data['icon']       = $row['icon'];
            $data['segment']    = $row['segment'];
            $data['parent']     = $row['parent'];
            $data['type']       = $row['type'];
            $data['parent']     = $row['parent'];
            $data['label']      = $row['label'];
            $data['position']      = $row['position'];

            if (is_null($row['parent']) OR $row['parent'] == 0) {
                $list[ $row['id'] ] = &$data;
            } else {
                $refs[ $row['parent'] ]['children'][ $row['id'] ] = &$data;
            }
        }

            return create_list($list);

    }

    function create_list($list, $child = false)
    {
        $ci =& get_instance();
        $html = '<ul class="sidebar-menu  sidebar-admin tree"  data-widget="tree">';
        foreach ($list as $key => $value) {
            //SIngle Menu
            $links = explode('/', $value['url']);
            $segments= array_slice($ci->uri->segment_array(), 0, count($links));

            if (implode('/', $segments) == implode('/', $links)) {
                $active = 'active';
            } else {
                $active = '';
            }
            $link = filter_var($value['url'], FILTER_VALIDATE_URL) ? $value['url'] : base_url($value['url']);
            //Label Menu
            if ($value['type'] == 'label') {
                    $html .= '<li class="header treeview">'.($value['label']).'</li>';

            } else {
                if (array_key_exists('children', $value)) {
                     $html .= '<li class="treeview '.$active.' ">
                      <a href="'.$link.'"><i class="fa '.($value['icon']).'"></i> <span>'.($value['label']).'</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                          </span>
                      </a>
                      <ul class="treeview-menu">';
                        $html .= create_list($value['children'], true);
                      $html .='</ul>
                    </li>';

                } else {
                    if ($child == true) {
                        $icon = 'fa-circle-o';
                    } else {
                        $icon = '';
                    }
                    $html .= '<li class="'.$active.' "> 
                                          <a href="'.$link.'">';
                    $html .= '<i class="fa '.($value['icon']).' '. $icon.'"></i> <span>'.($value['label']).'</span>
                                                  </a>';  
                    $html .= "</li>";    
                }
            }
        }
        $html .= "</ul>";
        return $html;
    }

    function user($get)
    {
        $ci =& get_instance();
        $ci->load->model('Users_model', 'user');
        $id = $ci->session->userdata('id');
        $users = $datas = $ci->user->select(
                [
                    'users.id', 'users.username', 'users.email', 'users.fullname', 'users.is_active', 'users.avatar', 'users.token', 'users.created_at', 'role.role'
                ])
            ->join('role','left')
            ->where('users.id', $id)->get();
        foreach ($users as $row) {
            switch ($get) {
                case 'photo':
                    return (STORAGEUSER.'/'.$row->avatar);
                    break;
                case 'username':
                    return strtoupper($row->username);
                    break;
                case 'fullname':
                    return strtoupper($row->fullname);;
                    break;
                case 'email':
                    return strtolower($row->email);;
                    break;
                case 'role':
                    return strtolower($row->role);;
                    break;      
                case 'created_at':
                    return tgl_indo($row->created_at);;
                    break;                     
                default:
                    # code...
                    break;
            }
        }
    }
    

function hari_ini($hari)
{

    $seminggu = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");

    //$hari     = date("w");

    return $hari_ini = $seminggu[$hari]; // konversi menjadi hari bahasa indonesia



    $tgl_sekarang = date("Ymd");

    $thn_sekarang = date("Y");

    $jam_sekarang = date("H:i:s");
}

// fungsi untuk mengubah tanggal menjadi format bahasa indonesia, contoh: 14 Maret 2014 

function tgl_indo($tgl){

    $tanggal = substr($tgl,8,2);

    $bulan   = ambilbulan(substr($tgl,5,2)); // konversi menjadi nama bulan bahasa indonesia

    $tahun   = substr($tgl,0,4);

    return $tanggal.' '.$bulan.' '.$tahun;       

}   



// fungsi untuk mengubah angka bulan menjadi nama bulan

function ambilbulan($bln){

  if ($bln=="01") return "Januari";

  elseif ($bln=="02") return "Februari";

  elseif ($bln=="03") return "Maret";

  elseif ($bln=="04") return "April";

  elseif ($bln=="05") return "Mei";

  elseif ($bln=="06") return "Juni";

  elseif ($bln=="07") return "Juli";

  elseif ($bln=="08") return "Agustus";

  elseif ($bln=="09") return "September";

  elseif ($bln=="10") return "Oktober";

  elseif ($bln=="11") return "November";

  elseif ($bln=="12") return "Desember";

} 



// fungsi untuk mengubah susunan format tanggal

function ubah_tgl($tanggal) { 

   $pisah   = explode('/',$tanggal);

   $larik   = array($pisah[2],$pisah[1],$pisah[0]);

   $satukan = implode('-',$larik);

   return $satukan;

}



function ubah_tgl2($tanggal) { 

   $pisah   = explode('-',$tanggal);

   $larik   = array($pisah[2],$pisah[1],$pisah[0]);

   $satukan = implode('/',$larik);

   return $satukan;

}  



function set_message($message = null, $type = 'success') {
    $ci =& get_instance();

    $ci->session->set_flashdata('f_message', $message);
    $ci->session->set_flashdata('f_type', $type);
}

function redirect_back($url = '')
{
    if(isset($_SERVER['HTTP_REFERER']))
    {
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }
    else
    {
        redirect($url);
    }
    exit;
}


function getParent($col, $con) {
    $ci =& get_instance();
     $ci->load->model('Menu_model', 'menu');
    if ($col == null) {
        return $main_menu = $ci->menu->get();
    } else {
        $ci->menu->where($col, $con);
        return $ci->menu->get();
    }
    
    
}

function getRole() {
    $ci =& get_instance();
    $main_menu = $ci->db->get('role');
    return $main_menu->result(); 
}

function db_get_all_data($table_name = null, $where = false) {
    $ci =& get_instance();
    if ($where) {
        $ci->db->where($where);
    }
    $query = $ci->db->get($table_name);

    return $query->result();
}

function display_menu_module($parent, $level, $menu_type, $ignore_active = false) {
    $ci =& get_instance();
    $ci->load->database();
    $ci->load->model('Menu_model', 'model_menu');
    //$menu_type_id = $ci->model_menu->get_id_menu_type_by_flag($menu_type);
    $result = $ci->db->query("SELECT a.id, a.label, a.type, a.active, a.link, Deriv1.Count FROM `menu` a  LEFT OUTER JOIN (SELECT parent, COUNT(*) AS Count FROM `menu` GROUP BY parent) Deriv1 ON a.id = Deriv1.parent WHERE a.menu_type_id = ".$menu_type_id." AND a.parent=" . $parent." ".($ignore_active ? '' : 'and active = 1')." order by `sort` ASC")->result();

    $ret = '';
    if ($result) {
        $ret .= '<ol class="dd-list">';
        foreach ($result as $row) {
            if ($row->Count > 0) {
                 $ret .= '<li class="dd-item dd3-item '.($row->active ? '' : 'menu-toggle-activate_inactive').' menu-toggle-activate" data-id="'.$row->id.'" data-status="'.$row->active.'">';

                if ($row->type != 'label') {
                    $ret .= '<div class="dd-handle dd3-handle dd-handles"></div>';
                    $ret .= '<div class="dd3-content">'._ent($row->label);
                } else{
                    $ret .= '<div class="dd3-content  dd-label dd-handles"><b>'._ent($row->label).'</b>';
                }

                
                if ($ci->aauth->is_allowed('menu_delete')) {
                        $ret .= '<span class="pull-right"><a class="remove-data" href="javascript:void()" data-href="'.site_url('administrator/menu/delete/'.$row->id).'"><i class="fa fa-trash btn-action"></i></a>
                            </span';
                }

                if ($ci->aauth->is_allowed('menu_update')) {
                        $ret .= '<span class="pull-right"><a href="'.site_url('administrator/menu/edit/'.$row->id).'"><i class="fa fa-pencil btn-action"></i></a>
                            </span>';
                }

                $ret .= '</div>';
                $ret .= display_menu_module($row->id, $level + 1, $menu_type, $ignore_active);
                $ret .= "</li>";
            } elseif ($row->Count==0) {
                $ret .= '<li class="dd-item dd3-item '.($row->active ? '' : 'menu-toggle-activate_inactive').' menu-toggle-activate" data-id="'.$row->id.'" data-status="'.$row->active.'">';

                if ($row->type != 'label') {
                    $ret .= '<div class="dd-handle dd3-handle dd-handles"></div>';
                    $ret .= '<div class="dd3-content">'._ent($row->label);
                } else{
                    $ret .= '<div class="dd3-content  dd-label dd-handles"><b>'._ent($row->label).'</b>';
                }

                if ($ci->aauth->is_allowed('menu_delete')) {
                        $ret .= '<span class="pull-right"><a class="remove-data" href="javascript:void()" data-href="'.site_url('administrator/menu/delete/'.$row->id).'"><i class="fa fa-trash btn-action"></i></a>
                            </span';
                }

                if ($ci->aauth->is_allowed('menu_update')) {
                        $ret .= '<span class="pull-right"><a href="'.site_url('administrator/menu/edit/'.$row->id).'"><i class="fa fa-pencil btn-action"></i></a>
                            </span>';
                }

                $ret .= '</div></li>';
            }
        }
        $ret .= "</ol>";
    }

    return $ret;
}
