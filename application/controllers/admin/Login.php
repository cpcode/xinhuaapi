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
        $adminid= $this->session->adminid;
        if($adminid){
            redirect('admin/manager/index');
        }else
        $this->load->view('admin/login.html');
    }

    public function captcha()
    {
        $this->load->library('session');

        $this->load->helper('captcha');

        $vals = array(
            'word'         =>$this->rand_chr(5), // 这里用了自己的随机字符串库。
            'font_path'    => BASEPATH.'fonts/texb.ttf', // 定义自己的验证码字体
            'img_width'    => '120',
            'img_height'   => 34,
            'expiration'   => 300,
            'word_length'  => 8,
            'font_size'    => 16,
            'colors'       => array(
                'background' => array(255, 255, 255),
                'border'     => array(255, 255, 255),
                'text'		=> array(204,153,153),
                'grid'		=> array(255,182,182)
            )

        );
        $cap = create_captcha($vals);
        $this->session->set_userdata('code', $cap);  //如果只存验证码 到session 用$cap['word'] ，此处存进session的是数组。
       // echo $cap;
    }
    function rand_chr($length)
    {
        $str = '234578ZYACEFGHJKLMNPRSTUVW';  //自己定义喜欢的字符串
        $str = str_shuffle($str);
        return substr($str,0,$length);
    }

    //登录表单验证
    public function cklogin()
    {
        $word=strtolower($this->session->code['word']);
        $code = strtolower($this->input->post('code'));
        if ($word!=$code)
        {  $data['alert'] = "验证码错误！";
            $this->load->view('admin/login.html', $data);
            return false;}
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
        $this->input->set_cookie('name','');
        $this->input->set_cookie('adminid','');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('adminid');
        redirect('admin/login');
    }

}
