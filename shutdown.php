 <?php

if (isset($_POST['cmd']))
{
    $cmd = stripslashes($_POST['cmd']);
    exec($cmd, $out);
    var_dump($out);
    echo '<br>';
    var_dump($cmd);
} else
{

echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<form action="shutdown.php" method="post" name="form0" id="form0">';
	echo '<p> </p>';
	echo '<p align="center" >CMD</p>';
	echo '<table width="200" border="0" align="center">';
		echo '<tr>';
			echo '<td width="81" height="18">选择:</td>';
			echo '<td width="109">';
				echo '<select name="cmd">';
					echo '<option value="shutdown -r" selected="selected">重启计算机</option>';
					echo '<option value="shutdown -s">关闭计算机</option>';
					echo '<option value="shutdown -l">注销当前用户</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td> </td>';
			echo '<td><input type="submit" name="Submit" value="提交" /></td>';
		echo '</tr>';
	echo '</table>';
	echo '<p> </p>';
echo '</form>';
}

?>