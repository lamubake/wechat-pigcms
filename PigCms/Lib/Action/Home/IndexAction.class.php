<?php
class IndexAction extends BaseAction{
	public $includePath;
	protected function _initialize(){
		parent::_initialize();
		$this->home_theme=$this->home_theme?$this->home_theme:'pigcms';
		$this->includePath='./tpl/Home/'.$this->home_theme.'/';
		$this->assign('includeHeaderPath',$this->includePath.'Public_header.html');
		$this->assign('includeFooterPath',$this->includePath.'Public_footer.html');
		$time = date('Y',time());
		$this->assign('time',$time);
		$agentid=$this->agentid;
		$db=M('Customs');
		if($agentid == 0){
			//多个电话号码处理
			$str = C('site_mp');
			$arr = explode(",",$str);
			$this->assign('sitemp',$arr);
			//多个QQ号码处理
			$str1 = (C('site_qq'));
			$arr1 = explode(",",$str1);
			$this->assign('siteqq',$arr1);
			$image = D('images');
			$where=array('agentid'=>'0');
			$images=$image->where($where)->find();
			$this->assign('images',$images);
			$zdydh=$db->where(array('agentid'=>'0'))->select();
			$this->assign('zdydh',$zdydh);
		}else{
			$agent = M('Agent');
			$lists = $agent->where(array('id'=>$agentid))->field('mp,qrcode,qq,copyright,agenturl')->select();
			$lists = $lists[0];
			$qrcode=$lists['qrcode'];
			$agenturl=$lists['agenturl'];
			$copyright=$lists['copyright'];
			$qq = explode(",",$lists['qq']);
			$mp = explode(",",$lists['mp']);
			$image = D('images');
			$did = $this->agentid;
			$where=array('agentid'=>$did);
			$images=$image->where($where)->find();
			$this->assign('images',$images);
			$this->assign('qrcode',$qrcode);
			$this->assign('agenturl',$agenturl);
			$this->assign('siteqq',$qq);
			$this->assign('sitemp',$mp);
			$this->assign('copyright',$copyright);
			if($lists['isnav'] == 0){
				$zdydh=$db->where(array('agentid'=>$agentid))->select();
				$this->assign('zdydh',$zdydh);
			}
		}
		foreach($zdydh as $k=>$v){
			switch($v['type']){
				case 1: $type['fc']=1;break;
				case 2: $type['about']=1;break;
				case 3: $type['prize']=1;break;
				case 4: $type['common']=1;break;
				case 5: $type['login']=1;break;
				case 6: $type['help']=1;break;
			}
		}
		$this->assign('type',$type);
		$this->assign('agentid',$agentid);
	}

