<?php
    header("content-type:text/html;charset=utf-8");

	include 'UpLoadFile.class.php';
	include './Zoom.class.php';
	include  './config.php';
	//文本框的name 要传过去的目录年月日
	$p1 = new UpLoadFile('file','./uploads/'.date('Y').'/'.date('m').'/'.date('d').'/');

	$p1->SetSize('10000000000',array('jpg','jpeg','png','gif'));
	
	if($filname=$p1->up()){
		//define ('PATH',str_replace('\\','/',dirname( __FILE__)).'/');
	
		//拼接完整的img_path
		$img_path =PATH.'uploads/';
		$img_path .=substr($filname,0,4).'/';
		$img_path .=substr($filname,4,2).'/';
		$img_path .=substr($filname,6,2).'/';
		$img_path .=$filname;
		$z2 =new Zoom($img_path,array(array('width'=>400,'height'=>400),array('width'=>600,'height'=>600),array('width'=>1000,'height'=>1000)));
		$result=$z2->zoom();
		if($result){
			echo '缩放成功';
		}else{
			echo '缩放失败';exit;
		}	
		echo '上传成功';
	}else{
		echo $p1->GetErrorInfo();
	}

  

