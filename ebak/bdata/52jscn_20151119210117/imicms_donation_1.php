<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_donation`;");
E_C("CREATE TABLE `imicms_donation` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `token` varchar(80) NOT NULL,
  `name` varchar(30) NOT NULL,
  `note` varchar(300) NOT NULL COMMENT '介绍',
  `content` text NOT NULL COMMENT '详情',
  `company` varchar(200) NOT NULL COMMENT '捐款接受机构',
  `fixed_money1` smallint(5) unsigned NOT NULL default '20' COMMENT '固定捐款金额',
  `share_content1` varchar(90) NOT NULL COMMENT '众筹宣传语范例1',
  `share_content2` varchar(90) NOT NULL COMMENT '众筹宣传语范例2',
  `creattime` int(10) unsigned NOT NULL COMMENT '创建时间',
  `share_num` int(10) unsigned NOT NULL COMMENT '分享次数',
  `keyword` varchar(20) NOT NULL COMMENT '关键词',
  `reply_title` varchar(50) NOT NULL COMMENT '回复标题',
  `reply_content` varchar(200) NOT NULL COMMENT '回复内容',
  `reply_pic` varchar(260) NOT NULL,
  `pic` varchar(260) NOT NULL,
  `fixed_money2` smallint(5) unsigned NOT NULL default '50',
  `fixed_money3` smallint(5) unsigned NOT NULL default '100',
  `fixed_money4` smallint(5) unsigned NOT NULL default '200',
  `status` tinyint(4) NOT NULL default '1' COMMENT '状态（0：关闭，1：正常）',
  `starttime` int(10) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(10) unsigned NOT NULL COMMENT '结束时间',
  `logo` varchar(300) NOT NULL,
  `tip` varchar(15) NOT NULL COMMENT '募捐提示语',
  `share_pic` varchar(200) NOT NULL COMMENT '分享图片',
  `is_delete` tinyint(1) NOT NULL default '0' COMMENT '是否可以删除（0：可以，1：不可以）',
  `account` varchar(30) NOT NULL COMMENT '款项去向',
  PRIMARY KEY  (`id`),
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>