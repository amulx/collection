<?php

	@mysql_connect("localhost:3306","root","root")or die("库连接失败");
	@mysql_select_db("news");
	mysql_query("set names utf8");

	$sql = "select randnumber,username from jike";

	$query = mysql_query($sql);

	$num = mysql_num_rows($query);

	$data = array();
	for ($i=0; $i < $num; $i++) { 
		// $row = mysql_fetch_array($query);
		$row = mysql_fetch_assoc($query);
		$data[] = $row;
	}
	mysql_close();
	echo json_encode($data);


?>