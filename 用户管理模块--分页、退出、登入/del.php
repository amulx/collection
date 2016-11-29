<?php 
	header("Content-type:text/html;charset=utf-8");
	require_once('config.php');

	$id = $_GET['id'];

	// echo $id;
	$sql = "delete from lixin where id = $id";

	$rs = mysql_query($sql);

	if($rs){
		echo "<script>alert('删除成功')</script>";
		echo "<a href='index.php'>返回</a>";
	}else{
		echo "失败";
	}

?>