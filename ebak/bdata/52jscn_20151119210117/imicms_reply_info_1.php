<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_reply_info`;");
E_C("CREATE TABLE `imicms_reply_info` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `title` varchar(30) NOT NULL default '',
  `picurl` varchar(255) NOT NULL default '',
  `info` varchar(120) NOT NULL default '',
  `infotype` varchar(20) NOT NULL default '',
  `diningyuding` tinyint(1) NOT NULL default '1',
  `diningwaimai` tinyint(1) NOT NULL default '1',
  `config` text NOT NULL,
  `if_pay` tinyint(1) NOT NULL COMMENT '支付方式,0为关闭,1为开启',
  `pay_type` tinyint(1) NOT NULL COMMENT '支付方式,0为在线支付,1为货到付款',
  `keyword` varchar(50) NOT NULL default '',
  `apiurl` varchar(200) NOT NULL default '',
  `picurls1` varchar(255) NOT NULL,
  `picurls2` varchar(255) NOT NULL,
  `picurls3` varchar(255) NOT NULL,
  `readpass` char(40) default NULL,
  `banner` varchar(500) NOT NULL default '',
  `money_chg_send_sms` int(1) NOT NULL,
  `integral_chg_send_sms` int(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_reply_info` values('1','99630ff411650cfa','欢迎您的订餐','','我们会您送外卖','Dining','1','1','','0','0','订餐','','','','',NULL,'','0','0');");
E_D("replace into `imicms_reply_info` values('2','99630ff411650cfa','亚蓝微营销商城','','欢迎您查看我们的演示商城','Shop','1','1','','0','0','商城','','','','',NULL,'','0','0');");
E_D("replace into `imicms_reply_info` values('3','99630ff411650cfa','亚蓝--微酒店展示','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141107/99630ff411650cfa2014110700212069982.jpg','本初做为展示使用，不做为入驻只用。','Hotels','1','1','','0','0','酒店','','','','',NULL,'','0','0');");
E_D("replace into `imicms_reply_info` values('4','99630ff411650cfa','相册','/tpl/Wap/default/common/css/Photo/banner.jpg','','album','1','1','','0','0','','','','','',NULL,'','0','0');");
E_D("replace into `imicms_reply_info` values('5','99630ff411650cfa','微团购展示','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141111/99630ff411650cfa2014111101221434082.jpg','本功能只为给客户展示，数据部做真实性。请勿下单购买','Groupon','1','1','a:1:{s:5:\"tplid\";i:0;}','0','0','','','','','',NULL,'','0','0');");

require("../../inc/footer.php");
?>