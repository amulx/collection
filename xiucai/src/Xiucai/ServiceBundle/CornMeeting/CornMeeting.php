<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dtsola
 * Date: 14-09-29
 * Time: 下午2:46
 * To change this template use File | Settings | File Templates.
 */
namespace Xiucai\ServiceBundle\CornMeeting;
final class object{}
/**
 * 描述：会议主动接口类<br/>
 * @version : 1.0.0<br/>
 * @lastupdate : 20140930<br/>
 */
final class CornMeeting {
    /** 描述：应用类型，主要决定了，webService服务器回调的（被动接口）地址<br/>
     *  取值：<br/>
     *      xiucai : 秀才版本生产环境应用<br/>
     *      xiucai4curtest : 秀才版本测试环境应用<br/>
     *      xiucai4test : 秀才最新稳定版本环境应用<br/>
     *
     */
    private $appType = null;
    /**
     * 描述：房间UI类型<br/>
     * 取值：<br/>
     * xiucai : 秀才版本UI
     *
    */
    private $uiType = null;
    /**
     * 描述：主动接口调用密钥KEY<br/>
     * 对应JAVA端：/Yumi4Sina/src/dtsolaConfig.properties<br/>
     *              配置参数：XorEnKey <br/>
     * 测试：roomtest1.yumihuiyi.cn <br/>
     * 正式：3i*&jKS%hd*8J^x
     */
    private $xorEnKey = null;
    /**
     * 主动接口服务根地址<br/>
     *
    */
    private $webServiceUrl = null;


    /***
     * 描述：构造器<br/>
     * @param $appType：应用类型<br/>
     * @param $uiType：房间UI类型<br/>
     * @param $xorEnKey：密钥<br/>
     * @param $webServiceUrl：主动接口服务根地址<br/>
     */
    public function __construct($appType, $uiType, $xorEnKey, $webServiceUrl)
    {
        $this->appType = $appType;
        $this->uiType = $uiType;
        $this->xorEnKey = $xorEnKey;
        $this->webServiceUrl = $webServiceUrl;
        //
    }

    /**
     * 描述：获取主动接口服务根地址
     */
    public function getWebServiceUrl(){
        return $this->webServiceUrl;
    }

    /**
     * 描述：设置主动接口服务根地址
     */
    public function setWebServiceUrl($webServiceUrl){
        $this->webServiceUrl = $webServiceUrl;
    }

    /**
     * 描述：获取密钥
     */
    public function getXorEnKey(){
        return $this->xorEnKey;
    }

    /**
     * 描述：设置密钥
     */
    public function setXorEnKey($xorEnKey){
        $this->xorEnKey = $xorEnKey;
    }

    /**
     * 描述：获取应用类型
    */
    public function getAppType(){
        return $this->appType;
    }

    /**
     * 描述：设置应用类型
     */
    public function setAppType($appType){
        $this->appType = $appType;
    }

    /**
     * 描述：获取房间UI类型
     */
    public function getUIType(){
        return $this->uiType;
    }

    /**
     * 描述：设置房间UI类型
     */
    public function setUIType($uiType){
        $this->uiType = $uiType;
    }

    /**
     * 描述：改变配置参数<br/>
     * @param $appType：应用类型<br/>
     * @param $uiType：房间UI类型<br/>
     * @param $xorEnKey：密钥<br/>
     * @param $webServiceUrl：主动接口服务根地址<br/>
     * @return 无<br/>
     * @explain 如果传的值为null，会保留原始值<br/>
    */
    public function changeConfig($appType=null, $uiType=null, $xorEnKey=null, $webServiceUrl=null){
        if($appType != null || $appType != ""){
            $this->appType = $appType;
        }
        //
        if($uiType != null || $uiType != ""){
            $this->uiType = $uiType;
        }
        //
        if($xorEnKey != null || $xorEnKey != ""){
            $this->xorEnKey = $xorEnKey;
        }
        //
        if($webServiceUrl != null || $webServiceUrl != ""){
            $this->webServiceUrl = $webServiceUrl;
        }
    }

    //////////////////////////////////////

