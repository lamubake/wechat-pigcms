<?php

class CashierAction extends UserAction {
    public function _initialize() {
        parent::_initialize();
        $this->canUseFunction("Cashier");
    }

    public function index(){
		$siteurl=isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
		$siteurl=strtolower($siteurl);
		if(strpos($siteurl,"http:")===false && strpos($siteurl,"https:")===false) $siteurl='http://'.$siteurl;
		$siteurl=rtrim($siteurl,'/');
		$postdata=array('account'=>$this->token,'userid'=>$this->token,'username'=>$this->wxuser['wxname'],'appid'=>$this->wxuser['appid'],'appsecret'=>$this->wxuser['appsecret'],'wxid'=>$this->wxuser['wxid'],'weixin'=>$this->wxuser['weixin'],'logo'=>$this->wxuser['headerpic'],'domain'=>$_SERVER['HTTP_HOST'],'source'=>1,'email'=>$this->user['email']);
		$postdata['sign'] = $this->getSign($postdata);
		$postdataStr=json_encode($postdata);
		$postdataStr=$this->Encryptioncode($postdataStr,'ENCODE');
		$postdataStr=urlencode($postdataStr);
		$request_url=$siteurl.'/merchants.php?m=Index&c=auth&a=getIdentifier';
		$responsearr = $this->httpRequest($request_url, 'POST', $postdataStr);
		$tmpdata = trim($responsearr['1']);
        if (empty($tmpdata)) {
            $responsearr = $this->httpRequest($request_url, 'POST', $postdataStr);
            $tmpdata = trim($responsearr['1']);
        }
		$tmpdata=json_decode($tmpdata,true);
		if(isset($tmpdata['code'])){
		  header('Location: '.$siteurl.'/merchants.php?m=Index&c=auth&a=login&code='.$tmpdata['code']);
		}else{
			//print_r($responsearr);
		    $this->error($tmpdata['message']);
		}
    }

   private function getSign($data) {
	foreach ($data as $key => $value) {
		if (is_array($value)) {
			$validate[$key] = $this->getSign($value);
		} else {
			$validate[$key] = $value;
		}			
	}
	$validate['salt'] = 'pigcmso2oCashier';	//salt
	sort($validate, SORT_STRING);
	return sha1(implode($validate));
  }

/**
 * 加密和解密函数
 *
 * <code>
 * // 加密用户ID和用户名
 * $auth = authcode("{$uid}\t{$username}", 'ENCODE');
 * // 解密用户ID和用户名
 * list($uid, $username) = explode("\t", authcode($auth, 'DECODE'));
 * </code>
 *
 * @access public
 * @param  string  $string    需要加密或解密的字符串
 * @param  string  $operation 默认是DECODE即解密 ENCODE是加密
 * @param  string  $key       加密或解密的密钥 参数为空的情况下取全局配置encryption_key
 * @param  integer $expiry    加密的有效期(秒)0是永久有效 注意这个参数不需要传时间戳
 * @return string
 */
public function Encryptioncode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;
    $key = md5($key != '' ? $key : 'lhs_simple_encryption_code_87063');
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
	}
}

?>