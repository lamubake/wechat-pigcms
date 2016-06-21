<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_diymen_class`;");
E_C("CREATE TABLE `imicms_diymen_class` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `pid` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `is_show` tinyint(1) NOT NULL,
  `sort` tinyint(3) NOT NULL,
  `type` varchar(30) NOT NULL default 'click',
  `url` varchar(255) default NULL,
  `wxsys` char(40) NOT NULL,
  `text` varchar(500) NOT NULL,
  `tel` varchar(20) default NULL,
  `emoji_code` varchar(16) NOT NULL COMMENT '图标码',
  `nav` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_diymen_class` values('6','257e21c8a9e1be8c','0','关于我们','http://wx.eake.cn/index.php?g=Wap&m=Index&a=content&token=257e21c8a9e1be8c&id=5','1','3','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('5','257e21c8a9e1be8c','0','服务项目','http://wx.eake.cn/index.php?g=Wap&m=Index&a=content&token=257e21c8a9e1be8c&id=5','1','2','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('4','257e21c8a9e1be8c','0','首页','http://wx.eake.cn/index.php?g=Wap&m=Index&a=index&token=257e21c8a9e1be8c','1','1','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('7','257e21c8a9e1be8c','5','保洁','http://wx.eake.cn/index.php?g=Wap&m=Index&a=lists&classid=4&token=257e21c8a9e1be8c&wecha_id=oupbZjof1M0-rpqKcJcylfXzKGG8','1','1','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('8','257e21c8a9e1be8c','5','家政','http://wx.eake.cn/index.php?g=Wap&m=Index&a=lists&classid=5&token=257e21c8a9e1be8c&wecha_id=oupbZjof1M0-rpqKcJcylfXzKGG8','1','2','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('9','257e21c8a9e1be8c','5','工程开荒','http://wx.eake.cn/index.php?g=Wap&m=Index&a=lists&classid=6&token=257e21c8a9e1be8c&wecha_id=oupbZjof1M0-rpqKcJcylfXzKGG8','1','3','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('14','257e21c8a9e1be8c','5','花园景观工程','http://wx.eake.cn/index.php?g=Wap&m=Index&a=lists&classid=7&token=257e21c8a9e1be8c&wecha_id=oupbZjof1M0-rpqKcJcylfXzKGG8','1','4','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('11','257e21c8a9e1be8c','6','联系我们','http://wx.eake.cn/index.php?g=Wap&m=Index&a=content&token=257e21c8a9e1be8c&id=12','1','0','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('12','257e21c8a9e1be8c','6','关于杰诚','http://wx.eake.cn/index.php?g=Wap&m=Index&a=content&token=257e21c8a9e1be8c&id=13','1','0','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('13','257e21c8a9e1be8c','6','在线预约','http://wx.eake.cn/index.php?g=Wap&m=Business&a=project&token=257e21c8a9e1be8c&wecha_id=oupbZjof1M0-rpqKcJcylfXzKGG8&type=housekeeper&bid=1&sid=1','1','0','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('15','257e21c8a9e1be8c','5','生活小常识','http://wx.eake.cn/index.php?g=Wap&m=Index&a=lists&classid=11&token=257e21c8a9e1be8c&wecha_id=oupbZjof1M0-rpqKcJcylfXzKGG8','1','5','1',NULL,'','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('16','99630ff411650cfa','0','首页','首页','1','0','click','','','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('17','99630ff411650cfa','0','使用帮助','查询','1','0','click','','','',NULL,'',NULL);");
E_D("replace into `imicms_diymen_class` values('18','99630ff411650cfa','0','关于我们','我们','1','0','click','','','',NULL,'',NULL);");

require("../../inc/footer.php");
?>