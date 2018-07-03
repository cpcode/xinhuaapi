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
        $userid = $this->session->userid;
        if(!$userid){
            redirect('login');
        }
    }
     function permitions()
    {

    }

}
?>