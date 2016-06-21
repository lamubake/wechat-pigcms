<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_unitary_lucknum`;");
E_C("CREATE TABLE `imicms_unitary_lucknum` (
  `id` int(11) NOT NULL auto_increment,
  `order_id` int(11) default NULL COMMENT '订单id',
  `token` varchar(100) default NULL,
  `wecha_id` varchar(100) default NULL,
  `lucknum` int(11) default NULL,
  `addtime` double default NULL,
  `unitary_id` int(11) default NULL,
  `cart_id` int(11) default NULL COMMENT '购物id',
  `state` int(11) NOT NULL default '0',
  `paifa` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>