<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<title>新建网页</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<script type="text/javascript">
	function shuaxin(){
    		window.location.reload();
	}
</script>
<?php
	require_once('../upyun.class.php');
	$upyun = new UpYun("chenam", "chenamu", "12345678lx");
	if(!empty($_POST['sub'])){

		move_uploaded_file($_FILES['up']['tmp_name'],$_FILES['up']['name']);
		$fh = fopen($_FILES['up']['name'], 'r');
    	$upyun->writeFile("/".$_FILES['up']['name'], $fh,True);   // 上传图片，自动创建目录
    	fclose($fh);
        unlink($_FILES['up']['name']);
	}
?>
    <body>
    	<form action="" method="post" enctype="multipart/form-data">
    		<input type="file" name="up">
    		<input type="submit" name="sub" value="上传">
    	</form>
    <?php
    	$dirimage = $upyun->readDir('/');

    	foreach ($dirimage as $value) {

    	if ($value['type']=="folder") {
    		continue;
    	}
    ?>
    <img src="http://chenam.b0.upaiyun.com/<?php echo $value['name'] ?>" style="  height:200px;
  width:200px;">

    <?php 
    	}
    ?>
    </body>
</html>