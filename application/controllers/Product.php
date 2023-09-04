<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use ImageKit\ImageKit;


 

class Product extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model');
        // $this->load->library('Imagekit_lib');
        $this->load->helper('imagekit');
        $this->load->library('pagination');
    }

    public function add_product()
    {
        // Retrieve the data from the request
        $product_data = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'affiliate_url' => $this->input->post('affiliate_url')??'',
            'category' => $this->input->post('category')??0,
            'created_at' => date('Y-m-d H:i:s'),
        );

        // Validate the data (you can add your validation rules here)
        // ...
        // $imagekit = get_imagekit_instance();
        
        $imagekit= new ImageKit
        ( "public_PK0IdnZqslL0W8TtdI13tICk6vw=",   
        "private_L7CEn8Sr6/79HMWWYGmr4BL3GfI=", 
        "https://ik.imagekit.io/k2uegtqj2"
        );
        // Handle image upload
        if (!empty($_FILES['image']['name'])) {
           

            $config['upload_path'] = './uploads/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 10240; // 10 MB

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('image')) {
                $error = array('error' => $this->upload->display_errors());
                $this->output->set_content_type('application/json')->set_output(json_encode($error));
            } else {
                $data = $this->upload->data();
                $imagePath = $data['full_path'];
               

                $uploadData = $imagekit->upload(array(
                    // 'file' => $imagePath,
                    'file' => fopen($imagePath, "r"),
                    'fileName' => $data['file_name'],
                ));
                

                $arr= (array) $uploadData->result;
               
                $arr1=(array) $uploadData->responseMetadata;
                
                if($arr1['statusCode']==200){
                    unlink($imagePath);
                    $product_data['image']=$arr1['raw']->url;
                }
                else{
                    $response = array('status' => 'error', 'message' => 'Image upload failed');
                    $this->output->set_content_type('application/json')->set_output(json_encode($response));
                    return;
                }
               
                
            }

           
        } else {
           
        }

        // Add the product to the database
        $product_id = $this->Product_model->add_product($product_data);

        if ($product_id) {
            // Product added successfully
            $response = array('status' => 'success', 'message' => 'Product added successfully', 'product_id' => $product_id);
            // echo json_encode($response);
        } else {
            // Failed to add the product
            $response = array('status' => 'error', 'message' => 'Failed to add the product');
            // echo json_encode($response);
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

    public function list_all_products()
    {
        $products = $this->Product_model->get_all_products();

        if ($products) {
            $response = array('status' => 'success', 'data' => $products);
        } else {
            $response = array('status' => 'error', 'message' => 'No products found');
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }

   

    public function list_products(){
        
            $page = $this->input->get('page', TRUE);
            $page = max(1, intval($page)); // Ensure page number is a positive integer
    
            $this->load->library('pagination');
    
            $config['base_url'] = base_url('api/products');
            $config['total_rows'] = $this->Product_model->count_all_products();
            $config['per_page'] = 10;
    
            $this->pagination->initialize($config);
    
            $start = ($page - 1) * $config['per_page'];
            $products = $this->Product_model->get_products_with_pagination($config['per_page'], $start);
    
            if ($products) {
                $response = array(
                    'status' => 'success',
                    'data' => $products,
                    'pagination' => array(
                        'total_pages' => ceil($config['total_rows'] / $config['per_page']),
                        'current_page' => $page,
                        'per_page' => $config['per_page']
                    )
                );
            } else {
                $response = array('status' => 'error', 'message' => 'No products found');
            }
    
            $this->output->set_content_type('application/json')->set_output(json_encode($response));
        
    }

    public function search_products()
    {
        $search_query = $this->input->get('q', TRUE);
        $page = $this->input->get('page', TRUE);
        $page = max(1, intval($page)); // Ensure page number is a positive integer

        if (empty($search_query)) {
            $response = array('status' => 'error', 'message' => 'Search query is required');
        } else {
            $this->load->library('pagination');

            $config['base_url'] = base_url('api/products/search');
            $config['total_rows'] = $this->Product_model->count_search_results($search_query);
            $config['per_page'] = 10;

            $this->pagination->initialize($config);

            $start = ($page - 1) * $config['per_page'];
            $results = $this->Product_model->search_products_by_name_or_description_with_pagination($search_query, $config['per_page'], $start);

            if ($results) {
                $response = array(
                    'status' => 'success',
                    'data' => $results,
                    'pagination' => array(
                        'total_pages' => ceil($config['total_rows'] / $config['per_page']),
                        'current_page' => $page,
                        'per_page' => $config['per_page']
                    )
                );
            } else {
                $response = array('status' => 'error', 'message' => 'No products found matching the search query');
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode($response));
    }


    public function edit_product() {
        $product_id = $this->input->post('product_id');
        $product_name = $this->input->post('name');
        $product_description = $this->input->post('description');
        $product_category = $this->input->post('category')??0;
        $product_button_url = $this->input->post('affiliate_url');
    
        if (!$product_id ) {
            $response = array('status' => 'error', 'message' => 'Product ID are required');
            $this->output->set_status_header(400);
        } else {
            $data = array(
                'name' => $product_name,
                'description' => $product_description,
                'category' => $product_category,
                'affiliate_url' => $product_button_url
            );
            
            // Handle image upload if needed and update image URL in $data
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = './uploads/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 10240; // 1 MB
    
                $this->load->library('upload', $config);
    
                if (!$this->upload->do_upload('image')) {
                    $error = array('status' => 'error', 'message' => $this->upload->display_errors());
                    $this->output->set_content_type('application/json')->set_output(json_encode($error));
                    return;
                } else {
                    // $data['image'] = $this->upload->data('file_name');
                    // Handle the ImageKit upload logic here
                    $data = $this->upload->data();
                    $imagePath = $data['full_path'];
                    
                    $imagekit= new ImageKit
                    ( "public_PK0IdnZqslL0W8TtdI13tICk6vw=",   
                    "private_L7CEn8Sr6/79HMWWYGmr4BL3GfI=", 
                    "https://ik.imagekit.io/k2uegtqj2"
                    );

                    $uploadData = $imagekit->upload(array(
                        // 'file' => $imagePath,
                        'file' => fopen($imagePath, "r"),
                        'fileName' => $data['file_name'],
                    ));
                    

                    $arr= (array) $uploadData->result;
                
                    $arr1=(array) $uploadData->responseMetadata;
                    
                    if($arr1['statusCode']==200){
                        unlink($imagePath);
                        $data['image']=$arr1['raw']->url;
                    }
                    else{
                        $response = array('status' => 'error', 'message' => 'Image upload failed');
                        $this->output->set_content_type('application/json')->set_output(json_encode($response));
                        return;
                    }
                }
            }
            
            $success = $this->Product_model->edit_product($product_id, $data);
            
            if ($success) {
                $response = array('status' => 'success', 'message' => 'Product updated successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to update product');
                $this->output->set_status_header(500);
            }
        }
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    public function delete_product() {
        $product_id = $this->input->post('product_id');
    
        if (!$product_id) {
            $response = array('status' => 'error', 'message' => 'Product ID is required');
            $this->output->set_status_header(400);
        } else {
            $success = $this->Product_model->soft_delete_product($product_id);
            if ($success) {
                $response = array('status' => 'success', 'message' => 'Product marked as deleted');
            } else {
                $response = array('status' => 'error', 'message' => 'Failed to mark product as deleted');
                $this->output->set_status_header(500);
            }
        }
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    public function list_deleted_products() {
        $page = $this->input->get('page', TRUE);
        $page = max(1, intval($page)); // Ensure page number is a positive integer
    
        $this->load->library('pagination');
    
        $config['total_rows'] = $this->Product_model->count_deleted_products();
        $config['per_page'] = 10;
    
        $this->pagination->initialize($config);
    
        $start = ($page - 1) * $config['per_page'];
    
        $products = $this->Product_model->get_deleted_products_with_pagination($config['per_page'], $start);
    
        if ($products) {
            $response = array(
                'status' => 'success',
                'data' => $products,
                'pagination' => array(
                    'total_pages' => ceil($config['total_rows'] / $config['per_page']),
                    'current_page' => $page,
                    'per_page' => $config['per_page']
                )
            );
        } else {
            $response = array('status' => 'error', 'message' => 'No deleted products found');
        }
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    
    public function revoke_deleted_product() {
        $product_id=$this->input->post('product_id');
        if(!empty($product_id)){
            $product = $this->Product_model->get_product($product_id);

            if ($product && $product->deleted_at !== null) {
                $deleted_at = new DateTime($product->deleted_at);
                $now = new DateTime();
                $interval = $deleted_at->diff($now);
        
                if ($interval->days < 30) {
                    $result = $this->Product_model->revoke_deleted_product($product_id);
        
                    if ($result) {
                        $response = array('status' => 'success', 'message' => 'Product revoked successfully');
                    } else {
                        $response = array('status' => 'error', 'message' => 'Failed to revoke product');
                        $this->output->set_status_header(500);
                    }
                } else {
                    $response = array('status' => 'error', 'message' => 'Product cannot be revoked after 30 days');
                    $this->output->set_status_header(400);
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Product not found');
                $this->output->set_status_header(404);
            }
        }
        else {
            $response = array('status' => 'error', 'message' => 'Please enter product to delete');
            $this->output->set_status_header(400);
        }
        
    
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
    

}
