<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_secakepur`;");
E_C("CREATE TABLE `imicms_secakepur` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `tel` varchar(255) default NULL,
  `qq` varchar(255) default NULL,
  `danwei` varchar(255) default NULL,
  `type` varchar(255) default NULL,
  `content` text,
  `add_time` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>