<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Statis extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Statis_model','statis_model');
        $this->load->library('form_validation');
    }

    /**
     * 获取已有的接口列表
     */
    public function index()
    {
        $query=$this->statis_model->getall();
        $data['statislist']=$query->result();
        $data['num'] = $query->num_rows();
        $this->load->view('admin/source/list.html',$data);
    }

    /**
     * 添加源接口
     */
    public  function  add()
    {
        $data = $this->input->post();
        if (count($data)>0)
        {
            $this->statis_model->add($data);
            redirect('admin/statis');
        }
        else{
            $this->load->view('admin/source/add.html');
        }
    }

    /**
     * 编辑源接口
     */
    public  function  edit($id)
    {
        $data = $this->input->post();
        if (count($data)>0)
        {
            $data['id']=$id;
            $this->statis_model->update($data);
            $this->load->view('admin/source/add.html',$data);
            redirect('admin/statis');
        }
        else{
            $data['statis']=$this->statis_model->getsta($id);
            $this->load->view('admin/source/edit.html',$data);
        }
    }
    /**
     * 编辑源接口
     */
    public  function delete($id)
    {
        $this->statis_model->delete($id);
        redirect('admin/statis');
    }

}
