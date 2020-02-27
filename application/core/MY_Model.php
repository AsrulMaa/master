<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Model extends CI_Model {

    protected $table = '';
    protected $perPage = 5;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        if (!$this->table) {
            $this->table = strtolower(
                str_replace('_model', '', get_class($this))
            );
        }
        
    }

    /**
     * Fungsi validasi input
     * Rules : dideklarasikan dalam masing-masing model
     * 
     * @return void
     */

    public function validate($rules = null)
    {
        
       if ($rules != null) {
            $this->load->library('form_validation');
            // $this->form_validation->set_error_delimiters(
            //     '<small class="form-text text-danger">', '</small>'
            // );

            $validationRules = $this->getValidationRules($rules);
            $this->form_validation->set_rules($validationRules);
        } else {
            $this->load->library('form_validation');

            $validationRules = $this->getValidationRules();
            $this->form_validation->set_rules($validationRules);  
        }
        

        return $this->form_validation->run();
           
        
    }

    /**
     * Seleksi data perkolom
     * Chain Method
     * 
     * @param [type] $columns
     * @return void    
     */

    public function select($columns)
    {
        $this->db->select($columns);
        return $this;
    }

    /**
     * Mecari suatu data pada kolom tertentu dengan data yang sama
     * Chain Method
     * 
     * @param [type] $columns
     * @param [type] $condition
     * @return void
     */
    public function where($columns, $condition)
    {
        $this->db->where($columns, $condition);
        return $this;
    }

    public function where_in($columns, $condition)
    {
        $this->db->where_in($columns, $condition);
        return $this;
    }

    /**
     * Mecari suatu data pada kolom tertentu dengan data   yang di inputkan oleh user
     * Chain Method
     * 
     * @param [type] $columns
     * @param [type] $condition
     * @return void
     */
    public function Like($columns, $condition)
    {
        $this->db->like($columns, $condition);
        return $this;
    }

    /**
     * Mecari suatu data pada kolom tertentu dengan data yang di inputkan oleh user
     * Chain Method
     * 
     * @param [type] $columns
     * @param [type] $condition
     * @return void
     */
    public function orLike($columns, $condition)
    {
        $this->db->or_like($columns, $condition);
        return $this;
    }

    /**
     * MEnghubungkan Table yang berelasi dengan tabel yg memiliki foreign key id_namatable
     * Chain Method
     * 
     * @param [type] $table
     * @param [type] $type
     * @return void
     */
    public function join($table, $type = 'left')
    {
        $this->db->join($table, "$this->table.id_$table = $table.id", $type);
        return $this;
    }

    /**
     * Mengurutkan data dari hasil query dan kondisi
     * default ascending
     * Chain Method
     * 
     * @param [type] $columns
     * @param [type] $condition
     * @return void
     */
    public function orderBy($columns, $order = 'asc')
    {
        $this->db->order_by($columns, $order);
        return $this;
        
    }

    /**
     * Menampilkan Satu data dari hasil query dan kondisi
     * Chain Method
     * 
     * @param [type] $columns
     * @param [type] $condition
     * @return void
     */
    public function first($table = null)
    {
        if ($table != null) {
            return $this->db->get($table)->row();
                        
        } else {
            return $this->db->get($this->table)->row();

        }
        
    }

    /**
     * Menampilkan Banyak data dari hasil query dan kondisi
     * Chain Method
     * 
     * @param [type] $columns
     * @param [type] $condition
     * @return void
     */
    public function get($table = null)
    {
        if ($table != null) {
            return $this->db->get($table)->result();
            
                        
        } else {
            return $this->db->get($this->table)->result();
           

        }
        
    }

      public function get_result($table = null)
    {
        if ($table != null) {
            return $this->db->get($table)->result_array();
            
                        
        } else {
            return $this->db->get($this->table)->result_array();
           

        }
        
    }

    /**
     * Mecari suatu data pada kolom tertentu dengan data yang sama
     * Chain Method
     * 
     * @param [type] $columns
     * @param [type] $condition
     * @return void
     */
    public function count()
    {
        return $this->db->count_all_results($this->table);
        
    }

    public function count_filtered()
    {
        return $this->db->get($this->table)->num_rows();
        
    }

   

    /**
     * Seleksi data perkolom
     * Chain Method
     * 
     * @param [type] $columns
     * @return void    
     */

    public function distinct()
    {
        $this->db->distinct();
        return $this;
    }

    /**
     * Mecari suatu data pada kolom tertentu dengan data yang sama
     * Chain Method
     * 
     * @param [type] $columns
     * @param [type] $condition
     * @return void
     */
    public function create($data, $table = null)
    {
        if ($table == null) {
           $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        } else{
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        }
        
    }

    public function insert_batch($data, $table = null)
    {
        $count = count($data);
        if ($table == null) {
            $this->db->insert_batch($this->table, $data);
            $first_id = $this->db->insert_id();
            return $last_id = $first_id + ($count-1);
        } else { 
            $this->db->insert_batch($table, $data);
            $first_id = $this->db->insert_id();
            return $last_id = $first_id + ($count-1);
        }
        

    }


    public function update_batch($data, $table = null, $id)
    {
        $count = count($data);
        if ($table == null) {
            $this->db->update_batch($this->table, $data, $id);
             $updated_status = $this->db->affected_rows();
            if($updated_status):
                return $id;
            else:
                return false;
            endif;
        } else { 
            $this->db->update_batch($table, $data,$id);
            $updated_status = $this->db->affected_rows();
            if($updated_status):
                return $id;
            else:
                return false;
            endif;
        }
        

    }

    public function update($data, $table = null)
    {
        if ($table != null) 
            return $this->db->update($table, $data);
       
         return $this->db->update($this->table, $data);
    }

    public function delete()
    {
        $this->db->delete($this->table);
        return $this->db->affected_rows();
        
        
    }

    public function paginate($page)
    {
        $this->db->limit(
            $this->perPage,
            $this->calculateRealOffset($page)
        );

        return $this;
        
    }

    public function calculateRealOffset($page)
    {
        if (is_null($page) || empty($page)){
            $offset = 0;
        } else {
            $offset = ($page * $this->perPage) - $this->perPage;
        }

        return $offset;
    }

    public function makePagination($baseUrl, $uriSegment, $totalRows = null)
    {
        $this->load->library('pagination');
        
        $config = [
            'base_url'          => $baseUrl,
            'uri_segment'       => $uriSegment,
            'per_page'          => $this->perPage,
            'total_rows'        => $totalRows,
            'use_page_numbers'  => true,

            'full_tag_open'     => '<ul class="pagination">',
            'full_tag_close'    => '</ul>',
            'attributes'        => ['class' => 'page-link'],
            'first_link'        => false,
            'last_link'         => false,
            'first_tag_open'    => '<li class="page-item">',
            'first_tag_close'   => '</li>',
            'prev_link'         => '&laquo',
            'prev_tag_open'     =>'<li class="page-item">',  
            'prev_tag_close'    =>'</li>',
            'next_link'         => '&raquo',
            'next_tag_open'     =>'<li class="page-item">',  
            'next_tag_close'    =>'</li>',
            'last_tag_open'     =>'<li class="page-item">',  
            'last_tag_close'    =>'</li>',
            'cur_tag_open'      => '<li class="page-item active"><a href="#" class="page-link">',
            'cur_tag_close'     => '<span class="sr-only">(current)</span></a></li>',
            'num_tag_open'      => '<li class="page-item">',
            'num_tag_close'     => '</li>',
        ];

        $this->pagination->initialize($config);
        return $this->pagination->create_links();

    }


    public function datatables()
    {
        $this->db->from($this->table);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();  
        return $query->result();   
    }


    //2
    public function search_item($items = array())
    {
        $i = 0;
        foreach ($items as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($items) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        return $this;
    }

    public function column_order($order = [])
    {
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 

        return $this;
    }

    
    public function find($id = NULL, $select_field = [])
    {
        if (is_array($select_field) AND count($select_field)) {
            $this->db->select($select_field);
        }

        $this->db->where("".$this->table.'.'.$this->primary_key,$id);
        $query = $this->db->get($this->table);

        if($query->num_rows()>0)
        {
            return $query->row();
        }
        else
        {
            return FALSE;
        }
    }

}

/* End of file core/MY_Model.php */
