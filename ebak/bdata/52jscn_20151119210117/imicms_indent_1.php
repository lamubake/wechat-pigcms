<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_indent`;");
E_C("CREATE TABLE `imicms_indent` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `gid` tinyint(2) NOT NULL,
  `uname` varchar(60) NOT NULL,
  `title` varchar(60) NOT NULL,
  `info` int(11) NOT NULL,
  `indent_id` char(20) NOT NULL,
  `widtrade_no` int(20) NOT NULL,
  `price` float NOT NULL,
  `create_time` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `month` mediumint(5) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_indent` values('1','1','0','eake','充值','0','1_1412773358','0','10000','1412773358','0','0');");
E_D("replace into `imicms_indent` values('2','1','0','eake','充值','0','1_1416558094','0','10000','1416558094','0','0');");
E_D("replace into `imicms_indent` values('3','17','0','2224245092','充值','0','17_1441185393','0','100000','1441185393','0','0');");
E_D("replace into `imicms_indent` values('4','17','0','2224245092','充值','0','17_1441185402','0','100000','1441185402','0','0');");

require("../../inc/footer.php");
?>