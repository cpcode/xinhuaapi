<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model','common_model');
        $this->load->model('User_model','user_model');
    }

	public function index($id='')
    {
        $userlist=$this->user_model->getall();
        $data['users']=$userlist->result();
        if ($id=='') $id=$data['users'][0]->id;
        $data['prolist']=$this->orderlist($id);
        $data['userid']=$id;
         $this->load->view('admin/order/list.html',$data);
    }

    public  function  orderlist($id)
    {
        $str="select a.*,b.pro_name,b.api_name from api_order a left join api_product b on a.pro_id=b.id where a.user_id='".$id."' order by a.id desc";
        return $this->common_model->querylist($str);
    }

    public  function  getdetail($id)
    {
        $str="select detail from api_order where id='".$id."'";
        $data=$this->common_model->query_one($str);
        echo $data['detail'];


    }
}
