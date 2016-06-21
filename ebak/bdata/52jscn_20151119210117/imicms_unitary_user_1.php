<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_unitary_user`;");
E_C("CREATE TABLE `imicms_unitary_user` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) default NULL COMMENT '昵称',
  `phone` varchar(100) default NULL COMMENT '手机号',
  `address` text COMMENT '地址',
  `token` varchar(100) default NULL,
  `wecha_id` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>