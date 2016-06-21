<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_photo`;");
E_C("CREATE TABLE `imicms_photo` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `title` varchar(20) NOT NULL,
  `keyword` varchar(200) NOT NULL,
  `kw_pic` text NOT NULL,
  `picurl` text NOT NULL,
  `num` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `create_time` int(11) NOT NULL,
  `info` text NOT NULL,
  `sort` tinyint(4) NOT NULL,
  `isshoinfo` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_photo` values('1','257e21c8a9e1be8c','杰诚家政有限公司','相册','http://wx.eake.cn/tpl/static/images/albums_kw_headpic.jpg','http://wx.eake.cn/tpl/static/images/albums_head.jpg','0','1','1412412095','家政公司相册','10','0');");
E_D("replace into `imicms_photo` values('2','99630ff411650cfa','亚蓝微营销-该相册展示了楼盘效果','相册','http://wx.eake.cn/tpl/static/images/albums_kw_headpic.jpg','http://wx.eake.cn/tpl/static/images/albums_head.jpg','5','1','1415292101','该相册展示了地产公司一般使用图片展示楼盘效果，表达清楚，直观。购房者能够清楚的看到该楼盘的经典展示效果。','10','0');");

require("../../inc/footer.php");
?>