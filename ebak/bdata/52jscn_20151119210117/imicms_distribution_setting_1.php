<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_distribution_setting`;");
E_C("CREATE TABLE `imicms_distribution_setting` (
  `id` int(10) NOT NULL auto_increment,
  `commission_type` varchar(10) NOT NULL default '' COMMENT '佣金类型 fixed 固定 float 百分比',
  `commission` decimal(8,2) NOT NULL default '0.00' COMMENT '佣金',
  `multi_distribution` char(1) NOT NULL default '0' COMMENT '多级分销 0, 1',
  `upgrade_channel_commission` decimal(8,2) NOT NULL default '0.00' COMMENT '升级渠道商奖励',
  `token` varchar(50) NOT NULL default '' COMMENT '主站唯一标识',
  `agreement` text NOT NULL COMMENT '加盟协议',
  `is_check` char(1) NOT NULL default '0' COMMENT '分销申请是否要审核',
  `level_max` int(3) NOT NULL default '0' COMMENT '最大分销级别',
  `paid_day` tinyint(3) default '2' COMMENT '佣金支付处理（工作日）',
  `ad_img` varchar(200) NOT NULL default '' COMMENT '分销引导广告（图片）',
  `home_banner_img` varchar(200) NOT NULL default '' COMMENT '分销店铺首页banner图片',
  `logo` varchar(200) NOT NULL default '' COMMENT '分销店铺logo图片',
  `allow_distribution` char(1) NOT NULL default '0' COMMENT '是否允许分销 0不允许 0允许',
  PRIMARY KEY  (`id`),
  KEY `token` USING BTREE (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销配置'");

require("../../inc/footer.php");
?>