    //
    /**
     * 描述：申请一个房间链接
     * @param $type：房间类型,public|private|part_public
     * @param $jointype：加入方式,free&invite|apply&invite|invite
     * @param $size：房间大小
     * @param $lecturer：演讲人(用户对象),//只能一个人
     * @param $moderator：主持人(用户对象数组),//至少一个人,是一个对象数组
     * @param $owner：用户对象,拥有者/创建者,一般是企业用户对象
     * @param $startT：房间开始时间,long类型,精确到秒
     * @param $duration：房间时长,long类型,精确到秒
     * @return json
     *              roomtoken:房间token值,
     *              exp:如果token值返回为null或空,通常是有异常发生,需要参看此参数作为debug判断,
     *              sn:效验字符串
     */
    public function wbCreateRoom($type, $jointype, $size, $lecturer, $moderator, $owner, $startT, $duration){
        $obj = array();
        $obj["type"] = $type;//{房间类型,public|private|part_public},
        $obj["jointype"] = $jointype;//{加入方式,free&invite|apply&invite|invite},
        $obj["size"] = $size;//{房间大小},
        $obj["lectuer"] = $lecturer;//{演讲人{用户对象数组}},
        $obj["moderator"] = $moderator;//{主持人{用户对象数组}},
        $obj["owner"] = $owner;
        $obj["startT"] = $startT;//{房间开始时间,long类型,精确到秒},
        $obj["duration"] = $duration;//{房间时长,long类型,精确到秒},
        $obj["appType"] = $this->appType;
        $obj["uiType"] = $this->uiType;
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
        //
        $str = $this->encrypt(json_encode($obj), $this->keys());
        $str = urlencode($str);
        $str = str_replace("%", "@", $str);
        $ret = file_get_contents($this->rooturl()."CreateRoom/".$str);
        $ret = $this->encrypt($ret, $this->keys());
        $ret = json_decode($ret, true);

        if(!isset($ret["roomtoken"]))
        {
            $ret["roomtoken"] = "";
        }
//        return $ret["roomtoken"];
        return $ret;
    }


//    /**
//     * 描述：进入房间（全屏）
//     * @param $roomtoken：房间token值
//     * @param $user：用户对象
//     * @param $sina_token：用户授权后的token值
//     * @return null
//     */
//    public function wbGoToRoom4FullScreen($roomtoken, $user, $sina_token){
//        $obj = array();
//        $obj["roomtoken"] = $roomtoken;//{房间token值},
//        $obj["user"] = $user;//{用户对象},
//        $obj["sina_token"] = $sina_token;//{/用户授权后的token值},
//        $currentTime = $this->getSNCurrentTime();
//        $obj["sn"] = $this->sn($currentTime);
//        $obj["currentTime"] = $currentTime;
//        $str = $this->encrypt(json_encode($obj), $this->keys());
//        //
//        $str = urlencode($str);
//        $str = str_replace("%", "@", $str);
//        return ($this->rooturl()."GoToRoom4FullScreen/".$str);
//
//    }

//    /**
//     * 描述：进入房间
//     * @param $roomtoken：房间token值
//     * @param $user：用户对象
//     * @param $sina_token：用户授权后的token值
//     * @return null
//     */
//    public function wbGoToRoom($roomtoken, $user, $sina_token){
//        $obj = array();
//        $obj["roomtoken"] = $roomtoken;//{房间token值},
//        $obj["user"] = $user;//{用户对象},
//        $obj["sina_token"] = $sina_token;//{/用户授权后的token值},
//        $currentTime = $this->getSNCurrentTime();
//        $obj["sn"] = $this->sn($currentTime);
//        $obj["currentTime"] = $currentTime;
//        $str = $this->encrypt(json_encode($obj), $this->keys());
//        //
//        $str = urlencode($str);
//        $str = str_replace("%", "@", $str);
//        return ($this->rooturl()."GoToRoom/".$str);
//
//    }

//    /**
//     * 描述：获得当前房间列表
//     * @param $status：房间状态关键字,可以是多个状态,不写关键字匹配所有状态
//     * @param $page：分页码,从1开始计
//     * @param $size：分页大小,10,20,30
//     * @param $search：搜索关键字,默认值为all,不匹配特定字符串
//     * @param $owner：创建者uid,如果uid=all,匹配所有创建者
//     * @param $sort：默认值按创建时间排序
//     * @return json
//     */
//    public function wbGetRoomList($status, $page, $size, $search, $owner, $sort){
//        $obj=array();
//        $obj["status"] = $status;//[房间状态关键字,可以是多个状态,不写关键字匹配所有状态],
//        $obj["page"] = $page;//{分页码,从1开始计},
//        $obj["size"] = $size;//{分页大小,10,20,30},
//        $obj["search"] = $search;//{搜索关键字,默认值为all,不匹配特定字符串},
//        $obj["owner"] = $owner;//{创建者uid,如果uid=all,匹配所有创建者},
//        $obj["sort"] = $sort;//{默认值按创建时间排序},
//        $currentTime = $this->getSNCurrentTime();
//        $obj["sn"] = $this->sn($currentTime);
//        $obj["currentTime"] = $currentTime;
//        //
//        $str = $this->encrypt(json_encode($obj), $this->keys());
//        $str = urlencode($str);
//        $str = str_replace("%", "@", $str);
//        $ret = file_get_contents($this->rooturl()."GetRoomList/".$str);
//        $ret = $this->encrypt($ret, $this->keys());
//        $ret = json_decode($ret, true);
//        return $ret;
//
//    }

