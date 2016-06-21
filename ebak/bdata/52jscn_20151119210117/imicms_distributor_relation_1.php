<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_distributor_relation`;");
E_C("CREATE TABLE `imicms_distributor_relation` (
  `did` int(10) NOT NULL default '0' COMMENT '分销商id',
  `supdid` int(10) NOT NULL default '0' COMMENT '所属分销商id',
  `supdids` varchar(3000) NOT NULL default '0' COMMENT '上级分销商id列表',
  `level` int(5) NOT NULL default '1' COMMENT '级别',
  KEY `did` USING BTREE (`did`),
  KEY `supdid` USING BTREE (`supdid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分销商关系'");

require("../../inc/footer.php");
?>