<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_pc_site`;");
E_C("CREATE TABLE `imicms_pc_site` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `site` char(30) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `site` (`site`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_pc_site` values('24','kaiqpo1447853601','m.52jscn.com');");

require("../../inc/footer.php");
?>