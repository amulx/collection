<?php 

	header("Content-type:text/html;charset=utf-8");
	setcookie('username','',time()-1,'/');
	setcookie('userid','',time()-1,'/');
	echo "<script>alert('退出成功')</script>";
	echo "<script>location='login.php'</script>";



?>