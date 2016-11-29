<?php
// =================================================
// echo 'helloajax';
// =====================================================
// header('content-type:text/xml');
// $str="<root>";
// $str.="<jia>123</jia>";
// $str.="<jian>123</jian>";
// $str.="</root>";
// echo $str;
// ===========================================================

$rows = array('goodsname'=>'手机','price'=>1000);
echo json_encode($rows);
?>