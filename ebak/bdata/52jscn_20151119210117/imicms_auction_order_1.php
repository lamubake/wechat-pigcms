<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_auction_order`;");
E_C("CREATE TABLE `imicms_auction_order` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(100) NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `orderid` varchar(30) NOT NULL default '0',
  `paid` int(11) NOT NULL default '0',
  `transactionid` varchar(150) default NULL,
  `paytype` varchar(30) default NULL,
  `price` varchar(100) NOT NULL,
  `third_id` varchar(100) default NULL,
  `auctionid` int(11) NOT NULL,
  `topriceid` int(11) NOT NULL,
  `addtime` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `address` varchar(300) NOT NULL,
  `state` int(11) NOT NULL default '0',
  `expressnum` varchar(100) default NULL,
  `expressname` varchar(100) default NULL,
  `thirdpay` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `orderid` (`orderid`),
  KEY `auctionid` (`auctionid`),
  KEY `topriceid` (`topriceid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>