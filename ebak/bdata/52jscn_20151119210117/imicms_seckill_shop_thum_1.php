<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_seckill_shop_thum`;");
E_C("CREATE TABLE `imicms_seckill_shop_thum` (
  `id` int(11) NOT NULL auto_increment,
  `shop_id` varchar(20) NOT NULL COMMENT '商品id',
  `shop_thum` varchar(500) NOT NULL COMMENT '缩略图',
  `shop_big` varchar(500) NOT NULL COMMENT '大图',
  PRIMARY KEY  (`id`),
  KEY `shop_id` USING BTREE (`shop_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>