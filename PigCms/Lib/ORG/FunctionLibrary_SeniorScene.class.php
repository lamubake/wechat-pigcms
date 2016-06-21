<?php
class FunctionLibrary_SeniorScene{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'<font color="red">场景</font>',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$arr=array(
				'name'=>'场景',
				'subkeywords'=>array(
				),
				'sublinks'=>array(
				),
			);
			
			$db		= M('Senior_scene');
			$where	=array('token'=>$this->token);
			$items 	= $db->where($where)->order('add_time DESC')->select();
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['name'],'keyword'=>$v['keyword']);
				}
			}
			
			$wxinfo = M('Wxuser')->where(array('uid' => intval(session('uid')), 'token' => $this->token))->find();
			$PData = array('uname' => $this->token, 'domain' => $_SERVER['HTTP_HOST'], 'email' => $wxinfo['qq'], 'gzh' => $wxinfo['wxid'], 'gzhname' => $wxinfo['wxname']);
			$key = "Y@2T&9i3l#m8u"; /*         * *加密KEY,请求两边要一样** */
			$tmp = array();
			foreach ($PData as $kk => $vv) {
				$tmp[] = md5($kk . trim($vv) . $key);
			}
			$key = base64_encode(implode('_', $tmp));
			$PData['key'] = $key;
			$request_url = 'http://www.meihua.com/index.php?m=Index&c=extend&a=scene_list';
			$responsearr = $this->httpRequest($request_url, 'POST', $PData);
			$scene_list = json_decode($responsearr[1],true);
			
			if($scene_list){
				foreach ($scene_list as $v){
					$arr['sublinks'][$v['tid']]=array('name'=>$v['title'],'link'=>'{changjingUrl}/index.php?m=Wap&c=view&a=index&tid='.$v['tid']);
				}
			}
			
			return $arr;	
		}
	}
	public function httpRequest($url, $method, $postfields = null, $headers = array(), $debug = false){
		$method=strtoupper($method);
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }
		$ssl =preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
        curl_setopt($ci, CURLOPT_URL, $url);
		if($ssl){
		  curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
		  curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
		}
		//curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
		curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
        $response = curl_exec($ci);
		$requestinfo=curl_getinfo($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            echo "=====info===== \r\n";
            print_r($requestinfo);

            echo "=====response=====\r\n";
            print_r($response);
        }
        curl_close($ci);
        return array($http_code, $response,$requestinfo);
    }
}