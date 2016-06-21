<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wall`;");
E_C("CREATE TABLE `imicms_wall` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(20) NOT NULL default '',
  `time` int(11) NOT NULL default '0',
  `logo` varchar(100) default '',
  `title` varchar(100) NOT NULL default '',
  `startbackground` varchar(100) default '',
  `background` varchar(100) default '',
  `endbackground` varchar(100) default '',
  `isopen` tinyint(1) default '1',
  `firstprizename` varchar(50) default '',
  `firstprizepic` varchar(100) default '',
  `firstprizecount` mediumint(5) default '0',
  `secondprizename` varchar(50) default '',
  `secondprizecount` mediumint(4) default '0',
  `secondprizepic` varchar(150) default '',
  `thirdprizename` varchar(50) default '',
  `thirdprizepic` varchar(100) default '',
  `thirdprizecount` mediumint(4) default '0',
  `fourthprizename` varchar(50) default '',
  `fourthprizecount` mediumint(4) default '0',
  `fourthprizepic` varchar(100) default '',
  `fifthprizename` varchar(50) default '',
  `fifthprizecount` mediumint(5) default '0',
  `fifthprizepic` varchar(100) default '',
  `sixthprizename` varchar(50) default '',
  `sixthprizecount` mediumint(4) default '0',
  `sixthprizepic` varchar(100) default '',
  `keyword` varchar(60) default '',
  `qrcode` varchar(100) default '',
  `ck_msg` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>