<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_secakecouexp`;");
E_C("CREATE TABLE `imicms_secakecouexp` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `tel` varchar(50) default NULL,
  `descript` varchar(255) default NULL,
  `zhan_gui` varchar(255) default NULL,
  `zhan_gui_num` varchar(255) default NULL,
  `img_1` varchar(255) default NULL,
  `img_2` varchar(255) default NULL,
  `img_3` varchar(255) default NULL,
  `content` text,
  `add_time` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>