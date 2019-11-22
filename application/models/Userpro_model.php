<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  Userpro_model extends CI_Model
{
//添加一个接口
public function add($post)
{
    $data = array(
        'user_id' => $post['user_id'],
        'pro_id' => $post['pro_id'],
        'isable' =>isset($post['isable'])?$post['isable']:1,
        'used_count' => isset($post['used_count'])?$post['used_count']:0,
        'charge_count' => isset($post['charge_count'])?$post['charge_count']:0,
        'all_count' => $post['all_count'],
        'create_time' => date('Y-m-d h:i:s'),
        'update_time' => date('Y-m-d h:i:s')
    );
    return $this->db->insert('userpro', $data);
}

//获取所有接口
public function getall()
{
    return $this->db->get('userpro');
}

//获取用户可用接口
public function getendableapi($uid){
    $sql = "SELECT api_userpro.id,charge_count,all_count,pro_name,api_name from api_userpro LEFT JOIN api_product on api_userpro.pro_id = api_product.id where api_userpro.user_id = ". $uid ." and api_userpro.isable = 1 and api_product.isable = 1";
    $query = $this->db->query($sql);
    return $query->result();
}

//根据id获取接口
public function getsta($id)
{
    $res = $this->db->get_where('userpro', array('id' => $id));
    return $res->row();
}

//停用启用接口
public function setstatus($id)
{
    $status = $this->getsta($id)->isable;
    $this->db->where('id', $id);
    if ($status) {
        $data = array('isable' => 0);
        $bool = $this->db->update('userpro', $data);
    } else {
        $data = array('isable' => 1);
        $bool = $this->db->update('userpro', $data);
    }
    return $bool;
}
    //更新接口
    public function update($post){
        $data = array(
            'isable' => $post['isable'],
            'used_count' => $post['used_count'],
            'charge_count' => $post['charge_count'],
            'all_count' => $post['all_count'],
            'update_time'=>date("Y-m-d h:i:s")
        );
        $this->db->where('id',$post['id']);
        return $this->db->update('product', $data);
    }

    //查询1条数据，返回结果
    function query_one($sql){
        return $this->db->query($sql)->row_array();
    }

    //查询list data
    function querylist($sql){
        $result =array();
        $query = $this->db->query($sql);
       if($query){
            foreach($query->result_array() as $row){
                $result[] = $row ;
            }
        }
        return $result ;
    }
    //修改数据
    function update_data($sql){

        $query = $this->db->query($sql);
        return $this->db->affected_rows(); //返回影响的行数
    }

    //获取条数
    function getusage($uid,$starttime = null,$endtime = null){
        $sql = "SELECT api_order.pro_id,api_product.pro_name,count(api_order.id) FROM `api_order` LEFT JOIN api_product on api_order.pro_id = api_product.id where api_order.user_id = ".$uid."  and api_order.ischarge = 1";
        if($starttime){
            $sql .= " and api_order.create_time >= '".$starttime."'";
        }
        if ($endtime){
            $sql .= " and api_order.create_time < '".$endtime."'";
        }
        $sql .=" GROUP BY api_order.pro_id";

        echo $sql;
        $query = $this->db->query($sql);
        return $query->result();
    }

}
?>