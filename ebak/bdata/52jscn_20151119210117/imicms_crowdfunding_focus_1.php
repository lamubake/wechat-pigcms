<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_crowdfunding_focus`;");
E_C("CREATE TABLE `imicms_crowdfunding_focus` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(11) NOT NULL,
  `wecha_id` char(40) NOT NULL,
  `token` char(40) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `id` (`cid`),
  KEY `wecha_id` (`wecha_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>