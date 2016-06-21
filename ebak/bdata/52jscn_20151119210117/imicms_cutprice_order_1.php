<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cutprice_order`;");
E_C("CREATE TABLE `imicms_cutprice_order` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `cid` int(11) NOT NULL,
  `orderid` varchar(200) NOT NULL,
  `num` int(11) NOT NULL,
  `nowprice` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `address` varchar(500) NOT NULL,
  `endtime` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `wecha_id` varchar(200) NOT NULL,
  `transactionid` varchar(200) default NULL,
  `paytype` varchar(100) default NULL,
  `third_id` varchar(100) default NULL,
  `paid` int(11) NOT NULL default '0',
  `paystate` int(11) NOT NULL default '0',
  `fahuo` int(11) NOT NULL default '0',
  `fahuoid` varchar(100) default NULL,
  `fahuoname` varchar(100) default NULL,
  PRIMARY KEY  (`imicms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>