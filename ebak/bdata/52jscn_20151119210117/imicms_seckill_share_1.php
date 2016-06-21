<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_seckill_share`;");
E_C("CREATE TABLE `imicms_seckill_share` (
  `share_id` int(11) NOT NULL auto_increment,
  `user_aid` int(11) NOT NULL COMMENT '活动id',
  `user_share` varchar(50) NOT NULL COMMENT '分享key',
  `share_nickname` varchar(50) default '' COMMENT '昵称',
  `share_time` int(11) default '0' COMMENT '减少时间',
  `share_pic` varchar(255) default '' COMMENT '用户图像',
  `is_opened` tinyint(4) default '0' COMMENT '0 表示用户未接受 1 表示已接受',
  `open_time` int(11) default '0' COMMENT '分享时间',
  `share_wechaid` varchar(50) default '' COMMENT '分享链接wecha_id',
  PRIMARY KEY  (`share_id`),
  KEY `user_share` USING BTREE (`user_share`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>