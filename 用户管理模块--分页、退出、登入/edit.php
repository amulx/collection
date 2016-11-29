<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<title>新建网页</title>
    	<?php
		if (!empty($_COOKIE["username"]))
  			echo "Welcome " . $_COOKIE["username"] . "!<br />";
				else
  			echo "<script>location='login.php'</script>";
		?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="布尔教育 http://www.itbool.com" />
<style>
	#button{
		text-align: center;
	}
</style>
</head>
    <body>
    <?php
		require_once('config.php');

		header("Content-type:text/html;chartset=utf-8");

		$id = $_GET['id'];

		$sql = "select * from lixin where id = $id";

		$query = mysql_query($sql);

		$rs = mysql_fetch_assoc($query);
	
	?>
	<table align="center">
		<center><h1>编辑用户</h1></center>
		<hr />
		<form action="editAction.php" method="post">
			<input type="hidden" name="id" value=<?php echo $rs['id']?> />
			<tr>
				<td>用户名:</td>
				<td><input type="text" name="username" value="<?php echo $rs['username']?>"/></td>
			</tr>
			<tr>
				<td>密&nbsp;&nbsp;&nbsp;&nbsp;码:</td>
				<td><input type="text" name="password" value="<?php echo $rs['password']?>" /></td>
			</tr>
			<tr>
				<td>注册期:</td>
				<td><input type="text" name="time" value="<?php echo $rs['time']?>"/></td>
			</tr>
			<tr >
				<td id='button'><input type="submit" value='提交'/></td>
				<td id='button'><input type="reset" value='重置'/></td>
			</tr>
		</form>
	</table>
    </body>
</html>