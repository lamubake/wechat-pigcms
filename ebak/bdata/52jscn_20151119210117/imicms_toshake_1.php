<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_toshake`;");
E_C("CREATE TABLE `imicms_toshake` (
  `id` int(8) NOT NULL auto_increment,
  `phone` varchar(15) NOT NULL,
  `token` varchar(20) NOT NULL,
  `wecha_id` varchar(30) default NULL,
  `point` int(10) default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>