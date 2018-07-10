<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_model','product_model');
        $this->load->library('form_validation');
    }

    /**
     * 获取已有的接口列表
     */
	public function index($page='')
    {
        $page_num=15;

        if ($page=='')$page=0;

        $data['count']=$this->product_model->query_count();
        $query=$this->product_model->getall($page_num,($page)*$page_num);
        $data['prolist']=$query->result();
        $this->load->library('common_page');
        $data['page']=$this->common_page->create_page($data['count'],$page,$page_num,'/admin/product/index');
        $this->load->view('admin/product/list.html',$data);
    }


    /**
     * 添加新华数据接口
     */
    public  function  add()
    {
        $data = $this->input->post();
        if (count($data)>0)
        {
            $this->product_model->add($data);
           echo  "操作成功";
            exit();
        }
        else{
            $this->load->view('admin/product/add.html');
        }
    }

    /**
     * 接口修改
     */
    public  function  edit($id)
    {
        $data = $this->input->post();
        if (count($data)>0)
        {
            if (!isset($data['isable'])) $data['isable']=0;
            $data['id']=$id;
            $data['product'] = $this->product_model->update($data);
            redirect('/admin/product');
        }
        else
        {
           $data['product']=$this->product_model->getsta($id);
            $this->load->view('admin/product/edit.html',$data);
        }
    }
    /**
     *
     * 设置接口启用/禁用状态
     */
    public function setstatus($id)
    {
        $bool = $this->product_model->setstatus($id);
        echo $bool;
    }

}
