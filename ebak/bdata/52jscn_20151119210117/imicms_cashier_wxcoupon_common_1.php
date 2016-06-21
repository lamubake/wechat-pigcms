<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_cashier_wxcoupon_common`;");
E_C("CREATE TABLE `imicms_cashier_wxcoupon_common` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `mid` int(10) unsigned NOT NULL,
  `logurl` varchar(250) NOT NULL,
  `mname` varchar(100) NOT NULL COMMENT '商户名字',
  `wxlogurl` varchar(250) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>