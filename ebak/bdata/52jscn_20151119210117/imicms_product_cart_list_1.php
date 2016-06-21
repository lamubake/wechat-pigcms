<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_product_cart_list`;");
E_C("CREATE TABLE `imicms_product_cart_list` (
  `id` int(10) NOT NULL auto_increment,
  `cartid` int(10) NOT NULL default '0',
  `productid` int(10) NOT NULL default '0',
  `price` float NOT NULL default '0',
  `total` mediumint(4) NOT NULL default '0',
  `wecha_id` varchar(60) NOT NULL default '',
  `token` varchar(50) NOT NULL default '',
  `time` int(10) NOT NULL default '0',
  `cid` int(10) unsigned NOT NULL,
  `shipping` float NOT NULL default '0' COMMENT '运费',
  `sku_id` int(10) NOT NULL default '0' COMMENT '库存id',
  `comment` varchar(300) NOT NULL default '0' COMMENT '买家留言',
  PRIMARY KEY  (`id`),
  KEY `cartid` (`cartid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>