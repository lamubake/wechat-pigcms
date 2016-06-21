<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_live`;");
E_C("CREATE TABLE `imicms_live` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `show_company` enum('0','1') NOT NULL,
  `name` char(50) NOT NULL,
  `keyword` char(40) NOT NULL,
  `open_pic` char(120) NOT NULL,
  `is_masking` enum('0','1') NOT NULL,
  `masking_pic` char(120) NOT NULL,
  `intro` varchar(500) NOT NULL,
  `music` char(120) NOT NULL,
  `end_pic` char(120) NOT NULL,
  `share_bg` char(120) NOT NULL,
  `share_button` char(120) NOT NULL,
  `add_time` char(11) NOT NULL,
  `is_open` enum('0','1') NOT NULL,
  `token` char(25) character set cp1251 collate cp1251_bin NOT NULL,
  `warn` char(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_live` values('1','1','亚蓝微营销-微场景展示','微场景','http://wx.eake.cn/tpl/static/attachment/background/car/9.jpg','1','http://wx.eake.cn/tpl/static/attachment/background/car/4.jpg','本次或是是展示，不做为任何活动使用！','http://wx.eake.cn/tpl/static/live/default/mis.mp3','','http://wx.eake.cn/tpl/static/attachment/background/car/10.jpg','http://wx.eake.cn/tpl/static/live/default/share-button.png','1415293650','1','99630ff411650cfa','');");

require("../../inc/footer.php");
?>