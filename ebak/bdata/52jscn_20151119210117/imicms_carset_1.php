<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_carset`;");
E_C("CREATE TABLE `imicms_carset` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL default '',
  `logo` varchar(200) NOT NULL default '',
  `head_url` varchar(200) NOT NULL default '',
  `url` varchar(200) NOT NULL,
  `title1` varchar(50) NOT NULL default '',
  `title2` varchar(50) NOT NULL default '',
  `title3` varchar(50) NOT NULL default '',
  `title4` varchar(50) NOT NULL default '',
  `title5` varchar(50) NOT NULL default '',
  `title6` varchar(50) NOT NULL default '',
  `head_url_1` varchar(200) NOT NULL default '',
  `head_url_2` varchar(200) NOT NULL default '',
  `head_url_3` varchar(200) NOT NULL default '',
  `head_url_4` varchar(200) NOT NULL default '',
  `head_url_5` varchar(200) NOT NULL default '',
  `head_url_6` varchar(200) NOT NULL default '',
  `url1` varchar(200) NOT NULL default '',
  `url2` varchar(200) NOT NULL default '',
  `url3` varchar(200) NOT NULL default '',
  `url4` varchar(200) NOT NULL default '',
  `url5` varchar(200) NOT NULL default '',
  `url6` varchar(200) NOT NULL default '',
  `path` varchar(3000) default '0',
  `tpid` int(11) default '23',
  `conttpid` int(11) default NULL,
  `title7` varchar(50) default NULL,
  `title8` varchar(50) default NULL,
  `title9` varchar(50) default NULL,
  `title10` varchar(50) default NULL,
  `title11` varchar(50) default NULL,
  `head_url_7` varchar(200) default NULL,
  `head_url_8` varchar(200) default NULL,
  `head_url_9` varchar(200) default NULL,
  `head_url_10` varchar(200) default NULL,
  `head_url_11` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_carset` values('1','99630ff411650cfa','汽车','微汽车演示','http://wx.eake.cn/tpl/User/default/common/car/logo.jpg','http://wx.eake.cn/tpl/User/default/common/car/car_title.jpg','','经销车型','销售顾问','在线预约','车主关怀','实用工具','车型欣赏','http://wx.eake.cn/tpl/User/default/common/car/car_jx.jpg','http://wx.eake.cn/tpl/User/default/common/car/car_sell.png','http://wx.eake.cn/tpl/User/default/common/car/car_yuyue.jpg','http://wx.eake.cn/tpl/User/default/common/car/carowner.png','http://wx.eake.cn/tpl/User/default/common/car/tool-box-preferences.png','http://wx.eake.cn/tpl/User/default/common/car/lanbo14.jpg','','','','','','','0','23','23',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);");

require("../../inc/footer.php");
?>