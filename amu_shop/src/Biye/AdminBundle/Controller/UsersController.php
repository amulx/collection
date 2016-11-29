<?php
/**
 * Created by PhpStorm.
 * User: amu
 * Date: 15-5-17
 * Time: 下午12:25
 */

namespace Biye\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Biye\StoreBundle\Entity\ImoocUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller{

    public function listUsersAction(){
        //从模板中获取查询条件参数
        $contents   = isset($_REQUEST['contents'])?trim($_REQUEST['contents']):'';
        $condition  = isset($_REQUEST['condition'])?trim($_REQUEST['condition']):'';
        $timeSelect = isset($_REQUEST['timeSelect'])?trim($_REQUEST['timeSelect']):'';
        $createTime  = isset($_REQUEST['createTime'])?trim($_REQUEST['createTime']):'';
        $lastLogin    = isset($_REQUEST['lastLogin'])?trim($_REQUEST['lastLogin']):'';
        $userType   = isset($_REQUEST['userType'])?trim($_REQUEST['userType']):'';
        $vendorStatus = isset($_REQUEST['vendorStatus'])?trim($_REQUEST['vendorStatus']):'';
        $cityType = isset($_REQUEST['cityType'])?trim($_REQUEST['cityType']):'';






        $conn   = $this->get('database_connection'); //获取数据库连接对象
        $sql    = "select * from imooc_user m where m.id<>0";


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

        //用户类型搜索条件
        if($userType == '1'){
            $sql .=" and m.source_id is NULL";
        }elseif($userType == '2'){
            $sql .=" and m.source_id = 2";
        }elseif($userType == '3'){
            $sql .=" and m.source_id = 1";
        }
        //服务商状态搜索条件
        if($vendorStatus == '1'){
            $sql .= " and m.vendor_status = 0";
        }elseif($vendorStatus == '2'){
            $sql .= " and m.vendor_status = 1";
        }elseif($vendorStatus == '3'){
            $sql .= " and m.vendor_status = 2";
        }elseif($vendorStatus == '4'){
            $sql .= " and m.vendor_status = 3";
        }elseif($vendorStatus == '5'){
            $sql .= " and m.vendor_status = 4";
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
        $pager = $pagination->getNavigation($count, $limit, $page, 'index', $request); //分页处理：总数，每页条数，页码，action名称，搜索参数
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
        );
        return $this->render('BiyeAdminBundle:Users:listUsers.html.twig',array('pages' => $pages,'parameter'=>$parameter,'tdata'=>$tdata));

    }

} 