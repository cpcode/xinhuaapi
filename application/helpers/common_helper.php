<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('showmessage')){
    //跳转

    function showmessage($message='',$url='',$timeout='3',$iserror = 0,$params = '' ){
        if($iserror == 1 ){//错误
            include APPPATH.'/views/errors/showerror.html';
        }else{
            include APPPATH.'/views/errors/show.html';
        }
        die();
    }
}

?>