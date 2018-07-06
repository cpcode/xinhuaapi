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
    public function getall()
    {
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
}
?>