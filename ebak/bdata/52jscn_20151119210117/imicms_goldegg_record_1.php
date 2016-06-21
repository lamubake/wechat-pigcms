<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_goldegg_record`;");
E_C("CREATE TABLE `imicms_goldegg_record` (
  `id` int(11) NOT NULL auto_increment,
  `lid` int(11) NOT NULL COMMENT '关联ID',
  `usenums` tinyint(1) NOT NULL default '0' COMMENT '用户使用次数',
  `wecha_id` varchar(60) NOT NULL COMMENT '微信唯一识别码',
  `token` varchar(60) NOT NULL COMMENT '微信TOKEN',
  `islucky` int(1) NOT NULL COMMENT '是否中奖',
  `wecha_name` varchar(60) NOT NULL COMMENT '微信号',
  `phone` varchar(20) NOT NULL COMMENT '手机号',
  `sn` varchar(60) NOT NULL COMMENT '中奖后序列号',
  `time` int(11) NOT NULL COMMENT '时间',
  `prize` varchar(60) NOT NULL default '' COMMENT '已中奖项',
  `sendstutas` int(11) NOT NULL default '0' COMMENT '领取状态',
  `sendtime` int(11) NOT NULL COMMENT '领取时间',
  PRIMARY KEY  (`id`,`lid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>