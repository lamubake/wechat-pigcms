<?php
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 2010
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `imicms_user_group`;");
E_C("CREATE TABLE `imicms_user_group` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(30) NOT NULL,
  `diynum` int(11) NOT NULL,
  `connectnum` int(11) NOT NULL,
  `iscopyright` tinyint(1) NOT NULL,
  `activitynum` int(3) NOT NULL,
  `gongzhongnum` tinyint(2) NOT NULL,
  `price` int(11) NOT NULL,
  `statistics_user` int(11) NOT NULL,
  `create_card_num` int(4) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `taxisid` int(10) NOT NULL default '0',
  `wechat_card_num` tinyint(4) NOT NULL,
  `func` varchar(3000) default NULL,
  `agentid` int(10) NOT NULL default '0',
  `access_count` int(10) unsigned NOT NULL default '0',
  `access_count_notice` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `agentid` USING BTREE (`agentid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8");
E_D("replace into `imicms_user_group` values('1','vip0','2000','2000','1','0','1','100','0','10','1','1','1','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin','0','0','');");
E_D("replace into `imicms_user_group` values('2','VIP1','3000','3000','1','0','1','50','0','50','1','2','2','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,hotel,school,taobao,userinfo,fanyi,api,suanming,baike,caipiao,shake,forum,medical,shouye,adma,huiyuanka,shenhe,geci,gua2,panorama,wcard,vote,estate,album,scenes,messageboard,car,share,host_kev,diyform,groupmessage,Coupon,lbsNews,Market,Custom,heka,beauty,fitness,gover,food,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Kefu,Zhida,rippleos,Vcard,plugmenu_set,Gamecenter,Collectword','0','0','');");
E_D("replace into `imicms_user_group` values('3','vip2','4000','4000','0','0','1','1000','0','80','1','3','3','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,hotel,school,taobao,userinfo,fanyi,api,suanming,baike,caipiao,shake,forum,medical,shouye,adma,huiyuanka,shenhe,geci,gua2,panorama,wcard,vote,estate,album,scenes,messageboard,car,share,host_kev,GoldenEgg,diyform,dx,shop,Coupon,lbsNews,Live,yuming,Market,Custom,heka,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Kefu,Zhida,rippleos,Vcard,plugmenu_set,Gamecenter,Gamecenter,Red_packet,Punish,Website,Aaactivity','0','0','');");
E_D("replace into `imicms_user_group` values('4','vip3','5000','5000','0','1','1','1500','0','100','1','4','3','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,hotel,school,taobao,userinfo,fanyi,api,suanming,baike,caipiao,shake,forum,medical,shouye,adma,huiyuanka,shenhe,geci,gua2,panorama,wcard,vote,estate,album,scenes,messageboard,car,wxq,share,host_kev,GoldenEgg,diyform,dx,shop,etuan,diymen_set,groupmessage,LuckyFruit,choujiang,Coupon,lbsNews,Live,yuming,Autumn,Lovers,AppleGame,Research,Problem,Signin,Scene,Market,Custom,heka,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Kefu,Zhida,rippleos,Vcard,plugmenu_set,Gamecenter,Gamecenter,Red_packet,Punish,Invite,Autumns,diaoyan,Helping,Popularity,Jiugong,Service,Eqx,Message,Paper,Zhaopin,Card,Voteimg,wedding,ServiceUser,Person_card,Website,Web,Yundabao,Weizhaohuan,Paper,Assistente,Aaactivity,LivingCircle,Test,Pengyouquanad,Auction,ShakeLottery,DirectHongbao','0','0','');");
E_D("replace into `imicms_user_group` values('5','vip4','6000','6000','0','2','1','2980','0','100','1','5','4','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,hotel,school,taobao,userinfo,fanyi,api,suanming,baike,caipiao,shake,forum,medical,shouye,adma,huiyuanka,shenhe,geci,gua2,panorama,wcard,vote,estate,album,scenes,messageboard,car,share,host_kev,GoldenEgg,diyform,dx,shop,etuan,diymen_set,groupmessage,LuckyFruit,choujiang,Coupon,lbsNews,Live,yuming,Autumn,Lovers,AppleGame,Research,Problem,Signin,Scene,Market,Custom,heka,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Kefu,Zhida,rippleos,Vcard,plugmenu_set,Gamecenter,Gamecenter,Red_packet,Punish,Invite,Autumns,diaoyan,Helping,Popularity,Jiugong,Crowdfunding,Unitary,MicroBroker,Bargain,DishOut,Weixin,Hongbao,Seckill,Service,Shakearound,Numqueue,Micrstore,Sentiment,CoinTree,FrontPage,Collectword,Eqx,AdvanceTpl,Message,Paper,Zhaopin,Phone,SeniorScene,Fuwu,Card,Voteimg,wedding,ServiceUser,Person_card,Hongbaoqiye,Website,Web,Aaactivity,Test,Pengyouquanad,Auction,Cashier,ShakeLottery,DirectHongbao','0','0','');");
E_D("replace into `imicms_user_group` values('6','vip5','7000','90000','0','5','1','3980','0','1000000','1','6','4','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,hotel,school,taobao,userinfo,fanyi,api,suanming,baike,caipiao,shake,forum,medical,shouye,adma,huiyuanka,shenhe,geci,gua2,panorama,wcard,vote,estate,album,scenes,messageboard,car,share,host_kev,GoldenEgg,diyform,dx,shop,etuan,diymen_set,groupmessage,LuckyFruit,choujiang,Coupon,lbsNews,Live,yuming,Autumn,Lovers,AppleGame,Research,Problem,Signin,Scene,Market,Custom,heka,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Kefu,Zhida,rippleos,Vcard,plugmenu_set,Gamecenter,Gamecenter,Red_packet,Punish,Invite,Autumns,diaoyan,Helping,Popularity,Jiugong,Crowdfunding,Unitary,MicroBroker,Bargain,DishOut,Weixin,Hongbao,Seckill,Service,Shakearound,Cutprice,Numqueue,Sentiment,CoinTree,Collectword,Eqx,AdvanceTpl,Message,Paper,Zhaopin,Phone,SeniorScene,Fuwu,Card,Voteimg,wedding,ServiceUser,Person_card,CustomTmpls,Cashbonus,Hongbaoqiye,Dajiangsai,Website,Web,Yundabao,Weizhaohuan,Paper,Assistente,Adkanjia,Aaactivity,LivingCircle,Test,Pengyouquanad,Cashier,ShakeLottery,DirectHongbao','0','0','');");
E_D("replace into `imicms_user_group` values('7','vip6','100000','200000','0','10','1','100000','0','10000','1','7','5','tianqi,qiushi,jishuan,langdu,jiankang,kuaidi,xiaohua,changtoushi,peiliao,liaotian,mengjian,yuyinfanyi,huoche,gongjiao,shenfenzheng,shouji,yinle,fujin,hotel,school,taobao,userinfo,fanyi,api,suanming,baike,caipiao,shake,forum,medical,shouye,adma,huiyuanka,shenhe,geci,gua2,panorama,wcard,vote,estate,album,scenes,messageboard,car,wxq,share,host_kev,GoldenEgg,diyform,dx,shop,etuan,diymen_set,groupmessage,LuckyFruit,choujiang,Coupon,lbsNews,Live,yuming,Autumn,Lovers,AppleGame,Research,Problem,Signin,Scene,Market,Custom,heka,beauty,fitness,gover,food,travel,flower,property,ktv,bar,fitment,buswedd,affections,housekeeper,lease,Kefu,Zhida,rippleos,Vcard,plugmenu_set,Gamecenter,Gamecenter,Red_packet,Punish,Invite,Autumns,diaoyan,Helping,Popularity,Jiugong,Crowdfunding,Unitary,MicroBroker,Bargain,DishOut,Weixin,Hongbao,Seckill,Service,Shakearound,Cutprice,Numqueue,Micrstore,Sentiment,CoinTree,FrontPage,Collectword,Eqx,AdvanceTpl,Message,Paper,Zhaopin,Phone,SeniorScene,Fuwu,Card,Voteimg,wedding,ServiceUser,Person_card,CustomTmpls,Cashbonus,Hongbaoqiye,Dajiangsai,Website,Web,Yundabao,Weizhaohuan,Paper,Assistente,Adkanjia,Aaactivity,LivingCircle,Test,Pengyouquanad,Auction,Cashier,ShakeLottery,DirectHongbao','0','0','');");

require("../../inc/footer.php");
?>