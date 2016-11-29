<?php


	class Image{

		private $image;
		//图片的基本信息
		private $info;

		/**
		*打开一张图片，读取到内存中
		*/
		public function __construct($src){

			$info = getimagesize($src);
			$this ->info = array(
				'width' => $info[0] , 
				'height' => $info[1],
				'type' => image_type_to_extension($info[2],false),
				'mime' => $info['mime']
			);
			$fun = "imagecreatefrom{$this->info['type']}";
			$this->image = $fun($src);
		}
		/**
		*操作图片 添加文字
		*/
		public function fontMark($content,$font_url,$size,$color,$local,$angle){
			$col = imagecolorallocatealpha($this->image, $color[0], $color[1], $color[2], $color[3]);
			imagettftext($this->image, $size, $angle, $local['x'], $local['y'], $col, $font_url, $content);
		}



		public function imageMark($src_Mark,$local,$alpha){
			$info2 = getimagesize($src_Mark);
			$type2 = image_type_to_extension($info2[2],false);
			$fun2 = "imagecreatefrom{$type2}";
			$water = $fun2($src_Mark);
			imagecopymerge($this->image, $water, $local['x'], $local['y'], 0, 0, $info2[0], $info2[1], $alpha);
			imagedestroy($water);
		}

		/**
		*缩略图生成
		*/
		public function thumb($width,$height){
			$image_thumb = imagecreatetruecolor($width, $height);
			imagecopyresampled($image_thumb, $this->image, 0, 0, 0, 0, $width, $height, $this->info['width'], $this->info['height']);
			imagedestroy($this->image);
			$this->image = $image_thumb;
		}
		/**
		*显示图片
		*/
		public function show(){
			header("Content_type;" .$this->info['mime']);
			$funs = "image{$this->info['type']}";
			$funs($this->image);
		}
		/**
		*保存图片
		*/
		public function save($newname){
			$funs = "image($this->info['type'])";
			$funs($this->image,$newname.'.'.$this->info['type']);
		}
		/**
		*销毁图片
		*/
		public function __destruct(){
			imagedestroy(($this->image));
		}
	}


?>