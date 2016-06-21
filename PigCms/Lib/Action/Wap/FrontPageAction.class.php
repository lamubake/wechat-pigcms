<?php

class FrontPageAction extends WapAction
{
	public $action_id;

	public function _initialize()
	{
		parent::_initialize();
		
		$this->action_id = $this->_get('id', 'intval');
		D('Userinfo')->convertFake(M('frontpage_users'), array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'fakeopenid' => $this->fakeopenid));
	}

	public function index()
	{
		if ($this->action_id == '') {
			$this->error('参数错误');
		}

		$cacahe_action = S($this->token . '_' . $this->action_id . '_frontaction');

		if ($cacahe_action != '') {
			$action = $cacahe_action;
		}
		else {
			$action = M('frontpage_action')->where(array('id' => $this->action_id))->find();
			S($this->token . '_' . $this->action_id . '_frontaction', $action);
		}

		if (empty($action)) {
			$this->error('活动不存在');
		}
		else {
			if (!empty($action) && ($action['status'] != 1)) {
				$this->error('活动未开启');
			}
			else {
				if (!empty($action) && (time() < $action['start_time'])) {
					$this->error('活动未开始');
				}
				else {
					if (!empty($action) && ($action['end_time'] < time())) {
						$this->error('活动已结束');
					}
				}
			}
		}

		if ($this->wecha_id != '') {
			$loginuser = M('frontpage_users')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->action_id))->find();
			$fuwuuserinfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();

			if (empty($loginuser)) {
				$data = array();
				$data['cid'] = $this->action_id;
				$data['wecha_id'] = $this->wecha_id;
				$data['wecha_name'] = !empty($fuwuuserinfo['wechaname']) ? $fuwuuserinfo['wechaname'] : '匿名用户';
				$data['wecha_pic'] = !empty($fuwuuserinfo['portrait']) ? $fuwuuserinfo['portrait'] : $this->siteUrl . '/tpl/User/default/common/images/portrait.jpg';
				$data['phone'] = !empty($fuwuuserinfo['tel']) ? $fuwuuserinfo['tel'] : 'no';
				$data['token'] = $this->token;
				$data['share_key'] = substr(md5(time() . mt_rand(1000, 9999) . $this->wecha_id), 0, 32);
				$data['addtime'] = $_SERVER['REQUEST_TIME'];
				$userid = M('frontpage_users')->add($data);
				$this->assign('userid', $userid);
				$this->assign('wecha_pic', $data['wecha_pic']);
				$username = $data['wecha_name'];
			}
			else {
				if (($fuwuuserinfo['tel'] != '') || ($fuwuuserinfo['portrait'] != '') || ($fuwuuserinfo['wechaname'] != '')) {
					$savedata = array('phone' => $fuwuuserinfo['phone'], 'wecha_pic' => $fuwuuserinfo['portrait'], 'wecha_name' => $fuwuuserinfo['wechaname']);
					M('frontpage_users')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->action_id))->save($savedata);
				}

				$username = $loginuser['wecha_name'];
				$this->assign('wecha_pic', $loginuser['wecha_pic']);
				$this->assign('userid', $loginuser['id']);
			}
		}
		else {
			$this->error('参数错误');
		}

		$action['custom_sharetitle'] = $username . '正在参加“' . $action['action_name'] . '”活动，速来围观吧！';
		$action['custom_sharedsc'] = '“' . $action['action_name'] . '”活动,可以自己定义新闻,让指定的人轻松上头条,快来参加吧！';
		$this->public_notice($action, $fuwuuserinfo['tel']);
		$this->clear_create_day(array('token' => $this->token, 'action_id' => $this->action_id, 'wecha_id' => $this->wecha_id));
		$this->assign('action_id', $this->action_id);
		$this->assign('actioninfo', $action);
		$this->display();
	}

	public function MakeNews()
	{
		if (IS_AJAX) {
			if (($_POST['token'] == '') || ($_POST['userid'] == '') || ($_POST['action_id'] == '')) {
				echo json_encode(array('status' => 'error', 'msg' => '参数错误'));
				exit();
			}

			$cacahe_action = S($_POST['token'] . '_' . $_POST['action_id'] . '_frontaction');

			if ($cacahe_action != '') {
				$action = $cacahe_action;
			}
			else {
				$action = M('frontpage_action')->where(array('id' => (int) $_POST['action_id']))->find();
				S($_POST['token'] . '_' . $_POST['action_id'] . '_frontaction', $action);
			}

			$user = M('frontpage_users')->where(array('token' => trim($_POST['token']), 'wecha_id' => $this->wecha_id, 'cid' => (int) $_POST['action_id']))->find();
			if ((0 < $action['day_create']) && ($action['day_create'] <= $user['today_create'])) {
				echo json_encode(array('status' => 'error', 'msg' => '超过今日生成条数限制'));
				exit();
			}

			if ($action['total_create'] <= $user['total_create']) {
				echo json_encode(array('status' => 'error', 'msg' => '超过总生成条数限制'));
				exit();
			}

			$data = array();
			$data['frontpage_name'] = trim($_POST['frontpage_name']);
			if (($data['frontpage_name'] == '') || ($data['frontpage_name'] == '请输入姓名')) {
				echo json_encode(array('status' => 'error', 'msg' => '请输入姓名'));
				exit();
			}

			$news_type = intval($_POST['news_type']);

			if ($news_type == 1) {
				$randNews = $this->randNews(array('token' => $_POST['token'], 'action_id' => $_POST['action_id'], 'per' => $_POST['per']));
				if (($randNews['news_txt'] == '') || ($randNews['news_title'] == '')) {
					echo json_encode(array('status' => 'error', 'msg' => '没有符合条件的随机事件,请选择自定义事件'));
					exit();
				}

				$randNews['news_title'] = str_replace('[name]', $data['frontpage_name'], $randNews['news_title']);
				$randNews['news_txt'] = str_replace('[name]', $data['frontpage_name'], $randNews['news_txt']);
				$data['news_title'] = trim($randNews['news_title']);
				$data['news_txt'] = trim($randNews['news_txt']);
				$data['per'] = isset($_POST['per']) ? intval($_POST['per']) : intval($randNews['per']);
				$data['frontpage_img'] = $randNews['default_img'];
			}
			else {
				$data['per'] = mt_rand(0, 1);
				$data['news_title'] = trim($_POST['news_title']);
				$data['news_txt'] = trim($_POST['news_txt']);

				if ($data['news_title'] == '') {
					echo json_encode(array('status' => 'error', 'msg' => '新闻标题不能为空'));
					exit();
				}

				if ($data['news_txt'] == '') {
					echo json_encode(array('status' => 'error', 'msg' => '新闻内容不能为空'));
					exit();
				}
			}

			$data['spd'] = 7;
			$data['token'] = trim($_POST['token']);
			$data['tok'] = $this->getRandToken();

			if ($data['tok'] != '') {
				$media = $this->getMedia($data);

				if ($media['status'] == 'success') {
					$data['userid'] = (int) $_POST['userid'];
					$data['wecha_name'] = (string) $user['wecha_name'];
					$data['create_time'] = $_SERVER['REQUEST_TIME'];
					$data['voicepath'] = trim($media['msg']);
					$data['status'] = 1;
					$data['news_type'] = 2;
					$data['cid'] = $_POST['action_id'];
					$addnewid = M('frontpage_makenews')->add($data);

					if ($addnewid) {
						M('frontpage_users')->where(array('token' => trim($_POST['token']), 'wecha_id' => $this->wecha_id, 'cid' => (int) $_POST['action_id']))->save(array(
	'total_create' => array('exp', 'total_create+1'),
	'today_create' => array('exp', 'today_create+1')
	));
						echo json_encode(array('status' => 'success', 'msg' => $addnewid));
						exit();
					}
					else {
						echo json_encode(array('status' => 'error', 'msg' => '生成失败'));
						exit();
					}
				}
				else if ($media['status'] == 'error') {
					echo json_encode(array('status' => 'error', 'msg' => $media['msg']));
					exit();
				}
			}
			else {
				echo json_encode(array('status' => 'error', 'msg' => '接口令牌获取失败'));
				exit();
			}
		}
	}

	public function loadingNews()
	{
		$news_id = $this->_get('newsid', 'intval');
		$cid = $this->_get('id', 'intval');
		$opentype = $this->_get('opentype', 'trim');
		$this->assign('news_id', $news_id);
		$this->assign('cid', $cid);
		$this->assign('opentype', $opentype);
		$news_title = M('frontpage_makenews')->where(array('id' => $news_id))->getField('news_title');
		$this->assign('news_title', $news_title);
		$this->display();
	}

	public function playNews()
	{
		$news_id = $this->_get('newsid', 'intval');
		$cid = $this->_get('id', 'intval');
		$news = M('frontpage_makenews')->where(array('id' => $news_id))->find();

		if (!empty($news)) {
			$this->assign('news', $news);
		}
		else {
			$this->error('该新闻不存在');
		}

		$cacahe_action = S($this->token . '_' . $cid . '_frontaction');

		if ($cacahe_action != '') {
			$action = $cacahe_action;
		}
		else {
			$action = M('frontpage_action')->where(array('id' => $cid))->find();
			S($this->token . '_' . $cid . '_frontaction', $action);
		}

		if ((strpos($action['remind_link'], '{siteUrl}') !== false) || (strpos($action['remind_link'], '{wechat_id}') !== false)) {
			$action['remind_link'] = str_replace(array('{siteUrl}', '{wechat_id}'), array($this->siteUrl, $this->wecha_id), $action['remind_link']);
		}

		$action['custom_sharetitle'] = $action['custom_sharetitle'] != '' ? str_replace(array('{{username}}'), array($news['frontpage_name']), $action['custom_sharetitle']) : $news['frontpage_name'] . '上新闻头条了！';
		$action['custom_sharedsc'] = $action['custom_sharedsc'] != '' ? str_replace(array('{{title}}'), array($news['news_title']), $action['custom_sharedsc']) : trim($news['news_title']);
		$other_news = M('frontpage_makenews')->where(array(
	'cid'    => $cid,
	'token'  => $this->token,
	'userid' => array(
		array('neq', $news['userid']),
		array('neq', 0)
		)
	))->order('create_time desc')->limit(0, $action['latest_count'])->select();
		$userinfo = M('frontpage_users')->where(array('id' => $news['userid']))->find();
		$this->assign('actioninfo', $action);
		$this->assign('other_news', $other_news);
		$this->assign('userinfo', $userinfo);
		$this->assign('opentype', $this->_get('opentype', 'trim'));
		$this->display();
	}

	private function randNews($array = '')
	{
		if (empty($array['token']) || empty($array['action_id'])) {
			return false;
		}

		$per = ($array['per'] == 1 ? 'm' : 'f');
		$notper = ($array['per'] == 1 ? 0 : 1);
		$frontpagenews = include './PigCms/Lib/ORG/FrontPageNews.php';
		$token = trim($array['token']);
		$action_id = intval($array['action_id']);
		$frontpage_news = M('frontpage_makenews')->where(array(
	'news_type' => 1,
	'token'     => $token,
	'cid'       => $action_id,
	'status'    => 1,
	'per'       => array('neq', $notper)
	))->select();
		$findnews = (!empty($frontpage_news) ? $frontpage_news : array());
		$default_id = M('frontpage_action')->where(array('id' => $action_id))->getField('defaultnews_hide');
		$default_id = explode(',', $default_id);

		foreach ($frontpagenews as $key => $val) {
			if (in_array($val['id'], $default_id) || (($val['per'] != 'both') && ($val['per'] != $per))) {
				unset($frontpagenews[$key]);
			}
		}

		$default_news = (!empty($frontpagenews) ? $frontpagenews : array());
		$News = array_merge($default_news, $findnews);
		$randNews = array();
		$keys = array_keys($News);
		$key = array_rand($keys, 1);
		$randnum = $keys[$key];
		$randNews = $News[$randnum];
		$defaultImg = M('frontpage_newsimg')->where(array('news_id' => $randNews['id'], 'cid' => $action_id, 'token' => $token))->getField('default_img');

		if ($defaultImg) {
			$randNews['default_img'] = $defaultImg;
		}

		if (!empty($randNews)) {
			return $randNews;
		}
		else {
			return false;
		}
	}

	private function getRandToken()
	{
		$configure = M('frontpage_configure')->where(array('token' => $this->token))->select();
		$validtoken = array();

		foreach ($configure as $key => $val) {
			if (($_SERVER['REQUEST_TIME'] - $val['addtime']) < $val['expires_in']) {
				$validtoken[] = $val['access_token'];
			}
		}

		$randkey = array_rand($validtoken, 1);
		return $validtoken[$randkey];
	}

	public function getMedia($post = '')
	{
		if (($post['news_txt'] == '') || ($post['tok'] == '')) {
			return false;
		}

		$url = 'http://tsn.baidu.com/text2audio';
		$params['post'] = array();
		$params['post']['tex'] = $post['news_txt'];
		$params['post']['lan'] = 'zh';
		$params['post']['tok'] = $post['tok'];
		$params['post']['ctp'] = 1;
		$params['post']['cuid'] = md5(substr(time() . $params['post']['tok'], 0, 32));
		$params['post']['per'] = $post['per'] == 1 ? $post['per'] : 0;
		$params['post']['spd'] = $post['spd'] ? $post['spd'] : 5;
		$binary_file = HttpClient::getInstance()->post($url, $params);
		$result = json_decode($binary_file, true);
		if (($result['err_no'] != '') && ($result['err_msg'] != '')) {
			return array('status' => 'error', 'msg' => $result['err_msg']);
		}
		else {
			$up_domainname = (C('up_domainname') ? str_replace('http://', '', C('up_domainname')) : '');
			$upload_type = ((C('upload_type') != '') && ($up_domainname != '') ? C('upload_type') : 'local');

			if ($upload_type == 'upyun') {
				$temppath = RUNTIME_PATH . substr($params['post']['cuid'], 0, 6) . '.mp3';
				file_put_contents($temppath, $binary_file);
				$json = $this->Upyun_upload($temppath);
				$decode_json = json_decode($json, true);
				unlink($temppath);
				if (($decode_json['code'] == 200) && ($decode_json['message'] == 'ok')) {
					$filepath = 'http://' . $up_domainname . $decode_json['url'];
					return array('status' => 'success', 'msg' => $filepath);
				}
				else {
					return array('status' => 'error', 'msg' => $decode_json['message']);
				}
			}
			else {
				$firstLetter = substr($this->token, 0, 1);
				$savePath = './uploads/frontpage/' . $firstLetter . '/' . $this->token . '/';
				if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/uploads') || !is_dir($_SERVER['DOCUMENT_ROOT'] . '/uploads')) {
					mkdir($_SERVER['DOCUMENT_ROOT'] . '/uploads', 511);
				}

				$firstDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/frontpage/';
				if (!file_exists($firstDir) || !is_dir($firstDir)) {
					mkdir($firstDir, 511);
				}

				$firstLetterDir = $firstDir . $firstLetter;
				if (!file_exists($firstLetterDir) || !is_dir($firstLetterDir)) {
					mkdir($firstLetterDir, 511);
				}

				if (!file_exists($firstLetterDir . '/' . $this->token) || !is_dir($firstLetterDir . '/' . $this->token)) {
					mkdir($firstLetterDir . '/' . $this->token, 511);
				}

				if (!file_exists($savePath) || !is_dir($savePath)) {
					mkdir($savePath, 511);
				}

				$filePath = $savePath . substr($params['post']['cuid'], 0, 6) . '.mp3';
				file_put_contents($filePath, $binary_file);
				$filePath = $this->siteUrl . '/' . trim($filePath, './');
				return array('status' => 'success', 'msg' => $filePath);
			}
		}
	}

	public function ajaxImgUpload()
	{
		$filename = trim($_POST['filename']);
		$img = $_POST[$filename];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$imgdata = base64_decode($img);
		$getupload_dir = '/uploads/frontpage/' . date('Ymd');
		$upload_dir = '.' . $getupload_dir;

		if (!is_dir($upload_dir)) {
			mkdir($upload_dir, 511, true);
		}

		$newfilename = 'frontpage_' . date('YmdHis') . '.jpg';
		$save = file_put_contents($upload_dir . '/' . $newfilename, $imgdata);
		$up_domainname = (C('up_domainname') ? str_replace('http://', '', C('up_domainname')) : '');
		$upload_type = ((C('upload_type') != '') && ($up_domainname != '') ? C('upload_type') : 'local');

		if ($upload_type == 'upyun') {
			$json = $this->Upyun_upload($upload_dir . '/' . $newfilename);
			$decode_json = json_decode($json, true);
			unlink($upload_dir . '/' . $newfilename);
			if (($decode_json['code'] == 200) && ($decode_json['message'] == 'ok')) {
				$this->dexit(array(
	'error' => 0,
	'data'  => array('code' => 1, 'url' => 'http://' . $up_domainname . $decode_json['url'], 'msg' => '')
	));
			}
			else {
				$this->dexit(array(
	'error' => 1,
	'data'  => array('code' => 0, 'url' => '', 'msg' => $decode_json['message'])
	));
			}
		}
		else if ($save) {
			$this->dexit(array(
	'error' => 0,
	'data'  => array('code' => 1, 'url' => $this->siteUrl . $getupload_dir . '/' . $newfilename, 'msg' => '')
	));
		}
		else {
			$this->dexit(array(
	'error' => 1,
	'data'  => array('code' => 0, 'url' => '', 'msg' => '保存失败！')
	));
		}
	}

	public function Upyun_upload($resource = '')
	{
		$resource = $_SERVER['DOCUMENT_ROOT'] . str_replace(array('./'), array('/'), $resource);

		if (!@file_exists($resource)) {
			return json_encode(array('code' => 1000, 'message' => '上传文件不存在'));
		}

		$bucket = C('up_bucket');
		$form_api_secret = C('up_form_api_secret');
		if (empty($bucket) || empty($form_api_secret)) {
			return json_encode(array('code' => 1002, 'message' => '参数错误,请登录总后台在[站点管理-附件设置]中设置正确的值'));
		}

		$options = array();
		$options['bucket'] = $bucket;
		$options['expiration'] = time() + 600;
		$options['save-key'] = '/' . $this->token . '/{year}/{mon}/{day}/' . time() . '_{random}{.suffix}';
		$options['allow-file-type'] = C('up_exts');
		$options['content-length-range'] = '0,' . (intval(C('up_size')) * 1024);
		$policy = base64_encode(json_encode($options));
		$signature = md5($policy . '&' . $form_api_secret);
		$requestUrl = 'http://v0.api.upyun.com/' . $bucket;
		$respon_json = $this->postCurl($requestUrl, array('file' => '@' . $resource, 'policy' => $policy, 'signature' => $signature));
		return $respon_json;
	}

	public function public_notice($action_info = '', $tel = '')
	{
		$stat = true;
		if (($action_info['is_follow'] == 0) && ($this->isSubscribe() == false)) {
			$this->assign('notice_content', 'no_follow');
			$this->memberNotice('', 1);
			$stat = false;
		}
		else {
			if (($action_info['is_register'] == 1) && ($tel == '') && ($action_info['sms_verify'] != 1)) {
				$this->assign('notice_content', 'no_register');
				$this->memberNotice('');
				$stat = false;
			}
			else {
				$this->assign('notice_content', '');
			}
		}

		return $stat;
	}

	public function postCurl($url, $data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_TIMEOUT, 40);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$exec = curl_exec($ch);

		if ($exec) {
			curl_close($ch);
			return $exec;
		}
		else {
			$errno = curl_errno($ch);
			$error = curl_error($ch);
			curl_close($ch);
			return json_encode(array('errcode' => $errno, 'errmsg' => $error));
		}
	}

	private function clear_create_day($cache_parameter = '')
	{
		$token = $cache_parameter['token'];
		$action_id = $cache_parameter['action_id'];
		$wecha_id = $cache_parameter['wecha_id'];
		if (($token != '') && ($action_id != '') && ($wecha_id != '')) {
			if (S($token . '_' . $action_id . '_' . $wecha_id . '_frontpage') == '') {
				$today_time = strtotime(date('Y-m-d 00:00:00', $_SERVER['REQUEST_TIME']));
				$evening_time = strtotime(date('Y-m-d 23:59:59', $_SERVER['REQUEST_TIME']));
				$cache_time = $evening_time - $_SERVER['REQUEST_TIME'];
				$where = 'cid = ' . $action_id . ' and token = \'' . $token . '\' and wecha_id = \'' . $wecha_id . '\'';
				$frontpage_users = M('frontpage_users')->where($where)->find();

				if (!empty($frontpage_users)) {
					M('frontpage_users')->where($where)->save(array('today_create' => 0));
				}

				S($token . '_' . $action_id . '_' . $wecha_id . '_frontpage', 1, $cache_time);
			}
		}
	}

	private function dexit($data = '')
	{
		if (is_array($data)) {
			echo json_encode($data);
		}
		else {
			echo $data;
		}

		exit();
	}
}

?>
