<?php
	require_once("conn.php");

	$sql = "insert into lixin (name) values ('name')";
	echo  $sql;
	$query = mysql_query($sql);

?>