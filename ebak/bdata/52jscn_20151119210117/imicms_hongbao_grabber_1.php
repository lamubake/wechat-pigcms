<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hongbao_grabber`;");
E_C("CREATE TABLE `imicms_hongbao_grabber` (
  `grabber_id` int(11) NOT NULL auto_increment,
  `hongbao_id` int(11) NOT NULL COMMENT '抢到的红包id',
  `money` float(6,2) unsigned NOT NULL COMMENT '抢到的红包金额',
  `grabber_nickname` varchar(20) default '' COMMENT '抢红包者昵称',
  `grabber_headimgurl` varchar(255) default NULL COMMENT '抢红包者头像',
  `grabber_shareid` varchar(100) default '' COMMENT '抢红包者分享的key',
  `grabber_wechaid` varchar(100) default '' COMMENT '抢红包者wcha_id',
  `grabber_sex` enum('0','1') default '0' COMMENT '抢红包者性别',
  `grabber_tel` varchar(20) default '' COMMENT '抢红包者电话',
  `grabber_qq` varchar(20) default '' COMMENT '抢红包者QQ',
  `grabber_address` varchar(50) default '' COMMENT '抢红包者address',
  `grabber_province` varchar(50) default '' COMMENT '抢红包者province',
  `grabber_city` varchar(50) default '' COMMENT '抢红包者city',
  `share_money` int(11) default '0' COMMENT '抢红包者合体奖励的金额',
  `share_content` int(11) default '0' COMMENT '抢红包者分享语',
  `token` varchar(50) default NULL COMMENT 'token',
  `grabber_time` int(11) NOT NULL,
  `isgrabbed` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`grabber_id`),
  KEY `hongbao_id` USING BTREE (`hongbao_id`),
  KEY `my_packets` (`hongbao_id`,`grabber_wechaid`,`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>