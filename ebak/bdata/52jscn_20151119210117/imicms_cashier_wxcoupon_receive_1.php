<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cashier_wxcoupon_receive`;");
E_C("CREATE TABLE `imicms_cashier_wxcoupon_receive` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `openid` varchar(250) NOT NULL COMMENT '领取人openid',
  `give_openId` varchar(250) NOT NULL COMMENT '转赠送方账号openid',
  `cardid` varchar(250) NOT NULL,
  `cardtype` tinyint(1) unsigned NOT NULL default '0' COMMENT '卡券类型',
  `cardtitle` varchar(250) NOT NULL COMMENT '卡券标题',
  `isgivebyfriend` tinyint(1) unsigned NOT NULL default '0' COMMENT '是否为转赠',
  `cardcode` varchar(100) NOT NULL COMMENT 'code序列号',
  `oldcardcode` varchar(100) NOT NULL COMMENT '转赠前的code序列号',
  `outerid` int(10) unsigned NOT NULL COMMENT 'mid值',
  `status` tinyint(3) unsigned NOT NULL default '0' COMMENT '0领取1核销',
  `addtime` int(10) unsigned NOT NULL COMMENT '添加时间',
  `deltime` int(10) unsigned NOT NULL COMMENT '用户删除时间',
  `consumetime` int(10) unsigned NOT NULL COMMENT '消费时间',
  `consumesource` varchar(100) NOT NULL COMMENT '核销来源',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>