<?php

if(isset($_POST['submit'])){
	$extname = strrchr($_FILES['photo']['name'], '.');//取文件扩展名
	$filename = time().$extname;//生成新文件名称
	copy($_FILES['photo']['tmp_name'],'upload/'.$filename);
	//将js语句输出到iframe中，在iframe中调用函数
	echo  "<script>parent.callback('$filename');</script>";
}

?>