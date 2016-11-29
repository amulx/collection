<?php


/**
 *$filesrc是要缩略图片的具体路径
 *$dst_w  缩略图的宽
 *$dst_h  缩略图的高
 */
function suolue($filesrc,$dst_w,$dst_h){

// ============================1、打开原图片=============================================
		// 获取图片路径
	$imfile_src = $filesrc;
		// 获取图片基本信息
	$im_info = getimagesize($imfile_src);
		//获取图片类型
	$im_type = image_type_to_extension($im_info[2],false);
		//创建与原图类型一样的图片
	$fun = "imagecreatefrom{$im_type}";
	$image = $fun($imfile_src);


// ============================2、操作图片===============================================
		// 缩略图的宽高
	$dst_w = $dst_w;
	$dst_h = $dst_h;

		// 图片比例的处理
	if (($dst_w/$im_info[0])>($dst_h/$im_info[1])) {
		$bili = $dst_h/$im_info[1];
	}else{
		$bili = $dst_w/$im_info[0];
	}
		// 等比缩放的宽、高
	$dst_w = floor($im_info[0]*$bili);
	$dst_h = floor($im_info[1]*$bili);
		//创建缩放图片
	$image_thumb = imagecreatetruecolor($dst_w, $dst_h);

	imagecopyresampled($image_thumb, $image, 0, 0, 0, 0, $dst_w, $dst_h, $im_info[0], $im_info[1]);
		//销毁大图
	imagedestroy($image);
// ===========================3、输出图片==============================================
	header("Content-Type:".$im_info['mime']);
	
	$func = "image{$im_type}";
	$func($image_thumb);
	$func($image_thumb,"thumbimage.".$im_type);

// ===========================4、销毁图片 释放内存====================================

	imagedestroy($image_thumb);
}

// 函数的调用
$filesrc = "font/ceshi.jpg";
suolue($filesrc,50,20);

?>