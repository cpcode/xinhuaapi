<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  User_model extends CI_Model
{
//添加一个用户
public function add($post)
{
    $data = array(
        'username' => $post['username'],
        'password' => md5($post['password']),
        'realname' => $post['realname'],
        'appkey' => $this->addkeys(),
        'public_key'=>$this->valcode(),
        'pid'=>$post['pid'],
        'create_time' => date('Y-m-d h:i:s'),
        'update_time' => date('Y-m-d h:i:s')
    );
    return $this->db->insert('user', $data);
}

public function  valcode()
{
    $code=uniqid();
    return 'xhsj'.$code;
}
/*获取uuid*/
public  function  addkeys()
{
    $data=$this->db->query("select replace(uuid(), '-', '') AS uuid")->row_array();
    return $data['uuid'];
}
    /**/
    public  function  getlist($where=null,$limit=null,$offset=null)
    {
         return $this->db->get_where('user',$where,$limit,$offset);
    }
//获取所有用户 //获取所有接口
    public function getall($limit=null,$offset=null)
    {
        if (!is_null($limit)&&!is_null($offset)) return $this->db->get('user',$limit,$offset);
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
    $status = $this->getsta($id)->isable;
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
    //更新用户
    public function update($post){
        $data = array(
            'username' =>	$post['username'],
            'realname' =>	$post['realname'],
             'isable' =>	$post['isable'],
            'update_time'=>date("Y-m-d h:i:s")
        );
         if (isset($post['password'])&&$post['password']!='')
             $data['password']=$post['password'];
        $this->db->where('id',$post['id']);
        return $this->db->update('user', $data);
    }

//更新用户登录时间
    public function replacelogtime($id){
        $time = date("Y-m-d h:i:s",time());
        $this->db->where('id', $id);
        $this->db->update('user', array('update_time'=>$time));
    }

    //查询返回的结果
    function query_count($strwhere=''){
        $sql="select count(1) from api_user";
        if ($strwhere!='')$sql=$sql.$strwhere;
        $query = $this->db->query($sql);
        $num_array = $query->result_array();
        $num = 0 ;
        if(isset($num_array[0]) && !empty($num_array[0])){
            foreach ($num_array[0] as $k=>$v){
                $num = $v ;
                break;
            }
        }
        return $num ;
    }
}
?>