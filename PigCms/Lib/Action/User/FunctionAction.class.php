<?php
class FunctionAction extends UserAction{
	public function _initialize(){
		parent::_initialize();
	}
	function index(){

		$array = trim($array['id']["uid"]);

		$id=$this->_get('id','intval');

		if (!$id){
			//$token=$this->token;
			$info=M('Wxuser')->find(array('where'=>array('token'=>$this->token)));
		}else {
			$info=M('Wxuser')->find($id);
		}

		$token=$this->_get('token','trim');
		if($info==false||$info['token']!=$token){
			$this->error('非法操作',U('Home/Index/index'));
		}
		$this->redirect("User/Function/welcome",array('token'=>$token,'id'=>$id));exit;

		session('token',$token);
		session('wxid',$info['id']);
		session('companyid', null);
		//第一次登陆　创建　功能所有权
		$token_open=M('Token_open');

		$toback        =$token_open->field('id,queryname')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$open['uid']   =session('uid');
		$open['token'] =session('token');
		//遍历功能列表
		if (!C('agent_version')){
			$group=M('User_group')->field('id,name,func')->where('status=1 AND id = '.session('gid'))->order('id ASC')->find();
			$funcs = M('Function')->where("1 = 1")->select();
		}else {
			$group=M('User_group')->field('id,name,func')->where('status=1 AND agentid='.$this->agentid.' AND id = '.session('gid'))->order('id ASC')->find();
			$funcs = M('Agent_function')->where(array('agentid'=>$this->agentid))->select();
		}

		$check=explode(',',trim($toback['queryname'],','));
/*
		foreach ($check as $ck => $cv){
			if(strpos($group['func'],$cv) === false){
				$group['func'] .= ','.$cv;
			}

		}


*/
		$group['func'] = explode(',',$group['func']);

			foreach ($group['func'] as $gk=>$gv){

					$open_func = M('Token_open')->where(array('token'=>session('token'),'uid'=>session('uid')))->getField('queryname');

					if(strpos($open_func,$gv) === false){
						$where = array('funname'=>$gv,'status'=>1);
					}else{
						$where = array('funname'=>$gv);
					}

					if (C('agent_version')&&$this->agentid){
						$where['agentid'] = $this->agentid;
						$group['func'][$gk] = M('Function')->where($where)->field('id,funname,name,info')->find();
					}else{
						$where['status'] = 1;
						$group['func'][$gk] = M('Function')->where($where)->field('id,funname,name,info')->find();
					}

				if($group['func'][$gk] == NULL){
					unset($group['func'][$gk]);
				}
			}


		$this->assign('fun',$group);

		//
		$wecha=M('Wxuser')->field('wxname,wxid,headerpic,weixin')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('wecha',$wecha);
		$this->assign('token',session('token'));
		$this->assign('check',$check);
		$this->display();
	}




