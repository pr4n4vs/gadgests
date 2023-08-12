<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
    }

    public function add_category() {
        $category_name = $this->input->post('name');

        if (!$category_name) {
            $response = array('status' => 'error', 'message' => 'Category name is required');
            $this->output->set_status_header(400);
        } else {
            $category_id = $this->Category_model->add_category($category_name);
            if ($category_id) {
                $response = array('status' => 'success', 'message' => 'Category added successfully', 'data' => array('category_id' => $category_id, 'name' => $category_name));
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to add category');
                $this->output->set_status_header(500);
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    public function edit_category() {
        $category_id = $this->input->post('category_id');
        $category_name = $this->input->post('name');
    
        if (!$category_id || !$category_name) {
            $response = array('status' => 'error', 'message' => 'Category ID and name are required');
            $this->output->set_status_header(400);
        } else {
            $success = $this->Category_model->edit_category($category_id, $category_name);
            if ($success) {
                $response = array('status' => 'success', 'message' => 'Category updated successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to update category');
                $this->output->set_status_header(500);
            }
        }
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function delete_category() {
        $category_id = $this->input->post('category_id');
    
        if (!$category_id) {
            $response = array('status' => 'error', 'message' => 'Category ID is required');
            $this->output->set_status_header(400);
        } else {
            $success = $this->Category_model->soft_delete_category($category_id);
            if ($success) {
                $response = array('status' => 'success', 'message' => 'Category marked as deleted');
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to mark category as deleted');
                $this->output->set_status_header(500);
            }
        }
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }


    public function list_all_categories() {
        $this->load->model('Category_model');
        $categories = $this->Category_model->get_all_categories();
    
        if ($categories) {
            $response = array('status' => 'success', 'message' => 'Categories fetched successfully', 'data' => $categories);
        } else {
            $response = array('status' => 'error', 'message' => 'Failed to fetch categories');
            $this->output->set_status_header(500);
        }
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    
}
