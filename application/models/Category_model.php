<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function add_category($category_name) {
        $data = array('name' => $category_name,'created_at'=>date('Y-m-d H:i:s'));
        $this->db->insert('category', $data);
        return $this->db->insert_id();
    }

    public function edit_category($category_id, $category_name) {
        $data = array('name' => $category_name);
        $this->db->where('id', $category_id);
        return $this->db->update('category', $data);
    }
 
    public function soft_delete_category($category_id) {
        $data = array('deleted_at' => date('Y-m-d H:i:s'));
        $this->db->where('id', $category_id);
        return $this->db->update('category', $data);
    }

    public function get_all_categories() {
        $this->db->select('id,name,created_at');
        $this->db->from('category');
        // $this->db->where('deleted_at',null);
        $this->db->where('deleted_at', NULL); 
        $query = $this->db->get();
        return $query->result();
    }
    
}
