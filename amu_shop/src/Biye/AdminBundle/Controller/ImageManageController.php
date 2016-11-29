<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-5-11
 * Time: 下午11:45
 */

namespace Biye\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ImageManageController extends Controller{
    /**
     * 商品图片的管理
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listProImagesAction(){
        //查询所有商品信息
        $conn = $this->get('database_connection');
        $sqlpro = "select * from imooc_pro";
        $query = $conn->query($sqlpro);
        $pro = $query->fetchAll();

        //查询商品的总记录数
        $count = count($pro);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit = 4;  //页面限制条数
        $page = empty($params['page'])?1:$params['page'];  //当前页数初始化
        $offset = ($page - 1)*$limit;  //计算分页偏移量
        $tdata = array_slice($pro,$offset,4);
        $pagination = $this->container->get('PagePaginationServicesAdmin'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'listProImages', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= 4) ? '' : $pager; //每4条记录作为一页



        $contents   = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';  //输入框输入的关键字
        $condition  = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):''; //选择的下拉菜单
        $timeSelect = isset($_REQUEST['timeSelect'])?trim($_REQUEST['timeSelect']):'';
        $createTime  = isset($_REQUEST['createTime'])?trim($_REQUEST['createTime']):'';
        $lastLogin    = isset($_REQUEST['lastLogin'])?trim($_REQUEST['lastLogin']):'';
        $userType   = isset($_REQUEST['userType'])?trim($_REQUEST['userType']):'';  //用户类型
        $vendorStatus = isset($_REQUEST['vendorStatus'])?trim($_REQUEST['vendorStatus']):''; //服务商类型
        $cityType = isset($_REQUEST['cityType'])?trim($_REQUEST['cityType']):''; //城市

        $parameter =array(
            'page'        => $page,
            'contents'   => $contents,
            'condition'  => $condition,
            'nums'        => $count,
            'timeSelect' => $timeSelect,
            'createTime'  => $createTime,
            'lastLogin'    => $lastLogin,
            'userType'   => $userType,
            'vendorStatus' => $vendorStatus,
            'cityType'   =>$cityType
        );

        //查询所有商品的图片
        $sqlambum = "select * from imooc_album";
        $query = $conn->query($sqlambum);
        $album = $query->fetchAll();

        return $this->render('BiyeAdminBundle:ImageManage:listProImages.html.twig',array('pages' => $pages,'parameter'=>$parameter,'tdata'=>$tdata,'pro'=>$pro,'album'=>$album));
    }

    /**
     * 添加文字水印
     */
    public function fontMarkAction(){

        if($this->getRequest()->getMethod()==="POST"){
            $id = isset($_POST['id'])?$_POST['id']:'';
            $conn = $this->get('database_connection');
            $sql = "select * from imooc_album where pid = '".$id."'";
            $result = $conn->query($sql)->fetchAll();

            /*打开图片*/
            //1 配置图片路径
            $url = 'D:/Ycxy/web/trunk'.$result[0]['albumPath'];

            //2 获取图像信息D:\Ycxy\web\trunk\web\images
            $info = getimagesize($url);
            //3 通过图像的编号来获取图像的类型
            $type = image_type_to_extension($info[2],false);
            //4 在内存中创建一个和我们图像类型一样的图像
            $fun = "imagecreatefrom{$type}";
            //5 把图像复制到我们的内存中
            $image = $fun($url);

            /* 操作图片 */
            //   1 设置字体的路径
            $font = "D:/Ycxy/web/trunk/web/assets/font/msyh.ttf";
            $content = "阿木商城";

            $col = imagecolorallocatealpha($image,255,0,0,50);

            imagettftext($image,20,0,20,30,$col,$font,$content);

            header("Content-type;" .$info['mime']);
            $func = "image{$type}";
//            $func($image);
            $func($image,"$url");
            imagedestroy($image);
        }
        echo 3;
        exit;
    }

    /**
     * 图像水印
     */
    public function imageMarkAction(){

        if($this->getRequest()->getMethod()==="POST"){
            //获取从页面传过来的id
            $id = isset($_POST['id'])?$_POST['id']:'';
            //   连接数据库，根据id遍历表imooc—album
            $conn = $this->get('database_connection');
            $sql = "select * from imooc_album where pid = '".$id."'";
            $result = $conn->query($sql)->fetchAll();
            /*一、打开图片*/
                // 1、配置图片路径
            $src = 'D:/Ycxy/web/trunk'.$result[0]['albumPath'];
                // 2、获取图片的基本信息
            $info = getimagesize($src);
                // 3、通过图像的编号来获取图像的类型（后缀名）
            $type = image_type_to_extension($info[2],false);
                // 4、创建一个和我们图像类型一直的图像
            $fun = "imagecreatefrom{$type}";
                // 5、把要操作的图片复制到内存中
            $image = $fun($src);
            /*二、操作图片*/
                // 1、设置水印图片路径
            $src_Mark = "D:/Ycxy/web/trunk/web/assets/images/002.jpg";
                // 2、获取水印图片的基本类型
            $info2 = getimagesize($src_Mark);
                // 3、通过图像的编号获取图片类型
            $type2 = image_type_to_extension($info2[2],false);
                // 4、创建一个和水印图片类型一直的图像
            $fun2 = "imagecreatefrom{$type2}";
                // 5、把水印图片复制到内存中
            $water = $fun2($src_Mark);
                // 6、给图片添加水印
            imagecopymerge($image,$water,20,30,0,0,$info2[0],$info2[1],50);
                // 7、销毁水印图片
            imagedestroy($water);
            /*三、输出图片*/
            header("Content_type;" .$info['mime']);
            $func = "image{$type}";
                //保存图片
            $func($image,$src);
            /*四、销毁图片，释放内存*/
            imagedestroy($image);
        }

        echo 4;
        exit;
    }
    //获取图片的缩略图
    public function thumbAction(){

        $src = "001.jpg";
        $info = getimagesize($src);
        $type = image_type_to_extension($info[2],false);
        $fun = "imagecreatefrom{$type}";
        $image = $fun($src);
        //在内存中建立一个宽300，高200的真色彩图片
        $image_thumb = imagecreatetruecolor(300,200);
        // 将原图复制到新创建的真色彩图片上（按一定比例缩小）
        imagecopyresampled($image_thumb,$image,0,0,0,0,300,200,$info[0],$info[1]);
        //销毁原始图片
        imagedestroy($image);
        /*输出图片*/
        header("Content_type;" .$info['mime']);
        $func = "image{$type}";
        //把图片输出到浏览器
        $func($image_thumb);
        //保存图片
        $func($image_thumb,"thumbimage.".$type);
        /*销毁图片，释放内存*/
        imagedestroy($image_thumb);

    }

} 