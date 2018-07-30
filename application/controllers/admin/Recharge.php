<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recharge extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_model','user_model');
        $this->load->model('Common_model','common_model');
        $this->load->library('form_validation');
    }
	public function index($id='',$page='')
    {
        $page_num=15;
        if ($page=='')$page=0;
        $userlist=$this->user_model->getall();
        $data['users']=$userlist->result();
        if ($id=='') $id=$data['users'][0]->id;
        $data['count']=$this->common_model->query_count("select count(1) from api_recharge a left join api_product b on a.api_proid=b.id where a.api_userid='".$id."'");
        $sql="select a.*,b.pro_name,b.api_name from api_recharge a left join api_product b on a.api_proid=b.id where a.api_userid='".$id."'";
        $sql=$sql.' limit '.$page.','.$page_num;
        $data['prolist']=$this->common_model->querylist($sql);
        $data['userid']=$id;
        $this->load->library('common_page');
        $data['page']=$this->common_page->create_page($data['count'],$page,$page_num,'/admin/recharge/index/'.$id);

        $this->load->view('admin/buy/history.html',$data);
    }

     public  function  usercharge($id)
     {
         $str="select a.*,b.pro_name,b.api_name from api_recharge a left join api_product b on a.api_proid=b.id where a.api_userid='".$id."'";
         return $this->common_model->querylist($str);
     }

}
