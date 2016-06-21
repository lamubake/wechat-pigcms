<?php

class HelpingAction extends WapAction
{
	public $helping;
	public $isamap;

	public function _initialize()
	{
		parent::_initialize();
		$this->fans = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
		$id = $this->_get('id', 'intval');
		$helping = S($id . 'helping' . $this->token);

		if ($helping == '') {
			$helping = M('Helping')->where(array('token' => $this->token, 'id' => $this->_get('id', 'intval'), 'is_open' => 1))->find();

			if ($helping == '') {
				$this->error('活动不存在');
			}
			else {
				S($id . 'helping' . $this->token, $helping);
			}
		}

		$this->helping = $helping;

		if ($helping['is_newtp'] == 1) {
			$news_list = S($id . 'helping' . $this->token . 'news');

			if ($news_list == '') {
				$news_list = M('helping_news')->where(array('token' => $this->token, 'pid' => $id))->order('id')->select();

				foreach ($news_list as $nk => $nv) {
					$news_list[$nk]['url'] = $this->getLink($nv['url']);
				}

				S($id . 'helping' . $this->token . 'news', $news_list);
			}

			$prize_list = S($id . 'helping' . $this->token . 'prize');

			if ($prize_list == '') {
				$prize_list = M('helping_prize')->where(array('token' => $this->token, 'pid' => $id))->order('id')->select();
				S($id . 'helping' . $this->token . 'prize', $prize_list);
			}

			$this->assign('news_list', $news_list);
			$this->assign('prize_list', $prize_list);
		}

		D('Userinfo')->convertFake(D('HelpingRecord'), array('token' => $this->token, 'fakeopenid' => $this->fakeopenid, 'wecha_id' => $this->wecha_id));
		D('Userinfo')->convertFake(D('HelpingUser'), array('token' => $this->token, 'fakeopenid' => $this->fakeopenid, 'wecha_id' => $this->wecha_id));
		if (($this->helping['rank_num'] == '') || ($this->helping['rank_num'] == 0)) {
			if ($this->helping['is_newtp'] == 1) {
				$this->helping['rank_num'] = 10;
			}
			else {
				$this->helping['rank_num'] = 30;
			}
		}

		$reply_pic = explode('http', $this->helping['reply_pic']);

		if (count($reply_pic) <= 1) {
			$this->helping['reply_pic'] = C('site_url') . $this->helping['reply_pic'];
		}

		$this->assign('info', $this->helping);
	}

