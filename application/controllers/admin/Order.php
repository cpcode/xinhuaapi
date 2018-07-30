<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model','common_model');
        $this->load->model('User_model','user_model');
    }

	public function index($id='',$page='')
    {
        $userlist=$this->user_model->getall();
        $page_num=20;
        if ($page=='')$page=0;
        $data['users']=$userlist->result();
        if ($id=='') $id=$data['users'][0]->id;

        $data['count']=$this->common_model->query_count("select count(1) from api_order where user_id='".$id."'");
        $sql="select a.*,b.pro_name,b.api_name from api_order a left join api_product b on a.pro_id=b.id where a.user_id='".$id."' order by a.id desc";
        $sql=$sql.' limit '.$page.','.$page_num;
        $data['prolist']=  $this->common_model->querylist($sql);
        $data['userid']=$id;
        $this->load->library('common_page');
        $data['page']=$this->common_page->create_page($data['count'],$page,$page_num,'/admin/order/index/'.$id);

         $this->load->view('admin/order/list.html',$data);
    }


    public  function  getdetail($id)
    {
        $str="select detail from api_order where id='".$id."'";
        $data=$this->common_model->query_one($str);
        echo $data['detail'];


    }
}
