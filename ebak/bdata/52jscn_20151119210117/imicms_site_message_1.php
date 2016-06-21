<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_site_message`;");
E_C("CREATE TABLE `imicms_site_message` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `from` varchar(60) NOT NULL,
  `relation` tinyint(3) unsigned default '0',
  `content` varchar(255) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '0',
  `read_time` int(10) unsigned NOT NULL default '0',
  `create_time` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站内信'");

require("../../inc/footer.php");
?>