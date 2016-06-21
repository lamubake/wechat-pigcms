<?php

class WeixinAction extends Action
{
	private $token;
	private $fun;
	private $data = array();
	public $fans;
	private $my = '小艾';
	public $wxuser;
	public $apiServer;
	public $siteUrl;
	public $user;
	public $ali;

	public function index($ApiData = '')
	{
		if ($ApiData != '') {
			$_GET = $ApiData;
		}

		$this->ali = 0;
		if (isset($_GET['ali']) && intval($_GET['ali'])) {
			$this->ali = 1;
		}

		$this->siteUrl = C('site_url');

		if (!class_exists('SimpleXMLElement')) {
			exit('SimpleXMLElement class not exist');
		}

		if (!function_exists('dom_import_simplexml')) {
			exit('dom_import_simplexml function not exist');
		}

		if (empty($_GET['token']) && isset($_GET['appid'])) {
			$appid = ltrim($_GET['appid'], '/');
			$this->token = M('Wxuser')->where('appid=\'' . $appid . '\'')->getField('token');
		}
		else {
			$this->token = htmlspecialchars($_GET['token']);
		}

		if ($appid == 'wx570bc396a51b8ff8') {
			$wxarr['type'] = 1;
			$wxarr['encode'] = 2;
			$oauth = new Wechat($appid, $wxarr);
			$apiOauth = new apiOauth();
			$data = $oauth->request();
			$openid = $data['FromUserName'];
			$ToUserName = $data['ToUserName'];

			switch ($data['MsgType']) {
			case 'text':
				if ($data['Content'] == 'TESTCOMPONENT_MSG_TYPE_TEXT') {
					$content = 'TESTCOMPONENT_MSG_TYPE_TEXT_callback';
					$oauth->response($content);
				}
				else if (strstr($data['Content'], 'QUERY_AUTH_CODE')) {
					$auth_code = str_replace('QUERY_AUTH_CODE:', '', $data['Content']);
					$authorization_info = $apiOauth->get_authorization_info($auth_code);
					$access_token = $authorization_info['authorizer_access_token'];
					$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
					$content = $auth_code . '_from_api';
					$call = '{\r\n\t\t\t\t\t\t\t\t\t\'touser\':\'' . $openid . '\',\r\n\t\t\t\t\t\t\t\t\t\'msgtype\':\'text\',\r\n\t\t\t\t\t\t\t\t\t\'text\':\r\n\t\t\t\t\t\t\t\t\t{\r\n\t\t\t\t\t\t\t\t\t\t \'content\':\'' . $content . '\'\r\n\t\t\t\t\t\t\t\t\t}\r\n\t\t\t\t\t\t\t\t}';
					$apiOauth->https_request($url, $call);
				}

				break;

			case 'event':
				$content = $data['Event'] . 'from_callback';
				$oauth->response($content);
				break;
			}

			exit();
		}

		if (($this->token == 'pigcms') || ($this->token == 'qcloud')) {
			$qcloudUser = M('Qcloud_user')->where(array('openid' => $_GET['openId']))->find();
			$this->token = $qcloudUser['token'];
		}

		if (!preg_match('/^[0-9a-zA-Z]{3,42}$/', $this->token)) {
			exit('error token');
		}

		if (!$this->ali) {
			$weixin = new Wechat($this->token, $this->wxuser);
		}

		$this->wxuser = S('wxuser_' . $this->token);
		if (!$this->wxuser || 1) {
			$this->wxuser = D('Wxuser')->where(array('token' => $this->token))->find();
			if (C('agent_version') && intval($this->wxuser['agentid'])) {
				$thisAgent = M('Agent')->where(array('id' => $this->wxuser['agentid']))->find();
				$this->siteUrl = $thisAgent['siteurl'];
			}

			S('wxuser_' . $this->token, $this->wxuser);
		}

		$this->user = M('Users')->where(array('id' => $this->wxuser['uid']))->find();

		if (!$this->ali) {
			$data = $weixin->request();
			$this->data = $weixin->request();
		}

		$this->fans = S('fans_' . $this->token . '_' . $this->data['FromUserName']);
		if (!$this->fans || 1) {
			$this->fans = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']))->find();
			S('fans_' . $this->token . '_' . $this->data['FromUserName'], $this->fans);
		}

		$session_openid_name = 'token_openid_' . $this->token;
		$_SESSION[$session_openid_name] = $this->data['FromUserName'];
		$this->my = C('site_my');
		$this->apiServer = apiServer::getServerUrl();
		$open = M('Token_open')->where(array('token' => $this->token))->find();
		$this->fun = $open['queryname'];

		if (!$this->ali) {
			list($content, $type) = $this->reply($data);

			if (is_array($content)) {
				$content = (empty($content) ? '' : $content);
			}
			else {
				$content = (string) $content;
			}

			if (('' === $content) && !is_array($content)) {
				return '';
			}
			else {
				$weixin->response($content, $type);
			}
		}
		else {
			$data = array();
			$data['Content'] = htmlspecialchars($_GET['keyword']);
			$data['FromUserName'] = htmlspecialchars($_GET['fromUserName']);
			$data['FromUserName'] = 'z_' . md5($data['FromUserName']);
			if (isset($_GET['eventType']) && $_GET['eventType']) {
				$data['Event'] = trim(htmlspecialchars($_GET['eventType']));

				if ($data['Event'] == 'SCAN') {
					$data['EventKey'] = $_GET['EventKey'];
					$eventReplyClassName = 'SCANEventReply';
					class_exists($eventReplyClassName);
					$SCANEventReply = new $eventReplyClassName($this->token, $data['FromUserName'], $data, $this->siteUrl, $this->ali);
					return $SCANEventReply->index();
				}
			}

			$this->data = $data;
			return $this->reply($data);
		}
	}

