<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_set`;");
E_C("CREATE TABLE `imicms_member_card_set` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `cardname` varchar(60) NOT NULL,
  `miniscore` int(10) NOT NULL default '0',
  `clogo` varchar(200) NOT NULL,
  `logo` varchar(200) NOT NULL,
  `bg` varchar(100) NOT NULL,
  `diybg` varchar(200) NOT NULL,
  `msg` varchar(100) NOT NULL,
  `numbercolor` varchar(10) NOT NULL,
  `vipnamecolor` varchar(10) NOT NULL,
  `Lastmsg` varchar(100) NOT NULL,
  `vip` varchar(100) NOT NULL,
  `qiandao` varchar(100) NOT NULL,
  `shopping` varchar(100) NOT NULL,
  `memberinfo` varchar(100) NOT NULL,
  `membermsg` varchar(100) NOT NULL,
  `contact` varchar(100) NOT NULL,
  `create_time` int(11) NOT NULL,
  `recharge` varchar(100) NOT NULL default '/tpl/User/default/common/images/cart_info/recharge.jpg',
  `payrecord` varchar(100) NOT NULL default '/tpl/User/default/common/images/cart_info/payrecord.jpg',
  `info` text NOT NULL,
  `company_pwd` char(32) NOT NULL,
  `is_check` enum('0','1') NOT NULL,
  `donate_intro` text NOT NULL,
  `is_donate` tinyint(4) NOT NULL,
  `showname` int(1) unsigned NOT NULL default '1' COMMENT '显示名称',
  `showlogo` int(1) unsigned NOT NULL default '1' COMMENT '显示logo',
  `sub_give` tinyint(1) unsigned NOT NULL COMMENT '关注是赠送（0：不赠送，1：赠送）',
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`),
  KEY `miniscore` (`miniscore`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_member_card_set` values('2','99630ff411650cfa','亚蓝微营销会员卡','0','','','./tpl/User/default/common/images/card/card_bg15.png','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141104/99630ff411650cfa2014110413483762745.png','微时代会员卡，方便携带收藏，永不挂失','#FF9900','#FF9900','/tpl/User/default/common/images/cart_info/news.jpg','/tpl/User/default/common/images/cart_info/vippower.jpg','/tpl/User/default/common/images/cart_info/qiandao.jpg','/tpl/User/default/common/images/cart_info/shopping.jpg','/tpl/User/default/common/images/cart_info/user.jpg','/tpl/User/default/common/images/cart_info/vippower.jpg','/tpl/User/default/common/images/cart_info/addr.jpg','1415077670','/tpl/User/default/common/images/cart_info/recharge.jpg','/tpl/User/default/common/images/cart_info/payrecord.jpg','','','0','','0','1','1','0');");

require("../../inc/footer.php");
?>