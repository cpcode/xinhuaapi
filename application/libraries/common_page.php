<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common_page
{

    /**
     * @param $total 数据总条数
     * @param int $current 当前页
     * @param $page_num 每页的数量
     */
    public function create_page($total,$current=0,$page_num=15,$base_url)
    {	$CI =& get_instance();
        $CI->load->library('pagination');//加载分页类
        $config['total_rows']=$total;
        $config['num_links'] = 5;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        //每一页显示的数据条数
        $config['per_page']=$page_num;
        $config['first_link']= '首页';
        $config['next_link']= '下一页';
        $config['prev_link']= '上一页';
        $config['last_link']= '末页';
        $config['base_url'] = site_url($base_url);
        $CI->pagination->cur_page = $current ;
        $CI->pagination->initialize($config); // 配置分页
        return $CI->pagination->create_links();

     }


}



