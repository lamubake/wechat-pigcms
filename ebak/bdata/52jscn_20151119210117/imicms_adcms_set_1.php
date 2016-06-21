<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_adcms_set`;");
E_C("CREATE TABLE `imicms_adcms_set` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `wxname` varchar(255) NOT NULL,
  `token` varchar(255) character set ucs2 NOT NULL,
  `wxurl` varchar(255) NOT NULL,
  `sjtg` varchar(255) NOT NULL,
  `xszd` varchar(255) NOT NULL,
  `rmb` decimal(9,2) NOT NULL,
  `yj` varchar(255) NOT NULL,
  `mbid` varchar(255) NOT NULL,
  `xiaxianid` varchar(255) NOT NULL,
  `txtype` int(6) NOT NULL,
  `quyu` varchar(255) NOT NULL,
  `quyus` varchar(255) NOT NULL,
  `quyux` varchar(255) NOT NULL,
  KEY `id` USING BTREE (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>