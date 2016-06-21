<?php

class NumqueueAction extends WapAction
{
	public $action_id;
	public $token;
	public $action;
	public $amap;

	public function _initialize()
	{
		parent::_initialize();
		
		$this->action_id = $this->_get('id', 'intval');
		$this->token = $this->_get('token', 'trim');
		$this->action = M('numqueue_action')->where(array('id' => $this->action_id, 'token' => $this->token, 'is_open' => 1))->find();
		$this->assign('isFuwu', $this->isFuwu);
	}

	public function index()
	{
		if (empty($this->action_id) || empty($this->token)) {
			$this->error('非法操作');
			exit();
		}

		if (empty($this->action)) {
			$this->error('未找到该排号或该排号没有开启');
			exit();
		}

		$store_list = M('numqueue_store')->where(array('action_id' => $this->action_id, 'token' => $this->token, 'status' => 1))->order('rank desc')->limit(5)->select();

		foreach ($store_list as $key => $list) {
			$store_list[$key]['receive_count'] = $this->ajax_waits($list['id']);
		}

		$this->over_receive($this->action_id, $this->token);
		$this->assign('action_id', $this->action_id);
		$this->assign('token', $this->token);
		$this->assign($this->action);
		$this->assign('store_list', $store_list);
		$this->display();
	}

	public function hot_store()
	{
		if ($this->action['is_hot'] != 1) {
			$this->error('非法操作');
			exit();
		}

		if (empty($this->action_id) || empty($this->token)) {
			$this->error('非法操作');
			exit();
		}

		$hot_store = M('numqueue_store')->where(array('action_id' => $this->action_id, 'token' => $this->token, 'status' => 1))->order('rank desc')->select();

		if (empty($hot_store)) {
			$this->error('抱歉，没有找到相关店铺', '/index.php?g=Wap&m=Numqueue&a=index&id=' . $this->action_id . '&token=' . $this->token);
		}

		foreach ($hot_store as $key => $list) {
			$hot_store[$key]['receive_count'] = $this->ajax_waits($list['id']);
		}

		$this->assign('store_list', $hot_store);
		$this->assign('action_id', $this->action_id);
		$this->assign($this->action);
		$this->display();
	}

	public function store_list()
	{
		$where = array();
		$where['action_id'] = $this->action_id;
		$where['token'] = $this->token;
		$where['status'] = 1;
		$name = $this->_request('name', 'trim');

		if (!empty($name)) {
			$this->assign('keyword', $name);
			$where['name'] = array('like', '%' . $name . '%');
		}

		$store_list = M('numqueue_store')->where($where)->select();
		if ((count($store_list) == 1) || empty($store_list[1])) {
			header('Location:/index.php?g=Wap&m=Numqueue&a=detail_store&id&store_id=' . $store_list[0]['id'] . '&id=' . $this->action_id . '&token=' . $this->token);
		}
		else {
			$nowlat = ($this->_get('nowlat') ? floatval($this->_get('nowlat', 'trim')) : 0);
			$nowlng = ($this->_get('nowlng') ? floatval($this->_get('nowlng', 'trim')) : 0);
			if ((0 < $nowlat) && (0 < $nowlng)) {
				$temp = array();

				foreach ($store_list as $kk => $vv) {
					$tmpd = $this->getDistance_map($nowlat, $nowlng, $vv['latitude'], $vv['longitude']);
					$tmpdstr = (1000 < $tmpd ? round(floatval($tmpd / 1000), 2) . ' km' : intval($tmpd) . ' m');
					$vv['distance'] = $tmpd;
					$vv['distancestr'] = $tmpdstr;
					$vv['receive_count'] = $this->ajax_waits($vv['id']);
					$store_list[$kk] = $vv;
					$temp[$kk] = $tmpd;
				}

				asort($temp);
				$newlist = array();

				foreach ($temp as $tk => $tv) {
					$newlist[] = $store_list[$tk];
				}

				$store_list = (!empty($newlist) ? $newlist : $store_list);
				$this->assign('is_loaded', true);
			}
		}

		$this->assign($this->action);
		$this->assign('list', $store_list);
		$this->display();
	}

