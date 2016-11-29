<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
	<title>
		amu
	</title>
</head>
<body>
<?php
	require_once("conn.php");
	$id = $_GET['id'];
	$sql = "select * from amu where id = '$id'";
	$query = mysql_query($sql);
	$rs = mysql_fetch_array($query);

	$sqlup = "update amu set hits = hits + 1 where id = '$id'";	//博客阅读量的统计
	mysql_query($sqlup);
?>
<h1><?php echo $rs['title']?></h1>
<h2><?php echo $rs['dates']?> &nbsp;&nbsp;&nbsp;&nbsp;点击数：<?php echo $rs['hits']?></h2>
<hr>
<p><?php echo $rs['contents']?></p>
</body>
</html>