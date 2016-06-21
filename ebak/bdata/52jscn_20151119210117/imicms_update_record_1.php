<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_update_record`;");
E_C("CREATE TABLE `imicms_update_record` (
  `id` int(11) NOT NULL auto_increment,
  `msg` varchar(600) NOT NULL default '',
  `type` varchar(20) NOT NULL default '',
  `time` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>