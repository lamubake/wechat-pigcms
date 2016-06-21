<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_diaoyan`;");
E_C("CREATE TABLE `imicms_diaoyan` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `title` varchar(200) default NULL,
  `stime` date default NULL,
  `etime` date default NULL,
  `stat` tinyint(2) default '0',
  `pic` varchar(200) default NULL,
  `sinfo` varchar(500) default NULL,
  `einfo` varchar(500) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>