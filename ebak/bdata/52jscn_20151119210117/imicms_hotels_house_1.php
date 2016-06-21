<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hotels_house`;");
E_C("CREATE TABLE `imicms_hotels_house` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(80) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(200) NOT NULL,
  `sid` int(10) unsigned NOT NULL,
  `note` varchar(500) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `sid` (`sid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_hotels_house` values('1','1','99630ff411650cfa','房间测试','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141112/99630ff411650cfa2014111215542329657.jpg','1','本酒店做为客户演示使用，不做为入驻使用时使用。。。');");

require("../../inc/footer.php");
?>