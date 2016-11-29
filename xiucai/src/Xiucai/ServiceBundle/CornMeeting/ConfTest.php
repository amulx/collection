<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-10-8
 * Time: 下午2:34
 * To change this template use File | Settings | File Templates.
 */

namespace Xiucai\ServiceBundle\CornMeeting;

/**
 * 描述：会议室测试类
 */
final class ConfTest {

    /**
     * 描述：接口工具类
    */
    private $cm = null;

    public function __construct($cm)
    {
        if($cm instanceof CornMeeting){}
        else{
            throw new \Exception("must be instance of CornMeeting !");
        }
        $this->cm = $cm;
    }


    /**
     * 描述：测试创建房间
    */
    public function testwbCreateRoom(){
        /**
        type:{房间类型,public|private|part_public},
        jointype:{加入方式,free&invite|apply&invite|invite},
        size:{房间大小},
        lecturer:{演讲人{用户对象}},//只能一个人
        moderator:[主持人{用户对象数组}},//至少一个人,是一个对象数组
        owner:{用户对象,拥有者/创建者,一般是企业用户对象},
        startT:{房间开始时间,long类型,精确到秒},
        duration:{房间时长,long类型,精确到秒},
        */
        $type = "public";
        $jointype = "free&invite";
        $size = 500;
        $lecturer =
            array("uid" => "1234567890", "logo" => "asset/imgs/logo.jpg", "name" => "dtsola");
        $moderator = array(
            array("uid" => "2234567890", "logo" => "asset/imgs/logo.jpg", "name" => "tom"),
            array("uid" => "3234567890", "logo" => "asset/imgs/logo.jpg", "name" => "tim")
        );
        $owner =
            array("uid" => "1234567890", "logo" => "asset/imgs/logo.jpg", "name" => "dtsola");
        $startT = time() + 180000;
        $duration = 600000;
        //.
//        var_dump("dt : " . $this->cm->getWebServiceUrl());
        $json =
            $this->cm->wbCreateRoom($type, $jointype, $size, $lecturer, $moderator, $owner, $startT, $duration);
        //
        var_dump($json);
//        //
        //d7e08c97c9a2fff0f8eebfe9586cab27
    }

    /**
     * 描述：测试获取房间状态
    */
    public function testwbGetRoomStatus(){
        /**
            会议室状态:
            1.不存在(NONEXIST)
            更新到此状态后,相当于删除房间!
            2.就绪(READY)
            通过设置此状态,可以创建一个房间!,默认情况下主持人和演讲人提前30分钟可以进入房间,其他人提前15分钟可以进入!(这个规则由web端控制)
            3.已开始(START)
            在状态值非NONEXIST状态下,更新此状态会强制设置会议的开始时间为当前时间!意味着可以重开会议或提前开始会议!!!
            4.进行中(RUNNING)
            此状态为只读,不能手动修改!
            5.超时中(TIMEOUT)
            在状态值非NONEXIST状态下,此状态下会倒计时30分钟(倒计时时间可以自定义)关闭课堂!
            6.已结束(CLOSED)
            在状态值非NONEXIST状态下,此状态会踢掉所有在线人员,并关闭会议室!
            7.锁定中(LOCKED)
            在状态值非NONEXIST状态下,此状态下,任何人员无法登入会议室,且只出不进!
         */
        $roomToken = "d58d81bbf4bdeb343eda113b4afd717e";
        $json = $this->cm->wbGetRoomStatus($roomToken);
        //
        var_dump($json);
    }

    /**
     * 描述：测试获取房间属性
    */
    public function testwbGetRoomProperty(){
        /**
            AppType:””,
            UIType:””,
            roomtoken:{房间唯一识别码},
            title:{房间标题},
            logo:{房间logo url},
            type:{房间类型,public|private|part_public},
            jointype:{加入方式,free&invite|apply&invite|invite},
            size:{房间大小},
            detail:{房间详情介绍,支持富文本},
            startT:{房间开始时间},
            duration:{房间时长},
            exp:{debug信息},
            sn:{效验字符串}
         */
        $roomToken = "d58d81bbf4bdeb343eda113b4afd717e";
        $json = $this->cm->wbGetRoomProperty($roomToken);
        //
        var_dump($json);
    }

