<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_storeflash`;");
E_C("CREATE TABLE `imicms_storeflash` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `img` char(255) NOT NULL,
  `url` char(255) NOT NULL,
  `info` varchar(90) NOT NULL,
  `tip` smallint(11) NOT NULL default '1',
  `sort` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `tip` (`tip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>