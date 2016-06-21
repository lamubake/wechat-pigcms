<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_images`;");
E_C("CREATE TABLE `imicms_images` (
  `id` int(11) NOT NULL auto_increment,
  `fc` char(250) NOT NULL,
  `about` char(250) NOT NULL,
  `price` char(250) NOT NULL,
  `login` char(250) NOT NULL,
  `help` char(250) NOT NULL,
  `common` char(250) NOT NULL,
  `agentid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>