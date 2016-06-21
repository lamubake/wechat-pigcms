<?php

class Person_cardAction extends UserAction
{
	public $serverUrl = 'http://www.meihua.com';

	public function _initialize()
	{
		parent::_initialize();
	
		$this->canUseFunction('Person_card');

		if ($this->token != $this->_get('token')) {
		}
	}

	public function index()
	{
		import('ORG.Util.Page');
		$uid = $this->getuid();
		$p = intval($this->_get('p'));

		if (!isset($_GET['o'])) {
			$falg = 1;
		}
		else {
			$falg = $this->_get('o', 'intval');

			if ($falg != 2) {
				$falg = 1;
			}
		}

		$data['uid'] = $uid;
		$data['o'] = $falg;
		$url = $this->serverUrl . '/index.php?m=Api&c=card&a=lists&uid=' . $uid . '&o=' . $falg;
		$rt = $this->api_notice_increment($url, array(), 'get');
		$cards = json_decode($rt, true);

		if ($p) {
			$url = $url . '&p=' . $p;
			$rt = $this->api_notice_increment($url, array(), 'get');
			$cards = json_decode($rt, true);
		}

		$page = new Page($cards['count_number'], 15);
		$this->assign('num', $cards['count_number']);
		unset($cards['count_number']);
		$show = $page->show();
		$this->assign('serverUrl', $this->serverUrl);
		$this->assign('card', $cards);
		$this->assign('falg', $falg);
		$this->assign('page', $show);
		$this->display();
	}

	public function weimp_add()
	{
		if (IS_POST) {
			$data['token'] = $this->token;
			$data['portrait'] = $this->_post('touX');
			$data['font_color'] = $this->_post('color');

			if ($data['font_color'] == '') {
				$data['font_color'] = '#ffffff';
			}

			if ($this->_post('Bgbody') != '') {
				$data['background'] = $this->_post('Bgbody');
				$data['background_id'] = 1;
			}

			if ($this->_post('Bgbody1') != '') {
				$data['background'] = $this->_post('Bgbody1');
				$data['background_id'] = 2;
			}

			if ($this->_post('Bgbody2') != '') {
				$data['background'] = $this->_post('Bgbody2');
				$data['background_id'] = 3;
			}

			$data['username'] = $this->_post('name');
			$data['position'] = $this->_post('position');
			$data['company'] = $this->_post('company');
			$data['domain'] = $this->_post('url');
			$data['mail'] = $this->_post('mail');
			$data['mobile'] = $this->_post('tel');
			$data['tel'] = $this->_post('phone');
			$data['fax'] = $this->_post('fax');
			$data['address'] = $this->_post('address');
			$data['remark'] = $this->_post('mark');
			$data['regtime'] = time();
			$mban_id = $this->_post('radios', 'intval');
			if (isset($mban_id) && ($mban_id != '')) {
				$data['mould_id'] = $mban_id;
			}

			$data['uid'] = $this->getuid();
			$data['id'] = intval($this->_get('id'));

			if ($data['id'] == 0) {
				unset($data['id']);
				$data['type'] = 'add';

				if ($data['background'] == '') {
					$data['background'] = '#1dca1d';
					$data['background_id'] = 3;
				}
			}
			else {
				$data['type'] = 'updata';

				if ($data['background'] == '') {
					unset($data['background']);
				}
			}

			$url = $this->serverUrl . '/index.php?m=Api&c=card&a=set';
			$rt = $this->api_notice_increment($url, $data);
			$cards = json_decode($rt, true);
			if (($cards['status'] == 1) && ($data['type'] == 'add')) {
				$this->success('恭喜您成功创建微名片！', U('Person_card/index'));
			}

			if (($cards['status'] != 1) && ($data['type'] == 'add')) {
				$this->error('创建微名片失败！');
			}

			if (($cards['status'] == 1) && ($data['type'] == 'updata')) {
				$this->success('恭喜您成功更新微名片！', U('Person_card/index'));
			}

			if (($cards['status'] != 1) && ($data['type'] == 'updata')) {
				$this->error('更新微名片失败！');
			}
		}
	}

