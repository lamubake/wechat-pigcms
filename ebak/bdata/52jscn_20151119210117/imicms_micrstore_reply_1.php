<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_micrstore_reply`;");
E_C("CREATE TABLE `imicms_micrstore_reply` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `keyword` char(20) NOT NULL,
  `description` varchar(300) NOT NULL,
  `title` varchar(300) NOT NULL,
  `img` varchar(300) NOT NULL,
  `token` varchar(100) NOT NULL,
  `sid` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>