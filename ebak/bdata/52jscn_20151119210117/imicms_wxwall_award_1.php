<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_wxwall_award`;");
E_C("CREATE TABLE `imicms_wxwall_award` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `wxq_id` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_wxwall_award` values('2','3','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','1415351445','1');");
E_D("replace into `imicms_wxwall_award` values('3','3','oQqMOt_BsAHx6kGaCUhj3_xd5904','1415351456','1');");
E_D("replace into `imicms_wxwall_award` values('4','3','oQqMOt2PCOj2QkFLW-sjjQbvG1U0','1415352065','1');");
E_D("replace into `imicms_wxwall_award` values('5','3','oQqMOt_BsAHx6kGaCUhj3_xd5904','1415352071','1');");

require("../../inc/footer.php");
?>