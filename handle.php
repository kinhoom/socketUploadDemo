<?php
// var_dump($_FILES);
defined('UPLOAD') or define('UPLOAD',dirname(__FILE__).'/upload');
var_dump(UPLOAD);
if($_FILES[file][error]==0){
	$name=$_POST['name'];
	$age=$_POST['age'];
	 echo 'name is:',$name,"<br/>age is:",$age."<br/>";
	$file = $_FILES['file'];
	$ext=strrchr($file['name'],'.');
	$filename=$_SERVER['REQUEST_TIME'].$ext;
	if (move_uploaded_file($file['tmp_name'],UPLOAD.'/'.$filename)) {
        echo '<img src="upload/'.$filename.'">';
    }
}
