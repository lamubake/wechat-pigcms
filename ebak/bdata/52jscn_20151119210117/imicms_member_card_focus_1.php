<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_focus`;");
E_C("CREATE TABLE `imicms_member_card_focus` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `info` varchar(300) default NULL,
  `img` varchar(200) default NULL,
  `url` varchar(200) default NULL,
  `token` char(40) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>