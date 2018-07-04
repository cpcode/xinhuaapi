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
            $this->load->view('admin/users/add.html');
        }
    }

    /**
     *
     * 设置用户启用/禁用状态
     */
     public function setstatus($id)
     {
         $bool = $this->user_model->setstatus($id);
         echo "1";
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
            $this->load->view('admin/users/edit.html',$data);


        }

    }


}
