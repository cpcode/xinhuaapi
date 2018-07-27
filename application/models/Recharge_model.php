<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  Recharge_model extends CI_Model
{
//充值
public function add($post)
{
    $data = array(
        'api_userid' => $post['api_userid'],
        'api_proid' => $post['api_proid'],
        'count' => $post['count'],
        'create_time' => date('Y-m-d h:i:s'),
        'update_time' => date('Y-m-d h:i:s')
    );
    return $this->db->insert('recharge', $data);
}

//获取所有充值记录
public function getall()
{
    $this->db->order_by('id','desc');
    return $this->db->get('recharge');
}

//根据指定充值记录
public function getsta($id)
{
    $res = $this->db->get_where('recharge', array('id' => $id));
    return $res->row();
}



}
?>