<?php
class LivingCircleAction extends UserAction{
	public function _initialize(){
		parent::_initialize();
		$this->canUseFunction("LivingCircle");
		$this->m_type = M("livingcircle_type");
		$this->m_sellcircle = M("livingcircle_sellcircle");
		$this->m_company = M("company");
		$this->m_seller = M("livingcircle_seller");
		$this->m_mysellergoods = M("livingcircle_mysellergoods");
		$this->m_mysellercart = M("livingcircle_mysellercart");
		$this->m_mysellerorder = M("livingcircle_mysellerorder");
		$this->m_livingcircle = M("livingcircle");
		$this->m_user = M("livingcircle_user");
		$this->m_comment = M("livingcircle_comment");
		$this->m_userinfo = M("userinfo");
		//判断是否商家登录
		if(session('companyLogin') == 1 && !in_array(ACTION_NAME, array('mysellercomment','mysellerorderstup','mysellerorder','myseller','sellerajax','domysellerupdate','mysellergoods','mysellergoodsadd','domysellergoodsadd','mysellergoodsupdate','domysellergoodsupdate','selleroperate'))){
			$this->redirect("LivingCircle/myseller",array("token"=>$this->token));
			exit;
		}
	}
	//生活圈基本信息
	public function index(){
		$where_livingcircle['token'] = $this->token;
		$find_livingcircle = $this->m_livingcircle->where($where_livingcircle)->find();
		if(empty($find_livingcircle)){
			$add_livingcircle['token'] = $this->token;
			$add_livingcircle['keyword'] = "生活圈";
			$add_livingcircle['wxtitle'] = "微信生活圈";
			$add_livingcircle['wxpic'] = $this->staticPath."/tpl/static/livingcircle/images/wxnewspic.jpg";
			$add_livingcircle['fistpic'] = $this->staticPath."/tpl/static/livingcircle/images/wxnewspic.jpg";
			$add_livingcircle['navpic'] = $this->staticPath."/tpl/static/livingcircle/images/bendidaohang.gif";
			$add_livingcircle['mysellerpic'] = $this->staticPath."/tpl/static/livingcircle/images/wodeshangjia.gif";
			$id_livingcircle = $this->m_livingcircle->add($add_livingcircle);
			$this->handleKeyword($id_livingcircle,'LivingCircle',"生活圈",0,0);
		}
		$where_livingcircle2['token'] = $this->token;
		$find_livingcircle2 = $this->m_livingcircle->where($where_livingcircle2)->find();
		$this->assign("livingcircle",$find_livingcircle2);
		$this->display();
	}
	//生活圈基本信息执行修改
	public function doupdate(){
		$where_livingcircle['token'] = $this->token;
		$find_livingcircle = $this->m_livingcircle->where($where_livingcircle)->find();
		$this->handleKeyword($find_livingcircle['imicms_id'],'LivingCircle',$_POST['keyword'],0,0);
		$save_livingcircle['keyword'] = $_POST['keyword'];
		$save_livingcircle['wxtitle'] = $_POST['wxtitle'];
		$save_livingcircle['wxpic'] = $_POST['wxpic'];
		$save_livingcircle['wxinfo'] = $_POST['wxinfo'];
		$save_livingcircle['fistpic'] = $_POST['fistpic'];
		$save_livingcircle['secondpic'] = $_POST['secondpic'];
		$save_livingcircle['thirdpic'] = $_POST['thirdpic'];
		$save_livingcircle['fourpic'] = $_POST['fourpic'];
		$save_livingcircle['fivepic'] = $_POST['fivepic'];
		$save_livingcircle['sixpic'] = $_POST['sixpic'];
		$save_livingcircle['navpic'] = $_POST['navpic'];
		$save_livingcircle['mysellerpic'] = $_POST['mysellerpic'];
		$update_livingcircle = $this->m_livingcircle->where($where_livingcircle)->save($save_livingcircle);
		$this->success("修改成功",U("LivingCircle/index",array("token"=>$this->token)));
	}
	//分类首页
	public function type(){
		$where_type['token'] = $this->token;
		$where_type['typeid'] = 0;
		$where_page['token'] = $this->token;
		if(!empty($_GET['name'])){
			$where_type['name'] = array("like","%".$_GET['name']."%");
			$where_page['name'] = $_GET['name'];
		}
		import('ORG.Util.Page');
		$count = $this->m_type->where($where_type)->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$type_list = $this->m_type->where($where_type)->order("num asc,addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$show);
		$this->assign("type_list",$type_list);
		$this->display();
	}
	//分类添加
	public function typeadd(){
		
		$this->display();
	}
	//分类执行添加
	public function dotypeadd(){
		$where_type['name'] = $_POST['name'];
		$where_type['token'] = $this->token;
		$find_type = $this->m_type->where($where_type)->find();
		if($find_type != null){
			$this->error("分类名称重复");
			exit;
		}else{
			$add_type['name'] = $_POST['name'];
			$add_type['pic'] = $_POST['pic'];
			$add_type['fistpic'] = $_POST['fistpic'];
			$add_type['secondpic'] = $_POST['secondpic'];
			$add_type['thirdpic'] = $_POST['thirdpic'];
			$add_type['fourpic'] = $_POST['fourpic'];
			$add_type['fivepic'] = $_POST['fivepic'];
			$add_type['sixpic'] = $_POST['sixpic'];
			$add_type['num'] = $_POST['num'];
			$add_type['display'] = $_POST['display'];
			$add_type['token'] = $this->token;
			$add_type['addtime'] = time();
			$id_type = $this->m_type->add($add_type);
			if($id_type > 0){
				$this->success("添加分类【".$_POST['name']."】成功",U("LivingCircle/type",array("token"=>$this->token)));
			}
		}
	}
	//分类修改
	public function typeupdate(){
		$where_type['token'] = $this->token;
		$where_type['imicms_id'] = $_GET['typeid'];
		$find_type = $this->m_type->where($where_type)->find();
		if(empty($find_type)){
			$this->error("没有这个分类",U("LivingCircle/type",array("token"=>$this->token)));
			exit;
		}
		$this->assign("type",$find_type);
		$this->display();
	}
	//分类执行修改
	public function dotypeupdate(){
		$where_type_name['token'] = $this->token;
		$where_type_name['name'] = $_POST['name'];
		$where_type_name['imicms_id'] = array("neq",(int)($_POST['typeid']));
		$find_type_name = $this->m_type->where($where_type_name)->find();
		if($find_type_name != null){
			$this->error("分类名称重复");
			exit;
		}else{
			$where_type['token'] = $this->token;
			$where_type['imicms_id'] = (int)($_POST['typeid']);
			$save_type['name'] = $_POST['name'];
			$save_type['pic'] = $_POST['pic'];
			$save_type['fistpic'] = $_POST['fistpic'];
			$save_type['secondpic'] = $_POST['secondpic'];
			$save_type['thirdpic'] = $_POST['thirdpic'];
			$save_type['fourpic'] = $_POST['fourpic'];
			$save_type['fivepic'] = $_POST['fivepic'];
			$save_type['sixpic'] = $_POST['sixpic'];
			$save_type['num'] = $_POST['num'];
			$save_type['display'] = $_POST['display'];
			$update_type = $this->m_type->where($where_type)->save($save_type);
			//$this->redirect("LivingCircle/index",array("token"=>$this->token));
			$this->success("修改成功",U("LivingCircle/type",array("token"=>$this->token)));
		}
	}
	//子分类首页
	public function zitype(){
		$where_type['token'] = $this->token;
		$where_type['imicms_id'] = (int)($_GET['typeid']);
		$find_type = $this->m_type->where($where_type)->find();
		if(empty($find_type)){
			$this->error("没有这个分类",U("LivingCircle/type",array("token"=>$this->token)));
			exit;
		}
		$this->assign("type",$find_type);
		$where_type_zi['token'] = $this->token;
		$where_type_zi['typeid'] = (int)($_GET['typeid']);
		$where_page['token'] = $this->token;
		$where_page['typeid'] = (int)($_GET['typeid']);
		if(!empty($_GET['name'])){
			$where_type_zi['name'] = array("like","%".$_GET['name']."%");
			$where_page['name'] = $_GET['name'];
		}
		import('ORG.Util.Page');
		$count = $this->m_type->where($where_type_zi)->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$type_zi_list = $this->m_type->where($where_type_zi)->order("num asc,addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign("page",$show);
		$this->assign("zitype_list",$type_zi_list);
		$this->display();
	}
	//子分类添加
	public function zitypeadd(){
		$where_type['token'] = $this->token;
		$where_type['imicms_id'] = (int)($_GET['typeid']);
		$find_type = $this->m_type->where($where_type)->find();
		if(empty($find_type)){
			$this->error("没有这个分类",U("LivingCircle/type",array("token"=>$this->token)));
			exit;
		}
		$this->assign("type",$find_type);
		$this->display();
	}
	//子分类执行添加
	public function dozitypeadd(){
		$where_type['name'] = $_POST['name'];
		$where_type['token'] = $this->token;
		$find_type = $this->m_type->where($where_type)->find();
		if($find_type != null){
			$this->error("分类名称重复");
			exit;
		}else{
			$add_type['name'] = $_POST['name'];
			$add_type['pic'] = $_POST['pic'];
			$add_type['num'] = $_POST['num'];
			$add_type['display'] = $_POST['display'];
			$add_type['typeid'] = $_POST['typeid'];
			$add_type['token'] = $this->token;
			$add_type['addtime'] = time();
			$id_type = $this->m_type->add($add_type);
			if($id_type > 0){
				$this->success("添加分类【".$this->m_type->where(array("imicms_id"=>$_POST['typeid']))->getField("name")."】的子分类【".$_POST['name']."】成功",U("LivingCircle/zitype",array("token"=>$this->token,"typeid"=>(int)($_POST['typeid']))));
			}
		}
	}
	//子分类修改
	public function zitypeupdate(){
		$where_type['imicms_id'] = (int)($_GET['typeid']);
		$where_type['token'] = $this->token;
		$find_type = $this->m_type->where($where_type)->find();
		if(empty($find_type)){
			$this->error("没有这个分类",U("LivingCircle/type",array("token"=>$this->token)));
			exit;
		}
		$this->assign("type",$find_type);
		$where_type_zi['imicms_id'] = (int)($_GET['zitypeid']);
		$where_type_zi['token'] = $this->token;
		$find_type_zi = $this->m_type->where($where_type_zi)->find();
		if(empty($find_type_zi)){
			$this->error("没有这个分类",U("LivingCircle/type",array("token"=>$this->token)));
			exit;
		}
		$this->assign("zitype",$find_type_zi);
		$this->display();
	}
	//子分类执行修改
	public function dozitypeupdate(){
		$where_type['name'] = $_POST['name'];
		$where_type['token'] = $this->token;	
		$where_type['imicms_id'] =array("neq",(int)($_POST['zitypeid']));
		$find_type = $this->m_type->where($where_type)->find();
		if($find_type != null){
			$this->error("分类名称重复");
			exit;
		}else{
			$where_type_zi['token'] = $this->token;
			$where_type_zi['imicms_id'] = (int)($_POST['zitypeid']);
			$save_type_zi['name'] = $_POST['name'];
			$save_type_zi['pic'] = $_POST['pic'];
			$save_type_zi['num'] = $_POST['num'];
			$save_type_zi['display'] = $_POST['display'];
			$update_type_zi = $this->m_type->where($where_type_zi)->save($save_type_zi);
			$this->success("修改成功",U("LivingCircle/zitype",array("token"=>$this->token,"typeid"=>$_POST['typeid'])));
		}
	}
	//商圈首页
	public function sellcircle(){
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['sellcircleid'] = 0;
		$where_page['token'] = $this->token;
		if(!empty($_GET['name'])){
			$where_sellcircle['name'] = array("like","%".$_GET['name']."%");
			$where_page['name'] = $_GET['name'];
		}
		import('ORG.Util.Page');
		$count = $this->m_sellcircle->where($where_sellcircle)->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$sellcircle_list = $this->m_sellcircle->where($where_sellcircle)->order("num asc,addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$show);
		$this->assign("sellcircle_list",$sellcircle_list);
		$this->display();
	}
	//商圈添加
	public function sellcircleadd(){
		
		$this->display();
	}
	//商圈执行添加
	public function dosellcircleadd(){
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['name'] = $_POST['name'];
		$find_sellcircle = $this->m_sellcircle->where($where_sellcircle)->find();
		if(!empty($find_sellcircle)){
			$this->error("商圈名称重复");
			exit;
		}else{
			$add_sellcircle['token'] = $this->token;
			$add_sellcircle['name'] = $_POST['name'];
			$add_sellcircle['num'] = $_POST['num'];
			$add_sellcircle['display'] = $_POST['display'];
			$add_sellcircle['addtime'] = time();
			$id_sellcircle = $this->m_sellcircle->add($add_sellcircle);
			if($id_sellcircle > 0){
				$this->success("添加成功",U("LivingCircle/sellcircle",array("token"=>$this->token)));
			}
		}
	}
	//商圈修改
	public function sellcircleupdate(){
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['imicms_id'] = (int)($_GET['sellcircleid']);
		$find_sellcircle = $this->m_sellcircle->where($where_sellcircle)->find();
		if(empty($find_sellcircle)){
			$this->error("没有这个商圈",U("LivingCircle/sellcircle",array("token"=>$this->token)));
			exit;
		}
		$this->assign("sellcircle",$find_sellcircle);
		$this->display();
	}
	//商圈执行修改
	public function dosellcircleupdate(){
		$where_sellcircle_name['token'] = $this->token;
		$where_sellcircle_name['name'] = $_POST['name'];
		$where_sellcircle_name['imicms_id'] = array("neq",(int)($_POST['sellcircleid']));
		$find_sellcircle_name = $this->m_sellcircle->where($where_sellcircle_name)->find();
		if(!empty($find_sellcircle_name)){
			$this->error("商圈名称重复");
			exit;
		}
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['imicms_id'] = (int)($_POST['sellcircleid']);
		$save_sellcircle['name'] = $_POST['name'];
		$save_sellcircle['num'] = $_POST['num'];
		$save_sellcircle['display'] = $_POST['display'];
		$update_sellcircle = $this->m_sellcircle->where($where_sellcircle)->save($save_sellcircle);
		$this->success("修改成功",U("LivingCircle/sellcircle",array("token"=>$this->token)));
	}
	//子商圈首页
	public function zisellcircle(){
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['imicms_id'] = (int)($_GET['sellcircleid']);
		$find_sellcircle = $this->m_sellcircle->where($where_sellcircle)->find();
		if(empty($find_sellcircle)){
			$this->error("没有这个商圈",U("LivingCircle/sellcircle",array("token"=>$this->token)));
			exit;
		}
		$this->assign("sellcircle",$find_sellcircle);
		$where_sellcircle_zi['token'] = $this->token;
		$where_sellcircle_zi['sellcircleid'] = (int)($_GET['sellcircleid']);
		$where_page['token'] = $this->token;
		$where_page['sellcircleid'] = (int)($_GET['sellcircleid']);
		if(!empty($_GET['name'])){
			$where_sellcircle_zi['name'] = array("like","%".$_GET['name']."%");
			$where_page['name'] = $_GET['name'];
		}
		import('ORG.Util.Page');
		$count = $this->m_sellcircle->where($where_sellcircle_zi)->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$zisellcircle_list = $this->m_sellcircle->where($where_sellcircle_zi)->order("num asc,addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign("page",$show);
		$this->assign("zisellcircle_list",$zisellcircle_list);
		$this->display();
	}
	//子商圈添加
	public function zisellcircleadd(){
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['imicms_id'] = (int)($_GET['sellcircleid']);
		$find_sellcircle = $this->m_sellcircle->where($where_sellcircle)->find();
		if(empty($find_sellcircle)){
			$this->error("没有这个商圈",U("LivingCircle/sellcircle",array("token"=>$this->token)));
			exit;
		}
		$this->assign("sellcircle",$find_sellcircle);
		$this->display();
	}
	//子商圈执行添加
	public function dozisellcircleadd(){
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['name'] = $_POST['name'];
		$find_sellcircle = $this->m_sellcircle->where($where_sellcircle)->find();
		if(!empty($find_sellcircle)){
			$this->error("商圈名称重复");
			exit;
		}
		$add_zisellcircle['token'] = $this->token;
		$add_zisellcircle['name'] = $_POST['name'];
		$add_zisellcircle['num'] = $_POST['num'];
		$add_zisellcircle['display'] = $_POST['display'];
		$add_zisellcircle['sellcircleid'] = (int)($_POST['sellcircleid']);
		$add_zisellcircle['addtime'] = time();
		$id_zisellcircle = $this->m_sellcircle->add($add_zisellcircle);
		if($id_zisellcircle > 0){
			$this->success("添加成功",U("LivingCircle/zisellcircle",array("token"=>$this->token,"sellcircleid"=>$_POST['sellcircleid'])));
		}
	}
	//子商圈修改
	public function zisellcircleupdate(){
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['imicms_id'] = (int)($_GET['sellcircleid']);
		$find_sellcircle = $this->m_sellcircle->where($where_sellcircle)->find();
		if(empty($find_sellcircle)){
			$this->error("没有这个商圈",U("LivingCircle/sellcircle",array("token"=>$this->token)));
			exit;
		}
		$where_sellcircle_zi['token'] = $this->token;
		$where_sellcircle_zi['imicms_id'] = (int)($_GET['zisellcircleid']);
		$find_sellcircle_zi = $this->m_sellcircle->where($where_sellcircle_zi)->find();
		if(empty($find_sellcircle_zi)){
			$this->error("没有这个商圈",U("LivingCircle/sellcircle",array("token"=>$this->token)));
			exit;
		}
		$this->assign("sellcircle",$find_sellcircle);
		$this->assign("zisellcircle",$find_sellcircle_zi);
		$this->display();
	}
	//子商圈执行修改
	public function dozisellcircleupdate(){
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['name'] = $_POST['name'];
		$where_sellcircle['imicms_id'] = array("neq",(int)($_POST['zisellcircleid']));
		$find_sellcircle = $this->m_sellcircle->where($where_sellcircle)->find();
		if(!empty($find_sellcircle)){
			$this->error("商圈名称重复");
			exit;
		}
		$where_sellcircle_zi['token'] = $this->token;
		$where_sellcircle_zi['imicms_id'] = (int)($_POST['zisellcircleid']);
		$save_sellcircle_zi['name'] = $_POST['name'];
		$save_sellcircle_zi['num'] = $_POST['num'];
		$save_sellcircle_zi['display'] = $_POST['display'];
		$update_sellcircle_zi = $this->m_sellcircle->where($where_sellcircle_zi)->save($save_sellcircle_zi);
		$this->success("修改成功",U("LivingCircle/zisellcircle",array("token"=>$this->token,"sellcircleid"=>$_POST['sellcircleid'])));
	}
	//商家首页
	public function seller(){
		$where_seller['token'] = $this->token;
		$where_page['token'] = $this->token;
		if(!empty($_GET['name'])){
			$where_seller['name'] = array("like","%".$_GET['name']."%");
			$where_page['name'] = $_GET['name'];
		}
		import('ORG.Util.Page');
		$count = $this->m_seller->where($where_seller)->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$seller_list = $this->m_seller->where($where_seller)->order("num asc,addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		foreach($seller_list as $k=>$vo){
			$where_company['token'] = $this->token;
			$where_company['id'] = $vo['cid'];
			$find_company = $this->m_company->where($where_company)->find();
			if($find_company['tel'] == null){
				$seller_list[$k]['phone'] = $find_company['mp'];
			}elseif($find_company['mp'] == null){
				$seller_list[$k]['phone'] = $find_company['tel'];
			}else{
				$seller_list[$k]['phone'] = $find_company['mp'];
			}
			$seller_list[$k]['logourl'] = $find_company['logourl'];
			$seller_list[$k]['display'] = $find_company['display'];
			$seller_list[$k]['loginurl'] = $_SERVER['HTTP_HOST'] . '/index.php?m=Index&a=clogin&cid=' . $vo['cid'] . '&k=' . md5($vo['cid'] . $find_company['username']);
		}
		$this->assign('page',$show);
		$this->assign("seller_list",$seller_list);
		$this->display();
	}
	//商家添加
	public function selleradd(){
		$where_type['token'] = $this->token;
		$where_type['typeid'] = 0;
		$type_list = $this->m_type->where($where_type)->order("num asc,addtime desc")->select();
		$this->assign("type_list",$type_list);
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['sellcircleid'] = 0;
		$sellcircle_list = $this->m_sellcircle->where($where_sellcircle)->order("num asc,addtime desc")->select();
		$this->assign("sellcircle_list",$sellcircle_list);
		$this->display();
	}
	//商家执行添加
	public function doselleradd(){
		//分支结构添加
		$add_company['token'] = $this->token;
		$add_company['display'] = $_POST['display'];
		$add_company['name'] = $_POST['name'];
		$add_company['username'] = $_POST['username'];
		$add_company['password'] = md5($_POST['password']);
		$add_company['shortname'] = $_POST['shortname'];
		$add_company['mp'] = $_POST['mp'];
		$add_company['tel'] = $_POST['tel'];
		$add_company['address'] = $_POST['address'];
		$add_company['latitude'] = $_POST['latitude'];
		$add_company['longitude'] = $_POST['longitude'];
		$add_company['intro'] = $_POST['intro'];
		$add_company['logourl'] = $_POST['logourl'];
		$add_company['taxis'] = $_POST['taxis'];
		$add_company['add_time'] = time();
		$add_company['isbranch'] = 1;
		$id_company = $this->m_company->add($add_company);
		//商家带分支id添加更多数据
		if($id_company > 0){
			$add_seller['token'] = $this->token;
			$add_seller['cid'] = $id_company;
			$add_seller['typeid'] = (int)($_POST['type']);
			$add_seller['zitypeid'] = (int)($_POST['zitype']);
			$add_seller['sellcircleid'] = (int)($_POST['sellcircle']);
			$add_seller['zisellcircleid'] = (int)($_POST['zisellcircle']);
			$add_seller['name'] = $_POST['name'];
			$add_seller['address'] = $_POST['address'];
			$add_seller['num'] = $_POST['taxis'];
			$add_seller['fistpic'] = $_POST['fistpic'];
			$add_seller['secondpic'] = $_POST['secondpic'];
			$add_seller['thirdpic'] = $_POST['thirdpic'];
			$add_seller['fourpic'] = $_POST['fourpic'];
			$add_seller['fivepic'] = $_POST['fivepic'];
			$add_seller['sixpic'] = $_POST['sixpic'];
			$add_seller['qrcode'] = $_POST['qrcode'];
			$add_seller['weurl'] = $_POST['weurl'];
			$add_seller['recommend'] = $_POST['recommend'];
			$add_seller['addtime'] = time();
			$id_seller = $this->m_seller->add($add_seller);
			if($id_seller > 0){
				$this->success("添加成功",U("LivingCircle/seller",array("token"=>$this->token)));
			}
		}
	}
	//商家修改
	public function sellerupdate(){
		$where_seller['token'] = $this->token;
		$where_seller['imicms_id'] = (int)($_GET['sellerid']);
		$find_seller = $this->m_seller->where($where_seller)->find();
		$where_company['token'] = $this->token;
		$where_company['id'] = $find_seller['cid'];
		$find_company = $this->m_company->where($where_company)->find();
		$this->assign("seller",$find_seller);
		$this->assign("company",$find_company);
		$where_type['token'] = $this->token;
		$where_type['typeid'] = 0;
		$type_list = $this->m_type->where($where_type)->select();
		if($find_seller['typeid'] != 0){
			$where_zitype['token'] = $this->token;
			$where_zitype['typeid'] = $find_seller['typeid'];
			$zitype_list = $this->m_type->where($where_zitype)->select();
		}
		$this->assign("type_list",$type_list);
		$this->assign("zitype_list",$zitype_list);
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['sellcircleid'] = 0;
		$sellcircle_list = $this->m_sellcircle->where($where_sellcircle)->select();
		if($find_seller['sellcircleid'] != 0){
			$where_zisellcircle['token'] = $this->token;
			$where_zisellcircle['sellcircleid'] = $find_seller['sellcircleid'];
			$zisellcircle_list = $this->m_sellcircle->where($where_zisellcircle)->select();
		}
		$this->assign("sellcircle_list",$sellcircle_list);
		$this->assign("zisellcircle_list",$zisellcircle_list);
		$this->display();
	}
	//商家执行修改
	public function dosellerupdate(){
		//分支结构修改
		$where_company['token'] = $this->token;
		$where_company['id'] = (int)($_POST['companyid']);
		$find_company = $this->m_company->where($where_company)->find();
		$save_company['display'] = $_POST['display'];
		$save_company['name'] = $_POST['name'];
		$save_company['username'] = $_POST['username'];
		if($_POST['password'] == ''){
			$save_company['password'] = $find_company['password'];
		}else{
			$save_company['password'] = md5($_POST['password']);
		}
		$save_company['shortname'] = $_POST['shortname'];
		$save_company['mp'] = $_POST['mp'];
		$save_company['tel'] = $_POST['tel'];
		$save_company['address'] = $_POST['address'];
		$save_company['latitude'] = $_POST['latitude'];
		$save_company['longitude'] = $_POST['longitude'];
		$save_company['intro'] = $_POST['intro'];
		$save_company['logourl'] = $_POST['logourl'];
		$save_company['taxis'] = $_POST['taxis'];
		$update_company = $this->m_company->where($where_company)->save($save_company);
		//商家带分支id修改更多数据
		$save_seller['typeid'] = (int)($_POST['type']);
		$save_seller['zitypeid'] = (int)($_POST['zitype']);
		$save_seller['sellcircleid'] = (int)($_POST['sellcircle']);
		$save_seller['zisellcircleid'] = (int)($_POST['zisellcircle']);
		$save_seller['name'] = $_POST['name'];
		$save_seller['address'] = $_POST['address'];
		$save_seller['num'] = $_POST['taxis'];
		$save_seller['fistpic'] = $_POST['fistpic'];
		$save_seller['secondpic'] = $_POST['secondpic'];
		$save_seller['thirdpic'] = $_POST['thirdpic'];
		$save_seller['fourpic'] = $_POST['fourpic'];
		$save_seller['fivepic'] = $_POST['fivepic'];
		$save_seller['sixpic'] = $_POST['sixpic'];
		$save_seller['qrcode'] = $_POST['qrcode'];
		$save_seller['weurl'] = $_POST['weurl'];
		$save_seller['recommend'] = $_POST['recommend'];
		$where_seller['token'] = $this->token;
		$where_seller['imicms_id'] = (int)($_POST['sellerid']);
		$update_seller = $this->m_seller->where($where_seller)->save($save_seller);
		
		$this->success("修改成功",U("LivingCircle/seller",array("token"=>$this->token)));
	}
	//操作执行
	public function operate(){
		switch($_GET['type']){
			//分类删除
			case 'typedel':
				$where_seller['token'] = $this->token;
				$where_seller['typeid'] = (int)($_GET['typeid']);
				$save_seller['typeid'] = 0;
				$save_seller['zitypeid'] = 0;
				$update_seller = $this->m_seller->where($where_seller)->save($save_seller);
				$where_type['token'] = $this->token;
				$where_type['imicms_id'] = (int)($_GET['typeid']);
				$del_type = $this->m_type->where($where_type)->delete();
				$where_type_zi['token'] = $this->token;
				$where_type_zi['typeid'] = (int)($_GET['typeid']);
				$del_type_zi = $this->m_type->where($where_type_zi)->delete();
				$this->success("删除成功",U("LivingCircle/type",array("token"=>$this->token)));
			break;
			//子分类删除
			case 'zitypedel':
				$where_seller['token'] = $this->token;
				$where_seller['zitypeid'] = (int)($_GET['zitypeid']);
				$save_seller['zitypeid'] = 0;
				$update_seller = $this->m_seller->where($where_seller)->save($save_seller);
				$where_type['token'] = $this->token;
				$where_type['imicms_id'] = (int)($_GET['zitypeid']);
				$del_type = $this->m_type->where($where_type)->delete();
				$this->success("删除成功",U("LivingCircle/zitype",array("token"=>$this->token,"typeid"=>$_GET['typeid'])));
			break;
			//商圈删除
			case 'sellcircledel':
				$where_seller['token'] = $this->token;
				$where_seller['sellcircleid'] = (int)($_GET['sellcircleid']);
				$save_seller['sellcircleid'] = 0;
				$save_seller['zisellcircleid'] = 0;
				$update_seller = $this->m_seller->where($where_seller)->save($save_seller);
				$where_sellcircle['token'] = $this->token;
				$where_sellcircle['imicms_id'] = (int)($_GET['sellcircleid']);
				$del_sellcircle = $this->m_sellcircle->where($where_sellcircle)->delete();
				$where_sellcircle_zi['token'] = $this->token;
				$where_sellcircle_zi['sellcircleid'] = (int)($_GET['sellcircleid']);
				$del_sellcircle_zi = $this->m_sellcircle->where($where_sellcircle_zi)->delete();
				$this->success("删除成功",U("LivingCircle/sellcircle",array("token"=>$this->token)));
			break;
			//子商圈删除
			case 'zisellcircledel':
				$where_seller['token'] = $this->token;
				$where_seller['zisellcircleid'] = (int)($_GET['zisellcircleid']);
				$save_seller['zisellcircleid'] = 0;
				$update_seller = $this->m_seller->where($where_seller)->save($save_seller);
				$where_sellcircle['token'] = $this->token;
				$where_sellcircle['imicms_id'] = (int)($_GET['zisellcircleid']);
				$del_sellcircle = $this->m_sellcircle->where($where_sellcircle)->delete();
				$this->success("删除成功",U("LivingCircle/zisellcircle",array("token"=>$this->token,"sellcircleid"=>$_GET['sellcircleid'])));
			break;
			//商家删除
			case 'sellerdel':
				$where_seller['token'] = $this->token;
				$where_seller['imicms_id'] = (int)($_GET['sellerid']);
				$find_seller = $this->m_seller->where($where_seller)->find();
				$where_company['token'] = $this->token;
				$where_company['id'] = $find_seller['cid'];
				$del_company = $this->m_company->where($where_company)->delete();
				$del_seller = $this->m_seller->where($where_seller)->delete();
				$this->success("删除成功",U("LivingCircle/seller",array("token"=>$this->token)));
			break;
		}
	}
	//ajax
	public function ajax(){
		switch($_POST['type']){
			//分类是否显示操作
			case 'typedisplay':
				$where_type['token'] = $_POST['token'];
				$where_type['imicms_id'] = (int)($_POST['id']);
				$display = $this->m_type->where($where_type)->getField("display");
				if($display == 1){
					$save_type['display'] = 0;
					$data['error'] = 0;
				}else{
					$save_type['display'] = 1;
					$data['error'] = 1;
				}
				$update_type = $this->m_type->where($where_type)->save($save_type);
				$this->ajaxReturn($data,'JSON');
			break;
			//商圈是否显示操作
			case 'sellcircledisplay':
				$where_sellcircle['token'] = $_POST['token'];
				$where_sellcircle['imicms_id'] = (int)($_POST['id']);
				$display = $this->m_sellcircle->where($where_sellcircle)->getField("display");
				if($display == 1){
					$save_sellcircle['display'] = 0;
					$data['error'] = 0;
				}else{
					$save_sellcircle['display'] = 1;
					$data['error'] = 1;
				}
				$update_sellcircle = $this->m_sellcircle->where($where_sellcircle)->save($save_sellcircle);
				$this->ajaxReturn($data,'JSON');
			break;
			//商家是否显示操作
			case 'sellerdisplay':
				$where_company['token'] = $_POST['token'];
				$where_company['id'] = (int)($_POST['id']);
				$display = $this->m_company->where($where_company)->getField("display");
				if($display == 1){
					$save_company['display'] = 0;
					$data['error'] = 0;
				}else{
					$save_company['display'] = 1;
					$data['error'] = 1;
				}
				$update_company = $this->m_company->where($where_company)->save($save_company);
				$this->ajaxReturn($data,'JSON');
			break;
			//商家是否被推荐
			case 'sellerrecommend':
				$where_seller['token'] = $_POST['token'];
				$where_seller['imicms_id'] = (int)($_POST['id']);
				$recommend = $this->m_seller->where($where_seller)->getField("recommend");
				if($recommend == 1){
					$save_seller['recommend'] = 0;
					$data['error'] = 0;
				}else{
					$save_seller['recommend'] = 1;
					$data['error'] = 1;
				}
				$update_seller = $this->m_seller->where($where_seller)->save($save_seller);
				$this->ajaxReturn($data,'JSON');
			break;
			//获取子分类选择列表
			case 'zitype':
				$where_zitype['token'] = $_POST['token'];
				$where_zitype['typeid'] = $_POST['typeid'];
				$zitype_list = $this->m_type->where($where_zitype)->select();
				$list = '<option value=\'\'>--请选择子分类--</option>';
				foreach($zitype_list as $vo){
					$list.='<option value=\''.$vo['imicms_id'].'\'>'.$vo['name'].'</option>';
				}
				$data['list'] = $list;
				if($zitype_list == ''){
					$data['error'] = 0;
				}else{
					$data['error'] = 1;
				}
				$this->ajaxReturn($data,'JSON');
			break;
			//获取子商圈选择列表
			case 'zisellcircle':
				$where_zisellcircle['token'] = $_POST['token'];
				$where_zisellcircle['sellcircleid'] = $_POST['sellcircleid'];
				$zisellcircle_list = $this->m_sellcircle->where($where_zisellcircle)->select();
				$list = '<option value=\'\'>--请选择子商圈--</option>';
				foreach($zisellcircle_list as $vo){
					$list.='<option value=\''.$vo['imicms_id'].'\'>'.$vo['name'].'</option>';
				}
				$data['list'] = $list;
				if($zisellcircle_list == ''){
					$data['error'] = 0;
				}else{
					$data['error'] = 1;
				}
				$this->ajaxReturn($data,'JSON');
			break;
		}
	}
	/*
***********************************************************************************************************商家登录***********************************************************************************************************
	*/
	//商家登录-商家信息
	public function myseller(){
		$cid = session("companyid");
		$where_seller['cid'] = $cid;
		$where_seller['token'] = $this->token;
		$find_seller = $this->m_seller->where($where_seller)->find();
		$where_company['token'] = $this->token;
		$where_company['id'] = $find_seller['cid'];
		$find_company = $this->m_company->where($where_company)->find();
		$this->assign("seller",$find_seller);
		$this->assign("company",$find_company);
		$where_type['token'] = $this->token;
		$where_type['typeid'] = 0;
		$type_list = $this->m_type->where($where_type)->select();
		$where_zitype['token'] = $this->token;
		$where_zitype['typeid'] = $find_seller['typeid'];
		$zitype_list = $this->m_type->where($where_zitype)->select();
		$this->assign("type_list",$type_list);
		$this->assign("zitype_list",$zitype_list);
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['sellcircleid'] = 0;
		$sellcircle_list = $this->m_sellcircle->where($where_sellcircle)->select();
		$where_zisellcircle['token'] = $this->token;
		$where_zisellcircle['sellcircleid'] = $find_seller['sellcircleid'];
		$zisellcircle_list = $this->m_sellcircle->where($where_zisellcircle)->select();
		$this->assign("sellcircle_list",$sellcircle_list);
		$this->assign("zisellcircle_list",$zisellcircle_list);
		$this->display();
	}
	//商家登录-商家信息修改
	public function domysellerupdate(){
		$cid = session("companyid");
		$where_seller['cid'] = $cid;
		$where_seller['token'] = $this->token;
		$where_company['id'] = $cid;
		$where_company['token'] = $this->token;
		$save_company['name'] = $_POST['name'];
		$save_company['shortname'] = $_POST['shortname'];
		$save_company['tel'] = $_POST['tel'];
		$save_company['mp'] = $_POST['mp'];
		$save_company['address'] = $_POST['address'];
		$save_company['logourl'] = $_POST['logourl'];
		$save_company['longitude'] = $_POST['longitude'];
		$save_company['latitude'] = $_POST['latitude'];
		$save_company['intro'] = $_POST['intro'];
		$update_company = $this->m_company->where($where_company)->save($save_company);
		$save_seller['typeid'] = (int)($_POST['type']);
		$save_seller['zitypeid'] = (int)($_POST['zitype']);
		$save_seller['sellcircleid'] = (int)($_POST['sellcircle']);
		$save_seller['zisellcircleid'] = (int)($_POST['zisellcircle']);
		$save_seller['name'] = $_POST['name'];
		$save_seller['fistpic'] = $_POST['fistpic'];
		$save_seller['secondpic'] = $_POST['secondpic'];
		$save_seller['thirdpic'] = $_POST['thirdpic'];
		$save_seller['fourpic'] = $_POST['fourpic'];
		$save_seller['fivepic'] = $_POST['fivepic'];
		$save_seller['sixpic'] = $_POST['sixpic'];
		$save_seller['qrcode'] = $_POST['qrcode'];
		$save_seller['weurl'] = $_POST['weurl'];
		$update_seller = $this->m_seller->where($where_seller)->save($save_seller);
		$this->success("修改成功",U("LivingCircle/myseller",array("token"=>$this->token)));
	}
	//商家登录-商品管理
	public function mysellergoods(){
		$cid = session("companyid");
		$where_mysellergoods['token'] = $this->token;
		$where_mysellergoods['cid'] = $cid;
		$where_page['token'] = $this->token;
		if(!empty($_GET['name'])){
			$where_mysellergoods['name'] = array("like","%".$_GET['name']."%");
			$where_page['name'] = $_GET['name'];
		}
		import('ORG.Util.Page');
		$count = $this->m_mysellergoods->where($where_mysellergoods)->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$goods_list = $this->m_mysellergoods->where($where_mysellergoods)->order("num asc,addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$show);
		$this->assign("goods_list",$goods_list);
		$this->display();
	}
	//商家登录-商品添加
	public function mysellergoodsadd(){
		
		$this->display();
	}
	//商家登录-商品执行添加
	public function domysellergoodsadd(){
		$cid = session("companyid");
		$where_seller['token'] = $this->token;
		$where_seller['cid'] = $cid;
		$sellerid = $this->m_seller->where($where_seller)->getField("imicms_id");
		$where_mysellergoods['token'] = $this->token();
		$where_mysellergoods['cid'] = $cid;
		$where_mysellergoods['name'] = $_POST['name'];
		$find_mysellergoods = $this->m_mysellergoods->where($where_mysellergoods)->find();
		if(empty($find_mysellergoods)){
			$add_mysellergoods['name'] = $_POST['name'];
			$add_mysellergoods['price'] = $_POST['price'];
			$add_mysellergoods['num'] = $_POST['num'];
			$add_mysellergoods['display'] = $_POST['display'];
			$add_mysellergoods['token'] = $this->token;
			$add_mysellergoods['sellerid'] = $sellerid;
			$add_mysellergoods['cid'] = $cid;
			$add_mysellergoods['addtime'] = time();
			$id_mysellergoods = $this->m_mysellergoods->add($add_mysellergoods);
			if($id_mysellergoods > 0){
				$this->success("添加成功",U("LivingCircle/mysellergoods",array("token"=>$this->token)));
			}
		}else{
			$this->error("商品重复");
		}
	}
	//商家登录-商品修改
	public function mysellergoodsupdate(){
		$cid = session("companyid");
		$where_mysellergoods['token'] = $this->token;
		$where_mysellergoods['cid'] = $cid;
		$where_mysellergoods['imicms_id'] = (int)($_GET['goodsid']);
		$find_goods = $this->m_mysellergoods->where($where_mysellergoods)->find();
		$this->assign("goods",$find_goods);
		$this->display();
	}
	//商家登录-商品执行修改
	public function domysellergoodsupdate(){
		$cid = session("companyid");
		$where_mysellergoods['token'] = $this->token;
		$where_mysellergoods['cid'] = $cid;
		$where_mysellergoods['imicms_id'] = (int)($_POST['goodsid']);
		$find_goods = $this->m_mysellergoods->where($where_mysellergoods)->find();
		
		$where_mysellergoods_name['token'] = $this->token;
		$where_mysellergoods_name['cid'] = $cid;
		$where_mysellergoods_name['name'] = $_POST['name'];
		$find_goods_name = $this->m_mysellergoods->where($where_mysellergoods_name)->find();
		
		if(!empty($find_goods_name) && $find_goods['name'] != $_POST['name']){
			$this->error("商品重复");
		}else{
			$save_mysellergoods['name'] = $_POST['name'];
			$save_mysellergoods['price'] = $_POST['price'];
			$save_mysellergoods['num'] = $_POST['num'];
			$save_mysellergoods['display'] = $_POST['display'];
			$update_mysellergoods = $this->m_mysellergoods->where($where_mysellergoods)->save($save_mysellergoods);
			$this->success("修改成功",U("LivingCircle/mysellergoods",array('token'=>$this->token)));
		}
		
	}
	//商家登录-ajax
	public function sellerajax(){
		$cid = session("companyid");
		switch($_POST['type']){
			//获取子分类选择列表
			case 'zitype':
				$where_zitype['token'] = $_POST['token'];
				$where_zitype['typeid'] = $_POST['typeid'];
				$zitype_list = $this->m_type->where($where_zitype)->select();
				$list = '<option value=\'\'>--请选择子分类--</option>';
				foreach($zitype_list as $vo){
					$list.='<option value=\''.$vo['imicms_id'].'\'>'.$vo['name'].'</option>';
				}
				$data['list'] = $list;
				if($zitype_list == ''){
					$data['error'] = 0;
				}else{
					$data['error'] = 1;
				}
				$this->ajaxReturn($data,'JSON');
			break;
			//获取子商圈选择列表
			case 'zisellcircle':
				$where_zisellcircle['token'] = $_POST['token'];
				$where_zisellcircle['sellcircleid'] = $_POST['sellcircleid'];
				$zisellcircle_list = $this->m_sellcircle->where($where_zisellcircle)->select();
				$list = '<option value=\'\'>--请选择子商圈--</option>';
				foreach($zisellcircle_list as $vo){
					$list.='<option value=\''.$vo['imicms_id'].'\'>'.$vo['name'].'</option>';
				}
				$data['list'] = $list;
				if($zisellcircle_list == ''){
					$data['error'] = 0;
				}else{
					$data['error'] = 1;
				}
				$this->ajaxReturn($data,'JSON');
			break;
			//商品是否显示
			case 'goodsdisplay':
				$where_mysellergoods['token'] = $_POST['token'];
				$where_mysellergoods['imicms_id'] = (int)($_POST['id']);
				$where_mysellergoods['cid'] = $cid;
				$display = $this->m_mysellergoods->where($where_mysellergoods)->getField("display");
				if($display == 1){
					$save_mysellergoods['display'] = 0;
					$data['error'] = 0;
				}else{
					$save_mysellergoods['display'] = 1;
					$data['error'] = 1;
				}
				$update_mysellergoods = $this->m_mysellergoods->where($where_mysellergoods)->save($save_mysellergoods);
				$this->ajaxReturn($data,'JSON');
			break;
		}
	}
	//商家登录-执行操作
	public function selleroperate(){
		$cid = session("companyid");
		$where_seller['cid'] = $cid;
		$where_seller['token'] = $this->token;
		$find_seller = $this->m_seller->where($where_seller)->find();
		switch($_GET['type']){
			case 'goodsdel':
				$where_mysellergoods['token'] = $this->token;
				$where_mysellergoods['cid'] = $cid;
				$where_mysellergoods['imicms_id'] = (int)($_GET['goodsid']);
				$del_mysellergoods = $this->m_mysellergoods->where($where_mysellergoods)->delete();
				$this->success("删除成功",U("LivingCircle/mysellergoods",array("token"=>$this->token)));
			break;
			case 'commentdel':
				$where_comment['token'] = $this->token;
				$where_comment['sellerid'] = $find_seller['imicms_id'];
				$where_comment['imicms_id'] = (int)($_GET['commentid']);
				$del_comment = $this->m_comment->where($where_comment)->delete();
				$this->success("删除成功",U("LivingCircle/mysellercomment",array("token"=>$this->token)));
			break;
		}
	}
	//商家登录-订单管理
	public function mysellerorder(){
		$cid = session("companyid");
		$where_seller['token'] = $this->token;
		$where_seller['cid'] = $cid;
		$sellerid = $this->m_seller->where($where_seller)->getField("imicms_id");
		switch($_GET['type']){
			case '1':
				$where_order['token'] = $this->token;
				$where_order['sellerid'] = $sellerid;
				$where_order['paid'] = 0;
			break;
			case '2':
				$where_order['token'] = $this->token;
				$where_order['sellerid'] = $sellerid;
				$where_order['paid'] = 1;
				$where_order['state'] = 0;
			break;
			case '3':
				$where_order['token'] = $this->token;
				$where_order['sellerid'] = $sellerid;
				$where_order['paid'] = 1;
				$where_order['state'] = 1;
			break;
			case '4':
				$where_order['token'] = $this->token;
				$where_order['sellerid'] = $sellerid;
				$where_order['paid'] = 1;
				$where_order['state'] = 2;
			break;
			default:
				$where_order['token'] = $this->token;
				$where_order['sellerid'] = $sellerid;
		}
		$where_page['token'] = $this->token;
		$where_page['type'] = $_GET['type'];
		if($_GET['name'] != ""){
			$where_order['imicms_id'] = (int)($_GET['name']) - 10000000;
			$where_page['name'] = $_GET['name'];
		}
		import('ORG.Util.Page');
		$count = $this->m_mysellerorder->where($where_order)->order("addtime desc")->count();
		$page = new Page($count,8);
		foreach($where_page as $key=>$val){
			$page->parameter.="$key=".urlencode($val).'&';
		}
		$show = $page->show();
		$order_list = $this->m_mysellerorder->where($where_order)->order("addtime desc")->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$show);
		$this->assign("order_list",$order_list);
		foreach($order_list as $vo){
			$where_cart['token'] = $this->token;
			$where_cart['wecha_id'] = $vo['wecha_id'];
			$where_cart['sellerid'] = $vo['sellerid'];
			$where_cart['orderid'] = $vo['imicms_id'];
			$cart_list[$vo['imicms_id']] = $this->m_mysellercart->where($where_cart)->select();
			foreach($cart_list[$vo['imicms_id']] as $k=>$v){
				$where_goods['token'] = $this->token;
				$where_goods['imicms_id'] = $v['goodsid'];
				$cart_list[$vo['imicms_id']][$k]['name'] = $this->m_mysellergoods->where($where_goods)->getField('name');
				$cart_list[$vo['imicms_id']][$k]['price'] = $this->m_mysellergoods->where($where_goods)->getField('price');
			}
		}
		$this->assign("cart_list",$cart_list);
		$this->display();
	}
	//商家登录-订单操作
	public function mysellerorderstup(){
		if($_GET['type'] = 'fahuo'){
			$where_order['token'] = $this->token;
			$where_order['imicms_id'] = (int)($_GET['orderid']);
			$orderid = (int)($_GET['orderid']) + 10000000;
			$save_order['state'] = 1;
			$update_order = $this->m_mysellerorder->where($where_order)->save($save_order);
			$order = $this->m_mysellerorder->where($where_order)->find();
			$model = new templateNews();
			$model->sendTempMsg('OPENTM200565259', array('href' => $this->siteUrl.U('LivingCircle/myorder',array('token' => $this->token, 'type' => 1)), 'wecha_id' => $order['wecha_id'], 'first' => '您的订单'.$orderid.'已发货', 'keyword1' => $orderid, 'keyword2' => '无', 'keyword3' => '无', 'remark' => date("Y年m月d日H时i分s秒")));
			$this->success("发货成功",U("LivingCircle/mysellerorder",array("token"=>$this->token)));
		}
	}
	//商家登录-评论管理
	public function mysellercomment(){
		$cid = session("companyid");
		$where_seller['cid'] = $cid;
		$where_seller['token'] = $this->token;
		$find_seller = $this->m_seller->where($where_seller)->find();
		$where_comment['token'] = $this->token;
		$where_comment['sellerid'] = $find_seller['imicms_id'];
		$comment_list = $this->m_comment->where($where_comment)->order('addtime desc')->select();
		foreach($comment_list as $k=>$v){
			$where_userinfo['token'] = $this->token;
			$where_userinfo['wecha_id'] = $v['wecha_id'];
			$wechaname = $this->m_userinfo->where($where_userinfo)->getField('wechaname');
			$comment_list[$k]['wechaname'] = $wechaname;
			if($wechaname == ''){
				$comment_list[$k]['wechaname'] = '游客';
			}
		}
		$this->assign("comment_list",$comment_list);
		$this->display();
	}
}
?>