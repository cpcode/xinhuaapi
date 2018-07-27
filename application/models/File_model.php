<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class  File_model extends CI_Model
{
   //添加一个接口
    public function add($post)
    {
        $data = array(
            'name'=>$post['name'],
            'upload_url' => isset($post['upload_url'])?$post['upload_url']:null,
            'download_url' => isset($post['download_url'])?$post['download_url']:null,
            'user_id' => isset($post['user_id'])?$post['user_id']:null,
            'upload_time' =>  isset($post['upload_url'])?date('Y-m-d h:i:s'):null,
            'download_time' =>   isset($post['download_url'])?date('Y-m-d h:i:s'):null,
            'admin_state'=>isset($post['admin_state'])?$post['admin_state']:-1,
            'user_state'=>isset($post['user_state'])?$post['user_state']:null
        );
        return $this->db->insert('file', $data);
    }
    /**/
    public  function  getlist($where=null,$limit=null,$offset=null)
    {
        return $this->db->get_where('file',$where,$limit,$offset);
    }

    public function getall($limit=null,$offset=null)
    {
        $this->db->order_by('id','desc');
        if (!is_null($limit)&&!is_null($offset)) return $this->db->get('file',$limit,$offset);
        return $this->db->get('file');
    }

//根据id获取接口
    public function getsta($id)
    {
        $res = $this->db->get_where('file', array('id' => $id));
        return $res->row();
    }

    //更新接口
    public function update($post){
        $data = array(
            'download_time' =>   isset($post['download_url'])?date('Y-m-d h:i:s'):null
        );
        if (isset($post['upload_url']))$data['upload_url']=$post['upload_url'];
        if (isset($post['download_url']))$data['download_url']=$post['download_url'];
        if (isset($post['upload_time']))$data['upload_time']=$post['upload_time'];
        if (isset($post['user_state']))$data['user_state']=$post['user_state'];
        if (isset($post['admin_state']))$data['admin_state']=$post['admin_state'];
        $this->db->where('id',$post['id']);
        return $this->db->update('file', $data);
    }
    //查询返回的结果
    function query_count($strwhere=''){
        $sql="select count(1) from api_file";
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