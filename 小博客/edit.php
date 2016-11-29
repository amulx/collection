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

	if($_GET['id']){
		$id = $_GET['id'];
		$sql = "select * from amu where id = '$id'";
		$query = mysql_query($sql);
		$rs = mysql_fetch_array($query);
		// print_r($rs);
		// exit;
	}
?>

<form action="editAction.php" method="post">
	<input hidden="hidden" type="text" name="id" value="<?php echo $rs['Id']?>" ><br>
	标题<input type="text" name="title" value="<?php echo $rs['title'] ?>"  ><br>
	内容<textarea rows="5" cols="50" name="con"><?php echo $rs['contents']?></textarea><br>
	<input type="submit" name="sub" value="提交">
</form>
</body>
</html>