<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {


    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->model('Manager_model','manager_model');
        $this->load->library('form_validation');
    }
	public function index()
    {
        $this->load->view('admin/login.html');
    }
    //登录表单验证
    public function cklogin()
    {
        $username = $this->input->post('username');
        $password = md5($this->input->post('password'));
        $res = $this->manager_model->getuserbyname($username);
        if ($res) {
            if ($password != $res->password) {
                $data['alert'] = "账号或密码错误！";
                $this->load->view('admin/login.html', $data);
                return false;
            } else {
                $this->session->name = $username;
                $this->session->adminid = $res->id;
                $cookie = array(
                    'name' => 'name',
                    'value' => $res->username,
                    'expire' => '80000'
                );
                $this->input->set_cookie($cookie);
                $cookie2 = array(
                    'name' => 'adminid',
                    'value' => $res->id,
                    'expire' => '80000'
                );
                $this->input->set_cookie($cookie2);
                $this->manager_model->replacelogtime($res->id);
                redirect('admin/manager/index');
            }
        } else {
            $data['alert'] = "账号或密码错误！";
            $this->load->view('admin/login.html', $data);
            return false;
        }

    }

    //退出登录
    public function loginout(){
        $this->input->set_cookie('username','');
        $this->input->set_cookie('adminid','');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('adminid');
        redirect('login');
    }

}
