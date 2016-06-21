<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_renew`;");
E_C("CREATE TABLE `imicms_renew` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL,
  `url` char(255) NOT NULL,
  `img_x` char(200) NOT NULL,
  `img_w` char(200) NOT NULL,
  `time` varchar(13) NOT NULL,
  `status` int(1) NOT NULL,
  `agentid` int(10) NOT NULL default '0',
  `imgs` char(250) NOT NULL,
  `color` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>