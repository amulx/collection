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

	if(!empty($_POST['sub'])){
		// @mysql_connect("localhost:3306","root","root")or die("mysql链接失败");//用@进行屏蔽 or die 当前面执行不成功时执行or die语句
		// @mysql_select_db("news")or die("db链接失败");
		// mysql_set_charset("utf8");
		$title = $_POST['title'];
		$contents = $_POST['con'];
		$dates = date("Y-m-d");
		$sql = "insert into amu (title,dates,contents) values ('$title','$dates','$contents')";

		mysql_query($sql);
				mysql_close();
		// echo "添加成功";
		echo "<script language='javascript' type='text/javascript'>alert('success');</script>";

	}
?>
<form action="add.php" method="post">
	标题<input type="text" name="title"><br>
	内容<textarea rows="5" cols="50" name="con"></textarea><br>
	<input type="submit" name="sub" value="提交">
</form>
</body>
</html>