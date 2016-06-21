<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_yuyue_setcin`;");
E_C("CREATE TABLE `imicms_yuyue_setcin` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) default NULL,
  `name` varchar(20) default NULL,
  `yuanjia` varchar(10) default NULL,
  `youhui` varchar(10) default NULL,
  `memo` varchar(100) default NULL,
  `messages` text,
  `type` varchar(20) default NULL,
  `pic1` varchar(100) default NULL,
  `pic2` varchar(100) default NULL,
  `pic3` varchar(100) default NULL,
  `pic4` varchar(100) default NULL,
  `pic5` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>