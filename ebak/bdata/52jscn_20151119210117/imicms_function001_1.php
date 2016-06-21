<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_function001`;");
E_C("CREATE TABLE `imicms_function001` (
  `id` int(11) NOT NULL auto_increment,
  `gid` tinyint(3) NOT NULL,
  `usenum` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `funname` varchar(100) NOT NULL,
  `info` varchar(100) NOT NULL,
  `isserve` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `gid` (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=172 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_function001` values('1','1','0','天气查询','tianqi','帮客户时时了解天气状况，方便出行。例：发送 北京天气','1','1');");
E_D("replace into `imicms_function001` values('2','1','0','糗事','qiushi','糗事　直接发送糗事','1','1');");
E_D("replace into `imicms_function001` values('3','1','0','计算器','jishuan','计算器使用方法　例：计算50-50　，计算100*100','1','1');");
E_D("replace into `imicms_function001` values('4','4','0','朗读','langdu','朗读＋关键词　例：朗读多用户营销系统','1','1');");
E_D("replace into `imicms_function001` values('5','1','0','健康指数查询','jiankang','健康指数查询　健康＋高，＋重　例：健康170,65','1','1');");
E_D("replace into `imicms_function001` values('6','1','0','快递查询','kuaidi','主流快递单号查询.例：快递顺丰117215889174','1','1');");
E_D("replace into `imicms_function001` values('7','1','0','笑话','xiaohua','客户随机调取经典笑话，放松心情，轻松一笑。直接发送笑话','1','1');");
E_D("replace into `imicms_function001` values('8','2','0','藏头诗','changtoushi',' 藏头诗+关键词　例：藏头诗我爱你','1','1');");
E_D("replace into `imicms_function001` values('9','1','0','陪聊','peiliao','聊天　直接输入聊天关键词即可','1','1');");
E_D("replace into `imicms_function001` values('10','1','0','小黄鸡','liaotian','直接输入聊天关键词即可 很黄很暴力','1','1');");
E_D("replace into `imicms_function001` values('11','2','0','周公解梦','mengjian','周公解梦　梦见+关键词　例如:梦见父母','1','1');");
E_D("replace into `imicms_function001` values('12','2','0','语音翻译','yuyinfanyi','翻译＋关键词 例：翻译你好','1','1');");
E_D("replace into `imicms_function001` values('13','2','0','火车查询','huoche','火车查询　火车＋城市＋目的地　例火车上海南京','1','1');");
E_D("replace into `imicms_function001` values('14','2','0','公交查询','gongjiao','公交＋城市＋公交编号　例：上海公交774','1','1');");
E_D("replace into `imicms_function001` values('15','1','0','身份证查询','shenfenzheng','身份证＋号码　　例：身份证342423198803015568','1','1');");
E_D("replace into `imicms_function001` values('16','1','0','手机归属地查询','shouji','手机归属地查询(吉凶 运势) 手机＋手机号码　例：手机13800138000','1','1');");
E_D("replace into `imicms_function001` values('17','3','0','音乐查询','yinle','音乐＋音乐名  例：音乐爱你一万年','1','1');");
E_D("replace into `imicms_function001` values('18','2','0','附近周边信息查询','fujin','附近周边信息查询(ＬＢＳ）','1','1');");
E_D("replace into `imicms_function001` values('75','5','0','酒店宾馆','hotel','微酒店模块，让您的公众号瞬间变成在线酒店，粉丝可以在微信上查看多家分店的信息，地理位置。','1','1');");
E_D("replace into `imicms_function001` values('76','5','0','微教育','school','','1','1');");
E_D("replace into `imicms_function001` values('20','4','0','淘宝店铺','taobao','输入淘宝＋关键词　即可访问淘宝3g手机店铺','2','1');");
E_D("replace into `imicms_function001` values('21','4','0','会员资料管理','userinfo','会员资料管理　输入会员　即可参与','2','1');");
E_D("replace into `imicms_function001` values('22','1','0','翻译','fanyi','翻译＋关键词 例：翻译你好','1','1');");
E_D("replace into `imicms_function001` values('23','4','0','第三方接口','api','第三方接口整合模块，请在管理平台下载接口文件并配置接口信息，','1','1');");
E_D("replace into `imicms_function001` values('24','1','0','姓名算命','suanming','姓名算命 算命＋关键词　例：算命李白','1','1');");
E_D("replace into `imicms_function001` values('25','1','0','百度百科','baike','百度百科　使用方法：百科＋关键词　例：百科北京','2','1');");
E_D("replace into `imicms_function001` values('26','2','0','彩票查询','caipiao','回复内容:彩票+彩种即可查询彩票中奖信息,例：彩票双色球','1','1');");
E_D("replace into `imicms_function001` values('70','5','0','摇一摇','shake','营销会议功能，主要用于4S店车展，房产看房团，会议营销等应用场景，活跃气氛，手机参与者的联系方式等。','1','1');");
E_D("replace into `imicms_function001` values('71','5','0','微论坛','forum','微社区是基于商家微信公众账号的社交平台，社区虽小，但商家互不相识的粉丝，可以在社区内进行互动，在互动中共同创造内容发生传播，并且支持图片、视频、文字、表情等方式，让交流无限。','1','1');");
E_D("replace into `imicms_function001` values('72','5','0','微医疗','medical','挂号、预约、查询医院详情。','1','1');");
E_D("replace into `imicms_function001` values('29','1','0','微网站','shouye','输入首页,访问微 网站','1','1');");
E_D("replace into `imicms_function001` values('30','3','0','DIY宣传页','adma','只需要填写二维码地址，简单介绍你公众号的特点，就可以自动生成一个漂亮的PC推广页面，方便你分享给好友，分享到微博，论坛，博客等等，起到好的推广的作用。','1','1');");
E_D("replace into `imicms_function001` values('31','3','0','会员卡','huiyuanka','尊贵享受vip会员卡,回复会员卡即可领取会员卡','1','1');");
E_D("replace into `imicms_function001` values('33','4','0','官网帐号审核','shenhe','官网帐号审核','1','1');");
E_D("replace into `imicms_function001` values('34','2','0','歌词查询','geci','歌词查询 回复歌词＋歌名即可查询一首歌曲的歌词,例：歌词醉清风','1','1');");
E_D("replace into `imicms_function001` values('59','4','0','刮刮卡','gua2','刮刮卡抽奖活动','1','1');");
E_D("replace into `imicms_function001` values('60','4','0','全景','panorama','','1','1');");
E_D("replace into `imicms_function001` values('61','4','0','婚庆喜帖','wcard','微信喜帖功能，专门针对婚庆行业定制。','2','1');");
E_D("replace into `imicms_function001` values('62','4','0','投票','vote','开启此功能,可以发起文本投票和图片投票两种形式，可单选和多选。','2','1');");
E_D("replace into `imicms_function001` values('63','5','0','房产','estate','预约看房、楼盘介绍、在线视频、全景看房等功能。','2','1');");
E_D("replace into `imicms_function001` values('64','4','0','3G相册','album','在微信上拥有绚丽的图片展示系统，适用于有丰富图片展现需要的账号。','1','1');");
E_D("replace into `imicms_function001` values('37','2','0','应用场景','scenes','','1','1');");
E_D("replace into `imicms_function001` values('67','4','0','留言板','messageboard','','2','1');");
E_D("replace into `imicms_function001` values('68','5','0','微汽车','car','预约试驾、预约保养、车主关怀、在售车型和在线客服功能。','2','1');");
E_D("replace into `imicms_function001` values('69','4','0','微信墙','wxq','用户发出的包含关键词的微信文字和图片，可以显示上墙，并实现在线抽奖功能。','1','1');");
E_D("replace into `imicms_function001` values('74','5','0','分享统计','share','完善的微信分享机制及分享数据统计功能。','1','1');");
E_D("replace into `imicms_function001` values('43','4','0','通用预订系统','host_kev','通用预订系统 可用于酒店预订，ktv订房，旅游预订等。','2','1');");
E_D("replace into `imicms_function001` values('65','4','0','砸金蛋','GoldenEgg','砸金蛋','2','1');");
E_D("replace into `imicms_function001` values('45','4','0','自定义表单','diyform','自定义表单(用于报名，预约,留言)等','1','1');");
E_D("replace into `imicms_function001` values('46','5','0','无线网络订餐','dx','无线网络订餐','1','1');");
E_D("replace into `imicms_function001` values('47','4','0','在线商城','shop','在线商城,购买系统','1','1');");
E_D("replace into `imicms_function001` values('48','4','0','在线团购系统','etuan','在线团购系统','1','1');");
E_D("replace into `imicms_function001` values('49','4','0','自定义菜单','diymen_set','通过底部菜单可以实现有效的导航和提醒作用。该功能仅限服务类帐号','1','1');");
E_D("replace into `imicms_function001` values('73','4','0','群发消息','groupmessage','使用群发接口，实现消息群发。','1','1');");
E_D("replace into `imicms_function001` values('66','4','0','水果机','LuckyFruit','','2','1');");
E_D("replace into `imicms_function001` values('57','4','0','幸运大转盘','choujiang','输入抽奖　即可参加幸运大转盘抽奖活动','2','1');");
E_D("replace into `imicms_function001` values('58','4','0','优惠券','Coupon','优惠券,输入优惠券即可参加优惠券活动','1','1');");
E_D("replace into `imicms_function001` values('78','2','0','附近图文','lbsNews','','2','1');");
E_D("replace into `imicms_function001` values('79','4','0','微场景','Live','','2','1');");
E_D("replace into `imicms_function001` values('80','2','0','域名whois查询','yuming','域名whois查询　回复域名 可查询网站备案信息,域名whois注册时间等等\r\n 例：imicms.com','1','1');");
E_D("replace into `imicms_function001` values('81','1','0','中秋吃月饼','Autumn','','1','1');");
E_D("replace into `imicms_function001` values('82','1','0','摁死小情侣游戏','Lovers','回复 “小情侣” 即可参加','1','1');");
E_D("replace into `imicms_function001` values('83','1','0','七夕走鹊桥','AppleGame','回复 “走鹊桥” 即可参与','1','1');");
E_D("replace into `imicms_function001` values('84','1','0','有奖调研','Research','','1','1');");
E_D("replace into `imicms_function001` values('85','1','0','一战到底','Problem','','1','1');");
E_D("replace into `imicms_function001` values('86','1','0','微信签到','Signin','','1','1');");
E_D("replace into `imicms_function001` values('87','1','0','现场活动','Scene','','1','1');");
E_D("replace into `imicms_function001` values('88','1','0','微商圈','Market','','1','1');");
E_D("replace into `imicms_function001` values('89','1','0','微预约','Custom','','1','1');");
E_D("replace into `imicms_function001` values('90','1','0','祝福贺卡','heka','','1','1');");
E_D("replace into `imicms_function001` values('91','1','0','微美容','beauty','','1','1');");
E_D("replace into `imicms_function001` values('92','1','0','微健身','fitness','','1','1');");
E_D("replace into `imicms_function001` values('93','1','0','微政务','gover','','1','1');");
E_D("replace into `imicms_function001` values('94','1','0','微食品','food','','1','1');");
E_D("replace into `imicms_function001` values('95','1','0','微旅游','travel','','1','1');");
E_D("replace into `imicms_function001` values('96','1','0','微花店','flower','','1','1');");
E_D("replace into `imicms_function001` values('97','1','0','微物业','property','','1','1');");
E_D("replace into `imicms_function001` values('98','1','0','微KTV','ktv','','1','1');");
E_D("replace into `imicms_function001` values('99','1','0','微酒吧','bar','','1','1');");
E_D("replace into `imicms_function001` values('100','1','0','微装修','fitment','','1','1');");
E_D("replace into `imicms_function001` values('101','1','0','微婚庆','buswedd','','1','1');");
E_D("replace into `imicms_function001` values('102','1','0','微宠物','affections','','1','1');");
E_D("replace into `imicms_function001` values('103','1','0','微家政','housekeeper','','1','1');");
E_D("replace into `imicms_function001` values('104','1','0','微租赁','lease','','1','1');");
E_D("replace into `imicms_function001` values('105','1','0','在线客服','Kefu','','1','1');");
E_D("replace into `imicms_function001` values('106','1','0','百度直达号','Zhida','','1','1');");
E_D("replace into `imicms_function001` values('107','1','0','微路由','rippleos','','1','1');");
E_D("replace into `imicms_function001` values('108','1','0','微名片','Vcard','','1','1');");
E_D("replace into `imicms_function001` values('109','1','0','快捷与版权','plugmenu_set','','1','1');");
E_D("replace into `imicms_function001` values('110','1','0','微游戏','Gamecenter','','1','1');");
E_D("replace into `imicms_function001` values('111','1','0','微游戏','Gamecenter','','1','1');");
E_D("replace into `imicms_function001` values('112','1','0','微信红包','Red_packet','','1','1');");
E_D("replace into `imicms_function001` values('113','1','0','惩罚台','Punish','','1','1');");
E_D("replace into `imicms_function001` values('114','1','0','邀请函','Invite','','1','1');");
E_D("replace into `imicms_function001` values('115','1','0','拆礼盒','Autumns','','1','1');");
E_D("replace into `imicms_function001` values('116','1','0','微调研','diaoyan','','1','1');");
E_D("replace into `imicms_function001` values('118','1','0','分享助力','Helping','','1','1');");
E_D("replace into `imicms_function001` values('119','1','0','人气冲榜','Popularity','','1','1');");
E_D("replace into `imicms_function001` values('120','1','0','幸运九宫格','Jiugong','','1','1');");
E_D("replace into `imicms_function001` values('121','1','0','微众筹','Crowdfunding','','1','1');");
E_D("replace into `imicms_function001` values('122','1','0','一元购','Unitary','','1','1');");
E_D("replace into `imicms_function001` values('123','1','0','全民经纪人','MicroBroker','全民经纪人','1','1');");
E_D("replace into `imicms_function001` values('124','1','0','微砍价','Bargain','一起来砍价','1','1');");
E_D("replace into `imicms_function001` values('125','1','0','微外卖','DishOut','新版外卖','1','1');");
E_D("replace into `imicms_function001` values('126','1','0','微信公众号','Weixin','','1','1');");
E_D("replace into `imicms_function001` values('127','1','0','合体红包','Hongbao','合体红包','1','1');");
E_D("replace into `imicms_function001` values('128','1','0','微秒杀','Seckill','微信秒杀活动，看谁手快','1','1');");
E_D("replace into `imicms_function001` values('129','1','0','微客服','Service','微信客服功能','1','1');");
E_D("replace into `imicms_function001` values('131','1','0','摇一摇 周边','Shakearound','','1','1');");
E_D("replace into `imicms_function001` values('132','1','0','降价拍','Cutprice','','1','1');");
E_D("replace into `imicms_function001` values('133','1','0','微排号','Numqueue','','1','1');");
E_D("replace into `imicms_function001` values('135','1','0','微店系统','Micrstore','','1','1');");
E_D("replace into `imicms_function001` values('136','0','0','谁是情圣','Sentiment','','0','1');");
E_D("replace into `imicms_function001` values('137','0','0','摇钱树','CoinTree','','0','1');");
E_D("replace into `imicms_function001` values('138','0','0','我要上头条','FrontPage','','0','1');");
E_D("replace into `imicms_function001` values('139','0','0','集字游戏','Collectword','','0','1');");
E_D("replace into `imicms_function001` values('140','1','0','高级场景','Eqx','','1','1');");
E_D("replace into `imicms_function001` values('141','2','0','高级模板','AdvanceTpl','','2','1');");
E_D("replace into `imicms_function001` values('142','4','0','群发消息','Message','','2','1');");
E_D("replace into `imicms_function001` values('143','4','0','公众小秘书','Paper','公众小秘书','2','1');");
E_D("replace into `imicms_function001` values('144','4','0','微招聘','Zhaopin','','2','1');");
E_D("replace into `imicms_function001` values('145','4','0','手机站','Phone','','2','1');");
E_D("replace into `imicms_function001` values('146','4','0','微场景','SeniorScene','','2','1');");
E_D("replace into `imicms_function001` values('147','4','0','支付宝服务窗','Fuwu','','2','1');");
E_D("replace into `imicms_function001` values('148','4','0','微贺卡','Card','','2','1');");
E_D("replace into `imicms_function001` values('149','4','0','图文投票','Voteimg','','2','1');");
E_D("replace into `imicms_function001` values('150','4','0','婚庆喜帖','wedding','','2','1');");
E_D("replace into `imicms_function001` values('151','4','0','人工客服','ServiceUser','','2','1');");
E_D("replace into `imicms_function001` values('152','4','0','微名片','Person_card','','2','1');");
E_D("replace into `imicms_function001` values('153','4','0','H5动态模板','CustomTmpls','','2','1');");
E_D("replace into `imicms_function001` values('154','4','0','微信现金红包','Cashbonus','','2','1');");
E_D("replace into `imicms_function001` values('155','4','0','人气红包','Hongbaoqiye','','2','1');");
E_D("replace into `imicms_function001` values('156','4','0','分享大奖赛','Dajiangsai','','2','1');");
E_D("replace into `imicms_function001` values('157','4','0','WEB站点','Website','','2','1');");
E_D("replace into `imicms_function001` values('158','4','0','PC站点','Web','','2','1');");
E_D("replace into `imicms_function001` values('159','4','0','云打包','Yundabao','','2','1');");
E_D("replace into `imicms_function001` values('160','4','0','点赚传媒','Weizhaohuan','','2','1');");
E_D("replace into `imicms_function001` values('161','4','0','微召唤','Paper','','2','1');");
E_D("replace into `imicms_function001` values('162','4','0','店员管理','Assistente','','2','1');");
E_D("replace into `imicms_function001` values('163','4','0','砍价广告传媒','Adkanjia','','2','1');");
E_D("replace into `imicms_function001` values('164','4','0','AA聚会报名','Aaactivity','','2','1');");
E_D("replace into `imicms_function001` values('165','4','0','微信生活圈','LivingCircle','','2','1');");
E_D("replace into `imicms_function001` values('166','4','0','趣味游戏','Test','','2','1');");
E_D("replace into `imicms_function001` values('167','4','0','朋友圈','Pengyouquanad','','2','1');");
E_D("replace into `imicms_function001` values('168','4','0','微拍卖','Auction','','2','1');");
E_D("replace into `imicms_function001` values('169','4','0','收银台','Cashier','','2','1');");
E_D("replace into `imicms_function001` values('170','4','0','摇一摇抽奖','ShakeLottery','','2','1');");
E_D("replace into `imicms_function001` values('171','4','0','粉丝红包','DirectHongbao','','2','1');");

require("../../inc/footer.php");
?>