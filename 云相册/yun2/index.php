<?php
require('upyun.class.php');
$upyun = new UpYun("chenam", "chenamu", "12345678lx");


if(!empty($_POST['sub'])){
 move_uploaded_file($_FILES['up']['tmp_name'],$_FILES['up']['name']);
 $fh = fopen($_FILES['up']['name'],'r'); 
 $upyun->writeFile("/".$_FILES['up']['name'], $fh);  //返回一个布尔值
 fclose($fh);
 unlink($_FILES['up']['name']);
}



?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>又拍云存储</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="./css/bootstrap.css" />
<link rel="stylesheet" href="./css/bootstrap-responsive.css" />
<link rel="stylesheet" href="./css/prettyPhoto.css" />
<link rel="stylesheet" href="./css/custom-styles.css" />
<!-- JS
================================================== -->
<script src="./js/jquery-latest.js" type="text/javascript"></script>
<script src="./js/jquery.easing.1.3.js"></script>
<script src="./js/bootstrap.js"></script>
<script src="./js/jquery.prettyPhoto.js"></script>
<script src="./js/jquery.quicksand.js"></script>
<script src="./js/jquery.custom.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=gbk" /></head>
<body>
	<div class="color-bar-1"></div>
    <div class="color-bar-2 color-bg"></div>
    <div class="container main-container">
      <div class="row header">
	  <hr>
	  <h1>又拍云存储的使用</h1>
	  <form action="" method="post" enctype="multipart/form-data">
	  <input type="file" name="up" >
	  <input type="submit" name='sub' value="上传">
	  </form>
	  <hr>
            <div class="row clearfix">
			
                <ul class="gallery-post-grid holder">
    <?php 
	foreach($upyun->readDir('/') as $v){
	//$upyun->deleteFile("/".$v->name);
	if($v->type=="folder")continue;
	?>
				   <!-- Gallery Item 1 -->
                    <li class="span4 gallery-item" data-id="id-1" data-type="illustration">
                        <span class="gallery-hover-3col hidden-phone hidden-tablet">
                            <span class="gallery-icons">
                                <a href="http://chenam.b0.upaiyun.com/<?php echo $v->name; ?>" 
								class="item-zoom-link lightbox" 
								title="云相册" 
								data-rel="prettyPhoto"></a>
                            </span>
                        </span>
                        <img src="http://chenam.b0.upaiyun.com/<?php echo $v->name; ?>!370300" alt="Gallery" />
                    </li>
<?php 
}
?>

                </ul>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <ul>
                <li class="active"><a href="#">Prev</a></li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">Next</a></li>
                </ul>
            </div>

        </div><!-- End gallery list-->

    </div><!-- End container row -->
    
    </div> <!-- End Container -->

  
</body>
</html>
