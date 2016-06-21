<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_livingcircle_seller`;");
E_C("CREATE TABLE `imicms_livingcircle_seller` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `cid` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `num` int(11) NOT NULL default '1',
  `typeid` int(11) NOT NULL,
  `zitypeid` int(11) NOT NULL default '0',
  `sellcircleid` int(11) NOT NULL,
  `zisellcircleid` int(11) NOT NULL default '0',
  `fistpic` varchar(100) NOT NULL,
  `secondpic` varchar(100) default NULL,
  `thirdpic` varchar(100) default NULL,
  `fourpic` varchar(100) default NULL,
  `fivepic` varchar(100) default NULL,
  `sixpic` varchar(100) default NULL,
  `qrcode` varchar(100) default NULL,
  `weurl` varchar(512) default NULL,
  `recommend` int(11) NOT NULL default '0',
  `pv` int(11) NOT NULL default '0',
  `addtime` int(11) NOT NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>