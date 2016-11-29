<?php
	
	require_once("SqlTool.php");
// =====================执行插入语句
// ==================== 1 ==========================
	// $sql = "insert into users (username,password,email) values (111,111,111)";

	// // $res = SqlTool::execute_dml($sql);
	// $SqlTool = new SqlTool();

	// $res= $SqlTool->execute_dml($sql);

	// header("Content-type:text/html;charset=utf-8");
	// if ($res == 0) {
	// 	echo "失败";
	// } else if($res == 1) {
	// 	echo "success";
	// }else if($res == 2){
	// 	echo "no factor";
	// }


	
// =================================================


// ============================= 2 ============================
	$sql = "select * from users";

	$SqlTool = new SqlTool();

	$res = $SqlTool->execute_dql($sql);

	while ($row = mysql_fetch_assoc($res)) {
		print_r($row);
		echo "<br>";
	}

	mysql_free_result($row);


?>