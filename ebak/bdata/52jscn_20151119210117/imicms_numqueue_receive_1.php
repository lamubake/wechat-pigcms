<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_numqueue_receive`;");
E_C("CREATE TABLE `imicms_numqueue_receive` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `store_id` int(11) NOT NULL,
  `queue_type` char(5) NOT NULL default 'A' COMMENT '等待类型',
  `queue_number` char(30) NOT NULL default '' COMMENT '排队号码',
  `numbers` int(11) NOT NULL default '0',
  `phone` char(30) NOT NULL default '0' COMMENT '手机号',
  `status` tinyint(1) NOT NULL COMMENT '号码状态',
  `wecha_id` char(50) NOT NULL,
  `token` char(25) NOT NULL,
  `add_time` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED");

require("../../inc/footer.php");
?>