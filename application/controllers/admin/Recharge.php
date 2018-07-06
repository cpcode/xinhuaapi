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
	public function index($id='')
    {
        $userlist=$this->user_model->getall();
        $data['users']=$userlist->result();
        if ($id=='') $id=$data['users'][0]->id;
        $data['prolist']=$this->usercharge($id);
        $data['userid']=$id;
        $this->load->view('admin/buy/history.html',$data);
    }

     public  function  usercharge($id)
     {
         $str="select a.*,b.pro_name,b.api_name from api_recharge a left join api_product b on a.api_proid=b.id where a.api_userid='".$id."'";
         return $this->common_model->querylist($str);
     }

}
