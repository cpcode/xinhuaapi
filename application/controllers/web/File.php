<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends Home_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('File_model', 'file_model');
        $this->load->model('Common_model', 'common_model');
        $this->load->library('form_validation');
    }
    /*
     * 下载用户上传的文件
     */
public  function uploadfile($id)
{

    $data=$this->file_model->getsta($id);
    if($this->session->adminid)
    {
        //如果是管理员下载，则更新文件的处理进度
        $d["user_state"]=1;
        $d["id"]=$id;
        $this->file_model->update($d);
    }
    $file = fopen ( $_SERVER['DOCUMENT_ROOT']  . $data->upload_url , "rb" );
    Header ( "Content-type: application/octet-stream" );
    //请求范围的度量单位
    Header ( "Accept-Ranges: bytes" );
    //Content-Length是指定包含于请求或响应中数据的字节长度
    Header ( "Accept-Length: " . filesize ( $_SERVER['DOCUMENT_ROOT']  . $data->upload_url ) );
    //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
    Header ( "Content-Disposition: attachment; filename=" . $data->name );

    //读取文件内容并直接输出到浏览器
    echo fread ( $file , filesize ( $_SERVER['DOCUMENT_ROOT']  . $data->upload_url ) );
    fclose ( $file );
    exit ();
}
    /**
     *$page 为当前页
     */
    public function index($page='')
    {
        $page_num=20;
        if ($page=='')$page=0;
        $data['count']=$this->file_model->query_count();
        $query=$this->file_model->getall($page_num,$page);
        $data['list']=$query->result();
        $this->load->library('common_page');
        $data['page']=$this->common_page->create_page($data['count'],$page,$page_num,'/web/file/index');
        $this->load->view('web/user/list.html',$data);

    }
    public  function  store()
    {
        $data = $this->input->post();
        $data['user_id']=$this->session->uid;
        $data['user_state']=0;
        $this->file_model->add($data);
        redirect('web/file/upload/1');
    }

    public function  upload($alert='')
    {
        if ($alert=='1') {
            $data['alert'] = '操作成功';
            $this->load->view('web/user/upload.html',$data);
        }
        else    $this->load->view('web/user/upload.html');

    }
       /*文件上传*/
    public  function  uploadify()
    {
        $file=time().".log";//strtotime(date("yyMMdd"));//time().".log";
        //file_put_contents($file,"uploadify\r\n",FILE_APPEND);
        $targetFolder = '/uploads/'.$this->session->uid;
        $filepath=$_SERVER['DOCUMENT_ROOT'] . $targetFolder . '/';
        if(!empty($_POST['filename']))$filepath=$filepath. $_POST['filename'];
        if (!file_exists($filepath))$res=mkdir(iconv("UTF-8", "utf-8", $filepath),0777,true);
            $verifyToken = md5('unique_salt' . $_POST['timestamp']);
        //echo $verifyToken."token";
        if (!empty($_FILES) && $_POST['token'] = $verifyToken) {
            $tempFile =$_FILES['Filedata']['tmp_name']; ;//$_FILES['Filedata']['tmp_name'];
            if (!file_exists($tempFile)) {mkdir(iconv("UTF-8", "utf-8", $tempFile),0777,true);}
            $targetPath= $_SERVER['DOCUMENT_ROOT'] . $targetFolder;

            $fileParts = pathinfo($_FILES['Filedata']['name']);
            $savename=$verifyToken.'.'.$fileParts['extension'];
            // $savename=$_FILES['Filedata']['name'];
            $targetFile = rtrim($targetPath,'/') . '/'.iconv("UTF-8","gb2312",$savename);

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png','txt','doc','xml','docx'); // File extensions

            if (in_array($fileParts['extension'],$fileTypes)) {
                move_uploaded_file($tempFile,$targetFile);
                echo $targetFolder.'/'.$savename;
            } else {
                echo '格式不正确';
            }
        }
        else echo "token值不一致";
    }

    /**
     * @param $id
     * 用户提交文件后，后天未进行下载，用户可以删除重新上传
     */
    function  delete($id)
    {
        $data=$this->file_model->getsta($id);
        if($data->user_state==0)
        {
            $sql="delete from api_file where id='".$id."'";
           if ($this->common_model->del_data($sql))
           {
             echo "操作成功";
           }
        }
        else
        echo "您上传的文件已在审核中！删除请联系管理员";

    }

}