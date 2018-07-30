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
	public function index($page='')
    {
        $page_num=15;
        if ($page=='')$page=0;
        $data['count']=$this->user_model->query_count();
        $query=$this->user_model->getall($page_num,$page);
        $this->load->library('common_page');
        $data['page']=$this->common_page->create_page($data['count'],$page,$page_num,'/admin/user/index');
        $data['users']=$query->result();
        $this->load->view('admin/users/list.html',$data);
    }
    function getchild($id,$page='0')
    {
        $page_num=15;
        if ($page=='')$page=0;
        $data['count']=$this->user_model->query_count();
        $query=$this->user_model->getlist(array('pid'=>$id),$page_num,$page);
        $this->load->library('common_page');
        $data['page']=$this->common_page->create_page($data['count'],$page,$page_num,'/admin/user/index');
        $data['users']=$query->result();
        $this->load->view('admin/users/list.html',$data);

    }
    /**
     * 添加企业用户
     */
    public  function  add()
    {
        $data = $this->input->post();
        if (count($data)>0)
        {
        if ($data['password']!=$data['confirmpassword'])
        {
            $data['alert'] = "密码与确认密码输入不一致！";
            $this->load->view('admin/users/add.html', $data);
            return false;
        }
         $this->user_model->add($data);
          redirect('admin/user');
        }
        else{
            $data['ulist']=$this->user_model->getlist(array('pid'=>'-1'))->result();
            $this->load->view('admin/users/add.html',$data);
        }
    }

    /**
     *
     * 设置用户启用/禁用状态
     */
     public function setstatus($id)
     {
         $bool = $this->user_model->setstatus($id);
         echo $bool;
     }

    /**
     *
     * 编辑企业用户
     */
    public function edit($id)
    {
        $data = $this->input->post();
        if (count($data)>0)
        {
            if ($data['password']!=$data['confirmpassword'])
            {
                $data['alert'] = "密码与确认密码输入不一致！";
                $this->load->view('admin/users/add.html', $data);
                return false;
            }
            if (!isset($data['isable'])) $data['isable']=0;
            $data['id']=$id;
            if ($data['password']!='')$data['password']=md5($data['password']);
            $data['user'] = $this->user_model->update($data);
            redirect('/admin/user');
        }
        else
        {
            $data['user'] = $this->user_model->getsta($id);
            $data['ulist']=$this->user_model->getlist(array('pid'=>'-1'))->result();
            $this->load->view('admin/users/edit.html',$data);
        }

    }


}
