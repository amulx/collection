<?php
header("Cache-Control: no-cache, must-revalidate");
	if (!empty($_GET['username'])) {
		$username = $_GET['username'];
	}else{
		$username = 1;
	}
	
	
	@mysql_connect("localhost:3306","root","root");
	@mysql_select_db("news");
	mysql_query("set names uft8");

	$sql = "select * from users where username='$username'";

	// echo $sql;
	$query = mysql_query($sql);

	$num = mysql_num_rows($query);

	mysql_close();
// 1用户名可用  2 不可用
	if ($num>0) {
		echo 1;
	}else{
		echo 2;
	}

?>