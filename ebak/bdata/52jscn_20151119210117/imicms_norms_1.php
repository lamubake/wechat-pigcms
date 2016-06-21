<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_norms`;");
E_C("CREATE TABLE `imicms_norms` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` tinyint(1) unsigned NOT NULL,
  `catid` int(10) unsigned NOT NULL,
  `value` varchar(150) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `catid` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>