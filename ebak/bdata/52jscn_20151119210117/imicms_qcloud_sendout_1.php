<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_qcloud_sendout`;");
E_C("CREATE TABLE `imicms_qcloud_sendout` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `suborderid` varchar(1000) default NULL,
  `orderid` varchar(1000) default NULL,
  `packageid` varchar(1000) default NULL,
  `payprice` varchar(100) default NULL,
  `openid` varchar(1000) default NULL,
  `paynum` varchar(100) default NULL,
  `freedays` varchar(100) default NULL,
  `servicedays` varchar(100) default NULL,
  `payunit` char(100) default NULL,
  `service` char(50) default 'site',
  `serviceId` varchar(1000) default NULL,
  `price` varchar(100) default NULL,
  `providerId` char(100) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>