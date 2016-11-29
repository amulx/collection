<?php
	require_once('config.php');
	header("Content-type:text/html;charset=utf-8");

	$username = $_POST['username'];
	$password = $_POST['password'];
	$time = $_POST['time'];
	$id = $_POST['id'];
	$time = time();

	$sql = "update lixin set username='$username',password='$password',time='$time' where id='$id'";
	$query = mysql_query($sql);

	if ($query) {
		echo "<script>alert('success')</script>";		
	}else{
		echo "failure";
	}
	echo "<center><a href='index.php'>返回</a></center>";


?>