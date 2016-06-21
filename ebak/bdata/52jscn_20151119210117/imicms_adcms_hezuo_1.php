<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_adcms_hezuo`;");
E_C("CREATE TABLE `imicms_adcms_hezuo` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `token` varchar(255) NOT NULL,
  `tel` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `qq` varchar(255) NOT NULL,
  `address` varchar(255) character set ucs2 NOT NULL,
  `beizhu` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `statue` smallint(6) NOT NULL,
  KEY `id` USING BTREE (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>