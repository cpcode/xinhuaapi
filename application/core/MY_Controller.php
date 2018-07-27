<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller{

   public  function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->check_login();
    }
//通用检测登录
    function check_login(){
        $adminid= $this->session->adminid;
        if(!$adminid){
            redirect('admin');
        }
    }
    /*左侧权限判断*/
     function permitions()
    {

    }

}

class Home_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->check_login();
    }
    //通用检测登录
    function check_login(){
        $uid= $this->session->uid;
        if(!$uid){
            redirect('login');
        }
    }
}

?>