<?php
	require_once("conn.php");
	if(!empty($_POST)){
		$id = $_POST['id'];
		$title = $_POST['title'];
		$contents = $_POST['con'];
		$dates = date("Y-m-d");
		header("Content-type:text/html;charset=utf-8");

		$sql = "update amu set title = '$title' , contents = '$contents', dates = '$dates' where id = '$id' ";
		// echo $sql;
		mysql_query($sql);
		mysql_close();
		header("Content-type:text/html;charset=utf-8");
		echo "<script>alert('更新成功');window.location.href='listboke.php';</script>";
	}

?>