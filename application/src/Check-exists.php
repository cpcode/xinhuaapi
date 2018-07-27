<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$targetFolder = '/uploads'; // Relative to the root and should match the upload folder in the uploader script
$filepath=BASEPATH . $targetFolder . '/';
if(!empty($_POST['filename']))$filepath=$filepath. $_POST['filename'];
if (file_exists($filepath)) {
	echo 1;
} else {
			$res=mkdir(iconv("UTF-8", "utf-8", $filepath),0777,true); 
		if ($res){
			echo "$filepath";
		}else{
			echo "目录 $filepath 创建失败";
		}
}
?>