	public function ajax_waits($store_id = '')
	{
		$receive_count = M('numqueue_receive')->where(array('store_id' => $store_id, 'token' => $this->token, 'status' => 1))->count();
		$receive_count = (0 < $receive_count ? $receive_count : 0);
		return $receive_count;
	}

	public function detail_store()
	{
		$store_id = $this->_get('store_id', 'intval');
		$store_info = M('numqueue_store')->where(array('id' => $store_id, 'status' => 1))->find();

		if (empty($store_info)) {
			$this->error('抱歉，没有找到相关店铺', '/index.php?g=Wap&m=Numqueue&a=index&id=' . $this->action_id . '&token=' . $this->token);
			exit();
		}

		$type_name = unserialize($store_info['type_name']);
		$type_value = unserialize($store_info['type_value']);

		foreach ((array) $type_name as $key => $val) {
			$count = M('numqueue_receive')->where(array('store_id' => $store_id, 'queue_type' => $key, 'status' => 1))->count();
			$type_name[$key] = $val . '_' . $count . '_' . $type_value[$key];
		}

		$nowlat = ($this->_get('nowlat') ? floatval($this->_get('nowlat', 'trim')) : 0);
		$nowlng = ($this->_get('nowlng') ? floatval($this->_get('nowlng', 'trim')) : 0);
		if ((0 < $nowlat) && (0 < $nowlng)) {
			$tmpd = $this->getDistance_map($nowlat, $nowlng, $store_info['latitude'], $store_info['longitude']);
			$tmpdstr = (1000 < $tmpd ? round(floatval($tmpd / 1000), 2) . ' km' : intval($tmpd) . ' m');
			$this->assign('distancestr', $tmpdstr);
			$this->assign('distance', $tmpd / 1000);
			$this->assign('is_loaded', true);
		}

		$opentime = mktime((int) $store_info['opentime'], 0, 0, date('m'), date('d'), date('Y'));
		$closetime = mktime((int) $store_info['closetime'], 0, 0, date('m'), date('d'), date('Y'));
		if ((time() < $opentime) || ($closetime < time())) {
			$business = 'close';
		}
		else if ($store_info['need_wait'] == 2) {
			$business = 'not_line';
		}

		$this->assign('type_name', $type_name);
		$this->assign('store_id', $store_id);
		$this->assign($this->action);
		$this->assign('business', $business);
		$this->assign('store_info', $store_info);
		$this->assign('action_name', ACTION_NAME);
		$this->display();
	}

	public function receive_number()
	{
		if (empty($this->wecha_id)) {
			echo 'fail';
			exit();
		}

		$store_id = $this->_post('store_id', 'intval');
		$token = $this->_post('token', 'trim');
		$action_id = $this->_post('action_id', 'intval');
		$numbers = $this->_post('numbers', 'intval');
		$queue_type = $this->_post('queue_type', 'trim');
		$phone = $this->_post('phone');
		if (empty($store_id) || empty($token) || empty($queue_type) || empty($action_id)) {
			echo 'fail';
			exit();
		}

		$this->over_receive('', $token, $store_id);
		$action = M('numqueue_action')->where(array('id' => $action_id, 'token' => $token, 'is_open' => 1))->find();
		$where = array();
		$where['store_id'] = $store_id;
		$where['token'] = $token;
		$where['queue_type'] = $queue_type;
		$where['add_time'] = array('egt', strtotime(date('Y-m-d 00:00:00', time())));
		$total = M('numqueue_receive')->where($where)->count();
		$wait_numbers = M('numqueue_receive')->where(array(
	'store_id'   => $store_id,
	'token'      => $token,
	'queue_type' => $queue_type,
	'status'     => 1,
	'add_time'   => array('egt', strtotime(date('Y-m-d 00:00:00', time())))
	))->count();
		$store_name = M('numqueue_store')->where(array('id' => $store_id))->find();
		$data = array();
		$data['store_id'] = $store_id;
		$data['queue_type'] = $queue_type;
		$data['queue_number'] = $queue_type . ($total + 1);
		$data['numbers'] = $numbers;
		$data['phone'] = $phone;
		$data['wecha_id'] = $this->wecha_id;
		$data['token'] = $token;
		$data['add_time'] = time();
		$data['status'] = 1;
		$add = M('numqueue_receive')->add($data);

		if ($add) {
			if ($wait_numbers == 0) {
				$remark = '排队成功,若过号请重排。';
			}
			else {
				$remark = '排队成功,请耐心等待,若过号请重排。';
			}

			$model = new templateNews();
			$model->sendTempMsg('OPENTM200869995', array('href' => $this->siteUrl . U('Numqueue/recevice_detial', array('store_id' => $store_id, 'wecha_id' => $this->wecha_id, 'id' => $action_id, 'token' => $token)), 'wecha_id' => $this->wecha_id, 'first' => $store_name['name'] . '门店的排队提醒通知', 'keyword1' => $data['queue_number'], 'keyword2' => date('H:i', $data['add_time']), 'keyword3' => ($wait_numbers * $store_name['wait_time']) . '分钟', 'keyword4' => $wait_numbers . '人', 'remark' => $remark));
			echo 'done';
			exit();
		}
		else {
			echo 'fail';
			exit();
		}
	}

