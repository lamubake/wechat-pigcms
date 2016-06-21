<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_broker_client`;");
E_C("CREATE TABLE `imicms_broker_client` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `bid` int(10) unsigned NOT NULL,
  `tjuid` int(10) unsigned NOT NULL default '0' COMMENT '推挤人id 推挤人idbroker_user表id',
  `verifyuid` int(11) NOT NULL default '0' COMMENT '顾问id 推挤人idbroker_user表id',
  `status` tinyint(1) unsigned NOT NULL default '0' COMMENT '状态',
  `cname` varchar(90) NOT NULL COMMENT '客户名称',
  `ctel` varchar(15) NOT NULL COMMENT '客户手机号',
  `proid` int(11) NOT NULL default '0' COMMENT 'broker_item表id',
  `remark` varchar(255) NOT NULL COMMENT '备注',
  `addtime` int(11) unsigned NOT NULL default '0',
  `uptime` int(11) unsigned NOT NULL default '0' COMMENT '更新时间',
  `wecha_id` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `bid` USING BTREE (`bid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>