    /**
     * 描述：测试获取房间用户列表
     */
    public function testwbGetRoomUsers(){
        /**
             roomtoken:{房间唯一标识},
            type:{onlineNum|onlineList|all},//默认值为onlineNum
            sn:{效验字符串}
        */
        $roomToken = "d58d81bbf4bdeb343eda113b4afd717e";
        $type = "all";
        $json = $this->cm->wbGetRoomUsers($roomToken, $type);
        //
        var_dump($json);
    }

    /**
     * 描述：测试更新房间状态
    */
    public function testwbUpdateRoomStatus(){
        //
    }

    /**
     * 描述：测试更新房间属性
     */
    public function testwbUpdateRoomProperty(){
        /**
            AppType:””,
            UIType:””,
            roomtoken:{房间唯一识别码},
            type:{房间类型,public|private|part_public},
            jointype:{加入方式,free&invite|apply&invite|invite},
            size:{房间大小},
            startT:{房间开始时间},
            duration:{房间时长},
            sn:{效验字符串}
         */
        //
        $roomtoken = "d58d81bbf4bdeb343eda113b4afd717e";
        $type = "public";
        $jointype = "free&invite";
        $size = 1000;
        $startT = time() + 1800;
        $duration = 8000;
        //
        $json =
            $this->cm->wbUpdateRoomProperty($roomtoken, $type, $jointype, $size, $startT, $duration);
        //
        var_dump($json);
    }

    /**
     * 描述：测试 对房间用户进行，增，删，改
    */
    public function testwbUpdateRoomViewers(){
        //
        /**
            roomtoken:房间唯一标识
            type:{moderator|lecturer|viewer|invitee},
            action:{add|del|update},
            data:[用户对象数组],
            sn:{效验字符串}
         */
        //
        $roomtoken = "d58d81bbf4bdeb343eda113b4afd717e";
        $type = "viewer";
        $action = "add";
        $data = array(
            array("uid" => "w2234567890", "logo" => "asset/imgs/logo.jpg", "name" => "slg2012"),
            array("uid" => "w3234567890", "logo" => "asset/imgs/logo.jpg", "name" => "psv5252")
        );
        //
        $json =
            $this->cm->wbUpdateRoomViewers($roomtoken, $type, $action, $data);
        //
        var_dump($json);
    }

    /**
     * 描述：测试根据用户id获取用户 文件列表
    */
    public function testwbGetFSListByUid(){
        $uid = md5("dtsola");
        //
        //
        $json =
            $this->cm->wbGetFSListByUid($uid);
        //
        var_dump($json);
    }

    /**
     * 描述：测试删除指定文件
    */
    public function testwbResDel(){
        $filetoken = "06d738e5921390b7e48efb37be8608e6";
        //
        //
        $json =
            $this->cm->wbResDel($filetoken);
        //
        var_dump($json);
    }

    /**
     * 描述：测试获取资源URl
    */
    public function testwbResEx(){
        $key = "2014/1008/1316/cf8be481ba8027f583500e5ed27be8ff.pdf";
        //
        $json =
            $this->cm->wbResEx($key);
        //
        var_dump($json);
    }

    /**
     * 描述：测试根据roomtoken获取房间文件列表
    */
    public function testwbGetFile4RoomSpace(){
        $roomtoken = "default4demo";
        //
        $json =
            $this->cm->wbGetFile4RoomSpace($roomtoken);
        //
        var_dump($json);
    }
    //

    /**
     * 描述：测试进入房间
    */
    public function testwbRooms(){
        $roomtoken = "d58d81bbf4bdeb343eda113b4afd717e";
        $user =
            array("uid" => md5("dtsola"), "logo" => "asset/imgs/logo.jpg", "name" => "dtsola");
        $roomvvtype = RoomVVType::$RTMP_P2P;
        //
        //
        $json =
            $this->cm->wbRooms($roomtoken, $user, $roomvvtype);
        //
//        var_dump($json);
        header("Location: $json");
        exit;
    }
    //

}

