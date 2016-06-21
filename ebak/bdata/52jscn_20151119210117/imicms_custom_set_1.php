<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_custom_set`;");
E_C("CREATE TABLE `imicms_custom_set` (
  `set_id` int(10) unsigned NOT NULL auto_increment,
  `title` char(30) NOT NULL,
  `keyword` char(25) NOT NULL,
  `intro` varchar(100) NOT NULL,
  `addtime` char(10) NOT NULL,
  `top_pic` char(100) NOT NULL,
  `succ_info` char(35) NOT NULL,
  `err_info` char(35) NOT NULL,
  `detail` text NOT NULL,
  `limit_id` int(11) NOT NULL,
  `token` char(20) NOT NULL,
  `tel` char(20) NOT NULL,
  `address` char(80) NOT NULL,
  `longitude` char(20) NOT NULL,
  `latitude` char(20) NOT NULL,
  PRIMARY KEY  (`set_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_custom_set` values('2','家政服务','预约','杰诚家政服务有限公司成立于2014年7月，公司致力于家政服务行业。公司旗下目前由：家政服务、保洁服务、园林绿化、花园景观工程、经济信息咨询、礼仪庆典服务、承办会议及商品展示。','1412326093','http://wx.eake.cn/uploads/2/257e21c8a9e1be8c/c/f/5/6/thumb_542e624aadc55.jpg','您的订单已成功','对不起，老板没起床','&lt;p&gt;\r\n	&lt;img src=&quot;file://c:/users/administrator/appdata/roaming/360se6/User Data/temp/257e21c8a9e1be8c2014100316151944768.jpg&quot; /&gt;&lt;img src=&quot;file://c:/users/administrator/appdata/roaming/360se6/User Data/temp/257e21c8a9e1be8c2014100316151944768.jpg&quot; /&gt; \r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n	联系电话：0871-68681294\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;br /&gt;\r\n&lt;/p&gt;\r\n&lt;p style=&quot;color:#444444;font-family:arial, 微软雅黑;font-size:14px;background-color:#FFFFFF;&quot;&gt;\r\n	移动电话：13888384509 &amp;nbsp; &amp;nbsp; 15198862744\r\n&lt;/p&gt;\r\n&lt;p style=&quot;color:#444444;font-family:arial, 微软雅黑;font-size:14px;background-color:#FFFFFF;&quot;&gt;\r\n	Email：2334066415@qq.com&amp;nbsp;&amp;nbsp;联系QQ：2334066415\r\n&lt;/p&gt;\r\n&lt;p style=&quot;color:#444444;font-family:arial, 微软雅黑;font-size:14px;background-color:#FFFFFF;&quot;&gt;\r\n	联系地址：安宁市金方街道办事处普河村委会大普河村55号\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n	&lt;br /&gt;\r\n&lt;/p&gt;','2','257e21c8a9e1be8c','0871-68681294','安宁市金方街道办事处普河村委会大谱河村55号','102.560618','24.892086');");
E_D("replace into `imicms_custom_set` values('3','杰成家政在线预约','预约','杰诚家政服务有限公司成立于2004年7月，公司致力于家政服务行业。公司旗下目前由：家政服务、保洁服务、园林绿化、花园景观工程、经济信息咨询、礼仪庆典服务、承办会议及商品展示。','1416383118','http://wx.eake.cn/uploads/c/c4448ac95e30a1eb/7/e/0/d/thumb_546c43072ff57.jpg','您的预约提交成功！稍后客户联系您','您的预约提交失败，请重新提交','&lt;p&gt;\r\n	杰诚家政服务有限公司成立于2004年7月，公司致力于家政服务行业。公司目前主要开展以下业务：家政服务、保洁服务、园林绿化、花园景观工程、经济信息咨询、礼仪庆典服务、承办会议及商品展览展示活动。\r\n&lt;/p&gt;\r\n&lt;p&gt;\r\n	&amp;nbsp; &amp;nbsp; &amp;nbsp;&amp;nbsp;&lt;span style=&quot;font-family:Verdana, Arial, Tahoma;line-height:30px;background-color:#FFFFFF;&quot;&gt;公司自成立以来，&lt;span style=&quot;color:#333333;font-family:Arial, Verdana, sans-serif, 宋体;line-height:22px;&quot;&gt;经过辛勤耕耘，我们的清洁管理服务多次在评比及检查过程中达标，并获得政府部门的好评及客户赞赏。杰诚家政服务有限公司自成立以来,上下一心共同努力,杰诚家政服务有限公司得到了长足的发展,并已拓展至安宁，昆钢，太平新城及周边地区,同时,&amp;nbsp;杰诚家政服务有限公司汇集了一批有才能,有干劲的年青人,建立了一个充满活力和朝气的管理团队,培养了一批工作高效,专业技术良好的清洁队伍,赢得了被服务单位的一致好评！杰诚家政服务有限公司全体员工本着“顾客至上，质量第一”的精神，竭诚为广大客户服务。&lt;/span&gt;&lt;br /&gt;\r\n&lt;span style=&quot;color:#333333;font-family:Arial, Verdana, sans-serif, 宋体;line-height:22px;&quot;&gt;以“ 服务铸就品牌，专业铸就权威”为经营理念，不断创新提高技术标准，扩大市场范围，将服务品牌推向更高的层次，使杰诚家政服务有限公司，共同与中国清洁市场走向辉煌灿烂的明天！&lt;/span&gt;&lt;/span&gt; \r\n&lt;/p&gt;','4','c4448ac95e30a1eb','13888384509','安宁市连然镇太极山18号（爱善幼儿园对面）','102.489697','24.91964');");
E_D("replace into `imicms_custom_set` values('4','拍照预约','预约','虹贝尔摄影是昆钢最专业的儿童摄影工作室','1441170887','http://wx.eake.cn/uploads/1/1c5990460d702b81/0/d/c/8/thumb_55e661268583b.jpg','提交成功','','','5','1c5990460d702b81','15025106511','昆钢建设街','102.497432','24.893832');");
E_D("replace into `imicms_custom_set` values('5','电脑版网上预约','电脑版网上预约','','1447872304','','提交成功','提交失败','电脑版网上预约，删除此表单将可能导致电脑版网站不完整。','6','kaiqpo1447853601','','电脑版网上预约','0','0');");

require("../../inc/footer.php");
?>