    /**
     * 描述：获得某个房间的状态
     * @param $roomtoken：房间唯一标识
     * @return json
     * 会议室状态:
     *  1.不存在(NONEXIST)
     *  更新到此状态后,相当于删除房间!
     *  2.就绪(READY)
     *  通过设置此状态,可以创建一个房间!,默认情况下主持人和演讲人提前30分钟可以进入房间,其他人提前15分钟可以进入!(这个规则由web端控制)
     *  3.已开始(START)
     *  在状态值非NONEXIST状态下,更新此状态会强制设置会议的开始时间为当前时间!意味着可以重开会议或提前开始会议!!!
     *  4.进行中(RUNNING)
     *  此状态为只读,不能手动修改!
     *  5.超时中(TIMEOUT)
     *  在状态值非NONEXIST状态下,此状态下会倒计时30分钟(倒计时时间可以自定义)关闭课堂!
     *  6.已结束(CLOSED)
     *  在状态值非NONEXIST状态下,此状态会踢掉所有在线人员,并关闭会议室!
     *  7.锁定中(LOCKED)
     *  在状态值非NONEXIST状态下,此状态下,任何人员无法登入会议室,且只出不进!
     *
     */
    public function wbGetRoomStatus($roomtoken){
        $obj = array();
        $obj["roomtoken"] = $roomtoken;
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
        //
        $str = $this->encrypt(json_encode($obj), $this->keys());
        $str = urlencode($str);
        $str = str_replace("%", "@", $str);
        $ret = file_get_contents($this->rooturl()."GetRoomStatus/".$str);
        $ret = $this->encrypt($ret, $this->keys());
        $ret = json_decode($ret, true);
        return $ret;

    }

    /**
     * 描述：获得某个房间的属性
     * @param $roomtoken：房间唯一标识
     * @return json
     */
    public function wbGetRoomProperty($roomtoken){
        $obj = array();
        $obj["roomtoken"] = $roomtoken;
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
        //
        $str = $this->encrypt(json_encode($obj), $this->keys());
        $str = urlencode($str);
        $str = str_replace("%", "@", $str);
        $ret = file_get_contents($this->rooturl()."GetRoomProperty/".$str);
        $ret = $this->encrypt($ret, $this->keys());
        $ret = json_decode($ret, true);
        return $ret;

    }

    /**
     * 描述：获得某个房间的人员列表
     * @param $roomtoken：房间唯一标识
     * @param $type：onlineNum|onlineList|all,//默认值为onlineNum
     * @return json
     */
    public function wbGetRoomUsers($roomtoken, $type){
        $obj = array();
        $obj["roomtoken"] = $roomtoken;
        $obj["type"] = $type;//{onlineNum|onlineList|all},默认值为onlineNum
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
        //
        $str = $this->encrypt(json_encode($obj),$this->keys());
        $str = urlencode($str);
        $str = str_replace("%","@",$str);
        $ret = file_get_contents($this->rooturl()."GetRoomUsers/".$str);
        $ret = $this->encrypt($ret,$this->keys());
        $ret = json_decode($ret,true);
        return $ret;

    }

    /**
     * 描述：修改房间状态（不推荐使用）
     * @param $roomtoken：房间唯一标识
     * @param $status：状态字符串
     * @param $data：自定义数据
     * @return json
     * @deprecated
     * @since 1.0.0
     */
    public function wbUpdateRoomStatus($roomtoken, $status, $data){
        $obj = array();
        $obj["roomtoken"] = $roomtoken;
        $obj["status"] = $status;//{状态字符串},不存在(NONEXIST)就绪(READY)已开始(START)进行中(RUNNING)此状态为只读,不能手动修改!超时中(TIMEOUT)已结束(CLOSED)锁定中(LOCKED)
        $obj["data"] = $data;//{自定义数据},
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
        //
        $str = $this->encrypt(json_encode($obj), $this->keys());
        $str = urlencode($str);
        $str = str_replace("%", "@", $str);
        $ret = file_get_contents($this->rooturl()."UpdateRoomStatus/".$str);
        $ret = $this->encrypt($ret, $this->keys());
        $ret = json_decode($ret, true);
        return $ret;

    }