	public function design()
	{
		$data['id'] = intval($this->_get('id'));
		$data['uid'] = $this->getuid();

		if (!$data['uid']) {
		}

		$url = $this->serverUrl . '/index.php?m=Api&c=card&a=edit';
		$rt = $this->api_notice_increment($url, $data);
		$cards = json_decode($rt, true);

		foreach ($cards as $key => $values) {
			$cards = $values;
		}

		$this->assign('card', $cards);
		$this->display();
	}

	public function del()
	{
		$data['id'] = intval($this->_get('id'));
		$data['uid'] = $this->getuid();
		if ($data['id'] && $data['uid']) {
			$url = $this->serverUrl . '/index.php?m=Api&c=card&a=del';
			$rt = $this->api_notice_increment($url, $data);
			$cards = json_decode($rt, true);

			if ($cards['status'] == 1) {
				$this->success('删除名片成功！', U('Person_card/index'));
			}
			else {
				$this->error('删除名片失败！');
			}
		}
		else {
			$this->error('非法操作！');
		}
	}

	public function delALL()
	{
		$data['uid'] = $this->getuid();
		$ids = $this->_post('id');
		$data['id'] = $ids;
		$url = $this->serverUrl . '/index.php?m=Api&c=card&a=delall';
		$rt = $this->api_notice_increment($url, $data);
		$cards = json_decode($rt, true);

		if ($cards) {
			echo json_encode($cards);
		}
		else {
			$this->error('非法操作！');
		}
	}

	public function forward()
	{
		$id = intval($_GET['id']);
		$zhuanfa = $this->_post('content');
		$data['uid'] = $this->getuid();
		$data['id'] = $id;
		$data['forward_content'] = $zhuanfa;
		$url = $this->serverUrl . '/index.php?m=Api&c=card&a=update';
		$rt = $this->api_notice_increment($url, $data);
		$cards = json_decode($rt, true);

		if ($cards) {
			echo json_encode($cards);
		}
		else {
			$this->error('非法操作！');
		}
	}

	public function picer()
	{
		$uid = $this->getuid();
		$id = intval($_GET['id']);
		$url = $this->serverUrl . '/index.php?m=Wap&c=card&a=index&uid=' . $uid . '&id=' . $id;
		$content = $this->qrcode($url);
		$file_size = strlen($content);
		header('Pragma: public');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Cache-Control: private', false);
		header('Content-Type: image/png');
		header('Content-Disposition: attachment; filename=qrcode.png');
		header('Content-Transfer-Encoding: binary');
		header('Content-Length: ' . $file_size);
		echo $content;
	}

	public function qrcode($content = '')
	{
		include './PigCms/Lib/ORG/phpqrcode.php';
		$errorLevel = 'L';
		$size = '4';
		$file_path = './uploads/images';
		$file_name = md5(mt_rand(11111111, 99999999)) . '.png';

		if (!is_dir($file_path)) {
			@mkdir($file_path, 511);
		}

		QRcode::png($content, $file_path . '/' . $file_name, $errorLevel, $size);
		$file = fopen($file_path . '/' . $file_name, 'r');
		$result = fread($file, filesize($file_path . '/' . $file_name));
		fclose($file);

		if ($result) {
			chmod($file_path . '/' . $file_name, 511);
			@unlink($file_path . '/' . $file_name);
			return $result;
		}
	}

	public function getuid()
	{
		$config = $this->wxuser;
		$data = $this->config($this->token, $config['wxname'], $config['wxid'], $config['headerpic'], '');

		if ($data['status']) {
			$uid = $data['data'];
		}
		else {
			echo $data['data'];
			exit();
		}

		return $uid;
	}

	public function config($token, $wxname, $wxid, $wxlogo, $link)
	{
		$data = array('username' => trim(C('server_topdomain')) . '_' . $token, 'wxname' => $wxname, 'domain' => $_SERVER['HTTP_HOST'], 'wxid' => $wxid, 'wxlogo' => urlencode($wxlogo), 'link' => urlencode($link));
		$url = $this->serverUrl . '/index.php?m=Api&c=public&a=userInfo';
		$rt = $this->api_notice_increment($url, $data);
		return json_decode($rt, 1);
	}

	public function api_notice_increment($url, $data, $method = 'POST')
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

		if (strtoupper($method) == 'POST') {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno = curl_errno($ch);

		if ($errorno) {
			return Http::fsockopenDownload($url);
			return array('rt' => false, 'errorno' => $errorno);
		}
		else {
			return $tmpInfo;
		}
	}
}

?>
