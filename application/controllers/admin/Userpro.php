<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userpro extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Userpro_model','userpro_model');
        $this->load->model('User_model','user_model');
        $this->load->library('form_validation');
    }

    /**
     * 获取已有的接口列表
     */
	public function index()
    {

        $userlist=$this->user_model->getall();
        $data['users']=$userlist->result();
         $id=$data['users'][1]->id;
         $sql='';
         $query=$this->userpro_model->querylist($sql);
         $data['prolist']=$query->result();


        $this->load->view('admin/buy/list.html',$data);
    }
}