	private function reply($data)
	{
		$userinfoData = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']))->find();

		if ($userinfoData) {
			M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']))->setField('issub', 1);
		}

		if ($this->wxuser['openphotoprint']) {
			$photoPrint = new photoPrint($this->wxuser, $this->data['FromUserName']);
		}

		if ($this->wxuser['openphotoprint'] && $this->fans['photoprintopen']) {
			return $photoPrint->reply($data);
		}

		if ($this->user['viptime'] < time()) {
			return array('您的账号 ' . $this->user['username'] . ' 已经过期，请联系' . $this->siteUrl . '开通', 'text');
		}

		$eventReplyClassName = $data['Event'] . 'EventReply';

		if (class_exists($eventReplyClassName)) {
			$eventReplyClassName = new $eventReplyClassName($this->token, $this->data['FromUserName'], $data, $this->siteUrl);
			return $eventReplyClassName->index();
		}

		if ('CLICK' == $data['Event']) {
			$data['Content'] = $data['EventKey'];
			$this->data['Content'] = $data['EventKey'];
		}
		else if ($data['Event'] == 'SCAN') {
			if ($this->wxuser['openphotoprint']) {
				$photoPrint->initUser();
			}

			$data['Content'] = $this->getRecognition($data['EventKey']);
			$this->data['Content'] = $data['Content'];
		}
		else if ($data['Event'] == 'MASSSENDJOBFINISH') {
			M('Send_message')->where(array('msg_id' => $data['msg_id']))->save(array('reachcount' => $data['SentCount']));
		}
		else if ('subscribe' == $data['Event']) {
			if ($this->wxuser['openphotoprint']) {
				$photoPrint->initUser();
			}

			$this->requestdata('follownum');
			$follow_data = M('Areply')->field('home,keyword,content')->where(array('token' => $this->token))->find();
			$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
			$apidata = $this->api_notice_increment('http://we-cdn.net', $xml, 1);
			$subscribe = new subscribe($this->token, $this->data['FromUserName'], $data, $this->siteUrl, $xml);
			$subscribe->sub();

			if (!(strpos($data['EventKey'], 'qrscene_') === false)) {
				$eventReplyClassName = 'SCANEventReply';
				class_exists($eventReplyClassName);
				$data['EventKey'] = str_replace('qrscene_', '', $data['EventKey']);
				$SCANEventReply = new $eventReplyClassName($this->token, $this->data['FromUserName'], $data, $this->siteUrl);
				return $SCANEventReply->index();
			}

			if ($follow_data['home'] == 1) {
				if ((trim($follow_data['keyword']) == '首页') || ($follow_data['keyword'] == 'home')) {
					return $this->shouye();
				}
				else if (trim($follow_data['keyword']) == '我要上网') {
					return $this->wysw();
				}

				return $this->keyword($follow_data['keyword']);
			}
			else {
				if ($follow_data['keyword'] != '') {
                    return $this->keyword($follow_data['keyword']);
                } else {
                    $guanhuai = M('wxuser')->where(array('token' => $this->token))->find();
                    if ($guanhuai['guanhuai'] == 1) {
                        //用户关怀
                        $access_token = $this->_getAccessToken();
                        $b = $this->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $this->data['FromUserName'] . '');
                        $jsonr = json_decode($b, 1);
                        $c = $this->curlGet('https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $access_token . '');
                        $jsonr_a = json_decode($c, 1);
                        $jsonr['total'] = $jsonr_a['total'];
                        $guanhuai['url'] = str_replace('&amp;', '&', $guanhuai['url']);
                        $guanhuai['title1'] = str_replace('nickname', $jsonr['nickname'], $guanhuai['title1']);
                        $guanhuai['title1'] = str_replace('openid', $jsonr['openid'], $guanhuai['title1']);
                        $guanhuai['title1'] = str_replace('city', $jsonr['city'], $guanhuai['title1']);
                        $guanhuai['title1'] = str_replace('province', $jsonr['province'], $guanhuai['title1']);
                        $guanhuai['title1'] = str_replace('country', $jsonr['country'], $guanhuai['title1']);
                        $guanhuai['title1'] = str_replace('total', $jsonr['total'], $guanhuai['title1']);
                        if ($jsonr['sex'] = '1') {
                            $guanhuai['title1'] = str_replace('sex', '男', $guanhuai['title1']);
                        } else {
                            $guanhuai['title1'] = str_replace('sex', '女', $guanhuai['title1']);
                        }
                        return array(array(array($guanhuai['title1'], strip_tags(htmlspecialchars_decode($guanhuai['text'])), $jsonr['headimgurl'], $guanhuai['url'])), 'news');
                    }
					////用户关怀end
				}
			   return array(html_entity_decode($follow_data['content']), 'text');
			}
		}
		else if ('unsubscribe' == $data['Event']) {
			$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
			$apidata = $this->api_notice_increment('http://we-cdn.net', $xml, 1);
			$subscribe = new subscribe($this->token, $this->data['FromUserName'], $data, $this->siteUrl, $xml);
			$subscribe->unsub();
			$this->requestdata('unfollownum');
		}
		else if ($data['Event'] == 'LOCATION') {
			return $this->nokeywordApi();
		}

		if ($data['Content'] == 'wechat ip') {
			return array($_SERVER['REMOTE_ADDR'], 'text');
		}

		if (strtolower($data['Content']) == 'go') {
			$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
			$apidata = $this->api_notice_increment('http://we-cdn.net', $xml, 1);
			header('Content-type: text/xml');
			exit($apidata);
			return false;
		}

		if (!(strpos($this->fun, 'api') === false)) {
			$apiData = M('Api')->where(array('token' => $this->token, 'status' => 1, 'noanswer' => 0))->select();
			$excecuteNoKeywordReply = 0;

			if ($apiData) {
				foreach ($apiData as $apiArray) {
					if (!$apiArray['keyword']) {
						$excecuteNoKeywordReply = 1;
						break;
					}
				}
			}

			if ($excecuteNoKeywordReply) {
				$nokeywordReply = $this->nokeywordApi(0, $apiData);

				if ($nokeywordReply) {
					return $nokeywordReply;
				}
			}

			if ($data['Content'] && $apiData) {
				foreach ($apiData as $apiArray) {
					if (!(strpos($data['Content'], $apiArray['keyword']) === false)) {
						$api = $apiArray;
						break;
					}
				}

				if ($api != false) {
					$vo['fromUsername'] = $this->data['FromUserName'];
					$vo['Content'] = $this->data['Content'];
					$vo['toUsername'] = $this->token;
					$api['url'] = $this->getApiUrl($api['url'], $api['apitoken']);

					if ($api['type'] == 2) {
						if (intval($api['is_colation'])) {
							$vo['Content'] = trim(str_replace($api['keyword'], '', $vo['Content']));
						}

						$apidata = $this->api_notice_increment($api['url'], $vo, 0, 0);
						return array($apidata, 'text');
					}
					else {
						$xml = $GLOBALS['HTTP_RAW_POST_DATA'];

						if (intval($api['is_colation'])) {
							$xml = str_replace(array($api['keyword'], $api['keyword'] . ' '), '', $xml);
						}

						$xml = $this->handleApiXml($xml);
						$apidata = $this->api_notice_increment($api['url'], $xml, 0);
						header('Content-type: text/xml');
						exit($apidata);
						return false;
					}
				}
			}
		}

		if (!(strpos($data['Content'], '审核') === false) && !(strpos($this->fun,'usernameCheck')) === FALSE) {
			return array($this->shenhe(str_replace('审核', '', $data['Content'])), 'text');
		}

		if (strtolower($data['Content']) == 'wx#open') {
			D('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']))->save(array('wallopen' => 1));
			S('fans_' . $this->token . '_' . $this->data['FromUserName'], NULL);
			return array('您已进入微信墙对话模式，您下面发送的所有文字和图片信息都将会显示在大屏幕上，如需退出微信墙模式，请输入“quit”', 'text');
		}
		else if (strtolower($data['Content']) == 'quit') {
			D('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']))->save(array('wallopen' => 0));
			S('fans_' . $this->token . '_' . $this->data['FromUserName'], NULL);
			return array('成功退出微信墙对话模式', 'text');
		}

		if ($this->fans['wallopen']) {
			$where = array('token' => $this->token);
			$where['is_open'] = array('gt', 0);
			$thisItem = M('Wechat_scene')->where($where)->find();
			$acttype = 3;
			if (!$thisItem || !$thisItem['is_open']) {
				$thisItem = M('Wall')->where(array('token' => $this->token, 'isopen' => 1))->find();
				$acttype = 1;
			}

			if (!$thisItem) {
				return array('微信墙活动不存在,如需退出微信墙模式，请输入“quit”', 'text');
			}
			else {
				$memberRecord = M('Wall_member')->where(array('act_id' => $thisItem['id'], 'act_type' => $acttype, 'wecha_id' => $this->data['FromUserName']))->find();

				if (!$memberRecord) {
					$this->data['Content'] = $thisItem['keyword'];
					$data['Content'] = $thisItem['keyword'];

					if ($acttype == 1) {
						$picLogo = $thisItem['startbackground'];
					}
					else {
						$picLogo = $thisItem['pic'];
					}

					return array(
	array(
		array($thisItem['title'], '请点击这里完善信息后再参加此活动', $picLogo, $this->siteUrl . U('Wap/Scene_member/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'act_type' => $acttype, 'id' => $thisItem['id'], 'name' => 'wall')))
		),
	'news'
	);
				}
				else {
					$row = array();

					if ('image' != $data['MsgType']) {
						$message = str_replace('wx#', '', $data['Content']);
					}
					else {
						$message = '';
						$row['picture'] = $data['PicUrl'];
					}

					$row['uid'] = $memberRecord['id'];
					$row['wecha_id'] = $this->data['FromUserName'];
					$row['token'] = $this->token;
					$thisWall = $thisItem;
					$thisMember = $memberRecord;

					if ($acttype == 1) {
						$row['wallid'] = $thisItem['id'];
						$needCheck = intval($thisWall['ck_msg']);
					}
					else {
						$row['wallid'] = intval($thisItem['wall_id']);
						$includeWall = M('Wall')->where(array('id' => $row['wallid']))->find();
						$needCheck = intval($includeWall['ck_msg']);
					}

					$row['content'] = $message;
					$row['uid'] = $thisMember['id'];
					$row['time'] = time();
					$row['check_time'] = $row['time'];

					if ($acttype == 3) {
						$row['is_scene'] = '1';
					}
					else {
						$row['is_scene'] = '0';
					}

					$row['is_check'] = 1;

					if ($needCheck) {
						$row['is_check'] = 0;
					}

					M('Wall_message')->add($row);
					$str = $this->wallStr($acttype, $thisItem);
					return array($str, 'text');
				}
			}
		}
		else {
		
		//判断用户提交是否为图片
                if ($data['MsgType'] == 'image') {
                //发送图片目前是晒图片的功能，
			$pic_wall_inf = M('pic_wall')->where(array('token' => $this->token, 'status' => 1))->order('id desc')->find();
			if (!$pic_wall_inf) {
			     return array('温馨提示：发送图片还可开启“照片墙”功能来晒图片哦！', 'text');
			}
                        if ($pic_wall_inf && $pic_wall_inf['status'] === '1') {
			 //存在晒照片活动并且 活动开关是开的
                         //开始下载图片操作
                         $sub_dir = date('Ymd');
                         if (!file_exists(($_SERVER['DOCUMENT_ROOT'] . '/uploads')) || !is_dir(($_SERVER['DOCUMENT_ROOT'] . '/uploads'))) {
                              mkdir($_SERVER['DOCUMENT_ROOT'] . '/uploads', 511);
                         }
                         $firstLetterDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/picwall';
                         if (!file_exists($firstLetterDir) || !is_dir($firstLetterDir)) {
                              mkdir($firstLetterDir, 511);
                         }
                         $firstLetterDir = ($_SERVER['DOCUMENT_ROOT'] . '/uploads/picwall/') . $sub_dir;
                         if (!file_exists($firstLetterDir) || !is_dir($firstLetterDir)) {
                              mkdir($firstLetterDir, 511);
                         }
                         $file_name = ((date('YmdHis') . '_') . rand(10000, 99999)) . '.jpg';
                         $pic_wall_save_path = ((($_SERVER['DOCUMENT_ROOT'] . '/uploads/picwall/') . $sub_dir) . '/') . $file_name;
                         $file_web_path = ((($this->siteUrl . '/uploads/picwall/') . $sub_dir) . '/') . $file_name;
                         $PicUrl = $data['PicUrl'];
                         $imgdata = $this->curlGet($PicUrl);
                         $fp = fopen($pic_wall_save_path, 'w');
                         fwrite($fp, $imgdata);
                         fclose($fp);
                         //将照片的路径放入到 缓存中
                         $checkresult = $pic_wall_inf['ischeck'] ? 0 : 1;
                         //设置上墙图片的检查结果。如果活动设置 是需要审核，那么上墙结果为0需要审核，审核成功以后为1
                         //插入到照片墙表中
                         $pic_wall_log = array('uid' => $pic_wall_inf['id'], 'token' => $this->token, 'picurl' => $file_web_path, 'wecha_id' => $data['FromUserName'], 'create_time' => time(), 'username' => '', 'state' => $checkresult);
                         S('zhaopianwall_' . $this->data['FromUserName'], $pic_wall_log, 60);
                         //--下载图片结束
                         return array('图片接收成功，请在一分钟内输入上墙图片的显示名字，或者回复 取消 结束本次活动', 'text');
			 }else{
                         return array('图片上墙失败！还未开启照片墙功能。', 'text');
		     }
	      }

                //判断照片墙
                $zhaopianwall_result = S('zhaopianwall_' . $data['FromUserName']);
                if ($zhaopianwall_result) {
                       return $this->zhaopianwall($zhaopianwall_result);
		        }
				
				
				
			$MsgTypeReplyClassName = $data['MsgType'] . 'MsgTypeReply';

			if (class_exists($MsgTypeReplyClassName)) {
				$MsgTypeReplyClassName = new $MsgTypeReplyClassName($this->token, $this->data['FromUserName'], $data, $this->siteUrl);
				return $MsgTypeReplyClassName->index();
			}

			if ('voice' == $data['MsgType']) {
				$data['Content'] = $data['Recognition'];

				if ($data['Recognition']) {
					$this->data['Content'] = $data['Recognition'];
				}
				else {
					$voice_apiwhere = array('token' => $this->token, 'status' => 1);
					$voice_apiwhere['noanswer'] = array('gt', 0);
					$api = M('Api')->where($voice_apiwhere)->find();

					if (!$api) {
						return $this->noreplyReturn();
					}
					else {
						return $this->nokeywordApi();
					}
				}
			}
		
			if (('image' == $data['MsgType']) || ('video' == $data['MsgType'])) {
				if ($this->wxuser['openphotoprint'] && ('image' == $data['MsgType'])) {
					return $photoPrint->uploadPic($data['PicUrl']);
				}

				if (!$this->wxuser['openphotoprint'] && ('image' == $data['MsgType'])) {
					$apiwhere = array('token' => $this->token, 'status' => 1);
					$apiwhere['noanswer'] = array('gt', 0);
					$api = M('Api')->where($apiwhere)->find();

					if (!$api) {
						return $this->noreplyReturn();
					}
				}

				return $this->nokeywordApi();
			}
		}

		if (!(strpos($data['Content'], '附近') === false)) {
			$this->recordLastRequest($data['Content']);
			$return = $this->fujin(array(str_replace('附近', '', $data['Content'])));
		}
		else {
			if (!(strpos($this->fun, 'gongjiao') === false) && !(strpos($data['Content'], '公交') === false) && (strpos($data['Content'], '坐公交') === false)) {
				$return = $this->gongjiao(explode('公交', $data['Content']));
			}
			else if (!(strpos($data['Content'], '域名 ') === false)) {
				$return = $this->yuming(str_replace('域名 ', '', $data['Content']));
			}
			else {
				$check = $this->user('connectnum');

				if ($check['connectnum'] != 1) {
					if (C('connectout')) {
						return array(C('connectout'), 'text');
					}
					else {
						return array('请求量已用完', 'text');
					}
				}

				$Pin = new GetPin();
				$key = $data['Content'];
				$datafun = explode(',', $this->fun);
				$tags = $this->get_tags($key);
				$back = explode(',', $tags);
				if (($key == '首页') || ($key == 'home')) {
					return $this->home();
				}

				foreach ($back as $keydata => $data) {
					$string = $Pin->Pinyin($data);
					if (in_array($string, $datafun) && $string) {
						if ($string == 'fujin') {
							$this->recordLastRequest($key);
						}

						$this->requestdata('textnum');
						unset($back[$keydata]);
						$thirdApp = new thirdApp();

						if (in_array($string, $thirdApp->modules())) {
							eval ('$thirdApps=new thirdApp();$return=$thirdApps->' . $string . '($back);');
						}
						else if (method_exists('WeixinAction', $string)) {
							eval ('$return= $this->' . $string . '($back);');
						}

						break;
					}
				}
			}
		}

		if (!empty($return)) {
			if (is_array($return)) {
				return $return;
			}
			else {
				return array($return, 'text');
			}
		}
		else {
			if (!(strpos($key, 'cheat') === false)) {
				$arr = explode(' ', $key);
				$datas['lid'] = intval($arr[1]);
				$lotteryPassword = $arr[2];
				$datas['prizetype'] = intval($arr[3]);
				$datas['intro'] = $arr[4];
				$datas['wecha_id'] = $this->data['FromUserName'];
				$thisLottery = M('Lottery')->where(array('id' => $datas['lid']))->find();

				if ($lotteryPassword == $thisLottery['parssword']) {
					$rt = M('Lottery_cheat')->add($datas);

					if ($rt) {
						return array('设置成功', 'text');
					}

					return array('设置失败:未知原因', 'text');
				}
				else {
					return array('设置失败:密码不对', 'text');
				}
			}

			if ($this->data['Location_X']) {
				$this->recordLastRequest($this->data['Location_Y'] . ',' . $this->data['Location_X'], 'location');
				return $this->map($this->data['Location_X'], $this->data['Location_Y']);
			}

			if (!(strpos($key, '开车去') === false) || !(strpos($key, '坐公交') === false) || !(strpos($key, '步行去') === false)) {
				$this->recordLastRequest($key);
				$user_request_model = M('User_request');
				$loctionInfo = $user_request_model->where(array('token' => $this->token, 'msgtype' => 'location', 'uid' => $this->data['FromUserName']))->find();
				if ($loctionInfo && intval((time() - 60) < $loctionInfo['time'])) {
					$latLng = explode(',', $loctionInfo['keyword']);
					return $this->map($latLng[1], $latLng[0]);
				}

				return array('请发送您所在的位置(对话框右下角点击＋号，然后点击“位置”)', 'text');
			}

			return $this->keyword($key);
		}
	}

	private function handleApiXml($xml)
	{
		if (strpos($xml, '<Event><![CDATA[CLICK]]></Event>')) {
			$xml = str_replace('<Event><![CDATA[CLICK]]></Event>', '', $xml);
			$xml = str_replace('<MsgType><![CDATA[event]]></MsgType>', '<MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . $this->data['Content'] . ']]></Content>', $xml);
		}

		return $xml;
	}

	private function xiangce()
	{
		$this->behaviordata('album', '', '1');
		$photo = M('Photo')->where(array('token' => $this->token, 'status' => 1))->find();
		$data['title'] = $photo['title'];
		$data['keyword'] = $photo['info'];
		$data['url'] = rtrim($this->siteUrl, '/') . U('Wap/Photo/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']));
		$data['picurl'] = $photo['picurl'] ? $photo['picurl'] : rtrim($this->siteUrl, '/') . '/tpl/static/images/yj.jpg';
		return array(
	array(
		array($data['title'], $data['keyword'], $data['picurl'], $data['url'])
		),
	'news'
	);
	}

	private function companyMap()
	{
		$mapAction = new Maps($this->token);
		return $mapAction->staticCompanyMap();
	}

	private function shenhe($name)
	{
		$this->behaviordata('usernameCheck', '', '1');

		if (empty($name)) {
			return '正确的审核帐号方式是：审核+帐号';
		}
		else {
			$user = M('Users')->field('id')->where(array('username' => $name))->find();

			if ($user == false) {
				return $this->my . '提醒您,您还没注册吧' . "\n" . '正确的审核帐号方式是：审核+帐号,不含+号';
			}
			else {
				$viptime = time() + (intval(C('reg_validdays')) * 24 * 3600);
				$gid = C('reg_groupid');
				$up = M('users')->where(array('id' => $user['id']))->save(array('viptime' => $viptime, 'status' => 1, 'gid' => $gid, 'openid' => $this->data['FromUserName']));

				if ($up != false) {
					return $this->my . '恭喜您,您的帐号已经审核,您现在点击网页上的蓝色按钮就可以体验啦!';
				}
				else {
					return '服务器繁忙请稍后再试';
				}
			}
		}
	}

	private function huiyuanka($name)
	{
		return $this->member();
	}

	private function member()
	{
		$card = M('member_card_create')->where(array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']))->find();
		$cardInfo = M('member_card_set')->where(array('token' => $this->token))->find();
		$this->behaviordata('Member_card_set', $cardInfo['id']);
		$reply_info_db = M('Reply_info');

		if ($card) {
			$where_member = array('token' => $this->token, 'infotype' => 'membercard');
			$memberConfig = $reply_info_db->where($where_member)->find();

			if (!$memberConfig) {
				$memberConfig = array();
				$memberConfig['picurl'] = rtrim($this->siteUrl, '/') . '/tpl/static/images/vip.jpg';
				$memberConfig['title'] = '省钱 打折 促销 优先知道';
				$memberConfig['info'] = '尊贵vip，是您消费身份的体现，省钱 打折 促销 优先知道';
			}

			$data['picurl'] = $memberConfig['picurl'];
			$data['title'] = $memberConfig['title'];
			$data['keyword'] = $memberConfig['info'];

			if (!$memberConfig['apiurl']) {
				$data['url'] = rtrim($this->siteUrl, '/') . U('Wap/Card/card', array('token' => $this->token, 'cardid' => $card['cardid'], 'wecha_id' => $this->data['FromUserName']));
			}
			else {
				$data['url'] = str_replace('{wechat_id}', $this->data['FromUserName'], $memberConfig['apiurl']);
			}
		}
		else {
			$where_unmember = array('token' => $this->token, 'infotype' => 'membercard_nouse');
			$unmemberConfig = $reply_info_db->where($where_unmember)->find();

			if (!$unmemberConfig) {
				$unmemberConfig = array();
				$unmemberConfig['picurl'] = rtrim($this->siteUrl, '/') . '/tpl/static/images/member.jpg';
				$unmemberConfig['title'] = '申请成为会员';
				$unmemberConfig['info'] = '申请成为会员，享受更多优惠';
			}

			$data['picurl'] = $unmemberConfig['picurl'];
			$data['title'] = $unmemberConfig['title'];
			$data['keyword'] = $unmemberConfig['info'];

			if (!$unmemberConfig['apiurl']) {
				$data['url'] = rtrim($this->siteUrl, '/') . U('Wap/Card/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']));
			}
			else {
				$data['url'] = str_replace('{wechat_id}', $this->data['FromUserName'], $unmemberConfig['apiurl']);
			}
		}

		return array(
	array(
		array($data['title'], $data['keyword'], $data['picurl'], $data['url'])
		),
	'news'
	);
	}

	private function taobao($name)
	{
		$name = array_merge($name);
		$data = M('Taobao')->where(array('token' => $this->token))->find();

		if ($data != false) {
			if (strpos($data['keyword'], $name)) {
				$url = $data['homeurl'] . '/search.htm?search=y&keyword=' . $name . '&lowPrice=&highPrice=';
			}
			else {
				$url = $data['homeurl'];
			}

			return array(
	array(
		array($data['title'], $data['keyword'], $data['picurl'], $url)
		),
	'news'
	);
		}
		else {
			return '商家还未及时更新淘宝店铺的信息,回复帮助,查看功能详情';
		}
	}

	private function choujiang($name)
	{
		$data = M('lottery')->field('id,keyword,info,title,starpicurl')->where(array('token' => $this->token, 'status' => 1, 'type' => 1))->order('id desc')->find();

		if ($data == false) {
			return array('暂无抽奖活动', 'text');
		}

		$pic = ($data['starpicurl'] ? $data['starpicurl'] : rtrim($this->siteUrl, '/') . '/tpl/User/default/common/images/img/activity-lottery-start.jpg');
		$url = rtrim($this->siteUrl, '/') . U('Wap/Lottery/index', array('type' => 1, 'token' => $this->token, 'id' => $data['id'], 'wecha_id' => $this->data['FromUserName']));
		return array(
	array(
		array($data['title'], $data['info'], $pic, $url)
		),
	'news'
	);
	}

	private function keyword($key)
	{
		$params = func_get_args();
		if (($params[1] != '') && ($this->token == '')) {
			$this->token = $params[1];
			$this->data = $params[2];
			$this->siteUrl = $params[3];
			$this->wxuser = D('Wxuser')->where(array('token' => $this->token))->find();
			$this->user = M('Users')->where(array('id' => $this->wxuser['uid']))->find();
		}

		$keyarray = explode('_', $key);
		$key = $keyarray[0];

		switch ($key) {
		case '首页':
		case 'home':
		case 'Home':
			return $this->home();
			break;

		case '主页':
			return $this->home();
			break;

		case '地图':
			return $this->companyMap();
		case '最近的':
			$this->recordLastRequest($key);
			$user_request_model = M('User_request');
			$loctionInfo = $user_request_model->where(array('token' => $this->token, 'msgtype' => 'location', 'uid' => $this->data['FromUserName']))->find();
			if ($loctionInfo && intval((time() - 60) < $loctionInfo['time'])) {
				$latLng = explode(',', $loctionInfo['keyword']);
				return $this->map($latLng[1], $latLng[0]);
			}

			return array('请发送您所在的位置(对话框右下角点击＋号，然后点击“位置”)', 'text');
			break;

		case '帮助':
			return $this->help();
			break;

		case 'help':
			return $this->help();
			break;

		case '会员卡':
			return $this->member();
			break;

		case '会员':
			return $this->member();
			break;

		case '3g相册':
			return $this->xiangce();
			break;

		case '相册':
			return $this->xiangce();
			break;

		case '商城':
			$pro = M('reply_info')->where(array('infotype' => 'Shop', 'token' => $this->token))->find();
			$url = $this->siteUrl . '/index.php?g=Wap&m=Store&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '';

			if ($pro['apiurl']) {
				$url = str_replace('&amp;', '&', $pro['apiurl']);
			}

			return array(
	array(
		array($pro['title'], strip_tags(htmlspecialchars_decode($pro['info'])), $pro['picurl'], $url)
		),
	'news'
	);
			break;

		case '订餐':
			$pro = M('reply_info')->where(array('infotype' => 'Dining', 'token' => $this->token))->find();
			$url = $this->siteUrl . '/index.php?g=Wap&m=Repast&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '';

			if ($pro['apiurl']) {
				$url = str_replace('&amp;', '&', $pro['apiurl']);
			}

			return array(
	array(
		array($pro['title'], strip_tags(htmlspecialchars_decode($pro['info'])), $pro['picurl'], $url)
		),
	'news'
	);
			break;

		case '留言':
			$pro = M('reply_info')->where(array('infotype' => 'message', 'token' => $this->token))->find();

			if ($pro) {
				return array(
	array(
		array($pro['title'], strip_tags(htmlspecialchars_decode($pro['info'])), $pro['picurl'], $this->siteUrl . '/index.php?g=Wap&m=Reply&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '')
		),
	'news'
	);
			}
			else {
				return array(
	array(
		array('留言板', '在线留言', rtrim($this->siteUrl, '/') . '/tpl/Wap/default/common/css/style/images/ly.jpg', $this->siteUrl . '/index.php?g=Wap&m=Reply&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '')
		),
	'news'
	);
			}

			break;

		case '酒店':
			$pro = M('reply_info')->where(array('infotype' => 'Hotels', 'token' => $this->token))->find();

			if ($pro) {
				return array(
	array(
		array($pro['title'], strip_tags(htmlspecialchars_decode($pro['info'])), $pro['picurl'], $this->siteUrl . '/index.php?g=Wap&m=Hotels&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'])
		),
	'news'
	);
			}
			else {
				return array(
	array(
		array('酒店', '酒店在线预订', rtrim($this->siteUrl, '/') . 'tpl/static/images/homelogo.png', $this->siteUrl . '/index.php?g=Wap&m=Hotels&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'])
		),
	'news'
	);
			}

			break;

		case '团购':
			$pro = M('reply_info')->where(array('infotype' => 'Groupon', 'token' => $this->token))->find();
			$url = $this->siteUrl . '/index.php?g=Wap&m=Groupon&a=grouponIndex&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '';

			if ($pro['apiurl']) {
				$url = str_replace('&amp;', '&', $pro['apiurl']);
			}

			return array(
	array(
		array($pro['title'], strip_tags(htmlspecialchars_decode($pro['info'])), $pro['picurl'], $url)
		),
	'news'
	);
			break;

		case '全景':
			$pro = M('reply_info')->where(array('infotype' => 'panorama', 'token' => $this->token))->find();

			if ($pro) {
				return array(
	array(
		array($pro['title'], strip_tags(htmlspecialchars_decode($pro['info'])), $pro['picurl'], $this->siteUrl . '/index.php?g=Wap&m=Panorama&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '')
		),
	'news'
	);
			}
			else {
				return array(
	array(
		array('360°全景看车看房', '通过该功能可以实现3D全景看车看房', rtrim($this->siteUrl, '/') . '/tpl/User/default/common/images/panorama/360view.jpg', $this->siteUrl . '/index.php?g=Wap&m=Panorama&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '')
		),
	'news'
	);
			}

			break;

		case '讨论社区':
		case '论坛':
			$fconfig = M('Forum_config')->where(array('token' => $this->token))->find();
			return array(
	array(
		array($fconfig['forumname'], str_replace('&nbsp;', '', strip_tags(htmlspecialchars_decode($fconfig['intro']))), $fconfig['picurl'], $this->siteUrl . '/index.php?g=Wap&m=Forum&a=index&&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'])
		),
	'news'
	);
			break;

		case '微商圈':
			$thisItem = M('Market')->where(array('token' => $this->token))->find();
			return array(
	array(
		array($thisItem['title'], $thisItem['address'], $thisItem['logo_pic'], $this->siteUrl . U('Wap/Market/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'])))
		),
	'news'
	);
			break;
		case '微招聘':
				$pro = M ( 'zhaopin_reply' )->where ( array (
						'token' => $this->token 
				) )->find ();
				if ($pro) {
					return array (
							array (
									array (
											$pro ['title'],
											strip_tags ( htmlspecialchars_decode ( $pro ['info'] ) ),
											$pro ['tp'],
											$this->siteUrl . U ( 'Wap/Zhaopin/index', array (
													'token' => $this->token,
													'wecha_id' => $this->data ['FromUserName'],
													'sgssz' => 'mp.weixin.qq.com' 
											) ) 
									),
									array (
											'【找简历】找简历，看这里',
											strip_tags ( htmlspecialchars_decode ( $pro ['info'] ) ),
											$this->siteUrl . '/tpl/Wap/default/common/zhaopin/jianli.png',
											$this->siteUrl . U ( 'Wap/Zhaopin/jlindex', array (
													'token' => $this->token,
													'wecha_id' => $this->data ['FromUserName'],
													'sgssz' => 'mp.weixin.qq.com' 
											) ) 
									),
									array (
											'【企业版】我要发布招聘',
											strip_tags ( htmlspecialchars_decode ( $pro ['info'] ) ),
											$this->siteUrl . '/tpl/Wap/default/common/zhaopin/qiye.png',
											$this->siteUrl . U ( 'Wap/Zhaopin/qiye', array (
													'token' => $this->token,
													'wecha_id' => $this->data ['FromUserName'],
													'sgssz' => 'mp.weixin.qq.com' 
											) ) 
									),
									array (
											'【个人版】我要发布简历',
											strip_tags ( htmlspecialchars_decode ( $pro ['info'] ) ),
											$this->siteUrl . '/tpl/Wap/default/common/zhaopin/geren.png',
											$this->siteUrl . U ( 'Wap/Zhaopin/geren', array (
													'token' => $this->token,
													'wecha_id' => $this->data ['FromUserName'],
													'sgssz' => 'mp.weixin.qq.com' 
											) ) 
									) 
							),
							'news' 
					);
				} else {
					return array (
							'该公众号暂未配置微招聘信息',
							'text' 
					);
				}
				break;
		}

		$check = $this->user('diynum', $key);

		if ($check['diynum'] != 1) {
			return array(C('connectout'), 'text');
		}

		$like['keyword'] = $key;
		$like['precisions'] = 1;
		$like['token'] = $this->token;
		$data = M('keyword')->where($like)->order('id desc')->find();

		if (!$data) {
			$like['keyword'] = array('like', '%' . $key . '%');
			$like['precisions'] = 0;
			$data = M('keyword')->where($like)->order('id desc')->find();
		}

		if ($data != false) {
			$this->behaviordata($data['module'], $data['pid']);
			$replyClassName = $data['module'] . 'Reply';

			if (class_exists($replyClassName)) {
				$replyClass = new $replyClassName($this->token, $this->data['FromUserName'], $data, $this->siteUrl, $key);
				return $replyClass->index();
			}
			else {
				switch ($data['module']) {
				case 'Img':
					$this->requestdata('imgnum');
					$img_db = M($data['module']);
					$back = $img_db->field('id,text,pic,url,title')->limit(9)->order('usort desc')->where($like)->select();
					$idsWhere = 'id in (';
					$comma = '';

					foreach ($back as $keya => $infot) {
						$idsWhere .= $comma . $infot['id'];
						$comma = ',';

						if ($infot['url'] != false) {
							if (!(strpos($infot['url'], 'http') === false)) {
								$url = $this->getFuncLink(html_entity_decode($infot['url']));
							}
							else {
								$url = $this->getFuncLink($infot['url']);
							}
						}
						else {
							$url = rtrim($this->siteUrl, '/') . U('Wap/Index/content', array('token' => $this->token, 'id' => $infot['id'], 'wecha_id' => $this->data['FromUserName']));
						}

						$return[] = array($infot['title'], $this->handleIntro($infot['text']), $infot['pic'], $url);
					}

					$idsWhere .= ')';

					if ($back) {
						$img_db->where($idsWhere)->setInc('click');
					}

					return array($return, 'news');
					break;

				case 'Host':
					$this->requestdata('other');
					$host = M('Host')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($host['name'], $host['info'], $host['ppicurl'], $this->siteUrl . '/index.php?g=Wap&m=Host&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&hid=' . $data['pid'] . '')
		),
	'news'
	);
					break;

				case 'Reservation':
					$this->requestdata('other');
					$rt = M('Reservation')->where(array('id' => $data['pid']))->find();

					if (!strpos($rt['picurl'], 'ttp:')) {
						$rt['picurl'] = $this->siteUrl . $rt['picurl'];
					}

					return array(
	array(
		array($rt['title'], str_replace(array('&nbsp;', 'br /', '&amp;', 'gt;', 'lt;'), '', strip_tags(htmlspecialchars_decode($rt['info']))), $rt['picurl'], $this->siteUrl . '/index.php?g=Wap&m=Reservation&a=index&rid=' . $data['pid'] . '&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '')
		),
	'news'
	);
					break;

				case 'Text':
					$this->requestdata('textnum');
					$info = M($data['module'])->order('id desc')->find($data['pid']);
					return array(htmlspecialchars_decode(str_replace('{wechat_id}', $this->data['FromUserName'], $info['text'])), 'text');
					break;

				case 'Product':
					$this->requestdata('other');
					$infos = M('Product')->limit(9)->order('id desc')->where($like)->select();

					if ($infos) {
						$return = array();

						foreach ($infos as $info) {
							if (!$info['groupon']) {
								$url = $this->siteUrl . '/index.php?g=Wap&m=Store&a=product&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&id=' . $info['id'];
							}
							else {
								$url = $this->siteUrl . '/index.php?g=Wap&m=Groupon&a=product&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&id=' . $info['id'];
							}

							$return[] = array($info['name'], $this->handleIntro(strip_tags(htmlspecialchars_decode($info['intro']))), $info['logourl'], $url);
						}
					}

					return array($return, 'news');
					break;

				case 'Selfform':
					$this->requestdata('other');
					$pro = M('Selfform')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($pro['name'], strip_tags(htmlspecialchars_decode($pro['intro'])), $pro['logourl'], $this->siteUrl . '/index.php?g=Wap&m=Selfform&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&id=' . $data['pid'] . '')
		),
	'news'
	);
					break;

				case 'Custom':
					$this->requestdata('other');
					$pro = M('Custom_set')->where(array('set_id' => $data['pid']))->find();
					return array(
	array(
		array($pro['title'], strip_tags(htmlspecialchars_decode($pro['intro'])), $pro['top_pic'], $this->siteUrl . '/index.php?g=Wap&m=Custom&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&id=' . $data['pid'] . '')
		),
	'news'
	);
					break;

				case 'Panorama':
					$this->requestdata('other');
					$pro = M('Panorama')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($pro['name'], strip_tags(htmlspecialchars_decode($pro['intro'])), $pro['frontpic'], $this->siteUrl . '/index.php?g=Wap&m=Panorama&a=item&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&id=' . $data['pid'] . '')
		),
	'news'
	);
					break;

				case 'Wedding':
					$this->requestdata('other');
					$wedding = M('Wedding')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($wedding['title'], strip_tags(htmlspecialchars_decode($wedding['word'])), $wedding['coverurl'], $this->siteUrl . '/index.php?g=Wap&m=Wedding&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&id=' . $data['pid'] . ''),
		array('查看我的祝福', strip_tags(htmlspecialchars_decode($wedding['word'])), $wedding['picurl'], $this->siteUrl . '/index.php?g=Wap&m=Wedding&a=check&type=1&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&id=' . $data['pid'] . ''),
		array('查看我的来宾', strip_tags(htmlspecialchars_decode($wedding['word'])), $wedding['picurl'], $this->siteUrl . '/index.php?g=Wap&m=Wedding&a=check&type=2&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&id=' . $data['pid'] . '')
		),
	'news'
	);
					break;

				case 'Vote':
					$this->requestdata('other');
					$Vote = M('Vote')->where(array('id' => $data['pid']))->order('id DESC')->find();
					return array(
	array(
		array($Vote['title'], '', $Vote['picurl'], $this->siteUrl . '/index.php?g=Wap&m=Vote&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&id=' . $data['pid'] . '')
		),
	'news'
	);
					break;

				case 'Greeting_card':
					$this->requestdata('other');
					$Vote = M('Greeting_card')->where(array('id' => $data['pid']))->order('id DESC')->find();
					return array(
	array(
		array($Vote['title'], str_replace(array('&nbsp;', 'br /', '&amp;', 'gt;', 'lt;'), '', strip_tags(htmlspecialchars_decode($Vote['info']))), $Vote['picurl'], $this->siteUrl . '/index.php?g=Wap&m=Greeting_card&a=index&id=' . $data['pid'] . '')
		),
	'news'
	);
					break;

				case 'Lottery':
					$this->requestdata('other');
					$info = M('Lottery')->find($data['pid']);
					if (($info == false) || ($info['status'] == 3)) {
						return array('活动可能已经结束或者被删除了', 'text');
					}

					switch ($info['type']) {
					case 1:
						$model = 'Lottery';
						break;

					case 2:
						$model = 'Guajiang';
						break;

					case 3:
						$model = 'Coupon';
						break;

					case 4:
						$model = 'LuckyFruit';
						break;

					case 5:
						$model = 'GoldenEgg';
						break;

					case 7:
						$model = 'AppleGame';
						break;

					case 8:
						$model = 'Lovers';
						break;

					case 9:
						$model = 'Autumn';
						break;

					case 10:
						$model = 'Jiugong';
						break;
					}

					$id = $info['id'];
					$type = $info['type'];

					if ($info['status'] == 1) {
						$picurl = $info['starpicurl'];
						$title = $info['title'];
						$id = $info['id'];
						$info = $info['info'];
					}
					else {
						$picurl = $info['endpicurl'];
						$title = $info['endtite'];
						$info = $info['endinfo'];
					}

					$url = $this->siteUrl . U('Wap/' . $model . '/index', array('token' => $this->token, 'type' => $type, 'wecha_id' => $this->data['FromUserName'], 'id' => $id, 'type' => $type));
					return array(
	array(
		array($title, $info, $picurl, $url)
		),
	'news'
	);
				case 'Carowner':
					$this->requestdata('other');
					$thisItem = M('Carowner')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($thisItem['title'], str_replace(array('&nbsp;', 'br /', '&amp;', 'gt;', 'lt;'), '', strip_tags(htmlspecialchars_decode($thisItem['info']))), $thisItem['head_url'], $this->siteUrl . '/index.php?g=Wap&m=Car&a=owner&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&id=' . $data['pid'] . '')
		),
	'news'
	);
					break;

				case 'Carowner':
					$this->requestdata('other');
					$thisItem = M('Carowner')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($thisItem['title'], str_replace(array('&nbsp;', 'br /', '&amp;', 'gt;', 'lt;'), '', strip_tags(htmlspecialchars_decode($thisItem['info']))), $thisItem['head_url'], $this->siteUrl . '/index.php?g=Wap&m=Car&a=owner&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'])
		),
	'news'
	);
					break;

				case 'Carset':
					$this->requestdata('other');
					$thisItem = M('Carset')->where(array('id' => $data['pid']))->find();
					$news = array();
					array_push($news, array($thisItem['title'], '', $thisItem['head_url'], $thisItem['url'] ? $thisItem['url'] : $this->siteUrl . '/index.php?g=Wap&m=Car&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName']));
					array_push($news, array($thisItem['title1'], '', $thisItem['head_url_1'], $thisItem['url1'] ? $thisItem['url1'] : $this->siteUrl . '/index.php?g=Wap&m=Car&a=brands&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName']));
					array_push($news, array($thisItem['title2'], '', $thisItem['head_url_2'], $thisItem['url2'] ? $thisItem['url2'] : $this->siteUrl . '/index.php?g=Wap&m=Car&a=salers&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName']));
					array_push($news, array($thisItem['title3'], '', $thisItem['head_url_3'], $thisItem['url3'] ? $thisItem['url3'] : $this->siteUrl . '/index.php?g=Wap&m=Car&a=CarReserveBook&addtype=drive&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName']));
					array_push($news, array($thisItem['title4'], '', $thisItem['head_url_4'], $thisItem['url4'] ? $thisItem['url4'] : $this->siteUrl . '/index.php?g=Wap&m=Car&a=owner&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName']));
					array_push($news, array($thisItem['title5'], '', $thisItem['head_url_5'], $thisItem['url5'] ? $thisItem['url5'] : $this->siteUrl . '/index.php?g=Wap&m=Car&a=tool&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName']));
					array_push($news, array($thisItem['title6'], '', $thisItem['head_url_6'], $thisItem['url6'] ? $thisItem['url6'] : $this->siteUrl . '/index.php?g=Wap&m=Car&a=showcar&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName']));
					return array($news, 'news');
					break;

				case 'medicalSet':
					$this->requestdata('other');
					$thisItem = M('Medical_set')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($thisItem['title'], str_replace(array('&nbsp;', 'br /', '&amp;', 'gt;', 'lt;'), '', strip_tags(htmlspecialchars_decode($thisItem['info']))), $thisItem['head_url'], $this->siteUrl . '/index.php?g=Wap&m=Medical&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'])
		),
	'news'
	);
					break;
					
					case 'zhaopianwall':
                $thisItem = M('pic_wall')->where(array('token' => $this->token, 'status' => 1))->order('id desc')->find();
                if (!$thisItem) {
                    return array('您还未开启照片墙功能。', 'text');
                }
                return array(array(array($thisItem['title'], $this->handleIntro($thisItem['info']), $thisItem['starpicurl'], ((((C('site_url') . '/index.php?g=Wap&m=Zhaopianwall&a=index&token=') . $this->token) . '&wecha_id=') . $this->data['FromUserName']) . '&sgssz=mp.weixin.qq.com')), 'news');
                break;

				case 'Wall':
				case 'Scene':
					if ($data['module'] == 'Wall') {
						$act_model = M('Wall');
					}
					else {
						$act_model = M('Wechat_scene');
					}

					$thisItem = $act_model->where(array('id' => $data['pid']))->find();

					if ($data['module'] == 'Wall') {
						$acttype = 1;
						$isopen = $thisItem['isopen'];
						$picLogo = $thisItem['startbackground'];
					}
					else {
						$acttype = 3;
						$isopen = $thisItem['is_open'];
						$picLogo = $thisItem['pic'];
					}

					$str = $this->wallStr($acttype, $thisItem);

					if (!$isopen) {
						return array($thisItem['title'] . '活动已关闭', 'text');
					}
					else {
						$actid = $data['pid'];
						$memberRecord = M('Wall_member')->where(array('act_id' => $actid, 'act_type' => $acttype, 'wecha_id' => $this->data['FromUserName']))->find();

						if (!$memberRecord) {
							return array(
	array(
		array($thisItem['title'], '请点击这里完善信息后再参加此活动', $picLogo, $this->siteUrl . U('Wap/Scene_member/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'act_type' => $acttype, 'id' => $actid, 'name' => 'wall')))
		),
	'news'
	);
						}
						else {
							D('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']))->save(array('wallopen' => 1));
							S('fans_' . $this->token . '_' . $this->data['FromUserName'], NULL);
							return array($str, 'text');
						}
					}

					break;

				case 'Recipe':
					$this->requestdata('other');
					$thisItem = M('Recipe')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($thisItem['title'], str_replace(array('&nbsp;', 'br /', '&amp;', 'gt;', 'lt;'), '', strip_tags(htmlspecialchars_decode($thisItem['infos']))), $thisItem['headpic'], $this->siteUrl . '/index.php?g=Wap&m=Recipe&a=index&token=' . $this->token . '&type=' . $thisItem['type'] . '&id=' . $thisItem['id'] . 'wecha_id=' . $this->data['FromUserName'])
		),
	'news'
	);
					break;

				case 'Router_config':
					$routerUrl = Router::login($this->token, $this->data['FromUserName']);
					$thisItem = M('Router_config')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($thisItem['title'], $thisItem['info'], $thisItem['picurl'], $routerUrl)
		),
	'news'
	);
					break;

				case 'Schoolset':
					$thisItem = M('School_set_index')->where(array('setid' => $data['pid']))->find();
					return array(
	array(
		array($thisItem['title'], $thisItem['info'], $thisItem['head_url'], $this->siteUrl . U('Wap/School/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'])))
		),
	'news'
	);
					break;

				case 'Research':
					$thisItem = M('Research')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($thisItem['title'], $thisItem['description'], $thisItem['logourl'], $this->siteUrl . U('Wap/Research/index', array('reid' => $data['pid'], 'token' => $this->token, 'wecha_id' => $this->data['FromUserName'])))
		),
	'news'
	);
					break;

				case 'Business':
					$this->requestdata('other');
					$thisItem = M('Busines')->where(array('bid' => $data['pid']))->find();
					return array(
	array(
		array($thisItem['title'], str_replace(array('&nbsp;', 'br /', '&amp;', 'gt;', 'lt;'), '', strip_tags(htmlspecialchars_decode($thisItem['business_desc']))), $thisItem['picurl'], $this->siteUrl . '/index.php?g=Wap&m=Business&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&bid=' . $thisItem['bid'] . '&type=' . $thisItem['type'])
		),
	'news'
	);
					break;

				case 'Sign':
					$thisItem = M('Sign_set')->where(array('id' => $data['pid']))->find();
					return array(
	array(
		array($thisItem['title'], $thisItem['content'], $thisItem['reply_img'], $this->siteUrl . U('Wap/Fanssign/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $data['pid'])))
		),
	'news'
	);
					break;

				case 'Multi':
					$multiImgClass = new multiImgNews($this->token, $this->data['FromUserName'], $this->siteUrl);
					return $multiImgClass->news($data['pid']);
					break;

				case 'Market':
					$thisItem = M('Market')->where(array('market_id' => $data['pid']))->find();
					return array(
	array(
		array($thisItem['title'], $thisItem['address'], $thisItem['logo_pic'], $this->siteUrl . U('Wap/Market/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'])))
		),
	'news'
	);
				default:
					$replyClassName = $data['module'] . 'Reply';

					if (class_exists($replyClassName)) {
						$replyClass = new $replyClassName($this->token, $this->data['FromUserName'], $data, $this->siteUrl);
						return $replyClass->index();
					}
					else {
						$this->requestdata('videonum');
						$info = M($data['module'])->order('id desc')->find($data['pid']);
						return array(
	array($info['title'], $info['keyword'], $info['musicurl'], $info['hqmusicurl']),
	'music'
	);
					}
				}
			}
		}
		else {
			$nokeywordReply = $this->nokeywordApi();

			if ($nokeywordReply) {
				return $nokeywordReply;
			}

			if ($this->wxuser['transfer_customer_service']) {
				return array('turn on transfer_customer_service', 'transfer_customer_service');
			}

			$chaFfunction = M('Function')->where(array('funname' => 'liaotian'))->find();
			if ((strpos($this->fun, 'liaotian') === false) || !$chaFfunction['status']) {
				if ($keyarray[1] == 'noreplyReturn') {
					return array('要不要这么难，这个我回答不上来。%>_<%', 'text');
					exit();
				}
				else {
					return $this->noreplyReturn();
				}
			}

			if (!C('not_support_chat')) {
				$this->selectService();
			}

			return array($this->chat($key), 'text');
		}
	}

	private function noreplyReturn()
	{
		$other = M('Other')->where(array('token' => $this->token))->find();

		if ($other == false) {
			return array('', 'text');
		}
		else if (empty($other['keyword'])) {
			return array($other['info'], 'text');
		}
		else {
			return $this->keyword($other['keyword'] . '_noreplyReturn');
		}
	}

	private function wallStr($acttype, $thisItem)
	{
		$str = '处理成功，您下面发送的所有文字和图片都将会显示在“' . $thisItem['title'] . '”大屏幕上，如需退出微信墙模式，请输入“quit”';

		if ($acttype == 3) {
			if ($thisItem['shake_id']) {
				$str .= '' . "\r\n" . '<a href="' . $this->siteUrl . '/index.php?g=Wap&m=Shake&a=index&id=' . $thisItem['id'] . '&token=' . $this->token . '&act_type=' . $acttype . '&wecha_id=' . $this->data['FromUserName'] . '">点击这里参与摇一摇活动</a>';
			}

			if ($thisItem['vote_id']) {
				$str .= '' . "\r\n" . '' . "\r\n" . '<a href="' . $this->siteUrl . '/index.php?g=Wap&m=Scene_vote&a=index&id=' . $thisItem['id'] . '&token=' . $this->token . '&act_type=' . $acttype . '&wecha_id=' . $this->data['FromUserName'] . '">点击这里参与投票</a>';
			}
		}

		return $str;
	}

	private function nokeywordApi($noanswer = 1, $apidata = 0)
	{
		if (!(strpos($this->fun, 'api') === false)) {
			$apiwhere = array('token' => $this->token, 'status' => 1, 'noanwser' => $noanswer);

			if ($noanswer) {
				$apiwhere['noanswer'] = array('gt', 0);
			}
			else {
				$apiwhere['noanswer'] = 0;
			}

			if ($apidata) {
				$api = $apidata[0];
			}
			else {
				$api = M('Api')->where($apiwhere)->find();
			}

			if ($api != false) {
				$vo['fromUsername'] = $this->data['FromUserName'];
				$vo['Content'] = $this->data['Content'];

				if (intval($api['is_colation'])) {
					$vo['Content'] = trim(str_replace($api['keyword'], '', $this->data['Content']));
				}

				$vo['toUsername'] = $this->token;
				$api['url'] = $this->getApiUrl($api['url'], $api['apitoken']);

				if ($api['type'] == 2) {
					$apidata = $this->api_notice_increment($api['url'], $vo, 0, 0);

					if ($apidata != 'false') {
						return array($apidata, 'text');
					}
				}
				else {
					$xml = file_get_contents('php://input');

					if (intval($api['is_colation'])) {
						$xml = str_replace(array($api['keyword'], $api['keyword'] . ' '), '', $xml);
					}

					$xml = $this->handleApiXml($xml);
					$apidata = $this->api_notice_increment($api['url'], $xml, 0);

					if ($apidata != 'false') {
						header('Content-type: text/xml');
						exit($apidata);
						return false;
					}
				}
			}
		}
	}

	private function getApiUrl($url, $token)
	{
		$timestamp = time();
		$nonce = $_GET['nonce'];
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$signature = sha1($tmpStr);
		$url = str_replace('&amp;', '&', $url);

		if (strpos($url, '?')) {
			$url = $url . '&fromthirdapi=1&signature=' . $signature . '&timestamp=' . $timestamp . '&nonce=' . $nonce . '&apitoken=' . $this->token;
		}
		else {
			$url = $url . '?fromthirdapi=1&signature=' . $signature . '&timestamp=' . $timestamp . '&nonce=' . $nonce . '&apitoken=' . $this->token;
		}

		return $url;
	}

	private function getFuncLink($u)
	{
		$urlInfos = explode(' ', $u);

		switch ($urlInfos[0]) {
		default:
			$url = str_replace(array('{wechat_id}', '{siteUrl}', '&amp;'), array($this->data['FromUserName'], $this->siteUrl, '&'), $urlInfos[0]);
			break;

		case '刮刮卡':
			$Lottery = M('Lottery')->where(array('token' => $this->token, 'type' => 2, 'status' => 1))->order('id DESC')->find();
			$url = $this->siteUrl . U('Wap/Guajiang/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $Lottery['id']));
			break;

		case '大转盘':
			$Lottery = M('Lottery')->where(array('token' => $this->token, 'type' => 1, 'status' => 1))->order('id DESC')->find();
			$url = $this->siteUrl . U('Wap/Lottery/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $Lottery['id']));
			break;

		case '商家订单':
			$url = $this->siteUrl . '/index.php?g=Wap&m=Host&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '&hid=' . $urlInfos[1] . '';
			break;

		case '优惠券':
			$Lottery = M('Lottery')->where(array('token' => $this->token, 'type' => 3, 'status' => 1))->order('id DESC')->find();
			$url = $this->siteUrl . U('Wap/Coupon/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $Lottery['id']));
			break;

		case '万能表单':
			$url = $this->siteUrl . U('Wap/Selfform/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $urlInfos[1]));
			break;

		case '会员卡':
			$url = $this->siteUrl . U('Wap/Card/vip', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']));
			break;

		case '首页':
			$url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'];
			break;

		case '团购':
			$url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Groupon&a=grouponIndex&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'];
			break;

		case '商城':
			$url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Store&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'];
			break;

		case '订餐':
			$url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Repast&a=index&dining=1&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'];
			break;

		case '相册':
			$url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Photo&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'];
			break;

		case '网站分类':
			$url = $this->siteUrl . U('Wap/Index/lists', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'classid' => $urlInfos[1]));
			break;

		case 'LBS信息':
			if ($urlInfos[1]) {
				$url = $this->siteUrl . U('Wap/Company/map', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'companyid' => $urlInfos[1]));
			}
			else {
				$url = $this->siteUrl . U('Wap/Company/map', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName']));
			}

			break;

		case 'DIY宣传页':
			$url = $this->siteUrl . '/index.php/show/' . $this->token;
			break;

		case '婚庆喜帖':
			$url = $this->siteUrl . U('Wap/Wedding/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $urlInfos[1]));
			break;

		case '投票':
			$url = $this->siteUrl . U('Wap/Vote/index', array('token' => $this->token, 'wecha_id' => $this->data['FromUserName'], 'id' => $urlInfos[1]));
			break;
		}

		return $url;
	}

	private function home()
	{
		return $this->shouye();
	}

	private function shouye($name)
	{
		$home = M('Home')->where(array('token' => $this->token))->find();
		$this->behaviordata('home', '', '1');

		if ($home == false) {
			return array('商家未做首页配置，请稍后再试', 'text');
		}
		else {
			$imgurl = $home['picurl'];

			if ($home['apiurl'] == false) {
				if (!$home['advancetpl']) {
					$url = rtrim($this->siteUrl, '/') . '/index.php?g=Wap&m=Index&a=index&token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '';
				}
				else {
					$url = rtrim($this->siteUrl, '/') . '/cms/index.php?token=' . $this->token . '&wecha_id=' . $this->data['FromUserName'] . '';
				}
			}
			else {
				$url = $home['apiurl'];
			}
		}

		return array(
	array(
		array($home['title'], $home['info'], $imgurl, $url)
		),
	'news'
	);
	}

	private function kuaidi($data)
	{
		$data = array_merge($data);
		$str = file_get_contents('http://www.weinxinma.com/api/index.php?m=Express&a=index&name=' . $data[0] . '&number=' . $data[1]);

		if ($str == '不支持的快递公司') {
			$str = file_get_contents('http://www.weinxinma.com/api/index.php?m=Express&a=index&name=' . $data[1] . '&number=' . $data[0]);
		}

		return $str;
	}

	private function langdu($data)
	{
		$data = implode('', $data);
		$mp3url = 'http://www.apiwx.com/aaa.php?w=' . urlencode($data);
		return array(
	array($data, '点听收听', $mp3url, $mp3url),
	'music'
	);
	}

	private function jiankang($data)
	{
		if (empty($data)) {
			return '主人，' . $this->my . '提醒您' . "\n" . '正确的查询方式是:' . "\n" . '健康+身高,+体重' . "\n" . '例如：健康170,65';
		}

		$height = $data[1] / 100;
		$weight = $data[2];
		$Broca = (($height * 100) - 80) * 0.7;
		$kaluli = (66 + (13.7 * $weight) + (5 * $height * 100)) - (6.8 * 25);
		$chao = $weight - $Broca;
		$zhibiao = $chao * 0.1;
		$res = round($weight / ($height * $height), 1);

		if ($res < 18.5) {
			$info = '您的体形属于骨感型，需要增加体重' . $chao . '公斤哦!';
			$pic = 1;
		}
		else if ($res < 24) {
			$info = '您的体形属于圆滑型的身材，需要减少体重' . $chao . '公斤哦!';
		}
		else if (24 < $res) {
			$info = '您的体形属于肥胖型，需要减少体重' . $chao . '公斤哦!';
		}
		else if (28 < $res) {
			$info = '您的体形属于严重肥胖，请加强锻炼，或者使用我们推荐的减肥方案进行减肥';
		}

		return $info;
	}

	private function fujin($keyword)
	{
		if (!$this->turnFunc('lbsNews')) {
			$keyword = implode('', $keyword);

		if ($keyword == false) {
			return $this->my . '很难过,无法识别主人的指令,正确使用方法是:输入【附近+关键词】当' . $this->my . '提醒您输入地理位置的时候就OK啦';
		}

		$data = array();
		$data['time'] = time();
		$data['token'] = $this->token;
		$data['keyword'] = $keyword;
		$data['uid'] = $this->data['FromUserName'];
		$re = M('Nearby_user');
		$user = $re->where(array('token' => $this->token, 'uid' => $data['uid']))->find();

			if ($user == false) {
				$re->data($data)->add();
			}
			else {
				$id['id'] = $user['id'];
				$re->where($id)->save($data);
			}
		}

		return '主人【' . $this->my . '】已经接收到你的指令' . "\n" . '请发送您的地理位置(对话框右下角点击＋号，然后点击“位置”)给我哈';
	}

	private function wysw()
	{
		$routerUrl = Router::login($this->token, $this->data['FromUserName']);
		$thisItem = M('Router_config')->where(array('token' => $this->token))->find();
		return array(
	array(
		array($thisItem['title'], $thisItem['info'], $thisItem['picurl'], $routerUrl)
		),
	'news'
	);
	}

	private function recordLastRequest($key, $msgtype = 'text')
	{
		$rdata = array();
		$rdata['time'] = time();
		$rdata['token'] = $this->token;
		$rdata['keyword'] = $key;
		$rdata['msgtype'] = $msgtype;
		$rdata['uid'] = $this->data['FromUserName'];
		$user_request_model = M('User_request');
		$user_request_row = $user_request_model->where(array('token' => $this->token, 'msgtype' => $msgtype, 'uid' => $rdata['uid']))->find();

		if (!$user_request_row) {
			$user_request_model->add($rdata);
		}
		else {
			$rid['id'] = $user_request_row['id'];
			$user_request_model->where($rid)->save($rdata);
		}
	}

	public function map($x, $y)
	{
		if (C('baidu_map')) {
			$transUrl = 'http://api.map.baidu.com/ag/coord/convert?from=2&to=4&x=' . $x . '&y=' . $y;
			$json = Http::fsockopenDownload($transUrl);

			if ($json == false) {
				$json = file_get_contents($transUrl);
			}

			$arr = json_decode($json, true);
			$x = base64_decode($arr['x']);
			$y = base64_decode($arr['y']);
		}
		else {
			$amap = new amap();
			$lact = $amap->coordinateConvert($y, $x, 'gps');
			$x = $lact['latitude'];
			$y = $lact['longitude'];
		}

		$user_request_model = M('User_request');
		$urWhere = array('token' => $this->token, 'msgtype' => 'text', 'uid' => $this->data['FromUserName']);
		$urWhere['time'] = array('gt', time() - (5 * 60));
		$user_request_row = $user_request_model->where($urWhere)->find();

		if (!(strpos($user_request_row['keyword'], '附近') === false)) {
			if (!$this->turnFunc('lbsNews')) {
				$user = M('Nearby_user')->where(array('token' => $this->token, 'uid' => $this->data['FromUserName']))->find();
				$keyword = $user['keyword'];
				$radius = 2000;

			if (C('baidu_map')) {
				$map = new baiduMap($keyword, $x, $y);
				$str = $map->echoJson();
				$array = json_decode($str);
				$map = array();

				foreach ($array as $key => $vo) {
					$map[] = array($vo->title, $key, rtrim($this->siteUrl, '/') . '/tpl/static/images/home.jpg', $vo->url);
				}

				if ($map) {
					return array($map, 'news');
				}
				else {
					$str = file_get_contents($this->siteUrl . '/map.php?keyword=' . urlencode($keyword) . '&x=' . $x . '&y=' . $y);
					$array = json_decode($str);
					$map = array();

					foreach ($array as $key => $vo) {
						$map[] = array($vo->title, $key, rtrim($this->siteUrl, '/') . '/tpl/static/images/home.jpg', $vo->url);
					}

					if ($map) {
						return array($map, 'news');
					}
					else {
						return array('附近信息无法调出，请稍候再试一下（关键词' . $keyword . ',坐标：' . $x . '-' . $y . ')', 'text');
					}
				}
			}
			else {
				$amamp = new amap();
				return $amamp->around($x, $y, $keyword, $radius);
			}
		}
		else {
				$lbsImg = new lbsImgNews($this->token, $this->data['FromUserName'], $this->siteUrl);
				return $lbsImg->news($x, $y);
			}
		}
		else {
			$mapAction = new Maps($this->token);
			if (!(strpos($user_request_row['keyword'], '开车去') === false) || !(strpos($user_request_row['keyword'], '坐公交') === false) || !(strpos($user_request_row['keyword'], '步行去') === false)) {
				if (!(strpos($user_request_row['keyword'], '步行去') === false)) {
					$companyid = str_replace('步行去', '', $user_request_row['keyword']);

					if (!$companyid) {
						$companyid = 1;
					}

					return $mapAction->walk($x, $y, $companyid);
				}

				if (!(strpos($user_request_row['keyword'], '开车去') === false)) {
					$companyid = str_replace('开车去', '', $user_request_row['keyword']);

					if (!$companyid) {
						$companyid = 1;
					}

					return $mapAction->drive($x, $y, $companyid);
				}

				if (!(strpos($user_request_row['keyword'], '坐公交') === false)) {
					$companyid = str_replace('坐公交', '', $user_request_row['keyword']);

					if (!$companyid) {
						$companyid = 1;
					}

					return $mapAction->bus($x, $y, $companyid);
				}
			}
			else {
				switch ($user_request_row['keyword']) {
				default:
					return $this->companyMap();
					break;

				case '最近的':
					return $mapAction->nearest($x, $y);
					break;
				}
			}
		}
	}

	private function _checkFunction($function)
	{
		$userGroup = M('UserGroup')->where(array('id' => $this->user['gid']))->find();
		$function = strtolower($function);
		$functions = array_map('strtolower', explode(',', $userGroup['func']));
		$funname = M('Function')->where(array('funname' => $function))->find();

		if (!empty($funname)) {
			$status = $funname['status'];
		}

		return in_array($function, $functions) && $status ? true : false;
	}

	private function suanming($name)
	{
		if ($this->_checkFunction('suanming')) {
			$name = implode('', $name);

			if (empty($name)) {
				return '主人' . $this->my . '提醒您正确的使用方法是[算命+姓名]';
			}

			$data = require_once CONF_PATH . 'suanming.php';
			$num = mt_rand(0, 80);
			return $name . "\n" . trim($data[$num]);
		}
		else {
			return $this->noreplyReturn();
		}
	}

	public function geci($n)
	{
		if ($this->_checkFunction('geci')) {
			$name = implode ( '', $n );
		    @($str = 'http://api.ajaxsns.com/api.php?key=free&appid=0&msg=' . urlencode ( ('歌词' . $name) ));
	     	$json = json_decode ( file_get_contents ( $str ) );
		    $str = str_replace ( '{br}', "\n", $json->content );
	    	return str_replace ( 'mzxing_com', 'pigcms', $str );
		}
		else {
			return $this->noreplyReturn();
		}
	}

	private function yuming($n)
	{
		if ($this->_checkFunction('whois')) {
			$name = implode ( '', $n );
		    $str = 'http://api.ajaxsns.com/api.php?key=free&appid=0&msg=' . urlencode ( ('域名 ' . $name) );
		    $json = json_decode ( file_get_contents ( $str ) );
		    $str = str_replace ( '{br}', "\n", $json->content );
		    return str_replace ( 'mzxing_com', 'pigcms', $str );
		}
		else {
			return $this->noreplyReturn();
		}
	}

	private function tianqi($n)
	{
		if ($this->_checkFunction('tianqi')) {
			$name = implode ( '', $n );
		if ($name == '') {
			$name = '北京';
		}
		$url = 'http://api.map.baidu.com/telematics/v3/weather?location=' . urlencode ( $name ) . '&output=json&ak=908bb5a05fa5f839dafeb1a957c7ed51';
		$weatherJson = file_get_contents ( $url );
		$weather = json_decode ( $weatherJson, true );
		$re = $weather ['error'];
		if ($re == 0) {
			$map = array ();
			$map1 = $weather ['results'] ['0'] ['weather_data'];
			
			foreach ( $map1 as $key => $vo ) {
				
				$map [] = array (
						
						$vo ['date'] . $vo ['weather'] . $vo ['wind'] . $vo ['temperature'],
						
						'',
						
						$vo ['dayPictureUrl'],
						
						'' 
				);
			}
			$arr1 = array (
					$weather ['date'] . $weather ['results'] ['0'] ['currentCity'] . "最近三天天气预报",
					'',
					'',
					'' 
			);
			array_unshift ( $map, $arr1 );
			return array (
					
					$map,
					
					'news' 
			);
		} else {
			$map = "您输入的格式有误，输入正确的方式是：城市+天气 例如：北京天气";
			return $map;
		}
		}
		else {
			return $this->noreplyReturn();
		}
	}

	private function shouji($n)
	{
		if ($this->_checkFunction('shouji')) {
			$name = implode ( '', $n );
		@($str = 'http://api.ajaxsns.com/api.php?key=free&appid=0&msg=' . urlencode ( ('归属' . $name) ));
		// @$str = $this->apiServer . '/pgicms_api/api.php?key=free&server_key='
		// . base64_encode ( C ( 'server_key' ) ) . '&server_topdomain=' . C (
		// 'server_topdomain' ) . '&appid=0&msg=' . urlencode ( '归属' . $name );
		$json = json_decode ( file_get_contents ( $str ) );
		$str = str_replace ( '{br}', "\n", $json->content );
		$str = str_replace ( '菲菲', $this->my, str_replace ( '提示：', $this->my . '提醒您:', str_replace ( '{br}', "\n", $str ) ) );
		return $str;
		}
		else {
			return $this->noreplyReturn();
		}
	}

	private function shenfenzheng($n)
	{
		if ($this->_checkFunction('shenfenzheng')) {
			$n = implode('', $n);

		if (1 < count($n)) {
			$this->error_msg($n);
			return false;
		}

		$str1 = file_get_contents('http://www.youdao.com/smartresult-xml/search.s?jsFlag=true&type=id&q=' . $n);
		$array = explode(':', $str1);
		$array[2] = rtrim($array[4], ',\'gender\'');
		$str = trim($array[3], ',\'birthday\'');

		if ($str !== iconv('UTF-8', 'UTF-8', iconv('UTF-8', 'UTF-8', $str))) {
			$str = iconv('GBK', 'UTF-8', $str);
		}

		$str = '【身份证】 ' . $n . "\n" . '【地址】' . $str . '' . "\n" . ' 【该身份证主人的生日】' . str_replace('\'', '', $array[2]);
		return $str;
		}
		else {
			return $this->noreplyReturn();
		}
	}

	private function gongjiao($data)
	{
		if ($this->_checkFunction('gongjiao')) {
			$data = array_merge($data);

		if (count($data) < 2) {
			$this->error_msg();
			return false;
		}

		$json = file_get_contents('http://www.twototwo.cn/bus/Service.aspx?format=json&action=QueryBusByLine&key=a3f88d7c-86b6-4815-9dae-70668fc1f0d5&zone=' . $data[0] . '&line=' . $data[1]);
		$data = json_decode($json);
		$xianlu = $data->Response->Head->XianLu;
		$xdata = get_object_vars($xianlu->ShouMoBanShiJian);
		$xdata = $xdata['#cdata-section'];
		$piaojia = get_object_vars($xianlu->PiaoJia);
		$xdata = $xdata . ' -- ' . $piaojia['#cdata-section'];
		$main = $data->Response->Main->Item->FangXiang;
		$xianlu = $main[0]->ZhanDian;
		$str = '【本公交途经】' . "\n" . '';
		

		for ($i = 0; $i < count($xianlu); $i++) {
			$str .= "\n" . trim($xianlu[$i]->ZhanDianMingCheng);
		}

		return $str;
		}
		else {
			return $this->noreplyReturn();
		}
	}

	private function huoche($data, $time = '')
	{
		if ($this->_checkFunction('huoche')) {
			$data = array_merge($data);
		$data[2] = date('Y', time()) . $time;

		if (count($data) != 3) {
			$this->error_msg($data[0] . '至' . $data[1]);
			return false;
		}

		$time = (empty($time) ? date('Y-m-d', time()) : date('Y-', time()) . $time);
		$json = file_get_contents('http://www.twototwo.cn/train/Service.aspx?format=json&action=QueryTrainScheduleByTwoStation&key=a3f88d7c-86b6-4815-9dae-70668fc1f0d5&startStation=' . $data[0] . '&arriveStation=' . $data[1] . '&startDate=' . $data[2] . '&ignoreStartDate=0&like=1&more=0');

		if ($json) {
			$data = json_decode($json);
			$main = $data->Response->Main->Item;

			if (10 < count($main)) {
				$conunt = 10;
			}
			else {
				$conunt = count($main);
			}

			

			for ($i = 0; $i < $conunt; $i++) {
				$str .= '' . "\n" . ' 【编号】' . $main[$i]->CheCiMingCheng . '' . "\n" . ' 【类型】' . $main[$i]->CheXingMingCheng . '' . "\n" . '【发车时间】:　' . $time . ' ' . $main[$i]->FaShi . '' . "\n" . '【耗时】' . $main[$i]->LiShi . ' 小时';
				$str .= '' . "\n" . '----------------------';
			}
		}
		else {
			$str = '没有找到 ' . $name . ' 至 ' . $toname . ' 的列车';
		}

		return $str;
		}
		else {
			return $this->noreplyReturn();
		}
	}

	private function fanyi($name)
	{
		if ($this->_checkFunction('fanyi')) {
			$name = array_merge($name);
		$url = 'http://openapi.baidu.com/public/2.0/bmt/translate?client_id=kylV2rmog90fKNbMTuVsL934&q=' . $name[0] . '&from=auto&to=auto';
		$json = Http::fsockopenDownload($url);

		if ($json == false) {
			$json = file_get_contents($url);
		}

		$json = json_decode($json);
		$str = $json->trans_result;

		if ($str[0]->dst == false) {
			return $this->error_msg($name[0]);
		}

		$mp3url = 'http://www.apiwx.com/aaa.php?w=' . $str[0]->dst;

		if (strpos($mp3url, ' ')) {
			return array($name[0] . ':' . $str[0]->dst, 'text');
		}
		else {
			return array(
	array($str[0]->src, $str[0]->dst, $mp3url, $mp3url),
	'music'
	);
		}
		}
		else {
			return $this->noreplyReturn();
		}
	}

	private function caipiao($name)
	{
		if ($this->_checkFunction('caipiao')) {
			$name = array_merge($name);
		$url = 'http://api2.sinaapp.com/search/lottery/?appkey=0020130430&appsecert=fa6095e113cd28fd&reqtype=text&keyword=' . $name[0];
		$json = Http::fsockopenDownload($url);

		if ($json == false) {
			$json = file_get_contents($url);
		}

		$json = json_decode($json, true);
		$str = $json['text']['content'];
		return $str;
		}
		else {
			return $this->noreplyReturn();
		}
	}

	public function gupiao($name)
	{
		if ($this->_checkFunction('gupiao')) {
			$url = 'http://api2.sinaapp.com/search/stock/?appkey=0020130430&appsecert=fa6095e113cd28fd&reqtype=text&keyword=' . $name[1];
		$json = Http::fsockopenDownload($url);

		if ($json == false) {
			$json = file_get_contents($url);
		}

		$json = json_decode($json, true);
		$str = $json['text']['content'];
		return $str;
		}
		else {
			return $this->noreplyReturn();
		}
	}

	public function getmp3($data)
	{
		$obj = new getYu();
		$ContentString = $obj->getGoogleTTS($data);
		$randfilestring = 'mp3/' . time() . '_' . sprintf('%02d', rand(0, 999)) . '.mp3';
		return rtrim($this->siteUrl, '/') . $randfilestring;
	}

	public function xiaohua()
	{
		if ($this->_checkFunction('xiaohua')) {
			$this->ltkey=C('liaotian_key');
		$name=implode('',$n);
		@$str = 'http://www.tuling123.com/openapi/api?key='.$this->ltkey.'&info=' . urlencode('笑话' . $name);
		$json=json_decode(file_get_contents($str));
		$str=str_replace('{br}',"\n",$json->content);
		return str_replace(array('mzxing_com','提示：按分类看笑话请发送“笑话分类”'),array('weimicms',''),$str);
		}
		else {
			return $this->noreplyReturn();
		}
	}

	private function liaotian($name)
	{
		$name=array_merge($name);
		$this->chat($name[0]);
	}

	private function chat($name){
		$this->ltkey=C('liaotian_key');
		$function=M('Function')->where(array('funname'=>'liaotian'))->find();
		if (!$function['status']){
			return '';
		}
		$this->requestdata('textnum');
		$check=$this->user('connectnum');
		if($check['connectnum']!=1){
			return C('connectout');
		}
                if (!(strpos($name, '你叫') === FALSE)||!(strpos($name, '你是') === FALSE)){
                        return '咳咳，我是智能微信机器人';
		}
		if($name == "你叫什么" || $name == "你是谁"){
                        return '咳咳，我是聪明与智慧并存的美女，主人你可以叫我' . $this -> my . ',人家刚交男朋友,你不可追我啦';
		}elseif($name == "你父母是谁" || $name == "你爸爸是谁" || $name == "你妈妈是谁"){
                        return '主人,' . $this -> my . '是您创造的,所以他们是我的父母,不过主人我属于你的';
		}elseif($name == '糗事'){
                        $name = '笑话';
		}elseif($name=='网站'||$name=='官网'||$name=='网址'||$name=='3g网址'){
			return "【".C('site_name')."】\n".C('site_name')."\n【".C('site_name')."服务宗旨】\n化繁为简,让菜鸟也能使用强大的系统!";
		}
     $str =file_get_contents('http://www.tuling123.com/openapi/api?key='.$this->ltkey.'&info='.urlencode($name));
     $arr = json_decode($str,true);
      //return  $arr['text'];
	  return '【机器人】'.$arr['text'];
	}

	private function fistMe($data)
	{
		if (('event' == $data['MsgType']) && ('subscribe' == $data['Event'])) {
			return $this->help();
		}
	}

	private function help()
	{
		$this->behaviordata('help', '', '1');
		$data = M('Areply')->where(array('token' => $this->token))->find();
		if (!$data || !$data['content']) {
			$data = array('content' => '恭喜您，接入成功');
		}

		return array(preg_replace('/(' . "\r\n" . ')|(' . "\r" . ')|(' . "\n" . ')/', "\n", $data['content']), 'text');
	}

	private function error_msg($data)
	{
		return '没有找到' . $data . '相关的数据';
	}

	private function user($action, $keyword = '')
	{
		$user = $this->wxuser;
		$usersdata = M('Users');
		$dataarray = array('id' => $user['uid']);
		$users = $this->user;
		$group = M('User_group')->where(array('id' => $users['gid']))->find();

		if ($users['diynum'] < $group['diynum']) {
			$data['diynum'] = 1;

			if ($action == 'diynum') {
			}
		}

		if ($users['connectnum'] < $group['connectnum']) {
			$data['connectnum'] = 1;

			if ($action == 'connectnum') {
				$usersdata->where($dataarray)->setInc('connectnum');
			}
		}

		if (time() < $users['viptime']) {
			$data['viptime'] = 1;
		}

		return $data;
	}

	private function requestdata($field)
	{
		$data['year'] = date('Y');
		$data['month'] = date('m');
		$data['day'] = date('d');
		$data['token'] = $this->token;
		$mysql = M('Requestdata');
		$check = $mysql->field('id')->where($data)->find();

		if ($check == false) {
			$data['time'] = time();
			$data[$field] = 1;
			$mysql->add($data);
		}
		else {
			$mysql->where($data)->setInc($field);
		}
	}

	private function behaviordata($field, $id = '', $type = '')
	{
		$data['date'] = date('Y-m-d', time());
		$data['token'] = $this->token;
		$data['openid'] = $this->data['FromUserName'];
		$data['keyword'] = $this->data['Content'];

		if (!$data['keyword']) {
			$data['keyword'] = '用户关注';
		}

		$data['model'] = $field;

		if ($id != false) {
			$data['fid'] = $id;
		}

		if ($type != false) {
			$data['type'] = 1;
		}

		$mysql = M('Behavior');
		$check = $mysql->field('id')->where($data)->find();
		$this->updateMemberEndTime($data['openid']);

		if ($check == false) {
			$data['num'] = 1;
			$data['enddate'] = time();
			$mysql->add($data);
		}
		else {
			$mysql->where($data)->setInc('num');
		}
	}

	private function updateMemberEndTime($openid)
	{
		$mysql = M('Wehcat_member_enddate');
		$id = $mysql->field('id')->where(array('openid' => $openid))->find();
		$data['enddate'] = time();
		$data['openid'] = $openid;
		$data['token'] = $this->token;

		if ($id == false) {
			$mysql->add($data);
		}
		else {
			$data['id'] = $id['id'];
			$mysql->save($data);
		}
	}

	private function selectService()
	{
		if (!C('without_chat')) {
			$this->behaviordata('chat', '');
			$sepTime = 30 * 60;
			$nowTime = time();
			$time = $nowTime - $sepTime;
			$where['token'] = $this->token;
			$serviceUserWhere = array('token' => $this->token, 'status' => 0);
			$serviceUserWhere['endJoinDate'] = array('gt', $time);
			$serviceUser = M('Service_user')->field('id')->where($serviceUserWhere)->select();

			if ($serviceUser != false) {
				$list = M('wechat_group_list')->field('id')->where(array('openid' => $this->data['FromUserName'], 'token' => $this->token))->find();

				if ($list == false) {
					$this->adddUserInfo();
				}

				$serviceJoinDate = M('wehcat_member_enddate')->field('id,uid,joinUpDate')->where(array('token' => $this->token, 'openid' => $this->data['FromUserName']))->find();
				if (($serviceJoinDate['uid'] == false) || ($sepTime < ($nowTime - $serviceJoinDate['joinUpDate']))) {
					foreach ($serviceUser as $key => $users) {
						$user[] = $users['id'];
					}

					if (count($user) == 1) {
						$id = $user[0];
					}
					else {
						$rand = mt_rand(0, count($user) - 1);
						$id = $user[$rand];
					}

					$where['id'] = $serviceJoinDate['id'];
					$where['uid'] = $id;
					M('wehcat_member_enddate')->data($where)->save();
				}
				else {
					exit();
				}
			}
		}
	}

	private function baike($name)
	{
		$name = implode('', $name);

		if ($name == 'imicms') {
			return '世界上最牛B的微信营销系统，两天前被腾讯收购，当然这只是一个笑话';
		}

		$name_gbk = iconv('utf-8', 'gbk', $name);
		$encode = urlencode($name_gbk);
		$url = 'http://baike.baidu.com/list-php/dispose/searchword.php?word=' . $encode . '&pic=1';
		$get_contents = $this->httpGetRequest_baike($url);
		$get_contents_gbk = iconv('gbk', 'utf-8', $get_contents);
		preg_match('/URL=(\\S+)\'>/s', $get_contents_gbk, $out);
		$real_link = 'http://baike.baidu.com' . $out[1];
		$get_contents2 = $this->httpGetRequest_baike($real_link);
		preg_match('#"Description"\\scontent="(.+?)"\\s\\/\\>#is', $get_contents2, $matchresult);
		if (isset($matchresult[1]) && ($matchresult[1] != '')) {
			return htmlspecialchars_decode($matchresult[1]);
		}
		else {
			return '抱歉，没有找到与“' . $name . '”相关的百科结果。';
		}
	}

	private function getRecognition($id)
	{
		$GetDb = D('Recognition');
		$data = $GetDb->field('keyword,groupid')->where(array('id' => $id, 'status' => 0))->find();

		if ($data != false) {
			$GetDb->where(array('id' => $id))->setInc('attention_num');
			$wecha_id = $this->data['FromUserName'];
			$group_list = M('wechat_group_list');
			$fid = $group_list->where(array('token' => $this->token, 'openid' => $wecha_id))->getField('id');

			if ($fid) {
				$group_list->where('id=' . $fid)->setField('g_id', $data['groupid']);
			}
			else {
				$group_list->add(array('token' => $this->token, 'openid' => $wecha_id, 'g_id' => $data['groupid']));
			}

			$access_token = $this->_getAccessToken();
			$url = 'https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=' . $access_token;
			$json = json_decode($this->curlGet($url, 'post', '{"openid":"' . $wecha_id . '","to_groupid":' . $data['groupid'] . '}'));
			return $data['keyword'];
		}
		else {
			return false;
		}
	}

	private function api_notice_increment($url, $data, $converturl = 1, $xmlmode = 1)
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';

		if ($converturl) {
			if (strpos($url, '?')) {
				$url .= '&token=' . $this->token;
			}
			else {
				$url .= '?token=' . $this->token;
			}
		}

		if ($xmlmode) {
			$headers = array('User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1', 'Accept-Language: en-us,en;q=0.5', 'Referer:http://mp.weixin.qq.com/', 'Content-type: text/xml');
		}

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);

		if (curl_errno($ch)) {
			return false;
		}
		else {
			return $tmpInfo;
		}
	}

	private function httpGetRequest_baike($url)
	{
		$headers = array('User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1', 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', 'Accept-Language: en-us,en;q=0.5', 'Referer: http://www.baidu.com/');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$output = curl_exec($ch);
		curl_close($ch);

		if ($output === false) {
			return 'cURL Error: ' . curl_error($ch);
		}

		return $output;
	}

	private function adddUserInfo()
	{
		$access_token = $this->_getAccessToken();
		$url2 = 'https://api.weixin.qq.com/cgi-bin/user/info?openid=' . $this->data['FromUserName'] . '&access_token=' . $access_token;
		$classData = json_decode($this->curlGet($url2));
		$db = M('wechat_group_list');
		$data['token'] = $this->token;
		$data['openid'] = $this->data['FromUserName'];
		$item = $db->where(array('token' => $this->token, 'openid' => $this->data['FromUserName']))->find();
		$data['nickname'] = str_replace('\'', '', $classData->nickname);
		$data['sex'] = $classData->sex;
		$data['city'] = $classData->city;
		$data['province'] = $classData->province;
		$data['headimgurl'] = $classData->headimgurl;
		$data['subscribe_time'] = $classData->subscribe_time;
		$url3 = 'https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=' . $access_token;
		$json2 = json_decode($this->curlGet($url3, 'post', '{"openid":"' . $data['openid'] . '"}'));
		$data['g_id'] = $json->groupid;

		if (!$data['g_id']) {
			$data['g_id'] = 0;
		}

		if (!$item) {
			$db->data($data)->add();
		}
		else {
			$db->where(array('token' => $this->token, 'openid' => $this->data['FromUserName']))->save($data);
		}
	}

	private function _getAccessToken()
	{
		$where = array('token' => $this->token);
		$this->thisWxUser = M('Wxuser')->where($where)->find();
		$apiOauth = new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
		return $access_token;
	}

	private function curlGet($url, $method = 'get', $data = '')
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}

	private function get_tags($title, $num = 10)
	{
		vendor('Pscws.Pscws4', '', '.class.php');
		$pscws = new PSCWS4();
		$pscws->set_dict(CONF_PATH . 'etc/dict.utf8.xdb');
		$pscws->set_rule(CONF_PATH . 'etc/rules.utf8.ini');
		$pscws->set_ignore(true);
		$pscws->send_text($title);
		$words = $pscws->get_tops($num);
		$pscws->close();
		$tags = array();

		foreach ($words as $val) {
			$tags[] = $val['word'];
		}

		return implode(',', $tags);
	}

	public function handleIntro($str)
	{
		$search = array('&quot;', '&nbsp;');
		$replace = array('"', '');
		return str_replace($search, $replace, $str);
	}

	private function turnFunc($name)
	{
		if (strpos($this->fun, $name) === false) {
			return false;
		}
		else {
			return true;
		}
	}

	public function api($mothod)
	{
		$params = func_get_args();
		return self::$mothod($params[1], $params[2], $params[3], $params[4], $params[5]);
	}
	//照片墙
	public function zhaopianwall($zhaopianwall_result){

        $message = $this->data;

        $zhaopianwall_name = '';

        if ($message['MsgType'] == 'text') {

            $zhaopianwall_name = $message['Content'];

        }

        //取消直接删除缓存

        if ($zhaopianwall_name == '取消') {

            S('zhaopianwall_' . $this->data['FromUserName'], NULL);

            return array('晒图片取消成功！感谢您的参与', 'text');

        } else {

            S('zhaopianwall_' . $this->data['FromUserName'], NULL);

            $zhaopianwall_result['username'] = $zhaopianwall_name;

            $pic_wall_inf = M('pic_wall')->where(array('token' => $this->token, 'id' => $zhaopianwall_result['uid']))->order('id desc')->find();

            M('pic_walllog')->data($zhaopianwall_result)->add();

            if ($zhaopianwall_result['state']) {

                //照片上传成功

                return array(array(array('图片上墙成功', $pic_wall_inf['info'], $pic_wall_inf['starpicurl'], ((((C('site_url') . '/index.php?g=Wap&m=Zhaopianwall&a=index&token=') . $this->token) . '&wecha_id=') . $this->data['FromUserName']) . '&sgssz=mp.weixin.qq.com')), 'news');

            } else {

                //照片需要审核

                return array('图片上传成功，正在审核，审核通过后可以显示', 'text');

            }

        }
	}
}

?>