	public function index()
	{
		$id = $this->_get('id', 'intval');
		$share_key = $this->_get('share_key', 'trim');
		$now = time();
		M('helping')->where(array('token' => $this->token, 'id' => $id))->setInc('pv', 1);
		if (($_GET['tel'] != '') && ($this->wecha_id != '')) {
			$userinfo_tel = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->save(array('tel' => $_GET['tel'], 'isverify' => 1));
			$helping_user_tel = M('helping_user')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'pid' => $id))->save(array('tel' => $_GET['tel']));
			$this->fans['tel'] = $_GET['tel'];
			S('fans_' . $this->token . '_' . $this->wecha_id, $this->fans);
		}

		$my = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
		$myhelp = M('helping_user')->where(array('pid' => $id, 'token' => $this->token, 'wecha_id' => $this->wecha_id))->find();

		if ($myhelp['is_join2'] == 1) {
			$backtext = '返回我的';
		}
		else {
			$backtext = '我也参与';
		}

		$this->assign('backtext', $backtext);
		if (($myhelp['add_time'] == '') || ($myhelp['add_time'] == 0)) {
			M('helping_user')->where(array('pid' => $id, 'token' => $this->token, 'wecha_id' => $this->wecha_id))->save(array('add_time' => time()));
		}

		if ($share_key != '') {
			$is_my = M('helping_user')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $this->wecha_id, 'share_key' => $share_key))->find();

			if ($is_my != '') {
				$this->redirect('Helping/index', array('token' => $this->token, 'id' => $id));
			}
		}
		else {
			if ((($myhelp['is_join2'] == 0) && ($myhelp != '') && (1 < $myhelp['help_count'])) || ((intval($_GET['is_join2']) == 1) && ($myhelp['is_join2'] == 0))) {
				$join = M('helping_user')->where(array('pid' => $id, 'token' => $this->token, 'wecha_id' => $this->wecha_id))->save(array('is_join2' => 1, 'add_time' => time(), 'help_count' => $myhelp['help_count'] + 1));
				$this->redirect('Helping/index', array('token' => $this->token, 'id' => $id));
			}
		}

		if ($now < $this->helping['start']) {
			$is_over = 1;
		}
		else if ($this->helping['end'] < $now) {
			$is_over = 2;
		}
		else {
			$is_over = 0;
		}

		if ($this->fans) {
			$us = M('Helping_user')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'pid' => $this->helping['id']))->find();
			if (!empty($this->wecha_id) && empty($us)) {
				$data = array('pid' => $this->helping['id'], 'wecha_id' => $this->wecha_id, 'token' => $this->token, 'add_time' => 0, 'help_count' => 0, 'share_key' => 0, 'is_join2' => 0);
				$uid = M('Helping_user')->add($data);
				$share_key2 = $this->getKey($uid);
				M('Helping_user')->where(array('token' => $this->token, 'id' => $uid))->save(array('share_key' => $share_key2));
			}

			if ($share_key != '') {
				$user = M('Helping_user')->where(array('token' => $this->token, 'share_key' => $share_key, 'pid' => $this->helping['id']))->find();
			}
			else {
				$user = M('Helping_user')->where(array('token' => $this->token, 'wecha_id' => $this->fans['wecha_id'], 'pid' => $this->helping['id']))->find();
			}

			$rank = M('Helping_user')->where(array(
	'token'      => $this->token,
	'pid'        => $this->helping['id'],
	'is_join2'   => 1,
	'help_count' => array('gt', 0)
	))->order('help_count desc,share_num desc')->select();
			$i = 0;

			foreach ($rank as $v) {
				$i++;
				$paiming[$v['wecha_id']] = $i;
			}

			$user['help_rank'] = $paiming[$user['wecha_id']];
		}

		$count = M('Helping_user')->where(array(
	'token'      => $this->token,
	'pid'        => $this->helping['id'],
	'is_join2'   => 1,
	'help_count' => array('gt', 0)
	))->count();
		if (($this->helping['is_attention'] == 2) && !$this->isSubscribe()) {
			$this->memberNotice('', 1);
		}
		else {
			if ((($this->helping['is_reg'] == 1) || ($this->helping['is_sms'] == 1)) && empty($this->fans['tel'])) {
				if (($this->helping['is_sms'] == 0) && ($this->helping['is_reg'] == 1)) {
					$this->memberNotice();
				}
				else {
					if (($this->helping['is_sms'] == 1) && ($this->helping['is_newtp'] == 1)) {
						$this->assign('sms', 1);
						$this->assign('memberNotice', '<div style="display:none"></div>');
					}
				}
			}
			else {
				if (($this->helping['is_sms'] == 1) && empty($this->fans['tel']) && ($this->fans['isverify'] != 1) && ($this->helping['is_newtp'] == 1)) {
					$this->assign('sms', 1);
					$this->assign('memberNotice', '<div style="display:none"></div>');
				}
			}
		}

		$user['wechaname'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $user['wecha_id']))->getField('wechaname');
		$user['portrait'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $user['wecha_id']))->getField('portrait');
		$this->assign('share_key', $share_key);
		$this->assign('share', $share);
		$this->assign('rank', $this->get_rank());
		$this->assign('user', $user);
		M('helping_user')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $user['wecha_id']))->setInc('pv', 1);
		$this->assign('fans', $this->fans);
		$this->assign('count', $count);
		$this->assign('is_over', $is_over);
		$this->assign('my', $my);

		if ($this->helping['is_newtp'] == 1) {
			if ($_GET['helps'] == 1) {
				$helps_list = M('helping_record')->where(array('token' => $this->token, 'pid' => $id, 'share_key' => $user['share_key']))->order('addtime desc')->limit(99)->select();
				$helps_count = M('helping_record')->where(array('token' => $this->token, 'pid' => $id, 'share_key' => $user['share_key']))->count();

				foreach ($helps_list as $hk => $hv) {
					$helps_list[$hk]['wechaname'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $hv['wecha_id']))->getField('wechaname');
					$helps_list[$hk]['portrait'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $hv['wecha_id']))->getField('portrait');
				}

				$this->assign('helps_count', $helps_count);
				$this->assign('helps_list', $helps_list);
				$this->display('helps');
			}
			else {
				$this->display('index_new');
			}
		}
		else {
			$this->display();
		}
	}

	public function sms()
	{
		if ($_POST['tel'] != '') {
			$is_tel = M('userinfo')->where(array('token' => $_POST['token'], 'tel' => $_POST['tel'], 'isverify' => 1))->find();

			if ($is_tel == '') {
				$params = array();
				$session_sms = session($_POST['wecha_id'] . 'code' . $_POST['token'] . $_POST['id']);
				if ((time() < $session_sms['time']) && ($session_sms['tel'] == $_POST['tel'])) {
					$code = $session_sms['code'];
				}
				else {
					session($_POST['wecha_id'] . 'code' . $_POST['token'] . $_POST['id'], NULL);
					$code = rand(100000, 999999);
					$session_sms['tel'] = $_POST['tel'];
					$session_sms['code'] = $code;
					$session_sms['time'] = time() + (60 * 30);
					session($_POST['wecha_id'] . 'code' . $_POST['token'] . $_POST['id'], $session_sms);
				}

				$params['sms'] = array('token' => $this->token, 'mobile' => $_POST['tel'], 'content' => '您的验证码是：' . $code . '。 此验证码30分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！');
				$data['error'] = MessageFactory::method($params, 'SmsMessage');
				$this->ajaxReturn($data, 'JSON');
			}
			else {
				$data['error'] = 'tel';
				$this->ajaxReturn($data, 'JSON');
			}
		}
	}

	public function smsyz()
	{
		$session_sms = session($_POST['wecha_id'] . 'code' . $_POST['token'] . $_POST['id']);

		if ($_POST['code'] != $session_sms['code']) {
			$data['error'] = 1;
		}
		else if ($_POST['tel'] != $session_sms['tel']) {
			$data['error'] = 2;
		}
		else if ($session_sms['time'] < time()) {
			$data['error'] = 3;
		}
		else {
			$data['error'] = 0;
		}

		$this->ajaxReturn($data, 'JSON');
	}

	public function add_share()
	{
		$now = time();
		$share_key = $this->_get('share_key', 'trim');
		$cookie = cookie('helping_share');
		$cookie_arr = json_decode(json_encode($cookie), true);
		$share = M('Helping_user')->where(array('token' => $this->token, 'share_key' => $share_key))->find();
		$record = array('token' => $this->token, 'pid' => $share['pid'], 'share_key' => $share_key, 'addtime' => time(), 'wecha_id' => $this->wecha_id);

		if (empty($share)) {
			exit();
		}

		if ($now < $this->helping['start']) {
			echo json_encode(array('err' => 3, 'info' => '活动还没开始'));
			exit();
		}

		if ($this->helping['end'] < $now) {
			echo json_encode(array('err' => 4, 'info' => '活动已结束'));
			exit();
		}

		if ($share['wecha_id'] == $this->wecha_id) {
			exit();
		}

		$is_share = M('Helping_record')->where(array('token' => $this->token, 'pid' => $share['pid'], 'wecha_id' => $this->wecha_id, 'share_key' => $share_key))->count();
		if (in_array($share_key, $cookie_arr[$this->helping['id']]) || $is_share) {
			exit();
		}
		else if (M('Helping_record')->add($record)) {
			M('Helping_user')->where(array('token' => $this->token, 'pid' => $this->helping['id'], 'share_key' => $share_key))->setInc('help_count', 1);
			if (($share['add_time'] == 0) || ($share['add_time'] == '')) {
				M('Helping_user')->where(array('token' => $this->token, 'pid' => $this->helping['id'], 'share_key' => $share_key))->save(array('add_time' => time()));
			}

			if (empty($cookie_arr[$this->helping['id']])) {
				$cookie_arr[$this->helping['id']] = array();
			}

			array_push($cookie_arr[$this->helping['id']], $share_key);
			cookie('helping_share', $cookie_arr, time() + (3600 * 24 * 30));
			echo json_encode(array('err' => 0, 'info' => '你的好友成功增加了1点助力值'));
			exit();
		}
	}

	public function get_rank()
	{
		$where = array(
			'token'      => $this->token,
			'pid'        => $this->helping['id'],
			'is_join2'   => 1,
			'help_count' => array('gt', 0)
			);
		$limit = $this->helping['rank_num'];
		$rank = M('Helping_user')->where($where)->order('help_count desc,share_num desc')->limit($limit)->select();

		foreach ($rank as $key => $val) {
			$user_info = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $val['wecha_id']))->find();
			$rank[$key]['username'] = $user_info['wechaname'] ? $user_info['wechaname'] : '匿名';
			$rank[$key]['tel'] = $user_info['tel'] ? substr_replace($user_info['tel'], '****', 3, 4) : '无';
			$rank[$key]['portrait'] = $user_info['portrait'];
		}

		return $rank;
	}

	public function getKey($id)
	{
		$str = md5(time() . mt_rand(1000, 9999) . $id);
		return $str;
	}
}

?>
