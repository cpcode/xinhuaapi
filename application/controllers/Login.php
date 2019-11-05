<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->model('User_model','user_model');
        $this->load->library('form_validation');
    }
	public function index()
    {
        $uid= $this->session->uid;
        if($uid){
            redirect('web/file/index');
        }else
        $this->load->view('web/login.html');
    }
    //登录表单验证
    public function cklogin()
    {
        $username = $this->input->post('username');
       $postpassword= $this->input->post('password');

       if ($username==''||$postpassword==''){
           $data['alert']="用户名和密码均不能为空";
           $this->load->view('web/login.html', $data);
           return false;
       }
        $password = md5($postpassword);
        $res = $this->user_model->getuserbyname($username);
        if ($res) {
            if ($password != $res->password) {
                $data['alert'] = "用户名或密码错误！";
                $this->load->view('web/login.html', $data);
                return false;
            } else {
                $this->session->username = $username;
                $this->session->uid = $res->id;
                $this->session->realname = $res->realname;

                $cookie = array(
                    'name' => 'username',
                    'value' => $res->username,
                    'expire' => '80000'
                );
                $this->input->set_cookie($cookie);
                $cookie2 = array(
                    'name' => 'uid',
                    'value' => $res->id,
                    'expire' => '80000'
                );
                $this->input->set_cookie($cookie2);
                $this->user_model->replacelogtime($res->id);
                redirect('web/api/index');
            }
        } else {
            $data['alert'] = "用户名或密码错误！";
            $this->load->view('web/login.html', $data);
            return false;
        }

    }

    //退出登录
    public function loginout(){
        $this->input->set_cookie('username','');
        $this->input->set_cookie('uid','');
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('uid');
        redirect('login');
    }

}
