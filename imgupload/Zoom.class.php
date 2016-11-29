<?php
    class Zoom{
		//这个类上衣一张图片，缩放多张(你要的)
		protected $img_path ;
		protected $img_arr=array();
		/**
		 * @param string   $img_path   图片路径
		 */
 		public function __construct($img_path,$arr){
 			$this->img_path=$img_path;
 			$this->img_arr=$arr;
 			//var_dump($this->img_arr);
			/*$this->width=$width;
			$this->height=$height;*/		
 		}
		public function zoom(){
			//获取图片的后缀
			$img_info = pathinfo($this->img_path);
			// print_r($img_info['extension']);exit();
			$suffix = $img_info['extension'];
			//这个地方是为了后面的imagecreatefrom jpeg 而准备的
			if($suffix == 'jpg'){
            	$suffix = 'jpeg';
        	}
        	// 拼接两个函数名
       		// 创建图片资源的函数名
       		// imagecreatefromjpeg imagecreatefrompng imagecreatefromgif
        	$func_resource = 'imagecreatefrom'.$suffix;

        	// 保存图片的函数名
        	// imagejpeg  imagepng   imagegif
        	$func_save = 'image'.$suffix;

        	// 获取原图的宽和高
        	list($src_w, $src_h)=getimagesize($this->img_path);
        	// 直接缩放
	        // 打开原图产生资源
	        $src =$func_resource($this->img_path);
	        for($i=0;$i<count($this->img_arr);$i++){
		        // 创建小图
		        $dst = imagecreatetruecolor($this->img_arr[$i]['width'], $this->img_arr[$i]['height']);

		        // 专业缩放的函数
		        imagecopyresampled($dst, $src, 0,0, 0,0, $this->img_arr[$i]['width'], $this->img_arr[$i]['height'], $src_w, $src_h);


		        // 处理缩放后的完整图片路径
		        $save_path = dirname($this->img_path).'/'.$this->img_arr[$i]['width'].'_'.basename($this->img_path);

		        //echo $save_path;
		          //  exit;

		        // 保存缩放后的图片
		        // imagejpeg imagepng imagegif  保存成功返回真，保存失败返回假
		        $result = $func_save($dst, $save_path);
		     }
		        //echo '绽放<br/>';

		        // 销毁资源
		        imagedestroy($src);
		        imagedestroy($dst);
		        return $result;
		}

	}

  

