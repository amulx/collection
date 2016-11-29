<?php

include 'config.inc.php';

if (!empty($_FILES)) {
    $uid = intval( $_REQUEST['uid'] );
    $ext = pathinfo($_FILES['Filedata']['name']);
    $ext = strtolower($ext['extension']);
    $tempFile = $_FILES['Filedata']['tmp_name'];
    $year = date('Y');
    $mouth = date('md');
    $targetPath   = ROOT_PATH . 'uploads/'.$year.'/'.$mouth.'/';

    if( !is_dir($targetPath) ){
        mkdir($targetPath,0777,true);
    }
    $memberId = $_GET['member_id'];
    $new_file_name = 'avatar_ori_'.$memberId.'.'.$ext;
    $targetFile = $targetPath . $new_file_name;
    move_uploaded_file($tempFile,$targetFile);
    if( !file_exists( $targetFile ) ){
        $ret['result_code'] = 0;
        $ret['result_des'] = 'upload failure';
    } elseif( !$imginfo=getImageInfo($targetFile) ) {
        $ret['result_code'] = 101;
        $ret['result_des'] = 'File is not exist';
    } else {
        $img = '/uploads/'.$year.'/'.$mouth.'/'.$new_file_name;
        resize($img); //等比例缩放图片
        $ret['result_code'] = 1;
        $ret['result_des'] = '/assets'.$img;
    }
} else {
    $ret['result_code'] = 100;
    $ret['result_des'] = 'No File Given';
}
exit( json_encode( $ret ) );