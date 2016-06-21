<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cashier_fans`;");
E_C("CREATE TABLE `imicms_cashier_fans` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` int(10) unsigned NOT NULL,
  `appid` varchar(200) NOT NULL COMMENT '公众号id',
  `openid` varchar(250) NOT NULL COMMENT '公众号对应的公众号openid',
  `cf` varchar(10) NOT NULL default 'local' COMMENT '来源',
  `totalfee` int(10) unsigned NOT NULL default '0' COMMENT '支付总额(分)',
  `refund` int(10) unsigned NOT NULL default '0' COMMENT '退款金额分',
  `is_subscribe` tinyint(4) NOT NULL COMMENT '1关注',
  `nickname` varchar(250) NOT NULL COMMENT '昵称',
  `sex` tinyint(1) unsigned NOT NULL default '0' COMMENT '1男2女0未知',
  `province` varchar(200) NOT NULL,
  `city` varchar(200) NOT NULL,
  `country` varchar(200) NOT NULL,
  `headimgurl` varchar(500) NOT NULL COMMENT '头像',
  `groupid` int(10) unsigned NOT NULL default '0' COMMENT '微信粉丝分组id',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>