<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Manager_model','manager_model');
        $this->load->library('form_validation');
    }
	public function index()
    {
        $this->load->view('admin/index.html');
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
                $this->session->userid = $res->id;
                $cookie = array(
                    'name' => 'name',
                    'value' => $res->name,
                    'expire' => '80000'
                );
                $this->input->set_cookie($cookie);
                $cookie2 = array(
                    'name' => 'userid',
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
        $this->input->set_cookie('userid','');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('userid');
        redirect('login');
    }


}
