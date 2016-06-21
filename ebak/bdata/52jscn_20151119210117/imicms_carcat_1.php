<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_carcat`;");
E_C("CREATE TABLE `imicms_carcat` (
  `id` int(11) NOT NULL auto_increment,
  `token` char(25) NOT NULL,
  `name` char(50) NOT NULL,
  `img` char(150) NOT NULL,
  `icon` char(150) NOT NULL,
  `sort` int(11) NOT NULL,
  `is_show` int(11) NOT NULL,
  `url` varchar(300) NOT NULL,
  `system` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>