<?php

class SentimentAction extends WapAction
{
	public $Sentiment;
	public $fans;

	public function _initialize()
	{
		parent::_initialize();
		
		$this->fans = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();

		if ($this->fans == '') {
			$this->error('商家未开启获取粉丝信息授权！获取不到您的个人信息！');
			exit();
		}

		$id = $this->_get('id', 'intval');
		$Sentiment = S($id . 'Sentiment' . $this->token);

		if ($Sentiment == '') {
			$Sentiment = M('Sentiment')->where(array('id' => $id, 'token' => $this->token, 'is_open' => 0))->find();

			if ($Sentiment == '') {
				$this->error('活动不存在');
				exit();
			}
			else {
				S($id . 'Sentiment' . $this->token, $Sentiment);
			}
		}

		$this->Sentiment = $Sentiment;
		$news_list = S($id . 'Sentiment' . $this->token . 'news');

		if ($news_list == '') {
			$news_list = M('Sentiment_news')->where(array('token' => $this->token, 'pid' => $id))->order('id')->select();

			foreach ($news_list as $nk => $nv) {
				$news_list[$nk]['url'] = $this->getLink($nv['url']);
			}

			S($id . 'Sentiment' . $this->token . 'news', $news_list);
		}

		$prize_list = S($id . 'Sentiment' . $this->token . 'prize');

		if ($prize_list == '') {
			$prize_list = M('Sentiment_prize')->where(array('token' => $this->token, 'pid' => $id))->order('id')->select();
			S($id . 'Sentiment' . $this->token . 'prize', $prize_list);
		}

		$this->assign('news_list', $news_list);
		$this->assign('prize_list', $prize_list);
		$this->Sentiment['name_zhi'] = mb_substr($this->Sentiment['name_zhi'], 0, 4, 'utf-8');
		$reply_pic = explode('http', $this->Sentiment['reply_pic']);

		if (count($reply_pic) <= 1) {
			$this->Sentiment['reply_pic'] = C('site_url') . $this->Sentiment['reply_pic'];
		}

		$this->assign('info', $this->Sentiment);
		$man_label = explode(',', $this->Sentiment['man_label']);
		$woman_label = explode(',', $this->Sentiment['woman_label']);

		for ($i = 0; $i < 4; $i++) {
			$nosex_label[$i] = $man_label[$i];
			$nosex_label[$i + 4] = $woman_label[$i];
		}

		$this->assign('man_label', $man_label);
		$this->assign('woman_label', $woman_label);
		$this->assign('nosex_label', $nosex_label);
		if ((($this->fans['sex'] == 0) || ($this->fans['sex'] == '')) && ($this->Sentiment['is_nosex'] == 0) && ($this->wecha_id != '')) {
			if ($_GET['sex'] == '') {
				$this->assign('is_nosex', 1);
			}
			else {
				M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->save(array('sex' => $_GET['sex']));
				$this->redirect('Sentiment/index', array('token' => $this->token, 'id' => $id, 'share_key' => $_GET['share_key']));
				exit();
			}
		}
	}

