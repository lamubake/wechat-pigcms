<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_distributor_commission_record`;");
E_C("CREATE TABLE `imicms_distributor_commission_record` (
  `id` int(10) NOT NULL auto_increment,
  `did` int(10) NOT NULL default '0' COMMENT '分销商id',
  `uid` int(10) NOT NULL default '0' COMMENT '买家id 0为游客结算',
  `product_id` int(10) NOT NULL default '0' COMMENT '商品id 0为升级渠道商奖励',
  `amount` decimal(8,2) NOT NULL default '0.00' COMMENT '佣金',
  `bak` text NOT NULL COMMENT '备注',
  `addtime` varchar(20) NOT NULL default '0' COMMENT '添加时间',
  PRIMARY KEY  (`id`),
  KEY `did` USING BTREE (`did`),
  KEY `uid` USING BTREE (`uid`),
  KEY `product_id` USING BTREE (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商佣金记录'");

require("../../inc/footer.php");
?>