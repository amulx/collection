<?php
	header("Content-type: text/html;charset=utf-8");

	// 获取当前年
	@$y = $_GET['y']?$_GET['y']:date('Y');

	// 获取当前月
	@$m = $_GET['m']?$_GET['m']:date('m');

	// 获取当前月多少天
	$days = date('t',strtotime("{$y}-{$m}-1"));

	// 当前一号是周几
	$week = date('w',strtotime("{$y}-{$m}-1"));

	// ========================================
	// 开始输出表格
	echo "<center>";
	echo "<table width='700px'border='1px' cellspace='0px'>";
	echo $y."年".$m."月";
	echo "<tr>";
	echo "<td>日</td> <td>一</td> <td>二</td> <td>三</td> <td>四</td> <td>五</td> <td>六</td>";
	echo "</tr>";

	for ($i=1-$week; $i < $days;) { 
		echo "<tr>";
		for ($j=0; $j < 7; $j++) { 
			if ($i > $days || $i < 1) {
				echo "<td>&nbsp;</td>";
			}else{
				echo "<td>".$i."</td>";
			}
			
			$i++;
		}
		echo "</tr>";
	}

	echo "</table><br>";

	if($m==1){
		$prevyear = $y - 1;
		$prevmonth = 12;
	}else{
		$prevmonth = $m - 1;
		$prevyear = $y;
	}

	echo "<a href='wannianli.php?y=$prevyear&m=$prevmonth'>上一月</a>|下一月";

	echo "</center>";

?>