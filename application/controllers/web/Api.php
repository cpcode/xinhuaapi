<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends Home_Controller
{

    public function index()
    {
        $this->load->model('Userpro_model','userpro_model');
        $userpro = $this->userpro_model->getendableapi($this->session->uid);
        $data["userpro"] = json_encode($userpro);
        $this->load->view('web/api/index.html',$data);
    }

    public function apitest($apiname){
        $this->load->model('User_model','user_model');
        $user = $this->user_model->getsta($this->session->uid);
        $data["user"] = $user;
        $key = $user->appkey;

        $datajson = file_get_contents('php://input');
        $url = "http://api.apixinhua.cn:9090/api/".$apiname;
        echo $this->getdata($url,$datajson,$key);

    }

    public function usage(){
        if (isset($_GET["starttime"])){
            $data['starttime'] = $_GET["starttime"];
        }else{
            $data['starttime'] = null;
        }
        if (isset($_GET["endtime"])){
            $data['endtime']  = $_GET["endtime"];
        }else{
            $data['endtime'] = null;
        }

        if (isset($_GET["starttime"]) || isset($_GET["endtime"])){
            $this->load->model('Userpro_model','userpro_model');
            $data['all'] = $this->userpro_model->getusage($this->session->uid,$data['starttime'],$data['endtime']);
        }else{
            $data['all'] = null;
        }
        $this->load->view('web/api/usage.html',$data);
    }

    public function getdata($url,$datajson,$key){
        try
        {
            //log_message('info',"111");
            //print_r($data_string);
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST,true);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$datajson);
            curl_setopt($ch, CURLOPT_TIMEOUT,5);
            curl_setopt($ch, CURLOPT_HTTPHEADER,array(
                'Content-Type: application/json; charset=utf-8','appkey:'.$key
            ));
            $out = curl_exec($ch);
            if(curl_errno($ch))
            {
                return "500";
            }
            curl_close($ch);
            return $out;
        }
        catch(Exception $e)
        {
            return "500";
        }
    }

}