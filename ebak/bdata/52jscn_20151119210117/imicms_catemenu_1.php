<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_catemenu`;");
E_C("CREATE TABLE `imicms_catemenu` (
  `id` int(10) NOT NULL auto_increment,
  `fid` int(10) NOT NULL default '0',
  `token` varchar(60) NOT NULL,
  `name` varchar(120) NOT NULL,
  `orderss` varchar(10) NOT NULL default '0',
  `picurl` varchar(1000) NOT NULL,
  `url` varchar(200) NOT NULL default '0',
  `status` varchar(10) NOT NULL,
  `RadioGroup1` varchar(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`,`orderss`,`status`),
  KEY `token_2` USING BTREE (`token`,`orderss`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_catemenu` values('49','36','99630ff411650cfa','了解我们','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu4.png','http://wx.eake.cn/index.php?g=Wap&amp;m=Index&amp;a=content&amp;token=99630ff411650cfa&amp;id=47','1','0');");
E_D("replace into `imicms_catemenu` values('48','35','99630ff411650cfa','微信墙','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu15.png','http://wx.eake.cn/index.php?g=User&amp;m=Wxq&amp;a=detail&amp;id=3','1','0');");
E_D("replace into `imicms_catemenu` values('45','35','99630ff411650cfa','微会员','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu2.png','{siteUrl}/index.php?g=Wap&amp;m=Card&amp;a=index&amp;token=99630ff411650cfa&amp;wecha_id={wechat_id}','1','0');");
E_D("replace into `imicms_catemenu` values('42','34','99630ff411650cfa','微全景','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu4.png','{siteUrl}/index.php?g=Wap&amp;m=Panoramic&amp;a=index&amp;token=99630ff411650cfa&amp;wecha_id={wechat_id}','1','0');");
E_D("replace into `imicms_catemenu` values('43','34','99630ff411650cfa','微教育','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu10.png','{siteUrl}/index.php?g=Wap&amp;m=School&amp;a=index&amp;token=99630ff411650cfa&amp;wecha_id={wechat_id}','1','0');");
E_D("replace into `imicms_catemenu` values('44','35','99630ff411650cfa','微喜帖','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu10.png','{siteUrl}/index.php?g=Wap&amp;m=Wcard&amp;a=index&amp;token=99630ff411650cfa&amp;wecha_id={wechat_id}&amp;id=1','1','0');");
E_D("replace into `imicms_catemenu` values('41','34','99630ff411650cfa','微商城','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu18.png','{siteUrl}/index.php?g=Wap&amp;m=Store&amp;a=cats&amp;token=99630ff411650cfa&amp;wecha_id={wechat_id}','1','0');");
E_D("replace into `imicms_catemenu` values('33','0','99630ff411650cfa','首页','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu6.png','http://wx.eake.cn/index.php?g=Wap&amp;m=Index&amp;a=index&amp;token=99630ff411650cfa','1','0');");
E_D("replace into `imicms_catemenu` values('34','0','99630ff411650cfa','微功能','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu7.png','','1','0');");
E_D("replace into `imicms_catemenu` values('35','0','99630ff411650cfa','微活动','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu11.png','','1','0');");
E_D("replace into `imicms_catemenu` values('36','0','99630ff411650cfa','关于我们','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu2.png','','1','0');");
E_D("replace into `imicms_catemenu` values('38','34','99630ff411650cfa','微房产','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu6.png','{siteUrl}/index.php?g=Wap&amp;m=Estate&amp;a=index&amp;token=99630ff411650cfa&amp;wecha_id={wechat_id}','1','0');");
E_D("replace into `imicms_catemenu` values('39','34','99630ff411650cfa','微汽车','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu14.png','{siteUrl}/index.php?g=Wap&amp;m=Car&amp;a=owner&amp;token=99630ff411650cfa&amp;wecha_id={wechat_id}','1','0');");
E_D("replace into `imicms_catemenu` values('40','34','99630ff411650cfa','微店','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu10.png','http://eake.cn/wap/home.php?id=59','1','0');");
E_D("replace into `imicms_catemenu` values('16','0','c4448ac95e30a1eb','首页','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu6.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=index&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}','1','0');");
E_D("replace into `imicms_catemenu` values('17','0','c4448ac95e30a1eb','生活助手','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu5.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=20','1','0');");
E_D("replace into `imicms_catemenu` values('18','0','c4448ac95e30a1eb','服务项目','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu2.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=21','1','0');");
E_D("replace into `imicms_catemenu` values('19','0','c4448ac95e30a1eb','关于我们','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu3.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=22','1','0');");
E_D("replace into `imicms_catemenu` values('20','19','c4448ac95e30a1eb','联系我们','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu1.png','tel:13888384509','1','0');");
E_D("replace into `imicms_catemenu` values('21','19','c4448ac95e30a1eb','关于杰成','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu4.png','','1','0');");
E_D("replace into `imicms_catemenu` values('22','17','c4448ac95e30a1eb','穿在安宁','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu18.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=27','1','0');");
E_D("replace into `imicms_catemenu` values('23','17','c4448ac95e30a1eb','吃在安宁','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu7.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=28','1','0');");
E_D("replace into `imicms_catemenu` values('24','17','c4448ac95e30a1eb','住在安宁','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu14.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=29','1','0');");
E_D("replace into `imicms_catemenu` values('25','17','c4448ac95e30a1eb','玩在安宁','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu16.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=30','1','0');");
E_D("replace into `imicms_catemenu` values('26','17','c4448ac95e30a1eb','其他生活常识','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu5.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=31','1','0');");
E_D("replace into `imicms_catemenu` values('27','18','c4448ac95e30a1eb','家政、保洁','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu19.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=24','1','0');");
E_D("replace into `imicms_catemenu` values('28','18','c4448ac95e30a1eb','工程开荒','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu10.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=25','1','0');");
E_D("replace into `imicms_catemenu` values('29','18','c4448ac95e30a1eb','花园景观工程','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu5.png','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;classid=26','1','0');");
E_D("replace into `imicms_catemenu` values('30','19','c4448ac95e30a1eb','在线预约','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu1.png','{siteUrl}/index.php?g=Wap&amp;m=Custom&amp;a=index&amp;token=c4448ac95e30a1eb&amp;wecha_id={wechat_id}&amp;id=3','1','0');");
E_D("replace into `imicms_catemenu` values('47','35','99630ff411650cfa','水果机','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu12.png','{siteUrl}/index.php?g=Wap&amp;m=LuckyFruit&amp;a=index&amp;token=99630ff411650cfa&amp;wecha_id={wechat_id}&amp;id=4','1','0');");
E_D("replace into `imicms_catemenu` values('46','35','99630ff411650cfa','大转盘','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu18.png','{siteUrl}/index.php?g=Wap&amp;m=Lottery&amp;a=index&amp;token=99630ff411650cfa&amp;wecha_id={wechat_id}&amp;id=1','1','0');");
E_D("replace into `imicms_catemenu` values('50','36','99630ff411650cfa','联系我们','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu1.png','tel:18987133915','1','0');");
E_D("replace into `imicms_catemenu` values('51','36','99630ff411650cfa','使用帮助','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu14.png','http://wx.eake.cn/index.php?g=Wap&amp;m=Index&amp;a=content&amp;token=99630ff411650cfa&amp;id=4','1','0');");
E_D("replace into `imicms_catemenu` values('52','34','99630ff411650cfa','微场景','0','','http://qx.eake.cn/v-U607225TG598','1','0');");
E_D("replace into `imicms_catemenu` values('53','0','1c5990460d702b81','联系我们','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu1.png','tel:15025106511','1','0');");
E_D("replace into `imicms_catemenu` values('54','0','1c5990460d702b81','关于我们','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu2.png','','1','0');");
E_D("replace into `imicms_catemenu` values('55','0','1c5990460d702b81','拍照预约','0','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu5.png','{siteUrl}/index.php?g=Wap&m=Custom&a=index&token=1c5990460d702b81&wecha_id={wechat_id}&id=4','1','0');");
E_D("replace into `imicms_catemenu` values('56','0','1c5990460d702b81','首页','1','http://wx.eake.cn/tpl/User/default/common/images/photo/plugmenu6.png','{siteUrl}/index.php?g=Wap&m=Index&a=index&token=1c5990460d702b81&wecha_id={wechat_id}','1','0');");

require("../../inc/footer.php");
?>