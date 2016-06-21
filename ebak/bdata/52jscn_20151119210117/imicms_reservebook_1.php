<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_reservebook`;");
E_C("CREATE TABLE `imicms_reservebook` (
  `id` int(11) NOT NULL auto_increment,
  `rid` int(11) NOT NULL,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  `truename` varchar(50) NOT NULL,
  `tel` varchar(13) NOT NULL,
  `housetype` varchar(50) NOT NULL,
  `dateline` varchar(50) NOT NULL,
  `timepart` varchar(50) NOT NULL,
  `info` varchar(100) NOT NULL,
  `type` char(15) NOT NULL,
  `booktime` int(11) NOT NULL,
  `remate` tinyint(3) NOT NULL default '0',
  `kfinfo` varchar(100) NOT NULL,
  `carnum` varchar(20) NOT NULL,
  `km` int(11) NOT NULL default '0',
  `sex` int(11) default '0',
  `age` int(11) NOT NULL default '0',
  `address` varchar(50) NOT NULL default '',
  `choose` varchar(50) NOT NULL default '',
  `number` int(11) NOT NULL default '0',
  `paid` int(1) default '0',
  `orderid` char(32) NOT NULL default '',
  `payprice` decimal(10,2) default NULL,
  `shiporder` char(32) NOT NULL default '',
  `productName` varchar(50) NOT NULL default '',
  `orderName` varchar(50) default '',
  `paytype` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `wecha_id` (`wecha_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_reservebook` values('16','1','257e21c8a9e1be8c','oupbZjsYocTtdvDjKYFJq3KYq1lU','是的发顺丰','13012345678','','','',' 撒打发士大夫','housekeeper','1413431539','0','','','0','1','0','撒旦飞洒副食店 ','','0','0','141016115219465800','280.00','','家庭，公司保洁，工程开荒，花园设计施工','','');");
E_D("replace into `imicms_reservebook` values('22','1','257e21c8a9e1be8c','oupbZjof1M0-rpqKcJcylfXzKGG8','夜明','13208845009','','','','夜明','housekeeper','1415607058','0','','','0','1','0','昆钢商场','','0','0','141110161058591535','280.00','','家庭，公司保洁，工程开荒，花园设计施工','','');");

require("../../inc/footer.php");
?>