	public function recevice_detial()
	{
		$store_id = $this->_get('store_id', 'intval');
		$store_info = M('numqueue_store')->where(array('id' => $store_id, 'token' => $this->token))->find();
		$recevice_detial = M('numqueue_receive')->where(array('store_id' => $store_id, 'token' => $this->token, 'wecha_id' => $this->wecha_id))->order('add_time desc')->limit(1)->find();
		$wait_num = M('numqueue_receive')->where(array(
	'store_id'   => $store_id,
	'token'      => $this->token,
	'queue_type' => $recevice_detial['queue_type'],
	'add_time'   => array('lt', $recevice_detial['add_time']),
	'status'     => 1
	))->count();
		$this->assign('recevice_detial', $recevice_detial);
		$this->assign('store_info', $store_info);
		$this->assign('wait_num', $wait_num);
		$this->assign($this->action);
		$this->display();
	}

	public function number_list()
	{
		$action_id = $this->_get('id', 'intval');
		$token = $this->_get('token', 'trim');
		$store_id = $this->_get('store_id', 'intval');
		if (empty($action_id) && empty($token)) {
			$this->error('参数错误');
			exit();
		}

		$where = array();
		$where['action_id'] = $action_id;
		$where['token'] = $token;

		if (!empty($store_id)) {
			$where['id'] = $store_id;
		}

		$store = M('numqueue_store')->where($where)->field('id,name,wait_time')->select();
		$number_list = array();
		$i = 0;

		foreach ($store as $val) {
			$numqueue_receive = M('numqueue_receive')->where(array('store_id' => $val['id'], 'token' => $token, 'wecha_id' => $this->wecha_id))->select();

			if (!empty($numqueue_receive)) {
				foreach ($numqueue_receive as $k => $v) {
					if ($v['status'] == 1) {
						$wait_num = M('numqueue_receive')->where(array(
	'store_id'   => $val['id'],
	'token'      => $token,
	'queue_type' => $v['queue_type'],
	'status'     => 1,
	'add_time'   => array('lt', $v['add_time'])
	))->count();
						$numqueue_receive[$k]['wait_num'] = $wait_num;
					}
					else {
						$numqueue_receive[$k]['wait_num'] = 0;
					}
				}

				$number_list[$i]['receive'] = $numqueue_receive;
				$number_list[$i]['store_name'] = $val['name'];
				$number_list[$i]['wait_time'] = $val['wait_time'];
				$number_list[$i]['store_id'] = $val['id'];
				$i++;
			}
		}

		$this->assign($this->action);
		$this->assign('number_list', $number_list);
		$this->display();
	}

