<?php
	// 从数组中生成json字符串
	// $row = array('name' =>'lisi' ,'age' =>30);

	// echo json_encode($row);

// ==========================================================
	// 从对象中生成json字符串
	// class Person{
	// 	public $name='zhangsna';
	// 	public $age = 20;
	// }
	// $p1 = new Person();
	// $str = json_encode($p1);
	// // 默认返回一个对象(不加参数)
	// $obj = json_decode($str);
	// var_dump($obj);
	// // 加上参数true表示返回数组
	// $arr = json_decode($str,true);
	// var_dump($arr);
// ===============================================================

	$arr = array('name' =>'张三' ,'age'=>30 );
	// $arr['name']=iconv('gb2312', 'utf-8', $arr['name']);
	echo json_encode($arr);
?>