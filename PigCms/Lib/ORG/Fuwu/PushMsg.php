<?php

require_once FUWU_PATH.'HttpRequst.php';

require_once FUWU_PATH.'AopSdk.php';

class PushMsg {
	//测试
	public function test() {
		$image_text_msg1 = $this->mkImageTextMsg ( "标题", "描述", "http://wap.taobao.com", "https://i.alipayobjects.com/e/201310/1H9ctsy9oN_src.jpg", "loginAuth" );
		$image_text_msg2 = $this->mkImageTextMsg ( "标题", "描述", "http://wap.taobao.com", "https://i.alipayobjects.com/e/201310/1H9ctsy9oN_src.jpg", "loginAuth" );
		// 组装多条图文信息
		$image_text_msg = array (
				$image_text_msg1,
				$image_text_msg2 
		);
		
		
		$toUserId = "xLF-4RvtNKGlYDC8xLgTnI97w0QKRHRl-OmymTOxsGHnKDWiwQekMHiEi06tEbjg01";
		// $toUserId="BM7PjM8f8-v6VFqeTlFUqo97w0QKRHRl-OmymTOxsGHnKDWiwQekMHiEi06tEbjg01";
		$biz_content = $this->mkImageTextBizContent ( $toUserId, $image_text_msg );

		print_r ( $this->sendRequest ( $biz_content ) );
	}
	
	// 纯文本消息
	public function mkTextMsg($content) {
		$text = array (
				'content' => $content 
		);
		return $text;
	}
	
	// 图文消息，
	// $authType=loginAuth时，用户点击链接会将带有auth_code，可以换取用户信息
	public function mkImageTextMsg($title, $desc, $url, $imageUrl, $authType) {
		$articles_arr = array (
				'actionName' => iconv ( "UTF-8", "GBK", "立即查看" ),
				'desc' => iconv ( "UTF-8", "GBK", $desc ),
				'imageUrl' => $imageUrl,
				'title' => iconv ( "UTF-8", "GBK", $title ),
				'url' => $url,
				'authType' => $authType 
		);
		return $articles_arr;
	}
	
	/**
	 * 返回图文消息的biz_content
	 *
	 * @param string $toUserId        	
	 * @param array $articles        	
	 * @return string
	 */
	public function mkImageTextBizContent($toUserId, $articles) {
		$biz_content = array (
				'msgType' => 'image-text',
				'createTime' => time (),
				'articles' => $articles 
		);
		return $this->toBizContentJson ( $biz_content, $toUserId );
	}
	/**
	 * 返回纯文本消息的biz_content
	 *
	 * @param unknown $toUserId        	
	 * @param unknown $text        	
	 * @return string
	 */
	public function mkTextBizContent($toUserId, $text) {
		$biz_content = array (
				'msgType' => 'text',
				'text' => $text 
		);
		return $this->toBizContentJson ( $biz_content, $toUserId );
	}
	private function toBizContentJson($biz_content, $toUserId) {
		// 如果toUserId为空，则是发给所有关注的而用户，且不可删除，慎用
		if (isset ( $toUserId ) && ! empty ( $toUserId )) {
			$biz_content ['toUserId'] = $toUserId;
		}
		
		$content = $this->JSON ( $biz_content );
		return $content;
	}
	public function sendRequest($biz_content) {
		$custom_send = new AlipayMobilePublicMessageCustomSendRequest ();
		$custom_send->setBizContent ( $biz_content );
		
		require FUWU_PATH.'config.php';
		$aop = new AopClient ();
		$aop->appId = FUWU_APPID;
		
		$aop->rsaPrivateKeyFilePath = $config ['merchant_private_key_file'];
		$result = $aop->execute ( $custom_send );
		return $result;
	}
	function is_utf8($text) {
		$e = mb_detect_encoding ( $text, array (
				'UTF-8',
				'GBK' 
		) );
		switch ($e) {
			case 'UTF-8' : // 如果是utf8编码
				return true;
			case 'GBK' : // 如果是gbk编码
				return false;
		}
	}
	
	/**
	 * 异步发送消息给用户
	 *
	 * @param string $biz_content        	
	 * @param string $isMultiSend
	 *        	如果发给所有人，则此参数必须为true，且biz_content中的toUserId必须为空
	 * @return string
	 */
	public function sendMsgRequest($biz_content, $isMultiSend = FALSE) {
		require FUWU_PATH.'config.php';
		$paramsArray = array (
				'method' => "alipay.mobile.public.message.custom.send",
				'biz_content' => $biz_content,
				'charset' => $config ['charset'],
				'sign_type' => 'RSA',
				'app_id' => FUWU_APPID,
				'timestamp' => date ( 'Y-m-d H:i:s', time () ) 
		);
		if ($isMultiSend) {
			$paramsArray ['method'] = "alipay.mobile.public.message.total.send";
		}
		require_once FUWU_PATH.'AlipaySign.php';
		$as = new AlipaySign ();
		$sign = $as->sign_request ( $paramsArray, $config ['merchant_private_key_file'] );
		$paramsArray ['sign'] = $sign;
		// print_r ( $paramsArray );
		// 日志记录下受到的请求
		file_put_contents ( "log.txt", var_export ( $paramsArray, true ) . "\r\n", FILE_APPEND );
		return HttpRequest::sendPostRequst ( $config ['gatewayUrl'], $paramsArray );
	}
	
	/**
	 * ************************************************************
	 *
	 * 使用特定function对数组中所有元素做处理
	 *
	 * @param
	 *        	string &$array 要处理的字符串
	 * @param string $function
	 *        	要执行的函数
	 * @return boolean $apply_to_keys_also 是否也应用到key上
	 * @access public
	 *        
	 *         ***********************************************************
	 */
	protected function arrayRecursive(&$array, $function, $apply_to_keys_also = false) {
		foreach ( $array as $key => $value ) {
			if (is_array ( $value )) {
				$this->arrayRecursive ( $array [$key], $function, $apply_to_keys_also );
			} else {
				$array [$key] = $function ( $value );
			}
			
			if ($apply_to_keys_also && is_string ( $key )) {
				$new_key = $function ( $key );
				if ($new_key != $key) {
					$array [$new_key] = $array [$key];
					unset ( $array [$key] );
				}
			}
		}
	}
	
	/**
	 * ************************************************************
	 *
	 * 将数组转换为JSON字符串（兼容中文）
	 *
	 * @param array $array
	 *        	要转换的数组
	 * @return string 转换得到的json字符串
	 * @access public
	 *        
	 *         ***********************************************************
	 */
	protected function JSON($array) {
		$this->arrayRecursive ( $array, 'urlencode', true );
		$json = json_encode ( $array );
		return urldecode ( $json );
	}
}