<?php

	@mysql_connect("localhost:3306","root","root")or die("库连接失败");
	@mysql_select_db("news");
	mysql_query("set names utf8");

	$sql = "select * from jike";

	$query = mysql_query($sql);

	$num = mysql_num_rows($query);

	$str = '<root>';
	for ($i=0; $i < $num; $i++) { 
		$row = mysql_fetch_array($query);
		$str .=	'<goods>';
		$str .= '<id>'.$row['Id'].'</id>';
		$str .= '<randnum>'.$row['randnumber'].'</randnum>';
		$str .= '<username>'.$row['username'].'</username>';
		$str .= '</goods>';
	}
	$str .= '</root>';
	header('Content-type:text/xml');
	echo $str;

?>