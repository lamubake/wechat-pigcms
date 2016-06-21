<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_adcms_list`;");
E_C("CREATE TABLE `imicms_adcms_list` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `token` varchar(255) character set ucs2 NOT NULL,
  `creattime` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `contents` mediumtext NOT NULL,
  `type` tinyint(6) NOT NULL,
  `click` varchar(255) NOT NULL,
  `adrmb` float NOT NULL,
  `gdadrmb` float NOT NULL,
  `sjname` varchar(255) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>