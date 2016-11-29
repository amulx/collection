<?php

	@mysql_connect("localhost:3306","root","root")or die("数据库连接失败");
	mysql_select_db("news")or die("数据表连接失败");
	mysql_query("set names utf8");



?>