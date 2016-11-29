<?php
	header("Content-type:text/html;charset=utf-8");
	require_once('config.php');
	
		if (!empty($_COOKIE["username"]))
  			echo "Welcome " . $_COOKIE["username"] . "!<br />";
				else
  			echo "<script>location='login.php'</script>";
		

	$username = $_POST['username'];
	$password = $_POST['password'];
	$time = time();

	$sql = "insert into lixin (username,password,time) values ('$username','$password','$time')";
	$query = mysql_query($sql);

	if ($query) {
		echo "<script>alert('success')</script>";
	}else{
		echo "failure";
	}

	echo "<center><a href='index.php'>返回</a></center>"



?>