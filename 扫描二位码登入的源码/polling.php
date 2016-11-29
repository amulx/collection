<?php 
	require 'conn.php';
	$randnumber = $_GET['randnumber'];
	$result = mysql_query("select * from jike where randnumber = '$randnumber' ");
	$row = mysql_fetch_array($result);
	if($row['username'] != ""){
		echo "true";
	}else{
		echo "false";
	}



?>