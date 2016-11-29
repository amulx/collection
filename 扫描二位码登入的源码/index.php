<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
<head>
<title>新建网页</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="布尔教育 http://www.itbool.com" />
</head>
    <body>
    	<?php
    		require 'conn.php';
    		$randnumber = "";
    		for ($i=0; $i < 8; $i++) { 
    			$randnumber .=rand(0,9);
    		}
    		mysql_query("insert into jike (randnumber) values ('$randnumber')");
    	?>
    	<img src="http://qr.liantu.com/api.php?text=<?php echo $randnumber; ?>" width="300px">
    	<input hidden="hidden" type="text" name="randnumber" id="randnumber" value="<?php echo $randnumber; ?>" />
    </body>
    <script type="text/javascript">

    	function polling(){

    		var xmlHttp;

    		if(window.XMLHttpRequest){
    			xmlHttp = new XMLHttpRequest();
    		}else{
    			xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    		}

    		xmlHttp.onreadystatechange = function(){
    			if(xmlHttp.status ==200 && xmlHttp.readyState == 4){
    				result = xmlHttp.responseText;
    				if (result == 'true') {
    					window.location.href = 'welcome.php';
    				}
    			}
    		}

    		randnumber = document.getElementById('randnumber').value;
    		xmlHttp.open("GET","polling.php?randnumber=" + randnumber,true);
    		xmlHttp.send();
    	}
    	setInterval("polling()",1000);
    </script>
</html>