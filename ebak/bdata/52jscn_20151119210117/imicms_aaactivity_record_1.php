<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_aaactivity_record`;");
E_C("CREATE TABLE `imicms_aaactivity_record` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL COMMENT '参与活动ID',
  `wecha_id` varchar(100) NOT NULL default '' COMMENT '报名者ID',
  `token` varchar(30) NOT NULL default '',
  `score` char(100) NOT NULL default '' COMMENT '扣除积分',
  `feiyong` char(100) NOT NULL default '',
  `share_key` varchar(100) NOT NULL default '' COMMENT '分享KEY',
  `add_time` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>