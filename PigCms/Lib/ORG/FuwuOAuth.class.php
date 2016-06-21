<?php

class FuwuOAuth
{
	public function index($token)
	{
		$app_id = M('Wxuser')->where(array('token' => $token))->getField('fuwuappid');

		if ($_GET['auth_code'] == '') {
			$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$api_url = 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id=' . $app_id . '&auth_skip=false&scope=auth_userinfo,auth_contact&redirect_uri=' . urlencode($redirect_uri);
			echo '<script>window.location.href=\'' . $api_url . '\'</script>';
		}
		else {
			$auth_code = $_GET['auth_code'];
			$fuwuurl = 'https://openapi.alipay.com/gateway.do';
			$fuwudata = array('app_id' => $app_id, 'method' => 'alipay.system.oauth.token', 'charset' => 'UTF-8', 'sign_type' => 'RSA', 'timestamp' => date('Y-m-d H:i:s', time()), 'version' => '1.0', 'grant_type' => 'authorization_code', 'code' => $auth_code);
			require './PigCms/Lib/ORG/Fuwu/config.php';
			$AlipaySign = new AlipaySign();
			ksort($fuwudata);
			$params = array();

			foreach ($fuwudata as $key => $value) {
				$params[] = $key . '=' . $value;
			}

			$fuwudata_build = implode('&', $params);
			$fuwudata['sign'] = $AlipaySign->rsa_sign($fuwudata_build, $config['merchant_private_key_file']);
			$re = new HttpRequest();
			$fuwu_result = $re->sendPostRequst($fuwuurl, $fuwudata);
			$return = json_decode(iconv('GBK', 'UTF-8', $fuwu_result), true);

			if ($return['alipay_system_oauth_token_response']['access_token'] != '') {
				$auth_token = $return['alipay_system_oauth_token_response']['access_token'];
				$userinfo_url = 'https://openapi.alipay.com/gateway.do';
				$userinfo_data = array('app_id' => $app_id, 'method' => 'alipay.user.userinfo.share', 'charset' => 'GBK', 'sign_type' => 'RSA', 'timestamp' => date('Y-m-d H:i:s', time()), 'version' => '1.0', 'auth_token' => $auth_token);
				ksort($userinfo_data);
				$params2 = array();

				foreach ($userinfo_data as $key2 => $value2) {
					$params2[] = $key2 . '=' . $value2;
				}

				$userinfo_data_build = implode('&', $params2);
				$userinfo_data['sign'] = $AlipaySign->rsa_sign($userinfo_data_build, $config['merchant_private_key_file']);
				$userinfo_result = $re->sendPostRequst($userinfo_url, $userinfo_data);
				$userinfo_return = json_decode(iconv('GBK', 'UTF-8', $userinfo_result), true);

				if ($userinfo_return['alipay_user_userinfo_share_response']['user_id'] != '') {
					$m_fuwuuser = M('fuwuuser');
					$where_fuwuuser['wecha_id'] = 'z_' . md5($userinfo_return['alipay_user_userinfo_share_response']['user_id']);
					$where_fuwuuser['token'] = $token;
					$fuwuuser = $m_fuwuuser->where($where_fuwuuser)->find();

					if ($fuwuuser == '') {
						$add_fuwuuser = $userinfo_return['alipay_user_userinfo_share_response'];
						$add_fuwuuser['wecha_id'] = 'z_' . md5($userinfo_return['alipay_user_userinfo_share_response']['user_id']);
						$add_fuwuuser['token'] = $token;
						$add_fuwuuser['addtime'] = time();
						$id_fuwuuser = $m_fuwuuser->add($add_fuwuuser);
					}
					else {
						$save_fuwuuser = $userinfo_return['alipay_user_userinfo_share_response'];
						$update_fuwuuser = $m_fuwuuser->where($where_fuwuuser)->save($save_fuwuuser);
					}

					$wecha_id = 'z_' . md5($userinfo_return['alipay_user_userinfo_share_response']['user_id']);
					return $wecha_id;
				}
			}
		}
	}

	public function buildQuery($query)
	{
		if (!$query) {
			return NULL;
		}

		ksort($query);
		$params = array();

		foreach ($query as $key => $value) {
			$params[] = $key . '=' . $value;
		}

		$data = implode('&', $params);
		return $data;
	}
}

require './PigCms/Lib/ORG/Fuwu/HttpRequst.php';
require './PigCms/Lib/ORG/Fuwu/aop/AopClient.php';
require './PigCms/Lib/ORG/Fuwu/AlipaySign.php';

?>
