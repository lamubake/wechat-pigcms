<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_research_question`;");
E_C("CREATE TABLE `imicms_research_question` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `rid` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>