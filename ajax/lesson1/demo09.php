<?php

	if(isset($_POST['username'])){
		echo $username = $_POST['username'];
	}else{
		$username = 1;
	}

	if (isset($_POST['password'])) {
		$password = $_POST['password'];
	}else{
		$password = 1;
	}

	if (isset($_POST['photo'])) {
		$photo = $_POST['photo'];
	}else{
		$photo = 1;
	}
	


	@mysql_connect("localhost:3306","root","root")or die("库失败");
	@mysql_select_db("news")or die("表失败");
	mysql_query("set names utf8");

	$sql = "insert into ajax (useranme,passwoed,photo) values ('$username','$password','$photo')";
	echo $sql;

	mysql_query($sql);
	mysql_close();

	echo "ok";
?>