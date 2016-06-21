<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_lottery_record`;");
E_C("CREATE TABLE `imicms_lottery_record` (
  `id` int(11) NOT NULL auto_increment,
  `lid` int(11) NOT NULL,
  `usenums` tinyint(1) NOT NULL default '0' COMMENT '用户使用次数',
  `wecha_id` varchar(60) NOT NULL COMMENT '微信唯一识别码',
  `token` varchar(30) NOT NULL,
  `islottery` int(1) NOT NULL COMMENT '是否中奖',
  `wecha_name` varchar(60) NOT NULL COMMENT '微信号',
  `phone` varchar(15) NOT NULL,
  `sn` varchar(13) NOT NULL COMMENT '中奖后序列号',
  `time` int(11) NOT NULL,
  `prize` varchar(50) NOT NULL default '' COMMENT '已中奖项',
  `sendstutas` int(11) NOT NULL default '0',
  `sendtime` int(11) NOT NULL,
  `type` varchar(60) NOT NULL,
  PRIMARY KEY  (`id`,`lid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_lottery_record` values('1','1','1','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','99630ff411650cfa','1','eakecn','13700674293','5424dde15fd84','1411702241','1','0','0','');");

require("../../inc/footer.php");
?>