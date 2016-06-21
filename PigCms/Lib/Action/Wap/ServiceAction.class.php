<?php
class ServiceAction extends WapAction{
	public function _initialize(){
		parent::_initialize();
		/*$checkFunc=new checkFunc();if (!function_exists('fdsrejsie3qklwewerzdagf4ds')){exit('error-4');}
        $checkFunc->cfdwdgfds3skgfds3szsd3idsj();*/
		//加载数据库
		$this->m_service = M("service_setup");
		$this->m_my = M("service_my");
		$this->m_preferential = M("service_preferential");
		$this->m_wechat = M('wechat_group_list');
		$this->m_wxuser = M('wxuser');
		$this->m_mywxuser = M('service_wxuser');
		
		if($_POST['app_id'] != '' && $this->token == ''){
			$where_mywxuser_token['app_id'] = $_POST['app_id'];
			$mywxuser_token = $this->m_mywxuser->where($where_mywxuser_token)->find();
			$this->token = $mywxuser_token['token'];
		}
	}
	public function index(){
		$where_mywxuser['token'] = $this->token;
		$mywxuser = $this->m_mywxuser->where($where_mywxuser)->find();
		if($mywxuser['state'] == 1){
			$data['app_id'] = $mywxuser['app_id'];
			$data['openid'] = $this->wecha_id;
			$key = $this->set_key($data,$mywxuser['app_key']);
			$url = "http://im-link.meihua.com/?app_id={$mywxuser['app_id']}&openid={$this->wecha_id}&key={$key}#serviceList";
			//$this->show("<script>window.location.href='{$url}'</script>");
			$this->assign("url",$url);
		}else{
			$this->error('客服功能未开启');
		}
		$this->display();
	}
	public function chat(){
		$where_mywxuser['token'] = $this->token;
		$mywxuser = $this->m_mywxuser->where($where_mywxuser)->find();
		if($mywxuser['app_id'] == ''){
			$this->error("请开通客服功能才可以使用");
			exit;
		}
		$data['app_id'] = $mywxuser['app_id'];
		$data['openid'] = $this->wecha_id;
		$key = $this->set_key($data,$mywxuser['app_key']);
		$url = "http://im-link.meihua.com/?app_id={$mywxuser['app_id']}&openid={$this->wecha_id}&key={$key}";
		$this->show("<script>window.location.href='{$url}'</script>");
	}
	//返回优惠活动数据
	public function activity(){
		$where_mywxuser['token'] = $this->token;
		$mywxuser = $this->m_mywxuser->where($where_mywxuser)->find();
		$mydata['app_id'] = $mywxuser['app_id'];
		$key = $this->set_key($mydata,$mywxuser['app_key']);
		if($key == $_POST['key']){
			$preferential = $this->m_preferential->where(array('token'=>$this->token))->select();
			foreach($preferential as $k=>$v){
				$predata[$k]['title'] = $v['name'];
				$predata[$k]['intro'] = $v['info'];
				$predata[$k]['url'] = $this->getLink($v['url']);
				$predata[$k]['image'] = $v['img'];
			}
			$data['err_code'] = 0;
			$data['data'] = $predata;
			exit($_GET['callback'].'('.json_encode($data).')');
		}else{
			$data['err_code'] = 1;
			$data['err_msg'] = '用户不匹配';
			exit($_GET['callback'].'('.json_encode($data).')');
		}
	}
	//制作key
	public function set_key($data,$app_key){
		$new_arr = array();
		ksort($data);
		foreach($data as $k=>$v){
			$new_arr[] = $k.'='.$v;
		}
		$new_arr[] = 'app_key='.$app_key;
		$str = implode('&',$new_arr);
		return md5($str);
	}
	//test msgtip
	public function testmsgtip(){
		$data['app_id'] = 5;
		$data['to_openid'] = "ora52t_ee7BNcQ83RCrr0WfdPob0";
		$data['nickname'] = "kefu";
		$data['url'] = 'http://www.baidu.com';
		$data['key'] = '123';
		$url = $this->siteUrl."/index.php?g=Wap&m=Service&a=msgtip&token=".$this->token;
		$dump = json_decode($this->https_request($url,$data), true);
		dump($dump);
	}
	//通知
	public function msgtip(){
		$where_mywxuser['token'] = $this->token;
		$mywxuser = $this->m_mywxuser->where($where_mywxuser)->find();
		$mydata['app_id'] = $mywxuser['app_id'];
		$key = $this->set_key($mydata,$mywxuser['app_key']);
		if($key != $_POST['key']){
			$this->servicesay($_POST['to_openid'],$_POST['from_nickname'],$_POST['url']);
		}else{
			$data['err_code'] = 1;
			$data['err_msg'] = '用户不匹配';
			echo json_encode($data);
		}
	}
	//发送客服消息
	public function servicesay($to_openid,$from_nickname,$url){
		$where_wxuser['token'] = $this->token;
		$appid = $this->m_wxuser->where($where_wxuser)->getField("appid");
		$secret = $this->m_wxuser->where($where_wxuser)->getField("appsecret");
		$wxuser = $this->m_wxuser->where($where_wxuser)->find();
		//$data['err'] = $this->token;echo json_encode($data);exit;
		if($appid =='' || $secret == ''){
			$data['err_code'] = 1;
			$data['err_msg'] = '公众号没有设置appid和appsecret！';
			echo json_encode($data);
			exit;
		}
		$apiOauth 	= new apiOauth();
		$url_access_token 	= $apiOauth->update_authorizer_access_token($appid,$wxuser);
		//$url_access_token = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}";
		$data_access_token = json_decode($this->https_request($url_access_token), true);
		if($data_access_token['access_token'] != ''){
			$access_token = $data_access_token['access_token'];
			//获取客服列表判断是否有公众号客服
			$url_kf_list = "https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token={$access_token}";
			$kf_list = json_decode($this->https_request($url_kf_list), true);
			//echo $this->https_request($url_kf_list);exit;
			if($kf_list['kf_list'] == ''){
				$data['err_code'] = $kf_list['errcode'];
				$data['err_msg'] = '客服列表获取失败';
				echo json_encode($data);
				exit;
			}
			foreach($kf_list['kf_list'] as $vo){
				if($vo['kf_account'] == "kefu@".$wxuser['weixin']){
					$kefu = $vo['kf_account'];
					$img = $vo['kf_headimgurl'];
				}
			}
				
			//判断是否有公众号客服
			if($kefu != ''){//判断有公众号客服
				//设置客服头像
				if($img == ''){
					$kf_headimgurl_url = "http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token={$access_token}&kf_account={$kefu}";
					$imgurl = str_replace($this->siteUrl,'',$wxuser['headerpic']);
					$imgurl = str_replace('./','/',$imgurl);
					//$data_kf_headimgurl['media'] = "@".$_SERVER['DOCUMENT_ROOT'].str_replace(array('./'),array('/'))."/tpl/User/default/common/images/portrait.jpg;type=image/jpg";
					$data_kf_headimgurl['media'] = "@".$_SERVER['DOCUMENT_ROOT'].$imgurl.";type=image/jpg";
					$uploadheadimg = json_decode($this->https_request($kf_headimgurl_url,$data_kf_headimgurl), true);
					if($uploadheadimg['errcode'] != 0){
						$data['err_code'] = $uploadheadimg['errcode'];
						$data['err_msg'] = '头像设置失败';
						echo json_encode($data);
						exit;
					}
				}
				//$data['headimg'] = $img;echo json_encode($data);exit;
				$fasongdata = '{"touser":"'.$to_openid.'","msgtype":"text","text":{"content":"您的好友'.$from_nickname.'给您发来了一条新的消息,<a href=\''.$url.'\'>点击查看</a>"},"customservice":{"kf_account":"'.$kefu.'"}}';
				$url_send = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
				$send = json_decode($this->https_request($url_send,$fasongdata), true);
				if($send['errcode'] == 0){
					$data['err_code'] = 0;
					$data['err_msg'] = '发送成功';
					echo json_encode($data);
				}else{
					$data['err_code'] = $send['errcode'];
					$data['err_msg'] = '发送失败';
					echo json_encode($data);
					exit;
				}
			}else{//判断没有公众号客服
				$url_kfadd = "https://api.weixin.qq.com/customservice/kfaccount/add?access_token=".$access_token;
				$kf_account = "kefu@".$wxuser['weixin'];
				$data_kfadd = '{"kf_account":"'.$kf_account.'","nickname":"'.mb_substr($wxuser['wxname'],0,6,'utf-8').'","password":"'.md5($this->token).'"}';
				//$data['err_msg'] = $data_kfadd;echo json_encode($data);exit;
				$add = json_decode($this->https_request($url_kfadd,$data_kfadd), true);
				if($add['errcode'] == 0){
					//发送消息
					$fasongdata = '{"touser":"'.$to_openid.'","msgtype":"text","text":{"content":"您的好友'.$from_nickname.'给您发来了一条新的消息,<a href=\''.$url.'\'>点击查看</a>"},"customservice":{"kf_account":"'.$kf_account.'"}}';
					$url_send = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
					$send = json_decode($this->https_request($url_send,$fasongdata), true);
					if($send['errcode'] == 0){
						$data['err_code'] = 0;
						$data['err_msg'] = '发送成功';
						echo json_encode($data);
					}else{
						$data['err_code'] = $send['errcode'];
						$data['err_msg'] = '发送失败';
						echo json_encode($data);
						exit;
					}
				}else{
					$data['err_code'] = $add['errcode'];
					$data['err_msg'] = '添加客服失败'.$data_kfadd;
					echo json_encode($data);
					exit;
				}
			}
		}else{
			$data['err_code'] = $data_access_token['errcode'];
			$data['err_msg'] = '未获取access_token';
			echo json_encode($data);
			exit;
		}
		
	}
	//https请求（支持GET和POST）
    protected function https_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}