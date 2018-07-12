<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  Permissions_model extends CI_Model
{
//添加一个用户
public function add($post)
{
    $data = array(
        'name' => $post['name'],
        'link' =>$post['link'],
        'pid' => $post['pid'],
        'description' => $post['description'],
        'icon' => $post['icon'],
        'dtime' => date('Y-m-d h:i:s')
    );
    return $this->db->insert('permissions', $data);
}
   //获取所有记录
    public function getall($limit=null,$offset=null)
    {
        if (!is_null($limit)&&!is_null($offset)) return $this->db->get('permissions',$limit,$offset);
        return $this->db->get('permissions');
    }

//根据id获取记录
public function getsta($id)
{
    $res = $this->db->get_where('permissions', array('id' => $id));
    return $res->row();
}
    //查询返回的结果
    function query_count($strwhere=''){
        $sql="select count(1) from api_permissions";
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