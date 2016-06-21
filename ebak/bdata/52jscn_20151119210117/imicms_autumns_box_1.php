<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_autumns_box`;");
E_C("CREATE TABLE `imicms_autumns_box` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `bid` int(11) NOT NULL COMMENT '活动ID',
  `invite` int(11) NOT NULL,
  `boxdate` int(11) NOT NULL COMMENT '领取盒子的时间',
  `box` int(11) NOT NULL COMMENT '盒子样式',
  `wecha_id` varchar(60) NOT NULL,
  `prize` varchar(100) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `isprize` int(11) NOT NULL COMMENT '是否中奖',
  `isprizes` int(11) NOT NULL COMMENT '是否领奖',
  `prizedate` varchar(20) NOT NULL COMMENT '兑奖起始时间',
  `prizedates` varchar(20) NOT NULL COMMENT '兑奖结束时间',
  `lvprize` varchar(30) NOT NULL,
  `sn` varchar(13) NOT NULL,
  `sntime` int(11) NOT NULL COMMENT '奖品发放时间',
  `sendstutas` int(11) NOT NULL default '0' COMMENT '是否发奖',
  `prtime` int(11) NOT NULL COMMENT '领奖时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>