<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_product`;");
E_C("CREATE TABLE `imicms_product` (
  `id` int(10) NOT NULL auto_increment,
  `catid` mediumint(4) NOT NULL default '0',
  `storeid` mediumint(4) NOT NULL default '0',
  `name` varchar(150) NOT NULL default '',
  `price` float NOT NULL default '0',
  `oprice` float NOT NULL default '0',
  `discount` float NOT NULL default '10',
  `intro` text NOT NULL,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(100) NOT NULL default '',
  `salecount` mediumint(4) NOT NULL default '0',
  `logourl` varchar(150) NOT NULL default '',
  `dining` tinyint(1) NOT NULL default '0',
  `groupon` tinyint(1) NOT NULL default '0',
  `endtime` int(10) NOT NULL default '0',
  `fakemembercount` mediumint(4) NOT NULL default '0',
  `time` int(10) NOT NULL default '0',
  `sort` int(10) NOT NULL default '0',
  `vprice` float NOT NULL,
  `mailprice` float NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `cid` int(10) unsigned NOT NULL,
  `gid` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `commission_type` varchar(10) NOT NULL default '' COMMENT '佣金类型 固定金额fixed, 百分比 float',
  `commission` decimal(8,2) NOT NULL default '0.00' COMMENT '分销佣金',
  `allow_distribution` char(1) NOT NULL default '0' COMMENT '允许分销 0, 1',
  `sn` tinyint(3) unsigned NOT NULL default '0',
  `sn_name` varchar(200) default NULL,
  `sn_pass` varchar(200) default NULL,
  `groupon_num` int(10) unsigned NOT NULL default '200',
  PRIMARY KEY  (`id`),
  KEY `catid` (`catid`,`storeid`),
  KEY `catid_2` (`catid`),
  KEY `storeid` (`storeid`),
  KEY `token` (`token`),
  KEY `price` (`price`),
  KEY `oprice` (`oprice`),
  KEY `discount` (`discount`),
  KEY `dining` (`dining`),
  KEY `groupon` (`groupon`,`endtime`),
  KEY `cid` (`cid`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_product` values('1','8388607','1','拉博基尼','1','1e+006','0','&lt;span style=&quot;font-family:arial, sans-serif;font-size:14px;line-height:28px;white-space:normal;background-color:#FFFFFF;&quot;&gt;作为全球顶级跑车制造商及欧洲奢侈品标志之一，兰博基尼一贯秉承将极致速度与时尚风格融为一体的品牌理念，不断创新并寻求全新品牌突破。此次涉足时装领域，将为兰博基尼品牌拥趸者提供更加丰富多彩的品牌产品选择。兰博基尼（Lamborghini），又被翻译作朗博基尼，林保坚尼。在意大利乃至全世界，兰博基尼是诡异的，它神秘地诞生，出人意料地推出一款又一款的让人咋舌的超级跑车。&lt;/span&gt;&lt;span style=&quot;font-family:arial, sans-serif;font-size:14px;line-height:28px;white-space:normal;background-color:#FFFFFF;&quot;&gt;&lt;/span&gt;','99630ff411650cfa','团购','1','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141111/99630ff411650cfa2014111101242568370.jpg','0','1','1419177599','100','1415640351','0','0','0','0','1','0','0','','0.00','0','0',NULL,NULL,'200');");

require("../../inc/footer.php");
?>