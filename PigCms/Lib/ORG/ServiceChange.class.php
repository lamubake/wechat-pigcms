<?php
class ServiceChange{
	public function index($token=""){
		$domain = C("site_url");
		$where['token'] = $token;
		$wxuser = M("wxuser")->where($where)->find();
		$service_wxuser = M("service_wxuser")->where($where)->find();
		if($service_wxuser != '' && $service_wxuser['app_id'] != ''){
			if($service_wxuser['domain'] != $domain || $service_wxuser['wxappid'] != $wxuser['appid'] || $service_wxuser['wxappsecret'] != $wxuser['appsecret']){
				$url = "http://im-link.meihua.com/api/app_change.php";
				$data['app_id'] = $service_wxuser['app_id'];
				$data['domain'] = str_replace('http://','',$domain);
				$data['domain'] = str_replace('https://','',$data['domain']);
				$data['wx_app_id'] = $wxuser['appid'];
				$data['wx_app_secret'] = $wxuser['appsecret'];
				$data['activity_url'] = $domain."/index.php?g=Wap&m=ServiceReturn&a=activity&token=".$token;
				$data['msg_tip_url'] = $domain."/index.php?g=Wap&m=ServiceReturn&a=msgtip&token=".$token;
				$data['my_url'] = $domain."/index.php?g=Wap&m=ServiceReturn&a=my&token=".$token;
				$data['key'] = $this->set_key($data,$service_wxuser['app_key']);
				$open = json_decode($this->https_request($url,$data), true);
				$save_service_wxuser['domain'] = $domain;
				$save_service_wxuser['wxappid'] = $wxuser['appid'];
				$save_service_wxuser['wxappsecret'] = $wxuser['appsecret'];
				$update_service_wxuser = M("service_wxuser")->where($where)->save($save_service_wxuser);
			}
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
	//https请求（支持GET和POST）
    protected function https_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
?>