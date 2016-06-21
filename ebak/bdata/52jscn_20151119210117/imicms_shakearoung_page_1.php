<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_shakearoung_page`;");
E_C("CREATE TABLE `imicms_shakearoung_page` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `page_id` int(11) NOT NULL COMMENT '页面ID',
  `title` char(10) NOT NULL default '' COMMENT '页面标题',
  `description` char(10) NOT NULL default '' COMMENT '页面副标题',
  `icon_url` varchar(255) NOT NULL default '' COMMENT '页面图标URL',
  `page_url` varchar(255) NOT NULL default '' COMMENT '跳转地址',
  `page_comment` char(15) NOT NULL default '' COMMENT '页面的备注信息',
  `token` char(50) NOT NULL default '',
  `add_time` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>