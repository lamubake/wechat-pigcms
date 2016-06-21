<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_classify`;");
E_C("CREATE TABLE `imicms_classify` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(60) NOT NULL,
  `info` varchar(90) NOT NULL COMMENT '分类描述',
  `ltype` varchar(30) NOT NULL,
  `sorts` tinyint(4) NOT NULL COMMENT '显示顺序',
  `img` char(255) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `url` char(255) NOT NULL,
  `tel` varchar(100) NOT NULL,
  `status` varchar(1) NOT NULL,
  `business_type` varchar(50) NOT NULL,
  `activity_type` tinyint(4) NOT NULL,
  `activity_value` bigint(11) NOT NULL,
  `place` varchar(255) NOT NULL,
  `lng` varchar(50) NOT NULL,
  `lat` varchar(50) NOT NULL,
  `token` varchar(30) NOT NULL,
  `createtime` datetime NOT NULL,
  `uptatetime` datetime NOT NULL,
  `click` int(11) NOT NULL default '0',
  `pid` int(11) NOT NULL COMMENT '父级分类',
  `fid` int(11) NOT NULL default '0',
  `path` varchar(3000) default '0',
  `tpid` int(10) default '1',
  `conttpid` int(10) default '1',
  `sonpagesize` int(2) NOT NULL default '5' COMMENT 'ֻÿҳʾ',
  `pc_web_id` int(11) NOT NULL,
  `pc_cat_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_classify` values('1','产品','所有产品项目介绍','','3','/tpl/static/home-300300.jpg','icon-group','','','1','','0','0','','','','99630ff411650cfa','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','257','3','5','0','0');");
E_D("replace into `imicms_classify` values('2','使用帮助','如果您还不了我们的微官网请点击此处','','0','/tpl/static/home-300300.jpg','icon-bullhorn','','','0','','0','0','','','','99630ff411650cfa','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','102','5','5','0','0');");
E_D("replace into `imicms_classify` values('4','保洁服务','家庭保洁   公司保洁','','2','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141004/257e21c8a9e1be8c2014100413245162693.jpg','icon-bullhorn','','','1','','0','0','','','','257e21c8a9e1be8c','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('5','家政服务','保姆  钟点工  老人看护','','3','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141004/257e21c8a9e1be8c2014100413235534038.jpg','icon-bullhorn','','','1','','0','0','','','','257e21c8a9e1be8c','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('6','工程开荒','新旧居开荒保洁；酒店、办公大楼新开荒、','','4','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141004/257e21c8a9e1be8c2014100413224746175.jpg','icon-bullhorn','','','1','','0','0','','','','257e21c8a9e1be8c','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('7','花园景观工程',' 入户花园景观设计施工 ','','5','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141003/257e21c8a9e1be8c2014100315091894929.jpg','icon-bullhorn','','','1','','0','0','','','','257e21c8a9e1be8c','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('10','首页','首页','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141004/257e21c8a9e1be8c2014100412270122214.jpg','icon-bullhorn','','','1','','0','0','','','','257e21c8a9e1be8c','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('8','联系我们','联系','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141003/257e21c8a9e1be8c2014100316125750698.jpg','icon-bullhorn','','','1','','0','0','','','','257e21c8a9e1be8c','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','105','8','5','0','0');");
E_D("replace into `imicms_classify` values('9','杰诚简介','关于我们','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141004/257e21c8a9e1be8c2014100413270614806.jpg','icon-bullhorn','','','1','','0','0','','','','257e21c8a9e1be8c','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('11','生活小常识','关注生活的方方面面','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141004/257e21c8a9e1be8c2014100413523882534.jpg','icon-bullhorn','','','1','','0','0','','','','257e21c8a9e1be8c','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('12','关于我们','了解我们','','0','/tpl/static/home-300300.jpg','icon-bullhorn','{siteUrl}/index.php?g=Wap&amp;m=Index&amp;a=lists&amp;token=99630ff411650cfa&amp;wecha_id={wechat_id}&amp;classid=14','','0','','0','0','','','','99630ff411650cfa','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','330','8','5','0','0');");
E_D("replace into `imicms_classify` values('14','亚蓝简介','亚蓝简介','','0','/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','99630ff411650cfa','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','12','0-12','38','1','5','0','0');");
E_D("replace into `imicms_classify` values('15','微信营销','讨论营销','','4','http://wx.eake.cn/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','99630ff411650cfa','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','257','8','5','0','0');");
E_D("replace into `imicms_classify` values('16','微博营销','微博营销','','5','/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','99630ff411650cfa','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','257','8','5','0','0');");
E_D("replace into `imicms_classify` values('17','新媒体营销','提供新媒体知识探讨及研究','','2','/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','99630ff411650cfa','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','257','8','5','0','0');");
E_D("replace into `imicms_classify` values('18','社会及其他营销','360一体化提供营销知识','','1','/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','99630ff411650cfa','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','257','8','5','0','0');");
E_D("replace into `imicms_classify` values('19','首页','首页','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111914395190535.jpg','icon-bullhorn','','','0','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('20','生活助手','生活信息','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111914571222079.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('21','服务项目','服务项目','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111914582950160.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('22','关于我们','关于我们','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111915010164689.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('23','关于杰成','关于杰成','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111915085455096.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','22','0-22','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('24','家政、保洁','家庭保洁   公司保洁','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111914582950160.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('25','工程开荒','新旧居开荒保洁；酒店、办公大楼新开荒、','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111915194594298.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('26','花园景观工程',' 入户花园景观设计施工','','0','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111915211498264.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','127','8','5','0','0');");
E_D("replace into `imicms_classify` values('27','穿在安宁','穿在安宁','','0','/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','20','0-20','38','1','5','0','0');");
E_D("replace into `imicms_classify` values('28','吃在安宁','吃在安宁','','0','/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','20','0-20','38','1','5','0','0');");
E_D("replace into `imicms_classify` values('29','住在安宁','住在安宁','','0','/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','20','0-20','38','1','5','0','0');");
E_D("replace into `imicms_classify` values('30','玩在安宁','玩在安宁','','0','/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','20','0-20','38','1','5','0','0');");
E_D("replace into `imicms_classify` values('31','其他生活常识','其他生活常识','','0','/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','c4448ac95e30a1eb','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','20','0-20','38','1','5','0','0');");
E_D("replace into `imicms_classify` values('32','微信热文','每天转载微信TOP热文','','0','/tpl/static/home-300300.jpg','icon-bullhorn','','','1','','0','0','','','','99630ff411650cfa','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','257','8','5','0','0');");
E_D("replace into `imicms_classify` values('33','美体','身体美容','','0','/tpl/static/home-300300.jpg','','','','1','','0','0','','','','2a94af5381fcc932','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','319','3','5','0','0');");
E_D("replace into `imicms_classify` values('34','美容','美白，去皱','','0','/tpl/static/home-300300.jpg','','','','1','','0','0','','','','2a94af5381fcc932','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','325','3','5','0','0');");
E_D("replace into `imicms_classify` values('35','丰胸','给你奇迹','','0','/tpl/static/home-300300.jpg','','','','1','','0','0','','','','2a94af5381fcc932','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','325','3','5','0','0');");
E_D("replace into `imicms_classify` values('36','美甲','女的第二长脸，您无需等待。','','0','/tpl/static/home-300300.jpg','','','','1','','0','0','','','','2a94af5381fcc932','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','325','3','5','0','0');");
E_D("replace into `imicms_classify` values('37','儿童摄影','儿童摄影','','0','http://wx.eake.cn/uploads/1/1c5990460d702b81/c/7/9/f/thumb_55e91d6a4c4b4.jpg','','','','1','','0','0','','','','1c5990460d702b81','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','258','3','5','0','0');");
E_D("replace into `imicms_classify` values('39','集体照','集体照','','0','http://wx.eake.cn/uploads/1/1c5990460d702b81/0/3/6/a/thumb_55e91f2698968.jpg','','','','1','','0','0','','','','1c5990460d702b81','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','258','3','5','0','0');");
E_D("replace into `imicms_classify` values('40','证件照','证件照','','0','http://wx.eake.cn/uploads/1/1c5990460d702b81/7/d/7/3/thumb_55e91fa4dd40a.jpg','','','','1','','0','0','','','','1c5990460d702b81','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','258','3','5','0','0');");
E_D("replace into `imicms_classify` values('41','全家福','全家福','','0','http://wx.eake.cn/uploads/1/1c5990460d702b81/e/0/5/5/thumb_55e91e794c4b4.jpg','','','','1','','0','0','','','','1c5990460d702b81','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','258','3','5','0','0');");
E_D("replace into `imicms_classify` values('42','婚礼纪实','婚礼当天全程跟拍','','0','http://wx.eake.cn/uploads/1/1c5990460d702b81/2/a/6/f/thumb_55e91c7631975.jpg','','','','1','','0','0','','','','1c5990460d702b81','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','258','3','5','0','0');");
E_D("replace into `imicms_classify` values('43','艺术照','记录最真实，最美的你','','0','http://wx.eake.cn/uploads/1/1c5990460d702b81/2/5/9/c/thumb_55e92f6698968.jpg','','','','1','','0','0','','','','1c5990460d702b81','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','0','0','38','1','5','0','0');");
E_D("replace into `imicms_classify` values('44','外景','最最自然真实的你','','0','http://wx.eake.cn/uploads/1/1c5990460d702b81/2/2/d/6/thumb_55e93194a037a.jpg','','','','1','','0','0','','','','1c5990460d702b81','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','43','0-43','38','1','5','0','0');");
E_D("replace into `imicms_classify` values('45','COSPIAY','动漫真人秀','','0','http://wx.eake.cn/uploads/1/1c5990460d702b81/f/c/e/0/thumb_55e943bd94c5f.jpg','','','','1','','0','0','','','','1c5990460d702b81','0000-00-00 00:00:00','0000-00-00 00:00:00','0','0','43','0-43','38','1','5','0','0');");

require("../../inc/footer.php");
?>