<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_livingcircle`;");
E_C("CREATE TABLE `imicms_livingcircle` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `keyword` varchar(100) NOT NULL,
  `wxtitle` varchar(100) NOT NULL,
  `wxpic` varchar(100) NOT NULL,
  `wxinfo` text,
  `fistpic` varchar(100) NOT NULL,
  `secondpic` varchar(100) default NULL,
  `thirdpic` varchar(100) default NULL,
  `fourpic` varchar(100) default NULL,
  `fivepic` varchar(100) default NULL,
  `sixpic` varchar(100) default NULL,
  `navpic` varchar(100) NOT NULL,
  `mysellerpic` varchar(100) NOT NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>