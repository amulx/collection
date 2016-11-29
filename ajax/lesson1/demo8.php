<?php

	$first = $_POST['first'];
	$second = $_POST['second'];

	$result1 = $first + $second;
	$result2 = $first - $second;
	$result3 = $first * $second;
	$result4 = $first / $second;

	$str = $result1.'|'.$result2.'|'.$result3.'|'.$result4;

	echo $str;
?>