<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_moopha_keywords`;");
E_C("CREATE TABLE `imicms_moopha_keywords` (
  `id` mediumint(4) NOT NULL auto_increment,
  `keyword` varchar(60) NOT NULL default '',
  `link` varchar(150) NOT NULL default '',
  `title` varchar(150) NOT NULL default '',
  `target` varchar(15) NOT NULL default '_blank',
  `time` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>