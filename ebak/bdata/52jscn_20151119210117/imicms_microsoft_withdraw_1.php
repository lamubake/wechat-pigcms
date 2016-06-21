<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_microsoft_withdraw`;");
E_C("CREATE TABLE `imicms_microsoft_withdraw` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `imicms_id` int(11) NOT NULL,
  `store_id` int(11) NOT NULL,
  `opening_bank` varchar(100) NOT NULL default '' COMMENT '开户行',
  `bank_card` varchar(100) NOT NULL default '' COMMENT '卡号',
  `bank_card_user` varchar(100) NOT NULL default '' COMMENT '开户名',
  `withdrawal_type` tinyint(1) NOT NULL,
  `add_time` int(11) NOT NULL,
  `status` char(30) NOT NULL default '',
  `amount` float(6,2) NOT NULL,
  `complate_time` int(11) NOT NULL,
  `bank` char(30) NOT NULL,
  `tel` char(30) NOT NULL,
  `nickname` varchar(100) NOT NULL default '' COMMENT '昵称',
  `store` varchar(100) NOT NULL default '',
  `user` varchar(100) NOT NULL default '',
  `token` char(30) NOT NULL,
  `is_show` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>