	public function del_receive()
	{
		$id = $this->_get('id', 'intval');
		$store_id = $this->_get('store_id', 'intval');
		$action_id = $this->_get('action_id', 'intval');
		$token = $this->_get('token', 'trim');
		$type = $this->_get('type', 'trim');
		$receive_info = M('numqueue_receive')->where(array('id' => $id, 'status' => 1))->find();

		if (empty($receive_info)) {
			$this->error('没有找到你的号单');
			exit();
		}

		if (empty($action_id) || empty($token) || empty($store_id)) {
			$this->error('参数缺失,取消排队失败');
			exit();
		}

		$update = M('numqueue_receive')->where(array('id' => $id))->save(array('status' => 3));

		if ($update) {
			if (!empty($type) && ($type == 'number_list')) {
				$this->success('取消排队成功', U('Numqueue/number_list', array('id' => $action_id, 'token' => $token)));
				exit();
			}
			else {
				$this->success('取消排队成功', U('Numqueue/detail_store', array('store_id' => $store_id, 'id' => $action_id, 'token' => $token)));
				exit();
			}
		}
		else {
			$this->error('取消排队失败');
			exit();
		}
	}

	public function clear_receive()
	{
		$store_id = $this->_get('store_id', 'intval');
		$token = $this->_get('token', 'trim');
		if (!$store_id && !$token) {
			exit('参数错误');
		}

		$where = array(
			'store_id' => $store_id,
			'token'    => $token,
			'wecha_id' => $this->wecha_id,
			'status'   => array('neq', 1)
			);
		$receive = M('numqueue_receive')->where($where)->count();

		if (0 < $receive) {
			$delete = M('numqueue_receive')->where($where)->delete();

			if ($delete) {
				exit('done');
			}
			else {
				exit('fail');
			}
		}
		else {
			exit('没有过号号单');
		}
	}

	public function check_receive()
	{
		$store_id = $this->_get('store_id', 'intval');
		$token = $this->_get('token', 'trim');
		if (!$store_id && !$token) {
			exit('参数错误');
		}

		$where = array(
			'store_id' => $store_id,
			'token'    => $token,
			'wecha_id' => $this->wecha_id,
			'status'   => array('neq', 1)
			);
		$receive = M('numqueue_receive')->where($where)->count();

		if (0 < $receive) {
			exit('done');
		}
		else {
			exit('没有过号号单');
		}
	}

	public function check_waiting()
	{
		$store_id = $this->_get('store_id', 'intval');
		$exists = M('numqueue_receive')->where(array('store_id' => $store_id, 'token' => $this->token, 'wecha_id' => $this->wecha_id, 'status' => 1))->find();

		if ($exists) {
			echo 1;
			exit();
		}
		else {
			echo 0;
			exit();
		}
	}

	public function store_info()
	{
		$store_id = $this->_get('store_id', 'intval');
		$action_name = $this->_get('action_name', 'trim');
		$store_info = M('numqueue_store')->where(array('id' => $store_id))->find();
		$my_receive = M('numqueue_receive')->where(array('store_id' => $store_id, 'wecha_id' => $this->wecha_id))->order('add_time desc')->limit(1)->find();

		if (30 < strlen($store_info['privilege'])) {
			$this->assign('arrow', 'up');
		}

		if (!empty($store_info['hankowthames'])) {
			if ((strpos($store_info['hankowthames'], '{siteUrl}') !== false) || (strpos($store_info['hankowthames'], '{wechat_id}') !== false)) {
				$hankowthames = str_replace(array('{siteUrl}', '{wechat_id}'), array($this->siteUrl, $this->wecha_id), $store_info['hankowthames']);
				$store_info['hankowthames'] = htmlspecialchars_decode($hankowthames);
			}
		}

		if (!empty($store_info['privilege_link'])) {
			if ((strpos($store_info['privilege_link'], '{siteUrl}') !== false) || (strpos($store_info['privilege_link'], '{wechat_id}') !== false)) {
				$privilege_link = str_replace(array('{siteUrl}', '{wechat_id}'), array($this->siteUrl, $this->wecha_id), $store_info['privilege_link']);
				$store_info['privilege_link'] = htmlspecialchars_decode($privilege_link);
			}
		}

		if ($_GET['id'] && $_GET['token']) {
			$store_count = M('numqueue_store')->where(array('action_id' => $_GET['id'], 'token' => $_GET['token'], 'status' => 1))->count();
			$this->assign('store_count', $store_count);
		}
		else {
			$this->assign('store_count', 0);
		}

		$this->assign('store_info', $store_info);
		$this->assign('my_receive', $my_receive);
		$this->assign('action_name', $action_name);
		$this->display();
	}

