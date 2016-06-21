<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_unitary_cart`;");
E_C("CREATE TABLE `imicms_unitary_cart` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(100) default NULL,
  `wecha_id` varchar(100) default NULL,
  `unitary_id` int(11) default NULL COMMENT '商品id',
  `count` int(11) default NULL COMMENT '数量',
  `state` int(11) NOT NULL default '0' COMMENT '购买/购物车状态',
  `order_id` int(11) default NULL COMMENT '订单id',
  `addtime` int(11) default NULL COMMENT '添加时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>