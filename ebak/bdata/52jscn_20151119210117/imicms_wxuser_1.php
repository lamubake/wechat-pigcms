<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wxuser`;");
E_C("CREATE TABLE `imicms_wxuser` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `wxname` varchar(60) NOT NULL COMMENT '公众号名称',
  `winxintype` smallint(2) NOT NULL default '1',
  `appid` varchar(50) NOT NULL default '',
  `appsecret` varchar(50) NOT NULL default '',
  `wxid` varchar(20) NOT NULL COMMENT '公众号原始ID',
  `weixin` char(20) NOT NULL COMMENT '微信号',
  `headerpic` char(255) NOT NULL COMMENT '头像地址',
  `token` char(255) NOT NULL,
  `province` varchar(30) NOT NULL COMMENT '省',
  `city` varchar(60) NOT NULL COMMENT '市',
  `areaid` varchar(50) NOT NULL,
  `qq` char(25) NOT NULL COMMENT '公众号邮箱',
  `wxfans` int(11) NOT NULL COMMENT '微信粉丝',
  `typeid` int(11) NOT NULL COMMENT '分类ID',
  `typename` varchar(90) NOT NULL default '0' COMMENT '分类名',
  `tongji` text NOT NULL,
  `allcardnum` int(11) NOT NULL,
  `cardisok` int(11) NOT NULL,
  `yetcardnum` int(11) NOT NULL,
  `totalcardnum` int(11) NOT NULL,
  `createtime` varchar(13) NOT NULL,
  `tpltypeid` int(10) NOT NULL,
  `updatetime` varchar(13) NOT NULL,
  `tpltypename` varchar(20) NOT NULL COMMENT '首页模版名',
  `tpllistid` int(10) NOT NULL,
  `tpllistname` varchar(20) NOT NULL COMMENT '列表模版名',
  `tplcontentid` int(10) NOT NULL,
  `menuid` int(10) NOT NULL,
  `tplcontentname` varchar(20) NOT NULL COMMENT '内容模版名',
  `copyright` varchar(255) NOT NULL default '亚蓝信息技术有限公司技术支持',
  `namecolor` varchar(20) NOT NULL default '#B2B789',
  `copyid` int(10) NOT NULL,
  `smtp_plat_status` int(1) NOT NULL default '0' COMMENT '邮件提醒|0为关闭|1为开启',
  `smtp_plat_host` varchar(60) NOT NULL COMMENT '邮件服务器地址',
  `smtp_plat_port` int(5) NOT NULL default '25' COMMENT '邮件服务器端口',
  `smtp_plat_send` varchar(60) NOT NULL COMMENT '邮件发送帐号',
  `smtp_plat_pass` varchar(60) NOT NULL COMMENT '邮件发送密码',
  `smtp_plat_reply` varchar(60) NOT NULL COMMENT '邮件回复地址',
  `smtp_plat_ssl` int(1) unsigned NOT NULL default '0' COMMENT '是否加密连接|0为不加密|1为加密',
  `smtp_plat_order_feed` int(1) NOT NULL default '0' COMMENT '下单后邮件通知',
  `smtp_plat_pay_feed` int(1) NOT NULL default '0' COMMENT '付款后邮件通知',
  `sms_plat_status` int(1) NOT NULL default '0' COMMENT '短信提醒|0为关闭|1为开启',
  `sms_plat_reply` varchar(60) NOT NULL COMMENT '用于接收订单的手机号',
  `sms_plat_user` varchar(60) NOT NULL COMMENT '短信平台帐号',
  `sms_plat_pass` varchar(60) NOT NULL COMMENT '短信平台密码',
  `sms_plat_order_feed` int(1) NOT NULL default '0' COMMENT '下单后短信通知',
  `sms_plat_pay_feed` int(1) NOT NULL default '0' COMMENT '付款后短信通知',
  `shoptpltypeid` int(11) NOT NULL default '1',
  `shoptpltypename` varchar(20) default '101_index_wis2a',
  `routerid` varchar(50) NOT NULL default '',
  `pigsecret` varchar(150) NOT NULL default '',
  `transfer_customer_service` tinyint(1) NOT NULL default '0',
  `color_id` int(2) NOT NULL,
  `smsstatus` int(1) default '0',
  `phone` varchar(20) default NULL,
  `smsuser` varchar(20) default NULL,
  `smspassword` varchar(20) default NULL,
  `emailstatus` int(1) default '0',
  `email` varchar(100) default NULL,
  `emailuser` varchar(20) default NULL,
  `emailpassword` varchar(20) default NULL,
  `printstatus` int(1) default '0',
  `member_code` varchar(50) default NULL,
  `feiyin_key` varchar(50) default NULL,
  `device_no` varchar(30) default NULL,
  `agentid` int(10) NOT NULL default '0',
  `openphotoprint` tinyint(1) NOT NULL default '0',
  `freephotocount` mediumint(4) NOT NULL default '3',
  `oauth` tinyint(1) NOT NULL default '0',
  `aeskey` varchar(45) NOT NULL default '',
  `encode` tinyint(1) NOT NULL default '0',
  `ifbiz` tinyint(1) default '0',
  `fuwuappid` char(20) default NULL,
  `oauthinfo` tinyint(1) NOT NULL default '1',
  `share_ticket` varchar(150) NOT NULL,
  `share_dated` char(15) NOT NULL,
  `authorizer_access_token` varchar(200) NOT NULL,
  `authorizer_refresh_token` varchar(200) NOT NULL,
  `authorizer_expires` char(10) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `web_access_token` varchar(200) NOT NULL COMMENT ' 网页授权token',
  `web_refresh_token` varchar(200) NOT NULL,
  `web_expires` char(10) NOT NULL,
  `wx_coupons` tinyint(4) NOT NULL,
  `card_ticket` char(120) NOT NULL,
  `wx_liaotian` tinyint(4) NOT NULL,
  `card_expires` char(15) NOT NULL,
  `qr` varchar(1000) NOT NULL,
  `dynamicTmpls` tinyint(1) NOT NULL default '0',
  `sub_notice` varchar(255) default NULL,
  `need_phone_notice` varchar(255) default NULL,
  `sub_notice_btn` varchar(60) default NULL,
  `eqx` varchar(5) NOT NULL,
  `eqxpassword` varchar(32) NOT NULL,
  `guanhuai` int(10) NOT NULL,
  `title1` varchar(255) NOT NULL,
  `title2` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `uid_2` (`uid`),
  KEY `agentid` USING BTREE (`agentid`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_wxuser` values('1','1','亚蓝网公众服务','1','wxbd7cf9b1d6970a54','b40932be7ca4762cb9e45ec7dde16c7b','gh_56dc11cd5df9','eakecn','http://wx.eake.cn/tpl/Static/kindeditors/attached/99630ff411650cfa/image/20140912/99630ff411650cfa2014091214262498428.jpg','99630ff411650cfa','云南省','昆明市','安宁市','4115037@qq.com','17','0','','','10000','0','10000','10000','1409736179','40','1423721635','140_index_qesf','1','yl_list','1','0','ktv_content','由亚蓝提供技术支持','#B2B789','0','1','smtp.qq.com','25','eakecn','www.eake.org.cn','eakecn@qq.com','0','0','0','0','','','','0','0','1','101_index_wis2a','','','1','2','0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0','0','3','0','','0','0',NULL,'1','sM4AOVdWfPE4DxkXGEs8VJOzi6EkFesjLI9bwDGr5vgb24uvRaEFjziMXi97dRtzlBPeiUYjS_xhxHmgLewgPw','1436244134','','','','0','','','','0','','0','','','0',NULL,NULL,NULL,'1','c5db244dbfc91ca5dc46626cc21d5594','0','','','','');");
