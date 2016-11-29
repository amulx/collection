<?php

	// mysql_connect();//链接数据库 数据库地址 用户名 密码
	// mysql_select_db(database_name);  //选择数据库名
	// mysql_set_charset(charset);//php5.2.3以后的函数 编码设置
	// mysql_query("set names 'utf8'");//设置编码

	// mysql_query(SQL语句或命令);//SQL命令或语句执行函数，返回资源类型 二进制
	// mysql_fetch_array(result);//处理有效资源型数据，返回数组类型 返回小标和键名数组
	// mysql_fetch_array(资源数据);//返回下标数组
	// mysql_fetch_object(result);//返回对象形式调用

	// mysql_num_rows(result);//获取数据资源中的数据条数，返回Int型
	// mysql_close(数据资源);



	@mysql_connect("localhost:3306","root","root")or die("mysql链接失败");//用@进行屏蔽 or die 当前面执行不成功时执行or die语句
	@mysql_select_db("news")or die("db链接失败");
	mysql_set_charset("utf8");
?>