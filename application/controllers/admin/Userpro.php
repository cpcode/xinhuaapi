<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userpro extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Userpro_model','userpro_model');
        $this->load->model('User_model','user_model');
        $this->load->library('form_validation');
    }

    /**
     * 获取已有的接口列表
     */
	public function index($id='',$page='')
    {
        $page_num=20;
        if ($page=='')$page=0;
        $userlist=$this->user_model->getall();
        $data['users']=$userlist->result();
        if ($id=='') $id=$data['users'][0]->id;
        $this->load->model('Common_model','common_model');
        $sql='select a.*,b.pro_name,b.api_name,b.isable as pro_isable FROM api_userpro a LEFT JOIN api_product b ON a.pro_id=b.id where user_id='.$id;
        $data['count']=$this->common_model->query_count('select count(1) FROM api_userpro a LEFT JOIN api_product b ON a.pro_id=b.id where user_id='.$id);
        $sql=$sql.' limit '.($page)*$page_num.','.$page_num;

        $data['prolist']=$this->userpro_model->querylist($sql);
        $this->load->library('common_page');
        $data['page']=$this->common_page->create_page($data['count'],$page,$page_num,'/admin/userpro/index/'.$id);
        $data['userid']=$id;
        $this->load->view('admin/buy/list.html',$data);
    }
    /**
     * 点击进入购买页面
     */
    public function  buy($id='')
    {
        $this->load->model('Product_model','product_model');
        $userlist=$this->user_model->getall();
        $data['users']=$userlist->result();
        $data['prolist']=$this->product_model->getall()->result();
       if ($id=='') $data['userid']=$data['users'][0]->id;
       else  $data['userid']=$id;
       $this->load->view('admin/buy/add.html',$data);
    }

    /**
     * 判断接口是否购买
     * 保存购买接口
     */
    public  function  store()
    {

        $data=$this->input->post();
        $this->load->model('Recharge_model','recharge_model');
        $str="select * from api_userpro where user_id='".$data['user_id']."' and pro_id='".$data['pro_id']."'";
        $exist=  $this->userpro_model->query_one($str);
        if (count($exist))
        {
            showmessage("已经购买!续费即可再用",'admin/userpro/index/'.$data['user_id']);
            exit();
        }
        else
        {
            $bool=$this->userpro_model->add($data);
           if ($bool)
           {
               $charge['api_userid']=$data['user_id'];
               $charge['api_upid']=$data['pro_id'];
               $charge['count']=$data['all_count'];
               $this->recharge_model->add($charge);
               showmessage("操作成功",'admin/userpro/index/'.$data['user_id']);
               exit();
              }
        }

    }
    /**
     *充值
     */
    public function  recharge($id)
    {
        $userpro=$this->userpro_model->getsta($id);
        $this->load->model('Product_model','product_model');
        $userid=$userpro->user_id;
        $pro_id=$userpro->pro_id;
        $data['user']=$this->user_model->getsta($userid);
        $data['product']=$this->product_model->getsta($pro_id);
        $data['api_upid']=$id;
        $this->load->view('admin/buy/edit.html',$data);
    }
    /**
     *充值更新
     */
    public function  update($id)
    {
          $data=$this->input->post();
          //总量累加
        $this->load->model('Recharge_model','recharge_model');
        $bool=$this->recharge_model->add($data);
        if ($bool)
       {
           $time=date('Y-m-d h:i:s');
           $str="update api_userpro set all_count= all_count+".$data['count'].",update_time='".$time."' where id='".$data['api_upid']."'";
           $upid=$this->userpro_model->update_data($str);
         //  echo "操作成功";//
           showmessage("操作成功",'admin/userpro/recharge/'.$id);
           exit();
        }
    }
    /**
     * @param $id 用户id
     * @return 返回该用户所购买的所有接口
     */
    public function userpro($id,$limit='')
    {
        $sql='select a.*,b.pro_name,b.api_name,b.isable as pro_isable FROM api_userpro a LEFT JOIN api_product b ON a.pro_id=b.id where user_id='.$id;
        $this->userpro_model->querylist($sql);
        if ($limit!='')

        return  $this->userpro_model->querylist($sql);
    }

    /**
     *
     * 设置接口启用/禁用状态
     */
    public function setstatus($id)
    {
        $bool = $this->userpro_model->setstatus($id);
        echo $bool;
    }
}
