<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  Manager_model extends CI_Model
{
//添加一个用户
public function add($post)
{
    $data = array(
        'password' => md5($post['password']),
        'username' => $post['name'],
        'realname' => $post['realname'],
    );
    return $this->db->insert('manager', $data);
}

//获取所有用户
public function getall()
{
    return $this->db->get('manager');
}

//根据id获取用户
public function getsta($id)
{
    $res = $this->db->get_where('manager', array('id' => $id));
    return $res->row();
}

//根据账号取回用户
public function getuserbyname($name)
{
    $query = $this->db->get_where('manager', array('username' => $name));
    return $query->row();
}

//停用启用某用户
public function setstatus($id)
{
    $status = $this->getsta($id)->isable;
    $this->db->where('id', $id);
    if ($status) {
        $data = array('isable' => 0);
        $bool = $this->db->update('manager', $data);
    } else {
        $data = array('isable' => 1);
        $bool = $this->db->update('manager', $data);
    }
    return $bool;
}

//更新用户登录时间
    public function replacelogtime($id){
        $time = date("Y-m-d h:i:s",time());
        $this->db->where('id', $id);
        $this->db->update('manager', array('lastlogintime'=>$time));
    }
}
?>