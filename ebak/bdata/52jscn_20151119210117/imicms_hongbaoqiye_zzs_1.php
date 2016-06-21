<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hongbaoqiye_zzs`;");
E_C("CREATE TABLE `imicms_hongbaoqiye_zzs` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `info` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `token` varchar(255) character set ucs2 NOT NULL,
  `url` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>