	public function admin_login()
	{
		if (IS_POST) {
			$store_id = $this->_post('store_id', 'intval');

			if (empty($store_id)) {
				$this->error('门店ID不能为空');
				exit();
			}

			$login_parameters = array();
			$login_parameters['token'] = $_POST['token'];
			$login_parameters['username'] = $_POST['username'];
			$login_parameters['password'] = $_POST['password'];
			$login_parameters['modelName'] = 'Numqueue';
			$CheckStaff = new CheckStaff($login_parameters);
			$login_status = json_decode($CheckStaff->check_login(), true);
			if (($login_status['error_code'] == 0) && ($login_status['error_msg'] == 'success')) {
				M('company_staff')->where(array('token' => $_POST['token'], 'username' => $_POST['username'], 'password' => $_POST['password']))->save(array('wecha_id' => $this->wecha_id));
				$exists = M('numqueue_admin')->where(array('token' => $_POST['token'], 'store_id' => $store_id))->find();

				if ($exists) {
					M('numqueue_admin')->where(array('token' => $_POST['token'], 'store_id' => $store_id))->save(array('password' => $_POST['password'], 'wecha_id' => $this->wecha_id));
				}
				else {
					$data = array('password' => $_POST['password'], 'token' => $_POST['token'], 'wecha_id' => $this->wecha_id, 'store_id' => $store_id);
					M('numqueue_admin')->add($data);
				}

				header('Location:/index.php?g=Wap&m=Numqueue&a=admin_manage&store_id=' . $store_id . '&token=' . $_POST['token'] . '&id=' . $_POST['action_id']);
			}
			else {
				$this->error($login_status['error_msg']);
			}
		}

		if ($this->wecha_id) {
			$login_parameters = array();
			$login_parameters['token'] = $this->token;
			$login_parameters['wecha_id'] = $this->wecha_id;
			$login_parameters['modelName'] = 'Numqueue';
			$CheckStaff = new CheckStaff($login_parameters);
			$login_status = json_decode($CheckStaff->check_login(), true);
			$exists = M('numqueue_admin')->where(array('token' => $this->token, 'store_id' => $_GET['store_id'], 'wecha_id' => $this->wecha_id))->find();
			if (($login_status['error_code'] == 0) && ($login_status['error_msg'] == 'success') && $exists) {
				header('Location:/index.php?g=Wap&m=Numqueue&a=admin_manage_list&wecha_id=' . $this->wecha_id . '&token=' . $this->token . '&id=' . $_GET['id']);
			}
		}

		$this->display();
	}

	public function admin_manage_list()
	{
		$id = $this->_get('id', 'intval');
		$token = $this->_get('token', 'trim');
		$wecha_id = $this->_get('wecha_id', 'trim');
		if (empty($token) && empty($wecha_id)) {
			$this->error('参数错误');
		}

		$store_list = array();
		$where = array();
		$where['wecha_id'] = $wecha_id;
		$where['token'] = $token;
		$store_id = M('numqueue_admin')->where($where)->field('store_id')->select();

		foreach ($store_id as $val) {
			$res = M('numqueue_store')->where(array('id' => $val['store_id']))->find();

			if (!empty($res)) {
				$store_list[] = $res;
			}
		}

		if (count($store_list) == 1) {
			header('Location:/index.php?g=Wap&m=Numqueue&a=admin_manage&store_id=' . $store_list[0]['id'] . '&token=' . $token . '&id=' . $id);
			exit();
		}

		$this->assign('store_list', $store_list);
		$this->assign($this->action);
		$this->display();
	}

