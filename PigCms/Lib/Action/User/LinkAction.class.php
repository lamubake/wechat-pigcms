<?php
class LinkAction extends UserAction{
	public $where;
	public $modules;
	public $arr;
	public function _initialize() {
		parent::_initialize();
		$this->where=array('token'=>$this->token);
		$this->modules=array(
		'Home'=>'首页',
		'Classify'=>'网站分类',
		'Img'=>'图文回复',
		'Company'=>'LBS信息',
		'Live'=>'微场景',
		//'Adma'=>'DIY宣传页',
		'Photo'=>'相册',
		'Eqx'=>'高级场景（Eqx）', //增加
		'Weizhaohuan'=>'微召唤',  //增加
		'Storenew'=>'分销商城',   //增加
		'Jingpai'=>'商城竞拍',   //增加
		'Selfform'=>'万能表单',
		'Host'=>'通用订单',
		'Groupon'=>'团购',
		'Shop'=>'商城',
		'Repast'=>'订餐',
		'Wedding'=>'婚庆喜帖',
		'Vote'=>'投票',
		'Panorama'=>'全景',
		'Lottery'=>'大转盘',
		'Guajiang'=>'刮刮卡',
		'Coupon'=>'优惠券',
		'MemberCard'=>'会员卡',
		'Estate'=>'微房产',
		'Message'=>'留言板',
		'Car'=>'微汽车',
		'GoldenEgg'=>'砸金蛋',
		'LuckyFruit'=>'水果机',
		'AppleGame'=>'走鹊桥',
		'Lovers'=>'摁死情侣',
		'Autumn'=>'吃月饼',
		'Problem'=>'一战到底',
		'Forum'=>'论坛',
		//'GreetingCard'=>'贺卡',
		'Medical'=>'微医疗',
		'School'=>'微教育',
		'Hotels'=>'酒店宾馆',
		'Business'=>'行业应用',
		'Market'=>'微商圈',
		'Research'=>'微调研',
		'Fansign'=>'微信签到',
		'OutsideLink'=>'<font color="red">生活服务</font>',
		);


		if(C('server_topdomain')!='pigcms.cn'){
			//demo settings
			$this->arr = array('game','Invite','Red_packet', 'Seckill','Autumns','Popularity','Helping','Jiugong','MicroBroker','Crowdfunding',"Unitary","DishOut","LivingCircle","Test","Bargain","Service","Hongbao","Micrstore","SeniorScene","SeniorCard","ServiceChat","Voteimg","Cutprice","Person_card","Numqueue","Punish","CoinTree","Sentiment","FrontPage","Auction","Collectword","ShakeLottery",'Weizhaohuan');

		}else{
			if(!is_file(RUNTIME_PATH.'function_library.php')){
				updateSync::sync_function_library();
			}
			$this->arr = require(RUNTIME_PATH.'function_library.php');
		}
		
	}
	
	/**
	 * 2015-05-27 lixiang
	 * 获取中文首写字母
	 * @param String $string
	 * @return string|Ambigous <string>
	 */
	private function _getFirstCharter($string) {
		if (empty($string)) {
			return '';
		}		
		$fchar = ord($string{0});		
		if ($fchar >= ord('A') && $fchar <= ord('z')) {
			return strtoupper($string{0});
		}		
		$s1 = iconv ( 'UTF-8', 'gb2312', $string );		
		$s2 = iconv ( 'gb2312', 'UTF-8', $s1 );		
		$s = $s2 == $string ? $s1 : $string;		
		$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
		$defined = array('12080'=>'H');
		if (!empty($defined[abs($asc)])) {
			return $defined[abs($asc)];
		}
		if ($asc >= -20319 && $asc <= -20284) return 'A';		
		if ($asc >= -20283 && $asc <= -19776) return 'B';		
		if ($asc >= -19775 && $asc <= -19219) return 'C';		
		if ($asc >= -19218 && $asc <= -18711) return 'D';		
		if ($asc >= -18710 && $asc <= -18527) return 'E';		
		if ($asc >= -18526 && $asc <= -18240) return 'F';		
		if ($asc >= -18239 && $asc <= -17923) return 'G';		
		if ($asc >= -17922 && $asc <= -17418) return 'H';		
		if ($asc >= -17417 && $asc <= -16475) return 'J';		
		if ($asc >= -16474 && $asc <= -16213) return 'K';		
		if ($asc >= -16212 && $asc <= -15641) return 'L';		
		if ($asc >= -15640 && $asc <= -15166) return 'M';		
		if ($asc >= -15165 && $asc <= -14923) return 'N';		
		if ($asc >= -14922 && $asc <= -14915) return 'O';		
		if ($asc >= -14914 && $asc <= -14631) return 'P';		
		if ($asc >= -14630 && $asc <= -14150) return 'Q';		
		if ($asc >= -14149 && $asc <= -14091) return 'R';		
		if ($asc >= -14090 && $asc <= -13319) return 'S';		
		if ($asc >= -13318 && $asc <= -12839) return 'T';		
		if ($asc >= -12838 && $asc <= -12557) return 'W';		
		if ($asc >= -12556 && $asc <= -11848) return 'X';		
		if ($asc >= -11847 && $asc <= -11056) return 'Y';		
		if ($asc >= -11055 && $asc <= -10247) return 'Z';
		$undefined = array('8460'=>'E'); //自定义
		return empty($undefined[abs($asc)]) ? '#' : $undefined[abs($asc)];
	}
	
