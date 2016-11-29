<?php

namespace Xiucai\PageBundle\Controller;

use Xiucai\StoreBundle\Entity\XcActivate;
use Xiucai\StoreBundle\Entity\XcMember;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserController extends Controller
{

    /**
     * 用户首页-我的直播
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction(Request $request){
        date_default_timezone_set("PRC");
        $session = $this->get('request')->getSession();
        $loginInfo = $this->getUser();
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getId();
        if(empty($memberId)){
            return $this->redirect($this->generateUrl('PageBundle_page_index'));
            exit;
        }

        $liveCourseData = array();
        $lType = 'live'; //判断左侧导航类型
        $rType = $request->get('rType'); //右侧类型
        $rType = $param['rType'] = (empty($rType)) ? 'new' : $rType; //判断页面类型

        //获取用户直播列表
        $liveId = $this->_getLiveId($param, $memberId, $rType);
        if(!empty($liveId)){
            $liveCourseData = $this->_getLiveCourseData($param, $liveId);
            unset($liveCourseData['course_count']);
        }

        return $this->render("PageBundle:User:index.html.twig",
            array(
                'rType'          => $rType,
                'lType'          => $lType,
                'liveCourseData' => $liveCourseData,
                'today_date'     => date('Ymd')
            )
        );
    }

    /**
     * ajax加载用户直播录播列表
     * @param Request $request
     * @return Response
     */
    public function ajaxLoadUserCourseAction(Request $request){
        $data['code'] = 300; //初始化返回状态
        if($request->getMethod() === "POST"){
            $session = $this->get('request')->getSession();
            $loginInfo = $this->getUser();
            $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getId();
            if(empty($memberId)){
                $response = new Response(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }

            $rType = $request->get('rType');
            $rType = empty($rType) ? "new" : $rType; //最新直播，往期直播type
            $param['rType'] = $rType;
            $param['page'] = $request->get('page'); //分页页码

            if($rType == 'new' || $rType == 'old'){
                //获取用户直播列表
                $liveId = $this->_getLiveId($param, $memberId, $rType);
                if(!empty($liveId)){
                    $liveCourseData = $this->_getLiveCourseData($param, $liveId);
                    $data['course_count'] = $liveCourseData['course_count'];
                    unset($liveCourseData['course_count']);
                    $data['data'] = $liveCourseData;
                    $data['code'] = 200;
                }
            }else{
                //获取用户录播列表
                $courseId = $this->_getCourseId($param, $memberId);
                if(!empty($courseId)){
                    $data['data'] = $this->_getCourseData($courseId);
                    $data['code'] = 200;
                }
            }
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 获取用户直播id，往期直播id
     * @param $param
     * @param $memberId
     * @param string $type
     * @return string
     */
    private function _getLiveId($param, $memberId, $type = 'new'){
        $idStr = "";

        //获取用户直播列表
        $courseService = $this->get('course_service');
        $memberLive = $courseService->getMemberLive($memberId);
        if(!empty($memberLive)){
            $newLiveArray = array();
            $emt = $this->getDoctrine()->getManager();
            foreach($memberLive as $new){
                $live = $emt->getRepository('StoreBundle:XcLiveCourse')
                    ->findOneBy(array('id' => $new[0], 'status' => 4)); //获取房间详细信息
                if($type == 'new'){
                    if(!empty($live)){
                        $conference = $emt->getRepository('StoreBundle:XcConference')
                            ->findOneBy(array('id' => $live->getConfId(), 'conferenceStatus' => 3)); //获取房间详细信息
                    }
                }else if($type == 'old'){
                    if(!empty($live)){
                        $conference = $emt->getRepository('StoreBundle:XcConference')
                            ->findOneBy(array('id' => $live->getConfId(), 'conferenceStatus' => 6)); //获取房间详细信息
                    }
                }

                if(!empty($conference)){
                    $newLiveArray[$new[1]] = $new[0];
                }
            }

            $page = empty($param['page']) ? 1 : $param['page']; //当前页数初始化
            $limit = empty($param['limit']) ? 3 : $param['limit']; //当前页数初始化
            $offset = ($page - 1)*$limit; //计算分页偏移量

            if(!empty($newLiveArray) && $type == 'old'){
                krsort($newLiveArray);
            }

            $newLiveArray = array_slice($newLiveArray, $offset, $limit);
            if(!empty($newLiveArray)){
                $idStr = implode(",", $newLiveArray); //拼接用户直播课程ID
            }
        }

        return $idStr;
    }

    /**
     * 获取用户直播列表信息处理
     * @param $param
     * @param $idStr
     * @return array
     */
    private function _getLiveCourseData($param, $idStr){
        $courseNewData = array();
        $conn = $this->get('database_connection'); //获取数据库连接对象

        //获取课程列表信息sql语句
        $sql = "SELECT l.`id`,l.`conf_id`,l.`title`,l.`brief`,l.`reserve_num`,l.`current_price`,l.duration,
        l.`course_level`,l.`img_url`,l.`schedule_time`
        FROM `xc_live_course` AS l
        LEFT JOIN  `xc_conference` AS con ON con.id = l.conf_id
        WHERE l.`status` = 4 AND l.id in( $idStr ) ";

        if($param['rType'] == 'new'){
            $sql .= " AND con.conference_status = 3 ";
            $sql .= " ORDER BY l.`schedule_time` ASC "; //按修改时间倒序排列
        }else{
            $sql .= " AND con.conference_status = 6";
            $sql .= " ORDER BY l.`schedule_time` DESC "; //按修改时间倒序排列
        }

        if(!empty($where)){
            $ready = $conn->prepare($sql);
            $ready->execute($where); //搜索参数执行匹配
            $courseData = $ready->fetchAll();
        }else{
            $courseData = $conn->fetchAll($sql);
        }

        unset($courseNewData);
        if(!empty($courseData)){
            $redis = $this->container->get('snc_redis.default');
            foreach($courseData as $key => $course){
                //直播课程播放报名人数
                $redisKeyList = 'live_course_reserve_count';
                $reserveCount = $redis->zscore($redisKeyList, $course['id']);
                $reserveCount = (empty($reserveCount)) ? 0 : $reserveCount;
                $courseData[$key]['reserve_count'] = $reserveCount;

                $timeKey = date('Ymd', strtotime($course['schedule_time']))."_p";
                $timeKey2 = date('His', strtotime($course['schedule_time']))."_c";
                if($course['course_level'] == 1){
                    $courseData[$key]['course_level'] = '初级';
                }else if($course['course_level'] == 2){
                    $courseData[$key]['course_level'] = '中级';
                }else{
                    $courseData[$key]['course_level'] = '高级';
                }

                $startTime = strtotime($course['schedule_time']); //直播时间
                $endTime = $startTime + ($course['duration']*60); //直播时长
                $currentMd = date("md", time());
                $startMd = date('md',$startTime);
                if($currentMd == $startMd){
                    $courseData[$key]['day_box'] = "today";
                }else{
                    $courseData[$key]['day_box'] = date('md',$startTime);
                }
                $timeLeft = $startTime - time();
                if($timeLeft < 0)
                    $timeLeft = 0;
                $courseData[$key]['time_left'] = $timeLeft;
                $courseData[$key]['img_url'] = "http://" . $_SERVER ['HTTP_HOST'].$course['img_url'];
                $courseData[$key]['time_length'] = date('H:i',$startTime).'-'.date('H:i',$endTime);
                $courseNewData[$timeKey][$timeKey2] = $courseData[$key];
            }
            $courseNewData['course_count'] = count($courseData);
        }else{
            $courseNewData['course_count'] = 0;
        }

        return $courseNewData;
    }

    /**
     * 用户首页-我的视频
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function videoAction(Request $request){
        $courseData = array();
        $session = $this->get('request')->getSession();
        $loginInfo = $this->getUser();
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getId();
        if(empty($memberId)){
            return $this->redirect($this->generateUrl('PageBundle_page_index'));
            exit;
        }

        $lType = 'video'; //判断左侧导航类型
        $rType = $request->get('rType'); //右侧类型
        $rType = (empty($rType)) ? 'playing' : $rType; //判断页面类型

        $param['page'] = 1;
        $param['limit'] = 9;
        $courseId = $this->_getCourseId($param, $memberId);
        if(!empty($courseId)){
            $courseData = $this->_getCourseData($courseId);
        }

        return $this->render("PageBundle:User:video.html.twig",
            array(
                'rType' => $rType,
                'lType' => $lType,
                'courseData' => $courseData
            )
        );
    }

    /**
     * 获取用户直播id，往期直播id
     * @param $param
     * @param $memberId
     * @param string $type
     * @return string
     */
    private function _getCourseId($param, $memberId, $type = 'running'){
        $idStr = "";

        //获取用户直播列表
        $courseService = $this->get('course_service');
        $memberCourse = $courseService->getMemberCourse($memberId);
        $emt = $this->getDoctrine()->getManager();
        if(!empty($memberCourse)){
            foreach($memberCourse as $key => $val){
                $data  = $emt->getRepository('StoreBundle:XcCourse')
                    ->findOneBy(array('id' => $val[0],'status' => 4));
                if(empty($data)){
                    unset($memberCourse[$key]);
                }
            }
            $page = empty($param['page']) ? 1 : $param['page']; //当前页数初始化
            $limit = empty($param['limit']) ? 9 : $param['limit']; //当前页数初始化
            $offset = ($page - 1)*$limit; //计算分页偏移量
            $newCourseArray = array_slice($memberCourse, $offset, $limit);
            foreach($newCourseArray as $new){
                $idStr .= $new[0].',';
            }
            $idStr = trim($idStr, ',');
        }

        return $idStr;
    }

    /**
     * 获取录播课程信息处理
     * @param $param
     * @return mixed
     */
    private function _getCourseData($param){
        $conn = $this->get('database_connection'); //获取数据库连接对象

        //获取课程列表信息sql语句
        $sql = "SELECT `id`,`title`,`brief`,`reserve_num`,`current_price`,instructor_id,
        `course_level`,`comment_star`,`create_time`,`modify_time`,`img_url`,`video_num`
        FROM `xc_course`
        WHERE `status` = 4 AND id in( $param ) ORDER BY `modify_time` DESC "; //按修改时间倒序排列

        $courseData = $conn->fetchAll($sql);
        if(!empty($courseData)){
            $emt = $this->getDoctrine()->getManager();
            foreach($courseData as $key => $course){
                //课程级别
                if($course['course_level'] == 1){
                    $courseData[$key]['course_level'] = '初级';
                }else if($course['course_level'] == 2){
                    $courseData[$key]['course_level'] = '中级';
                }else{
                    $courseData[$key]['course_level'] = '高级';
                }

                //录播课程页面主讲人信息
                $teacherData = $emt->getRepository('StoreBundle:XcInstructor')
                    ->findOneBy(array('id' => $course['instructor_id'])); //获取课程主讲人信息
                $courseData[$key]['instructor'] = (empty($teacherData)) ? "匿名老师" : $teacherData->getName();
                $courseData[$key]['img_url'] = "http://" . $_SERVER ['HTTP_HOST'].'/'.$course['img_url'];
                $courseData[$key]['modify_time'] = date('y年m月d日', strtotime($course['modify_time']));
            }
        }

        return $courseData;
    }

    /**
     * 我的主页左边用户信息导航
     * @param Request $request
     * @return Response
     */
    public function leftInfoAction(Request $request){
        $session = $request->getSession();
        $memberId = $session->get('member_id');
        $emt = $this->getDoctrine()->getManager();
        $memberInfo = $emt->getRepository('StoreBundle:XcMember')
            ->findOneBy(array('id' => $memberId)); //获取session用户信息

        //用户省份信息
        $province = $memberInfo->getProvince();
        if(!empty($province)){
            $provinceInfo = $emt->getRepository('StoreBundle:XcCityProvince')
                ->findOneBy(array('shengCode' => $province));
            $memberInfo->setProvince($provinceInfo->getShengName());
        }

        //获取用户城市信息
        $city = $memberInfo->getCity();
        if(!empty($city)){
            $cityInfo = $emt->getRepository('StoreBundle:XcCity')
                ->findOneBy(array('cityCode' => $city));
            $memberInfo->setCity($cityInfo->getCityName());
        }

        $type = $request->get('type');

        //近期直播:距离当前时间最近的一期直播
        $sql = "SELECT l.`id`,l.`conf_id`,l.`title`,l.`brief`,l.`reserve_num`,l.`current_price`,l.duration,
        l.`course_level`,l.`img_url`,l.`schedule_time`
        FROM `xc_live_course` AS l
        LEFT JOIN  `xc_conference` AS con ON con.id = l.conf_id
        WHERE l.`status` = 4  AND con.conference_status = 3 ORDER BY `schedule_time` ASC LIMIT 1";

        $conn = $this->get('database_connection'); //获取数据库连接对象
        $recentLiveInfo = $conn->fetchAssoc($sql);

        return $this->render("PageBundle:User:left_info.html.twig",
            array(
                'lType' => $type,
                'uploadRandom' => mt_rand(),
                'memberInfo' => $memberInfo,
                'recentLiveInfo' => $recentLiveInfo
            )
        );
    }

    /**
     * 用户编辑个人信息
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editorAction(Request $request){
        $session = $request->getSession();
        $memberId = $session->get('member_id');
        if(empty($memberId)){
            return $this->redirect($this->generateUrl('PageBundle_page_index'));
            exit;
        }

        //获取编辑用户信息
        $emt = $this->getDoctrine()->getManager();
        $memberInfo = $emt->getRepository('StoreBundle:XcMember')
            ->findOneBy(array('id' => $memberId)); //获取session用户信息

        //用户有效验证判断
        if(empty($memberInfo)){
            $this->location($this->generateUrl('PageBundle_page_index'),'该用户不存在或未激活。');
            exit;
        }

        //用户编辑信息表单处理
        if($request->getMethod() === "POST"){
            $email = $request->get('email'); //获取邮箱
            $cellphone = $request->get('cellphone'); //用户手机
            $nickname = $request->get('nickname'); //获取用户昵称
            $url = $this->generateUrl('PageBundle_user_editor',array('id' => $memberId));

            //邮箱非空，唯一判断
            if(!empty($email)){
                $pregEmail = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/i";
                if(preg_match($pregEmail,$email)){
                    if($memberInfo->getEmail() != $email){
                        $member = $emt->getRepository('StoreBundle:XcMember')
                            ->findOneBy(array('email' => $email)); //根据用户邮箱获取用户信息
                        if(!empty($member)){
                            $this->location($url,'该邮箱已存在,请重新输入。');
                            exit;
                        }
                    }
                }else{
                    $this->location($url,'您的邮箱格式不正确,请重新输入。');
                    exit;
                }
            }else{
                $this->location($url,'请输入邮箱地址。');
                exit;
            }

            //用户非空判断
            if(empty($nickname)){
                $this->location($url,'请输入昵称。');
                exit;
            }else{
                //用户昵称唯一
                if(!empty($params['username'])){
                    $memberData = $emt->getRepository('StoreBundle:XcMember')
                        ->findOneBy(array('nickname' => $params['username'])); //根据用户昵称获取用户信息
                    if(!empty($memberData)){
                        $this->location($url,'该昵称已存在,请重新输入。');
                        exit;
                    }
                }
            }

            //手机号唯一验证
            if(!empty($cellphone)){
                if($memberInfo->getCellphone() != $cellphone){
                    $member = $emt->getRepository('StoreBundle:XcMember')
                        ->findOneBy(array('cellphone' => $cellphone)); //根据用户手机获取用户信息
                    if(!empty($member)){
                        $this->location($url,'该手机已存在,请重新输入。');
                        exit;
                    }
                }
            }

            $memberInfo->setEmail($email); //修改邮箱
            $memberInfo->setNickname($nickname); //用户昵称
            $memberInfo->setCity($request->get('city')); //所属城市
            $memberInfo->setBrief($request->get('brief')); //用户简介
            $memberInfo->setGender($request->get('gender')); //用户性别
            $memberInfo->setAvatar($request->get('avatar')); //用户头像小头像50*50
            $memberInfo->setCompany($request->get('company')); //用户公司
            $memberInfo->setFullname($request->get('fullname')); //用户姓名
            $memberInfo->setProvince($request->get('province')); //所属省份
            $memberInfo->setPosition($request->get('position')); //用户职位
            $memberInfo->setCellphone($request->get('cellphone')); //用户手机
            $memberInfo->setWorkField($request->get('work_field')); //所属行业
            $memberInfo->setAvatarLarge($request->get('avatar_large')); //用户大头像100*100
            $emt->persist($memberInfo);
            $emt->flush(); //更新用户信息

            return $this->redirect($url);//跳转注册第四步设置
            exit;
        }

        //获取所有省份信息
        $provinceInfo = $emt->getRepository('StoreBundle:XcCityProvince')->findAll();

        $cityInfo = '';
        $city = $memberInfo->getCity();
        $province = $memberInfo->getProvince();
        if(!empty($city) && !empty($province)){
            //获取城市信息
            $cityInfo = $emt->getRepository('StoreBundle:XcCity')->findBy(array('shengCode' => $province));
        }

        //获取行业信息
        $businessInfo = $emt->getRepository('StoreBundle:XcBussiness')->findAll();

        return $this->render("PageBundle:User:editor.html.twig",
            array('memberInfo' => $memberInfo,
                'cityInfo' => $cityInfo,
                'provinceInfo' => $provinceInfo,
                'businessInfo' => $businessInfo,
                'uploadRandom' => mt_rand())
        );
    }

    /**
     * 用户重置密码
     * @param Request $request
     * @return Response
     */
    public function setPassAction(Request $request){
        $session = $request->getSession();
        $memberId = $session->get('member_id');
        if(empty($memberId)){
            return $this->redirect($this->generateUrl('PageBundle_page_index'));
            exit;
        }

        //获取编辑用户信息
        $emt = $this->getDoctrine()->getManager();
        $memberInfo = $emt->getRepository('StoreBundle:XcMember')
            ->findOneBy(array('id' => $memberId)); //获取session用户信息

        //用户有效验证判断
        if(empty($memberInfo)){
            $this->location($this->generateUrl('PageBundle_page_index'),'该用户不存在或未激活。');
            exit;
        }

        //修改密码处理


        return $this->render("PageBundle:User:set_password.html.twig");
    }

    /**
     * ajax重置密码操作
     * @param Request $request
     * @return Response
     */
    public function ajaxSetPassAction(Request $request){
        $data['code'] = 300;
        if($request->getMethod() === "POST"){
            $session = $request->getSession();
            $emt = $this->getDoctrine()->getManager();
            if($request->get('type') == 'set'){
                $memberId = $session->get('member_id');

                //获取编辑用户信息
                $memberInfo = $emt->getRepository('StoreBundle:XcMember')
                    ->findOneBy(array('id' => $memberId)); //获取session用户信息

                //用户有效验证判断
                if(empty($memberInfo)){
                    $data['msg'] = '该用户不存在或未激活';
                }else{
                    $currentPwd = $request->get('current_password');
                    $currentPwd = sha1($currentPwd); //密码加密
                    if($memberInfo->getPassword() != $currentPwd){
                        $data['msg'] = '当前密码输入错误，请重新输入';
                    }else{
                        $newPwd = $request->get('new_password');
                        $newPwd = sha1($newPwd); //新密码加密

                        $memberInfo->setPassword($newPwd); //设置新密码
                        $emt->persist($memberInfo);
                        $emt->flush(); //更新用户密码

                        $data['code'] = 200;
                    }
                }

            }else if($request->get('type') == 'reset'){
                $activate = $emt->getRepository('StoreBundle:XcActivate')
                    ->findOneBy(array('activateCode' => $request->get('find_code'))); //获取激活码信息
                if(empty($activate)){
                    $data['msg'] = '无效的验证无效，请重新发送找回密码邮件';
                }else{
                    //获取编辑用户信息
                    $memberInfo = $emt->getRepository('StoreBundle:XcMember')
                        ->findOneBy(array('id' => $activate->getMemberId())); //获取session用户信息

                    //用户有效验证判断
                    if(empty($memberInfo)){
                        $data['msg'] = '该用户不存在或未激活';
                    }else{
                        $newPwd = $request->get('new_password');
                        $newPwd = sha1($newPwd); //新密码加密

                        $memberInfo->setPassword($newPwd); //设置新密码
                        $emt->persist($memberInfo);
                        $emt->flush(); //更新用户密码

                        $session->set('member_id', $memberInfo->getId()); //session存储用户ID
                        $session->set('username', $memberInfo->getNickname()); //session存储用户名
                        $session->set('avatar', $memberInfo->getAvatar()); //session存储用户名头像
                        $data['code'] = 200;
                    }
                }
            }
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 用户找回密码
     * @param Request $request
     * @return Response
     */
    public function findPassAction(Request $request){
        $data['code'] = 300;
        if($request->getMethod() === "POST"){
            $email = $request->get('email');
            $email = trim($email);

            //邮箱验证
            if(!empty($email)){
                $pregEmail = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/i";
                if(preg_match($pregEmail,$email)){
                    $emt = $this->getDoctrine()->getManager();
                    $member = $emt->getRepository('StoreBundle:XcMember')
                        ->findOneBy(array('email' => $email)); //根据用户邮箱获取用户信息
                    if(empty($member)){
                        $data['msg'] = "该注册邮箱不存在,请重新输入";
                    }else{
                        $randCode = $this->_randCode(); //随机验证码

                        $activate = new XcActivate();
                        $activate->setEmail($email); //发送的邮箱
                        $activate->setMemberId($member->getId()); //记录注册用户ID
                        $expiredTime = strtotime("+1 day"); //设置过期时间
                        $activate->setActivateCode($randCode); //随机激活码
                        $activate->setExpiredDate($expiredTime);
                        $emt->persist($activate);
                        $emt->flush(); //记录找回密码验证

                        $url = "http://" . $_SERVER ['HTTP_HOST'].$this->generateUrl('PageBundle_user_passActivate',
                                array('find_code' => $randCode));

                        $message = \Swift_Message::newInstance()
                            ->setSubject('秀财网找回密码')
                            ->setFrom('no-reply@xiucai.com')
                            ->setTo($email)
                            ->setBody(
                                $this->renderView(
                                    'PageBundle:User:findPass_mail.html.twig',array('url'=>$url)
                                )
                            );
                        $this->get('mailer')->send($message); //发送激活邮件
                        $data['code'] = 200;
                    }
                }else{
                    $data['msg'] = "您的邮箱格式不正确,请重新输入";
                }
            }else{
                $data['msg'] = "请输入邮箱地址";
            }
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 找回密码邮件链接回调
     * @param Request $request
     * @return Response
     */
    public function passActivateAction(Request $request){
        $emt = $this->getDoctrine()->getManager();

        $activate = $emt->getRepository('StoreBundle:XcActivate')
            ->findOneBy(array('activateCode' => $request->get('find_code'))); //获取验证码信息

        $url = $this->generateUrl('PageBundle_page_index');
        if(empty($activate)){
            $this->location($url,'无效的验证地址，请重新发送找回密码邮件！');
            exit;
        }
        if(time() > $activate->getExpiredDate()){
            $this->location($url,'该找回密码邮件验证时间已过，请重新发送找回密码邮件！');
            exit;
        }

        return $this->render("PageBundle:User:reset_password.html.twig", array('findCode' => $request->get('find_code')));
    }

    /**
     * 获取城市信息
     * @param Request $request
     * @return Response]
     */
    public function getCityAction(Request $request){
        $data['code'] = 300;
        if($request->getMethod() === "POST"){
            $provinceId = $request->get('province_id');
            if(!empty($provinceId)){
                $sql = 'SELECT `city_code`,`city_name` FROM xc_city WHERE sheng_code = :province_id';

                $conn = $this->get('database_connection');
                $ready = $conn->prepare($sql);
                $ready->execute(array(':province_id' => $provinceId));
                $cityInfo = $ready->fetchAll();

                $data['code'] = 200;
                $data['city'] = $cityInfo;
            }
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 用户ajax登录
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function loginAction(Request $request){
        $session = $request->getSession();

        //退出操作
        if($request->get('log_out') == "log_out"){
            $session->clear();
            return $this->redirect($this->generateUrl('PageBundle_user_logout'));
        }else{
            $data['code'] = 300;
            if($request->getMethod() === "POST"){
                $email = $request->get('email'); //获取邮箱账号
                $remember = $request->get('remember'); //记住密码选项
                $password = $request->get('password'); //获取用户输入密码

                if(!empty($email) && !empty($password)){
                    $pregEmail = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/i";
                    if(preg_match($pregEmail,$email)){
                        $password = sha1($password); //密码加密

                        $emt = $this->getDoctrine()->getManager();
                        $memberData = $emt->getRepository('StoreBundle:XcMember')
                            ->findOneBy(array('email' => $email, 'password' => $password)); //根据用户邮箱获取用户信息
                        if(empty($memberData)){
                            $data['msg'] = "账号或密码错误，请重新输入";
                        }else{
                            //if($memberData->getIsActivate() == 1){
                                //记住密码操作
                                if(!empty($remember) && $remember == 'remember'){
                                    //下次自动登录session时间设置
                                    ini_set('session.cookie_lifetime',3600*24*14);

                                    //保存14天
                                    $lifeTime = 3600*24*14;
                                    setcookie(session_name(), session_id(), time() + $lifeTime, "/");
                                    setcookie("username", $email, time() + $lifeTime, "/");
                                }

                                $data['code'] = 200;
                                $session->set('member_id', $memberData->getId()); //session存储用户ID
                                $session->set('username', $memberData->getNickname()); //session存储用户名
                                $session->set('avatar', $memberData->getAvatar()); //session存储用户名头像
                            /*}else{
                                $data['msg'] = "该账号未激活，请到注册邮箱查收并激活账号";
                            }*/
                        }
                    }else{
                        $data['msg'] = "邮箱格式不正确";
                    }
                }else{
                    $data['msg'] = "请输入账号或密码";
                }
            }

            $response = new Response(json_encode($data));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * 用户注册页面
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $data['code'] = 300; //返回状态
        $data['msg'] = ""; //返回信息

        $session = $request->getSession();
        $loginInfo = $this->getUser();
        $memberId = empty($loginInfo) ? $session->get('member_id') : $loginInfo->getMemberId();
        if(!empty($memberId)){
            $data['code'] = 100;
            $response = new Response(json_encode($data));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        //注册表单数据处理
        if($request->getMethod() === "POST"){
            $params = $request->get('params');
            $emt = $this->getDoctrine()->getManager();
            /*if(!empty($params['code'])){
                //验证码验证
                $session = $request->getSession();
                $validate_code = $session->get('validate_code');
                if($validate_code != $params['code']){
                    $data['msg'] = "验证码不正确，请重新输入";
                    $response = new Response(json_encode($data));
                    $response->headers->set('Content-Type', 'application/json');
                    return $response;
                }
            }*/

            //邮箱验证
            if(!empty($params['email'])){
                $pregEmail = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/i";
                if(preg_match($pregEmail,$params['email'])){
                    $memberData = $emt->getRepository('StoreBundle:XcMember')
                        ->findOneBy(array('email' => $params['email'])); //根据用户邮箱获取用户信息
                    if(!empty($memberData)){
                        $data['msg'] = "该邮箱已存在,请重新输入";
                    }
                }else{
                    $data['msg'] = "您的邮箱格式不正确,请重新输入";
                }
            }else{
                $data['msg'] = "请输入邮箱地址";
            }

            //用户昵称非空及唯一验证
            /*if(empty($params['username']) || $params['username'] == '您的昵称'){
                $data['msg'] = "请输入昵称";
            }else{
                $memberData = $emt->getRepository('StoreBundle:XcMember')
                    ->findOneBy(array('nickname' => $params['username'])); //根据用户昵称获取用户信息
                if(!empty($memberData)){
                    $data['msg'] = "该昵称已存在,请重新输入";
                }
            }*/

            //密码验证及加密
            if(!empty($params['password'])){
                //$password = password_hash($params['password'], PASSWORD_BCRYPT, array('cost' => 12));
                $password = sha1($params['password']);
            }else{
                $data['msg'] = "请输入密码";
            }

            if(empty($data['msg'])){
                $member = new XcMember();
                $member->setGender(1); //默认性别男性
                $member->setAvatar('/assets/img/avatar_50.png'); //默认头像
                $member->setAvatarLarge('/assets/img/avatar_100.png'); //默认大头像
                $member->setEmail($params['email']); //邮箱设置
                $member->setNickname($params['username']); //用户昵称设置
                $member->setPassword($password); //用户密码设置
                $member->setIsActivate(0); //默认未激活状态
                $member->setRegisterIp($this->getMemberIp()); //设置用户注册ip
                $member->setCreateTime(new \DateTime(date("Y-m-d H:i:s")));
                $emt->persist($member); //添加用户信息操作
                $emt->flush();

                //发送激活账号邮件
                $this->_sendActivateCode($params['email'], $member->getId());

                $session->set('member_id', $member->getId()); //session存储用户ID
                $session->set('username', $member->getNickname()); //session存储用户名
                $session->set('avatar', $member->getAvatar()); //session存储用户名头像

                $data['code'] = 200;
            }
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * ajax：用户注册账号邮箱唯一验证，验证码验证
     * @param Request $request
     * @return Response
     */
    public function checkAction(Request $request){
        $data['code'] = 200;
        $params['email'] = $request->get('email'); //邮箱
        $params['username'] = $request->get('username');
        $params['verify_code'] = $request->get('verify_code'); //验证码
        $params['cellphone'] = $request->get('cellphone'); //手机号码
        $emt = $this->getDoctrine()->getManager();

        $session = $request->getSession();
        $memberId = $session->get('member_id');

        //我的主页个人信息用户昵称验证
        if(!empty($memberId) && !empty($params['username'])){
            $member = $emt->getRepository('StoreBundle:XcMember')->findOneBy(array('id' => $memberId));
            if($member->getNickname() != $params['username']){
                $member = $emt->getRepository('StoreBundle:XcMember')->findOneBy(array('nickname' => $params['username']));
                if(!empty($member)){
                    $data['msg'] = "该昵称已存在,请重新输入";
                    $data['code'] = 100;
                }
            }

            $response = new Response(json_encode($data));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        //我的主页个人信息邮箱验证
        if(!empty($memberId) && !empty($params['email'])){
            $member = $emt->getRepository('StoreBundle:XcMember')->findOneBy(array('id' => $memberId));
            if($member->getEmail() != $params['email']){
                $member = $emt->getRepository('StoreBundle:XcMember')->findOneBy(array('email' => $params['email']));
                if(!empty($member)){
                    $data['msg'] = "该邮箱已存在,请重新输入";
                    $data['code'] = 100;
                }
            }

            $response = new Response(json_encode($data));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        //我的主页个人信息手机号码验证
        if(!empty($memberId) && !empty($params['cellphone'])){
            $member = $emt->getRepository('StoreBundle:XcMember')->findOneBy(array('id' => $memberId));
            if($member->getCellphone() != $params['cellphone']){
                $member = $emt->getRepository('StoreBundle:XcMember')
                    ->findOneBy(array('cellphone' => $params['cellphone'])); //根据用户手机获取用户信息
                if(!empty($member)){
                    $data['msg'] = "该手机号已存在,请重新输入";
                    $data['code'] = 100;
                }
            }
        }

        //验证码输入验证
        if(!empty($params['verify_code'])){
            $session = $request->getSession();
            $validateCode = $session->get('validate');
            $params['verify_code'] = trim($params['verify_code']);

            if($params['verify_code'] != $validateCode){
                $data['data']= $params['verify_code'];
                $data['msg'] = "验证码不正确,请重新输入";
                $data['code'] = 100;
            }
        }

        //验证用户邮箱唯一
        if(!empty($params['email'])){
            $memberData = $emt->getRepository('StoreBundle:XcMember')
                ->findOneBy(array('email' => $params['email'])); //根据用户邮箱获取用户信息

            if(!empty($memberData)){
                $data['msg'] = "该邮箱已存在,请重新输入";
                $data['code'] = 100;
            }
        }

        //用户昵称唯一
        if(!empty($params['username'])){
            $memberData = $emt->getRepository('StoreBundle:XcMember')
                ->findOneBy(array('nickname' => $params['username'])); //根据用户昵称获取用户信息
            if(!empty($memberData)){
                $data['msg'] = "该昵称已存在,请重新输入";
                $data['code'] = 100;
            }
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 重新发送激活码
     * @param Request $request
     * @return Response
     */
    public function resendCodeAction(Request $request){
        if($request->getMethod() === "POST"){
            $activateCode = $request->get('activate_code');
            $emt = $this->getDoctrine()->getManager();
            $activateData = $emt->getRepository('StoreBundle:XcActivate')
                ->findOneBy(array('activateCode' => $activateCode)); //根据激活码获取用户信息
            if(!empty($activateData)){
                $sendActivate = $this->_sendActivateCode($activateData->getEmail(), $activateData->getMemberId(), $activateCode);
                if($sendActivate){
                    $data['code'] = 200;
                }else{
                    $data['code'] = 300;
                }
            }else{
                $data['code'] = 404;
            }
        }

        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * 注册账号激活
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function activateAction(Request $request){
        $session = $request->getSession();
        $emt = $this->getDoctrine()->getManager();

        $activate = $emt->getRepository('StoreBundle:XcActivate')
            ->findOneBy(array('activateCode' => $request->get('code'))); //获取激活码信息
        if(time() > $activate->getExpiredDate()){
            return $this->redirect($this->generateUrl('PageBundle_user_registerFeedback',
                array('code' => $request->get('code'),'type' => 'pass')));
        }else{
            $member = $emt->getRepository('StoreBundle:XcMember')->findOneBy(array('id'=>$activate->getMemberId()));
            if($member->getIsActivate() == '1'){
                return $this->redirect($this->generateUrl('PageBundle_user_registerFeedback', array('type' => 'already')));
            }else{
                $member->setIsActivate('1');
                $emt->persist($member);
                $emt->flush();

                $session->set('member_id', $member->getId()); //session存储用户ID
                $session->set('username', $member->getNickname()); //session存储用户名

                return $this->redirect($this->generateUrl('PageBundle_user_registerFeedback', array('type' => 'after')));
            }
        }
    }

    /**
     * 注册成功，验证
     * @param Request $request
     * @return Response
     */
    public function feedbackAction(Request $request){
        $code = '';
        $type = $request->get('type');
        if(isset($type)){
            if($type == "before"){
                $type = "before";
            }elseif($type == 'already'){
                $type = "already";
            }elseif($type == 'pass'){
                $type = "pass";
                $code = $request->get('code');
            }else{
                $type = "after";
            }
        }

        return $this->render("PageBundle:User:feedback.html.twig",array("type"=>$type, 'code' => $code));
    }

    /**
     * 验证码
     */
    public function validateCodeAction(Request $request){
        $session = $request->getSession();

        //生成验证码图片
        Header("Content-type: image/PNG");
        $im = imagecreate(100,30); // 画一张指定宽高的图片
        $back = ImageColorAllocate($im, 245,245,245); // 定义背景颜色
        imagefill($im,0,0,$back); //把背景颜色填充到刚刚画出来的图片中
        $codes = "";

        $str = "abcdefhjkmnprstuvwxyz23456789";
        $randChar = str_split($str);

        for ($i = 0; $i < 4; $i++) {
            $authNum = $randChar[array_rand($randChar)];
            $font = ImageColorAllocate($im, rand(100,255),rand(0,100),rand(100,255)); // 生成随机颜色
            $codes .= $authNum;
            imagestring($im, 5, 19+$i*20, 6, $authNum, $font);
        }

        $session->set('validate_code', $codes);

        for($i=0;$i<100;$i++) //加入干扰象素
        {
            $randColor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
            imagesetpixel($im, rand()%100 , rand()%30 , $randColor); // 画像素点函数
        }
        ImagePNG($im);
        ImageDestroy($im);
    }

    /**
     * 发送激活码处理
     * @param $email
     * @param $memberId
     * @param $activateCode
     * @return bool
     */
    private function _sendActivateCode($email, $memberId, $activateCode = ""){
        $emt = $this->getDoctrine()->getManager();
        if(!empty($activateCode)){
            $activate = $emt->getRepository('StoreBundle:XcActivate')
                ->findOneBy(array('activateCode' => $activateCode)); //根据激活码获取用户信息
            if(empty($activate))
                return false;
        }else{
            $activate = new XcActivate();
            $activate->setEmail($email); //发送的邮箱
            $activate->setMemberId($memberId); //记录注册用户ID
        }

        //发送激活账号邮件
        $randCode = $this->_randCode();

        $expiredTime = strtotime("+1 day"); //设置过期时间
        $activate->setActivateCode($randCode); //随机激活码
        $activate->setExpiredDate($expiredTime);
        $emt->persist($activate);
        $emt->flush();

        $url = "http://" . $_SERVER ['HTTP_HOST'].$this->generateUrl('PageBundle_user_activate',array('code'=>$randCode));

        $message = \Swift_Message::newInstance()
            ->setSubject('秀财网注册验证邮件')
            ->setFrom('no-reply@xiucai.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'PageBundle:User:register_mail.html.twig',array('url'=>$url)
                )
            );
        $this->get('mailer')->send($message); //发送激活邮件

        return true;
    }

    /**
     * 随机字符
     * @return string
     */
    private function _randCode(){
        $chrCode = "";
        for($i = 0; $i < 20; $i++){
            $chrCode .= chr(mt_rand(97, 122)); //随机激活码
        }
        $randCode = substr(md5(time()+rand(100,30000)), 20).$chrCode;
        $randCode = str_shuffle($randCode);

        return $randCode;
    }

    /**
     * 获取客户端IP
     */
    public function getMemberIp(){
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if(getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if(getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else $ip = "Unknow";

        return $ip;
    }

    public function location($url,$msg,$ifClose=0){
        header('content-type:text/html;charset=utf-8;');
        $host = $_SERVER['HTTP_HOST'];
        echo "<script src='http://$host/assets/js/jquery-1.7.2.min.js'></script>";
        echo "<script src='http://$host/assets/js/layer/layer.js'></script>";
        echo "<body>";
        if($ifClose == 1){
            echo "<script>layer.alert('$msg','',function(){window.opener=null;window.close();});</script>";
        }else{
            echo "<script>layer.alert('$msg','',function(){location.href='".$url."';});</script>";
        }
        //echo "<script>layer.alert('$msg');location.href='".$url."';</script>";
        //echo "<script>layer.confirm('$msg', function(){location.href='".$url."';});</script>";
        //echo "<script>layer.msg('$msg',3,'',function(){location.href='".$url."'} );</script>";
        echo "</body>";
        exit;
    }
}
