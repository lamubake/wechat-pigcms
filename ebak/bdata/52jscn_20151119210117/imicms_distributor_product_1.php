<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_distributor_product`;");
E_C("CREATE TABLE `imicms_distributor_product` (
  `id` int(10) NOT NULL auto_increment,
  `pid` int(10) NOT NULL default '0' COMMENT '商品id',
  `did` int(10) NOT NULL default '0' COMMENT '分销商id',
  `soldout` char(1) NOT NULL default '0' COMMENT '商品下架 0，1',
  `salesnum` int(5) NOT NULL default '0' COMMENT '销售量',
  `salestotal` decimal(8,2) NOT NULL default '0.00' COMMENT '销售额',
  `time` varchar(20) NOT NULL default '' COMMENT '操作时间(上架、下架)',
  PRIMARY KEY  (`id`),
  KEY `pid` USING BTREE (`pid`),
  KEY `did` USING BTREE (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商商品'");

require("../../inc/footer.php");
?>