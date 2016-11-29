<?php

include 'config.inc.php';

if( !$image = $_GET["img"] ){
    $ret['result_code'] = 101;
    $ret['result_des'] = "图片不存在";
} else {
    $image = dirname(dirname(dirname(__FILE__))).'/' . $image;

    $info = getImageInfo( $image);
    if( !$info ){
        $ret['result_code'] = 102;
        $ret['result_des'] = "图片不存在";
    } else {
        $x = $_GET["x"];
        $y = $_GET["y"];
        $w = $_GET["w"];
        $h = $_GET["h"];
        $member_id = $_GET['member_id'];
        $width = $srcWidth = $info['width'];
        $height = $srcHeight = $info['height'];

        //处理缩放比例，缩略图边长为289
        $thumbLength = 289;
        $max = $width > $height ? $width : $height;
        if ($max > 289){
            $ratio = 0;
            if ($width > $height){
                $ratio = $width / $thumbLength;
            }
            else{
                $ratio = $height / $thumbLength;
            }
            $x *= $ratio;
            $y *= $ratio;
            $w *= $ratio;
            $h *= $ratio;
        }

        $type = empty($type)?$info['type']:$type;
        $type = strtolower($type);
        unset($info);
        // 载入原图
        $createFun = 'imagecreatefrom'.($type=='jpg'?'jpeg':$type);
        $srcImg = $createFun($image);
        //创建缩略图
        if($type!='gif' && function_exists('imagecreatetruecolor'))
            $thumbImg = imagecreatetruecolor($width, $height);
        else
            $thumbImg = imagecreate($width, $height);
        // 复制图片
        if(function_exists("imagecopyresampled"))
            imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth,$srcHeight);
        else
            imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height,  $srcWidth,$srcHeight);
        if('gif'==$type || 'png'==$type) {

            $background_color  =  imagecolorallocate($thumbImg,  0,255,0);
            imagecolortransparent($thumbImg,$background_color);
        }
        // 对jpeg图形设置隔行扫描
        if('jpg'==$type || 'jpeg'==$type)
            imageinterlace($thumbImg,1);
        // 生成图片
        //$random = '_'.rand(1000,9999);
        $imgName1 = '50';
        $imgName2 = '100';
        $imageFun = 'image'.($type=='jpg'?'jpeg':$type);
        $thumbname01 = str_replace("ori", $imgName1, $image);
        $thumbname02 = str_replace("ori", $imgName2, $image);
		$quality= 9;
		if($type != 'png' ) $quality=100;
        $imageFun($thumbImg,$thumbname01,$quality);
        $imageFun($thumbImg,$thumbname02,$quality);
        $thumbImg01 = imagecreatetruecolor(50,50);
        imagecopyresampled($thumbImg01,$thumbImg,0,0,$x,$y,50,50,$w,$h);

        $thumbImg02 = imagecreatetruecolor(100,100);
        imagecopyresampled($thumbImg02,$thumbImg,0,0,$x,$y,100,100,$w,$h);

        $imageFun($thumbImg01,$thumbname01,$quality);
        $imageFun($thumbImg02,$thumbname02,$quality);
        imagedestroy($thumbImg01);
        imagedestroy($thumbImg02);
        imagedestroy($thumbImg);
        imagedestroy($srcImg);
        $ret['result_code'] = 1;

        $rootPath = dirname(dirname(dirname(__FILE__))).'/';
        $ret['result_des'] = array(
            "small"   => str_replace($rootPath, "", $thumbname01),
            "big"=> str_replace($rootPath, "", $thumbname02),
            "ori"=> str_replace($rootPath, "", $image)
            );
    }
}

$result=json_encode($ret);
//动态执行回调函数
/*$callback=$_GET['callback'];
echo $callback."($result)";*/
echo $result;
exit();