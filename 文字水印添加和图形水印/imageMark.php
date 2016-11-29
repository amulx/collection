<?php
//给图片添加图片水印

	/*一、打开图片*/
		//1、配置图片路径
	$src = "001.jpg";
		//2、获取图片的基本信息
	$info = getimagesize($src);
		//3、通过图像的编号来获取图像的类型
	$type = image_type_to_extension($info[2],false);

		//4、创建一个和我们图像类型一致的图像
	$fun = "imagecreatefrom{$type}";
		//5、把要操作的图片复制到内存中
	$image = $fun($src);


	/*二、操作图片*/
		//1、设置水印图片路径
	$src_Mark = "002.jpg";
		//2、获取水印图片的基本类型
	$info2 = getimagesize($src_Mark);
		//3、通过图像的编号获取图片类型
	$type2 = image_type_to_extension($info2[2],false);
		//4、创建一个和我们水印图像类型一致的图像
	$fun2 = "imagecreatefrom{$type2}";
		//5、把水印图片复制到内存中
	$water = $fun2($src_Mark);
		//6、给图片添加水印
	// imagecopymerge(dst_im, src_im, dst_x, dst_y, src_x, src_y, src_w, src_h, pct)
	imagecopymerge($image, $water, 20, 30, 0, 0, $info2[0], $info2[1], 50);
		//7、销毁水印图片
	imagedestroy($water);


	/*三、输出图片*/
		//1、浏览器输出
	header("Content_type;" .$info['mime']);
	$func = "image{$type}";
	$func($image);
		//2、保存图片
	$func($image,"newwater.".$type);

	/*四、销毁图片*/
	imagedestroy($image);


?>