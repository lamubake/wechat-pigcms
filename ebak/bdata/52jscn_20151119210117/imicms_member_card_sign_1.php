<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_member_card_sign`;");
E_C("CREATE TABLE `imicms_member_card_sign` (
  `id` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `wecha_id` varchar(50) NOT NULL,
  `sign_time` int(11) NOT NULL,
  `is_sign` int(11) NOT NULL,
  `expense` int(11) NOT NULL,
  `score_type` int(11) NOT NULL COMMENT '1为签到2为消费3为人为变更',
  `sell_expense` int(11) NOT NULL,
  `changepoints` int(11) NOT NULL COMMENT '积分变更数量',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>