<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Permissions extends MY_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Permissions_model','permissions_model');
        $this->load->model('Common_model','common_model');
        $this->load->library('form_validation');
    }

    /**
     * @param string $page
     */
    public function index($page='',$pid='')
    {
        $page_num=15;
        if ($page=='')$page=0;
        $data['count']=$this->permissions_model->query_count();
        $query=$this->permissions_model->getall($page_num,($page)*$page_num);
        $data['permissions']=$query->result();
        $this->load->library('common_page');
        $data['page']=$this->common_page->create_page($data['count'],$page,$page_num,'/admin/product/index');
        $this->load->view('admin/permissions/list.html',$data);
    }


    /**
     * 添加系统模块
     */
    public  function  add()
    {
        $data = $this->input->post();

        if (count($data)>0)
        {
            $this->permissions_model->add($data);
            redirect('admin/manager');
        }
        else{
            $this->load->view('admin/permissions/add.html',$data);
        }
    }

    /**
     * 编辑系统模块
     */
    public  function  edit($id)
    {
            $data['permission']=$this->permissions_model->getsta($id);
            $this->load->view('admin/permissions/edit.html',$data);
    }


}
