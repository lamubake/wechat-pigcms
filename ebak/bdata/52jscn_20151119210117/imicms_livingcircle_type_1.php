<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_livingcircle_type`;");
E_C("CREATE TABLE `imicms_livingcircle_type` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) default NULL,
  `name` varchar(100) default NULL,
  `pic` varchar(100) default NULL,
  `num` int(11) NOT NULL default '1',
  `typeid` int(11) NOT NULL default '0' COMMENT 'id',
  `display` int(11) NOT NULL default '1' COMMENT 'Ƿʾ',
  `fistpic` varchar(100) NOT NULL,
  `secondpic` varchar(100) default NULL,
  `thirdpic` varchar(100) default NULL,
  `fourpic` varchar(100) default NULL,
  `fivepic` varchar(100) default NULL,
  `sixpic` varchar(100) default NULL,
  `addtime` int(11) NOT NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>