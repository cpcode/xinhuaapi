<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  Product_model extends CI_Model
{
//添加一个接口
public function add($post)
{
    $data = array(
        'pro_name' => $post['pro_name'],
        'api_name' => $post['api_name'],
        'isable' => $post['isable'],
        'create_time' => date('Y-m-d h:i:s'),
        'update_time' => date('Y-m-d h:i:s')
    );
    return $this->db->insert('product', $data);
}

//获取所有接口
public function getall($limit=null,$offset=null)
{
    if (!is_null($limit)&&!is_null($offset)) return $this->db->get('product',$limit,$offset);
    return $this->db->get('product');
}

//根据id获取接口
public function getsta($id)
{
    $res = $this->db->get_where('product', array('id' => $id));
    return $res->row();
}

//停用启用接口
public function setstatus($id)
{
    $status = $this->getsta($id)->isable;
    $this->db->where('id', $id);
    if ($status) {
        $data = array('isable' => 0);
        $bool = $this->db->update('product', $data);
    } else {
        $data = array('isable' => 1);
        $bool = $this->db->update('product', $data);
    }
    return $bool;
}
    //更新接口
    public function update($post){
        $data = array(
            'pro_name' =>	$post['pro_name'],
            'api_name' =>	$post['api_name'],
            'isable' =>	$post['isable'],
            'update_time'=>date("Y-m-d h:i:s")
        );
        $this->db->where('id',$post['id']);
        return $this->db->update('product', $data);
    }
    //查询返回的结果
    function query_count($strwhere=''){
        $sql="select count(1) from api_product";
        if ($strwhere!='')$sql=$sql.$strwhere;
        $query = $this->db->query($sql);
        $num_array = $query->result_array();
        $num = 0 ;
        if(isset($num_array[0]) && !empty($num_array[0])){
            foreach ($num_array[0] as $k=>$v){
                $num = $v ;
                break ;
            }
        }
        return $num ;

    }
}
?>