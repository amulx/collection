<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
	<title>
		amu
	</title>
</head>
<body>
<a href="add.php">添加博客</a>
<hr>
<form action="" method="get">
<input type="text" name="keys">
<input type="submit" value="搜索" name="subs">
</form>
<hr>
<?php 
	require_once("conn.php");

	if (!empty($_GET['keys'])) {
		$w = " title like '%".$_GET['keys']."%'";  
	}else{
		$w = 1;
	}
	$sql ="select * from amu where $w limit 5";
	$query = mysql_query($sql);

	while ( $rs = mysql_fetch_array($query)) {
		
?>
<h2>标题：<?php echo $rs['title'] ?> &nbsp;&nbsp;|<a href="edit.php?id=<?php echo $rs['Id'] ?>">编辑</a>|<a href="del.php?id=<?php echo $rs['Id'] ?>">删除</a>|</h2>
<a href="details.php?id=<?php echo $rs['Id']?>">		<li><?php echo $rs['dates'] ?></li>
		<p><?php echo iconv_substr($rs['contents'], 0,5,"utf-8")?>......</p>
</a>
		<hr>
<?php

}
?>
</body>
</html>