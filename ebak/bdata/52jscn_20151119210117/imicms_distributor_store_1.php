<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_distributor_store`;");
E_C("CREATE TABLE `imicms_distributor_store` (
  `id` int(10) NOT NULL auto_increment,
  `did` int(10) NOT NULL default '0' COMMENT '分销商id',
  `name` varchar(50) NOT NULL default '' COMMENT '店铺名称',
  `tpid` int(10) NOT NULL default '0' COMMENT '首页模板',
  `footerid` int(10) NOT NULL default '0' COMMENT '底部导航',
  `headerid` int(10) NOT NULL default '0' COMMENT '顶部导航',
  `payee` varchar(50) NOT NULL default '' COMMENT '收款人',
  `bankcard` varchar(50) NOT NULL default '' COMMENT '银行卡',
  `logourl` varchar(200) NOT NULL default '' COMMENT '店铺logo',
  `intro` text NOT NULL COMMENT '图文详细页内容',
  `bankname` varchar(50) NOT NULL default '' COMMENT '开户银行',
  `notice` varchar(200) NOT NULL default '' COMMENT '店铺公告',
  `noticetime` varchar(20) NOT NULL default '' COMMENT '公告时间',
  `banner` varchar(200) NOT NULL default '' COMMENT '首页banner',
  `allow_distribution` char(1) NOT NULL default '0' COMMENT '是否允许分销加盟 0,1',
  `commission_rate` float NOT NULL default '0' COMMENT '佣金分成',
  `product_source` char(1) NOT NULL default '1' COMMENT '分销商品来源 0, 1 不限, 本店铺',
  `ad_img` varchar(200) NOT NULL default '' COMMENT '分销引导广告（图片）',
  PRIMARY KEY  (`id`),
  KEY `did` USING BTREE (`did`),
  KEY `tpid` USING BTREE (`tpid`),
  KEY `footerid` USING BTREE (`footerid`),
  KEY `headerid` USING BTREE (`headerid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商店铺'");

require("../../inc/footer.php");
?>