<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_adcms_black`;");
E_C("CREATE TABLE `imicms_adcms_black` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `wecha_id` varchar(255) NOT NULL,
  `token` varchar(255) character set ucs2 NOT NULL,
  KEY `id` USING BTREE (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>