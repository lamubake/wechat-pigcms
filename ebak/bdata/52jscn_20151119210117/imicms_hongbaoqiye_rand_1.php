<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hongbaoqiye_rand`;");
E_C("CREATE TABLE `imicms_hongbaoqiye_rand` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(255) NOT NULL,
  `r1` varchar(11) NOT NULL default '',
  `r2` int(11) NOT NULL,
  `r3` int(11) NOT NULL,
  `r4` int(11) NOT NULL,
  `r5` int(11) NOT NULL,
  `rand1` float(4,2) NOT NULL,
  `rand2` float(4,2) NOT NULL,
  `rand3` float(4,2) NOT NULL,
  `rand4` float(4,2) NOT NULL,
  `rand5` float(4,2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>