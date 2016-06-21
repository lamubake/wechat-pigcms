<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_user`;");
E_C("CREATE TABLE `imicms_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `role` smallint(6) unsigned NOT NULL COMMENT '组ID',
  `status` tinyint(1) unsigned NOT NULL default '0' COMMENT '状态 1:启用 0:禁止',
  `remark` varchar(255) default NULL COMMENT '备注说明',
  `last_login_time` int(11) unsigned NOT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(15) default NULL COMMENT '最后登录IP',
  `last_location` varchar(100) default NULL COMMENT '最后登录位置',
  `free_time` int(11) NOT NULL default '0',
  `scene_times` int(11) NOT NULL default '0',
  `is_admin` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_user` values('1','admin','761b7b6e50f8474743918169d56d124d','5','1','','1447879622','127.0.0.1','','0','0','1');");

require("../../inc/footer.php");
?>