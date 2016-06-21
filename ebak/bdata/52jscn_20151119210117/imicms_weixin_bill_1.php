<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_weixin_bill`;");
E_C("CREATE TABLE `imicms_weixin_bill` (
  `imicms_id` int(11) NOT NULL auto_increment,
  `orderid` varchar(60) NOT NULL,
  `price` float NOT NULL,
  `wecha_id` varchar(100) NOT NULL,
  `token` varchar(50) NOT NULL,
  `paid` tinyint(1) NOT NULL default '0',
  `from` varchar(50) NOT NULL,
  `time` int(11) NOT NULL,
  `third_id` varchar(64) NOT NULL COMMENT '第三方支付id',
  `plat_type` tinyint(1) unsigned NOT NULL COMMENT '微信支付到账号来源（0：当前的微信号，1：系统平台的账号，2：自己公司的其他账号）',
  `appid` varchar(64) NOT NULL COMMENT '支付到账号的appid',
  `mchid` varchar(64) NOT NULL COMMENT '支付到账号的商户ID',
  PRIMARY KEY  (`imicms_id`),
  KEY `time` (`time`),
  KEY `orderid` (`orderid`,`from`),
  KEY `third_id` (`third_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>