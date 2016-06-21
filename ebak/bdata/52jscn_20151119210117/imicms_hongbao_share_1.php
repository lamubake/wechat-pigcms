<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_hongbao_share`;");
E_C("CREATE TABLE `imicms_hongbao_share` (
  `share_id` int(11) NOT NULL auto_increment,
  `hongbao_id` int(11) NOT NULL COMMENT '红包id',
  `add_money` float(6,2) unsigned NOT NULL COMMENT '为合体者贡献的金额',
  `share_key` varchar(50) NOT NULL COMMENT '分享code',
  `share_nickname` varchar(50) default '' COMMENT '分享者昵称',
  `share_pic` varchar(255) default '' COMMENT '分享者头像',
  `is_opened` tinyint(4) default '0' COMMENT '是否进入分享页',
  `share_time` int(11) default '0' COMMENT '分享时间',
  `share_wechaid` varchar(50) default '' COMMENT '分享者openid',
  PRIMARY KEY  (`share_id`),
  KEY `hongbao_id` USING BTREE (`hongbao_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>