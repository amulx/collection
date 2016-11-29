<?php

//路径可以修改为自动获取
define( 'ROOT_PATH', realpath(dirname(dirname(__FILE__))).'/' );

function resize( $ori ){
    if( preg_match('/^http:\/\/[a-zA-Z0-9]+/', $ori ) ){
        return $ori;
    }
    $info = getImageInfo( ROOT_PATH . $ori );
    if( $info ){
        //上传图片后切割的最大宽度和高度
        $width = empty($_GET["w_max"]) ? $info['width'] : $_GET["w_max"];
        $height = empty($_GET["h_max"]) ? $info['height'] : $_GET["h_max"];
        $scrimg = ROOT_PATH . $ori;
        if( $info['type']=='jpg' || $info['type']=='jpeg' ){
            $im = imagecreatefromjpeg( $scrimg );
        }
        if( $info['type']=='gif' ){
            $im = imagecreatefromgif( $scrimg );
        }
        if( $info['type']=='png' ){
            $im = imagecreatefrompng( $scrimg );
        }
        if( $info['width']<=$width && $info['height']<=$height ){
            return;
        } else {
            if( $info['width'] > $info['height'] ){
                $height = intval( $info['height']/($info['width']/$width) );
            } else {
                $width = intval( $info['width']/($info['height']/$height) );
            }
        }

        $newimg = imagecreatetruecolor( $width, $height );
        imagecopyresampled( $newimg, $im, 0, 0, 0, 0, $width, $height, $info['width'], $info['height'] );
        imagejpeg( $newimg, ROOT_PATH . $ori );
        //imagedestroy( $im );

        //生成宽度为256的图片
        if(!empty($_GET['w_max'])){
            $width = 256;
            $height = 186;
            if( $info['width']<=$width && $info['height']<=$height ){
                return;
            } else {
                if( $info['width'] > $info['height'] ){
                    $height = intval( $info['height']/($info['width']/$width) );
                } else {
                    $width = intval( $info['width']/($info['height']/$height) );
                }
            }
            $position = strrpos($ori, '.');
            $oriTemp1 = substr($ori,0, $position);
            $oriTemp2 = substr($ori,$position);
            $ori = $oriTemp1.'_thumb'.$oriTemp2;
            $newimg = imagecreatetruecolor( $width, $height );
            imagecopyresampled( $newimg, $im, 0, 0, 0, 0, $width, $height, $info['width'], $info['height'] );
            imagejpeg( $newimg, ROOT_PATH . $ori );
        }
        imagedestroy( $im );
    }
    return;
}

function getImageInfo( $img ){
    $imageInfo = getimagesize($img);
    if( $imageInfo!== false) {
        $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
        $info = array(
            "width"		=>$imageInfo[0],
            "height"	=>$imageInfo[1],
            "type"		=>$imageType,
            "mime"		=>$imageInfo['mime'],
        );
        return $info;
    }else {
        return false;
    }
}