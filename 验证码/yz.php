<?php
	header("Content-type: text/html;charset=utf-8");

	session_start();

	$codeyz = strtolower($_SESSION['codestr']);
	

	$yzm = strtolower($_POST['yzm']);

	if ($codeyz == $yzm) {
		echo "success";
	}else{
		echo "failure";
	}

?>