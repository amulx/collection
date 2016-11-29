<?php
//获得图片的缩略图
/*打开图片*/
	//设置图片路径
	$src = "001.jpg";
	//获取图片信息
	$info = getimagesize($src);
	//获取图片类型
	$type = image_type_to_extension($info[2],false);
	//创建与原图片类型一样的图片
	$fun = "imagecreatefrom{$type}";
	//把图片放到内存中
	$image = $fun($src);


/*操作图片*/
	//在内存中建立一个宽300，高200的真色彩图片
	$image_thumb = imagecreatetruecolor(300, 200);
	//将原图复制到新创建的真色彩图片上（按一定比例缩小）
	imagecopyresampled($image_thumb, $image, 0, 0, 0, 0, 300, 200, $info[0], $info[1]);
	//销毁原始图片
	imagedestroy($image);

/*输出图片*/
	header("Content_type;" .$info['mime']);
	$func = "image{$type}";
	$func($image_thumb);
	$func($image_thumb,"thumbimage.".$type);




/*销毁图片*/
imagedestroy($image_thumb);


?>