<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_shakelottery_prize`;");
E_C("CREATE TABLE `imicms_shakelottery_prize` (
  `id` int(11) NOT NULL auto_increment,
  `aid` int(11) NOT NULL,
  `prizename` varchar(50) NOT NULL default '',
  `prizeimg` varchar(255) NOT NULL default '',
  `prizenum` int(11) NOT NULL,
  `expendnum` int(11) NOT NULL default '0',
  `provider` varchar(100) NOT NULL default '',
  `token` varchar(30) NOT NULL default '',
  `prize_type` tinyint(1) NOT NULL,
  `hongbao_configure` varchar(800) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>