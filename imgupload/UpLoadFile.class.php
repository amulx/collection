<?php
    header("content-type:text/html;charset=utf-8");
	error_reporting(E_ALL ^ E_NOTICE);

	class UpLoadFile{
		/*
		 *	@$name 			文本框中的name
		 *	@$dir   		要保存的目录
		 *	@$arr_size		设置数组的大小
		 *  @$arr_type		设置数组的类型
		 *	@$type          保存后缀
		 *	@$filename      上传成功后的文件名
		 *  @$errno         错误的信号
		 *	@$errorinfo	 	错误的信息
		 *	
		 *
		 *
		 */
		protected $name;
		protected $dir;
		protected $arr_size;
		protected $arr_type=array();
		protected $type;
		protected $filename;
		protected $errno;
		protected $errorinfo;
	


		public function __construct($name,$dir='./uploads/'){
			$this->name = $name;
			$this->dir  = $dir;
			//var_dump($_FILES);
		}

		public function SetSize($FileSize,$arr){
			$this->arr_size =$FileSize;
			$this->arr_type=$arr;
		}

		public function up(){
			/**
			 * 检查目录
			 */
			if(!is_dir($this->dir)){
				mkdir($this->dir,'0777',true);
			}
			//获取后缀

			$this->type = strrchr($_FILES[$this->name]['name'],'.');
		
			$this->type = ltrim($this->type,'.');
			//上传成功后的文件名
			$this->filename = date('Ymd').uniqid().mt_rand(0,9999).'.'.$this->type;
			
			//上传后的路径(先去掉右边的/，在加上去/)
			$this->dir= rtrim($this->dir,'/');
			$save_path=$this->dir.'/'.$this->filename;
			//var_dump($save_path);

			//检查错误信息
			//查看上传文件的大小
			//检查类型在不在数组中
			if(!$this->CheckErrno()||!$this->CheckSize()||!$this->CheckType()){
				return false;
			}
			//移动文件

			$res = move_uploaded_file($_FILES[$this->name]['tmp_name'],$save_path);
			//var_dump($res );
			if($res){
				return $this->filename;
			}
			$this->errinfo='文件移动失败';
			$this->errno='-1';
			return false;

		}
		public function CheckErrno(){
			switch ($_FILES[$this->name]['error']) {
				case '0':
						return true;
					break;
				case '1':
						$this->errno='1';
						$this->errorinfo='文件大小超出php.ini的限制';
					break;
				case '2':
						$this->errno='2';
						$this->errorinfo='文件大小超出表单的限制';
					break;	
				case '3':
						$this->errno='3';
						$this->errorinfo='部分文件被上传';
					break;		
				case '4':
						$this->errno='4';
						$this->errorinfo='没有文件被上传';
					break;
				case '6':
						$this->errno='6';
						$this->errorinfo='找不到临时目录';
					break;
				case '7':
						$this->errno='7';
						$this->errorinfo='写入文件失败';
					break;			
			}
			return false ;
		}

		public function CheckSize(){
			return $this->arr_size >= $_FILES[$this->name]['size'];
		}

		public function CheckType(){
			/**
			 * 如果$this->arr_type为空的话 就说明了允许上传任何大小
			 */
			if(empty($this->arr_type)){
				return true;
			}
			if(!in_array($this->type,$this->arr_type)){
				$this->errno='3';
				$this->errorinfo='文件上传错误';
				return false; 
			}
			return true;
		}
		public function GetErrorInfo(){
			return '错误类型'.$this->errno.':'.$this->errorinfo;
		}

	}

  

