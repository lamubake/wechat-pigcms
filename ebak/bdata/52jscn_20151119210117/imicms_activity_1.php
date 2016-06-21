<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_activity`;");
E_C("CREATE TABLE `imicms_activity` (
  `id` int(11) NOT NULL auto_increment,
  `joinnum` int(11) NOT NULL COMMENT '参与人数',
  `click` int(11) NOT NULL,
  `token` varchar(30) NOT NULL,
  `keyword` varchar(10) NOT NULL,
  `starpicurl` varchar(100) NOT NULL COMMENT '活动开始图片',
  `title` varchar(60) NOT NULL COMMENT '活动名称',
  `txt` varchar(60) NOT NULL COMMENT '兑奖信息',
  `sttxt` varchar(200) NOT NULL COMMENT '活动主题',
  `statdate` int(11) NOT NULL COMMENT '活动开始时间',
  `enddate` int(11) NOT NULL COMMENT '活动结束时间',
  `info` varchar(200) NOT NULL COMMENT '活动说明',
  `aginfo` varchar(200) NOT NULL COMMENT '活动规则',
  `endtite` varchar(60) NOT NULL COMMENT '结束公告',
  `endpicurl` varchar(100) NOT NULL COMMENT '结束图片地址',
  `endinfo` varchar(60) NOT NULL,
  `fist` varchar(50) NOT NULL COMMENT '一等奖奖品',
  `fistnums` int(4) NOT NULL COMMENT '一等奖奖品数量',
  `fistlucknums` int(1) NOT NULL COMMENT '一等奖中奖人数',
  `second` varchar(50) NOT NULL COMMENT '二等奖奖品',
  `type` tinyint(1) NOT NULL,
  `secondnums` int(4) NOT NULL COMMENT '二等奖奖品数量',
  `secondlucknums` int(1) NOT NULL COMMENT '三等奖中奖人数',
  `third` varchar(50) NOT NULL,
  `thirdnums` int(4) NOT NULL,
  `thirdlucknums` int(1) NOT NULL,
  `allpeople` int(11) NOT NULL COMMENT '预计参与人数',
  `canrqnums` int(2) NOT NULL COMMENT '个人抽奖次数限制',
  `parssword` int(15) NOT NULL,
  `renamesn` varchar(50) NOT NULL default '',
  `renametel` varchar(60) NOT NULL,
  `displayjpnums` int(1) NOT NULL,
  `createtime` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `four` varchar(50) NOT NULL COMMENT '四等奖奖品',
  `fournums` int(11) NOT NULL COMMENT '四等奖奖品数量',
  `fourlucknums` int(11) NOT NULL COMMENT '四等奖中奖人数',
  `five` varchar(50) NOT NULL COMMENT '五等奖奖品',
  `fivenums` int(11) NOT NULL COMMENT '5奖品数量',
  `fivelucknums` int(11) NOT NULL COMMENT '5中间人数',
  `six` varchar(50) NOT NULL COMMENT '六等奖奖品',
  `sixnums` int(11) NOT NULL COMMENT '6奖品数量',
  `sixlucknums` int(11) NOT NULL COMMENT '6中奖人数',
  `zjpic` varchar(150) NOT NULL default '',
  `daynums` mediumint(4) NOT NULL default '0',
  `maxgetprizenum` mediumint(4) NOT NULL default '1',
  `needreg` tinyint(1) NOT NULL default '0',
  `backmp3` varchar(100) NOT NULL COMMENT '背景音乐',
  `hpic` varchar(150) NOT NULL COMMENT '首页背景图片',
  `xpic` varchar(150) NOT NULL COMMENT '兑奖页面宣传图片',
  `mpic` varchar(150) NOT NULL COMMENT '我的盒子背景图片',
  `optime` int(11) NOT NULL COMMENT '需要分享的次数',
  `focus` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_activity` values('2','0','0','99630ff411650cfa','拆礼盒','http://wx.eake.cn/uploads/9/99630ff411650cfa/e/f/a/8/thumb_5472abdeb512a.jpg','拆礼盒活动开始了','兑奖请联系我们，电话138********','欢迎参加拆礼盒活动，祝您好运哦！','1416758400','1419350400','亲，请点击进入拆礼盒活动页面，祝您好运哦！','此活动杜绝恶意刷礼盒，每个手机只能有一个微信号帮忙。','拆礼盒活动已经结束了','http://wx.eake.cn/uploads/9/99630ff411650cfa/e/f/a/8/thumb_5472abdeb512a.jpg','亲，活动已经结束，请继续关注我们的后续活动哦。','','0','0','','1','0','0','','0','0','0','0','0','','','0','0','1','','0','0','','0','0','','0','0','','0','1','0','http://s.404.cn/tpl/static/autumns/mp3/bg.mp3','http://s.404.cn/tpl/static/autumns/img/backimg.jpg','http://s.404.cn/tpl/static/autumns/img/openimg.jpg','http://s.404.cn/tpl/static/autumns/img/myboximg.jpg','0','0');");

require("../../inc/footer.php");
?>