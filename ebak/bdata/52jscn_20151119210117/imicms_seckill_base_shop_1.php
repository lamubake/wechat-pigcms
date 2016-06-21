<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_seckill_base_shop`;");
E_C("CREATE TABLE `imicms_seckill_base_shop` (
  `shop_id` int(11) NOT NULL auto_increment,
  `action_id` int(11) NOT NULL COMMENT '活动id',
  `shop_name` varchar(20) NOT NULL COMMENT '商品名称',
  `shop_num` int(11) NOT NULL COMMENT '商品库存',
  `shop_price` decimal(10,2) NOT NULL COMMENT '商品价格',
  `shop_tran` decimal(10,2) NOT NULL COMMENT '运费',
  `shop_open` tinyint(4) default '0' COMMENT '商品状态',
  `shop_detail` text COMMENT '商品描述文本',
  `shop_img` text NOT NULL,
  PRIMARY KEY  (`shop_id`),
  KEY `shop_name` USING BTREE (`shop_name`),
  KEY `action_id` USING BTREE (`action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>