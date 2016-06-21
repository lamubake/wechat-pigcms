<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_distributor_receipt`;");
E_C("CREATE TABLE `imicms_distributor_receipt` (
  `id` int(10) NOT NULL auto_increment,
  `did` int(10) NOT NULL default '0' COMMENT '分销商id',
  `amount` decimal(8,2) NOT NULL default '0.00' COMMENT '金额',
  `paidtime` varchar(20) NOT NULL default '0' COMMENT '打款时间',
  `status` char(1) NOT NULL default '0' COMMENT '状态 0 已打款 , 1 已收款',
  `receipttime` varchar(20) NOT NULL default '0' COMMENT '收款时间',
  `time` varchar(20) NOT NULL default '0' COMMENT '申请提现时间',
  PRIMARY KEY  (`id`),
  KEY `did` USING BTREE (`did`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商收款'");

require("../../inc/footer.php");
?>