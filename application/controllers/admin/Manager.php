<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Manager_model','manager_model');
        $this->load->model('Common_model','common_model');
        $this->load->library('form_validation');
    }

    /**
     *$page 为当前页
     */
	public function index($page='')
    {
        $page_num=15;
        if ($page=='')$page=0;
        $data['count']=$this->common_model->query_count("select count(1) from api_manager a left join api_group b on a.group_id=b.id");
        $sql='select a.*,b.groupname from api_manager a left join api_group b on a.group_id=b.id';
        $sql=$sql.' limit '.($page)*$page_num.','.$page_num;
        $data['users']=$this->common_model->querylist($sql);
        $this->load->library('common_page');

        //($data['count']);
        $data['page']=$this->common_page->create_page($data['count'],$page,$page_num,'/admin/manager/index');
        $this->load->view('admin/manager/list.html',$data);
    }

    /**
     * 添加企业用户
     */
    public  function  add()
    {
        $data = $this->input->post();
        $sql='select id,groupname from api_group';

        if (count($data)>0)
        {
            if ($data['password']!=$data['confirmpassword'])
            {
                $data['alert'] = "密码与确认密码输入不一致！";
                $this->load->view('admin/users/add.html', $data);
                return false;
            }
            $this->manager_model->add($data);
            $data['groups']= $this->common_model->querylist($sql);
            redirect('admin/manager');
        }
        else{
            $data['groups'] = $this->common_model->querylist($sql);
            //   var_dump( $data['groups']);
          $this->load->view('admin/manager/add.html',$data);
        }
    }

    /**
     *
     * 设置用户启用/禁用状态
     */
    public function setstatus($id)
    {
        $bool = $this->manager_model->setstatus($id);
        echo $bool;
    }

    /**
     * @param $id 管理员表id
     */
       public  function  edit($id)
       {

           $data = $this->input->post();
           $sql='select id,groupname from api_group';
           if (count($data)>0)
           {
               if ($data['password']!=$data['confirmpassword'])
               {
                   $data['alert'] = "密码与确认密码输入不一致！";
                   $this->load->view('admin/manager/add.html', $data);
                   return false;
               }
               if (!isset($data['isable'])) $data['isable']=0;
               $data['id']=$id;
               if ($data['password']!='')$data['password']=md5($data['password']);
               $data['user'] = $this->manager_model->update($data);
               redirect('/admin/manager');
           }
           else
           {
               $data['user'] = $this->manager_model->getsta($id);
               $data['groups'] = $this->common_model->querylist($sql);
               $this->load->view('admin/manager/edit.html',$data);


           }

       }
}
