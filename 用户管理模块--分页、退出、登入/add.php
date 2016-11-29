<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
        <?php
        if (!empty($_COOKIE["username"]))
            echo "Welcome " . $_COOKIE["username"] . "!<br />";
                else
            echo "<script>location='login.php'</script>";
        ?>
<title>新建网页</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="布尔教育 http://www.itbool.com" />
</head>
    <body>
    	<table align='center'>
    		<center><h1>添加用户</h1></center>
			<hr>
    		<form action="addAction.php" method="post">
    			<tr>
    				<td>用户名:</td>
    				<td><input type="text" name="username"/></td>
    			</tr>
    			<tr>
    				<td>密&nbsp;&nbsp;&nbsp;&nbsp;码:</td>
    				<td><input type="text" name="password"/></td>
    			</tr>
    			<tr>
    				<td>注册期:</td>
    				<td><input type="text" name="time"/></td>
    			</tr>
    			<tr>
    				<td><input type="submit" value="提交" /></td>
    				<td><input type="reset" value="重置"/></td>
    			</tr>
    		</form>
    	</table>
    </body>
</html>