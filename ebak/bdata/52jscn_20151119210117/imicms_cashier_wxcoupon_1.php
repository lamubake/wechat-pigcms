<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cashier_wxcoupon`;");
E_C("CREATE TABLE `imicms_cashier_wxcoupon` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` int(10) unsigned NOT NULL,
  `card_type` tinyint(1) unsigned NOT NULL default '0',
  `card_title` varchar(250) NOT NULL,
  `card_id` varchar(250) NOT NULL COMMENT '微信卡券ID',
  `status` tinyint(1) NOT NULL default '0' COMMENT '卡券状态',
  `isdel` tinyint(1) unsigned NOT NULL default '0' COMMENT '1删除',
  `begin_timestamp` int(10) unsigned NOT NULL default '0',
  `end_timestamp` int(10) unsigned NOT NULL default '0',
  `quantity` int(10) unsigned NOT NULL default '0' COMMENT '库存',
  `receivenum` int(10) unsigned NOT NULL default '0' COMMENT '领取数',
  `consumenum` int(10) unsigned NOT NULL default '0' COMMENT '核销数量',
  `get_limit` int(10) unsigned NOT NULL default '1' COMMENT '每人可领几张',
  `kqcontent` text NOT NULL COMMENT '卡券内容',
  `kqexpand` text NOT NULL COMMENT '卡券扩展内容',
  `checktime` int(10) unsigned NOT NULL default '0' COMMENT '审核通过时间',
  `addtime` int(10) unsigned NOT NULL default '0' COMMENT '添加时间',
  `cardticket` varchar(250) NOT NULL,
  `cardurl` varchar(250) NOT NULL COMMENT ' 二维码图片解析后的地址',
  PRIMARY KEY  (`id`),
  KEY `mid` (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>