E_D("replace into `imicms_wxuser` values('2','3','昆明非凡灯改','1','','','gh_c98518f7cf2f','KUNMINGFEIFA','http://wx.eake.cn/tpl/Static/kindeditors/attached/image/20140923/2014092320570434864.jpg','0766c9e6242e738e','云南省','昆明市','市辖区','583713996@qq.com','0','0','','','0','0','0','0','1411477557','1','1411480027','ty_index','1','yl_list','1','0','ktv_content','由亚蓝提供技术支持','#B2B789','0','0','','25','','','','0','0','0','0','','','','0','0','1','101_index_wis2a','','','0','0','0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0','0','3','0','','0','0',NULL,'1','','','','','','0','','','','0','','0','','','0',NULL,NULL,NULL,'','','0','','','','');");
E_D("replace into `imicms_wxuser` values('4','6','杰诚家政服务有限公司','2','wxabe07624b4c3afa3','b575cc3e180e28e07643386eec9f07b4','gh_61326fdec3ba','j2334066415','http://wx.eake.cn/tpl/Static/kindeditors/attached/image/20140929/2014092917151422403.jpg','257e21c8a9e1be8c','云南省','昆明市','安宁市','1457963911@qq.com','100','0','','','10000','0','0','0','1411983456','327','1412043408','1327_index_n4fb','1','yl_list','1','0','ktv_content','由亚蓝提供技术支持','#B2B789','0','1','smtp.qq.com','110','cyangkun@qq.com','y13888384509','1457963911@qq.com ','0','1','1','0','','','','0','0','1','101_index_wis2a','','','0','0','0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0','0','3','0','','0','0',NULL,'1','bxLdikRXVbTPdHSM05e5u0RAPxbj0X73i1bHqIMmF2PmCwsX4Z9HiHIImZruSCsFnWPWuEg11RsQRZd0Uj9PkQ','1436234744','','','','0','','','','0','','0','','','0',NULL,NULL,NULL,'','','0','','','','');");
E_D("replace into `imicms_wxuser` values('9','15','雁鹰美体','1','','','gh_b76e3525ae30','yuanzimeiti','http://wx.eake.cn/tpl/Static/kindeditors/attached/image/20141207/2014120721315040215.jpg','2a94af5381fcc932','云南省','昆明市','安宁市','695546574@qq.com','5','0','','','0','0','0','0','1417959127','230','1432385423','1230_index_n4fb','1','yl_list','1','0','ktv_content','亚蓝信息技术有限公司技术支持','#B2B789','0','0','','25','','','','0','0','0','0','','','','0','0','1','101_index_wis2a','','','0','0','0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0','0','3','0','pvdKIX2TrOvi9WSsLcxkWIVPoZYROZcqa8j9k3NyQ1q','0','0',NULL,'1','','','','','','0','','','','0','','0','','','0',NULL,NULL,NULL,'','','0','','','','');");
E_D("replace into `imicms_wxuser` values('8','14','杰诚生活 ','1','','','gh_a9c20f405190','cyangkun','http://wx.eake.cn/tpl/Static/kindeditors/attached/image/20141119/2014111914150391540.jpg','c4448ac95e30a1eb','云南省','昆明市','安宁市','cyangkun@qq.com','39','7','生活','','0','0','0','0','1416377705','111','1416377705','1111_index_cveg','1','yl_list','1','0','ktv_content','亚蓝信息技术有限公司技术支持','#B2B789','0','0','','25','','','','0','0','0','0','','','','0','0','1','101_index_wis2a','','','0','0','0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0','0','3','0','','0','0',NULL,'1','','','','','','0','','','','0','','0','','','0',NULL,NULL,NULL,'','','0','','','','');");
E_D("replace into `imicms_wxuser` values('10','16','房贷放松的','1','','','gh_40cf14c948b4','骚的','http://wx.eake.cn/tpl/static/kindeditors/attached/image/20150628/2015062822202425147.jpg','c6402a3e1bd9cf46','北京市','市辖区','东城区','123456@qq.com','123','8','服务','','0','0','0','0','1435501242','1','1435501242','ty_index','1','yl_list','1','0','ktv_content','亚蓝信息技术有限公司技术支持','#B2B789','0','0','','25','','','','0','0','0','0','','','','0','0','1','101_index_wis2a','','','0','0','0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0','0','3','0','','0','0',NULL,'1','','','','','','0','','','','0','','0','','','0',NULL,NULL,NULL,'','','0','','','','');");
E_D("replace into `imicms_wxuser` values('11','17','虹贝儿摄影','1','','','gh_a6d67cd453d4','h15025106511','http://wx.eake.cn/tpl/static/kindeditors/attached/image/20150902/2015090210190970232.jpg','1c5990460d702b81','云南省','昆明市','安宁市','2224245092@qq.com','50','8','服务','','0','0','0','0','1441160352','258','1441160352','1258_index_n4fb','1','yl_list','1','0','ktv_content','亚蓝信息技术有限公司技术支持','#B2B789','0','0','','25','','','','0','0','0','0','','','','0','0','1','101_index_wis2a','','','0','0','0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0','0','3','0','','0','0',NULL,'1','','','','','','0','','','','0','','0','','','0',NULL,NULL,NULL,'','','0','','','','');");
E_D("replace into `imicms_wxuser` values('12','18','kakaka','1','','','kakaka','kakaka','http://wx.eake.cn/tpl/static/kindeditors/attached/image/20151026/2015102622575029451.jpg','a30137baf3b46e82','北京市','市辖区','东城区','kakaka@qq.com','100','8','服务','','0','0','0','0','1445871481','1','1445871481','ty_index','1','yl_list','1','0','ktv_content','亚蓝信息技术有限公司技术支持','#B2B789','0','0','','25','','','','0','0','0','0','','','','0','0','1','101_index_wis2a','','','0','0','0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0','0','3','0','','0','0',NULL,'1','','','','','','0','','','','0','','0','','','0',NULL,NULL,NULL,'','','0','','','','');");
E_D("replace into `imicms_wxuser` values('13','19','kaka','1','','','kaka','kaka','http://wx.eake.cn/tpl/static/kindeditors/attached/image/20151029/2015102921565474528.jpg','a06c39b267a5efae','北京市','市辖区','东城区','kakaka@qq.com','88','8','服务','','0','0','0','0','1446127018','1','1446127018','ty_index','1','yl_list','1','0','ktv_content','亚蓝信息技术有限公司技术支持','#B2B789','0','0','','25','','','','0','0','0','0','','','','0','0','1','101_index_wis2a','','','0','0','0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0','0','3','0','','0','0',NULL,'1','','','','','','0','','','','0','','0','','','0',NULL,NULL,NULL,'','','0','','','','');");
E_D("replace into `imicms_wxuser` values('14','23','52jscn','1','52jscn','52jscn','52jscn','52jscn','./tpl/User/default/common/images/portrait.jpg','kaiqpo1447853601','p','c','','1447853601@yourdomain.com','0','8','','','0','0','0','0','1447853609','0','1447853609','','1','yl_list','1','0','ktv_content','亚蓝信息技术有限公司技术支持','#B2B789','0','0','','25','','','','0','0','0','0','','','','0','0','1','101_index_wis2a','','54wwo1sojye7oQEtfLdt','0','0','0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0',NULL,NULL,NULL,'0','0','3','0','WJUkiUxmVWPTIIOMxCaHyQOXuEJShXHuMnQWeUVlNuw','0','0',NULL,'1','','','','','','0','','','','0','','0','','http://weixinmishu.host6.52jscn.com/uploads/a/admin/1/d/a/7/thumb_5645e531be892.jpg','69',NULL,NULL,NULL,'','','0','','','','');");

require("../../inc/footer.php");
?>