	public function index()
	{
		$id = $this->_get('id', 'intval');
		$share_key = $this->_get('share_key', 'trim');
		$now = time();
		if (($_GET['del_label'] == 1) && ($share_key == '') && ($this->wecha_id != '') && ($_GET['label_name'] != '')) {
			M('sentiment_label')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $this->wecha_id, 'label' => $_GET['label_name']))->save(array('state' => 0));
			$this->redirect('Sentiment/index', array('token' => $this->token, 'id' => $id, 'share_key' => $_GET['share_key']));
			exit();
		}

		if (($_GET['tel'] != '') && ($this->wecha_id != '')) {
			$userinfo_tel = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->save(array('tel' => $_GET['tel'], 'isverify' => 1));
			$Sentiment_user_tel = M('Sentiment_user')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'pid' => $id))->save(array('tel' => $_GET['tel']));
			$this->fans['tel'] = $_GET['tel'];
			S('fans_' . $this->token . '_' . $this->wecha_id, $this->fans);
			$this->redirect('Sentiment/index', array('token' => $this->token, 'id' => $id, 'share_key' => $_GET['share_key']));
			exit();
		}

		if ($share_key != '') {
			$is_my = M('Sentiment_user')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $this->wecha_id, 'share_key' => $share_key))->find();

			if ($is_my != '') {
				$this->redirect('Sentiment/index', array('token' => $this->token, 'id' => $id));
				exit();
			}
		}

		if ($now < $this->Sentiment['start']) {
			$is_over = 1;
		}
		else if ($this->Sentiment['end'] < $now) {
			$is_over = 2;
		}
		else {
			$is_over = 0;
		}

		if ($this->fans) {
			$my = M('Sentiment_user')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'pid' => $id))->find();
			if (!empty($this->wecha_id) && empty($my)) {
				$data = array('pid' => $id, 'wecha_id' => $this->wecha_id, 'token' => $this->token);
				$uid = M('Sentiment_user')->add($data);
				$share_key2 = $this->getKey($uid);
				$my['pid'] = $id;
				$my['wecha_id'] = $this->wecha_id;
				$my['token'] = $this->token;
				$my['share_key'] = $share_key2;
				M('Sentiment_user')->where(array('token' => $this->token, 'id' => $uid))->save(array('share_key' => $share_key2));
			}

			$my['wechaname'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->getField('wechaname');
			$my['portrait'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->getField('portrait');
			$my['sex'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->getField('sex');

			if ($share_key != '') {
				$user = M('Sentiment_user')->where(array('token' => $this->token, 'share_key' => $share_key, 'pid' => $id))->find();
				$user['wechaname'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $user['wecha_id']))->getField('wechaname');
				$user['portrait'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $user['wecha_id']))->getField('portrait');
				$user['sex'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $user['wecha_id']))->getField('sex');
			}
			else {
				$user = $my;
				if ((0 < $user['share_num']) && ($user['is_join'] == 0)) {
					M('Sentiment_user')->where(array('token' => $this->token, 'id' => $user['id']))->save(array('is_join' => 1, 'addtime' => $now));
				}
			}

			$rank = M('Sentiment_user')->where(array('token' => $this->token, 'pid' => $id, 'is_join' => 1))->order('help_count desc,addtime asc')->select();

			foreach ($rank as $k => $v) {
				$paiming[$v['wecha_id']] = $k + 1;
			}

			$user['help_rank'] = $paiming[$user['wecha_id']];
		}

		if (($_GET['del_tolabel'] == 1) && ($this->wecha_id != '') && ($_GET['label_name'] != '')) {
			$label_help_labels = M('sentiment_label_helps')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $this->wecha_id, 'label_wecha_id' => $user['wecha_id']))->getField('labels');
			$label_help_labels_array = explode(',', $label_help_labels);
			$label_help_labels_key = array_search($_GET['label_name'], $label_help_labels_array);

			if ($label_help_labels_key !== false) {
				array_splice($label_help_labels_array, $label_help_labels_key, 1);
			}

			$label_help_labels = implode(',', $label_help_labels_array);

			if ($label_help_labels == '') {
				M('sentiment_label_helps')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $this->wecha_id, 'label_wecha_id' => $user['wecha_id']))->delete();
			}
			else {
				M('sentiment_label_helps')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $this->wecha_id, 'label_wecha_id' => $user['wecha_id']))->save(array('labels' => $label_help_labels));
			}

			$thisLabel_count = M('sentiment_label')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $this->wecha_id, 'label' => $_GET['label_name']))->getField('count');

			if (1 < $thisLabel_count) {
				M('sentiment_label')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $user['wecha_id'], 'label' => $_GET['label_name']))->setDec('count', 1);
			}
			else {
				M('sentiment_label')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $user['wecha_id'], 'label' => $_GET['label_name']))->delete();
			}

			$this->redirect('Sentiment/index', array('token' => $this->token, 'id' => $id, 'share_key' => $_GET['share_key']));
			exit();
		}

		if (($this->Sentiment['is_attention'] == 1) && !$this->isSubscribe()) {
			$this->memberNotice('', 1);
		}
		else {
			if ((($this->Sentiment['is_reg'] == 1) || ($this->Sentiment['is_sms'] == 1)) && empty($this->fans['tel'])) {
				if ($this->Sentiment['is_sms'] == 0) {
					$this->memberNotice();
				}
				else {
					$this->assign('sms', 1);
					$this->assign('memberNotice', '<div style="display:none"></div>');
				}
			}
			else {
				if (($this->Sentiment['is_sms'] == 1) && empty($this->fans['tel']) && ($this->fans['isverify'] != 1)) {
					$this->assign('sms', 1);
					$this->assign('memberNotice', '<div style="display:none"></div>');
				}
			}
		}

		if (($my['tel'] != 0) || ($my['tel'] != '') || $this->isSubscribe() || ($this->fans['tel'] != 0) || ($this->fans['tel'] != '')) {
			$backtext = '返回我的';
		}
		else {
			$backtext = '我也参与';
		}

		$this->assign('backtext', $backtext);
		$this->assign('share_key', $share_key);
		$this->assign('user', $user);
		$this->assign('fans', $this->fans);
		$this->assign('is_over', $is_over);
		$this->assign('my', $my);
		$user_count = M('Sentiment_user')->where(array('token' => $this->token, 'pid' => $id, 'is_join' => 1))->count();
		$lt_user_count = M('Sentiment_user')->where(array(
	'token'      => $this->token,
	'pid'        => $id,
	'is_join'    => 1,
	'help_count' => array('elt', $user['help_count'])
	))->count();
		$bili = round($lt_user_count / $user_count, 2) * 100;
		$this->assign('bili', $bili);
		$label_list = M('sentiment_label')->where(array(
	'token'    => $this->token,
	'pid'      => $id,
	'wecha_id' => $user['wecha_id'],
	'state'    => 1,
	'count'    => array('gt', 0)
	))->order('addtime desc')->limit(8)->select();
		$this->assign('label_list', $label_list);
		$label_help_labels = M('sentiment_label_helps')->where(array('token' => $this->token, 'pid' => $id, 'wecha_id' => $this->wecha_id, 'label_wecha_id' => $user['wecha_id']))->getField('labels');
		$label_help_labels_array = explode(',', $label_help_labels);

		if ($label_help_labels_array[0] != '') {
			$label_help_labels_count = count($label_help_labels_array);
			$this->assign('tolabel_list', $label_help_labels_array);
		}
		else {
			$label_help_labels_count = 0;
		}

		$this->assign('label_help_labels_count', $label_help_labels_count);
		$man_zhi = M('sentiment_record')->where(array('token' => $this->token, 'pid' => $id, 'share_key' => $user['share_key'], 'sex' => 1))->count();
		$woman_zhi = M('sentiment_record')->where(array('token' => $this->token, 'pid' => $id, 'share_key' => $user['share_key'], 'sex' => 2))->count();
		$nosex_zhi = M('sentiment_record')->where(array('token' => $this->token, 'pid' => $id, 'share_key' => $user['share_key'], 'sex' => 0))->count();
		$people_zhi = M('sentiment_record')->where(array('token' => $this->token, 'pid' => $id, 'share_key' => $user['share_key']))->count();
		$this->assign('man_zhi', $man_zhi);
		$this->assign('woman_zhi', $woman_zhi);
		$this->assign('nosex_zhi', $nosex_zhi);
		$this->assign('people_zhi', $people_zhi);

		if ($share_key == '') {
			$this->assign('ta', '我');
		}
		else {
			$this->assign('ta', 'TA');
		}

		if ($_GET['info'] == 1) {
			$this->display('info');
		}
		else if ($_GET['helps'] == 1) {
			$helps_list = M('sentiment_record')->where(array('token' => $this->token, 'pid' => $id, 'share_key' => $user['share_key']))->order('addtime desc')->limit(100)->select();
			$helps_count = M('sentiment_record')->where(array('token' => $this->token, 'pid' => $id, 'share_key' => $user['share_key']))->count();

			foreach ($helps_list as $hk => $hv) {
				$helps_list[$hk]['wechaname'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $hv['wecha_id']))->getField('wechaname');
				$helps_list[$hk]['portrait'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $hv['wecha_id']))->getField('portrait');
				$helps_list[$hk]['sex'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $hv['wecha_id']))->getField('sex');

				if ($helps_list[$hk]['sex'] == 1) {
					if ($user['sex'] == 1) {
						$helps_list[$hk]['help_count'] = $this->Sentiment['same_sex'];
					}
					else if ($user['sex'] == 2) {
						$helps_list[$hk]['help_count'] = $this->Sentiment['opposite_sex'];
					}
					else {
						$helps_list[$hk]['help_count'] = $this->Sentiment['no_sex'];
					}
				}
				else if ($helps_list[$hk]['sex'] == 2) {
					if ($user['sex'] == 1) {
						$helps_list[$hk]['help_count'] = $this->Sentiment['opposite_sex'];
					}
					else if ($user['sex'] == 2) {
						$helps_list[$hk]['help_count'] = $this->Sentiment['same_sex'];
					}
					else {
						$helps_list[$hk]['help_count'] = $this->Sentiment['no_sex'];
					}
				}
				else {
					$helps_list[$hk]['help_count'] = $this->Sentiment['no_sex'];
				}

				$helps_list[$hk]['help_rank'] = $paiming[$hv['wecha_id']];
			}

			$this->assign('helps_count', $helps_count);
			$this->assign('helps_list', $helps_list);
			$this->display('helps');
		}
		else if ($_GET['rank'] == 1) {
			$rank_list = M('Sentiment_user')->where(array('token' => $this->token, 'pid' => $id, 'is_join' => 1))->order('help_count desc,addtime asc')->limit($this->Sentiment['rank_num'])->select();

			foreach ($rank_list as $key => $val) {
				$user_info = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $val['wecha_id']))->find();
				$rank_list[$key]['username'] = $user_info['wechaname'] ? $user_info['wechaname'] : '匿名';
				$rank_list[$key]['tel'] = $user_info['tel'] ? substr_replace($user_info['tel'], '****', 3, 4) : '无';
				$rank_list[$key]['portrait'] = $user_info['portrait'];
				$rank_list[$key]['sex'] = $user_info['sex'];
			}

			$this->assign('rank', $rank_list);
			$this->display('rank');
		}
		else if ($_GET['labels'] == 1) {
			$labels = M('sentiment_label_helps')->where(array('token' => $this->token, 'pid' => $id, 'label_wecha_id' => $user['wecha_id']))->order('addtime desc')->limit(100)->select();
			$labels_count = M('sentiment_label_helps')->where(array('token' => $this->token, 'pid' => $id, 'label_wecha_id' => $user['wecha_id']))->order('addtime desc')->count();

			foreach ($labels as $lk => $lv) {
				$usrinfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $lv['wecha_id']))->find();
				$labels[$lk]['wechaname'] = $usrinfo['wechaname'];
				$labels[$lk]['portrait'] = $usrinfo['portrait'];
				$labels[$lk]['sex'] = $usrinfo['sex'];
				$labels[$lk]['labels'] = explode(',', $lv['labels']);
			}

			$this->assign('labels', $labels);
			$this->assign('labels_count', $labels_count);
			$this->display('labels');
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
				$session_sms = session($_POST['wecha_id'] . 'codeSentiment' . $_POST['token'] . $_POST['id']);
				if ((time() < $session_sms['time']) && ($session_sms['tel'] == $_POST['tel'])) {
					$code = $session_sms['code'];
				}
				else {
					session($_POST['wecha_id'] . 'codeSentiment' . $_POST['token'] . $_POST['id'], NULL);
					$code = rand(100000, 999999);
					$session_sms['tel'] = $_POST['tel'];
					$session_sms['code'] = $code;
					$session_sms['time'] = time() + (60 * 30);
					session($_POST['wecha_id'] . 'codeSentiment' . $_POST['token'] . $_POST['id'], $session_sms);
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
		$session_sms = session($_POST['wecha_id'] . 'codeSentiment' . $_POST['token'] . $_POST['id']);

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

	public function label()
	{
		$_POST['label'] = str_replace(',', '，', $_POST['label']);
		$label_help_labels = M('sentiment_label_helps')->where(array('token' => $_POST['token'], 'pid' => $_POST['id'], 'wecha_id' => $_POST['help_wecha_id'], 'label_wecha_id' => $_POST['label_wecha_id']))->getField('labels');
		$label_help_labels_array = explode(',', $label_help_labels);

		if ($label_help_labels_array[0] != '') {
			$label_help_labels_count = count($label_help_labels_array);
		}
		else {
			$label_help_labels_count = 0;
		}

		if (2 < $label_help_labels_count) {
			$data['error'] = 1;
			$data['msg'] = '亲，最多只能贴3个标签哦~';
		}
		else if (in_array($_POST['label'], $label_help_labels_array)) {
			$data['error'] = 1;
			$data['msg'] = '亲，这个标签你贴过了哦~';
		}
		else if (30 < mb_strlen($_POST['label'], 'utf-8')) {
			$data['error'] = 1;
			$data['msg'] = '亲，不能超过30个字哦~';
		}
		else {
			$data['error'] = 0;
			$label = M('sentiment_label')->where(array('token' => $_POST['token'], 'pid' => $_POST['id'], 'wecha_id' => $_POST['label_wecha_id'], 'label' => $_POST['label']))->find();

			if ($label == '') {
				$add_label['token'] = $_POST['token'];
				$add_label['pid'] = $_POST['id'];
				$add_label['wecha_id'] = $_POST['label_wecha_id'];
				$add_label['label'] = $_POST['label'];
				$add_label['count'] = 1;
				$add_label['state'] = 1;
				$add_label['addtime'] = time();
				$id_label = M('sentiment_label')->add($add_label);
			}
			else {
				$save_label['count'] = $label['count'] + 1;
				$save_label['addtime'] = time();
				$up_label = M('sentiment_label')->where(array('token' => $_POST['token'], 'pid' => $_POST['id'], 'wecha_id' => $_POST['label_wecha_id'], 'label' => $_POST['label']))->save($save_label);
			}

			$label_helps = M('sentiment_label_helps')->where(array('token' => $_POST['token'], 'pid' => $_POST['id'], 'wecha_id' => $_POST['help_wecha_id'], 'label_wecha_id' => $_POST['label_wecha_id']))->find();

			if ($label_helps == '') {
				$add_label_helps['token'] = $_POST['token'];
				$add_label_helps['pid'] = $_POST['id'];
				$add_label_helps['wecha_id'] = $_POST['help_wecha_id'];
				$add_label_helps['label_wecha_id'] = $_POST['label_wecha_id'];
				$add_label_helps['labels'] = $_POST['label'];
				$add_label_helps['addtime'] = time();
				$id_label_helps = M('sentiment_label_helps')->add($add_label_helps);
				$data['count'] = 1;
			}
			else {
				$save_label_helps['labels'] = $label_helps['labels'] . ',' . $_POST['label'];
				$save_label_helps['addtime'] = time();
				$save_labels_array = explode(',', $save_label_helps['labels']);
				$data['count'] = count($save_labels_array);
				$up_label_helps = M('sentiment_label_helps')->where(array('token' => $_POST['token'], 'pid' => $_POST['id'], 'wecha_id' => $_POST['help_wecha_id'], 'label_wecha_id' => $_POST['label_wecha_id']))->save($save_label_helps);
			}
		}

		$data['label'] = $_POST['label'];
		$this->ajaxReturn($data, 'JSON');
	}

	public function getKey($id)
	{
		$str = md5(time() . mt_rand(1000, 9999) . $id);
		return $str;
	}

	public function add_share()
	{
		$now = time();
		$share_key = $this->_get('share_key', 'trim');
		$cookie = cookie('Sentiment_share');
		$cookie_arr = json_decode(json_encode($cookie), true);
		$share = M('Sentiment_user')->where(array('token' => $this->token, 'share_key' => $share_key))->find();
		$mysex = $this->fans['sex'];
		$tasex = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $share['wecha_id']))->getField('sex');
		$record = array('token' => $this->token, 'pid' => $share['pid'], 'share_key' => $share_key, 'addtime' => time(), 'wecha_id' => $this->wecha_id, 'sex' => $mysex);

		if (empty($share)) {
			exit();
		}

		if ($now < $this->Sentiment['start']) {
			echo json_encode(array('err' => 3, 'info' => '活动还没开始'));
			exit();
		}

		if ($this->Sentiment['end'] < $now) {
			echo json_encode(array('err' => 4, 'info' => '活动已结束'));
			exit();
		}

		if ($share['wecha_id'] == $this->wecha_id) {
			exit();
		}

		$is_share = M('Sentiment_record')->where(array('token' => $this->token, 'pid' => $share['pid'], 'wecha_id' => $this->wecha_id, 'share_key' => $share_key))->count();
		if (in_array($share_key, $cookie_arr[$this->Sentiment['id']]) || $is_share) {
			exit();
		}
		else if (M('Sentiment_record')->add($record)) {
			if ($tasex == 1) {
				if ($mysex == 1) {
					$zhi = $this->Sentiment['same_sex'];
				}
				else if ($mysex == 2) {
					$zhi = $this->Sentiment['opposite_sex'];
				}
				else {
					$zhi = $this->Sentiment['no_sex'];
				}
			}
			else if ($tasex == 2) {
				if ($mysex == 1) {
					$zhi = $this->Sentiment['opposite_sex'];
				}
				else if ($mysex == 2) {
					$zhi = $this->Sentiment['same_sex'];
				}
				else {
					$zhi = $this->Sentiment['no_sex'];
				}
			}
			else {
				$zhi = $this->Sentiment['no_sex'];
			}

			M('Sentiment_user')->where(array('token' => $this->token, 'pid' => $this->Sentiment['id'], 'share_key' => $share_key))->setInc('help_count', $zhi);

			if (empty($cookie_arr[$this->Sentiment['id']])) {
				$cookie_arr[$this->Sentiment['id']] = array();
			}

			array_push($cookie_arr[$this->Sentiment['id']], $share_key);
			cookie('Sentiment_share', $cookie_arr, time() + (3600 * 24 * 30));
			echo json_encode(array('err' => 0, 'zhi' => $zhi));
			exit();
		}
	}
}

?>
