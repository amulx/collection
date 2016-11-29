<?php 


	require_once('config.php');

	if (!empty($_GET['p'])) {
		$page = $_GET['p'];
	}else{
		$page = 1;
	}
	// $page = $_GET['p']?$_GET['p']:1;  //当前页
	$sqlp = "select count(*) from lixin";
	$queryp = mysql_query($sqlp);
	$rsp = mysql_fetch_assoc($queryp);
	// print_r($rsp);

	$length = 3;	//每页显示的条数
	$totalpage = $rsp['count(*)']/$length;	//总页数
	
	$offset = ($page - 1)*$length;	//偏移量
	
	$prevpage = $page - 1;
	$nextpage = $page + 1;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<title>新建网页</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
    <body>
    	<?php
		if (!empty($_COOKIE["username"]))
  			echo "Welcome " . $_COOKIE["username"] . "!<br />";
				else
  			echo "<script>location='login.php'</script>";
		?>
    	<center>
    		<h1>查看用户|<a href="add.php">添加用户</a>|<a href="logout.php">退出</a></h1>
    		<hr>
    	<table width="500px" border="1px" cellspacing="0px">
    		<form action="">
				<tr>
					<th>ID</th>
					<th>用户名</th>
					<th>注册时间</th>
					<th>编辑</th>
					<th>删除</th>
				</tr>
				<?php 
					$sql = "select id, username, time from lixin limit $offset,$length";
					$query = mysql_query($sql);
					while ( $rs = mysql_fetch_assoc($query)) {
				?>
					<tr>
						<th><?php echo $rs['id']?></th>
						<th><?php echo $rs['username']?></th>
						<th><?php echo date('Y-m-d H:m:s',$rs['time'])?></th>
						<th><a href="edit.php?id=<?php echo $rs['id']?>">编辑</a></th>
						<th><a href="del.php?id=<?php echo $rs['id']?>">删除</a></th>
					</tr>

				<?php	
					}	
					mysql_free_result($query);	
					mysql_close();	
				?>

    		</form>

    	</table>
    	<h3><tr>
    		<?php
    		echo "
				<a href='?p=$prevpage'>上一页</a>&nbsp;<a href='?p={$nextpage}'>下一页</a>
    		";?></tr>
    		</h3>
    	</center>
    </body>
</html>