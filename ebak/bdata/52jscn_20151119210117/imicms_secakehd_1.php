<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_secakehd`;");
E_C("CREATE TABLE `imicms_secakehd` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `app` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `user_id` varchar(50) NOT NULL default '0',
  `tel` varchar(50) NOT NULL,
  `jp_name` varchar(100) NOT NULL default '',
  `start_time` int(15) unsigned NOT NULL default '0',
  `status` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>