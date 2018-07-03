<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  User_model extends CI_Model
{
//添加一个用户
public function add($post)
{
    $data = array(
        'username' => $post['name'],
        'password' => md5($post['password']),
        'realname' => $post['realname'],
        'appkey' => $this->addkeys(),
        'create_time' => date('Y-m-d h:i:s'),
        'update_time' => date('Y-m-d h:i:s')

    );
    return $this->db->insert('manager', $data);
}
public  function  addkeys()
{
    return md5("ddd");
}

//获取所有用户
public function getall()
{
    return $this->db->get('user');
}

//根据id获取用户
public function getsta($id)
{
    $res = $this->db->get_where('user', array('id' => $id));
    return $res->row();
}

//根据账号取回用户
public function getuserbyname($name)
{
    $query = $this->db->get_where('user', array('username' => $name));
    return $query->row();
}

//停用启用某用户
public function setstatus($id)
{
    $status = $this->getsta($id)->status;
    $this->db->where('id', $id);
    if ($status) {
        $data = array('isable' => 0);
        $bool = $this->db->update('user', $data);
    } else {
        $data = array('isable' => 1);
        $bool = $this->db->update('user', $data);
    }
    return $bool;
}

//更新用户登录时间
    public function replacelogtime($id){
        $time = date("Y-m-d h:i:s",time());
        $this->db->where('id', $id);
        $this->db->update('manager', array('update_time'=>$time));
    }
}
?>