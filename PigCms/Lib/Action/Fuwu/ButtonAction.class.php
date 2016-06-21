<?php

require './PigCms/Lib/ORG/Fuwu/HttpRequst.php';
require './PigCms/Lib/ORG/Fuwu/aop/AopClient.php';
require './PigCms/Lib/ORG/Fuwu/AlipaySign.php';
class ButtonAction extends UserAction
{
	protected $appid;

	public function _initialize()
	{
		parent::_initialize();
		$model = M('Wxuser')->where(array('token' => $this->token))->find();
		$this->appid = $model['fuwuappid'];

		if (empty($this->appid)) {
			$this->error('AppId(服务窗) 不能为空，请填写保存后再进行生成菜单', '?g=User&m=Index&a=edit&id=' . $model['id']);
		}

		
	}

	public function dataReturn()
	{
		$r = $this->apiData('alipay.mobile.public.menu.add', $this->ButtonCreate());

		if ($r['alipay_mobile_public_menu_add_response']['code'] == 11022) {
			$r = $this->apiData('alipay.mobile.public.menu.add', $this->ButtonCreate(11022));
		}

		if ($r['alipay_mobile_public_menu_add_response']['code'] == 11013) {
			$r = $this->apiData('alipay.mobile.public.menu.update', $this->ButtonCreate());

			if ($r['alipay_mobile_public_menu_update_response']['code'] == 200) {
				$this->success('生成菜单成功');
			}
			else {
				$this->error('生成菜单失败~，' . $r['alipay_mobile_public_menu_update_response']['code']);
			}
		}
		else if ($r['alipay_mobile_public_menu_add_response']['code'] == 200) {
			$this->success('生成菜单成功');
		}
		else {
			$this->error('生成菜单失败~，' . $r['alipay_mobile_public_menu_add_response']['code']);
		}
	}

	public function apiData($method, $biz_content)
	{
		$url = 'https://openapi.alipay.com/gateway.do';
		$data = array('app_id' => $this->appid, 'method' => $method, 'charset' => 'UTF-8', 'sign_type' => 'RSA', 'timestamp' => date('Y-m-d H:i:s', time()), 'biz_content' => $biz_content, 'version' => '1.0');
		require './PigCms/Lib/ORG/Fuwu/config.php';
		$AlipaySign = new AlipaySign();
		$data['sign'] = $AlipaySign->rsa_sign($this->buildQuery($data), $config['merchant_private_key_file']);
		$re = new HttpRequest();
		$result = $re->sendPostRequst($url, $data);
		return json_decode(iconv('GBK', 'UTF-8', $result), true);
	}

	public function ButtonCreate($code = '')
	{
		$limit = (11022 == $code ? 3 : 4);
		$topMenu = M('Diymen_class')->where(array('token' => session('token'), 'pid' => 0, 'is_show' => 1))->limit($limit)->order('sort DESC, id ASC')->select();
		$data = $this->getData($topMenu);
		return '{"button":' . $data . '}';
	}

	public function getData($list)
	{
		$data = array();
		$subArr = array();
		$i = 0;

		foreach ($list as $key => $value) {
			$sub = M('Diymen_class')->where(array('pid' => $value['id']))->limit(5)->order('sort DESC, id ASC')->select();
			$data[$i]['name'] = $value['title'];

			if ($sub) {
				$data[$i]['subButton'] = json_decode($this->getData($sub), true);
			}
			else if ($value['url']) {
				if (strpos($value['url'], 'tel:') !== false) {
					$data[$i]['actionParam'] = ltrim($value['url'], 'tel:');
					$data[$i]['actionType'] = 'tel';
				}
				else {
					$data[$i]['actionParam'] = str_replace('&wecha_id={wechat_id}', '', htmlspecialchars_decode(str_replace('{siteUrl}', $this->siteUrl, $value['url'])));
					$data[$i]['actionType'] = 'link';
				}
			}
			else if ($value['keyword']) {
				$data[$i]['actionParam'] = $value['keyword'];
				$data[$i]['actionType'] = 'out';
			}
			else if ($value['tel']) {
				$data[$i]['actionParam'] = $this->siteUrl . U('Wap/Index/tel', array('tel' => $value['tel'], 'token' => session('token')));
				$data[$i]['actionType'] = 'link';
			}
			else if ($value['nav']) {
				$data[$i]['actionParam'] = $this->siteUrl . U('Wap/Index/map', array('nav' => $value['nav'], 'name' => urlencode($value['title']), 'token' => session('token')));
				$data[$i]['actionType'] = 'link';
			}

			$i++;
		}

		return json_encode($data);
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

?>
