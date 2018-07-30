<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('File_model', 'file_model');
        $this->load->model('Common_model', 'common_model');
        $this->load->library('form_validation');
    }
public  function downloadfile($id)
{
    $data=$this->file_model->getsta($id);
    $filepath=$_SERVER['DOCUMENT_ROOT']  . $data->upload_url;
    if (file_exists($filepath))
    {
        if($this->session->uid)
        {
            //如果是前台用户下载，则更新验证文件的处理进度
            $d["admin_state"]=1;
            $d["user_state"]=2;
            $d["id"]=$id;
            $this->file_model->update($d);
        }

        $file = fopen ( $filepath , "rb" );
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
}
    /**
     *$page 为当前页
     */
    public function index($page='')
    {
        $page_num=15;
        if ($page=='')$page=0;
        $data['count']=$this->file_model->query_count();
        $query=$this->file_model->getall($page_num,$page);
        $data['list']=$query->result();
        $this->load->library('common_page');
        $data['page']=$this->common_page->create_page($data['count'],$page,$page_num,'/admin/file/index');
        $this->load->view('admin/file/list.html',$data);

    }
    public  function  store($id)
    {
        $data = $this->input->post();
        $data['admin_state']=0;
        $data['user_state']=2;
        $data['id']=$id;
        $this->file_model->update($data);
        showmessage("操作成功",'admin/file/index');
    }

    public function  upload($id)
    {
         $data['file']=$this->file_model->getsta($id);
          $this->load->view('admin/file/upload.html',$data);

    }

    public  function  uploadify()
    {
        $file=time().".log";//strtotime(date("yyMMdd"));//time().".log";
        //file_put_contents($file,"uploadify\r\n",FILE_APPEND);
        $targetFolder = '/uploads/'.$this->session->adminid;
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

}