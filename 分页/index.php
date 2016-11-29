<?php
	header("Content-Type:text/html;charset=utf-8"); //设置页面编码
	require_once("conn.php");	//引入数据库链接文件

	if (!empty($_GET['page'])) {
		$page = $_GET['page'];  //获取page参数
	}else{
		$page = 1;
	}
	
	$showpage = 5;
	$sql = "select * from lixin LIMIT ".($page-1)*3;
	$sql .= " ,3";
	$query = mysql_query($sql);
	echo "<table border=1 cellspacing=0 width=40%>";
	echo "<tr><td>id</td><td>names</td><tr>";
	while($rs = mysql_fetch_array($query)){
		echo "<tr>";
			echo "<td>{$rs['id']}</td>";
			echo "<td>{$rs['name']}</td>";
		echo "</tr>";
		echo "<br>";
	}
	echo "</table>";

	$sqltotal = "select count(*) from lixin";
	$querytotal = mysql_query($sqltotal);
	$totalcount = mysql_fetch_array($querytotal);
	// print_r($totalcount);
	$total = $totalcount[0];
	$total_pages = ceil($total/3);
	// print_r($total_pages);




	$url = $_SERVER["PHP_SELF"];
	if($page > 1){
		$page1 = $page-1;
		$url .= "?page=$page1";
		echo $page_banner = "<a href=".$url.">上一页</a>";
	}




	$pageoffset =($showpage-1)/2;
	//初始化数据
	$start = 1;
	$end = $total_pages;
	if($total_pages>$showpage){
		if($page > $pageoffset+1){
			echo $page_sheng = "...";
		}
		if($page > $pageoffset){
			$start = $page - $pageoffset;
			$end = $total_pages > $page + $pageoffset ? $page + $pageoffset : $total_pages;
		}else{
			$start = 1;
			$end = $total_pages > $showpage?showpage:$total_pages;
		}
		if($page + $pageoffset > $total_pages){
			$start = $start-($page-$pageoffset-$end);
		}
	}


	for($i= $start;$i<=$end;$i++){
		$urloff = $_SERVER["PHP_SELF"];
		$urloff .= "?page=$i";
		echo $pagenum = "<a href=".$urloff.">$i</a>";
	}

	echo " ";
	$url1 = $_SERVER["PHP_SELF"];
	if ($page < $total_pages) {
		$page2 = $page + 1 ;
		$url1 .= "?page=$page2";
		echo $page_banner1 = "<a href=".$url1.">下一页</a>";
	}
	echo "  共".$total_pages."页";
	echo "<br>";
	echo date("Y-m-d H:i:s",time());
?>