<?php
	require_once("conn.php");
	$id = $_GET['id'];
	// echo $id;
	$sql = "delete from amu where id = '$id'";
	mysql_query($sql);
	echo "success";
?>