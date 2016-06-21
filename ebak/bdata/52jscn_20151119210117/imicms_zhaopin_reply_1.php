<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_zhaopin_reply`;");
E_C("CREATE TABLE `imicms_zhaopin_reply` (
  `id` int(12) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `info` varchar(500) default NULL COMMENT '????',
  `title` text NOT NULL,
  `tp` char(255) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `allowjl` tinyint(1) NOT NULL COMMENT '审核简历',
  `allowqy` tinyint(1) NOT NULL COMMENT '审核招聘',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>