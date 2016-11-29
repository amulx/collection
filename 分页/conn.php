<?php

	@mysql_connect("localhost:3306","root","root")or die("数据库链接失败");
	@mysql_select_db("news")or die("表链接失败");
	mysql_set_charset("utf8");

?>