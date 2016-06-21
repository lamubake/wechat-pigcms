<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_dish_order`;");
E_C("CREATE TABLE `imicms_dish_order` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `total` int(11) NOT NULL,
  `price` float NOT NULL,
  `nums` smallint(3) unsigned NOT NULL,
  `info` text NOT NULL,
  `name` varchar(100) NOT NULL,
  `sex` tinyint(1) NOT NULL,
  `tel` varchar(13) NOT NULL default '',
  `address` varchar(200) NOT NULL,
  `tableid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `reservetime` int(11) NOT NULL,
  `isuse` tinyint(1) NOT NULL,
  `paid` tinyint(1) unsigned NOT NULL,
  `orderid` varchar(100) NOT NULL,
  `printed` tinyint(1) unsigned NOT NULL,
  `des` varchar(500) NOT NULL,
  `takeaway` tinyint(1) unsigned NOT NULL,
  `catid` int(8) NOT NULL COMMENT '店铺id',
  `send_email` char(1) NOT NULL default '0' COMMENT '1已发0失败',
  `paytype` varchar(50) NOT NULL default '',
  `third_id` varchar(100) NOT NULL default '',
  `comefrom` varchar(50) NOT NULL default 'dish' COMMENT '表明来源字母全小写',
  `stype` tinyint(1) unsigned NOT NULL default '0' COMMENT '???ͷ?ʽ????',
  `isdel` tinyint(1) unsigned NOT NULL default '0' COMMENT '软删除1已删除',
  `allmark` text NOT NULL COMMENT '购物车备注',
  `havepaid` float unsigned NOT NULL default '0' COMMENT '二次支付时记录已经支付的金额',
  `paycount` tinyint(3) unsigned NOT NULL default '0' COMMENT '支付的次数',
  `advancepay` int(10) unsigned NOT NULL default '0' COMMENT '预付金额',
  `isover` tinyint(1) unsigned NOT NULL default '0' COMMENT '订单支付是否结束1进行2结束',
  `tmporderid` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cid` (`cid`,`wecha_id`),
  KEY `token` (`token`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_dish_order` values('1','1','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','99630ff411650cfa','3','180','1','a:2:{s:13:\"takeAwayPrice\";i:0;s:4:\"list\";a:1:{i:1;a:4:{s:5:\"price\";s:2:\"60\";s:3:\"num\";i:3;s:4:\"name\";s:12:\"麻辣螃蟹\";s:3:\"des\";s:0:\"\";}}}','','1','','','1','1412868437','1412868974','0','0','020141009232717','0','','2','0','0','','','dish','0','0','','0','0','0','0','');");
E_D("replace into `imicms_dish_order` values('2','1','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','99630ff411650cfa','1','60','1','a:2:{s:13:\"takeAwayPrice\";i:0;s:4:\"list\";a:1:{i:1;a:4:{s:5:\"price\";s:2:\"60\";s:3:\"num\";i:1;s:4:\"name\";s:12:\"麻辣螃蟹\";s:3:\"des\";s:0:\"\";}}}','','1','','','1','1413041310','1413041886','0','0','020141011232830','0','','2','0','0','','','dish','0','0','','0','0','0','0','');");

require("../../inc/footer.php");
?>