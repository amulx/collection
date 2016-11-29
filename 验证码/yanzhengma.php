<?php
	session_start();
	// 1、准备画布资源
	$im = imagecreatetruecolor(100, 50);

	// 2、准备涂料
	$black = imagecolorallocate($im, 0, 0, 0);
	// $red = imagecolorallocate($im, 255, 0, 0);
	$gray = imagecolorallocate($im, 200, 200, 200);

	// 3、背景填充
	imagefill($im, 0, 0, $gray);

	$x = (100-20*4)/2;
	$y = (50-20)/2+20;

	// 4、准备文字
	@$strarr = array_merge(range(0,9),range(a,z),range(A,Z));
	shuffle($strarr);
	$str = join('',array_slice($strarr, 0,4));

	$_SESSION['codestr'] = $str;
	// echo $str;
	// exit;
	$file = "font/msyh.ttf";
	imagettftext($im, 20, 0, $x, $y, $black, $file, $str);

	// 5、输出最终图像或保存最终图像
	header("Content-type:image/png");
	imagepng($im);

	// 6、释放画布资源
	imagedestroy($im);





?>