<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_service_my`;");
E_C("CREATE TABLE `imicms_service_my` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `type` varchar(2) NOT NULL,
  `title` varchar(100) default NULL,
  `img` varchar(100) default NULL,
  `display` int(11) NOT NULL default '1',
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>