<?php

	class SqlTool{

		private $conn;
		private $host="localhost:3306";
		private $user="root";
		private $password="root";
		private $db = "news";

		function SqlTool(){
			@$this->conn=mysql_connect($this->host,$this->user,$this->password);
			if (!$this->conn) {
				header("Content-type:text/html;charset=utf-8");
				die("数据库连接失败".mysql_error());
			}

			mysql_select_db($this->db,$this->conn);

			mysql_query("set names utf8");
		}

		// 完成select
		public function execute_dql($sql){
			$res = mysql_query($sql);

			return $res;
		}

		//完成update ， delete ， insert
		public function execute_dml($sql){
			$b = mysql_query($sql,$this->conn);

			if(!$b){
				return 0;  //失败
			}else{
				if (mysql_affected_rows($this->conn)>0) {
					return 1;//成功
				}else{
					return 2;//表示没有行数影响
				}
			}
		}

	}




?>