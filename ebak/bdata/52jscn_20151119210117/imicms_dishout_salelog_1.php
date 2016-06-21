<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('latin1');
E_D("DROP TABLE IF EXISTS `imicms_dishout_salelog`;");
E_C("CREATE TABLE `imicms_dishout_salelog` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(250) character set utf8 NOT NULL,
  `cid` int(10) unsigned NOT NULL COMMENT '??id',
  `did` int(10) unsigned NOT NULL COMMENT 'dish?id',
  `order_id` int(10) unsigned NOT NULL COMMENT 'dish_order?id',
  `money` int(10) unsigned NOT NULL COMMENT '????????',
  `nums` int(10) unsigned NOT NULL COMMENT '?????',
  `unitprice` int(10) unsigned NOT NULL COMMENT '???????',
  `dname` varchar(200) character set utf8 NOT NULL COMMENT '?????',
  `paytype` varchar(20) character set utf8 NOT NULL COMMENT '????',
  `addtime` int(10) unsigned NOT NULL COMMENT '????',
  `addtimestr` varchar(30) character set utf8 NOT NULL COMMENT '????',
  `comefrom` tinyint(1) unsigned NOT NULL default '0' COMMENT '0??1???',
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");

require("../../inc/footer.php");
?>