    /**
     * 描述：修改房间属性
     * @param $roomtoken：房间唯一标识
     * @param $type：房间类型,public|private|part_public
     * @param $jointype：加入方式,free&invite|apply&invite|invite
     * @param $size：房间大小
     * @param $startT：房间开始时间
     * @param $duration：房间时长
     * @return json
     */
    public function wbUpdateRoomProperty($roomtoken, $type, $jointype, $size, $startT, $duration){
        $obj = array();
        $obj["roomtoken"] = $roomtoken;
        $obj["type"] = $type;//{房间类型,public|private|part_public},
        $obj["jointype"] = $jointype;//{加入方式,free&invite|apply&invite|invite},
        $obj["size"] = $size;
        $obj["startT"] = $startT;//{房间开始时间},
        $obj["duration"] = $duration;//{房间时长},
        $obj["appType"] = $this->appType;
        $obj["uiType"] = $this->uiType;
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
        //
        $str = $this->encrypt(json_encode($obj), $this->keys());
        $str = urlencode($str);
        $str = str_replace("%", "@", $str);
        $ret = file_get_contents($this->rooturl()."UpdateRoomProperty/".$str);
        $ret = $this->encrypt($ret, $this->keys());
        $ret = json_decode($ret, true);
        return $ret;
    }

    /**
     * 描述：修改房间合法成员
     * @param $roomtoken：房间唯一标识
     * @param $type：moderator|lecturer|viewer|invitee
     * @param $action：add|del|update
     * @param $data：用户对象数组
     * @return json
     */
    public function wbUpdateRoomViewers($roomtoken, $type, $action, $data){
        $obj = array();
        $data[0]["name"] = urlencode($data[0]["name"]);
        $obj["roomtoken"] = $roomtoken;
        $obj["type"] = $type;//{moderator|lecturer|viewer|invitee},
        $obj["action"] = $action;//{add|del|update},
        $obj["data"] = $data;//[用户对象数组],
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
//        ps:对演讲人或主持人只能进行action=update操作,add或del操作都会失败!
//        主持人和演讲人只能设置一个uid,如果在data中写入了多个uid,将只会读取data[0]
//        设置主持人和演讲人的时候data.length不能<=0,且data[0]不能等于null或空字符串
        $str= json_encode($obj);
        $str=urldecode($str);
        $str = $this->encrypt($str, $this->keys());
        $str = urlencode($str);
        $str = str_replace("%", "@", $str);
        $ret = file_get_contents($this->rooturl()."UpdateRoomUsers/".$str);
        $ret = $this->encrypt($ret, $this->keys());
        $ret = json_decode($ret, true);
        return $ret;

    }

    /**
     * 描述：根据UID获取所有文档列表
     * @param $uid：用户ID
     * @return json
     */
    public function wbGetFSListByUid($uid){
        $ret = file_get_contents($this->rooturl()."getFSListByUid/".$uid);
        $ret = json_decode($ret, true);
        /*
        $i = 0;
        $j = 1024 * 1024;
        for(;$i < count($ret);$i++){
            //{文件大小单位转换为MB
            $ret[$i]["fsize"] = ($ret[$i]["fsize"] / $j);
            $ret[$i]["fsize"] = ($ret[$i]["fsize"]) < 0.01 ? 0.01 : $ret[$i]["fsize"];
            $ret[$i]["fsize"] = number_format($ret[$i]["fsize"], 2, ".", "");
            //}文件大小单位转换为MB
            //{时间转换
            //$ret[$i]["fAddT"] = $this->_timetostr($ret[$i]["fAddT"],"-");
            //}时间转换
        }
        //*/
        return $ret;
    }

    /**
     * 描述：删除指定文件
     * @param $filetoken：文件唯一标识
     * @return json
     */
    public function wbResDel($filetoken){
        $ret = null;
        $ret = file_get_contents($this->rooturl()."ResDel/".$filetoken);
        $ret = json_decode($ret, true);
        return $ret;
    }

