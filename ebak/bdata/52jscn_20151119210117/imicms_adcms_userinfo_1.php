<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_adcms_userinfo`;");
E_C("CREATE TABLE `imicms_adcms_userinfo` (
  `id` smallint(6) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `headpic` varchar(255) character set ucs2 NOT NULL,
  `wecha_id` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `click` int(11) NOT NULL,
  `time` varchar(255) default NULL,
  `balance` float NOT NULL default '0',
  `total_balance` float NOT NULL,
  `invite1` varchar(255) NOT NULL,
  `jibie` int(6) NOT NULL,
  `erweima` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  KEY `id` USING BTREE (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>