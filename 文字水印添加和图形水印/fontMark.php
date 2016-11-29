<?php
//为图片添加文字水印
	/*打开图片*/
		//1、配置图片的路径	
	$src = "001.jpg";
		// 2、获取图片信息
	$info = getimagesize($src);
		//3、通过图像的编号来获取图像的类型
	$type = image_type_to_extension($info[2],false);   //加不加false体现在有没有.号上
		// 4、在内存中穿件一个和我们图像类型一样的图像
	$fun = "imagecreatefrom{$type}";
		// 5、把图片复制到我们的内存中
	$image = $fun($src);

	/*操作图片*/
		// 1、设置字体的路径
	$font = "msyh.ttf";
		// 2、编写的内容
	$content = "你好，慕课";
	
		// 3、字体的颜色
	// imagecolorallocatealpha(image, red, green, blue, alpha)
	$col = imagecolorallocatealpha($image, 255, 255, 255, 50);
		// 4、写入文字
	// imagettftext(image, size, angle, x, y, color, fontfile, text)
	imagettftext($image, 20, 0, 20, 30, $col, $font, $content);

	/*输出图片*/
		// 1、浏览器输出
	header("Content-type;" .$info['mime']);
	$func = "image{$type}";
	$func($image);
		// 2、保存图片
	// $func($image,"newimage.".$type);
	$func($image,"001.jpg");

	/*销毁图片*/
	imagedestroy($image);

?>