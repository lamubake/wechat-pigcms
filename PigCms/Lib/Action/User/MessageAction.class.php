<?php

class MessageAction extends UserAction
{
	public $thisWxUser;

	public function _initialize()
	{
		parent::_initialize();
		$where = array('token' => $this->token);
		$this->thisWxUser = M('Wxuser')->where($where)->find();
	}

	public function sendHistory()
	{
		$db = D('Send_message');
		$where['token'] = $this->token;
		$count = $db->where($where)->count();
		$page = new Page($count, 25);
		$info = $db->where($where)->order('id DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
		$this->assign('page', $page->show());
		$this->assign('info', $info);
		$this->display();
	}

	public function index()
	{
		$wechat_group_db = M('Wechat_group');
		$groups = $wechat_group_db->where(array('token' => $this->token))->order('id ASC')->select();
		$this->assign('groups', $groups);

		if (IS_POST) {
			$msgtype = $this->_post('send_type', 'intval');
			$openid = rtrim($_POST['openid'], ',');
			$wechatgroupid = $this->_post('wechatgroupid', 'intval');
			$imgids = ltrim($_POST['imgids'], ',');

			if ($imgids) {
				$imgidsArr = explode(',', $imgids);
			}

			if ($msgtype == 1) {
				if ($wechatgroupid == '') {
					$this->error('请选择群发分组');
				}
			}
			else if ($msgtype == 2) {
				if (empty($openid)) {
					$this->error('请选择群发粉丝');
				}
			}

			if (empty($imgidsArr)) {
				$this->error('请选择图文消息');
			}

			$imgs = array();

			for ($i = 0; $i < count($imgidsArr); $i++) {
				$imgs[$i] = M('Img')->where(array('id' => $imgidsArr[$i]))->find();
			}

			$apiOauth = new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);

			if ($access_token) {
				$postMedia = array();
				$postMedia['access_token'] = $access_token;
				$postMedia['type'] = 'image';
				$str = '';

				foreach ($imgs as $img) {
					if (strpos($img['pic'], C('site_url')) !== false) {
						$postMedia['media'] = str_replace(C('site_url'), '.', $img['pic']);
					}
					else {
						$imgStr = $this->curlGet($img['pic']);

						if (!$imgStr) {
							$this->error('您的图文消息没有封面或者封面获取不到' . $img['pic']);
						}

						file_put_contents(CONF_PATH . 'img_' . $img['id'] . '.jpg', $imgStr);
						$postMedia['media'] = CONF_PATH . 'img_' . $img['id'] . '.jpg';
					}

					$postMedia['media'] = $_SERVER['DOCUMENT_ROOT'] . str_replace(array('./'), array('/'), $postMedia['media']);
					$rt = $this->curlPost('http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $postMedia['access_token'] . '&type=' . $postMedia['type'], array('media' => '@' . $postMedia['media']));

					if ($img['url'] != '') {
						$content_source_url = $this->convertLink($img['url']);
					}
					else {
						$content_source_url = '';
					}

					if ($rt['rt']) {
						$content = preg_replace('/<img [^>]*srctitle=[\'\\"]([^\'\\"]+)[^>]*>/', '<img src="$1" />', html_entity_decode($img['info']));
						$content = str_replace(array('"', '"/upload'), array('\\"', '"' . C('site_url') . '/upload'), $content);
						$str .= $comma . '{"thumb_media_id":"' . $rt['media_id'] . '","author":"","title":"' . $img['title'] . '","content_source_url":"' . $content_source_url . '","content":"' . $content . '","digest":"' . $img['text'] . '","show_cover_pic":' . $img['showpic'] . '}';
						$comma = ',';
					}
					else {
						$this->error('操作失败,curl_error:' . $rt['errorno']);
					}
				}

				if ($str) {
					$post = '{"articles": [' . $str . ']}';
					$rt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=' . $postMedia['access_token'], $post);

					if ($rt['rt']) {
						$row = array();
						$row['title'] = $this->_post('title', 'trim');
						$row['openid'] = $openid;
						$row['mediaid'] = $rt['media_id'];
						$row['groupid'] = $wechatgroupid;
						$row['msgtype'] = 'news';
						$row['imgids'] = $imgids;
						$row['token'] = $this->token;
						$row['status'] = 0;
						$row['send_type'] = $this->_post('send_type', 'intval');

						if (M('Send_message')->add($row)) {
							$this->success('信息保存完毕', U('Message/sendHistory', array('token' => $this->token)));
						}
					}
					else {
						$this->error('操作失败,curl_error:' . $rt['errorno']);
					}
				}
			}
			else {
				$this->error('获取access_token发生错误：错误代码' . $json->errcode . ',微信返回错误信息：' . $json->errmsg);
			}
		}
		else {
			$this->display();
		}
	}

	public function tosendAll()
	{
		if (IS_GET) {
			$id = $this->_get('id', 'intval');
			$info = M('Send_message')->where(array('token' => $this->token, 'id' => $id))->find();
			$apiOauth = new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);

			if ($access_token) {
				$openid = explode(',', $info['openid']);

				if ($info['send_type'] == 1) {
					$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $access_token, '{"filter":{"group_id":"' . $info['groupid'] . '"},"mpnews":{"media_id":"' . $info['mediaid'] . '"},"msgtype":"mpnews"}');
				}
				else if ($info['send_type'] == 2) {
					$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=' . $access_token, '{"touser":' . json_encode($openid) . ',"mpnews":{"media_id":"' . $info['mediaid'] . '"},"msgtype":"mpnews"}');
				}
				else {
					$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $access_token, '{"filter":{},"mpnews":{"media_id":"' . $info['mediaid'] . '"},"msgtype":"mpnews"}');
				}

				if ($sendrt['rt'] == false) {
					$this->error('操作失败,curl_error:' . $sendrt['errorno']);
				}
				else {
					M('Send_message')->where(array('id' => $id))->save(array('msg_id' => $sendrt['msg_id'], 'status' => 1, 'time' => time()));
					$this->success('发送任务已经启动，群发可能会在20分钟左右完成', U('Message/sendHistory', array('token' => $this->token)));
				}
			}
			else {
				$this->error('获取access_token发生错误：错误代码' . $json->errcode . ',微信返回错误信息：' . $json->errmsg);
			}
		}
	}

	public function message_fans()
	{
		$group = $this->_get('group', 'intval');
		$name = $this->_post('name', 'trim');
		$where = array('token' => $this->token);

		if ($group) {
			$where['g_id'] = $group;
		}

		if ($name) {
			$where['nickname'] = array('like', '%' . $name . '%');
		}

		$count = M('Wechat_group_list')->where($where)->count();
		$page = new Page($count, 10);
		$list = M('Wechat_group_list')->where($where)->order('id desc')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($list as $key => $val) {
			if ($val['g_id']) {
				$list[$key]['group_name'] = M('Wechat_group')->where(array('id' => $val['g_id']))->getField('name');
			}
			else {
				$list[$key]['group_name'] = '未分组';
			}
		}

		$this->assign('list', $list);
		$this->assign('page', $page->show());
		$this->display();
	}

	public function preview()
	{
		$id = $this->_get('id', 'intval');
		$info = M('Send_message')->where(array('token' => $this->token, 'id' => $id))->find();

		if (IS_POST) {
			$openid = $this->_post('openid', 'trim');

			if ($openid) {
				$apiOauth = new apiOauth();
				$access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);

				if ($access_token) {
					$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token=' . $access_token, '{"touser":"' . $openid . '","mpnews":{"media_id":"' . $info['mediaid'] . '"},"msgtype":"mpnews"}');

					if ($sendrt['rt'] == false) {
						$this->error('操作失败,curl_error:' . $sendrt['errorno']);
					}
					else {
						$this->success('预览消息发送');
					}
				}
				else {
					$this->error('获取access_token发生错误：错误代码' . $json->errcode . ',微信返回错误信息：' . $json->errmsg);
				}
			}
			else {
				$this->error('请填写openid');
			}
		}
		else {
			$this->display();
		}
	}

	public function getOpenid()
	{
		$name = $this->_get('name', 'trim');
		$where = array('token' => $this->token, 'nickname' => $name);
		$openid = M('Wechat_group_list')->where($where)->getField('openid');

		if ($openid) {
			echo json_encode(array('error' => 0, 'openid' => $openid));
		}
		else {
			echo json_encode(array('error' => 1, 'info' => '没有找到粉丝'));
		}
	}

	public function del()
	{
		$id = $this->_get('id', 'intval');
		$info = M('Send_message')->where(array('token' => $this->token, 'id' => $id))->find();

		if ($info['msg_id']) {
			$apiOauth = new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);

			if ($access_token) {
				$sendrt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token=' . $access_token, '{"msg_id":' . $info['msg_id'] . '}', 0);

				if (M('Send_message')->where(array('token' => $this->token, 'id' => $id))->delete()) {
					$this->success('删除成功');
				}
			}
			else {
				$this->error('获取access_token发生错误：错误代码' . $json->errcode . ',微信返回错误信息：' . $json->errmsg);
			}
		}
		else if (M('Send_message')->where(array('token' => $this->token, 'id' => $id))->delete()) {
			$this->success('删除成功');
		}
	}

	public function item()
	{
		if (isset($_GET['id'])) {
			$info = M('Send_message')->where(array('token' => $this->token, 'id' => intval($_GET['id'])))->find();
			$this->assign('info', $info);
		}

		if ($info['msgtype'] == 'news') {
			$imgids = explode(',', $info['imgids']);
			$imgID = 0;

			if ($imgids) {
				foreach ($imgids as $ii) {
					if (intval($ii)) {
						$imgID = $ii;
					}
				}
			}

			$thisNews = M('Img')->where(array('id' => $imgID))->find();
			$this->assign('img', $thisNews);
		}

		$this->display();
	}

	public function send()
	{
		$fans = M('Wechat_group_list')->where(array('token' => $this->token))->order('id ASC')->select();
		$i = intval($_GET['i']);
		$count = count($fans);
		$thisMessage = M('Send_message')->where(array('id' => intval($_GET['id'])))->find();

		if ($i < $count) {
			$fan = $fans[$i];
			$apiOauth = new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);

			if ($access_token) {
				switch ($thisMessage['msgtype']) {
				case 'text':
					$data = '{"touser":"' . $fan['openid'] . '","msgtype":"text","text":{"content":"' . $thisMessage['text'] . '"}}';
					break;

				case 'image':
				case 'voice':
					$data = '{"touser":"' . $fan['openid'] . '","msgtype":"' . $thisMessage['msgtype'] . '","' . $thisMessage['msgtype'] . '":{"media_id":"' . $thisMessage['mediaid'] . '"}}';
					break;

				case 'video':
					$data = '{"touser":"' . $fan['openid'] . '","msgtype":"' . $thisMessage['msgtype'] . '","' . $thisMessage['msgtype'] . '":{"media_id":"' . $thisMessage['mediaid'] . '","description":"' . $thisMessage['text'] . '","title":"' . $thisMessage['title'] . '"}}';
					break;

				case 'music':
					$data = '{"touser":"' . $fan['openid'] . '","msgtype":"' . $thisMessage['msgtype'] . '","' . $thisMessage['msgtype'] . '":{"media_id":"' . $thisMessage['mediaid'] . '"}}';
					break;

				case 'news':
					$imgids = explode(',', $thisMessage['imgids']);
					$imgID = 0;

					if ($imgids) {
						foreach ($imgids as $ii) {
							if (intval($ii)) {
								$imgID = $ii;
							}
						}
					}

					$thisNews = M('Img')->where(array('id' => $imgID))->find();

					if ($thisNews['url']) {
						$url = $this->convertLink($thisNews['url']);
					}
					else {
						$url = C('site_url') . U('Wap/Index/content', array('token' => $this->token, 'wecha_id' => $fan['openid'], 'id' => $thisNews['id']));
					}

					$thisNews['title'] = str_replace('"', '\\"', $thisNews['title']);
					$data = '{"touser":"' . $fan['openid'] . '","msgtype":"' . $thisMessage['msgtype'] . '","news":{"articles":[{"title":"' . $thisNews['title'] . '","description":"' . $thisNews['text'] . '","url":"' . $url . '","picurl":"' . $thisNews['pic'] . '"}]}}';
					break;
				}

				$rt = $this->curlPost('https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token, $data, 0);

				if ($rt['rt'] == false) {
				}
				else {
					M('Send_message')->where(array('id' => intval($thisMessage['id'])))->setInc('reachcount');
				}

				$i++;
				$this->success('发送中:' . $i . '/' . $count, U('Message/send', array('id' => $thisMessage['id'], 'i' => $i)));
			}
			else {
				$this->error('获取access_token发生错误：错误代码' . $json->errcode . ',微信返回错误信息：' . $json->errmsg);
			}
		}
		else {
			$this->success('发送完成，发送成功' . $thisMessage['reachcount'] . '条', U('Message/sendHistory'));
		}
	}

	public function img()
	{
		$db = M('Img');
		$where = array('token' => $this->token);
		$count = $db->where($where)->count();
		$Page = new Page($count, 5);
		$show = $Page->show();
		$list = $db->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order('id DESC')->select();
		$items = array();

		if ($list) {
			foreach ($list as $item) {
				array_push($items, array('id' => $item['id'], 'name' => $item['title'], 'pic' => $item['pic'], 'info' => str_replace(array('&#039;', '\'', "\r\n", "\r", "\n", '"'), '', $item['text']), 'linkcode' => '{siteUrl}/index.php?g=Wap&m=Index&a=content&token=' . $this->token . '&wecha_id={wechat_id}&id=' . $item['id'], 'linkurl' => '', 'keyword' => $item['keyword']));
			}
		}

		$this->assign('list', $items);
		$this->assign('page', $show);
		$this->display();
	}

	public function curlPost($url, $data, $showError = 1)
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno = curl_errno($ch);

		if ($errorno) {
			return array('rt' => false, 'errorno' => $errorno);
		}
		else {
			$js = json_decode($tmpInfo, 1);

			if (intval($js['errcode'] == 0)) {
				return array('rt' => true, 'errorno' => 0, 'media_id' => $js['media_id'], 'msg_id' => $js['msg_id']);
			}
			else if ($showError) {
				if ($js['errcode'] == '40130') {
					$this->error('抱歉，群发消息至少要选择两个人以上。');
				}
				else {
					$this->error('发生了Post错误：错误代码' . $js['errcode'] . ',微信返回错误信息：' . $js['errmsg']);
				}
			}
		}
	}

	public function curlGet($url)
	{
		$ch = curl_init();
		$header = 'Accept-Charset: utf-8';
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
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
}

?>
