<?php

class CustomTmplsAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		$this->canUseFunction('CustomTmpls');
	}

	public function dynamic()
	{
		$where['token'] = session('token');
		$serverUrl = 'http://www.meihua.com';

		if ($_GET['style']) {
			$data['dynamicTmpls'] = $this->_get('style', 'intval');
			$data['tpltypeid'] = 0;
			$data['tpltypename'] = '';
			M('Wxuser')->where($where)->save($data);
			M('Home')->where(array('token' => session('token')))->save(array('advancetpl' => 0));
			exit();
		}

		$game = new game();
		$url = $serverUrl . '/index.php?m=Api&c=tmpls&a=lists&uid=' . $this->getuid();
		$rt = $game->api_notice_increment($url, array(), 'GET');
		$tmpls = json_decode($rt, true);
		$wxuser = M('Wxuser')->where($where)->field('id,dynamicTmpls')->find();
		$dynamicTmpls = $wxuser['dynamicTmpls'];
		$this->assign('serverUrl', $serverUrl);
		$this->assign('tmpls', $tmpls);
		$this->assign('dynamicTmpls', $dynamicTmpls);
		$this->display();
	}

	public function getuid()
	{
		$data = $this->config($this->token, $this->wxuser['wxname'], $this->wxuser['wxid'], $this->wxuser['headerpic'], '');

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
		$serverUrl = 'http://www.meihua.com';
		$domainarr = explode('.', trim($_SERVER['HTTP_HOST']));
		unset($domainarr[0]);
		$data = array('username' => implode('.', $domainarr) . $token, 'wxname' => $wxname, 'domain' => $_SERVER['HTTP_HOST'], 'wxid' => $wxid, 'wxlogo' => urlencode($wxlogo), 'platformLogo' => urlencode(C('site_logo')), 'link' => urlencode($link));
		$game = new game();
		$url = $serverUrl . '/index.php?m=Api&c=public&a=userInfo';
		$rt = $game->api_notice_increment($url, $data, 'POST');
		return json_decode($rt, 1);
	}

	public function myDynamic()
	{
		$vhost = 'http://www.meihua.com';
		$wxinfo = M('Wxuser')->where(array('uid' => intval(session('uid')), 'token' => $this->token))->find();
		$PData = array('uname' => $this->token, 'domain' => $_SERVER['HTTP_HOST'], 'email' => $wxinfo['qq'], 'gzh' => $wxinfo['wxid'], 'gzhname' => $wxinfo['wxname']);
		$key = 'Y@2T&9i3l#m8u';
		$tmp = array();

		foreach ($PData as $kk => $vv) {
			$tmp[] = md5($kk . trim($vv) . $key);
		}

		$game = new game();
		$key = base64_encode(implode('_', $tmp));
		$PData['key'] = $key;
		$request_url = $vhost . '/index.php?m=Index&c=login&a=verifyUser';
		$tmpdata = $game->api_notice_increment($request_url, $PData, 'POST');

		if ($tmpdata == '') {
			$tmpdata = $game->api_notice_increment($request_url, $PData, 'POST');
		}

		$type = $this->_get('type', 'trim');
		unset($_GET['token']);
		$iframeUrl = $vhost . '/index.php?m=Index&c=login&a=loginNoPwd&logkey=' . $tmpdata . '&' . http_build_query($_GET);
		$this->assign('iframeUrl', $iframeUrl);
		$this->display();
	}
}

?>
