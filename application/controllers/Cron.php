<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
    }

    public function delete_old_products() {
        $now = new DateTime();
        $threshold = $now->modify('-30 days')->format('Y-m-d H:i:s');
        $this->Product_model->delete_old_products($threshold);

    }
}
