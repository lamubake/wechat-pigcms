<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_busines_main`;");
E_C("CREATE TABLE `imicms_busines_main` (
  `mid` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL,
  `bid_id` int(11) NOT NULL,
  `name` char(50) NOT NULL,
  `sort` int(11) NOT NULL,
  `main_desc` text NOT NULL,
  `type` char(15) NOT NULL,
  `telphone` char(12) NOT NULL default '',
  `maddress` varchar(50) NOT NULL default '',
  `desc_pic` varchar(200) NOT NULL,
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_busines_main` values('1','257e21c8a9e1be8c','1','普河村店','1','&lt;p style=&quot;color:#444444;font-family:arial, 微软雅黑;font-size:14px;background-color:#FFFFFF;&quot;&gt;\r\n	杰诚家政服务有限公司成立于2004年7月，公司致力于家政服务行业。公司旗下目前由：家政服务、保洁服务、园林绿化、花园景观工程、经济信息咨询、礼仪庆典服务、承办会议及商品展示。\r\n&lt;/p&gt;\r\n&lt;p style=&quot;color:#444444;font-family:arial, 微软雅黑;font-size:14px;background-color:#FFFFFF;&quot;&gt;\r\n	&amp;nbsp; &amp;nbsp; &amp;nbsp;&amp;nbsp;公司自成立以来，经过辛勤耕耘，我们的清洁管理服务多次在评比及检查过程中达标，并获得政府部门的好评及客户赞赏。杰诚家政服务有限公司自成立以来,在杰诚家政服务全体同仁的共同努力下,杰诚家政服务有限公司得到了长足的发展,并已拓展至昆明周边地区,同时,&amp;nbsp;杰诚家政服务有限公司汇集了一批有才能,有干劲的年青人,建立了一个充满活力和朝气的管理团队,培养了一批高效,专业技术良好的清洁正规军,赢得了被服务单位的一致好评！杰诚家政服务有限公司全体员工本着“顾客至上，质量第一”的精神，竭诚为广大客户服务。&lt;br /&gt;\r\n以“ 服务铸就品牌，专业铸就权威”为经营理念，不断创新提高技术标准，扩大市场范围，将服务品牌推向更高的层次，使杰诚家政服务有限公司立足于同行业的不败之地，共同与中国清洁市场走向辉煌灿烂的未来！\r\n&lt;/p&gt;\r\n&lt;br /&gt;','housekeeper','13888384509','','');");

require("../../inc/footer.php");
?>