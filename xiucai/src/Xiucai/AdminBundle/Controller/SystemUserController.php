<?php

namespace Xiucai\AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Xiucai\StoreBundle\Entity\XcAdmin;
header("Content-type: text/html; charset=utf-8");
class SystemUserController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();
        if ($request->attributes->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContextInterface::AUTHENTICATION_ERROR
            );
        } elseif (null !== $session && $session->has(SecurityContextInterface::AUTHENTICATION_ERROR)) {
            //$error = $session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
            $error = '账号或密码错误！';
            $session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        $lastUsername = (null === $session) ? '' : $session->get(SecurityContextInterface::LAST_USERNAME);
        return $this->render(
            'AdminBundle:SystemUser:login.html.twig',
            array(
                'last_username' => $lastUsername,
                'error'         => $error,
            )
        );
    }

    /**
     * 描述：生成验证码
     */
//    public function validateAction()
//    {
//        $code = '';
//        $numLength = 4;
//        $stuff = '1234567890abcdefghijklmnopqrstuvwxyz';
//        $stuffLength = strlen($stuff) - 1;
//        for($i = 0; $i < $numLength; $i++){
//            $code .= substr($stuff, mt_rand(0, $stuffLength), 1);
//        }
//        $session = $this->getRequest()->getSession();
//        $session->set('validate', $code);
//
//        // Dimension of the image
//        $imgWidth = isset($preferences['width']) ? $preferences['width'] : 100;
//        $imgHeight = isset($preferences['height']) ? $preferences['height'] : 30;
//        // Create image
//        $img = imageCreate($imgWidth, $imgHeight);
//        ImageColorAllocate($img, 0x6C, 0x74, 0x70);
//        $white = ImageColorAllocate($img, 0xff, 0xff, 0xff);
//
//        $imgX = 15;
//        $imgY = 7;
//        $numLength = strlen($code);
//        for ($i = 0; $i < $numLength; $i++)
//        {
//            imageString($img, 5, $imgX, $imgY, $code[$i], $white);
//            $imgX += 20;
//        }
//        // Mask the image
//        for($i=0;$i<200;$i++)
//        {
//            $randColor = ImageColorallocate($img,rand(0,255),rand(0,255),rand(0,255));
//            imagesetpixel($img, rand()%100 , rand()%50 , $randColor);
//        }
//        // Output
//        header("Content-type: ".image_type_to_mime_type(IMAGETYPE_PNG));
//        imagepng($img);
//        imagedestroy($img);
//        exit();
//    }
//    /**
//     * 描述：校验验证码
//     */
//    public function validateMatchingAction(Request $request){
//        $session = $request->getSession();
//        $yam     = isset($_POST['yzm'])?$_POST['yzm']:'';
//        if($yam == $session->get('validate')){
//            return new Response('ok');
//        }else{
//            return new Response('no');
//        }
//    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        // 获取权限信息
        $conn = $this->get('database_connection');
        $sql = "SELECT `c`.*
        FROM `xc_admin` AS `a`
        INNER JOIN `xc_admin_role_privilege` AS `b` ON b.admin_role_id = a.role_id
        INNER JOIN `xc_admin_privilege` AS `c` ON b.admin_privilege_id = c.admin_privilege_id
        INNER JOIN `xc_admin_role` AS `d` ON d.admin_role_id = a.role_id
        WHERE (a.admin_id = '2' ) AND (d.active = 1) AND (c.active = 1) AND (c.menu_show = 1)";
        $priRes['data'] = $conn->fetchAll($sql);

        //查询父节点
        $sql = "SELECT `xc_admin_privilege`.* FROM `xc_admin_privilege`
        WHERE (active = 1) AND (parent_id = 0) AND (menu_show = 1) ORDER BY `admin_privilege_id` ASC";
        $parents = $conn->fetchAll($sql);

        //查询子节点
        $sql = "SELECT `xc_admin_privilege`.* FROM `xc_admin_privilege`
        WHERE (parent_id <> 0) AND (active = 1) AND (menu_show = 1) ORDER BY `admin_privilege_id` ASC";
        $children = $conn->fetchAll($sql);

        //为父节点添加children属性
        for($i=0; $i < count($parents); $i++) {
            $parents[$i]['children'] = array();
        }
        //将子节点添加到对应的父节点之内
        foreach($children as $cValue){
            foreach($parents as $pk => $pValue){
                if($cValue['parent_id'] == $pValue['admin_privilege_id']){
                    $cValue['path_url'] = 'AdminBundle_'.$cValue['controller'].'_'.$cValue['action'];
                    $parents[$pk]['children'][] = $cValue;
                }
            }
        }
        $allPri = $parents;

        //整理权限及对应名称
        $priInfo  = array();
        if(!empty($priRes['data'])){
            //搜寻父级权限，并将信息添加到priInfo中
            foreach($priRes['data'] as $pri){
                if($pri['parent_id'] == 0){
                    foreach($allPri as $info){
                        if($info['admin_privilege_id'] == $pri['admin_privilege_id']){
                            array_push($priInfo, $info);
                            break;
                        }
                    }
                }
            }
            //整理子级权限
            foreach($priRes['data'] as $pri){
                if($pri['parent_id'] != 0){
                    $father = false;
                    foreach($priInfo as $infoK => $info){
                        if($info['admin_privilege_id'] == $pri['parent_id']){
                            //父级权限已存在
                            $father = true;
                            //判断子级权限是否已存在
                            $child  = false;
                            if(!empty($info['children'])){
                                foreach($info['children'] as $childPri){
                                    if($childPri['admin_privilege_id'] == $pri['admin_privilege_id']){
                                        $child  = true;
                                        break;
                                    }
                                }
                            }
                            if(!$child){
                                //子级权限不存在，则添加
                                if(empty($priInfo[$infoK]['children']))
                                    $priInfo[$infoK]['children']  = array();
                                array_push($priInfo[$infoK]['children'], $pri);
                            }
                            break;
                        }
                    }
                    if(!$father){
                        //父级权限不存在
                        $fatherInfo = array();
                        foreach($allPri as $info){
                            if($info['admin_privilege_id'] == $pri['parent_id']){
                                $fatherInfo = $info;
                                $fatherInfo['children'] = array();
                                break;
                            }
                        }
                        array_push($fatherInfo['children'], $pri);
                        array_push($priInfo, $fatherInfo);
                    }
                }
            }
        }
        //usort($priInfo, "privilegeComparator");
        $user = $this->get('security.context')->getToken()->getUser();
       // var_dump($user);
        $adminName = $user->getUsername();
// print_r($priInfo);
        return $this->render('AdminBundle:Page:index.html.twig', array('priInfo' => $priInfo,'adminName'=>$adminName));
    }
}
