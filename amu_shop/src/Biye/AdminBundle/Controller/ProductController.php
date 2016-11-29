<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-4-7
 * Time: 下午9:13
 */

namespace Biye\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Biye\StoreBundle\Entity\ImoocAlbum;
use Biye\StoreBundle\Entity\ImoocPro;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller{

    public function addProAction(){
        $conn = $this->get('database_connection');
        $sql = "select m.id, m.cName from imooc_cate m ";
        $query = $conn->query($sql);
        $result = $query->fetchAll();

        return $this->render('BiyeAdminBundle:Product:addPro.html.twig',array('result'=>$result));
    }

    /**
     * @return Response
     */
    public function proAddAction(){

        $pName = isset($_REQUEST['pName'])?$_REQUEST['pName']:'';   //商品名称
        $cId  =  isset($_REQUEST['cId'])?$_REQUEST['cId']:'';        //商品分类
        $pSn  =  isset($_REQUEST['pSn'])?$_REQUEST['pSn']:'';       //商品货号
        $pNum =  isset($_REQUEST['pNum'])?$_REQUEST['pNum']:'';   //商品数量

        $mPrice = isset($_REQUEST['mPrice'])?$_REQUEST['mPrice']:'';//市场价
        $iPrice = isset($_REQUEST['iPrice'])?$_REQUEST['iPrice']:'';//会员价
        $pDesc  = isset($_REQUEST['pDesc'])?$_REQUEST['pDesc']:'';//商品描述

        $tmp_name = $_FILES["album"]["tmp_name"];            //临时文件3
        $now_name  = $_FILES["album"]["name"];                  //当前文件4

        move_uploaded_file($tmp_name,'./images/'.date('yyyymdhis').$now_name);    //移动到指定的文件夹下面

        $albumPath = '/web/images/'.date('yyyymdhis').$now_name;

        $connpro = $this->get('database_connection');
        $admin = $connpro->insert('imooc_pro',array('pName'=>$pName,'pSn'=>$pSn,'pNum'=>$pNum,'mPrice'=>$mPrice,'iPrice'=>$iPrice,'pDesc'=>$pDesc,'isShow'=>1,'isHot' => 1,'cId'=>$cId));

        $conn = $this->get('database_connection');
        $sql = "select id from imooc_pro ";
        $query = $conn->query($sql);
        $result2 = $query->fetchAll();
        $count=count($result2);
        $pid = $count ;

        $ImoocAlbum = new ImoocAlbum();
        $ImoocAlbum->setPid($pid);
        $ImoocAlbum->setAlbumPath($albumPath);
        $em1 = $this->getDoctrine()->getEntityManager();
        $em1->persist($ImoocAlbum);
        $em1->flush();

//        return $this->render('BiyeAdminBundle:Product:addPro.html.twig');
        header("Content-type:text/html;charset=utf-8");
        echo "<script>alert('添加成功');history.back()</script>";
        exit;
    }

    /**
     * @return Response
     * 商品展示列表
     */
    public function listProAction(){
        //从模板中获取查询条件参数
        $contents   = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';  //输入框输入的关键字
        $condition  = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):''; //选择的下拉菜单
        $timeSelect = isset($_REQUEST['timeSelect'])?trim($_REQUEST['timeSelect']):'';
        $createTime  = isset($_REQUEST['createTime'])?trim($_REQUEST['createTime']):'';
        $lastLogin    = isset($_REQUEST['lastLogin'])?trim($_REQUEST['lastLogin']):'';
        $userType   = isset($_REQUEST['userType'])?trim($_REQUEST['userType']):'';  //用户类型
        $vendorStatus = isset($_REQUEST['vendorStatus'])?trim($_REQUEST['vendorStatus']):''; //服务商类型
        $cityType = isset($_REQUEST['cityType'])?trim($_REQUEST['cityType']):''; //城市

        //查询商品图片
        $conn = $this ->get('database_connection');
        $sqlproalbum = "select pid, albumPath from imooc_album";
        $query = $conn->query($sqlproalbum);
        $resultSM = $query->fetchAll();

        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select m.id, m.pName, m.pSn, m.pNum, m.mPrice, m.iPrice, m.pDesc, m.pubTime, m.isShow, m.isHot, m.cId from imooc_pro m where m.isShow<>3";

        if($condition == ''){
            $sql .= " and (m.id like '%".$contents ."%' or  m.pName like '%".$contents ."%'  or m.cId like '%".$contents ."%' or m.pSn like '%".$contents ."%')";
        }elseif($condition == '1'){
            $sql .= " and m.id like '%".$contents ."%'";
        }elseif($condition == '2'){
            $sql .= " and m.pName like '%".$contents ."%'";
        }elseif($condition == '3'){
            $sql .= " and m.cId like '%".$contents ."%'";
        }elseif($condition == '4'){
            $sql .= " and m.pSn like '%".$contents ."%'";
        }

        //时间收索条件
        if($timeSelect == '1'){
            if($createTime != '' && $lastLogin != ''){
                $sql .= " and m.create_time between '".$createTime."' and '".$lastLogin."'";
            }
            else
                if($createTime != ''){
                    $sql .=" and m.create_time >= '".$createTime."'";
                }
                else
                    if($lastLogin != ''){
                        $sql .=" and m.create_time <= '".$lastLogin."'";
                    }
        }elseif($timeSelect == '2'){
            if($createTime != '' && $lastLogin != ''){
                $sql .= " and m.last_login between '".$createTime."' and '".$lastLogin."'";
            }
            else
                if($createTime != ''){
                    $sql .=" and m.last_login >= '".$createTime."'";
                }
                else
                    if($lastLogin != ''){
                        $sql .=" and m.last_login <= '".$lastLogin."'";
                    }
        }

        //商品分类搜索条件
        if($userType == '1'){
            $sql .=" and m.cId = 1";
        }elseif($userType == '2'){
            $sql .=" and m.cId = 2";
        }elseif($userType == '3'){
            $sql .=" and m.cId = 3";
        }
        //是否展示搜索条件
        if($vendorStatus == '1'){
            $sql .= " and m.isShow = 1";
        }elseif($vendorStatus == '2'){
            $sql .= " and m.isShow = 2";
        }

        //城市条件搜索
        if($cityType <> 0){
            $sql = $sql." and m.city = '".$cityType."'";
        }

        $query  = $conn->query($sql);
        $result = $query->fetchAll();
        $count=count($result);
        $request = Request::createFromGlobals()->query;

        $params['page'] = $request->get('page');  //当前页码数
        $limit = 10; //页面限制条数
        $page = empty($params['page']) ? 1 : $params['page']; //当前页数初始化
        $offset = ($page - 1)*$limit; //计算分页偏移量
        $tdata  =  array_slice($result,$offset,10);
        $pagination = $this->container->get('PagePaginationServicesAdmin'); //获取分页services
        $pager = $pagination->getNavigation($count, $limit, $page, 'listPro', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
        $pages = ($count == 0 || $count <= 10) ? '' : $pager; //每10条记录作为一页
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
        return $this->render('BiyeAdminBundle:Product:listPro.html.twig',array('pages' => $pages,'parameter'=>$parameter,'tdata'=>$tdata,'resultSM'=>$resultSM));

    }

    public function proDetailAction(Request $request){
        //查找产品属性
        $id = $request->get('memberId');
        $em = $this->getDoctrine()->getEntityManager();
        $member= $this->getDoctrine()->getRepository('BiyeStoreBundle:ImoocPro')->find($id);

        //查找产品的图片
        $pid = $id;
        $album = $this->getDoctrine()->getRepository('BiyeStoreBundle:ImoocAlbum')->find($pid);

        //查找所有产品分类
        $conn = $this->get('database_connection');
        $sqlcate = "select id , cName from imooc_cate";
        $query = $conn->query($sqlcate);
        $resultcate = $query->fetchAll();

        return $this->render('BiyeAdminBundle:Product:proDetail.html.twig',array('member'=>$member,'album'=>$album,'resultcate'=>$resultcate));
    }

    //商品是否展示状态更改
    public function showDisableAction(){
        if($this->getRequest()->getMethod()==="POST"){
            //获取前台传过来的id
            $id = isset($_POST['id'])?$_POST['id']:'';

            $conn = $this->get('database_connection');
            $sql = "select isShow from imooc_pro where id='".$id."'";
            $result = $conn->query($sql)->fetchAll();

            $disableId = $result[0]['isShow'];
            if($disableId==1){
                $user = $conn->update('imooc_pro',array('isShow'=>0),array('id'=>$id));
            }else{
                $user = $conn->update('imooc_pro',array('isShow'=>1),array('id'=>$id));
            }
            echo 3;
            exit;
        }
    }

    //商品是否热卖状态更改
    public function hotDisableAction(){
        if($this->getRequest()->getMethod()==="POST"){
            $id = isset($_POST['id'])?$_POST['id']:'';

            $conn = $this->get('database_connection');
            $sql = "select isHot from imooc_pro where id='".$id."'";
            $result = $conn->query($sql)->fetchAll();

            $disableId = $result[0]['isHot'];
            if($disableId==1){
                $user = $conn->update('imooc_pro',array('isHot'=>0),array('id'=>$id));
            }else{
                $user = $conn->update('imooc_pro',array('isHot'=>1),array('id'=>$id));
            }
            echo 5;
            exit;
        }
    }

    /**
     * 更改商品
     * @return Response
     */
    public function editProAction(){
        $id = isset($_GET['id'])?$_GET['id']:'';

        $conn = $this->get('database_connection');
        $sqlpro = "select * from imooc_pro where id='".$id."'";
        $resultpro = $conn->query($sqlpro)->fetchAll();

        $sqlalbum = "select * from imooc_album where pid = '".$id."'";;
        $resultalbum = $conn->query($sqlalbum)->fetchAll();

        $sqlcate = "select * from imooc_cate ";
        $resultcate = $conn->query($sqlcate)->fetchAll();

        return $this->render('BiyeAdminBundle:Product:editPro.html.twig',array('resultpro'=>$resultpro[0],'resultalbum'=>$resultalbum[0],'result'=>$resultcate,'id'=>$id));
    }

    /**
     * 商品修改
     * @return Response
     */
    public function modifyProAction(){

        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $pName = isset($_REQUEST['pName'])?$_REQUEST['pName']:' ';   //商品名称
        $cId  =  isset($_REQUEST['cId'])?$_REQUEST['cId']:' ';        //商品分类
        $pSn  =  isset($_REQUEST['pSn'])?$_REQUEST['pSn']:' ';       //商品货号
        $pNum =  isset($_REQUEST['pNum'])?$_REQUEST['pNum']:' ';   //商品数量

        $mPrice = isset($_REQUEST['mPrice'])?$_REQUEST['mPrice']:'';//市场价
        $iPrice = isset($_REQUEST['iPrice'])?$_REQUEST['iPrice']:'';//会员价
        $pDesc  = isset($_REQUEST['pDesc'])?$_REQUEST['pDesc']:'';//商品描述

        $tmp_name = $_FILES["album"]["tmp_name"];            //临时文件3
        if($tmp_name){
            $now_name  = $_FILES["album"]["name"];                  //当前文件4
            move_uploaded_file($tmp_name,'./images/'.date('yyyymdhis').$now_name);    //移动到指定的文件夹下面
            $albumPath = '/web/images/'.date('yyyymdhis').$now_name;
            $conn = $this->get('database_connection');
            $album = $conn->update('imooc_album',array('albumPath'=>$albumPath),array('pid'=>$id));
        }

        $conn = $this->get('database_connection');
        $admin = $conn->update('imooc_pro',array('pName'=>$pName, 'cId'=>$cId,'pSn'=>$pSn,'pNum'=>$pNum,'mPrice'=>$mPrice,'iPrice'=>$iPrice,'pDesc'=>$pDesc),array('id'=>$id));

        return $this->render("BiyeAdminBundle:Product:modifysuccess.html.twig");

    }

    /**
     * 删除商品
     */
    public function deleteProAction(){

        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $conn = $this->get('database_connection');
        $admin = $conn->update('imooc_pro',array('isShow'=>3),array('id'=>$id));


        return new Response('ok');

    }


















    function buildInfo(){
        if(!$_FILES){
            return ;
        }
        $i=0;
        foreach($_FILES as $v){
            //单文件
            if(is_string($v['name'])){
                $files[$i]=$v;
                $i++;
            }else{
                //多文件
                foreach($v['name'] as $key=>$val){
                    $files[$i]['name']=$val;
                    $files[$i]['size']=$v['size'][$key];
                    $files[$i]['tmp_name']=$v['tmp_name'][$key];
                    $files[$i]['error']=$v['error'][$key];
                    $files[$i]['type']=$v['type'][$key];
                    $i++;
                }
            }
        }
        return $files;
    }

    public function uploadFile($path="uploads",$allowExt=array("gif","jpeg","png","jpg","wbmp"),$maxSize=2097152,$imgFlag=true){
        if(!file_exists($path)){
            mkdir($path,0777,true);
        }
        $i=0;
        $files=buildInfo();
        if(!($files&&is_array($files))){
            return ;
        }
        foreach($files as $file){
            if($file['error']===UPLOAD_ERR_OK){
                $ext=getExt($file['name']);
                //检测文件的扩展名
                if(!in_array($ext,$allowExt)){
                    exit("非法文件类型");
                }
                //校验是否是一个真正的图片类型
                if($imgFlag){
                    if(!getimagesize($file['tmp_name'])){
                        exit("不是真正的图片类型");
                    }
                }
                //上传文件的大小
                if($file['size']>$maxSize){
                    exit("上传文件过大");
                }
                if(!is_uploaded_file($file['tmp_name'])){
                    exit("不是通过HTTP POST方式上传上来的");
                }
                $filename=getUniName().".".$ext;
                $destination=$path."/".$filename;
                if(move_uploaded_file($file['tmp_name'], $destination)){
                    $file['name']=$filename;
                    unset($file['tmp_name'],$file['size'],$file['type']);
                    $uploadedFiles[$i]=$file;
                    $i++;
                }
            }else{
                switch($file['error']){
                    case 1:
                        $mes="超过了配置文件上传文件的大小";//UPLOAD_ERR_INI_SIZE
                        break;
                    case 2:
                        $mes="超过了表单设置上传文件的大小";			//UPLOAD_ERR_FORM_SIZE
                        break;
                    case 3:
                        $mes="文件部分被上传";//UPLOAD_ERR_PARTIAL
                        break;
                    case 4:
                        $mes="没有文件被上传1111";//UPLOAD_ERR_NO_FILE
                        break;
                    case 6:
                        $mes="没有找到临时目录";//UPLOAD_ERR_NO_TMP_DIR
                        break;
                    case 7:
                        $mes="文件不可写";//UPLOAD_ERR_CANT_WRITE;
                        break;
                    case 8:
                        $mes="由于PHP的扩展程序中断了文件上传";//UPLOAD_ERR_EXTENSION
                        break;
                }
                echo $mes;
            }
        }
        return $uploadedFiles;
    }

} 