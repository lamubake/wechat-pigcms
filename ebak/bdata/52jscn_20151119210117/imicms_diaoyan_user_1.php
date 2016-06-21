<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_diaoyan_user`;");
E_C("CREATE TABLE `imicms_diaoyan_user` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `diaoyan_id` int(11) default NULL,
  `wecha_id` varchar(100) default NULL,
  `qid` int(11) default NULL,
  `ans` varchar(20) default NULL,
  `jianyi` varchar(500) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>