    /**
     * 描述：获取资源
     * @param $key：oss文件key标识
     * @return 输出流
     */
    public function wbRes($key){
        try{
            $key = urlencode($key);
            $key = str_replace("%","@",$key);
            $ret = file_get_contents($this->rooturl()."Res/".$key);
        }catch (\Exception $e){
            $ret = null;
        }
        return $ret;
    }

    /**
     * 描述：获取资源地址
     * @param $key：oss文件key标识
     * @return 文件地址
     */
    public function wbResEx($key){
        $key = urlencode($key);
        $key = str_replace("%","@", $key);
        return $this->rooturl()."Res/".$key;
    }

    /**
     * 描述：根据roomtoken和file token关联文件到会议室!
     * @param $roomtoken：房间唯一标识
     * @param $filetoken：文件唯一标识
     * @return json
     */
    public function wbAddFile2RoomSpace($roomtoken, $filetoken){
        $obj = array();
        $obj["rt"] = $roomtoken;
        $obj["ft"]= $filetoken;
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
        //
        $str = $this->encrypt(json_encode($obj), $this->keys());
        $str = urlencode($str);
        $str = str_replace("%","@",$str);
        $ret = file_get_contents($this->rooturl()."AddFile2RoomSpace/".$str);
        $ret = $this->encrypt($ret, $this->keys());
        //echo $ret;
        $ret = json_decode($ret, true);
//        echo $ret->exp;
        return $ret;

    }

    /**
     * 描述：根据roomtoken和file token删除文件到会议室的关联!
     * @param $roomtoken：房间唯一标识
     * @param $filetoken：文件唯一标识
     * @return json
     */
    public function wbDelFile4RoomSpace($roomtoken, $filetoken){
        $obj = array();
        $obj["rt"] = $roomtoken;
        $obj["ft"] = $filetoken;
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
        $str = $this->encrypt(json_encode($obj),$this->keys());
        $str = urlencode($str);
        $str = str_replace("%","@",$str);
        $ret = file_get_contents($this->rooturl()."DelFile4RoomSpace/".$str);
        $ret = $this->encrypt($ret,$this->keys());
        //echo $ret;
        $ret = json_decode($ret,true);
//        echo $ret->exp;
        return $ret;

    }

    /**
     * 描述：根据roomtoken获取与该会议室关联的文档列表!
     * @param $roomtoken：房间唯一标识
     * @return json
     */
    public function wbGetFile4RoomSpace($roomtoken){
        $obj = array();
        $obj["rt"] = $roomtoken;
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
        $str = $this->encrypt(json_encode($obj),$this->keys());
        $str = urlencode($str);
        $str = str_replace("%", "@", $str);
        $ret = file_get_contents($this->rooturl()."GetFile4RoomSpace/".$str);
        //$ret = $this->encrypt($ret,$this->keys());
        //echo $ret;
        $ret = json_decode($ret,true);
//        echo $ret->exp;
        return $ret;

    }

    /**
     * 描述：进入正式房间
     * @param $roomtoken：房间token值
     * @param $user：用户对象
     * @param $roomvvtype：视频/语音发布类型，取值使用枚举类：
     *                      Xiucai\ServiceBundle\CornMeeting\RoomVVType
     * @return 房间地址
     */
    public function wbRooms($roomtoken, $user, $roomvvtype){
        $obj = array();
        $obj["roomtoken"] = $roomtoken;//{房间token值},
        $obj["user"] = $user;//{用户对象},
        $obj["sina_token"] = "";//{/用户授权后的token值},废弃字段
        $obj["roomvvtype"] = $roomvvtype;
        if($roomtoken == "personal")
            $obj["role"] = "moderator";//* @param $role：用户角色”moderator”|”viewer”，//只对进demo会议室有效。
        $currentTime = $this->getSNCurrentTime();
        $obj["sn"] = $this->sn($currentTime);
        $obj["currentTime"] = $currentTime;
        $str = $this->encrypt(json_encode($obj),$this->keys());

        $str = urlencode($str);
        $str=str_replace("%", "@", $str);
        //  $url=$this->rooturl()."GoToRoom/".$str;
        // echo $this->rooturl()."GoToRoom/".$str;
        return ($this->rooturl()."Rooms/$".$str."$");

    }

    /////////////////////////////////////工具方法/////////////////////////////
    /**
     * 描述：判断用户是否上传过文件
     * @param $uid：用户ID
     * @return boolean
     */
    public function isUploadedFile($uid){
        $counts = count($this->wbGetFSListByUid($uid));
        return $counts ? true : false;
    }

