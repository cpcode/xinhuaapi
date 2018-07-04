<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Manager_model','manager_model');
        $this->load->library('form_validation');
    }
	public function index()
    {
        $this->load->view('admin/index.html');
    }



}
