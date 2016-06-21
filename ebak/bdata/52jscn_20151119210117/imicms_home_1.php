<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_home`;");
E_C("CREATE TABLE `imicms_home` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(60) NOT NULL,
  `webname` varchar(255) NOT NULL,
  `title` varchar(30) NOT NULL,
  `matchtype` tinyint(1) NOT NULL default '0',
  `show_bg_img` text NOT NULL COMMENT '官网背景图片',
  `bg_img` text NOT NULL COMMENT '选择已有的官网背景',
  `bg_audio` text NOT NULL COMMENT '背景音乐',
  `play_img` varchar(3) NOT NULL default 'on' COMMENT '开启背景图片',
  `play_audio` varchar(3) NOT NULL default 'on' COMMENT '开启背景音乐',
  `animation` tinyint(2) NOT NULL default '0' COMMENT '开场动画',
  `stat_code` text NOT NULL COMMENT '统计代码',
  `picurl` text NOT NULL COMMENT '图文消息封面',
  `info` text NOT NULL COMMENT '图文消息简介',
  `apiurl` varchar(255) NOT NULL,
  `homeurl` varchar(255) NOT NULL,
  `musicurl` varchar(255) NOT NULL,
  `plugmenucolor` varchar(10) NOT NULL,
  `copyright` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `radiogroup` mediumint(4) NOT NULL default '0',
  `advancetpl` tinyint(1) NOT NULL default '0',
  `gzhurl` char(255) default NULL COMMENT '公众号链接地址',
  `stpic` varchar(200) NOT NULL COMMENT '开场动画图片',
  `start` int(11) NOT NULL COMMENT '开场动画',
  `switch` int(11) NOT NULL default '0',
  `pinglun` int(1) unsigned NOT NULL default '1' COMMENT '是否开启微站全站评论',
  `uyanid` varchar(50) NOT NULL default '' COMMENT '友言官网注册的uid',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_home` values('1','99630ff411650cfa','亚蓝旗下产品--亚蓝微营销案列展示','首页','1','','','','on','on','0','','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20141030/99630ff411650cfa2014103019372119008.jpg','亚蓝信息技术有限公司是一家集网站开发，模板制作、国内流行的CMS二次开为主的技术性公司。我们为你提供企业网站建设的终端服务，让您无需通过复杂的方式搭建企业网站。\r\n我们让互联网变得如此简单，诚信、共赢、共同进步。\r\n亚蓝微信营销平台提供：微信公众号功能开发、百度直达号等公众服务对接！有疑问可以加QQ：61091793咨询\r\n回复：产品  查看更多服务\r\n回复：营销  您可以学习到微信营销知识\r\n回复：微博  你可以了解到微博营销知识\r\n直接点击可以查看到亚蓝微官网\r\n不会玩？可直接回复：查询  玩转本公众号。\r\n','','','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/mp3/20141125/99630ff411650cfa2014112515214170452.mp3','#FFFFFC','亚蓝信息技术有限公司版权所有','','11','0','','http://s.404.cn/tpl/static/home/kcdhbj.jpg','0','0','1','');");
E_D("replace into `imicms_home` values('2','257e21c8a9e1be8c','首页','首页','1','','','','on','on','0','','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141003/257e21c8a9e1be8c2014100315050323268.jpg','杰诚家政服务有限公司成立于2004年7月，公司致力于家政服务行业。公司旗下目前由：家政服务、保洁服务、园林绿化、花园景观工程、经济信息咨询、礼仪庆典服务、承办会议及商品展示。','','','','','','','0','0',NULL,'','0','0','1','');");
E_D("replace into `imicms_home` values('3','c4448ac95e30a1eb','杰诚生活首页','首页','1','','','','on','on','0','','http://wx.eake.cn/tpl/Static/kindeditors/attached/c4448ac95e30a1eb/image/20141119/c4448ac95e30a1eb2014111914395190535.jpg','杰诚家政服务有限公司成立于2004年7月，公司致力于家政服务行业。公司旗下目前由：家政服务、保洁服务、园林绿化、花园景观工程、经济信息咨询、礼仪庆典服务、承办会议及商品展示。','','','','','','','10','0',NULL,'','0','0','1','');");
E_D("replace into `imicms_home` values('4','2a94af5381fcc932','雁鹰美体微官网','首页','0','','','','on','on','0','','http://wx.eake.cn/uploads/2/2a94af5381fcc932/4/a/1/8/thumb_54de1173dbba4.jpg','雁鹰美体美容生活馆位于安宁市昆钢蓝天城B区13幢703室,交通便利，雁鹰美体美容生活馆成立于2014年5月，主营：美体、美容、丰胸、美甲为一体的时尚女人生活馆。热情欢迎各位光临体验，雁鹰美体美容生活馆以城信，双赢为理念，提供先进的技术并力创一流的服务。','','','http://wx.eake.cn/tpl/static/attachment/music/default/2.mp3','','','http://wx.eake.cn/uploads/2/2a94af5381fcc932/5/f/9/2/thumb_54de078627fd0.jpg','0','0','','http://s.404.cn/tpl/static/home/kcdhbj.jpg','0','0','1','');");
E_D("replace into `imicms_home` values('5','1c5990460d702b81','虹贝儿摄影','首页','0','','','','on','on','0','','http://wx.eake.cn/uploads/1/1c5990460d702b81/6/1/1/9/thumb_55e65df70b71b.jpg','虹贝儿摄影是一家专业从事儿童摄影的工作室','','','','','','http://wx.eake.cn/uploads/1/1c5990460d702b81/0/d/c/8/thumb_55e661268583b.jpg','12','0','','http://s.404.cn/tpl/static/home/kcdhbj.jpg','0','0','1','');");

require("../../inc/footer.php");
?>