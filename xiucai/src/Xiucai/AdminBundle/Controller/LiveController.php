<?php

namespace Xiucai\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xiucai\ServiceBundle\CornMeeting\CornMeeting;
use Xiucai\StoreBundle\Entity\XcConference;
use Xiucai\StoreBundle\Entity\XcLiveCourse;

class LiveController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AdminBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * 添加直播课程
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addLiveCourseAction(Request $request){
        $liveData = "";
        $conference = "";
        $enableEditor = "enable";
        $cCategory = ""; //课程子当前二级分类
        $categoryChild = ""; //课程所有二级分类
        $categoryParent = ""; //课程父级分类
        $liveInstructor = ""; //课程主讲

        $emt = $this->getDoctrine()->getManager();
        $imgToken = substr(md5(time()+rand(100,30000)), 16).rand(100,999); //生成imgToken，上传图片生成的随机参数

        //表单数据处理操作
        if($request->getMethod() === "POST"){
            $status = $request->get('live_status');
            if($status == 2 || $status == 4){
                //上传课程图片处理
                $year = date("Y");
                $mDay = date('md');
                $uploadPath = $this->get('kernel')->getRootDir().'/../web/assets/uploads/'.$year.'/'.$mDay.'/';
                if(!is_dir($uploadPath) ){
                    mkdir($uploadPath,0777,true);
                }

                //课程图片-小图
                if(!empty($_FILES["img_url"]["name"])){
                    $imgToken = substr(md5(time()+rand(100,30000)), 16).rand(100,999);
                    $lastPosition = strrpos($_FILES["img_url"]["name"], '.');
                    $imgType = substr($_FILES["img_url"]["name"], $lastPosition);
                    $imgName = $imgToken.$imgType;
                    $imgThumbName_1 = $imgToken.'_1'.$imgType;//280*192
                    $imgThumbName_2 = $imgToken.'_2'.$imgType;//200*138

                    $targetUrl = $uploadPath.$imgName;
                    move_uploaded_file($_FILES["img_url"]["tmp_name"], $targetUrl);
                    self::img2thumb($targetUrl,$uploadPath.$imgThumbName_1, 280,192, 0, 0);
                    self::img2thumb($targetUrl,$uploadPath.$imgThumbName_2, 200,138, 0, 0);
                    $imgUrl = '/assets/uploads/'.$year.'/'.$mDay.'/'.$imgName;
                }else{
                    $imgUrl = "";
                }

                //课程图片-大图
                if(!empty($_FILES["banner_url"]["name"])){
                    $imgToken = substr(md5(time()+rand(100,30000)), 16).rand(100,999);
                    $lastPosition = strrpos($_FILES["banner_url"]["name"], '.');
                    $imgType = substr($_FILES["banner_url"]["name"], $lastPosition);
                    $imgName = $imgToken.$imgType;

                    $targetUrl = $uploadPath.$imgName;
                    move_uploaded_file($_FILES["banner_url"]["tmp_name"], $targetUrl);
                    $bannerUrl = '/assets/uploads/'.$year.'/'.$mDay.'/'.$imgName;
                }else{
                    $bannerUrl = "";
                }

                //2:保存直播不创建直播会议室 4:保存并创建直播会议室
                $this->saveLiveCourse($request, $status, $imgUrl, $bannerUrl);
            }else{
                $url = $this->generateUrl('AdminBundle_Live_add');
                echo "<script>alert('保存直播课程状态有误。'); location.href='".$url."'</script>";
                exit;
            }
        }

        $liveId = $request->get('id');
        if(!empty($liveId)){
            $liveData = $emt->getRepository('StoreBundle:XcLiveCourse')->findOneBy(array('id' => $liveId));
            if(empty($liveData)){
                $url = $this->generateUrl('AdminBundle_Course_live');
                echo "<script>alert('该直播课程ID不存在');location.href='".$url."'</script>";
                exit;
            }

            //获取直播间信息
            $confId = $liveData->getConfId();
            if(!empty($confId)){
                $conference = $emt->getRepository('StoreBundle:XcConference')->findOneBy(array('id' => $confId));

                if(!empty($conference)){
                    //获取直播间状态
                    $cm = new CornMeeting(
                        $this->container->getParameter("app_type"),
                        $this->container->getParameter("app_ui_type"),
                        $this->container->getParameter("web_service_key"),
                        $this->container->getParameter("web_service_url")
                    );
                    $roomUser = $cm->wbGetRoomUsers($conference->getRoomToken(), 'all');
                    if(!empty($roomUser['moderator'])){
                        $moderator = $emt->getRepository('StoreBundle:XcOptions')->findOneBy(array('key' => 'moderator'));
                        $moderator = json_decode($moderator->getValue(), true);
                        if(!empty($moderator)){
                            foreach($moderator as $m){
                                $newUid[] = $m['uid'];
                            }
                        }

                        //获取该直播课程的管理员
                        foreach($roomUser['moderator'] as $key => $m){
                            if(in_array($m['uid'], $newUid)){
                                unset($roomUser['moderator'][$key]);
                            }else{
                                //获取管理员用户信息
                                $roomUserInfo = $emt->getRepository('StoreBundle:XcMember')
                                    ->findOneBy(array('id' => $m['uid'])); //根据用户邮箱获取用户信息
                                if(!empty($roomUser)){
                                    $roomUser['moderator'][$key] = $roomUserInfo;
                                }else{
                                    unset($roomUser['moderator'][$key]);
                                }
                            }
                        }
                    }

                    $roomToken = $conference->getRoomToken();
                    if(!empty($roomToken)){
                        $json = $cm->wbGetRoomStatus($roomToken);
                        if(!empty($json['exp'])){
                            /*echo "<script>alert('".$json['exp']."'); </script>";
                            exit;*/
                            $conference->roomStatus = 'CLOSED';
                        }else{
                            $conference->roomStatus = $json['status'];
                        }
                    }else{
                        $conference->roomStatus = "NONEXIST";
                    }
                }else{
                    $conference->roomStatus = "NONEXIST";
                }
            }

            //获取该主讲信息
            $liveInstructor = $emt->getRepository('StoreBundle:XcInstructor')
                ->findOneBy(array('id' => $liveData->getInstructorId()));

            //课程子集分类
            $categoryId = $liveData->getCategoryId();
            if(!empty($categoryId)){
                $categoryParent = $emt->getRepository('StoreBundle:XcCategory')
                    ->findOneBy(array('categoryId' => $liveData->getCategoryId(), 'parentCategoryId' => 0)); //父级分类
                if(empty($categoryParent)){
                    $cCategory = $emt->getRepository('StoreBundle:XcCategory')->findOneBy(array('categoryId' => $liveData->getCategoryId()));
                    if(!empty($cCategory)){
                        $categoryChild = $emt->getRepository('StoreBundle:XcCategory')
                            ->findBy(array('parentCategoryId' => $cCategory->getParentCategoryId())); //相同父级下的所有子集分类
                    }
                }else{
                    $categoryChild = $emt->getRepository('StoreBundle:XcCategory')
                        ->findBy(array('parentCategoryId' => $categoryParent->getCategoryId())); //相同父级下的所有子集分类
                }
            }

            //直播开始前十五分钟不可编辑相应内容
            $scheduleTime = $liveData->getScheduleTime();
            $scheduleTime = (array)$scheduleTime;
            $scheduleTime = strtotime($scheduleTime['date'])-900;
            if(time() <= $scheduleTime || empty($conference))
                $enableEditor = 'enable';
            else
                $enableEditor = 'enableFalse';
        }

        //直播课程最大限制人数
        $maxAttendee = $emt->getRepository('StoreBundle:XcOptions')->findOneBy(array('key' => 'max_attendee'));
        $maxAttendee = empty($maxAttendee) ? 500 : $maxAttendee->getValue();

        //获取所有主讲信息
        $courseTeacher = $emt->getRepository('StoreBundle:XcInstructor')->findAll();

        //课程父级分类
        $courseCategory = $emt->getRepository('StoreBundle:XcCategory')->findBy(array('parentCategoryId' => 0));

        //该直播课程管理员信息
        $roomUser = isset($roomUser['moderator']) ? $roomUser['moderator'] : "";

        return $this->render('AdminBundle:Live:add.html.twig',
            array(
                'roomUser' => $roomUser,
                'liveData' => $liveData,
                'imgToken' => $imgToken,
                'cCategory' => $cCategory,
                'conference' => $conference,
                'maxAttendee' => $maxAttendee,
                'enableEditor' => $enableEditor,
                'liveTeacher' => $courseTeacher,
                'categoryChild' => $categoryChild,
                'categoryParent' => $categoryParent,
                'liveCategory' => $liveInstructor,
                'courseCategory' => $courseCategory,
                'liveInstructor' => $liveInstructor,
            )
        );
    }

    /**
     * 保存直播课程信息
     * @param $request
     * @param $status 直播发布状态 2:保存直播不创建直播会议室 4:保存并创建直播会议室
     * @param $imgUrl
     * @param $bannerUrl
     */
    public function saveLiveCourse($request, $status, $imgUrl, $bannerUrl){
        $emt = $this->getDoctrine()->getManager();
        $liveId = $request->get('live_id');
        if(!empty($liveId)){
            $liveInstructor = $request->get('live_instructor');
            $live = $emt->getRepository('StoreBundle:XcLiveCourse')->findOneBy(array('id' => $liveId));
            $live->setRecordUrl($request->get('record_url')); //直播结束录制的视频地址

            if(!empty($liveInstructor))
                $live->setInstructorId($liveInstructor); //课程主讲

            $oldData['scheduleTime'] = $live->getScheduleTime();
            $oldData['duration'] = $live->getDuration();
            $oldData['title'] = $live->getTitle();
        }else{
            $live = new XcLiveCourse();
            $live->setStatus(2); //课程发布状态
            $live->setReserveNum(0);
            $live->setInstructorId($request->get('live_instructor')); //课程主讲
            $live->setCreateTime(new \DateTime(date("Y-m-d H:i:s")));

            //添加默认课程小图
            if(empty($imgUrl)){
                $live->setImgUrl('/assets/img/zhibo_desc.jpg'); //课程图片
            }

            //添加默认课程大图
            if(empty($bannerUrl)){
                $live->setBannerUrl('/assets/img/introduce_index.jpg'); //课程图片
            }

            $oldData['scheduleTime'] = '';
            $oldData['duration'] = '';
            $oldData['title'] = '';
        }

        $reserveInit = $request->get('reserve_init'); //初始化播放人数
        $reserveInit = empty($reserveInit) ? 0 : $reserveInit;

        $categoryId = $request->get('course_kind'); //课程级别
        $categoryId = empty($categoryId) ? $request->get('parent_course_kind') : $categoryId;

        //设置课程小图
        if(!empty($imgUrl)){
            $live->setImgUrl($imgUrl); //课程图片
        }

        //设置课程banner图片
        if(!empty($bannerUrl)){
            $live->setBannerUrl($bannerUrl); //课程banner图片
        }

        $live->setCategoryId($categoryId); //课程类别分类
        $live->setTags($request->get('tags')); //课程标签
        $live->setReserveInit($reserveInit); //课程初始化预约人数
        $live->setContent($request->get('content')); //课程详细内容
        $live->setDuration($request->get('duration')); //课程时长
        $live->setTitle($request->get('live_title')); //课程名称
        $live->setBrief($request->get('live_brief')); //课程简介
        $live->setCourseLevel($request->get('live_level')); //课程级别
        $live->setCurrentPrice($request->get('current_price')); //课程现价
        $live->setOriginalPrice($request->get('original_price')); //课程原价
        $live->setModifyTime(new \DateTime(date("Y-m-d H:i:s"))); //课程更新时间
        $live->setScheduleTime(new \DateTime($request->get('live_start_time'))); //直播开始时间
        $emt->persist($live); //保存直播信息
        $emt->flush();

        if($status == 4){
            $meetingId = $this->cornMeeting($request, $live, $oldData);
            if(is_numeric($meetingId)){
                $live->setConfId($meetingId);
                $emt->persist($live); //更新直播间ID
                $emt->flush();
            }else{
                $url = $this->generateUrl('AdminBundle_Live_add',array('id' => $live->getId()));
                echo "<script>alert('直播conference处理失败。');location.href='".$url."'</script>";
                exit;
            }
        }

        $url = $this->generateUrl('AdminBundle_Course_live'); //跳转到列表页
        echo "<script>location.href='".$url."'</script>";
        exit;
    }


    /**
     * 直播接口接入
     * @throws \Exception
     */
    public function cornMeeting($request, $live, $oldData){
        $cm = new CornMeeting(
            $this->container->getParameter("app_type"),
            $this->container->getParameter("app_ui_type"),
            $this->container->getParameter("web_service_key"),
            $this->container->getParameter("web_service_url")
        );

        $maxAttendee = $request->get('max_attendee');
        $maxAttendee = empty($maxAttendee) ? 500 : $maxAttendee;

        $type = "public"; //房间类型
        $joinType = "free&invite"; //加入方式
        $size = $maxAttendee; //房间大小

        $emt = $this->getDoctrine()->getManager();
        $owner = $emt->getRepository('StoreBundle:XcOptions')->findOneBy(array('key' => 'owner')); //演讲人-测试管理员用户jason
        $owner = $lecturer = json_decode($owner->getValue(), true); //拥有者/创建者,一般是企业用户对象

        $moderator = $emt->getRepository('StoreBundle:XcOptions')->findOneBy(array('key' => 'moderator'));
        $moderator = json_decode($moderator->getValue(), true);

        //直播课程时长验证30分钟，数据库里的时长不变
        $liveDuration = $emt->getRepository('StoreBundle:XcOptions')->findOneBy(array('key' => 'after_duration'));

        $startT = strtotime($request->get('live_start_time')); //直播开始时间
        $duration = $request->get('duration')*60; //直播时长秒数
        $duration = $duration+($liveDuration->getValue()*60); //直播时长延时

        $confId = $live->getConfId();
        if(!empty($confId)){
            //直播开始前十五分钟不可编辑相应内容
            $scheduleTime = $live->getScheduleTime();
            $scheduleTime = (array)$scheduleTime;
            $scheduleTime = strtotime($scheduleTime['date'])-900;
            if($scheduleTime <= time())
                return $confId;

            //更新会议室属性
            $conference = $emt->getRepository('StoreBundle:XcConference')->findOneBy(array('id' => $confId));
            if(empty($conference)){
                echo "<script>alert('会议ID不存在。');history.back();</script>";
                exit;
            }

            //房间所有成员属性
            $jsonRoomMember = $cm->wbGetRoomUsers($conference->getRoomToken(), 'all');
            if(!empty($jsonRoomMember['moderator'])){
                foreach($jsonRoomMember['moderator'] as $member){
                    $cm->wbUpdateRoomViewers($conference->getRoomToken(),'moderator','del',array($member));
                }
            }

            //直播课程管理员
            $addLiveAdmin = $request->get('add_live_admin');
            if(!empty($addLiveAdmin)){
                $addLiveAdmin = explode(",", $addLiveAdmin);
                foreach($addLiveAdmin as $adminEmail){
                    $liveAdminInfo = $emt->getRepository('StoreBundle:XcMember')
                        ->findOneBy(array('email' => $adminEmail)); //根据用户邮箱获取用户信息
                    if(!empty($liveAdminInfo)){
                        $newModerator['uid'] = $liveAdminInfo->getId(); //用户ID
                        $newModerator['logo'] = $liveAdminInfo->getAvatar(); //用户头像
                        $newModerator['name'] = $liveAdminInfo->getNickname(); //用户昵称
                        $moderator[] = $newModerator;
                        unset($newModerator);
                    }
                }
            }

            //更新直播间属性
            $callbackJson = $cm->wbUpdateRoomProperty($conference->getRoomToken(), $type, $joinType, $size, $startT, $duration);

            //更新直播间管理员
            $cm->wbUpdateRoomViewers($conference->getRoomToken(),'moderator','update',$moderator);
            //$t2 = $cm->wbUpdateRoomViewers($conference->getRoomToken(),'lecturer','update',array($lecturer));
        }else{
            //直播课程管理员
            $addLiveAdmin = $request->get('add_live_admin');
            if(!empty($addLiveAdmin)){
                $addLiveAdmin = explode(",", $addLiveAdmin);
                foreach($addLiveAdmin as $adminEmail){
                    $liveAdminInfo = $emt->getRepository('StoreBundle:XcMember')
                        ->findOneBy(array('email' => $adminEmail)); //根据用户邮箱获取用户信息
                    if(!empty($liveAdminInfo)){
                        $newModerator['uid'] = $liveAdminInfo->getId(); //用户ID
                        $newModerator['logo'] = $liveAdminInfo->getAvatar(); //用户头像
                        $newModerator['name'] = $liveAdminInfo->getNickname(); //用户昵称
                        $moderator[] = $newModerator;
                        unset($newModerator);
                    }
                }
            }

            //创建直播间
            $callbackJson = $cm->wbCreateRoom($type, $joinType, $size, $lecturer, $moderator, $owner, $startT, $duration);

            $conference = new XcConference();
            $conference->setCreateTime(new \DateTime(date("Y-m-d H:i:s"))); //创建时间
        }
        if(!empty($callbackJson['exp'])){
            if(!empty($confId)){
                $scheduleTime = (array)$oldData['scheduleTime'];
                $scheduleTime = $scheduleTime['date'];
                $live->setTitle($oldData['title']); //名称还原
                $live->setDuration($oldData['duration']); //时长还原
                $live->setScheduleTime(new \DateTime($scheduleTime)); //时间还原
                $emt->persist($live); //保存直播信息
                $emt->flush();

                return $confId;
            }

            $url = $this->generateUrl('AdminBundle_Live_add',array('id' => $live->getId()));
            echo "<script>alert('".$callbackJson['exp']."'); location.href='".$url."'</script>";
            exit;
        }else if(!empty($callbackJson['roomtoken']) || $callbackJson['ret'] == "success"){
            //创建直播会议室成功处理
            if(!empty($callbackJson['roomtoken'])){
                $conference->setRoomToken($callbackJson['roomtoken']); //直播唯一标识
            }

            $conference->setPrivacy(3); //房间属性 1；private 2: part public 3: public
            $conference->setRoomType(4); //房间类型 4: 在线教育培训
            $conference->setIsRecord(0); //是否已录制
            $conference->setNumAttendee(0); //实际参会人数
            $conference->setConferenceStatus(3); //直播间状态 3:running 6:closed
            $conference->setMaxAttendee($maxAttendee); //直播间最多人数，默认缺省值为500
            $conference->setStatus(4); //live_course表中的status保持一致
            //$conference->setStatus($request->get('status')); //live_course表中的status保持一致
            $conference->setTitle($request->get('live_title')); //直播间名称
            $conference->setDuration($request->get('duration')); //直播时长
            $conference->setModifyTime(new \DateTime(date("Y-m-d H:i:s"))); //修改时间
            $conference->setVideoProtocol($request->get('video_protocol')); //直播协议
            $conference->setScheduleTime(new \DateTime($request->get('live_start_time'))); //直播开始时间
            $emt->persist($conference); //添加直播间信息
            $emt->flush();

            //管理员添加购该权限
            $alipay = $this->get('payment_service');
            $courseService = $this->get('course_service');

            $roomAdminInfo[$lecturer['uid']] = $lecturer;
            if(!empty($moderator)){
                foreach($moderator as $m){
                    $roomAdminInfo[$m['uid']] = $m;
                }
            }

            if(!empty($roomAdminInfo)){
                foreach($roomAdminInfo as $admin){
                    //判断用户是否购买过该课程
                    $isInventory = $alipay->existCourseInventory($admin['uid'], $live->getId(), 2);
                    if(!$isInventory){
                        $alipay->insertCourseInventory(array('member_id' => $admin['uid'],
                            'content_id' => $live->getId(), 'content_type'=>2));

                        //添加用户直播课程列表
                        $courseService->setMemberLive($startT, $live->getId(), $admin['uid']);

                        //直播课程预约人数列表
                        $courseService->setLiveCourseReserveCount($live->getId());

                        //单个直播课程预约用户列表
                        $user = json_encode($admin);
                        $courseService->setLiveCourseReserve($live->getId(), $user);

                    }
                }
            }

            return $conference->getId();
        }else{
            throw new \Exception('与会议室通信失败');
            exit;
        }
    }

    /**
     * 验证注册邮箱
     * @param Request $request
     * @return Response
     */
    public function addLiveAdminAction(Request $request){
        $result['code'] = 300;
        $email = $request->get('email');

        //邮箱非空，格式判断
        if(!empty($email)){
            $pregEmail = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/i";
            if(preg_match($pregEmail,$email)){
                $emt = $this->getDoctrine()->getManager();
                $moderator = $emt->getRepository('StoreBundle:XcOptions')->findOneBy(array('key' => 'moderator'));
                $moderator = json_decode($moderator->getValue(), true);
                if(!empty($moderator)){
                    foreach($moderator as $m){
                        $newUid[] = $m['uid'];
                    }
                }

                $memberInfo = $emt->getRepository('StoreBundle:XcMember')
                    ->findOneBy(array('email' => $email)); //根据用户邮箱获取用户信息
                if(empty($memberInfo)){
                    $result['msg'] = "请输入秀财网用户注册邮箱。";
                }else{
                    if(!empty($newUid)){
                        if(in_array($memberInfo->getId(), $newUid)){
                            $result['msg'] = "该邮箱用户已经是直播管理员。";
                        }else{
                            $result['code'] = 200;
                        }
                    }else{
                        $result['code'] = 200;
                    }
                }
            }else{
                $result['msg'] = "您的邮箱格式不正确,请重新输入。";
            }
        }else{
            $result['msg'] = "请输入邮箱地址。";
        }

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    public function fileext($file)
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * 生成缩略图
     * @param string     源图绝对完整地址{带文件名及后缀名}
     * @param string     目标图绝对完整地址{带文件名及后缀名}
     * @param int        缩略图宽{0:此时目标高度不能为0，目标宽度为源图宽*(目标高度/源图高)}
     * @param int        缩略图高{0:此时目标宽度不能为0，目标高度为源图高*(目标宽度/源图宽)}
     * @param int        是否裁切{宽,高必须非0}
     * @param int/float  缩放{0:不缩放, 0<this<1:缩放到相应比例(此时宽高限制和裁切均失效)}
     * @return boolean
     */
    public function img2thumb($src_img, $dst_img, $width = 75, $height = 75, $cut = 0, $proportion = 0)
    {
        if(!is_file($src_img))
        {
            return false;
        }
        $ot = self::fileext($dst_img);
        $otfunc = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
        $srcinfo = getimagesize($src_img);
        $src_w = $srcinfo[0];
        $src_h = $srcinfo[1];
        $type  = strtolower(substr(image_type_to_extension($srcinfo[2]), 1));
        $createfun = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);

        $dst_h = $height;
        $dst_w = $width;
        $x = $y = 0;

        /**
         * 缩略图不超过源图尺寸（前提是宽或高只有一个）
         */
        if(($width> $src_w && $height> $src_h) || ($height> $src_h && $width == 0) || ($width> $src_w && $height == 0))
        {
            $proportion = 1;
        }
        if($width> $src_w)
        {
            $dst_w = $width = $src_w;
        }
        if($height> $src_h)
        {
            $dst_h = $height = $src_h;
        }

        if(!$width && !$height && !$proportion)
        {
            return false;
        }
        if(!$proportion)
        {
            if($cut == 0)
            {
                if($dst_w && $dst_h)
                {
                    if($dst_w/$src_w> $dst_h/$src_h)
                    {
                        $dst_w = $src_w * ($dst_h / $src_h);
                        $x = 0 - ($dst_w - $width) / 2;
                    }
                    else
                    {
                        $dst_h = $src_h * ($dst_w / $src_w);
                        $y = 0 - ($dst_h - $height) / 2;
                    }
                }
                else if($dst_w xor $dst_h)
                {
                    if($dst_w && !$dst_h)  //有宽无高
                    {
                        $propor = $dst_w / $src_w;
                        $height = $dst_h  = $src_h * $propor;
                    }
                    else if(!$dst_w && $dst_h)  //有高无宽
                    {
                        $propor = $dst_h / $src_h;
                        $width  = $dst_w = $src_w * $propor;
                    }
                }
            }
            else
            {
                if(!$dst_h)  //裁剪时无高
                {
                    $height = $dst_h = $dst_w;
                }
                if(!$dst_w)  //裁剪时无宽
                {
                    $width = $dst_w = $dst_h;
                }
                $propor = min(max($dst_w / $src_w, $dst_h / $src_h), 1);
                $dst_w = (int)round($src_w * $propor);
                $dst_h = (int)round($src_h * $propor);
                $x = ($width - $dst_w) / 2;
                $y = ($height - $dst_h) / 2;
            }
        }
        else
        {
            $proportion = min($proportion, 1);
            $height = $dst_h = $src_h * $proportion;
            $width  = $dst_w = $src_w * $proportion;
        }

        $src = $createfun($src_img);
        $dst = imagecreatetruecolor($width ? $width : $dst_w, $height ? $height : $dst_h);
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);

        if(function_exists('imagecopyresampled'))
        {
            imagecopyresampled($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        else
        {
            imagecopyresized($dst, $src, $x, $y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
        }
        $otfunc($dst, $dst_img);
        imagedestroy($dst);
        imagedestroy($src);
        return true;
    }
}
