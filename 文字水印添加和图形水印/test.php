<?php
	require "image.class.php";
	$src = "001.jpg";
	// $image = new Image($src);
	// $image->thumb(400,300);
	// $image->show();

	$src_Mark = "002.jpg";
	$local = array('x' => 20, 'y'=>30);
	$alpha = 30;
	$image = new Image($src);
	$image -> imageMark($src_Mark,$local,$alpha);
	$image -> show();




	// // ($content,$font_url,$size,$color,$local,$angle)
	// $content = "hell world";
	// $font_url = "msyh.ttf";
	// $color = array(
	// 	0 =>255 ,
	// 	1 =>255,
	// 	2 =>255,
	// 	3 => 20
	// );
	// $local = array(
	// 	'x' => 20, 
	// 	'y' => 30
	// );
	// $size = 20;
	// $angle = 0;
	// $image = new Image($src);
	// $image -> fontMark($content,$font_url,$size,$color,$local,$angle);
	// $image -> show();

?>