	function welcome(){
		$id=$this->_get('id','intval');
		if (!$id){
			$info=M('Wxuser')->find(array('where'=>array('token'=>$this->token)));
		}else {
			$info=M('Wxuser')->find($id);
		}

		$token=$this->_get('token','trim');

		if($info==false||$info['token']!=$token){
			$this->error('非法操作',U('Home/Index/index'));
		}
		session('companyid', 0);
		session('token',$token);
		session('wxid',$info['id']);
		$wecha=M('Wxuser')->field('wxname,wxid,headerpic,weixin')->where(array('token'=>session('token'),'uid'=>session('uid')))->find();
		$this->assign('wecha',$wecha);


		$this->handleKeyword(1,'waphelp','waphelp',1);
		// 模板 0 不让进
		if ($this->usertplid != 1) {
			//$this->error('您需要选择使用新的模板才能进入此页');
			$this->redirect('User/Home/set',array('token'=>$token));exit;
		}

		$data = array();

		$data['mp']     = M('Wxuser')->where(array('uid'=>intval(session('uid'))))->count();
		$data['card']   = M('Member_card_create')->where(array('token'=>$this->token,'wecha_id'=>array('neq','')))->count();
		$data['active'] = M('Lottery')->where(array('token'=>$this->token))->count();
		$data['img']    = M('Img')->where(array('token'=>$this->token))->count();
		$this->assign('data',$data);

			$month=date('m');
			$year=date('Y');

		$this->assign('month',$month);
		$this->assign('year',$year);

		//手写功能名m必须为小写，默认区分大小写
		$uid=session('uid');
		$gid=M('Users')->where("id=$uid")->getField('gid');
		$where['status']=1;
		$where['id']=$gid;
		$func=M('User_group')->where($where)->getField('func');
		$arr_func0=explode(",",strtolower($func));
		//dump($arr_func0);
		//exit;
		$q_funname=M('Function')->field('funname,status')->select();
		foreach($q_funname as $k => $v){
				$q_arr[]=$v['funname'];
				if($v['status']==0){
					$C_not_status[]=$q_funname[$k]['funname'];
				}

		}

		$C=strtolower(implode(',',$C_not_status));
		$not_status=explode(',',$C);
		$arr_func=array_diff($arr_func0,$not_status);//状态没有开启=>没有选中
		$c_site=C('APP_GROUP_LIST');//判断有没有三网通功能,没有三网通功能=>没有勾选对应的PC网站功能
		$group_list=explode(',',$c_site);
		if(!in_array('Web',$group_list)){
			if(in_array('website',$arr_func)){
				foreach($arr_func as $k2=>$v2){
					if($v2=='website'){
						unset($arr_func[$k2]);
					}
				}
			}
		}
		if(!in_array('Fuwu',$group_list)){//判断有没有服务窗功能
			if(in_array('fuwu',$arr_func)){
				foreach($arr_func as $k3=>$v3){
					if($v3=='fuwu'){
						unset($arr_func[$k3]);
					}
				}
			}
		}
		$lower_q_arr=strtolower(implode(',',$q_arr));
		$all_quc=explode(',',$lower_q_arr);
		foreach($arr_func as $k4 => $v4){//判断功能模块被删除，也不能显示（被删除，不在function表中）
			if(!in_array($v4,$all_quc)){
				unset($arr_func[$k4]);
			}
		}
		if(session('role_name') == 'staff'){
			$staff_func = M('company_staff')->where(array('id'=>intval(session('staff_id'))))->getField('func');
			$func_allow = explode(",",strtolower($staff_func));
			$not_exists = array('tianqi','qiushi','jishuan','langdu','jiankang','kuaidi','xiaohua','changtoushi','peiliao','liaotian','mengjian','yuyinfanyi','huoche','gongjiao','shenfenzheng','shouji','yinle','fujin','geci','fanyi','api','suanming','baike','caipiao','Zhida','whois','Fuwu','Assistente');
			if($this->isFuwu){
				unset($not_exists['Fuwu']);
			}
			foreach($arr_func as $key=>$v){
				if(!in_array($v,$not_exists) && in_array($v,$func_allow)){
					$staff[$key] = $v;
				}
			}
			$arr_func = $staff;
		}
		$wel_content=array(
		array('title'=>'文本回复','url'=>U('Text/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_03.png','m'=>'text'),
		array('title'=>'图文回复','url'=>U('Img/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_05.png','m'=>'img'),
		array('title'=>'多图文回复','url'=>U('Img/multi',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_07.png','m'=>'img'),
		array('title'=>'群发消息','url'=>U('Message/sendHistory',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_09.png','m'=>'groupmessage'),
		array('title'=>'语音回复','url'=>U('Voiceresponse/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_11.png','m'=>'voiceresponse'),
		array('title'=>'自定义LBS','url'=>U('Company/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_19.png','m'=>'company'),
		array('title'=>'模板消息','url'=>U('TemplateMsg/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_20.png','m'=>'templatemsg'),
		array('title'=>'自定义菜单','url'=>U('Diymen/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_18.png','m'=>'diymen_set'),
		array('title'=>'微网站','url'=>U('Home/set',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_21.png','m'=>'shouye'),
		array('title'=>'模板设置','url'=>U('Tmpls/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_23.png','m'=>'tmpls'),
		array('title'=>'底部导航','url'=>U('Catemenu/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_30.png','m'=>'catemenu'),
		array('title'=>'PC网站','url'=>U('Web/set',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_32.png','m'=>'website'),
		array('title'=>'手机站','url'=>U('Phone/baseSet',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_34.png','m'=>'phone'),
		array('title'=>'APP打包','url'=>U('Yundabao/applist',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_35.png','m'=>'yundabao'),
		array('title'=>'百度直达号','url'=>U('Zhida/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_36.png','m'=>'zhida'),
		array('title'=>'微场景','url'=>U('SeniorScene/highLive',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_42.png','m'=>'seniorscene'),
		array('title'=>'H5模板','url'=>U('CustomTmpls/dynamic',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_43.png','m'=>'customtmpls')
		);
		//不受控制的
		$not_control_wel=array(
		array('title'=>'文本回复','url'=>U('Text/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_03.png','m'=>'text'),
		array('title'=>'图文回复','url'=>U('Img/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_05.png','m'=>'img'),
		array('title'=>'多图文回复','url'=>U('Img/multi',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_07.png','m'=>'img'),
		array('title'=>'语音回复','url'=>U('Voiceresponse/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_11.png','m'=>'voiceresponse'),
		array('title'=>'自定义LBS','url'=>U('Company/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_19.png','m'=>'company'),
		array('title'=>'模板消息','url'=>U('TemplateMsg/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_20.png','m'=>'templatemsg'),
		array('title'=>'模板设置','url'=>U('Tmpls/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_23.png','m'=>'tmpls'),
		array('title'=>'底部导航','url'=>U('Catemenu/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_30.png','m'=>'catemenu')
		//array('title'=>'APP打包','url'=>U('Yundabao/applist',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/houtai_35.png','m'=>'yundabao')
		);
		foreach($wel_content as $k5 => $v5){//二维数组变成一维数组，键值不变
			$wel_arr[]=$v5['m'];
		}
		$wel=array_intersect($wel_arr,$arr_func);
		foreach($wel as $k6=>$v6){
			$wel_s[]=$wel_content[$k6];
		}
		$all_wel=array_merge($not_control_wel,$wel_s);
		$this->assign('wel_content',$all_wel);
	//first end1
		$spr_content=array(
		array('title'=>'3G投票','url'=>U('Vote/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_1.png','m'=>'vote'),
		array('title'=>'摇一摇周边','url'=>U('Shakearound/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_2.png','m'=>'shakearound'),
		array('title'=>'微信签到','url'=>U('Signin/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_3.png','m'=>'signin'),
		array('title'=>'摇一摇','url'=>U('Shake/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_4.png','m'=>'shake'),
		array('title'=>'微信墙','url'=>U('Wall/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_5.png','m'=>'wall'),
		array('title'=>'现场活动','url'=>U('Scene/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_8.png','m'=>'scene'),
		array('title'=>'微信wifi','url'=>U('Router/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_6.png','m'=>'router'),
		array('title'=>'照片打印机','url'=>U('Hardware/photoprint',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_9.png','m'=>'hardware'),
		array('title'=>'微渠道','url'=>U('Recognition/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_7.png','m'=>'recognition'),
		array('title'=>'合体红包','url'=>U('Hongbao/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_10.png','m'=>'hongbao'),
		array('title'=>'分享助力','url'=>U('Helping/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_11.png','m'=>'helping'),
		array('title'=>'人气冲榜','url'=>U('Popularity/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_12.png','m'=>'popularity'),
		array('title'=>'拆礼盒','url'=>U('Autumns/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_13.png','m'=>'autumns'),
		array('title'=>'微游戏','url'=>U('Game/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_14.png','m'=>'gamecenter'),
		array('title'=>'微信红包','url'=>U('Red_packet/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_31.png','m'=>'red_packet'),
		array('title'=>'微名片','url'=>U('Person_card/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_32.png','m'=>'person_card')
		);
		//不受控制的
		$not_control_spr=array(
		array('title'=>'微信wifi','url'=>U('Router/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_6.png','m'=>'router'),
		array('title'=>'照片打印机','url'=>U('Hardware/photoprint',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_9.png','m'=>'hardware'),
		array('title'=>'微渠道','url'=>U('Recognition/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_7.png','m'=>'recognition')
		);

		foreach($spr_content as $ka => $va){//二维数组变成一维数组，键值不变
			$spr_arr[]=$va['m'];
		}
		$spr=array_intersect($spr_arr,$arr_func);
		foreach($spr as $kb => $vb){
			$spr_s[]=$spr_content[$kb];
		}
		$all_spr=array_merge($not_control_spr,$spr_s);
		if(C('router_key')== ''){ //判断有没有购买微信WIFI
				foreach($all_spr as $kspr=>$vspr){
					if($vspr['m']=='router'){
						unset($all_spr[$kspr]);
					}
				}
		}
		$this->assign('spr_content',$all_spr);
	//two end1
		$mar_content=array(
		array('title'=>'微游戏','url'=>U('Game/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_14.png','m'=>'gamecenter'),
		array('title'=>'微贺卡','url'=>U('Card/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_05.png','m'=>'card'),
		array('title'=>'图文投票','url'=>U('Voteimg/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_1.png','m'=>'voteimg'),
		array('title'=>'万能表单','url'=>U('Custom/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_09.png','m'=>'custom'),
		array('title'=>'微调研','url'=>U('Research/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_11.png','m'=>'research'),
		array('title'=>'分享管理','url'=>U('Share/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_18.png','m'=>'share'),
		array('title'=>'微信签到','url'=>U('Signin/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_3.png','m'=>'signin'),
		array('title'=>'摇一摇','url'=>U('Shake/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_4.png','m'=>'shake'),
		array('title'=>'微信墙','url'=>U('Wall/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_5.png','m'=>'wall'),
		array('title'=>'现场活动','url'=>U('Scene/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_8.png','m'=>'scene'),
		array('title'=>'合体红包','url'=>U('Hongbao/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_10.png','m'=>'hongbao'),
		array('title'=>'分享助力','url'=>U('Helping/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_11.png','m'=>'helping'),
		array('title'=>'人气冲榜','url'=>U('Popularity/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/spread_12.png','m'=>'popularity'),
		array('title'=>'大转盘','url'=>U('Lottery/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_33.png','m'=>'lottery'),
		array('title'=>'九宫格','url'=>U('Jiugong/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_34.png','m'=>'jiugong'),
		array('title'=>'优惠券','url'=>U('Coupon/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_40.png','m'=>'choujiang'),
		array('title'=>'刮刮卡','url'=>U('Guajiang/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_41.png','m'=>'gua2'),
		array('title'=>'水果机','url'=>U('LuckyFruit/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_42.png','m'=>'luckyfruit'),
		array('title'=>'砸金蛋','url'=>U('GoldenEgg/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_43.png','m'=>'goldenegg'),
		array('title'=>'走鹊桥','url'=>U('AppleGame/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_44.png','m'=>'applegame'),
		array('title'=>'摁死小情侣','url'=>U('Lovers/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_50.png','m'=>'lovers'),
		array('title'=>'中秋吃月饼','url'=>U('Autumn/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_61.png','m'=>'autumn'),
		array('title'=>'拆礼盒','url'=>U('Autumns/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_62.png','m'=>'autumns'),
		array('title'=>'一战到底','url'=>U('Problem/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_63.png','m'=>'problem'),
		array('title'=>'惩罚台','url'=>U('Punish/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/yingxiao_64.png','m'=>'punish')
		);
		//不受控制的，无
		foreach($mar_content as $kc => $vc){//二维数组变成一维数组，键值不变
			$mar_arr[]=$vc['m'];
		}
		$mar=array_intersect($mar_arr,$arr_func);
		foreach($mar as $kd=>$vd){
			$all_mar[]=$mar_content[$kd];
		}
		$this->assign('mar_content',$all_mar);
	//three end1
		$holi_content=array(
		array('title'=>'春节','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_03.png','m'=>''),
		array('title'=>'情人节','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_05.png','m'=>''),
		array('title'=>'劳动节','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_07.png','m'=>''),
		array('title'=>'儿童节','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_09.png','m'=>''),
		array('title'=>'端午节','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_11.png','m'=>''),
		array('title'=>'生日','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_18.png','m'=>''),
		array('title'=>'中秋节','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_19.png','m'=>''),
		array('title'=>'国庆节','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_20.png','m'=>''),
		array('title'=>'重阳节','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_21.png','m'=>''),
		array('title'=>'圣诞节','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_22.png','m'=>''),
		array('title'=>'元旦','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_29.png','m'=>''),
		array('title'=>'七夕','url'=>'','pic'=>'/tpl/static/newswelcome/images/jieri_31.png','m'=>'')
		);
		foreach($holi_content as $ke => $ve){//二维数组变成一维数组，键值不变
			$holi_arr[]=$ve['m'];
		}
		$holi=array_intersect($holi_arr,$c_fun);
		$this->assign('holi_content',$holi_content);
	//four end1
		$busi_content=array(
		array('title'=>'多种支付接口配置','url'=>U('Alipay_config/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/dianhsng_03.png','m'=>'alipay_config'),
		array('title'=>'平台对账','url'=>U('Platform/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/dianhsng_05.png','m'=>'platform'),
		array('title'=>'微团购','url'=>U('Groupon/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/dianhsng_07.png','m'=>'etuan'),
		array('title'=>'微商城','url'=>U('Store/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/dianhsng_09.png','m'=>'shop'),
		array('title'=>'三级分销','url'=>U('Micrstore/dis',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/dianhsng_11.png','m'=>'micrstore'),
		array('title'=>'微众筹','url'=>U('Crowdfunding/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/dianhsng_18.png','m'=>'crowdfunding'),
		array('title'=>'一元夺宝','url'=>U('Unitary/index',array('token'=>$token,'type'=>'seckill')),'pic'=>'/tpl/static/newswelcome/images/dianhsng_20.png','m'=>'unitary'),
		array('title'=>'微砍价','url'=>U('Bargain/index',array('token'=>$token,'type'=>'Bargain')),'pic'=>'/tpl/static/newswelcome/images/dianhsng_22.png','m'=>'bargain'),
		array('title'=>'降价拍','url'=>U('Cutprice/index',array('token'=>$token,'type'=>'Cutprice')),'pic'=>'/tpl/static/newswelcome/images/dianhsng_24.png','m'=>'cutprice'),
		array('title'=>'微商圈','url'=>U('Market/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/dianhsng_31.png','m'=>'market'),
		array('title'=>'微秒杀','url'=>U('Seckill/index',array('token'=>$token,'type'=>'seckill')),'pic'=>'/tpl/static/newswelcome/images/dianhsng_32.png','m'=>'seckill'),
		array('title'=>'微店','url'=>U('Micrstore/index',array('token'=>$token,'type'=>'Micrstore')),'pic'=>'/tpl/static/newswelcome/images/dianhsng_33.png','m'=>'micrstore')
		);
		//不受控制的
		$not_control_busi=array(
		array('title'=>'多种支付接口配置','url'=>U('Alipay_config/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/dianhsng_03.png','m'=>'alipay_config'),
		array('title'=>'平台对账','url'=>U('Platform/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/dianhsng_05.png','m'=>'platform')
		);
		foreach($busi_content as $k => $v){//二维数组变成一维数组，键值不变
			$busi_arr[]=$v['m'];
		}
		$busi=array_intersect($busi_arr,$arr_func);
		foreach($busi as $kh => $vh){
			$busi_s[]=$busi_content[$kh];
		}
		$all_busi=array_merge($not_control_busi,$busi_s);
		$this->assign('busi_content',$all_busi);
	//five end1
		$ind_content=array(
		array('title'=>'全民经纪人','url'=>U('MicroBroker/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/gongneng_03.png','m'=>'microbroker'),
		array('title'=>'微餐饮','url'=>U('Repast/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/gongneng_05.png','m'=>'dx'),
		array('title'=>'微外卖','url'=>U('DishOut/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/gongneng_07.png','m'=>'dishout'),
		array('title'=>'360°全景','url'=>U('Panorama/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/gongneng_09.png','m'=>'panorama'),
		array('title'=>'喜帖','url'=>U('Wedding/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/gongneng_11.png','m'=>'wedding'),
		array('title'=>'美容行业','url'=>U('Business/index',array('token'=>$token,'type'=>'beauty')),'pic'=>'/tpl/static/newswelcome/images/gongneng_33.png','m'=>'beauty'),
		array('title'=>'教育行业','url'=>U('School/index',array('token'=>$token,'type'=>'semester')),'pic'=>'/tpl/static/newswelcome/images/gongneng_22.png','m'=>'school'),
		array('title'=>'医疗行业','url'=>U('Medical/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/gongneng_24.png','m'=>'medical'),
		array('title'=>'酒店宾馆','url'=>U('Hotels/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/gongneng_26.png','m'=>'hotel'),
		array('title'=>'通用订单','url'=>U('Host/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/gongneng_20.png','m'=>'host_kev'),
		array('title'=>'汽车行业','url'=>U('Car/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/gongneng_18.png','m'=>'car'),
		array('title'=>'健身行业','url'=>U('Business/index',array('token'=>$token,'type'=>'fitness')),'pic'=>'/tpl/static/newswelcome/images/gongneng_34.png','m'=>'fitness'),
		array('title'=>'政务应用','url'=>U('Business/index',array('token'=>$token,'type'=>'gover')),'pic'=>'/tpl/static/newswelcome/images/gongneng_36.png','m'=>'gover'),
		array('title'=>'食品行业','url'=>U('Business/index',array('token'=>$token,'type'=>'food')),'pic'=>'/tpl/static/newswelcome/images/gongneng_38.png','m'=>'food'),
		array('title'=>'旅游行业','url'=>U('Business/index',array('token'=>$token,'type'=>'travel')),'pic'=>'/tpl/static/newswelcome/images/gongneng_39.png','m'=>'travel'),
		array('title'=>'花店行业','url'=>U('Business/index',array('token'=>$token,'type'=>'flower')),'pic'=>'/tpl/static/newswelcome/images/gongneng_45.png','m'=>'flower'),
		array('title'=>'物业公司','url'=>U('Business/index',array('token'=>$token,'type'=>'property')),'pic'=>'/tpl/static/newswelcome/images/gongneng_46.png','m'=>'property'),
		array('title'=>'KTV行业','url'=>U('Business/index',array('token'=>$token,'type'=>'ktv')),'pic'=>'/tpl/static/newswelcome/images/gongneng_47.png','m'=>'ktv'),
		array('title'=>'酒吧行业','url'=>U('Business/index',array('token'=>$token,'type'=>'bar')),'pic'=>'/tpl/static/newswelcome/images/gongneng_49.png','m'=>'bar'),
		array('title'=>'装修行业','url'=>U('Business/index',array('token'=>$token,'type'=>'fitment')),'pic'=>'/tpl/static/newswelcome/images/gongneng_50.png','m'=>'fitment'),
		array('title'=>'婚庆行业','url'=>U('Business/index',array('token'=>$token,'type'=>'wedding')),'pic'=>'/tpl/static/newswelcome/images/gongneng_56.png','m'=>'buswedd'),
		array('title'=>'宠物店','url'=>U('Business/index',array('token'=>$token,'type'=>'affections')),'pic'=>'/tpl/static/newswelcome/images/gongneng_57.png','m'=>'affections'),
		array('title'=>'微家政','url'=>U('Business/index',array('token'=>$token,'type'=>'housekeeper')),'pic'=>'/tpl/static/newswelcome/images/gongneng_58.png','m'=>'housekeeper'),
		array('title'=>'微租赁','url'=>U('Business/index',array('token'=>$token,'type'=>'lease')),'pic'=>'/tpl/static/newswelcome/images/gongneng_60.png','m'=>'lease')
		);
		//不受控制的，无
		foreach($ind_content as $k => $v){//二维数组变成一维数组，键值不变
			$ind_arr[]=$v['m'];
		}
		$ind=array_intersect($ind_arr,$arr_func);
		foreach($ind as $kind =>$vind){
			$all_ind[]=$ind_content[$kind];
		}
		$this->assign('ind_content',$all_ind);
	//sex end1
		$mem_content=array(
		array('title'=>'自动获取粉丝信息','url'=>U('Auth/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_03.png','m'=>'auth'),
		array('title'=>'粉丝管理','url'=>U('Wechat_group/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_05.png','m'=>'wechat_group'),
		array('title'=>'粉丝分组','url'=>U('Wechat_group/groups',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_07.png','m'=>'wechat_group'),
		array('title'=>'服务窗粉丝管理','url'=>U('Fuwu/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_09.png','m'=>'fuwu'),
		array('title'=>'网页客服','url'=>U('Service/servicelist',array('token'=>$token,'type'=>'setup')),'pic'=>'/tpl/static/newswelcome/images/guanli_11.png','m'=>'service'),
		array('title'=>'微信人工客服','url'=>U('ServiceUser/wechatService',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_18.png','m'=>'serviceuser'),
		array('title'=>'会员卡','url'=>U('Member_card/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_20.png','m'=>'huiyuanka'),
		array('title'=>'卡券管理','url'=>U('Member_card/coupons',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_19.png','m'=>'coupons'),
		array('title'=>'粉丝行为分析','url'=>U('Wechat_behavior/statistics',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_22.png','m'=>'wechat_behavior')
		);
		//不受控制的
		$not_control_mem=array(
		array('title'=>'自动获取粉丝信息','url'=>U('Auth/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_03.png','m'=>'auth'),
		array('title'=>'粉丝管理','url'=>U('Wechat_group/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_05.png','m'=>'wechat_group'),
		array('title'=>'粉丝分组','url'=>U('Wechat_group/groups',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_07.png','m'=>'wechat_group'),
		array('title'=>'卡券管理','url'=>U('Member_card/coupons',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_19.png','m'=>'coupons'),
		array('title'=>'粉丝行为分析','url'=>U('Wechat_behavior/statistics',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/guanli_22.png','m'=>'wechat_behavior')
		);
		foreach($mem_content as $k => $v){//二维数组变成一维数组，键值不变
			$mem_arr[]=$v['m'];
		}

		$mem=array_intersect($mem_arr,$arr_func);
		foreach($mem as $kmem => $vmem){
			$mem_s[]=$mem_content[$kmem];
		}
		$all_mem=array_merge($not_control_mem,$mem_s);
		$this->assign('mem_content',$all_mem);
	//seven end
		$oth_content=array(
		array('title'=>'留言板','url'=>U('Reply/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/other1.png','m'=>'messageboard'),
		array('title'=>'微论坛','url'=>U('Forum/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/other2.png','m'=>'forum'),
		array('title'=>'微调研','url'=>U('Research/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/other3.png','m'=>'research'),
		array('title'=>'邀请函','url'=>U('Invite/add',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/other4.png','m'=>'invite'),
		array('title'=>'无线打印机','url'=>U('Hardware/orderprint',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/other5.png','m'=>'hardware'),
		array('title'=>'二次开发','url'=>U('Api/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/other6.png','m'=>'api'),
		array('title'=>'请求数据统计','url'=>U('Requerydata/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/other7.png','m'=>'requerydata')
		);
		//不受控制的
		$not_control_oth=array(
		array('title'=>'无线打印机','url'=>U('Hardware/orderprint',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/other5.png','m'=>'hardware'),
		array('title'=>'请求数据统计','url'=>U('Requerydata/index',array('token'=>$token)),'pic'=>'/tpl/static/newswelcome/images/other7.png','m'=>'requerydata')
		);
		foreach($oth_content as $k => $v){//二维数组变成一维数组，键值不变
			$oth_arr[]=$v['m'];
		}
		$oth=array_intersect($oth_arr,$arr_func);
		foreach($oth as $koth => $voth){
			$oth_s[]=$oth_content[$koth];
		}
		$all_oth=array_merge($not_control_oth,$oth_s);
		$this->assign('oth_content',$all_oth);
	//eight end

	//关注和请求数统计
		$where=array('token'=>$this->token,'month'=>$month,'year'=>$year);
		$list=M('Requestdata')->where($where)->limit(31)->order('id ASC')->select();
		if($list){
			foreach ($list as $k => $v){
				$charts['xAxis']  .= '"'.$v['day'].'日",';
				$charts['follow'] .= '"'.$v['follownum'].'",';
				$charts['text']   .= '"'.$v['textnum'].'",';

			}
		}else{
			for($i=0;$i<30;$i++){
				$charts['xAxis']  .= '"'.($i+1).'日",';
				$charts['follow'] .= '"'.rand(1,100).'",';
				$charts['text']   .= '"'.rand(100,300).'",';

			}
				$charts['ifnull'] = 1;
		}


		function trim_map($val){
			return rtrim($val,',');
		}
		$charts = array_map('trim_map',$charts);
		$this->assign('charts',$charts);

	//粉丝行为数据统计

		$days=7;
		$this->assign('days',$days);
		$modules = R('User/Wechat_behavior/_modules');
		$where=array('token'=>$this->token);
		$where['enddate']=array('gt',time()-$days*24*3600);
		$behaviorDB=M('Behavior');
		$where['model']=array('neq','');
		$items=$behaviorDB->where($where)->order('num DESC')->select();

	if($items){
		$datas=array();
		if ($items){
			foreach ($items as $item){
				$module=strtolower($item['model']);
				if (key_exists($module,$datas)){
					$datas[$module]+=$item['num'];
				}else {
					$datas[$module]=$item['num'];
				}
			}
		}
		asort($datas);

		if ($datas){
			foreach ($datas as $k=>$v){
				if($modules[$k]['name']){
					//$pie['legend'] .= "'".$modules[$k]['name']."',";
					$pie['series'] .= "{value:$v, name:'".$modules[$k]['name']."'},";
				}


			}
		}
		$pie = array_map('trim_map',$pie);
	}else{
		$pie = array(
					'ifnull' => 1,
					'series' => "{value:".rand(1,100).", name:'万能表单'},{value:".rand(1,100).", name:'商城'},{value:".rand(1,100).", name:'全景'},{value:".rand(1,100).", name:'关注'},{value:".rand(1,100).", name:'文本请求'},{value:".rand(1,100).", name:'图文消息'},{value:".rand(1,100).", name:'通用订单'},{value:".rand(1,100).", name:'投票'},{value:".rand(1,100).", name:'婚庆喜帖'},{value:".rand(1,100).", name:'会员卡'},{value:".rand(1,100).", name:'推广活动'}");
	}

		$this->assign('pie',$pie);

	//会员性别统计

	$man = M('Userinfo')->where(array('token'=>$this->token,'sex'=>1))->count();
	$woman = M('Userinfo')->where(array('token'=>$this->token,'sex'=>2))->count();
	$other = M('Userinfo')->where(array('token'=>$this->token,'sex'=>3))->count();

	if($man == 0 && $woman == 0 && $other == 0){
		$man	=	rand(1,50);
		$woman	=	rand(1,100);
		$other	=	rand(1,10);
		$sex_series['ifnull'] = 1;
	}
		$sex_series['series'] = "{value:$man, name:'男'},"."{value:$woman, name:'女'},"."{value:$other, name:'其他'}";


		$this->assign('sex_series',$sex_series);
		$this->assign('token',session('token'));
		$this->display();
	}

}


?>