<?php
class LivingCircleAction extends WapAction{
	public $isamap;
	public function _initialize(){
		parent::_initialize();
		$this->m_type = M("livingcircle_type");
		$this->m_sellcircle = M("livingcircle_sellcircle");
		$this->m_company = M("company");
		$this->m_seller = M("livingcircle_seller");
		$this->m_mysellergoods = M("livingcircle_mysellergoods");
		$this->m_mysellercart = M("livingcircle_mysellercart");
		$this->m_mysellerorder = M("livingcircle_mysellerorder");
		$this->m_favorite = M("livingcircle_favorite");
		$this->m_user = M("livingcircle_user");
		$this->m_livingcircle = M("livingcircle");
		$this->m_userinfo = M("userinfo");
		$this->m_comment = M('livingcircle_comment');
		
		$this->mylat = session("mylat");
		$this->mylng = session("mylng");
		$this->assign("mylat",$this->mylat);
		$this->assign("mylng",$this->mylng);
		if($this->mylat == "" && !in_array(ACTION_NAME,array("index","latlng"))){
			$this->redirect("LivingCircle/index",array("token"=>$this->token));
			exit;
		}
		/*if($this->isSubscribe() == false && !in_array(ACTION_NAME,array("gzhurl"))){
			$this->success("您好，您还没有关注我们的公众号,关注后才能继续喔。",U("LivingCircle/gzhurl",array("token"=>$this->token)));
			exit;
		}*/
		if (C('baidu_map')){
			$this->isamap=0;
		}else {
			$this->isamap=1;
			$this->amap=new amap();
		}
	}
	//判断关注
	public function gzhurl(){
		$gzhurl = M('Home')->where(array('token'=>$this->token))->getField('gzhurl');
		if($gzhurl == null){
			$this->show("<h1>未设置关注链接</h1>");
		}else{
			$this->show("<script>window.location.href='".$gzhurl."'</script>");
		}
	}
	//生活圈首页
	public function index(){
		$where_livingcircle['token'] = $this->token;
		$find_livingcircle = $this->m_livingcircle->where($where_livingcircle)->find($find_livingcircle);
		$this->assign("livingcircle",$find_livingcircle);
		if($find_livingcircle['fistpic'] != ""){
			$headpic[] = $find_livingcircle['fistpic'];
		}
		if($find_livingcircle['secondpic'] != ""){
			$headpic[] = $find_livingcircle['secondpic'];
		}
		if($find_livingcircle['thirdpic'] != ""){
			$headpic[] = $find_livingcircle['thirdpic'];
		}
		if($find_livingcircle['fourpic'] != ""){
			$headpic[] = $find_livingcircle['fourpic'];
		}
		if($find_livingcircle['fivepic'] != ""){
			$headpic[] = $find_livingcircle['fivepic'];
		}
		if($find_livingcircle['sixpic'] != ""){
			$headpic[] = $find_livingcircle['sixpic'];
		}
		$this->assign("headpic",$headpic);
		
		$where_type['token'] = $this->token;
		$where_type['display'] = 1;
		$where_type['typeid'] = 0;
		$type_list = $this->m_type->where($where_type)->order("num asc,addtime desc")->limit(6)->select();
		$this->assign("type_list",$type_list);
		$this->display();
	}
	//分类列表本地导航
	public function typelist(){
		$where_type['token'] = $this->token;
		$where_type['display'] = 1;
		$where_type['typeid'] = 0;
		$type_list = $this->m_type->where($where_type)->order("num asc,addtime desc")->select();
		foreach($type_list as $vo){
			$where_zitype['token'] = $this->token;
			$where_zitype['display'] = 1;
			$where_zitype['typeid'] = $vo['imicms_id'];
			$zitype_list[$vo['imicms_id']] = $this->m_type->where($where_zitype)->order("num asc,addtime desc")->select();
		}
		$this->assign("type_list",$type_list);
		$this->assign("zitype_list",$zitype_list);
		$this->display();
	}
	//商家列表
	public function sellerlist(){
		//分类导航
		$where_type['token'] = $this->token;
		$where_type['display'] = 1;
		$where_type['typeid'] = 0;
		$type_list = $this->m_type->where($where_type)->order("num asc,addtime desc")->select();
		foreach($type_list as $vo){
			$where_zitype['token'] = $this->token;
			$where_zitype['display'] = 1;
			$where_zitype['typeid'] = $vo['imicms_id'];
			$zitype_list[$vo['imicms_id']] = $this->m_type->where($where_zitype)->order("num asc,addtime desc")->select();
		}
		$this->assign("type_list",$type_list);
		$this->assign("zitype_list",$zitype_list);
		if((int)($_GET['typeid']) != 0 && (int)($_GET['zitypeid']) == 0){
			$where_type_name['token'] = $this->token;
			$where_type_name['imicms_id'] = (int)($_GET['typeid']);
			$type_name = $this->m_type->where($where_type_name)->getField("name");
		}elseif((int)($_GET['typeid']) != 0 && (int)($_GET['zitypeid']) != 0){
			$where_type_name['token'] = $this->token;
			$where_type_name['imicms_id'] = (int)($_GET['zitypeid']);
			$type_name = $this->m_type->where($where_type_name)->getField("name");
		}else{
			$type_name = "行业分类";
		}
		$this->assign("type_name",$type_name);
		//商圈导航
		$where_sellcircle['token'] = $this->token;
		$where_sellcircle['display'] = 1;
		$where_sellcircle['sellcircleid'] = 0;
		$sellcircle_list = $this->m_sellcircle->where($where_sellcircle)->order("num asc,addtime desc")->select();
		foreach($sellcircle_list as $vo){
			$where_zisellcircle['token'] = $this->token;
			$where_zisellcircle['display'] = 1;
			$where_zisellcircle['sellcircleid'] = $vo['imicms_id'];
			$zisellcircle_list[$vo['imicms_id']] = $this->m_sellcircle->where($where_zisellcircle)->order("num asc,addtime desc")->select();
		}
		$this->assign("sellcircle_list",$sellcircle_list);
		$this->assign("zisellcircle_list",$zisellcircle_list);
		if((int)($_GET['sellcircleid']) != 0 && (int)($_GET['zisellcircleid']) == 0){
			$where_sellcircle_name['token'] = $this->token;
			$where_sellcircle_name['imicms_id'] = (int)($_GET['sellcircleid']);
			$sellcircle_name = $this->m_sellcircle->where($where_sellcircle_name)->getField("name");
		}elseif((int)($_GET['sellcircleid']) != 0 && (int)($_GET['zisellcircleid']) != 0){
			$where_sellcircle_name['token'] = $this->token;
			$where_sellcircle_name['imicms_id'] = (int)($_GET['zisellcircleid']);
			$sellcircle_name = $this->m_sellcircle->where($where_sellcircle_name)->getField("name");
		}else{
			$sellcircle_name = "全部商圈";
		}
		$this->assign("sellcircle_name",$sellcircle_name);
		//排序导航
		$sort = array("全部排序","离我最近","最新入驻","浏览最多");
		$this->assign("sort",$sort[(int)($_GET['sort'])]);
		
		//数据
		if($_GET['keyword'] != ""){
			$where_seller_keyword['name'] = array("like","%".$_GET['keyword']."%");
			$where_seller_keyword['address'] = array("like","%".$_GET['keyword']."%");
			$where_seller_keyword['_logic'] = 'or';
			$where_seller['_complex'] = $where_seller_keyword;
		}
		$where_seller['token'] = $this->token;
		if((int)($_GET['typeid']) != 0){
			$where_seller['typeid'] = (int)($_GET['typeid']);
			if((int)($_GET['zitypeid']) != 0){
				$where_seller['zitypeid'] = (int)($_GET['zitypeid']);
			}
		}
		if((int)($_GET['sellcircleid']) != 0){
			$where_seller['sellcircleid'] = (int)($_GET['sellcircleid']);
			if((int)($_GET['zisellcircleid']) != 0){
				$where_seller['zisellcircleid'] = (int)($_GET['zisellcircleid']);
			}
		}
		$p = (int)($_GET['p']);
		if($_GET['sort'] == 1){
			$seller_list = $this->m_seller->where($where_seller)->order("num asc,addtime desc")->select();
			foreach($seller_list as $vo){
				$where_company['id'] = $vo['cid'];
				$where_company['token'] = $this->token;
				$find_company = $this->m_company->where($where_company)->find();
				if($find_company['display'] == 1){
					$distance[$vo['imicms_id']] = $this->getDistance_map($this->mylat,$this->mylng,$find_company['latitude'],$find_company['longitude']);
				}
			}
			asort($distance);
			// $count = count($distance);
			// $l = 1;
			// $maxpage = ceil($count/$l);
			// $i = 0;
			$i = 0;
			foreach($distance as $k=>$v){
				$where_seller2['token'] = $this->token;
				$where_seller2['imicms_id'] = $k;
				$find_seller2 = $this->m_seller->where($where_seller2)->find();
				$where_company2['token'] = $this->token;
				$where_company2['id'] = $find_seller2['cid'];
				$find_company2 = $this->m_company->where($where_company2)->find();
				$sellerlist[$i]['name'] = $find_seller2['name'];
				$sellerlist[$i]['recommend'] = $find_seller2['recommend'];
				$sellerlist[$i]['logourl'] = $find_company2['logourl'];
				$sellerlist[$i]['address'] = $find_company2['address'];
				if($find_company2['mp'] == ""){
					$sellerlist[$i]['phone'] = $find_company2['tel'];
				}else{
					$sellerlist[$i]['phone'] = $find_company2['mp'];
				}
				if($v > 1000){
					$sellerlist[$i]['distance'] = round(($v/1000),1)."km";
				}else{
					$sellerlist[$i]['distance'] = $v."m";
				}
				$sellerlist[$i]['sellerid'] = $k;
				$where_comment['token'] = $this->token;
				$where_comment['sellerid'] = $k;
				$commentlist = $this->m_comment->where($where_comment)->select();
				$comcount = count($commentlist);
				$comtotal = 0;
				foreach($commentlist as $com){
					$comtotal = $comtotal + $com['star'];
				}
				$sellerlist[$i]['star'] = floor($comtotal/$comcount);
				$i++;
			}
			$this->assign("sellerlist",$sellerlist);
		}elseif($_GET['sort'] == 2){
			$seller_list = $this->m_seller->where($where_seller)->order("addtime desc")->select();
			$i = 0;
			foreach($seller_list as $k=>$v){
				$where_company['token'] = $this->token;
				$where_company['id'] = $v['cid'];
				$find_company = $this->m_company->where($where_company)->find();
				if($find_company['display'] == 1){
					$distance = $this->getDistance_map($this->mylat,$this->mylng,$find_company['latitude'],$find_company['longitude']);
					if($distance > 1000){
						$sellerlist[$i]['distance'] = round(($distance/1000),1)."km";
					}else{
						$sellerlist[$i]['distance'] = $distance."m";
					}
					if($find_company['mp'] == ""){
						$sellerlist[$i]['phone'] = $find_company['tel'];
					}else{
						$sellerlist[$i]['phone'] = $find_company['mp'];
					}
					$sellerlist[$i]['sellerid'] = $v['imicms_id'];
					$sellerlist[$i]['name'] = $v['name'];
					$sellerlist[$i]['recommend'] = $v['recommend'];
					$sellerlist[$i]['logourl'] = $find_company['logourl'];
					$sellerlist[$i]['address'] = $find_company['address'];
					$where_comment['token'] = $this->token;
					$where_comment['sellerid'] = $v['imicms_id'];
					$commentlist = $this->m_comment->where($where_comment)->select();
					$comcount = count($commentlist);
					$comtotal = 0;
					foreach($commentlist as $com){
						$comtotal = $comtotal + $com['star'];
					}
					$sellerlist[$i]['star'] = floor($comtotal/$comcount);
					$i++;
				}
			}
			$this->assign("sellerlist",$sellerlist);
		}elseif($_GET['sort'] == 3){
			$seller_list = $this->m_seller->where($where_seller)->order("pv desc")->select();
			$i = 0;
			foreach($seller_list as $k=>$v){
				$where_company['token'] = $this->token;
				$where_company['id'] = $v['cid'];
				$find_company = $this->m_company->where($where_company)->find();
				if($find_company['display'] == 1){
					$distance = $this->getDistance_map($this->mylat,$this->mylng,$find_company['latitude'],$find_company['longitude']);
					if($distance > 1000){
						$sellerlist[$i]['distance'] = round(($distance/1000),1)."km";
					}else{
						$sellerlist[$i]['distance'] = $distance."m";
					}
					if($find_company['mp'] == ""){
						$sellerlist[$i]['phone'] = $find_company['tel'];
					}else{
						$sellerlist[$i]['phone'] = $find_company['mp'];
					}
					$sellerlist[$i]['sellerid'] = $v['imicms_id'];
					$sellerlist[$i]['name'] = $v['name'];
					$sellerlist[$i]['recommend'] = $v['recommend'];
					$sellerlist[$i]['logourl'] = $find_company['logourl'];
					$sellerlist[$i]['address'] = $find_company['address'];
					$where_comment['token'] = $this->token;
					$where_comment['sellerid'] = $v['imicms_id'];
					$commentlist = $this->m_comment->where($where_comment)->select();
					$comcount = count($commentlist);
					$comtotal = 0;
					foreach($commentlist as $com){
						$comtotal = $comtotal + $com['star'];
					}
					$sellerlist[$i]['star'] = floor($comtotal/$comcount);
					$i++;
				}
			}
			$this->assign("sellerlist",$sellerlist);
		}else{
			$seller_list = $this->m_seller->where($where_seller)->order("recommend desc,num asc,addtime desc")->select();
			$i = 0;
			foreach($seller_list as $k=>$v){
				$where_company['token'] = $this->token;
				$where_company['id'] = $v['cid'];
				$find_company = $this->m_company->where($where_company)->find();
				if($find_company['display'] == 1){
					$distance = $this->getDistance_map($this->mylat,$this->mylng,$find_company['latitude'],$find_company['longitude']);
					if($distance > 1000){
						$sellerlist[$i]['distance'] = round(($distance/1000),1)."km";
					}else{
						$sellerlist[$i]['distance'] = $distance."m";
					}
					if($find_company['mp'] == ""){
						$sellerlist[$i]['phone'] = $find_company['tel'];
					}else{
						$sellerlist[$i]['phone'] = $find_company['mp'];
					}
					$sellerlist[$i]['sellerid'] = $v['imicms_id'];
					$sellerlist[$i]['name'] = $v['name'];
					$sellerlist[$i]['recommend'] = $v['recommend'];
					$sellerlist[$i]['logourl'] = $find_company['logourl'];
					$sellerlist[$i]['address'] = $find_company['address'];
					$where_comment['token'] = $this->token;
					$where_comment['sellerid'] = $v['imicms_id'];
					$commentlist = $this->m_comment->where($where_comment)->select();
					$comcount = count($commentlist);
					$comtotal = 0;
					foreach($commentlist as $com){
						$comtotal = $comtotal + $com['star'];
					}
					$sellerlist[$i]['star'] = floor($comtotal/$comcount);
					$i++;
				}
			}
			$this->assign("sellerlist",$sellerlist);
		}
		$this->display();
	}
	//商家
	public function seller(){
		$where_favorite['token'] = $this->token;
		$where_favorite['wecha_id'] = $this->wecha_id;
		$where_favorite['sellerid'] = (int)($_GET['sellerid']);
		$find_favorite = $this->m_favorite->where($where_favorite)->find();
		if(empty($find_favorite)){
			$this->assign("favorite","no");
		}else{
			$this->assign("favorite","yes");
		}
		$where_seller['token'] = $this->token;
		$where_seller['imicms_id'] = (int)($_GET['sellerid']);
		$pv = $this->m_seller->where($where_seller)->getField("pv");
		$save_seller['pv'] = $pv + 1;
		$update_seller = $this->m_seller->where($where_seller)->save($save_seller);
		$seller = $this->m_seller->where($where_seller)->find();
		$where_company['token'] = $this->token;
		$where_company['id'] = $seller['cid'];
		$company = $this->m_company->where($where_company)->find();
		if($company['mp'] == ""){
			$seller['phone'] = $company['tel'];
		}else{
			$seller['phone'] = $company['mp'];
		}
		$seller['intro'] = $company['intro'];
		$seller['address'] = $company['address'];
		$this->assign("seller",$seller);
		if($seller['fistpic'] != ""){
			$headpic[] = $seller['fistpic'];
		}
		if($seller['secondpic'] != ""){
			$headpic[] = $seller['secondpic'];
		}
		if($seller['thirdpic'] != ""){
			$headpic[] = $seller['thirdpic'];
		}
		if($seller['fourpic'] != ""){
			$headpic[] = $seller['fourpic'];
		}
		if($seller['fivepic'] != ""){
			$headpic[] = $seller['fivepic'];
		}
		if($seller['sixpic'] != ""){
			$headpic[] = $seller['sixpic'];
		}
		$this->assign("headpic",$headpic);
		$where_mysellergoods['token'] = $this->token;
		$where_mysellergoods['sellerid'] = (int)($_GET['sellerid']);
		$where_mysellergoods['display'] = 1;
		$mysellergoods = $this->m_mysellergoods->where($where_mysellergoods)->order("num asc,addtime desc")->select();
		$this->assign("mysellergoods",$mysellergoods);
		$where_comment['token'] = $this->token;
		$where_comment['sellerid'] = (int)($_GET['sellerid']);
		$commentlist = $this->m_comment->where($where_comment)->select();
		$this->assign('count',count($commentlist));
		$total = 0;
		foreach($commentlist as $v){
			$total = $total + $v['star'];
		}
		$count = count($commentlist);
		$star = floor($total/$count);
		$this->assign("star",$star);
		$this->display();
	}
	//路线导航
	public function map(){
		$where_seller['token'] = $this->token;
		$where_seller['imicms_id'] = (int)($_GET['sellerid']);
		$seller = $this->m_seller->where($where_seller)->find();
		$this->assign("seller",$seller);
		$where_company['token'] = $this->token;
		$where_company['id'] = $seller['cid'];
		$company = $this->m_company->where($where_company)->find();
		/*$this->assign("lat",$company['latitude']);
		$this->assign("lng",$company['longitude']);
		$this->display();*/
		if (!$this->isamap){
			$this->display();
		}else {			
			$link=$this->amap->getPointMapLink($company['longitude'],$company['latitude'],$company['name']);
			header('Location:'.$link);
		}
	}
	//确认我的地址和信息
	public function domyinfo(){
		$where_user['wecha_id'] = $this->wecha_id;
		$where_user['token'] = $this->token;
		$find_user = $this->m_user->where($where_user)->find();
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();
		if($find_user['name'] == null){
			$find_user['name'] = $find_userinfo['wechaname'];
		}
		$find_user['phone'] = $find_userinfo['tel'];
		$this->assign("user",$find_user);
		$this->display();
	}
	//执行购买
	public function dobuy(){
		$where_user['wecha_id'] = $this->wecha_id;
		$where_user['token'] = $this->token;
		$find_user = $this->m_user->where($where_user)->find();
		if($find_user == null){
			$add_user['token'] = $this->token;
			$add_user['wecha_id'] = $this->wecha_id;
			$add_user['address'] = $_GET['address'];
			$add_user['name'] = $_GET['name'];
			$id_usre = $this->m_user->add($add_user);
			$save_userinfo['tel'] = $_GET['phone'];
			$update_userinfo = $this->m_userinfo->where($where_user)->save($save_userinfo);
		}else{
			$save_user['address'] = $_GET['address'];
			$save_user['name'] = $_GET['name'];
			$update_user = $this->m_user->where($where_user)->save($save_user);
			$save_userinfo['tel'] = $_GET['phone'];
			$update_userinfo = $this->m_userinfo->where($where_user)->save($save_userinfo);
		}
		$add_order['token'] = $this->token;
		$add_order['wecha_id'] = $this->wecha_id;
		$add_order['name'] = $_GET['name'];
		$add_order['phone'] = $_GET['phone'];
		$add_order['address'] = $_GET['address'];
		$add_order['sellerid'] = (int)($_GET['sellerid']);
		$where_mysellercart['token'] = $this->token;
		$where_mysellercart['wecha_id'] = $this->wecha_id;
		$where_mysellercart['orderid'] = 0;
		$where_mysellercart['sellerid'] = (int)($_GET['sellerid']);
		$mysellercart = $this->m_mysellercart->where($where_mysellercart)->select();
		$total = 0;
		foreach($mysellercart as $vo){
			$where_mysellergoods['token'] = $this->token;
			$where_mysellergoods['imicms_id'] = $vo['goodsid'];
			$price = $this->m_mysellergoods->where($where_mysellergoods)->getField("price");
			$total = $total + $price;
		}
		$add_order['price'] = $total;
		$add_order['addtime'] = time();
		
		$id_order = $this->m_mysellerorder->add($add_order);
		$randnum = rand(1000,9999);
		$save_order['orderid'] = $id_order."LivingCircle".time().$randnum;
		$update_order = $this->m_mysellerorder->where(array('imicms_id'=>$id_order))->save($save_order);
		$save_mysellercart['orderid'] = $id_order;
		$update_mysellercart = $this->m_mysellercart->where($where_mysellercart)->save($save_mysellercart);
		$this->redirect("Alipay/pay",array("token"=>$this->token,"price"=>$total,"wecha_id"=>$this->wecha_id,"from"=>"LivingCircle","orderid"=>$save_order['orderid'],'single_orderid'=>$save_order['orderid']));//接上支付后打开
			
		//$this->redirect("LivingCircle/payReturn",array("token"=>$this->token,"price"=>$total,"wecha_id"=>$this->wecha_id,"from"=>"LivingCircle","orderid"=>$id_order));//接上支付后去掉
	}
	//支付后
	public function payReturn(){
		$where_order['token'] = $this->token;
		$where_order['orderid'] = $_GET['orderid'];
		$find_order = $this->m_mysellerorder->where($where_order)->find();
		if($find_order['paid'] == 1){
			$this->success("支付成功",U("LivingCircle/index",array('token'=>$this->token)));
		}else{
			$this->error("支付失败",U("LivingCircle/index",array('token'=>$this->token)));
		}
		
	}
	//个人中心
	public function my(){
		
		$this->display();
	}
	//我的收藏
	public function favorite(){
		$where_favorite['token'] = $this->token;
		$where_favorite['wecha_id'] = $this->wecha_id;
		$favorite = $this->m_favorite->where($where_favorite)->select();
		$i = 0;
		foreach($favorite as $vo){
			$where_seller['token'] = $this->token;
			$where_seller['imicms_id'] = $vo['sellerid'];
			$find_seller = $this->m_seller->where($where_seller)->find();
			$where_company['token'] = $this->token;
			$where_company['id'] = $find_seller['cid'];
			$find_company = $this->m_company->where($where_company)->find();
			if($find_company['display'] == 1){
				$distance = $this->getDistance_map($this->mylat,$this->mylng,$find_company['latitude'],$find_company['longitude']);
				if($distance > 1000){
					$sellerlist[$i]['distance'] = round(($distance/1000),1)."km";
				}else{
					$sellerlist[$i]['distance'] = $distance."m";
				}
				if($find_company['mp'] == ""){
					$sellerlist[$i]['phone'] = $find_company['tel'];
				}else{
					$sellerlist[$i]['phone'] = $find_company['mp'];
				}
				$sellerlist[$i]['sellerid'] = $find_seller['imicms_id'];
				$sellerlist[$i]['name'] = $find_seller['name'];
				$sellerlist[$i]['recommend'] = $find_seller['recommend'];
				$sellerlist[$i]['logourl'] = $find_company['logourl'];
				$sellerlist[$i]['address'] = $find_company['address'];
				$i++;
			}
		}
		$this->assign("sellerlist",$sellerlist);
		$this->display();
	}
	//我的订单
	public function myorder(){
		if($_GET['type'] == 0){
			$where_order['token'] = $this->token;
			$where_order['wecha_id'] = $this->wecha_id;
			$where_order['paid'] = 0;
			$order_list = $this->m_mysellerorder->where($where_order)->order("addtime desc")->select();
			foreach($order_list as $vo){
				$where_mysellercart['token'] = $this->token;
				$where_mysellercart['wecha_id'] = $this->wecha_id;
				$where_mysellercart['orderid'] = $vo['imicms_id'];
				$cart_list[$vo['imicms_id']] = $this->m_mysellercart->where($where_mysellercart)->select();
				foreach($cart_list[$vo['imicms_id']] as $k=>$v){
					$where_mysellergoods['token'] = $this->token;
					$where_mysellergoods['sellerid'] = $v['sellerid'];
					$where_mysellergoods['imicms_id'] = $v['goodsid'];
					$cart_list[$vo['imicms_id']][$k]['name'] = $this->m_mysellergoods->where($where_mysellergoods)->getField("name");
					$cart_list[$vo['imicms_id']][$k]['price'] = $this->m_mysellergoods->where($where_mysellergoods)->getField("price");
				}
			}
			$this->assign("order_list",$order_list);
			$this->assign("cart_list",$cart_list);
		}elseif($_GET['type'] == 1){
			$where_order['token'] = $this->token;
			$where_order['wecha_id'] = $this->wecha_id;
			$where_order['paid'] = 1;
			$order_list = $this->m_mysellerorder->where($where_order)->order("addtime desc")->select();
			foreach($order_list as $vo){
				$where_mysellercart['token'] = $this->token;
				$where_mysellercart['wecha_id'] = $this->wecha_id;
				$where_mysellercart['orderid'] = $vo['imicms_id'];
				$cart_list[$vo['imicms_id']] = $this->m_mysellercart->where($where_mysellercart)->select();
				foreach($cart_list[$vo['imicms_id']] as $k=>$v){
					$where_mysellergoods['token'] = $this->token;
					$where_mysellergoods['sellerid'] = $v['sellerid'];
					$where_mysellergoods['imicms_id'] = $v['goodsid'];
					$cart_list[$vo['imicms_id']][$k]['name'] = $this->m_mysellergoods->where($where_mysellergoods)->getField("name");
					$cart_list[$vo['imicms_id']][$k]['price'] = $this->m_mysellergoods->where($where_mysellergoods)->getField("price");
				}
			}
			$this->assign("order_list",$order_list);
			$this->assign("cart_list",$cart_list);
		}
		$this->display();
	}
	//执行未支付订单
	public function doorder(){
		$where_order['token'] = $this->token;
		$where_order['wecha_id'] = $this->wecha_id;
		$where_order['imicms_id'] = (int)($_GET['orderid']);
		$order = $this->m_mysellerorder->where($where_order)->find();
		$this->redirect("Alipay/pay",array("token"=>$this->token,"price"=>$order['price'],"wecha_id"=>$this->wecha_id,"from"=>"LivingCircle","orderid"=>$order['orderid'],'single_orderid'=>$order['orderid']));//接上支付后打开
		//$paid1 = $this->m_mysellerorder->where($where_order)->save(array('paid'=>1));//接上支付后去掉
		//$this->redirect("LivingCircle/payReturn",array("token"=>$this->token,"price"=>$order['price'],"wecha_id"=>$this->wecha_id,"from"=>"LivingCircle","orderid"=>(int)($_GET['orderid'])));//接上支付后去掉
	}
	//执行删除订单
	public function delorder(){
		$where_order['token'] = $this->token;
		$where_order['wecha_id'] = $this->wecha_id;
		$where_order['imicms_id'] = (int)($_GET['orderid']);
		$del_order = $this->m_mysellerorder->where($where_order)->delete();
		$where_cart['token'] = $this->token;
		$where_cart['wecha_id'] = $this->wecha_id;
		$where_cart['orderid'] = (int)($_GET['orderid']);
		$del_cart = $this->m_mysellercart->where($where_cart)->delete();
		$this->success("删除成功");
	}
	//确认收货
	public function yes(){
		$where_order['token'] = $this->token;
		$where_order['wecha_id'] = $this->wecha_id;
		$where_order['imicms_id'] = (int)($_GET['orderid']);
		$save_order['state'] = 2;
		$update_order = $this->m_mysellerorder->where($where_order)->save($save_order);
		$this->success("确认成功");
	}
	//个人资料
	public function myinfo(){
		$where_user['wecha_id'] = $this->wecha_id;
		$where_user['token'] = $this->token;
		$find_user = $this->m_user->where($where_user)->find();
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();
		if($find_user['name'] == null){
			$find_user['name'] = $find_userinfo['wechaname'];
		}
		$find_user['phone'] = $find_userinfo['tel'];
		$this->assign("user",$find_user);
		$this->display();
	}
	//修改个人资料
	public function stupmyinfo(){
		$where_user['wecha_id'] = $this->wecha_id;
		$where_user['token'] = $this->token;
		$find_user = $this->m_user->where($where_user)->find();
		if($find_user == null){
			$add_user['token'] = $this->token;
			$add_user['wecha_id'] = $this->wecha_id;
			$add_user['address'] = $_GET['address'];
			$add_user['name'] = $_GET['name'];
			$id_usre = $this->m_user->add($add_user);
			$save_userinfo['tel'] = $_GET['phone'];
			$update_userinfo = $this->m_userinfo->where($where_user)->save($save_userinfo);
		}else{
			$save_user['address'] = $_GET['address'];
			$save_user['name'] = $_GET['name'];
			$update_user = $this->m_user->where($where_user)->save($save_user);
			$save_userinfo['tel'] = $_GET['phone'];
			$update_userinfo = $this->m_userinfo->where($where_user)->save($save_userinfo);
		}
		$this->success("修改成功");
	}
	//把经纬度写入session
	public function latlng(){
		session("mylat",$_GET['mylat']);
		session("mylng",$_GET['mylng']);
		$this->redirect("LivingCircle/index",array("token"=>$this->token));
	}
	//ajax
	public function ajax(){
		switch($_POST['type']){
			case 'indextjsjlist':
				$where_seller['token'] = $this->token;
				$where_seller['recommend'] = 1;
				$seller_list = $this->m_seller->where($where_seller)->order("num asc,addtime desc")->select();
				foreach($seller_list as $vo){
					$where_company['id'] = $vo['cid'];
					$where_company['token'] = $this->token;
					$find_company = $this->m_company->where($where_company)->find();
					if($find_company['display'] == 1){
						$distance[$vo['imicms_id']] = $this->getDistance_map($_POST['lat'],$_POST['lng'],$find_company['latitude'],$find_company['longitude']);
					}
				}
				asort($distance);
				$i = 0;
				$tjsjlist = '<li class=\'divider\'>推荐商家</li>';
				foreach($distance as $k=>$v){
					$i++;
					if($i <= 20){
						$where_seller2['token'] = $this->token;
						$where_seller2['imicms_id'] = $k;
						$find_seller2 = $this->m_seller->where($where_seller2)->find();
						$where_company2['token'] = $this->token;
						$where_company2['id'] = $find_seller2['cid'];
						$find_company2 = $this->m_company->where($where_company2)->find();
						$tjsjlist.='<li style=\'min-height:70px;\'><a href=\''.U("LivingCircle/seller",array("token"=>$this->token,"sellerid"=>$k)).'\'><img class=\'lazy\' data-original=\''.$find_company2['logourl'].'\' src=\''.$find_company2['logourl'].'\' style=\'display: block;\'/><p class=\'strong\'>'.$find_company2['name'].'</p><p>'.$find_company2['address'].'</p></a></li>';
					}
				}
				$data['tjsjlist'] = $tjsjlist;
				$data['error'] = 0;
				$this->ajaxReturn($data,'JSON');
			break;
			case 'sellerbuy':
				$where_mysellercart['token'] = $this->token;
				$where_mysellercart['goodsid'] = (int)($_POST['goodsid']);
				$where_mysellercart['sellerid'] = (int)($_POST['sellerid']);
				$where_mysellercart['wecha_id'] = $this->wecha_id;
				$where_mysellercart['orderid'] = 0;
				$find_mysellercart = $this->m_mysellercart->where($where_mysellercart)->find();
				if(empty($find_mysellercart)){
					$add_mysellercart['token'] = $this->token;
					$add_mysellercart['wecha_id'] = $this->wecha_id;
					$add_mysellercart['goodsid'] = (int)($_POST['goodsid']);
					$add_mysellercart['sellerid'] = (int)($_POST['sellerid']);
					$add_mysellercart['addtime'] = time();
					$id_mysellercart = $this->m_mysellercart->add($add_mysellercart);
				}else{
					$del_mysellercart = $this->m_mysellercart->where($where_mysellercart)->delete();
				}
				$where_mysellercart_count['token'] = $this->token;
				$where_mysellercart_count['sellerid'] = (int)($_POST['sellerid']);
				$where_mysellercart_count['wecha_id'] = $this->wecha_id;
				$where_mysellercart_count['orderid'] = 0;
				$mysellercart_count = $this->m_mysellercart->where($where_mysellercart_count)->select();
				$sum = count($mysellercart_count);
				$total = 0;
				foreach($mysellercart_count as $vo){
					$where_mysellergoods['token'] = $this->token;
					$where_mysellergoods['imicms_id'] = $vo['goodsid'];
					$price = $this->m_mysellergoods->where($where_mysellergoods)->getField("price");
					$total = $total + $price;
				}
				$data['sum'] = $sum;
				$data['total'] = $total;
				$data['error'] = 0;
				$this->ajaxReturn($data,'JSON');
			break;
			case 'goodsdel':
				$where_mysellercart['token'] = $this->token;
				$where_mysellercart['sellerid'] = (int)($_POST['sellerid']);
				$where_mysellercart['wecha_id'] = $this->wecha_id;
				$where_mysellercart['orderid'] = 0;
				$del_mysellercart = $this->m_mysellercart->where($where_mysellercart)->delete();
				$data['error'] = 0;
				$this->ajaxReturn($data,'JSON');
			break;
			case 'favorite':
				$where_favorite['token'] = $this->token;
				$where_favorite['wecha_id'] = $this->wecha_id;
				$where_favorite['sellerid'] = (int)($_POST['sellerid']);
				$find_favorite = $this->m_favorite->where($where_favorite)->find();
				if(empty($find_favorite)){
					$add_favorite['token'] = $this->token;
					$add_favorite['wecha_id'] = $this->wecha_id;
					$add_favorite['sellerid'] = (int)($_POST['sellerid']);
					$id_favorite = $this->m_favorite->add($add_favorite);
					$data['error'] = 0;
				}else{
					$del_favorite = $this->m_favorite->where($where_favorite)->delete();
					$data['error'] = 1;
				}
				$this->ajaxReturn($data,'JSON');
			break;
			case 'commentlist':
				$where_comment['token'] = $this->token;
				$where_comment['sellerid'] = (int)($_POST['sellerid']);
				$p = 5;
				$commentlist = $this->m_comment->where($where_comment)->order('addtime desc')->limit($p)->select();
				$data['commentlist'] = "";
				foreach($commentlist as $k=>$v){
					$where_userinfo['token'] = $this->token;
					$where_userinfo['wecha_id'] = $v['wecha_id'];
					$userinfo = $this->m_userinfo->where($where_userinfo)->find();
					if($userinfo == ""){
						$userinfo['wechaname'] = "游客";
					}
					$data['commentlist'].= "<li class='comment'>".$userinfo['wechaname']."：<span class='grade'><i class='icon star-2'></i>";
					if($v['star'] > 1){
						$data['commentlist'].= "<i class='icon star-2'></i>";
					}else{
						$data['commentlist'].= "<i class='icon star'></i>";
					}
					if($v['star'] > 2){
						$data['commentlist'].= "<i class='icon star-2'></i>";
					}else{
						$data['commentlist'].= "<i class='icon star'></i>";
					}
					if($v['star'] > 3){
						$data['commentlist'].= "<i class='icon star-2'></i>";
					}else{
						$data['commentlist'].= "<i class='icon star'></i>";
					}
					if($v['star'] > 4){
						$data['commentlist'].= "<i class='icon star-2'></i>";
					}else{
						$data['commentlist'].= "<i class='icon star'></i>";
					}
					$data['commentlist'].= "</span><span class='huise' style='text-align:right'>&nbsp;&nbsp;".date("Y-m-d H:i",$v['addtime'])."</span><br/>".$v['info']."</li>";
				}
				$count = $this->m_comment->where($where_comment)->count();
				if($p < $count){
					$data['more'] = $p;
				}
				$data['count'] = $count;
				$data['error'] = 0;
				$this->ajaxReturn($data,'JSON');
			break;
			case 'addcomment':
				$add_comment['token'] = $this->token;
				$add_comment['wecha_id'] = $this->wecha_id;
				$add_comment['star'] = $_POST['iconcount'];
				$add_comment['info'] = $_POST['commentinfo'];
				$add_comment['sellerid'] = (int)($_POST['sellerid']);
				$add_comment['addtime'] = time();
				$id_comment = $this->m_comment->add($add_comment);
				if($id_comment > 0){
					$data['error'] = 0;
				}
				$this->ajaxReturn($data,'JSON');
			break;
			case 'morecomment':
				$where_comment['token'] = $this->token;
				$where_comment['sellerid'] = (int)($_POST['sellerid']);
				$i = (int)($_POST['i']);
				$p = 5;
				$commentlist = $this->m_comment->where($where_comment)->order('addtime desc')->limit($i.','.$p)->select();
				$data['commentlist'] = "";
				foreach($commentlist as $k=>$v){
					$where_userinfo['token'] = $this->token;
					$where_userinfo['wecha_id'] = $v['wecha_id'];
					$userinfo = $this->m_userinfo->where($where_userinfo)->find();
					$data['commentlist'].= "<li class='comment'>".$userinfo['wechaname']."：<span class='grade'><i class='icon star-2'></i>";
					if($v['star'] > 1){
						$data['commentlist'].= "<i class='icon star-2'></i>";
					}else{
						$data['commentlist'].= "<i class='icon star'></i>";
					}
					if($v['star'] > 2){
						$data['commentlist'].= "<i class='icon star-2'></i>";
					}else{
						$data['commentlist'].= "<i class='icon star'></i>";
					}
					if($v['star'] > 3){
						$data['commentlist'].= "<i class='icon star-2'></i>";
					}else{
						$data['commentlist'].= "<i class='icon star'></i>";
					}
					if($v['star'] > 4){
						$data['commentlist'].= "<i class='icon star-2'></i>";
					}else{
						$data['commentlist'].= "<i class='icon star'></i>";
					}
					$data['commentlist'].= "</span><span class='huise' style='text-align:right'>&nbsp;&nbsp;".date("Y-m-d H:i",$v['addtime'])."</span><br/>".$v['info']."</li>";
				}
				$count = $this->m_comment->where($where_comment)->count();
				if(($p+$i) < $count){
					$data['more'] = ($p+$i);
				}
				$data['count'] = $count;
				$data['error'] = 0;
				$this->ajaxReturn($data,'JSON');
			break;
		}
	}
	//计算两经纬度间的距离
    private function getDistance_map($lat_a, $lng_a, $lat_b, $lng_b) {
        //R是地球半径（米）
        $R = 6377830;
        $pk = doubleval(180 / 3.1415926);

        $a1 = doubleval($lat_a / $pk);
        $a2 = doubleval($lng_a / $pk);
        $b1 = doubleval($lat_b / $pk);
        $b2 = doubleval($lng_b / $pk);

        $t1 = doubleval(cos($a1) * cos($a2) * cos($b1) * cos($b2));
        $t2 = doubleval(cos($a1) * sin($a2) * cos($b1) * sin($b2));
        $t3 = doubleval(sin($a1) * sin($b1));
        $tt = doubleval(acos($t1 + $t2 + $t3));

        return round($R * $tt);
    }

}
?>