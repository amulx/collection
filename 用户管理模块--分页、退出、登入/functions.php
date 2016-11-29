<?php
	/**
	 *$tot总行数  $length每页显示的页数
	 */
	function page($tot,$length){
		$page = $_GET['p']?$_GET['p']:1;  //当前页
		$offset = ($page-1)*$length;  //偏移量
		$prevpage = $page-1;	//上一页

		$pages = ceil($tot/$length);//总页数

		if ($page>=$pages) {
			$nextpage = $pages;
		}else{
			$nextpage = $page + 1;
		}

		$limit = "limit {offset},{$length}";

		$show = "
		<h3>
			<a href='?p=1'>首页</a>
			<a href='?p={$prevpage}'>上一页</a>
			<a href='?p={$nextpage}'>下一页</a>
			<span>{$page}/{$pages}</span>
			<a href='?p={$pages}'>末页</a>;
		</h3>
		";
	}
?>