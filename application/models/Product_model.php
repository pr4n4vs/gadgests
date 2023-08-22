<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    // Add a new product to the database
    public function add_product($data)
    {
        $this->db->insert('products', $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
    }

    public function get_all_products()
    {
        $this->db->where('status',1);
        return $this->db->get('products')->result_array();
    }

    public function count_all_products()
    {
        $this->db->where('status',1);
        return $this->db->count_all('products');
    }

    // Get products with pagination
    public function get_products_with_pagination($limit, $start)
    {
        $this->db->limit($limit, $start);
        $this->db->where('status',1);
        return $this->db->get('products')->result_array();
    }

    public function count_search_results($search_query)
    {
        $this->db->like('name', $search_query);
        $this->db->or_like('description', $search_query);
        $this->db->where('status',1);
        return $this->db->count_all_results('products');
    }

    // Search products by name or description with pagination
    public function search_products_by_name_or_description_with_pagination($search_query, $limit, $start)
    {
        $this->db->like('name', $search_query);
        $this->db->or_like('description', $search_query);
        $this->db->limit($limit, $start);
        $this->db->where('status',1);
        return $this->db->get('products')->result_array();
    }



    public function edit_product($product_id, $data) {
        $this->db->where('id', $product_id);
        return $this->db->update('products', $data);
    }


    public function soft_delete_product($product_id) {
        $data = array('status' => 0);
        $this->db->where('id', $product_id);
        return $this->db->update('products', $data);
    }

    public function count_deleted_products() {
        $this->db->where('deleted_at IS NOT NULL');
        return $this->db->count_all_results('products');
    }
    
    public function get_deleted_products_with_pagination($per_page, $start) {
        $this->db->select('*');
        $this->db->from('products');
        $this->db->where('deleted_at IS NOT NULL');
        $this->db->limit($per_page, $start);
        $query = $this->db->get();
        $products = $query->result();

        // Calculate days left for automatic deletion
        foreach ($products as &$product) {
            $deleted_at = new DateTime($product->deleted_at);
            $now = new DateTime();
            $interval = $deleted_at->diff($now);
            $days_left = 30 - $interval->days;
            $product->days_left = max(0, $days_left);
        }

        return $products;

        
    }

    public function revoke_deleted_product($product_id) {
        $this->db->where('id', $product_id);
        $this->db->update('products', array('deleted_at' => NULL));
        return $this->db->affected_rows() > 0;
    }
    
    
    public function delete_old_products($threshold) {
        $this->db->where('deleted_at IS NOT NULL');
        $this->db->where('deleted_at <=', $threshold);
        $this->db->delete('products');
    }
    
    public function get_product($product_id) {
        $query = $this->db->get_where('products', array('id' => $product_id));
        return $query->row();
    }
}
