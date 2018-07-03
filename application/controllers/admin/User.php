<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model','user_model');
        $this->load->library('form_validation');
    }

    /**
     * 企业用户列表
     */
	public function index()
    {
        $query=$this->user_model->getall();
        $data['users']=$query->result();
        $data['num'] = $query->num_rows();
        $this->load->view('admin/users/list.html',$data);
    }
    /**
     * 添加企业用户
     */
    public  function  add()
    {

      return $this->load->view('admin/users/add.html');

    }

}
