<?php

	$first = $_POST['first'];
	$second = $_POST['second'];

	$result1 = $first + $second;
	$result2 = $first - $second;
	$result3 = $first * $second;
	$result4 = $first / $second;

	// 要想返回xml，首先链接一个xml格式的字符串
	$str = '<root>';
	$str .='<jia>'.$result1.'</jia>';
	$str .='<jian>'.$result2.'</jian>';
	$str .='<cheng>'.$result3.'</cheng>';
	$str .='<chu>'.$result4.'</chu>';
	$str .='</root>';
	header('Content-type:text/xml');
	echo $str;

	// $str=<<<str
	// <root>
	// </root>

	// str;

?>