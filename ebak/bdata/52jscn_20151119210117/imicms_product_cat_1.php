<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_product_cat`;");
E_C("CREATE TABLE `imicms_product_cat` (
  `id` mediumint(4) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL default '',
  `name` varchar(50) NOT NULL,
  `des` varchar(500) NOT NULL default '',
  `parentid` mediumint(4) NOT NULL,
  `logourl` varchar(255) NOT NULL,
  `dining` tinyint(1) NOT NULL default '0',
  `time` int(10) NOT NULL,
  `norms` varchar(100) NOT NULL,
  `color` varchar(100) NOT NULL,
  `sort` int(10) NOT NULL default '0',
  `cid` int(10) unsigned NOT NULL,
  `isfinal` tinyint(1) unsigned NOT NULL default '1',
  `pc_web_id` int(11) NOT NULL,
  `pc_cat_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `parentid` (`parentid`),
  KEY `token` (`token`),
  KEY `dining` (`dining`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_product_cat` values('1','99630ff411650cfa','手机','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141104/99630ff411650cfa2014110412045544260.jpg','0','1415073912','','','0','1','1','0','0');");
E_D("replace into `imicms_product_cat` values('2','99630ff411650cfa','化妆品','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141104/99630ff411650cfa2014110412074587060.jpg','0','1415074066','','','0','1','1','0','0');");
E_D("replace into `imicms_product_cat` values('4','99630ff411650cfa','数码产品','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141104/99630ff411650cfa2014110412100989192.jpg','0','1415074211','','','0','1','1','0','0');");
E_D("replace into `imicms_product_cat` values('5','99630ff411650cfa','体育用品','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141104/99630ff411650cfa2014110412103915061.gif','0','1415074254','','','0','1','1','0','0');");
E_D("replace into `imicms_product_cat` values('6','99630ff411650cfa','衣服','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141104/99630ff411650cfa2014110412111884100.jpg','0','1415074281','','','0','1','1','0','0');");
E_D("replace into `imicms_product_cat` values('7','99630ff411650cfa','文玩','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141104/99630ff411650cfa2014110412141389939.jpg','0','1415074456','','','0','1','1','0','0');");

require("../../inc/footer.php");
?>