	public function clogin()
	{
		$cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
		$k = isset($_GET['k']) ? $_GET['k'] : '';
		$this->assign('cid', $cid);
		$this->assign('k', $k);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	//关注回复
	public function index(){
		if (!$_SESSION['showNotice']) {
			$this->assign('showNotice','showNotice();');
			$_SESSION['showNotice'] = 1;
		}
		if(C('server_topdomain') == 'pigcms.cn'){
			$this->assign('agent_zid','1');
		}
		//原模版
		$where['status']=1;
		if (C('agent_version')){
			$where['agentid']=$this->agentid;
		}
		$links=D('Links')->where($where)->select();
		$this->assign('links',$links);
		//新模版
		$ren=M('Renew');
		$ban=M('Banners');
		$cas=M('Case');
		$renew=$ren->where(array('agentid'=>'0','status'=>'1'))->order('id DESC')->limit(5)->select();
		$banner=$ban->where($where)->order('id DESC')->select();
		//案例
		if(C('server_topdomain')=='pigcms.cn'){
			$class=D('Classify');
	      	$cont = D('Img');
	      	$class=$class->where(array('token'=>'yicms','fid'=>'1076407'))->order('sorts DESC')->field('id')->select();

		    $casesID=array();
		    foreach ($class as $sc){
	      		array_push($casesID,$sc['id']);
	      	}
	      	if ($casesID){
	      		$where['classid'] = array('in',$casesID);
	      		$where['token'] = 'yicms';
	      		$where['status'] = '1';
	      		$case=$cont->where($where)->order('usort DESC')->limit(10)->field('pic,title,writer,url')->select();
	      	}
		}else{
			$case=$cas->where($where)->order('id DESC')->limit(10)->select();
		}
		$this->assign('renew',$renew);
		$this->assign('banner',$banner);
		$this->assign('case',$case);
		//底部新闻列表
		$db = D('News');
		if (C('agent_version')){
			$wheres['agentid']=$this->agentid;
		}
		$info = $db->where($wheres)->find();
		$this->assign('info',$info);
		$db1 = D('Img');

		$title1 = $db1->where(array('classid'=>$info['class1']))->order('usort DESC')->limit(5)->select();
		$title2 = $db1->where(array('classid'=>$info['class2']))->order('usort DESC')->limit(5)->select();
		$title3 = $db1->where(array('classid'=>$info['class3']))->order('usort DESC')->limit(5)->select();
		$this->assign('title1',$title1);
		$this->assign('title2',$title2);
		$this->assign('title3',$title3);

		//微信营销首页小手三层缓存
		// S('top_arr',null);exit;
		if(C('server_topdomain')=='pigcms.cn'){
			if(S('top_arr')){
				$top_arr=S('top_arr');
			}else{
				$top_arr=M('Funclass')->order("id desc")->select();
				S('top_arr',$top_arr,3600);
			}
			if(S('top_jieri')){
				$top_jieri=S('top_jieri');
			}else{
				$top_jieri=M('Funclass_holi')->order("id desc")->select();
				S('top_jieri',$top_jieri,3600);
			}
			$this->assign('top_jieri',$top_jieri);
			$this->assign('top_arr',$top_arr);
		}

		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function convertLink($url){
		$url=str_replace(array('{siteUrl}','&amp;','&wecha_id={wechat_id}','{changjingUrl}'),array($this->siteUrl,'&','&diymenu=1','http://www.weihubao.com'),$url);
		return $url;
	}
	public function QRcode_ajax(){
		include './PigCms/Lib/ORG/phpqrcode.php';
		if(S('home_arr')){
			$home_arr=S('home_arr');
		}else{
			$home_arr=M('Funintro')->order("id desc")->select();
			S('home_arr',$home_arr,3600);
		}//全部内容缓存
		$kid=$_GET['kid'];
		foreach ($home_arr as $k => $v) {
				if($v['id']==$kid){
					$link=$v['menu_link'];
				}
			}
		$lk=$this->convertLink($link);
		QRcode::png($lk,false,1,11);
	}
	function home_ajax(){
		$zhu_id=$_POST['zhu_id'];
		if(S('home_arr')){
			$home_arr=S('home_arr');
		}else{
			$home_arr=M('Funintro')->order("id desc")->select();
			S('home_arr',$home_arr,3600);
		}//全部内容缓存
		if(S('top_arr')){
			$top_arr=S('top_arr');
		}else{
			$top_arr=M('Funclass')->order("id desc")->select();
			S('top_arr',$top_arr,3600);
		}//全部顶级栏目缓存
		if(S('top_jieri')){
			$top_jieri=S('top_jieri');
		}else{
			$top_jieri=M('Funclass_holi')->order("id desc")->select();
			S('top_jieri',$top_jieri,3600);
		}//全部节日栏目缓存
		foreach($top_arr as $k=>$v){
			if($v['id']==$zhu_id){
				$list=$v['list'];
			}
		}
		if($list != 1){//list！=1,不是节日营销导航
			foreach($home_arr as $k=>$v){
				if($v['classid']==$zhu_id){
					$right_caidan[]=$v;
				}
			}
			foreach($right_caidan as $k=>$v){
				if($k==0){//第1个加上圈圈样式
					$caidan_li.="<li  class='on right_dianji'  key='".$v['id']."'><i></i>".$v['title']."</li>";
					if(!empty($v['img1'])){
						$img_all.="<li> <img src='".$v['img1']."'/></li>";
						$one_point.="<i></i>";
					}
					if(!empty($v['img2'])){
						$img_all.="<li> <img src='".$v['img2']."'/></li>";
						$one_point.="<i></i>";
					}
					if(!empty($v['img3'])){
						$img_all.="<li> <img src='".$v['img3']."'/></li>";
						$one_point.="<i></i>";
					}
					if(!empty($v['img4'])){
						$img_all.="<li> <img src='".$v['img4']."'/></li>";
						$one_point.="<i></i>";
					}
					if(!empty($v['img5'])){
						$img_all.="<li> <img src='".$v['img5']."'/></li>";
						$one_point.="<i></i>";
					}
					$one_arr=$v;
				}else{
					$caidan_li.="<li class='right_dianji'   key='".$v['id']."'><i></i>".$v['title']."</li>";
				}
			}
			if(empty($img_all)){
				$img_all="<li> <img src='./tpl/Home/pigcms/common/images/step/wutu.png'/></li>";
				$one_point.="&nbsp;";
			}
			$data['li']=$caidan_li;
			$data['img_all']=$img_all;
			$data['one_point']=$one_point;
			$data['nick_title']=$one_arr['nick_title'];
			$data['desc']=$one_arr['desc'];
			$data['sce_content']=strip_tags(htmlspecialchars_decode($one_arr['sce_content']));
			$data['id']=$one_arr['id'];
			if(empty($one_arr['menu_link'])){
				$data['menu_link']=0;
			}else{
				$data['menu_link']=1;
			}
			$this->ajaxReturn($data,'JSON');

		}else{
			foreach($top_jieri as $k =>$v){
				if($k==0){
					$jr_id=$v['id'];
				}
			}
			foreach($home_arr as $k=>$v){
				if($v['holi_id']==$jr_id){//这个值为节日营销导航里面，横排导航第一个菜单的id
					$right_caidan[]=$v;
				}
			}
			foreach($right_caidan as $k=>$v){
				if($k==0){
					$caidan_li.="<li  class='on right_jr'  key='".$v['id']."'><i></i>".$v['title']."</li>";
					if(!empty($v['img1'])){
						$img_all.="<li> <img src='".$v['img1']."'/></li>";
						$one_point.="<i></i>";
					}
					if(!empty($v['img2'])){
						$img_all.="<li> <img src='".$v['img2']."'/></li>";
						$one_point.="<i></i>";
					}
					if(!empty($v['img3'])){
						$img_all.="<li> <img src='".$v['img3']."'/></li>";
						$one_point.="<i></i>";
					}
					if(!empty($v['img4'])){
						$img_all.="<li> <img src='".$v['img4']."'/></li>";
						$one_point.="<i></i>";
					}
					if(!empty($v['img5'])){
						$img_all.="<li> <img src='".$v['img5']."'/></li>";
						$one_point.="<i></i>";
					}
					$one_arr=$v;
				}else{
					$caidan_li.="<li class='right_jr'   key='".$v['id']."'><i></i>".$v['title']."</li>";
				}
			}
			if(empty($img_all)){
				$img_all="<li> <img src='./tpl/Home/pigcms/common/images/step/wutu.png'/></li>";
				$one_point.="&nbsp;";
			}
			$data['three']='3';
			$data['li']=$caidan_li;
			$data['img_all']=$img_all;
			$data['one_point']=$one_point;
			$data['nick_title']=$one_arr['nick_title'];
			$data['desc']=$one_arr['desc'];
			$data['sce_content']=strip_tags(htmlspecialchars_decode($one_arr['sce_content']));
			$data['id']=$one_arr['id'];
			if(empty($one_arr['menu_link'])){
				$data['menu_link']=0;
			}else{
				$data['menu_link']=1;
			}
			$this->ajaxReturn($data,'JSON');
		}
		//给一个默认进去后的页面,以及一个默认的列表
	}
	function four_ajax(){//第三层横排滑动，切换不同的默认首页，以及切换不同的列表
		$key_id=$_POST['key_id'];
		if(S('home_arr')){
			$home_arr=S('home_arr');
		}else{
			$home_arr=M('Funintro')->order("id desc")->select();
			S('home_arr',$home_arr,3600);
		}//读取缓存
		foreach($home_arr as $k=>$v){
			if($v['holi_id']==$key_id){
				$right_caidan[]=$v;
			}
		}
		foreach($right_caidan as $k=>$v){
			if($k=='0'){
				if(!empty($v['img1'])){
					$img_all.="<li> <img src='".$v['img1']."'/></li>";
					$one_point.="<i></i>";
				}
				if(!empty($v['img2'])){
					$img_all.="<li> <img src='".$v['img2']."'/></li>";
					$one_point.="<i></i>";
				}
				if(!empty($v['img3'])){
					$img_all.="<li> <img src='".$v['img3']."'/></li>";
					$one_point.="<i></i>";
				}
				if(!empty($v['img4'])){
					$img_all.="<li> <img src='".$v['img4']."'/></li>";
					$one_point.="<i></i>";
				}
				if(!empty($v['img5'])){
					$img_all.="<li> <img src='".$v['img5']."'/></li>";
					$one_point.="<i></i>";
				}
				$caidan_li.="<li  class='on right_jr'  key='".$v['id']."'><i></i>".$v['title']."</li>";
				$c_id=$v['id'];
				$nick_title=$v['nick_title'];
				$desc=$v['desc'];
				$sce_content=strip_tags(htmlspecialchars_decode($v['sce_content']));
				if(empty($v['menu_link'])){
					$menu_link=0;
				}else{
					$menu_link=1;
				}
			}else{
				$caidan_li.="<li class='right_jr'   key='".$v['id']."'><i></i>".$v['title']."</li>";
			}
		}
		if(empty($img_all)){
			$img_all="<li> <img src='./tpl/Home/pigcms/common/images/step/wutu.png'/></li>";
			$one_point.="&nbsp;";
		}
		$data['menu_link']=$menu_link;
		$data['img_all']=$img_all;
		$data['one_point']=$one_point;
		$data['id']=$c_id;
		$data['li']=$caidan_li;
		$data['nick_title']=$nick_title;
		$data['desc']=$desc;
		$data['sce_content']=$sce_content;
		$this->ajaxReturn($data,'JSON');
	}
	function five_ajax(){//节日营销右侧点击填充单页面
		$key_id=$_POST['key_id'];
		if(S('home_arr')){
			$home_arr=S('home_arr');
		}else{
			$home_arr=M('Funintro')->order("id desc")->select();
			S('home_arr',$home_arr,3600);
		}//读取缓存
		foreach($home_arr as $k=>$v){
			if($v['id']==$key_id){
				$one_arr['nick_title']=$v['nick_title'];
				$one_arr['desc']=$v['desc'];
				$one_arr['sce_content']=strip_tags(htmlspecialchars_decode($v['sce_content']));
				$one_arr['id']=$v['id'];
				if(!empty($v['img1'])){
					$one_arr['img_all'].="<li> <img src='".$v['img1']."'/></li>";
					$one_arr['one_point'].="<i></i>";
				}
				if(!empty($v['img2'])){
					$one_arr['img_all'].="<li> <img src='".$v['img2']."'/></li>";
					$one_arr['one_point'].="<i></i>";
				}
				if(!empty($v['img3'])){
					$one_arr['img_all'].="<li> <img src='".$v['img3']."'/></li>";
					$one_arr['one_point'].="<i></i>";
				}
				if(!empty($v['img4'])){
					$one_arr['img_all'].="<li> <img src='".$v['img4']."'/></li>";
					$one_arr['one_point'].="<i></i>";
				}
				if(!empty($v['img5'])){
					$one_arr['img_all'].="<li> <img src='".$v['img5']."'/></li>";
					$one_arr['one_point'].="<i></i>";
				}
				if(empty($v['menu_link'])){
					$one_arr['menu_link']=0;
				}else{
					$one_arr['menu_link']=1;
				}

			}
		}
		if(empty($one_arr['img_all'])){
			$one_arr['img_all']="<li> <img src='./tpl/Home/pigcms/common/images/step/wutu.png'/></li>";
			$one_arr['one_point']="&nbsp;";
		}
		$this->ajaxReturn($one_arr,'JSON');
	}
	function two_ajax(){//第二层右侧菜单点击后，切换不同单页面
		$key_id=$_POST['key_id'];
		if(S('home_arr')){
			$home_arr=S('home_arr');
		}else{
			$home_arr=M('Funintro')->order("id desc")->select();
			S('home_arr',$home_arr,3600);
		}
		foreach($home_arr as $k=>$v){
			if($v['id']==$key_id){
				$one_arr['id']=$v['id'];
				$one_arr['title']=$v['title'];
				$one_arr['nick_title']=$v['nick_title'];
				$one_arr['desc']=$v['desc'];
				$one_arr['sce_content']=strip_tags(htmlspecialchars_decode($v['sce_content']));
				if(empty($v['menu_link'])){
					$one_arr['menu_link']=0;
				}else{
					$one_arr['menu_link']=1;
				}
				if(!empty($v['img1'])){
					$one_arr['img_all'].="<li> <img src='".$v['img1']."'/></li>";
					$one_arr['one_point'].="<i></i>";
				}
				if(!empty($v['img2'])){
					$one_arr['img_all'].="<li> <img src='".$v['img2']."'/></li>";
					$one_arr['one_point'].="<i></i>";
				}
				if(!empty($v['img3'])){
					$one_arr['img_all'].="<li> <img src='".$v['img3']."'/></li>";
					$one_arr['one_point'].="<i></i>";
				}
				if(!empty($v['img4'])){
					$one_arr['img_all'].="<li> <img src='".$v['img4']."'/></li>";
					$one_arr['one_point'].="<i></i>";
				}
				if(!empty($v['img5'])){
					$one_arr['img_all'].="<li> <img src='".$v['img5']."'/></li>";
					$one_arr['one_point'].="<i></i>";
				}
			}
		}
		if(empty($one_arr['img_all'])){
			$one_arr['img_all']="<li> <img src='./tpl/Home/pigcms/common/images/step/wutu.png'/></li>";
			$one_arr['one_point']="&nbsp;";
		}
		$this->ajaxReturn($one_arr,'JSON');
	}
	function cache_ajax(){//清理缓存
		$id=$_POST['id'];
		if($id>0){
			S('home_arr',null);//内页内容全部缓存
			$home_arr=M('Funintro')->order("id desc")->select();
			S('home_arr',$home_arr,3600);

			S('top_arr',null);//第一层一级导航缓存
			$top_arr=M('Funclass')->order("id desc")->select();
			S('top_arr',$top_arr,3600);

			S('top_jieri',null);//第三层节日导航缓存
			$top_jieri=M('Funclass_holi')->order("id desc")->select();
			S('top_jieri',$top_jieri,3600);

			$this->ajaxReturn('1','JSON');
		}
	}
	public function verify(){
		Image::buildImageVerify(4,1,'png',0,28,'verify');
	}
	public function verifyLogin(){
		Image::buildImageVerify(4,1,'png',0,28,'loginverify');
	}
	public function resetpwd(){
		$uid=$this->_get('uid','intval');
		$code=$this->_get('code','trim');
		$rtime=$this->_get('resettime','intval');
		$info=M('Users')->find($uid);
		if( (md5($info['uid'].$info['password'].$info['email'])!==$code) || ($rtime<time()) ){
			$this->error('非法操作',U('Index/index'));
		}
		$this->assign('uid',$uid);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function fc(){
		if(C('server_topdomain')=='pigcms.cn'){
			$db=D('Classify');
			$pre=$db->where(array('fid'=>'1'))->order('sorts DESC')->select();
			$fid=array();
			foreach($pre as $sc){
		    	array_push($fid,$sc['id']);
		    }
		   	if($fid){
		   		$dbs=D('Img');
	      		$where['classid'] = array('in',$fid);
	      		$fun=$dbs->where($where)->order('usort DESC')->select();
	      	}
	      	$id=$this->_get('id','intval');
	      	if (!$id){
	      		$id=29549;
	      	}
			if (isset($id)){
				$fun=$dbs->where(array('id'=>$id,'type'=>0))->find();
				$fun['url']=$link=str_replace(array('{wechat_id}','{siteUrl}','&amp;','{changjingUrl}'),array($this->wecha_id,$this->siteUrl,'&','http://www.meihua.com'),$fun['url']);
			}else {
				$pid=$pre[0];
				if(empty($pid)){
					$pid=$pre[1];
				}
				$fun=$dbs->where(array('classid'=>$pid['id']))->order('id ASC')->find();
			}
			foreach($fun as $key=>$val){
				$fun['content'] = $fun['info'];
			}
			$where['classid'] = array('in',$fid);
			$funs=$dbs->where($where)->order('usort DESC')->field('id,title,classid')->select();
		}else{
			$db=D('Funclass');
			$where='';
			$pre=$db->where($where)->order('id ASC')->select();

			$id=$this->_get('id','intval');
			if (isset($id)){
				$fun=M('Funintro')->where(array('id'=>$id,'type'=>0))->find();
			}else {
				$pid=$pre[0];
				if(empty($pid)){
					$pid=$pre[1];
				}
				$fun=M('Funintro')->where(array('classid'=>$pid['id']))->order('id ASC')->find();
			}
			$funs=M('Funintro')->where('id>0 and type=0')->field('id,title,classid')->select();
			if(!empty($fun)){
				$fun['content'] = htmlspecialchars_decode($fun['content']);
			}
			if(!empty($funs)){
				$funs['content'] = htmlspecialchars_decode($funs['content']);
			}
		}

		$this->assign('funs',$funs);
		$this->assign('fun',$fun);
		$this->assign('id',$id);
		$this->assign('pre',$pre);
		$this->assign('title',$title);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function qrcode(){
		if (isset($_SESSION['preuid'])){
			$thisUser=M('Users')->where(array('id'=>intval($_SESSION['preuid'])))->find();
			$this->assign('thisUser',$thisUser);
			$this->display($this->home_theme.':Index:'.ACTION_NAME);
		}else {
			exit();
		}

	}
	public function qrcodeLogin(){
		if (isset($_SESSION['preuid'])){
			$thisUser=M('Users')->where(array('id'=>intval($_SESSION['preuid'])))->find();
			session('uid',$thisUser['id']);
			session('gid',$thisUser['gid']);
			session('uname',$thisUser['username']);
			session('diynum',0);
			session('connectnum',0);
			session('activitynum',0);
			//session('gname',$info['name']);
			//$this->success('现在进入体验',U('User/Index/bindTip'));
			$this->success('现在进入体验',U('User/Index/index'));
		}else {
			$this->success('超时，请联系客服审核账号',U('Home/Index/index'));
		}
	}
	public function isfollow(){
		$thisUser=M('Users')->where(array('id'=>intval($_SESSION['preuid'])))->find();
		echo '{"openid":"'.$thisUser['openid'].'"}';
	}
	public function about(){
		$agentid = $this->agentid;
		if($agentid == '0'){
			$fun=M('Funintro')->where('type=1')->find();
			if(!empty($fun)){
				$fun['content'] = html_entity_decode($fun['content']);
			}
		}else{
			$agent = M('Agent');
			$fun=$agent->where(array('id'=>$agentid))->find();
			if(!empty($fun)){
				$fun['content'] = html_entity_decode($fun['content']);
			}
		}
		$this->assign('fun',$fun);

		$id=$this->_get('iid','intval');
		$db=D('Img');
		$img = $db->where(array('id'=>$id))->find();
		if(!empty($img)){
			$img['info'] = html_entity_decode($img['info']);
		}
		$this->assign('img',$img);


		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function price(){
		$groupWhere=array();
		$groupWhere['status']=1;
		if (C('agent_version')){
			$groupWhere['agentid']=$this->agentid;
		}
		$groups=M('User_group')->where($groupWhere)->order('id ASC')->select();
		$this->assign('groups',$groups);
		$count=count($groups);
		$this->assign('count',$count);
		//
		$prices=array();
		$isCopyright=array();
		$wechatNums=array();
		$diynums=array();
		$connectnums=array();
		$activitynums=array();
		$create_card_nums=array();
		if ($groups){
			foreach ($groups as $g){
				array_push($prices,$g['price']);
				array_push($isCopyright,$g['iscopyright']);
				array_push($wechatNums,$g['wechat_card_num']);
				array_push($diynums,$g['diynum']);
				array_push($connectnums,$g['connectnum']);
				array_push($activitynums,$g['activitynum']);
				array_push($create_card_nums,$g['create_card_num']);
			}
		}
		$this->assign('prices',$prices);
		$this->assign('copyrights',$isCopyright);

		$this->assign('wechatNums',$wechatNums);
		$this->assign('diynums',$diynums);
		$this->assign('connectnums',$connectnums);
		$this->assign('activitynums',$activitynums);
		$this->assign('create_card_nums',$create_card_nums);
		//
		if(!ALI_FUWU_GROUP){
			$whe['funname']  = array('neq','Fuwu');
		}
		if (C('agent_version')&&$this->agentid){
			$whe['status']= '1';
			$whe['agentid'] = $this->agentid;
			$funs=M('Function')->where($whe)->order('id ASC')->select();
		}else {
			$whe['status']= '1';
			$funs=M('Function')->where($whe)->order('id ASC')->select();
		}
		if ($funs){

			foreach ($funs as $fk => $f){
				$funs[$fk]['access']=array();
				if ($groups){
					foreach ($groups as $g){
						$gs = explode(',',$g['func']);
						if(!in_array($f['funname'],$gs)){
							$canUse = 0;
						}else{
							$canUse = 1;
						}
						$funs[$fk]['access'][$g['id']] = $canUse;
					}
				}

			}
		}
		$this->assign('funs',$funs);
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function help(){
		if (isset($_GET['token'])){
			if (isset($_SESSION['uid'])){
				$thisWx=apiInfo::info($_SESSION['uid'],0,$this->_get('token'));

				$this->assign('wxuser',$thisWx);
			}else {
				$this->error('无权查看');
			}
		}
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	function think_encrypt($data, $key = '', $expire = 0) {
		$key  = md5(empty($key) ? C('DATA_AUTH_KEY') : $key);
		$data = base64_encode($data);
		$x    = 0;
		$len  = strlen($data);
		$l    = strlen($key);
		$char = '';

		for ($i = 0; $i < $len; $i++) {
			if ($x == $l) $x = 0;
			$char .= substr($key, $x, 1);
			$x++;
		}

		$str = sprintf('%010d', $expire ? $expire + time():0);

		for ($i = 0; $i < $len; $i++) {
			$str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1)))%256);
		}
		return str_replace('=', '',base64_encode($str));
	}
	function text(){
		$domain=$_GET['domain'];
		$domains=explode('.',$domain);

		echo '<a href="http://'.$domain.'/index.php?g=Home&m=T&a=test&n='.$this->think_encrypt($domains[1].'.'.$domains[2]).'" target="_blank">http://'.$domain.'/index.php?g=Home&m=T&a=test&n='.$this->think_encrypt($domains[1].'.'.$domains[2]).'</a><br>';
		echo '<a href="http://'.$domain.'/index.php?g=User&m=Create&a=index" target="_blank">http://'.$domain.'/index.php?g=User&m=Create&a=index</a><br>';
	}
	function common(){
		if(C('server_topdomain')=='pigcms.cn'){
			$db=D('Classify');
	    	$dbs=D('Img');
		    $class=$db->where(array('token'=>'yicms','fid'=>'1076407','status'=>1))->order('sorts DESC')->field('id,name')->select();
		    $casesID=array();
		    foreach ($class as $sc){
	      		array_push($casesID,$sc['id']);
	      	}
	      	$where['classid'] = array('in',$casesID);
	      	$where['token'] = 'yicms';
	      	$cases=$dbs->where($where)->field('id,title,classid,writer,pic,url')->order('usort DESC')->select();
		    $this->assign('cases',$cases);
		    $this->assign('class',$class);
		}else{
			$where['status']=1;
			if (C('agent_version')){
				$where['agentid']=$this->agentid;
			}
			$cases=M('Case')->where($where)->order('id DESC')->select();
			$this->assign('cases',$cases);

			$db=D('Caseclass');
			$class = $db->where($where)->select();
			$this->assign('class',$class);
		}
		$this->display($this->home_theme.':Index:'.ACTION_NAME);
	}
	public function caseJson(){
		$j=array();
		$db=D('Classify');
		$dbs=D('Img');
		$class=$db->where(array('token'=>'yicms','fid'=>'1076407','status'=>1))->order('sorts DESC')->field('id,name')->select();
		$casesID=array();
		foreach ($class as $sc){
			array_push($casesID,$sc['id']);
			$j[$sc['id']]=$sc;
		}
		$where['classid'] = array('in',$casesID);
		$where['token'] = 'yicms';
		$cases=$dbs->where($where)->field('id,title,classid,writer,pic,url')->order('usort DESC')->select();
		if ($cases){
			foreach ($cases as $c){
				if (!is_array($j[$c['classid']]['cases'])){
					$j[$c['classid']]['cases']=array();
				}
				array_push($j[$c['classid']]['cases'],$c);
			}
		}
		echo json_encode($j);
	}
	public function renewJson(){
		$db=D('Renew');
		$j=$db->where('id>0')->order('id DESC')->limit(5)->select();

		echo json_encode($j);
	}
	 public function userJson(){
    	if (C('server_topdomain')=='pigcms.cn'&&$this->_get('key')==C('server_key')){
    		$id=intval($_GET['id']);
    		$users=M('users')->where('id>'.$id)->order('id ASC')->limit(0,400)->select();
    		if ($users){
    			$i=0;
    			foreach ($users as $u){
    				unset($users[$i]['password']);
    				$i++;
    			}
    		}
    	}
    	echo json_encode($users);
    }


	public function login(){

		$business = include('./PigCms/Lib/ORG/Business.php');
		$i=0;
		foreach ($business as $k => $v){
			$data[$i]['key'] = $k;
			$data[$i]['val'] = $v;
			$i++;
		}
		$this->assign('business',$data);
		$this->display('login');
	}
	public function reg(){
		$business = include('./PigCms/Lib/ORG/Business.php');
		$i=0;
		foreach ($business as $k => $v){
			$data[$i]['key'] = $k;
			$data[$i]['val'] = $v;
			$i++;
		}
		$this->assign('business',$data);
		$this->display('reg');
	}
	/*
	public function printtest(){
		$op = new orderPrint();
		$op->printit($this->token, $this->_cid, 'Store', $msg, 0);
	}
	
	public function checkZend(){
		echo class_exists('checkFunc');
	}
	public function testpinyin(){
		$Pin=new GetPin();
		$string=$Pin->Pinyin('小萝莉');
		var_export($string);
	}
	
	public function up20150520(){
		echo 'success';
	}*/
	 public function version(){
    	$updateRecord=M('System_info')->order('lastsqlupdate DESC')->find();
    	echo json_encode($updateRecord);
    }
	//店员登陆
	public function staff_login(){
		if(session('role_name') == 'staff' && session('uid') != "" && session('staff_id') != "" && session('first_func') != "" && session('token') != ""){
			redirect(U('User/Function/welcome',array('id'=>session('wid'),'token'=>session('token'))));
		}
		$this->assign('token',$_GET['token']);
		$this->display('staff_login');
	}
}
?>