<?php

	@mysql_connect("localhost:3306","root","root");
	@mysql_select_db("news");
	mysql_query("set names utf8");

	$sql = "select count(*) as num from jike";
	$query = mysql_query($sql);
	$rsnum = mysql_fetch_assoc($query);
	$count = $rsnum['num']; //总行数

	$page = isset($_GET['page'])?$_GET['page']:1;
	$pageSize = 5;  //每页显示多少条数据
	$pageCount = ceil($count/$pageSize);
	$pagePre = $page-1; //上一页
	$pageNext = $page + 1 ; //下一页
// ==============================================判断页码越界
	if($pagePre<1){
		$pagePre =1;
	}
	if ($pageNext>$pageCount) {
		$pageNext = $pageCount;
	}
// =============================================
	if ($page<1) {
		$page = 1
	}
	if ($page>$pageCount) {
		$page=$pageCount;
	}

	$offset = ($page-1)*$pageSize;//偏移量

	$sql ="select * from jike order by id desc limit $offset,$pageSize";
	$result = mysql_query($sql);
	$num = mysql_num_rows($result);
	$data = array();
	for ($i=0; $i <$num ; $i++) { 
		$data[] = mysql_fetch_assoc($result);
	}

	mysql_close();
// ====================================================

?>