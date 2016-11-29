<?php
	header("Content-type:text/html;charset=utf-8");
	$username = $_POST['username'];
	$password = $_POST['password'];

	@mysql_connect("localhost:3306","root","root")or die("数据库连接失败");
	@mysql_select_db("news")or die("数据表连接失败");
	mysql_query("set names utf8");

	$sql = "select * from lixin where username = '{$username}' and password = '{$password}'";

	// echo $sql;
	// exit();

	$query = mysql_query($sql);

	$rs = mysql_fetch_assoc($query);

	$user = $rs['username'];
	$userid = $rs['id'];

	if ($rs) {
		setcookie('username',$user,time()+3600,'/');
		setcookie('userid',$userid,time()+3600,'/');
		echo "<script>alert('登入成功')</script>";
		echo "<script>location='index.php'</script>";
	}else{
		echo "没有";
	}
		
	
?>