    /**
     * 描述：判断指定会议是否上传过文件
     * @param $roomtoken：会议唯一标识
     * @return boolean
     */
    public function isUploadedFileByRoomToken($roomtoken){
        $counts = count($this->wbGetFSListByRoomToken($roomtoken));
        return $counts ? true : false;
    }
    /////////////////////////////////////工具方法/////////////////////////////



    /////////////////////////////////////other/////////////////////////////
    /**
     * 描述：将时间戳转换为 时间 字符串
     * @param $time：时间戳 整数
     * @param $flag：标记
     * @return string
     */
    /*
    //js转换时间戳函数
    <script type="text/javascript">
        function getTimeFormat ( s ){
            var data = new Date(s);
            return data.getFullYear()+"-"+(data.getMonth()+1)+"-"+data.getDate()+" "+data.getHours()+":"+data.getMinutes()+":"+data.getSeconds();
        }
    </script>
    */
    private function _timetostr($time,$flag){
        $ret = "";
        $date_time_array = getdate($time);
        $hours = $date_time_array["hours"];
        $minutes = $date_time_array["minutes"];
        //$seconds = $date_time_array["seconds"];
        $month = $date_time_array["mon"];
        $day = $date_time_array["mday"];
        $year = $date_time_array["year"];
        $ret = $year.$flag.$month.$flag.$day." ".$hours.":".$minutes;
        return $ret;
    }
    private function arrayToObject($e){
        if( gettype($e) != "array" ) return;
        foreach($e as $k => $v){
            if( gettype($v) == "array" || getType($v) == "object" )
                $e[$k] = (object)$this->arrayToObject($v);
        }
        return (object)$e;
    }
    //{加密 & 解密
    private function mb_html_entity_decode($string) {
        if (extension_loaded("mbstring") === true) {
            mb_language("Neutral");
            mb_internal_encoding("UTF-8");
            mb_detect_order(array("UTF-8", "ISO-8859-15", "ISO-8859-1", "ASCII"));

            return mb_convert_encoding($string, "UTF-8", "HTML-ENTITIES");
        }

        return html_entity_decode($string, ENT_COMPAT, "UTF-8");
    }
    private function mb_ord($string) {
        if (extension_loaded("mbstring") === true) {
            mb_language("Neutral");
            mb_internal_encoding("UTF-8");
            mb_detect_order(array("UTF-8", "ISO-8859-15", "ISO-8859-1", "ASCII"));

            $result = unpack("N", mb_convert_encoding($string, "UCS-4BE", "UTF-8"));

            if (is_array($result) === true) {
                return $result[1];
            }
        }

        return ord($string);
    }
    private function mb_chr($string) {
        return $this->mb_html_entity_decode("&#" . intval($string) . ";");
    }
    public  function encrypt($str, $cstrKey) {
        $result = "";
        for($i=0, $j=0; $i < mb_strlen($str,"utf-8"); $i++, $j++) {
            if ($j == strlen($cstrKey)) $j = 0;
            $a = $this->mb_ord(mb_substr($str, $i, 1, "utf-8"));
            $b = $this->mb_ord(mb_substr($cstrKey, $j, 1, "utf-8"));
            $snNum[$i] = $this->mb_chr($a ^ $b ^ (1 / ($i + 1)));
            $result .= $snNum[$i];
        }
        return $result;
    }
    //{加密 & 解密

    /**
     * 描述：获取加密key
     * @return string
     */
    public  function keys() {
        $key = $this->xorEnKey;
        return $key;
    }

    /**
     * 描述：当前时间 精确到分钟
     * @return string
     */
    private function getSNCurrentTime(){
        return date("YmdHi");
    }

    /**
     * 描述：获取 校验字符串
     * @return string
     */
    public function sn() {
        return $this->snEx($this->getSNCurrentTime());
    }

    /**
     * 描述：获取 校验字符串
     * @return string
     */
    private function snEx($currentTime) {
        //
        date_default_timezone_set("Asia/Shanghai");
        $sn=substr(MD5($this->keys().$currentTime), 5, 23);
        return ($sn);
    }

    /**
     * 描述：获取 主动接口 根路由
     * @return string
     */
    public function rooturl(){
        $rooturl = $this->webServiceUrl;
        return $rooturl;
    }
    /////////////////////////////////////other/////////////////////////////
}

?>


