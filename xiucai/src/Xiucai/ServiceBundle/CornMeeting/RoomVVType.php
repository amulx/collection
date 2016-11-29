<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 14-9-30
 * Time: 下午4:54
 * To change this template use File | Settings | File Templates.
 */

namespace Xiucai\ServiceBundle\CornMeeting;


/** 描述：视频/语音发布类型*/
class RoomVVType {
        /** 描述：双线，语音使用RTMP，视频使用P2P*/
        public static $RTMP_P2P = "RTMP_P2P";
		/** 描述：双线，语音使用P2P，视频使用RTMP*/
		public static $P2P_RTMP = "P2P_RTMP";
		/** 描述：单线，视频/语音使用相同的RTMP（连接）*/
		public static $ONLY_RTMP = "ONLY_RTMP";
		/** 描述：双线，视频/语音使用不同的RTMP（连接）*/
//		public static $DOUBLE_RTMP = "DOUBLE_RTMP";
		/** 描述：单线，视频/语音使用相同的P2P（连接）*/
		public static $ONLY_P2P = "ONLY_P2P";
		/** 描述：双线，视频/语音使用不同的P2P（连接）*/
//		public static $DOUBLE_P2P = "DOUBLE_P2P";

}