	/**
	 * 2015-05-27 lixiang
	 * 分组排序，计算每列的数量
	 * @param Array $modules
	 * @return Array
	 */
	public function _getModules($modules) {
		$key = array();
		foreach ($modules as $value) {
			$en =  $this->_getFirstCharter(strip_tags($value['name']));
			$array[$en.'000'] = $en;
			$num = str_pad(++$key[$en], 3, '0', STR_PAD_LEFT);
			$array[$en.$num] = $value;				
		}
		// count($key) 
		$count =  count($array);
		for ($i = 3; $i > 0; $i--) {
			$group[$i] = ceil($count / $i);
			$count = $count - $group[$i];
		}
		rsort($group);
		ksort($array);
		$i = 1; $k=0; $en = 0;
		foreach ($array as $key => $value) {
			$count = $group[$k];
			$i++;
			$result[$k][$key] = $value;
			if ($i > $count) {
				$k++;
				$i=1;
			}
		}
		return $result;
	}
	
	public function insert(){
		if ($_GET['iskeyword']){
			$modules=$this->keywordModules();
		}else {
			$modules=$this->modules();
		}
		$this->assign('modules', $this->_getModules($modules));
		$this->display();
	}
	public function keywordModules(){
		$school=M('School_set_index')->where(array('token'=>$this->token))->find();
		$t=array(
		array('module'=>'Home','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>'微站首页','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Home'],'askeyword'=>1),
		array('module'=>'Storenew','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Storenew&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Storenew'],'canselected'=>1,'linkurl'=>'','keyword'=>'分销商城','askeyword'=>1), //分销商城
		array('module'=>'Jingpai','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Storenew&a=jingpai&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Jingpai'],'canselected'=>1,'linkurl'=>'','keyword'=>'商城竞拍','askeyword'=>1),// 商城竞拍
		array('module'=>'Eqx','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Eqx&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Eqx'],'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),// 高级场景用这个
		array('module'=>'Weizhaohuan','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Weizhaohuan&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Weizhaohuan'],'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),// 微召唤
		array('module'=>'Img','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=content&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Img'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Company','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Company&a=map&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Company'],'canselected'=>1,'linkurl'=>'','keyword'=>'地图','askeyword'=>1),
		array('module'=>'Photo','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Photo&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Photo'],'canselected'=>1,'linkurl'=>'','keyword'=>'相册','askeyword'=>1),
		array('module'=>'Live','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Live&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Live'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Selfform','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Selfform&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Selfform'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Host','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Host&a=detail&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Host'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Groupon','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Groupon&a=grouponIndex&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Groupon'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'团购','askeyword'=>1),
		array('module'=>'Shop','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Store&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Shop'],'canselected'=>1,'linkurl'=>'','keyword'=>'商城','askeyword'=>1),
		array('module'=>'Repast','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Repast&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Repast'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'订餐','askeyword'=>1),
		array('module'=>'Wedding','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Wedding&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Wedding'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Vote','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Vote&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Vote'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Panorama','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Panorama&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Panorama'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>$this->modules['Panorama'],'askeyword'=>1),
		array('module'=>'Lottery','linkcode'=>'','name'=>$this->modules['Lottery'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Guajiang','linkcode'=>'','name'=>$this->modules['Guajiang'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Coupon','linkcode'=>'','name'=>$this->modules['Coupon'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'AppleGame','linkcode'=>'','name'=>$this->modules['AppleGame'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Lovers','linkcode'=>'','name'=>$this->modules['Lovers'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Autumn','linkcode'=>'','name'=>$this->modules['Autumn'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Problem','linkcode'=>'','name'=>$this->modules['Problem'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'MemberCard','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Card&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['MemberCard'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'会员卡','askeyword'=>1),
		array('module'=>'Estate','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Estate'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Message','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Reply&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Message'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'留言','askeyword'=>1),
		array('module'=>'Car','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Car&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Car'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'汽车','askeyword'=>1),
		array('module'=>'GoldenEgg','linkcode'=>'','name'=>$this->modules['GoldenEgg'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'LuckyFruit','linkcode'=>'','name'=>$this->modules['LuckyFruit'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Forum','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Forum&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Forum'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'论坛','askeyword'=>1),
		array('module'=>'Hotels','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Hotels&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>'酒店宾馆','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'酒店','askeyword'=>1),
		array('module'=>'School','linkcode'=>'','name'=>$this->modules['School'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$school['keyword'],'askeyword'=>1),
		//array('module'=>'GreetingCard','linkcode'=>'','name'=>$this->modules['GreetingCard'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Business','linkcode'=>'','name'=>$this->modules['Business'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		//array('module'=>'Donation','linkcode'=>'','name'=>$this->modules['Donation'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		
		array('module'=>'Market','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Market&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Market'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'微商圈','askeyword'=>1),
		array('module'=>'Research','linkcode'=>'','name'=>$this->modules['Research'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Fansign','linkcode'=>'','name'=>'微信签到','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'微信签到','askeyword'=>1),
		);
		
		if (C('server_topdomain')=='pigcms.cn'){
			unset($t[4]);
		}
		
		//
		$sub=isset($_GET['sub'])?intval($_GET['sub']):0;
		foreach ($this->arr as $ka){
			$className='FunctionLibrary_'.$ka;
			if (class_exists($className)){
				$classInstance=new $className($this->token,$sub);
				$o=$classInstance->index();
				$canselected=$o['keyword']?1:0;
				$s=array('module'=>$ka,'linkcode'=>'','name'=>$o['name'],'sub'=>$o['subkeywords'],'canselected'=>$canselected,'linkurl'=>'?g=User&m=Link&a=commondetail&module='.$ka.'&iskeyword=1','keyword'=>$o['keyword'],'askeyword'=>1);
				array_push($t,$s);
			}
		}
		return $this->_filterModule($t);
	}
	
	private function _filterModule($modules) {
		if (1 == $_GET['auth']) {
			return $modules;
		}
		$array = require(APP_PATH.'Lib/ORG/FuncToModel.php');
		$group = M('UserGroup')->where(array('id'=>$_SESSION['gid']))->find();
		$models = explode(',', $group['func']);
		$function = M('Function')->field(array('funname'))->select();
		foreach ($function as $value) {
			$fun[] = $array[$value['funname']] ? $array[$value['funname']] : $value['funname'];
		}
		foreach ($models as $key => $model) {
			$models[$key] = $array[$model] ? $array[$model] : $model;
		}
		foreach ($modules as $key => $module) {
			$result = array_uintersect($models, array($module['module']), 'strcasecmp');
			if (empty($result)) {
				if (in_array($module['module'], $fun)) {
					unset($modules[$key]);
				}
			}
		}
		return $modules;
	}
	
	public function commondetail(){
		$sub=isset($_GET['sub'])?intval($_GET['sub']):1;
		$className='FunctionLibrary_'.$this->_get('module');
		if (class_exists($className)){
			$classInstance=new $className($this->token,$sub);
			$o=$classInstance->index();
			/*
			$canselected=$o['keyword']?1:0;
			$s=array('module'=>$ka,'linkcode'=>'','name'=>$o['name'],'sub'=>$o['subkeywords'],'canselected'=>$canselected,'linkurl'=>'?g=User&m=Link&a=commondetail&module='.$ka.'&iskeyword=1','keyword'=>$o['keyword'],'askeyword'=>1);
			*/
			
			$this->assign('moduleName',$o['name']);
			$fromitems=intval($_GET['iskeyword'])?$o['subkeywords']:$o['sublinks'];
			$items=array();
			if ($fromitems){
				$i=0;
				foreach ($fromitems as $item){
					array_push($items,array('id'=>$i,'name'=>$item['name'],'linkcode'=>$item['link'],'linkurl'=>'','keyword'=>$item['keyword']));
				}
			}
		}
		
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function modules(){
		$t=array(
		array('module'=>'Storenew','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Storenew&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Storenew'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'分销商城','askeyword'=>1),//分销
		array('module'=>'Jingpai','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Storenew&a=Jingpai&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Jingpai'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'商城竞拍','askeyword'=>1),//竞拍		
		array('module'=>'Weizhaohuan','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Weizhaohuan&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Weizhaohuan'],'sub'=>1,'canselected'=>1,'linkurl'=>'{siteUrl}/index.php?g=Wap&m=Weizhaohuan&a=index&token='.$this->token.'&wecha_id={wechat_id}','keyword'=>'','askeyword'=>1),// 微召唤
		array('module'=>'Home','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>'微站首页','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Home'],'askeyword'=>1),
		array('module'=>'Classify','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=lists&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Classify'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>0),
		array('module'=>'Img','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=content&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Img'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Company','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Company&a=map&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Company'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'地图','askeyword'=>1),
		//array('module'=>'Adma','linkcode'=>'{siteUrl}/index.php/show/'.$this->token,'name'=>$this->modules['Adma'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>0),
		array('module'=>'Photo','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Photo&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Photo'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'相册','askeyword'=>1),
		array('module'=>'Live','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Live&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Live'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Selfform','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Custom&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Selfform'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Host','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Host&a=detail&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Host'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Groupon','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Groupon&a=grouponIndex&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Groupon'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'团购','askeyword'=>1),
		array('module'=>'Shop','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Store&a=select&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Shop'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'商城','askeyword'=>1),
		array('module'=>'ShopCats','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Store&a=select&token='.$this->token.'&wecha_id={wechat_id}','name'=>'商城分类','sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'商城','askeyword'=>0),
		array('module'=>'Repast','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Repast&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Repast'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'订餐','askeyword'=>1),
		array('module'=>'Wedding','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Wedding&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Wedding'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Vote','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Vote&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Vote'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Panorama','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Panorama&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Panorama'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>$this->modules['Panorama'],'askeyword'=>1),
		array('module'=>'Lottery','linkcode'=>'','name'=>$this->modules['Lottery'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Guajiang','linkcode'=>'','name'=>$this->modules['Guajiang'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Coupon','linkcode'=>'','name'=>$this->modules['Coupon'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'AppleGame','linkcode'=>'','name'=>$this->modules['AppleGame'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Lovers','linkcode'=>'','name'=>$this->modules['Lovers'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Autumn','linkcode'=>'','name'=>$this->modules['Autumn'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Problem','linkcode'=>'','name'=>$this->modules['Problem'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'MemberCard','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Card&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['MemberCard'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'会员卡','askeyword'=>1),
		array('module'=>'Estate','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Estate'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'微房产','askeyword'=>0),
		array('module'=>'Message','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Reply&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Message'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'留言','askeyword'=>1),
		array('module'=>'Car','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Car&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Car'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Medical','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Medical&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Medical'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'微医疗','askeyword'=>0),
		array('module'=>'School','linkcode'=>'{siteUrl}/index.php?g=Wap&m=School&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['School'],'sub'=>1,'canselected'=>1,'linkurl'=>'','keyword'=>'微医疗','askeyword'=>0),
		array('module'=>'GoldenEgg','linkcode'=>'','name'=>$this->modules['GoldenEgg'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'LuckyFruit','linkcode'=>'','name'=>$this->modules['LuckyFruit'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Forum','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Forum&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Forum'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'论坛','askeyword'=>1),
		//array('module'=>'GreetingCard','linkcode'=>'','name'=>$this->modules['GreetingCard'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Hotels','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Hotels&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>'酒店宾馆','sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'酒店','askeyword'=>1),
		array('module'=>'Business','linkcode'=>'','name'=>$this->modules['Business'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>1),
		array('module'=>'Market','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Market&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Market'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>0),
		array('module'=>'Research','linkcode'=>'','name'=>$this->modules['Research'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>0),
		array('module'=>'Fansign','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Fanssign&a=index&token='.$this->token.'&wecha_id={wechat_id}','name'=>$this->modules['Fansign'],'sub'=>0,'canselected'=>1,'linkurl'=>'','keyword'=>'','askeyword'=>0),
		array('module'=>'OutsideLink','linkcode'=>'','name'=>$this->modules['OutsideLink'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>0),
		//array('module'=>'Donation','linkcode'=>'','name'=>$this->modules['Donation'],'sub'=>1,'canselected'=>0,'linkurl'=>'','keyword'=>'','askeyword'=>0),
		
		);
/* 		if (C('server_topdomain')=='pigcms.cn'){
			unset($t[6]);
		} */
		$sub=isset($_GET['sub'])?intval($_GET['sub']):0;
		foreach ($this->arr as $ka){
			$className='FunctionLibrary_'.$ka;
			if (class_exists($className)){
				$classInstance=new $className($this->token,$sub);
				$o=$classInstance->index();
				$canselected=$o['link']?1:0;
				$s=array('module'=>$ka,'linkcode'=>$o['link'],'name'=>$o['name'],'sub'=>$o['sublinks'],'canselected'=>$canselected,'linkurl'=>'?g=User&m=Link&a=commondetail&module='.$ka.'&iskeyword=0','keyword'=>$o['keyword'],'askeyword'=>0);
				array_push($t,$s);
			}
		}
		
		return $this->_filterModule($t);
	}
	//新增加EQX
	public function Eqx(){
		$moduleName='Eqx';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('eqx_info');
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=User&m=Eqx&a=index&token='.$this->token.'&wecha_id={wechat_id}&reid='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	//新增微召唤
	public function Weizhaohuan(){
		$moduleName='Weizhaohuan';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Weizhaohuan');
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Weizhaohuan&a=index&token='.$this->token.'&wecha_id={wechat_id}&reid='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	//分销商城
	public function Storenew(){
		$moduleName='Storenew';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('New_product');
		$where=array('display'=>1,'token'=>$this->token);
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		//http://cms.xingboke.com/index.php?g=Wap&m=Store&a=cats&token=yicms&wecha_id=oLA6VjlHpnWSNuak_YchHaCUCMwg&cid=6
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Storenew&a=product&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'].'&cid='.$item['cid'],'linkurl'=>'','keyword'=>'分销商城'));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	//竞拍商城
	public function Jingpai(){
		$moduleName='Jingpai';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('New_product_jingpai');
		$where=array('display'=>1,'token'=>$this->token);
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		//http://cms.xingboke.com/index.php?g=Wap&m=Store&a=cats&token=yicms&wecha_id=oLA6VjlHpnWSNuak_YchHaCUCMwg&cid=6
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Storenew&a=biddingproduct&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'].'&cid='.$item['cid'],'linkurl'=>'','keyword'=>'商城竞拍'));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	
	public function Classify(){
		$pid = (int)$_GET['pid'];
		$this->assign('moduleName',$this->modules['Classify']);
		$db=M('Classify');
		$where=$this->where;
		

		if($pid != NULL){
			$where['fid'] = $pid;
			$count      = $db->where($where)->count();
			$Page       = new Page($count,10);
			$show       = $Page->show();
			$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		}else{
			$where['fid'] = 0;
			$count      = $db->where($where)->count();
			$Page       = new Page($count,10);
			$show       = $Page->show();
			
			$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		}
		
		$items=array();
		if ($list){
			foreach ($list as $k=>$item){
				$fid = $item['id'];
				
				array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'sublink'=>'?g=User&m=Link&a=Classify&iskeyword=0&pid='.$item['id'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=lists&token='.$this->token.'&wecha_id={wechat_id}&classid='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword'],'sub'=>$db->where(array('token'=>$this->token,'fid'=>$fid))->field('id,name')->select()));
		
			}
		}
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Img(){
		$this->assign('moduleName',$this->modules['Img']);
		$db=M('Img');
		$ssimg = $this->_post('ssimg','htmlspecialchars,trim');
		if($ssimg){
			$where['title'] = array('like','%'.$ssimg.'%');
		}
		$where['token']=$this->token;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=content&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('img',1);
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Live(){
		$this->assign('moduleName',$this->modules['Live']);
		$db=M('Live');
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Live&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Company(){
		$this->assign('moduleName',$this->modules['Company']);
		$db=M('Company');
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Company&a=map&token='.$this->token.'&wecha_id={wechat_id}&companyid='.$item['id'],'linkurl'=>'','keyword'=>'地图'));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Photo(){
		$this->assign('moduleName',$this->modules['Photo']);
		$db=M('Photo');
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Photo&a=plist&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>'相册'));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Selfform(){
		$this->assign('moduleName',$this->modules['Selfform']);
		$db=M('Custom_set');
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('set_id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['set_id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Custom&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['set_id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Host(){
		$moduleName='Host';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M($moduleName);
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Host&a=index&token='.$this->token.'&wecha_id={wechat_id}&hid='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Panorama(){
		$this->assign('moduleName',$this->modules['Panorama']);
		$db=M('Panorama');
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('time DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Panorama&a=item&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Wedding(){
		$moduleName='Wedding';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M($moduleName);
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Wedding&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Lottery(){
		$moduleName='Lottery';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M($moduleName);
		$where=$this->where;
		$where['type']=1;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Lottery&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function AppleGame(){
		$moduleName='AppleGame';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Lottery');
		$where=$this->where;
		$where['type']=7;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=AppleGame&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Lovers(){
		$moduleName='Lovers';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Lottery');
		$where=$this->where;
		$where['type']=8;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Lovers&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Problem(){
		$moduleName='Problem';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Problem_game');
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Problem&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Guajiang(){
		$moduleName='Guajiang';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Lottery');
		$where=$this->where;
		$where['type']=2;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Guajiang&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Coupon(){
		$moduleName='Coupon';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Lottery');
		$where=$this->where;
		$where['type']=3;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Coupon&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function LuckyFruit(){
		$moduleName='LuckyFruit';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Lottery');
		$where=$this->where;
		$where['type']=4;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=LuckyFruit&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function GoldenEgg(){
		$moduleName='GoldenEgg';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Lottery');
		$where=$this->where;
		$where['type']=5;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=GoldenEgg&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Research(){
		$moduleName='Research';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Research');
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Research&a=index&token='.$this->token.'&wecha_id={wechat_id}&reid='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function GreetingCard(){
		$moduleName='GreetingCard';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('greeting_card');
		$where=$this->where;
		//$where['type']=5;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Greeting_card&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Vote(){
		$moduleName='Vote';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M($moduleName);
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Vote&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Shop(){
		$moduleName='Shop';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Company');
		$where=array('display'=>1,'token'=>$this->token);
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		//http://cms.xingboke.com/index.php?g=Wap&m=Store&a=cats&token=yicms&wecha_id=oLA6VjlHpnWSNuak_YchHaCUCMwg&cid=6
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Store&a=cats&token='.$this->token.'&wecha_id={wechat_id}&cid='.$item['id'],'linkurl'=>'','keyword'=>'商城'));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function ShopCats(){
		$moduleName='Shop';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Product_cat');
		$where=array('dining'=>0,'token'=>$this->token);
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		//http://cms.xingboke.com/index.php?g=Wap&m=Store&a=cats&token=yicms&wecha_id=oLA6VjlHpnWSNuak_YchHaCUCMwg&cid=6
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				if ($item['isfinal'] == 1) {
					array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Store&a=products&token='.$this->token.'&wecha_id={wechat_id}&catid='.$item['id'],'linkurl'=>'','keyword'=>'商城'));
				} else {
					array_push($items,array('id'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Store&a=cats&token='.$this->token.'&wecha_id={wechat_id}&cid='.$item['cid'] . '&parentid='.$item['id'],'linkurl'=>'','keyword'=>'商城'));
				}
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Estate(){
		$moduleName='Estate';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$Estates=M('Estate')->where($this->where)->select();
		//
		$items=array();
		if ($Estates){
			foreach ($Estates as $e){
				array_push($items,array('id'=>$e['id'],'name'=>$e['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$e['id'],'linkurl'=>'','keyword'=>$e['keyword'],'sub'=>1,'sublink'=>'/index.php?g=User&m=Link&a=EstateDetail&token='.$this->token.'&id='.$e['id']));
			}
		}
		
		
		$this->assign('list',$items);
		$this->display('detail');
		/*
		$moduleName='Estate';
		$this->assign('moduleName',$this->modules[$moduleName]);
		//
		$items=array();
		array_push($items,array('id'=>1,'name'=>'楼盘介绍','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=index&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>'微房产'));
		array_push($items,array('id'=>2,'name'=>'楼盘相册','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=album&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>'微房产'));
		array_push($items,array('id'=>3,'name'=>'户型全景','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=housetype&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>'微房产'));
		array_push($items,array('id'=>4,'name'=>'专家点评','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=impress&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>'微房产'));
		$Estate=M('Estate')->where(array('token'=>$this->token))->find();
		$rt=M('Reservation')->where(array('id'=>$Estate['res_id']))->find();
		array_push($items,array('id'=>5,'name'=>'看房预约','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Reservation&a=index&rid='.$Estate['res_id'].'&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$rt['keyword']));
		$this->assign('list',$items);
		$this->display('detail');
		*/
	}
	public function EstateDetail(){
		$moduleName='Estate';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$id=intval($_GET['id']);
		//
		$items=array();
		array_push($items,array('id'=>1,'name'=>'楼盘介绍','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$id,'linkurl'=>'','keyword'=>'微房产'));
		array_push($items,array('id'=>2,'name'=>'楼盘相册','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=album&token='.$this->token.'&wecha_id={wechat_id}&id='.$id,'linkurl'=>'','keyword'=>'微房产'));
		array_push($items,array('id'=>3,'name'=>'户型全景','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=housetype&token='.$this->token.'&wecha_id={wechat_id}&id='.$id,'linkurl'=>'','keyword'=>'微房产'));
		array_push($items,array('id'=>4,'name'=>'专家点评','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Estate&a=impress&token='.$this->token.'&wecha_id={wechat_id}&id='.$id,'linkurl'=>'','keyword'=>'微房产'));
		$Estate=M('Estate')->where(array('token'=>$this->token,'id'=>$id))->find();
		$rt=M('Reservation')->where(array('id'=>$Estate['res_id']))->find();
		array_push($items,array('id'=>5,'name'=>'看房预约','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Reservation&a=index&rid='.$Estate['res_id'].'&token='.$this->token.'&wecha_id={wechat_id}&id='.$id,'linkurl'=>'','keyword'=>$rt['keyword']));
		$this->assign('list',$items);
		$this->display('detail');
	}
	public function Car(){
		$moduleName='Car';
		$this->assign('moduleName',$this->modules[$moduleName]);
		//
		$thisItem=M('Carset')->where(array('token'=>$this->token))->find();
		$thisItemNews=M('Carnews')->where(array('token'=>$this->token))->find();
		$items=array();
		array_push($items,array('id'=>1,'name'=>'经销车型','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Car&a=brands&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>2,'name'=>'销售顾问','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Car&a=salers&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>3,'name'=>'车主关怀','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Car&a=owner&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>4,'name'=>'车型欣赏','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Car&a=showcar&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>5,'name'=>'实用工具','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Car&a=tool&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>6,'name'=>'预约试驾','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Car&a=CarReserveBook&addtype=drive&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>7,'name'=>'预约保养','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Car&a=CarReserveBook&addtype=maintain&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		//
		array_push($items,array('id'=>8,'name'=>'最新车讯','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=lists&classid='.$thisItemNews['news_id'].'&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>9,'name'=>'最新优惠','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=lists&classid='.$thisItemNews['pre_id'].'&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>10,'name'=>'尊享二手车','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Index&a=lists&classid='.$thisItemNews['usedcar_id'].'&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>11,'name'=>'品牌相册','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Photo&a=plist&id='.$thisItemNews['album_id'].'&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>12,'name'=>'店铺LBS','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Company&a=map&companyid='.$thisItemNews['company_id'].'&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		$this->assign('list',$items);
		$this->display('detail');
	}
	public function Medical(){
		$moduleName='Medical';
		$this->assign('moduleName',$this->modules[$moduleName]);
		//
		$thisItem=M('Medical_set')->where(array('token'=>$this->token))->find();
		//$thisItemNews=M('Carnews')->where(array('token'=>$this->token))->find();
		$items=array();
		array_push($items,array('id'=>1,'name'=>'医院简介','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Medical&a=Introduction&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>2,'name'=>'热点聚焦','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Medical&a=publicListTmp&type=hotfocus&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>3,'name'=>'医院专家','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Medical&&a=publicListTmp&type=experts&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>4,'name'=>'尖端设备','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Medical&a=publicListTmp&type=equipment&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>5,'name'=>'康复案例','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Medical&a=publicListTmp&type=rcase&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>6,'name'=>'先进技术','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Medical&&a=publicListTmp&type=technology&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>6,'name'=>'研发药物','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Medical&&a=publicListTmp&type=drug&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>6,'name'=>'预约挂号','linkcode'=>'{siteUrl}/index.php?g=Wap&m=Medical&&a=registered&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		//
	
		$this->assign('list',$items);
		$this->display('detail');
	}
	public function School(){
		$moduleName='School';
		$this->assign('moduleName',$this->modules[$moduleName]);
		//
		$thisItem=M('Medical_set')->where(array('token'=>$this->token))->find();
		//$thisItemNews=M('Carnews')->where(array('token'=>$this->token))->find();
		$items=array();
		array_push($items,array('id'=>1,'name'=>'成绩查询','linkcode'=>'{siteUrl}/index.php?g=Wap&m=School&a=qresults&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		array_push($items,array('id'=>1,'name'=>'食谱列表','linkcode'=>'{siteUrl}/index.php?g=Wap&m=School&a=public_list&type=school&token='.$this->token.'&wecha_id={wechat_id}','linkurl'=>'','keyword'=>$thisItem['keyword']));
		//
	
		$this->assign('list',$items);
		$this->display('detail');
	}
	
//外链小工具
	
	public function OutsideLink(){
		//处理小工具数组文件
			$f = include('./PigCms/Lib/ORG/Func.links.php');
		//取出分类总汇
			$i=0;
		foreach ($f['func'] as $k=>$v){
			
			$list[$i]['name'] = $v;
			$list[$i]['code'] = $k;
			$i++;
		}

		$this->assign('list',$list);
		$this->display();
	}
	
	public function outsideLinkDetail(){
		$c = $this->_get('c');
		
		$f = include('./PigCms/Lib/ORG/Func.links.php');
		
		$list = $f[$c];
		$this->assign('list',$list);
		$this->display('OutsideLink');
	
	}
	public function Autumn(){
		$moduleName='Autumn';
		$this->assign('moduleName',$this->modules[$moduleName]);
		$db=M('Lottery');
		$where=$this->where;
		$where['type']=9;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('id'=>$item['id'],'name'=>$item['title'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Autumn&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		//
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	public function Business(){
		$this->assign('moduleName',$this->modules['Business']);
		$db=M('Busines');
		$where=$this->where;
		$count      = $db->where($where)->count();
		$Page       = new Page($count,5);
		$show       = $Page->show();
		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('bid DESC')->select();
		//
		$items=array();
		if ($list){
			foreach ($list as $item){
				array_push($items,array('bid'=>$item['bid'],'name'=>$item['mtitle'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Business&a=index&token='.$this->token.'&wecha_id={wechat_id}&bid='.$item['bid'].'&type='.$item['type'],'linkurl'=>'','keyword'=>$item['keyword']));
			}
		}
		$this->assign('list',$items);
		$this->assign('page',$show);
		$this->display('detail');
	}
	
// 	public function Donation(){
// 		$this->assign('moduleName',$this->modules['Donation']);
// 		$db=M('Donation');
// 		$where=$this->where;
// 		$count      = $db->where($where)->count();
// 		$Page       = new Page($count,5);
// 		$show       = $Page->show();
// 		$list=$db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();
// 		//
// 		$items=array();
// 		if ($list){
// 			foreach ($list as $item){
// 				array_push($items,array('bid'=>$item['id'],'name'=>$item['name'],'linkcode'=>'{siteUrl}/index.php?g=Wap&m=Donation&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$item['id'],'linkurl'=>'','keyword'=>$item['keyword']));
// 			}
// 		}
// 		$this->assign('list',$items);
// 		$this->assign('page',$show);
// 		$this->display('detail');
// 	}
	
}


?>