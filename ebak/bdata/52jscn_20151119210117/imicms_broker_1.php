<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_broker`;");
E_C("CREATE TABLE `imicms_broker` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(100) NOT NULL,
  `keyword` varchar(20) NOT NULL,
  `token` varchar(50) NOT NULL,
  `picurl` varchar(250) NOT NULL,
  `imgreply` varchar(250) NOT NULL COMMENT '消息回复图片',
  `invitecode` char(16) NOT NULL,
  `statdate` int(11) NOT NULL,
  `enddate` int(11) NOT NULL,
  `ruledesc` text NOT NULL,
  `registration` text NOT NULL,
  `isdel` tinyint(1) unsigned NOT NULL default '0' COMMENT '1表示已删除',
  `addtime` int(11) NOT NULL,
  `uptime` int(11) NOT NULL COMMENT '更新时间',
  `bgimg` varchar(250) NOT NULL COMMENT '背景图片',
  `rinfo` varchar(500) NOT NULL COMMENT '消息回复简介',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>