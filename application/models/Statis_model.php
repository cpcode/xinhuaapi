<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  Statis_model extends CI_Model
{
//添加一个源接口
    public function add($post)
    {
        $data = array(
            'all_name' => $post['all_name'],
            'api_name' => $post['api_name'],
            'create_time' => date('Y-m-d h:i:s'),
            'update_time' => date('Y-m-d h:i:s')
        );
        return $this->db->insert('statis', $data);
    }
    //获取所有接口
    public function getall($limit=null,$offset=null)
    {
        if (!is_null($limit)&&!is_null($offset)) return $this->db->get('statis',$limit,$offset);
        return $this->db->get('statis');
    }

//根据id获取接口
    public function getsta($id)
    {
        $res = $this->db->get_where('statis', array('id' => $id));
        return $res->row();
    }

    //更新接口
    public function update($post){
        $data = array(
            'all_name' =>	$post['all_name'],
            'api_name' =>	$post['api_name'],
            'update_time'=>date("Y-m-d h:i:s")
        );
        $this->db->where('id',$post['id']);
        return $this->db->update('statis', $data);
    }

    /**
     * @param $post
     * 删除记录
     */
    public function delete($id){

        $this->db->where('id',$id);
        return $this->db->delete('statis');
    }

    //查询返回的结果
    function query_count($strwhere=''){
        $sql="select count(1) from api_statis";
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