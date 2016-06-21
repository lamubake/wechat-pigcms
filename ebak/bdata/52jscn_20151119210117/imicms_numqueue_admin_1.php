<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_numqueue_admin`;");
E_C("CREATE TABLE `imicms_numqueue_admin` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `password` varchar(100) NOT NULL default '' COMMENT '登陆密码',
  `wecha_id` char(50) NOT NULL default '',
  `store_id` int(11) NOT NULL,
  `role` varchar(100) NOT NULL default '' COMMENT '所属权限',
  `token` char(25) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>