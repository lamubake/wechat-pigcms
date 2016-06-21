<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_product_cart`;");
E_C("CREATE TABLE `imicms_product_cart` (
  `id` int(10) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL default '',
  `wecha_id` varchar(60) NOT NULL default '',
  `info` varchar(300) NOT NULL default '',
  `total` mediumint(4) NOT NULL default '0',
  `price` float NOT NULL default '0',
  `truename` varchar(20) NOT NULL default '',
  `tel` varchar(14) NOT NULL default '',
  `address` varchar(100) NOT NULL default '',
  `ordertype` mediumint(2) NOT NULL default '0',
  `tableid` mediumint(4) NOT NULL default '0',
  `time` int(10) NOT NULL default '0',
  `buytime` varchar(100) NOT NULL default '',
  `groupon` tinyint(1) NOT NULL default '0',
  `dining` tinyint(1) NOT NULL default '0',
  `printed` tinyint(1) NOT NULL default '0',
  `handled` tinyint(1) NOT NULL default '0',
  `is_reward` int(1) NOT NULL default '0' COMMENT '是否返利',
  `transactionid` varchar(100) NOT NULL default '',
  `paytype` varchar(30) NOT NULL default '',
  `productid` int(10) NOT NULL default '0',
  `code` varchar(50) NOT NULL default '',
  `diningtype` mediumint(2) NOT NULL default '0',
  `year` mediumint(4) NOT NULL default '0',
  `month` mediumint(4) NOT NULL default '0',
  `day` mediumint(4) NOT NULL default '0',
  `hour` smallint(4) NOT NULL default '0',
  `paid` tinyint(1) NOT NULL default '0',
  `orderid` varchar(40) NOT NULL default '',
  `sent` tinyint(1) NOT NULL default '0',
  `logistics` varchar(70) NOT NULL default '',
  `logisticsid` varchar(50) NOT NULL default '',
  `handledtime` int(10) NOT NULL default '0',
  `handleduid` int(10) NOT NULL default '0',
  `score` int(10) unsigned NOT NULL,
  `paymode` tinyint(1) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `comment` varchar(300) NOT NULL default '' COMMENT '买家留言',
  `uid` int(10) NOT NULL default '0' COMMENT '买家id',
  `twid` varchar(20) NOT NULL COMMENT '来源推广人的推广ID',
  `totalprice` float NOT NULL COMMENT '购买商品的订单总价',
  `sn` tinyint(1) NOT NULL default '0',
  `sn_content` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`,`time`),
  KEY `groupon` (`groupon`),
  KEY `dining` (`dining`),
  KEY `printed` (`printed`),
  KEY `year` (`year`,`month`,`day`,`hour`),
  KEY `diningtype` (`diningtype`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_product_cart` values('1','99630ff411650cfa','oQqMOt_BsAHx6kGaCUhj3_xd5904','a:1:{i:1;a:2:{s:5:\"count\";i:1;s:5:\"price\";i:1;}}','1','1','夜明','13208845009','昆钢商场','0','0','1415676885','','1','0','0','0','0','','','1','oQqMOt1415676885','0','0','0','0','0','0','zaqz1415676885','0','','','0','0','0','0','1','','0','','0','0','');");

require("../../inc/footer.php");
?>