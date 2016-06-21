<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_distributor_order`;");
E_C("CREATE TABLE `imicms_distributor_order` (
  `id` int(10) NOT NULL auto_increment,
  `order_id` int(10) NOT NULL default '0' COMMENT '订单id',
  `did` int(10) NOT NULL default '0' COMMENT '分销商id',
  PRIMARY KEY  (`id`),
  KEY `order_id` USING BTREE (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商订单'");

require("../../inc/footer.php");
?>