	public function admin_manage()
	{
		$store_id = $this->_get('store_id', 'intval');
		$token = $this->_get('token', 'trim');
		$action_id = $this->_get('id', 'intval');
		$store_info = M('numqueue_store')->where(array('id' => $store_id))->find();

		if (empty($store_info)) {
			$this->error('抱歉，没有找到相关店铺');
			exit();
		}

		$opentime = mktime((int) $store_info['opentime'], 0, 0, date('m'), date('d'), date('Y'));
		$closetime = mktime((int) $store_info['closetime'], 0, 0, date('m'), date('d'), date('Y'));
		if ((time() < $opentime) || ($closetime < time())) {
			$this->assign('business', 'close');
		}

		$this->over_receive('', $token, $store_id);
		$type_name = unserialize($store_info['type_name']);
		$type_value = unserialize($store_info['type_value']);

		foreach ((array) $type_name as $key => $val) {
			$first_number = M('numqueue_receive')->where(array('store_id' => $store_id, 'queue_type' => $key, 'status' => 1))->order('add_time asc')->limit(1)->getField('queue_number');
			$count = M('numqueue_receive')->where(array('store_id' => $store_id, 'queue_type' => $key, 'status' => 1))->count();
			$type_name[$key] = $val . '_' . $count . '_' . $type_value[$key] . '_' . $first_number;
		}

		$this->assign('type_name', $type_name);
		$this->assign('store_id', $store_id);
		$this->assign('token', $token);
		$this->assign('action_id', $action_id);
		$this->assign('store_info', $store_info);
		$this->assign('action_name', ACTION_NAME);
		$this->display();
	}

	public function reduce_recevice()
	{
		$type = $this->_post('type', 'trim');
		$store_id = $this->_post('store_id', 'intval');

		if ($store_id == '') {
			echo json_encode(array('stat' => 'FAIL', 'data' => ''));
		}

		$store_list = M('numqueue_receive')->where(array('store_id' => $store_id, 'queue_type' => $type, 'status' => 1))->order('add_time asc')->select();
		$update = M('numqueue_receive')->where(array('id' => $store_list[0]['id']))->save(array('status' => 2));
		array_shift($store_list);
		$next_number = $store_list[0]['queue_number'];
		if (!empty($store_list[0]) && !empty($store_list[0]['wecha_id'])) {
			$receive_info = $store_list[0];
			$store_name = M('numqueue_store')->where(array('id' => $receive_info['store_id']))->find();
			$model = new templateNews();
			$model->sendTempMsg('OPENTM200869995', array('href' => $this->siteUrl . U('Numqueue/recevice_detial', array('store_id' => $receive_info['store_id'], 'wecha_id' => $receive_info['wecha_id'], 'id' => $store_name['action_id'], 'token' => $receive_info['token'])), 'wecha_id' => $receive_info['wecha_id'], 'first' => $store_name['name'] . '门店的到号提醒通知', 'keyword1' => $receive_info['queue_number'], 'keyword2' => date('H:i', $receive_info['add_time']), 'keyword3' => '0分钟', 'keyword4' => '0人', 'remark' => '您的排号已经到号,请尽快前来光顾吧！'));
		}

		echo json_encode(array('stat' => 'DONE', 'data' => $next_number));
	}

