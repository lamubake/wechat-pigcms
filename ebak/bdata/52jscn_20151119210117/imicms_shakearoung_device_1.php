<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_shakearoung_device`;");
E_C("CREATE TABLE `imicms_shakearoung_device` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `device_id` int(11) NOT NULL COMMENT '设备ID',
  `uuid` char(50) NOT NULL COMMENT '设备UUID',
  `major` int(11) NOT NULL COMMENT '主设备ID',
  `minor` int(11) NOT NULL COMMENT '次设备ID',
  `apply_id` int(11) NOT NULL default '0' COMMENT '批次ID',
  `device_comment` char(30) NOT NULL default '' COMMENT '设备名称',
  `page_num` int(11) NOT NULL COMMENT '关联的页面数',
  `page_ids` varchar(255) NOT NULL default '' COMMENT '关联的页面ID列表',
  `status` tinyint(1) NOT NULL COMMENT '设备状态',
  `token` char(50) NOT NULL,
  `add_reason` varchar(300) NOT NULL,
  `add_time` int(11) NOT NULL default '0' COMMENT '添加时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>