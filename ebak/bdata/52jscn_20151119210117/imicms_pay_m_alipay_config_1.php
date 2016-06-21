<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_pay_m_alipay_config`;");
E_C("CREATE TABLE `imicms_pay_m_alipay_config` (
  `token` varchar(60) NOT NULL,
  `name` varchar(40) NOT NULL default '',
  `pid` varchar(40) NOT NULL default '',
  `key` varchar(60) NOT NULL default '',
  `open` tinyint(1) NOT NULL default '0',
  `type` tinyint(1) NOT NULL default '0',
  KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>