	public function add_recevice()
	{
		$queue_type = $this->_post('type', 'trim');
		$token = $this->_post('token', 'trim');
		$store_id = $this->_post('store_id', 'intval');
		if (empty($queue_type) || empty($token) || empty($store_id)) {
			echo json_encode(array('stat' => 'FAIL', 'data' => ''));
			exit();
		}

		$store_info = M('numqueue_store')->where(array('id' => $store_id))->find();
		$opentime = mktime((int) $store_info['opentime'], 0, 0, date('m'), date('d'), date('Y'));
		$closetime = mktime((int) $store_info['closetime'], 0, 0, date('m'), date('d'), date('Y'));
		if ((time() < $opentime) || ($closetime < time())) {
			echo json_encode(array('stat' => 'FAIL', 'data' => '营业时间未到'));
			exit();
		}

		$where = array();
		$where['store_id'] = $store_id;
		$where['token'] = $token;
		$where['queue_type'] = $queue_type;
		$where['add_time'] = array('egt', strtotime(date('Y-m-d 00:00:00', time())));
		$total = M('numqueue_receive')->where($where)->count();
		$data = array();
		$data['store_id'] = $store_id;
		$data['queue_type'] = $queue_type;
		$data['queue_number'] = $queue_type . ($total + 1);
		$data['token'] = $token;
		$data['add_time'] = time();
		$data['status'] = 1;
		$add = M('numqueue_receive')->add($data);

		if ($add) {
			$wait_type = unserialize($store_info['type_name']);
			$msg = '';
			$msg .= chr(10) . '餐厅名称:' . $store_info['name'];
			$msg .= chr(10) . '排号类型:' . $wait_type[$queue_type];
			$msg .= chr(10) . '等待位数:' . $total;
			$msg .= chr(10) . '排队号码:' . $data['queue_number'];
			$msg .= chr(10) . '取号时间:' . date('YmdHis');
			$msg .= chr(10) . '联系电话:' . $store_info['tel'];
			$msg .= chr(10) . '*******************************';
			$msg .= chr(10) . '谢谢惠顾，欢迎下次光临!';
			$op = new orderPrint();
			$op->printit($token, 0, 'Numqueue', $msg, 0);
			$first_number = M('numqueue_receive')->where(array('store_id' => $store_id, 'queue_type' => $queue_type, 'status' => 1))->order('add_time asc')->limit(1)->getField('queue_number');
			echo json_encode(array('stat' => 'DONE', 'data' => $first_number));
			exit();
		}
		else {
			echo json_encode(array('stat' => 'FAIL', 'data' => '增加失败'));
			exit();
		}
	}

	private function over_receive($action_id = '', $token = '', $store_id = '')
	{
		if (!empty($store_id) && !empty($token)) {
			$store = M('numqueue_store')->where(array('id' => $store_id))->find();

			if (!empty($store)) {
				$condition = array();
				$condition['store_id'] = $store_id;
				$condition['token'] = $token;

				if (($store['closetime'] - $store['opentime']) == 23) {
					$condition['add_time'] = array('lt', strtotime(date('Y-m-d 00:00:00', time())));
				}
				else {
					$overtime = mktime($store['opentime'], 0, 0, date('m'), date('d'), date('Y'));
					$condition['add_time'] = array('lt', $opentime);
				}

				M('numqueue_receive')->where($condition)->save(array('status' => 2));
			}

			return false;
		}

		if ($action_id && $token) {
			$store_list = M('numqueue_store')->where(array('action_id' => $action_id, 'token' => $token))->field()->select();
			$condition = array();

			if (!empty($store_list)) {
				foreach ($store_list as $k => $v) {
					$condition['store_id'] = $v['id'];
					$condition['token'] = $token;

					if (($v['closetime'] - $v['opentime']) == 23) {
						$condition['add_time'] = array('lt', strtotime(date('Y-m-d 00:00:00', time())));
					}
					else {
						$overtime = mktime($v['opentime'], 0, 0, date('m'), date('d'), date('Y'));
						$condition['add_time'] = array('lt', $overtime);
					}

					M('numqueue_receive')->where($condition)->save(array('status' => 2));
				}
			}

			return true;
		}
		else {
			return false;
		}
	}

	public function map()
	{
		if (C('baidu_map')) {
			$this->isamap = 0;
		}
		else {
			$this->isamap = 1;
			$this->amap = new amap();
		}

		$lng = $this->_get('lng');
		$lat = $this->_get('lat');
		$this->assign('lng', $lng);
		$this->assign('lat', $lat);
		$this->display();
	}

	private function getDistance_map($lat_a, $lng_a, $lat_b, $lng_b)
	{
		$R = 6377830;
		$pk = doubleval(180 / 3.1415926000000001);
		$a1 = doubleval($lat_a / $pk);
		$a2 = doubleval($lng_a / $pk);
		$b1 = doubleval($lat_b / $pk);
		$b2 = doubleval($lng_b / $pk);
		$t1 = doubleval(cos($a1) * cos($a2) * cos($b1) * cos($b2));
		$t2 = doubleval(cos($a1) * sin($a2) * cos($b1) * sin($b2));
		$t3 = doubleval(sin($a1) * sin($b1));
		$tt = doubleval(acos($t1 + $t2 + $t3));
		return round($R * $tt);
	}
}

?>
