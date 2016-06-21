<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_seckill_users`;");
E_C("CREATE TABLE `imicms_seckill_users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_aid` int(11) NOT NULL COMMENT '活动id',
  `user_nickname` varchar(20) default '' COMMENT '普通用户昵称',
  `user_headimgurl` varchar(500) default '' COMMENT '用户用户头像',
  `user_shareid` varchar(100) default '' COMMENT '用户分享key',
  `user_wechaid` varchar(100) default '' COMMENT '用户wcha_id',
  `user_sex` tinyint(4) default '0' COMMENT '用户性别',
  `user_tel` varchar(20) default '' COMMENT '用户电话',
  `user_qq` varchar(20) default '' COMMENT '用户QQ',
  `user_address` varchar(50) default '' COMMENT '用户address',
  `user_province` varchar(50) default '' COMMENT '用户province',
  `user_city` varchar(50) default '' COMMENT '用户city',
  `user_mintime` int(11) default '0' COMMENT '用户分享奖励时间',
  `token` varchar(50) default NULL COMMENT 'token',
  PRIMARY KEY  (`user_id`),
  KEY `user_shareid` USING BTREE (`user_shareid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>