<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hongbao`;");
E_C("CREATE TABLE `imicms_hongbao` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` char(25) NOT NULL,
  `keyword` char(30) NOT NULL,
  `action_name` char(40) NOT NULL COMMENT '红包活动名称',
  `sharetimes` int(10) NOT NULL COMMENT '最小合体次数',
  `min_money` float(6,2) NOT NULL COMMENT '随机数最小值',
  `max_money` float(6,2) NOT NULL COMMENT '随机数最大值',
  `total_money` float(6,2) default NULL,
  `start_time` char(11) NOT NULL COMMENT '活动开始时间',
  `end_time` char(11) NOT NULL COMMENT '活动结束时间',
  `need_phone` enum('1','2') NOT NULL COMMENT '是否需要注册手机号',
  `need_follow` enum('1','2') NOT NULL COMMENT '是否需要关注',
  `action_desc` varchar(256) NOT NULL COMMENT '活动介绍',
  `reply_pic` varchar(100) NOT NULL,
  `status` enum('1','2') NOT NULL COMMENT '是否开启',
  `reply_title` varchar(20) NOT NULL,
  `reply_content` varchar(200) NOT NULL,
  `remind_word` varchar(255) NOT NULL,
  `remind_link` varchar(255) NOT NULL,
  `getway` tinyint(1) NOT NULL default '1',
  `timeline_hide` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `hongbao_index` (`id`,`token`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>