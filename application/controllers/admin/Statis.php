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
            $bool= $this->statis_model->add($data);
            if ($bool)
              showmessage('操作成功','admin/statis');
            else
                showmessage('操作失败','admin/statis/add',1);
            exit();
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
        showmessage('操作成功','admin/statis');
    }

}
