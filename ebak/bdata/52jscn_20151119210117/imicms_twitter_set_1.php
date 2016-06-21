<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_twitter_set`;");
E_C("CREATE TABLE `imicms_twitter_set` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `token` varchar(60) NOT NULL,
  `clickprice` float NOT NULL COMMENT '点击的价格',
  `clickmax` int(10) unsigned NOT NULL COMMENT '每天点击的上限',
  `registerprice` float NOT NULL COMMENT '注册的价格',
  `registermax` int(10) unsigned NOT NULL COMMENT '每天注册的上限',
  `percent` float NOT NULL COMMENT '商品总价的百分比',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>