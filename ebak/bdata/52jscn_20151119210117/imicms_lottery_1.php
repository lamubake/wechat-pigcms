<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_lottery`;");
E_C("CREATE TABLE `imicms_lottery` (
  `id` int(11) NOT NULL auto_increment,
  `joinnum` int(11) NOT NULL COMMENT '参与人数',
  `click` int(11) NOT NULL,
  `token` varchar(30) NOT NULL,
  `keyword` varchar(10) NOT NULL,
  `starpicurl` varchar(255) NOT NULL COMMENT '填写活动开始图片网址',
  `title` varchar(60) NOT NULL COMMENT '活动名称',
  `txt` varchar(60) NOT NULL COMMENT '用户输入兑奖时候的显示信息',
  `sttxt` varchar(200) NOT NULL COMMENT '简介',
  `statdate` int(11) NOT NULL COMMENT '活动开始时间',
  `enddate` int(11) NOT NULL COMMENT '活动结束时间',
  `info` varchar(200) NOT NULL COMMENT '活动说明',
  `aginfo` varchar(200) NOT NULL COMMENT '重复抽奖回复',
  `endtite` varchar(60) NOT NULL COMMENT '活动结束公告主题',
  `endpicurl` varchar(255) NOT NULL,
  `endinfo` varchar(60) NOT NULL,
  `fist` varchar(50) NOT NULL COMMENT '一等奖奖品设置',
  `fistnums` int(4) NOT NULL COMMENT '一等奖奖品数量',
  `fistlucknums` int(1) NOT NULL COMMENT '一等奖中奖号码',
  `second` varchar(50) NOT NULL COMMENT '二等奖奖品设置',
  `type` tinyint(1) NOT NULL,
  `secondnums` int(4) NOT NULL,
  `secondlucknums` int(1) NOT NULL,
  `third` varchar(50) NOT NULL,
  `thirdnums` int(4) NOT NULL,
  `thirdlucknums` int(1) NOT NULL,
  `allpeople` int(11) NOT NULL,
  `canrqnums` int(2) NOT NULL COMMENT '个人限制抽奖次数',
  `parssword` int(15) NOT NULL,
  `renamesn` varchar(50) NOT NULL,
  `renametel` varchar(60) NOT NULL,
  `displayjpnums` int(1) NOT NULL,
  `createtime` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  `four` varchar(50) NOT NULL,
  `fournums` int(11) NOT NULL,
  `fourlucknums` int(11) NOT NULL,
  `five` varchar(50) NOT NULL,
  `fivenums` int(11) NOT NULL,
  `fivelucknums` int(11) NOT NULL,
  `six` varchar(50) NOT NULL COMMENT '六等奖',
  `sixnums` int(11) NOT NULL,
  `sixlucknums` int(11) NOT NULL,
  `zjpic` varchar(150) NOT NULL default '',
  `daynums` mediumint(4) NOT NULL default '0',
  `maxgetprizenum` mediumint(4) NOT NULL default '1',
  `needreg` tinyint(1) NOT NULL default '0',
  `guanzhu` int(11) default NULL COMMENT '是否关注',
  `fistpic` varchar(100) default NULL,
  `secondpic` varchar(100) default NULL,
  `thirdpic` varchar(100) default NULL,
  `fourpic` varchar(100) default NULL,
  `fivepic` varchar(100) default NULL,
  `sixpic` varchar(100) default NULL,
  `infotxt` text NOT NULL,
  `bg` varchar(100) default NULL,
  `bgtype` int(11) NOT NULL default '0',
  `timespan` int(11) NOT NULL default '0',
  `isdaylottery` int(11) NOT NULL default '0',
  `cardid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`),
  KEY `zjpic` USING BTREE (`zjpic`),
  KEY `zjpic_2` USING BTREE (`zjpic`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_lottery` values('1','1','0','99630ff411650cfa','大转盘','http://wx.eake.cn/tpl/Wap/default/common/css/guajiang/images/activity-lottery-start.jpg','幸运大转盘活动开始了','兑奖请联系我们，电话18987133915','你中奖了','1411660800','1411747200','亲，请点击进入大转盘抽奖活动页面，祝您好运哦！','如果设置只允许抽一次奖的，请写：你已经玩过了，下次再来。','幸运大转盘活动已经结束了','http://wx.eake.cn/tpl/Wap/default/common/css/guajiang/images/activity-lottery-end.jpg','亲，活动已经结束，请继续关注我们的后续活动哦。','天猫5000元购物卡','100','1','天猫5000元购物卡','1','50','0','天猫5000元购物卡','10','0','1','1','0','','','0','0','1','天猫5000元购物卡','10','0','天猫5000元购物卡','10','0','天猫5000元购物卡','10','0','','1','1','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'0','0','0','0');");
E_D("replace into `imicms_lottery` values('2','0','0','99630ff411650cfa','优惠券','http://wx.eake.cn/tpl/Wap/default/common/css/guajiang/images/activity-coupon-start.jpg','亚蓝微营销优惠券','','亚蓝微营销优惠券活动为和用户产生互动。','1413964352','1416556352','亚蓝微营销会不定期举办活动','得到优惠券的用户可以用来抵扣平台购买费用。','您好该活动已经结束！','http://wx.eake.cn/tpl/Wap/default/common/css/guajiang/images/activity-coupon-end.jpg','期待下次您的参与','VIP购买抵扣优惠券','1','0','VIP年费费抵扣','3','2','0','VIP月费抵扣','3','0','1000','1','0','','61091793','0','0','0','','0','0','','0','0','','0','0','http://wx.eake.cn/tpl/Wap/default/common/css/guajiang/images/activity-coupon-winning.jpg','1','1','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'0','0','0','0');");
E_D("replace into `imicms_lottery` values('3','0','0','99630ff411650cfa','刮刮卡','http://wx.eake.cn/tpl/User/default/common/images/img/activity-scratch-card-start.jpg','刮刮卡活动开始了','兑奖请联系我们，QQ:61091793','恭喜您中奖了','1413965076','1416557076','亲，请点击进入刮刮卡抽奖活动页面，祝您好运哦！','','刮刮卡活动已经结束了','http://wx.eake.cn/tpl/User/default/common/images/img/activity-scratch-card-end.jpg','亲，活动已经结束，请继续关注我们的后续活动哦。','VIP购买抵扣优惠券','1','0','VIP年费费抵扣','2','2','0','VIP月费抵扣','3','0','1000','1','0','','','0','0','1','','0','0','','0','0','','0','0','','1','1','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'0','0','0','0');");
E_D("replace into `imicms_lottery` values('4','0','0','99630ff411650cfa','水果达人','http://wx.eake.cn/tpl/static/luckyFruit/user/start.jpg','水果达人活动开始了','兑奖请联系我们，电话138********','恭喜您中奖了','1413965357','1416557357','亲，请点击进入水果达人活动页面，祝您好运哦！','亲，继续努力哦！','水果达人活动已经结束了','http://wx.eake.cn/tpl/static/luckyFruit/user/end.jpg','亲，活动已经结束，请继续关注我们的后续活动哦。','VIP购买抵扣优惠券','1','0','VIP年费费抵扣','4','2','0','VIP月费抵扣','3','0','1000','1','0','','','0','0','1','','0','0','','0','0','','0','0','','0','1','1',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'0','0','0','0');");
E_D("replace into `imicms_lottery` values('5','0','0','99630ff411650cfa','拆礼盒','','','','','0','0','','','','','','','0','0','','0','0','0','','0','0','0','0','0','','','0','0','0','','0','0','','0','0','','0','0','2','0','1','0',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'',NULL,'0','0','0','0');");

require("../../inc/footer.php");
?>