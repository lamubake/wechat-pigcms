<?php
class RecognitionAction extends UserAction{
	public $thisWxUser;
	public $access_token;
	public $isgostr;
	public function _initialize(){
		parent::_initialize();
		if(ALI_FUWU_GROUP){
			$isgostr = '只有认证的服务号或者服务窗才可以使用！';
		}else{
			$isgostr = '只有认证的服务号才可以使用！';
		}
		$this->isgostr = $isgostr;
		$this->assign('isgostr',$this->isgostr);
		if(intval($this->wxuser['winxintype']) != 3 && $this->wxuser['fuwuappid'] == ''){
			$this->error($isgostr);exit;
		}
		$where=array('token'=>$this->token);
		$this->thisWxUser=M('Wxuser')->where($where)->find();
		
		$apiOauth 	= new apiOauth();
		$this->access_token 	= $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
	}
	
	public function index(){
		if(IS_POST){
			if(intval($this->wxuser['winxintype']) == 3 || $this->wxuser['fuwuappid'] != ''){
				$this->all_insert('Recognition');
			}else{
				$this->error($isgostr);exit;
			}
		}else{
			$db=D('Recognition');
			$where=array('token'=>session('token'));
			$count=$db->where($where)->count();
			$page=new Page($count,25);
			$wechat_group_db=M('Wechat_group');
			$list=$db->where($where)->limit($page->firstRow.','.$page->listRows)->order('id desc')->select();
			foreach ($list as $key => $value) {
				$list[$key]['group'] = $wechat_group_db->where(array('token'=>$this->token,'wechatgroupid'=>$value['groupid']))->getField('name');
			}
			
			$groups=$wechat_group_db->where(array('token'=>$this->token))->order('id ASC')->select();
			$this->assign('groups',$groups);
			$this->assign('page',$page->show());
			$this->assign('list',$list);
			if(ALI_FUWU_GROUP){
				$fuwu = 'yes';
			}else{
				$fuwu = 'no';
			}
			$this->assign('fuwu',$fuwu);
			$this->display();
		}
	}
	public function showqr(){
		$qrurl = urldecode($_GET['qrurl']);
		$this->show("<img src='".$qrurl."' />");
	}
	public function get_code(){
			if(intval($this->wxuser['winxintype']) != 3){
				$this->error('只有认证的服务号才可以使用！');exit;
			}
			$where=array('id'=>$this->_get('id','intval'),'token'=>session('token'));
			$GetDb=M('Recognition');
			$recognition=$GetDb->where($where)->field('id')->find();
			if($recognition == false) $this->error('非法操作');
			
			$qrcode_url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->access_token;
			//{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}
			$data['action_name']='QR_LIMIT_SCENE';
			$data['action_info']['scene']['scene_id']=$recognition['id'];
			$post=$this->api_notice_increment($qrcode_url,json_encode($data));
			if($post ==false ) $this->error('微信接口返回信息错误，请联系管理员');
			$update=$GetDb->where(array_merge(array('id'=>$recognition['id']),$where))->save(array('code_url'=>$post));
			if($update !=false){
				$this->success('获取成功');
			}else{
				$this->error('操作失败');
			}
	}
	public function fuwu_code(){
		if(intval($this->wxuser['winxintype']) != 3 && $this->wxuser['fuwuappid'] == ''){
			$this->error($isgostr);exit;
		}
		require './PigCms/Lib/ORG/Fuwu/HttpRequst.php';
		require './PigCms/Lib/ORG/Fuwu/aop/AopClient.php';
		require './PigCms/Lib/ORG/Fuwu/AlipaySign.php';
		$sceneId = (int)($_GET['id']);
		$biz_content = '{"codeInfo": {"scene": {"sceneId": "'.$sceneId.'"}},"codeType": "PERM","expireSecond": "","showLogo": "N"}';
		$app_id = M("Wxuser")->where(array('token'=>$this->token))->getField('fuwuappid');
		$url = 'https://openapi.alipay.com/gateway.do';
		$data = array(
			'app_id'      => $app_id, 
			'method'      => 'alipay.mobile.public.qrcode.create',
			'charset'     => 'UTF-8', 
			'sign_type'   => 'RSA', 
			'timestamp'   => date('Y-m-d H:i:s',time()), 
			'biz_content' => $biz_content,
			'version'     => '1.0', 
			);

		require './PigCms/Lib/ORG/Fuwu/config.php';

		
		$AlipaySign   = new AlipaySign();
		$data['sign'] = $AlipaySign->rsa_sign($this->buildQuery($data),$config['merchant_private_key_file']);
		
		$re           = new HttpRequest();
		$result       = $re->sendPostRequst($url,$data);

		$return = json_decode(iconv('GBK', 'UTF-8', $result),true);
		if($return['alipay_mobile_public_qrcode_create_response']['code'] == 200){
			$GetDb = M('Recognition');
			$where_GetDb['id'] = $sceneId;
			$save_GetDb['fuwu_code_url'] = $return['alipay_mobile_public_qrcode_create_response']['code_img'];
			$update_GetDb = $GetDb->where($where_GetDb)->save($save_GetDb);
			if($update_GetDb !=false){
				$this->success('获取成功');
			}else{
				$this->error('操作失败');
			}
		}else{
			$this->error('appid不正确');
		}
	}
	/*
	 * 查询参数排序 a-z
	 * */
	public function buildQuery( $query ){
		if ( !$query ) {
			return null;
		}

		ksort( $query );

		//重新组装参数
		$params = array();
		foreach($query as $key => $value){
			$params[] = $key .'='. $value ;
		}
		$data = implode('&', $params);

		return $data;

	}
	/*****微餐饮后台餐桌及餐桌二维码获取*******/
	public function get_Wxticket(){
		    $rid=$this->_get('rid') ? $this->_get('rid','intval'):0;
			$tid=$this->_get('tid') ? $this->_get('tid','intval'):0;
			/*$where=array('id'=>$this->_get('id','intval'),'token'=>session('token'));
			$GetDb=M('Recognition');
			$recognition=$GetDb->where($where)->field('id')->find();
			if($recognition == false) $this->error('非法操作');
			*/
			$db_dish_reply=M('Dish_reply');
			$tmp=$db_dish_reply->where(array('id' => $rid,'token'=>session('token')))->find();
			$reg_id=$tmp['reg_id'];
			$GetDb=M('Recognition');
			if(!($tmp['reg_id']>0)){
			   $reg_id=$GetDb->add(array('token'=>session('token'),'title'=>'餐饮二维码','attention_num'=>0,'keyword'=>$tmp['keyword'],'scene_id'=>0,'status'=>0));
				$db_dish_reply->where(array('id'=>$tmp['id']))->save(array('reg_id'=>$reg_id));
			}
			
			if($this->access_token){
			$qrcode_url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->access_token;
			//{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}
			$data['action_name']='QR_LIMIT_SCENE';
			$data['action_info']['scene']['scene_id']=$reg_id;
			$post=$this->api_notice_increment($qrcode_url,json_encode($data));
			if($post ==false ) $this->error('微信接口返回信息错误，请联系管理员');
			$update=$GetDb->where(array('id'=>$reg_id,'token'=>session('token')))->save(array('code_url'=>$post));
			if($update !=false){
				if($tid>0){
				   $this->success('获取成功',U('Repast/tableEwm',array('token'=>$this->token,'tid'=>$tid)));
				}else{
				  $this->success('获取成功',U('Repast/company',array('token'=>$this->token)));
				}
			}else{
				$this->error('操作失败');
			}
		}else{
		  $this->error('access_token获取失败');
		}
	}
	public function del(){
		$data=D('Recognition');
		$where['id']=$this->_get('id','intval');
		if($where['id']==false) $this->error('非法操作');
		$where['token']=$this->token;
		//dump($where);exit;
		$back=$data->where($where)->delete();
		if($back==false){
			$this->error('操作失败');
		}else{
			$this->success('操作成功');
		}
	}	
	public function status(){
		$data=D('Recognition');
		$where['id']=$this->_get('id','intval');
		if($where['id']==false) $this->error('非法操作');
		$where['token']=session('token');
		$type=$this->_get('type','intval');
		if($type==0){
			$back=$data->where($where)->setInc('status');
		}else{
			$back=$data->where($where)->setDec('status');
		}
		if($back==false){
			$this->error('操作失败');
		}else{
			$this->success('操作成功');
		}
	}
	function api_notice_increment($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			$this->error('发生错误：curl error'.$errorno);
			
		}else{

			$js=json_decode($tmpInfo,true);
			
			if (isset($js['ticket'])){
				return $js['ticket'];
			}else {
				$this->error('发生错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$js['errmsg']);
			}
		}
	}
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}

}
	?>