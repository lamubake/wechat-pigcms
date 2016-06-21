<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_busines`;");
E_C("CREATE TABLE `imicms_busines` (
  `bid` int(11) NOT NULL auto_increment,
  `token` varchar(50) NOT NULL default '',
  `keyword` varchar(50) NOT NULL default '',
  `mtitle` varchar(50) NOT NULL default '',
  `title` varchar(50) NOT NULL default '',
  `picurl` varchar(200) NOT NULL default '',
  `album_id` int(11) NOT NULL,
  `toppicurl` varchar(200) NOT NULL default '',
  `roompicurl` varchar(200) NOT NULL default '',
  `address` varchar(50) NOT NULL default '',
  `longitude` char(11) NOT NULL default '',
  `latitude` char(11) NOT NULL default '',
  `business_desc` text NOT NULL,
  `type` char(15) NOT NULL default '',
  `sort` int(11) NOT NULL,
  `blogo` varchar(200) NOT NULL,
  `businesphone` char(13) NOT NULL default '',
  `orderInfo` varchar(800) NOT NULL default '',
  `compyphone` char(12) NOT NULL default '',
  `path` varchar(3000) default '0',
  `tpid` int(11) default '36',
  `conttpid` int(11) default NULL,
  PRIMARY KEY  (`bid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_busines` values('1','257e21c8a9e1be8c','杰诚','杰诚家政有限公司','杰诚','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141003/257e21c8a9e1be8c2014100316561371265.jpg','1','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141003/257e21c8a9e1be8c2014100316125750698.jpg','http://wx.eake.cn/tpl/Static/kindeditors/attached/257e21c8a9e1be8c/image/20141003/257e21c8a9e1be8c2014100316125750698.jpg','安宁市金方街道办事处普河村委会大普河村55号','102.560977','24.892479','&lt;p style=&quot;color:#444444;font-family:arial, 微软雅黑;font-size:14px;background-color:#FFFFFF;&quot;&gt;\r\n	杰诚家政服务有限公司成立于2004年7月，公司致力于家政服务行业。公司旗下目前由：家政服务、保洁服务、园林绿化、花园景观工程、经济信息咨询、礼仪庆典服务、承办会议及商品展示。\r\n&lt;/p&gt;\r\n&lt;p style=&quot;color:#444444;font-family:arial, 微软雅黑;font-size:14px;background-color:#FFFFFF;&quot;&gt;\r\n	&amp;nbsp; &amp;nbsp; &amp;nbsp;&amp;nbsp;公司自成立以来，经过辛勤耕耘，我们的清洁管理服务多次在评比及检查过程中达标，并获得政府部门的好评及客户赞赏。杰诚家政服务有限公司自成立以来,在杰诚家政服务有限公司全体同仁的共同努力下,杰诚家政服务有限公司得到了长足的发展,并已拓展至昆明及安宁市周边地区,同时,&amp;nbsp;杰诚家政服务有限公司汇集了一批有才能,有干劲的年青人,建立了一个充满活力和朝气的管理团队,培养了一批高效,专业技术良好的清洁正规军,赢得了被服务单位的一致好评！杰诚家政服务有限公司全体员工本着“顾客至上，质量第一”的精神，竭诚为广大客户服务。&lt;br /&gt;\r\n以“ 服务铸就品牌，专业铸就权威”为经营理念，不断创新提高技术标准，扩大市场范围，将服务品牌推向更高的层次，使杰诚家政服务有限公司立足于同行业的不败之地，共同与中国清洁市场走向辉煌灿烂的未来！\r\n&lt;/p&gt;\r\n&lt;br /&gt;','housekeeper','1','','','杰诚家政网上订单。亲们可以通过给杰诚家政有限公司下订单了。','0871-6868129','0','127','36');");
E_D("replace into `imicms_busines` values('2','99630ff411650cfa','k','亚蓝微营销-KVT展示','亚蓝微营销-KVT展示','http://wx.eake.cn/uploads/9/99630ff411650cfa/5/3/0/5/thumb_542d3586a12e2.jpg','2','http://wx.eake.cn/uploads/9/99630ff411650cfa/3/d/9/5/thumb_545ba6ed29ee2.jpg','http://wx.eake.cn/uploads/9/99630ff411650cfa/f/5/9/9/thumb_545ba82805542.jpg','测试地址123','102.487316','24.920435','我们只为了展示给相关客户查看案列使用。所有此项目订单不作为消费使用，谢谢！','ktv','1','','','您现在看到是展示效果，不作为订单使用','087168651126','0','36','36');");

require("../../inc/footer.php");
?>