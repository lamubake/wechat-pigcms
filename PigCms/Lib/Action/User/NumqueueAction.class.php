<?php

class NumqueueAction extends UserAction
{
	public $token;

	public function __initialize()
	{
		parent::_initialize();
		$this->canUseFunction('Numqueue');
		$this->token = session('token') ? session('token') : session('wp_token');
	}

	public function index()
	{
		$token = $this->_get('token', 'trim');

		if (empty($token)) {
			$this->error('非法操作');
			exit();
		}

		$total = M('numqueue_action')->where(array('token' => $token))->count();
		$Page = new Page($total, 15);
		$list = M('numqueue_action')->where(array('token' => $token))->limit($Page->firstRow . ',' . $Page->listRows)->order('id desc')->select();
		$this->assign('list', $list);
		$this->assign('page', $Page->show());
		$this->display();
	}

	public function add_action()
	{
		if (IS_POST) {
			$reply_keyword = $this->_post('reply_keyword', 'trim');
			$reply_pic = $this->_post('reply_pic', 'trim');
			$reply_title = $this->_post('reply_title', 'trim');
			$reply_content = $this->_post('reply_content', 'trim');
			$icon = $this->_post('icon', 'trim');

			if (empty($reply_keyword)) {
				$this->error('回复关键词不能为空');
				exit();
			}

			if (empty($reply_title)) {
				$this->error('回复标题不能为空');
				exit();
			}

			if (empty($reply_pic)) {
				$this->error('回复图片不能为空');
				exit();
			}

			if (empty($reply_keyword)) {
				$this->error('回复关键词不能为空');
				exit();
			}

			if (empty($icon)) {
				$this->error('图标没上传');
				exit();
			}

			$data = array();
			$data['reply_keyword'] = $reply_keyword;
			$data['reply_pic'] = $reply_pic;
			$data['reply_title'] = $reply_title;
			$data['reply_content'] = $reply_content;
			$data['icon'] = $icon;
			$data['is_hot'] = $this->_post('is_hot', 'intval');
			$data['is_open'] = $this->_post('is_open', 'intval');
			$id = $this->_post('id', 'intval');

			if (empty($id)) {
				$data['token'] = $this->token;
				$add_id = M('numqueue_action')->add($data);

				if ($add_id) {
					$this->handleKeyword($add_id, 'Numqueue', $reply_keyword);
					$this->success('添加成功', U('Numqueue/index', array('token' => $this->token)));
					exit();
				}
				else {
					$this->error('添加失败');
					exit();
				}
			}
			else {
				$exist = M('numqueue_action')->where(array('id' => $id))->find();

				if ($exist) {
					$update = M('numqueue_action')->where(array('id' => $id))->save($data);

					if ($update) {
						$this->handleKeyword($id, 'Numqueue', $this->_post('reply_keyword', 'trim'));
						$this->success('修改成功', U('Numqueue/index', array('token' => $this->token)));
						exit();
					}
					else {
						$this->error('修改失败');
						exit();
					}
				}
				else {
					$this->error('未找到修改项');
					exit();
				}
			}
		}

		$id = $this->_get('id', 'intval');

		if ($id) {
			$action = M('numqueue_action')->where(array('id' => $id))->find();

			if ($action) {
				$this->assign('id', $id);
				$this->assign('vo', $action);
			}
			else {
				$this->error('未获取到修改项');
				exit();
			}
		}

		$this->display();
	}

	public function del_action()
	{
		$id = $this->_get('id', 'intval');
		$exist = M('numqueue_action')->where(array('id' => $id))->find();

		if ($exist) {
			$delete = M('numqueue_action')->where(array('id' => $id))->delete();

			if ($delete) {
				$this->handleKeyword(intval($id), 'Numqueue', '', '', 1);
				$this->success('删除成功', U('Numqueue/index', array('token' => $this->token)));
				exit();
			}
			else {
				$this->error('未获取到删除项');
				exit();
			}
		}
		else {
			$this->error('未获取到删除项');
			exit();
		}
	}

	public function store_list()
	{
		$token = $this->token;
		$action_id = $this->_get('action_id', 'intval');
		if (empty($token) || empty($action_id)) {
			$this->error('非法操作');
			exit();
		}

		$where = array();
		$where['action_id'] = $action_id;
		$where['token'] = $token;
		$name = $this->_get('name', 'name');

		if (!empty($name)) {
			$where['name'] = array('like', '%' . $name . '%');
		}

		$total = M('numqueue_store')->where($where)->count();
		$Page = new Page($total, 15);
		$list = M('numqueue_store')->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->order('rank desc')->select();
		$this->assign('list', $list);
		$this->assign('page', $Page->show());
		$this->assign('action_id', $action_id);
		$this->assign('token', $token);
		$this->display();
	}

	public function receive_list()
	{
		$store_id = $this->_get('store_id', 'intval');
		$token = $this->_get('token', 'trim');
		if (empty($store_id) || empty($token)) {
			$this->error('参数错误');
			exit();
		}

		$where = array();
		$where['store_id'] = $store_id;
		$where['token'] = $token;
		$end_time = (!empty($_GET['end_time']) ? strtotime($_GET['end_time']) : time());

		if ($end_time < strtotime($_GET['start_time'])) {
			$this->error('开始时间不能大于结束时间');
			exit();
		}

		if (!empty($_GET['start_time'])) {
			$where['add_time'] = array(
	'between',
	array(strtotime($_GET['start_time']), $end_time)
	);
		}

		$total = M('numqueue_receive')->where($where)->count();
		$page = new Page($total, 15);
		$receive_list = M('numqueue_receive')->where($where)->limit($page->firstRow . ',' . $page->listRows)->order('add_time desc')->select();
		$this->assign('receive_list', $receive_list);
		$this->assign('page', $page->show());
		$this->display();
	}

	public function over_number()
	{
		$id = $this->_get('id', 'intval');
		$receive = M('numqueue_receive')->where(array('id' => $id))->find();

		if (empty($receive)) {
			$this->error('未获取到你要设置的号单');
			exit();
		}

		if ($receive['status'] == 2) {
			$this->error('该号单已经是过号状态');
			exit();
		}

		$set = M('numqueue_receive')->where(array('id' => $id))->save(array('status' => 2));

		if ($set) {
			$this->success('设置成功', U('Numqueue/receive_list', array('store_id' => $receive['store_id'], 'token' => $receive['token'])));
			exit();
		}
		else {
			$this->error('设置失败');
			exit();
		}

		return false;
	}

	public function del_store()
	{
		$id = $this->_get('id', 'intval');
		$numqueue_store = M('numqueue_store')->where(array('id' => $id))->find();

		if (empty($numqueue_store)) {
			$this->error('未获取到你要删除的门店');
			exit();
		}

		$delete = M('numqueue_store')->where(array('id' => $id))->delete();

		if ($delete) {
			$this->success('删除成功', U('Numqueue/store_list', array('action_id' => $numqueue_store['action_id'], 'token' => $numqueue_store['token'])));
			exit();
		}
		else {
			$this->error('删除失败');
			exit();
		}

		return false;
	}

	public function del_receive()
	{
		$id = $this->_get('id', 'intval');
		$receive = M('numqueue_receive')->where(array('id' => $id))->find();

		if (empty($receive)) {
			$this->error('未获取到你要删除的号单');
			exit();
		}

		$delete = M('numqueue_receive')->where(array('id' => $id))->delete();

		if ($delete) {
			$this->success('删除成功', U('Numqueue/receive_list', array('store_id' => $receive['store_id'], 'token' => $receive['token'])));
			exit();
		}
		else {
			$this->error('删除失败');
			exit();
		}

		return false;
	}

	public function add_store()
	{
		if (IS_POST) {
			$name = $this->_post('name', 'trim');
			$logo = $this->_post('logo', 'trim');
			$store_type = $this->_post('store_type', 'intval');
			$opentime = $this->_post('opentime', 'intval');
			$closetime = $this->_post('closetime', 'intval');
			$remark = $this->_post('remark', 'trim');
			$address = $this->_post('address', 'trim');
			$tel = $this->_post('tel');
			$latitude = $this->_post('latitude');
			$longitude = $this->_post('longitude');
			$jump_name = $this->_post('jump_name', 'trim');
			$hankowthames = $this->_post('hankowthames', 'trim');
			$type_name = trim($_POST['type_name'], ',');
			$type_name = explode(',', $type_name);
			$type_value = trim($_POST['type_value'], ',');
			$type_value = explode(',', $type_value);

			foreach ((array) $type_value as $key => $val) {
				$wait_type[chr($key + 65)] = $val;
			}

			foreach ((array) $type_name as $k => $v) {
				$wait_name[chr($k + 65)] = $v;
			}

			$price = $this->_post('price', 'intval');

			if (30 < strlen($remark)) {
				$this->error('门店说明最多10个汉字,一个汉字3个英文字母');
				exit();
			}

			$status = $this->_post('status', 'intval');
			$rank = $this->_post('rank', 'intval');
			$wait_time = $this->_post('wait_time', 'intval');
			$allow_distance = $this->_post('allow_distance', 'floatval');
			$action_id = $this->_post('action_id', 'intval');
			$id = $this->_post('id', 'intval');

			if (empty($action_id)) {
				$this->error('活动id不能为空');
				exit();
			}

			if (empty($name)) {
				$this->error('门店名称不能为空');
				exit();
			}

			if (empty($logo)) {
				$this->error('logo不能为空');
				exit();
			}

			if (!preg_match('/http|https:\\/\\/[0-9a-z\\.\\/\\-]+\\/[0-9a-z\\.\\/\\-]+\\.([0-9a-z\\.\\/\\-]+)/', $logo)) {
				$this->error('logo地址不正确');
				exit();
			}

			if (strpos($tel, '-') !== false) {
				if (!preg_match('/^(0[0-9]{2,3})-([0-9]{7})/', $tel)) {
					$this->error('电话格式不正确');
					exit();
				}
			}
			else if (!preg_match('/^1([0-9]){10}/', $tel)) {
				$this->error('手机格式不正确');
				exit();
			}

			if (empty($address)) {
				$this->error('地址不能为空');
				exit();
			}

			if (empty($tel)) {
				$this->error('电话不能为空');
				exit();
			}

			if (empty($latitude)) {
				$this->error('纬度不能为空');
				exit();
			}

			if (empty($longitude)) {
				$this->error('经度不能为空');
				exit();
			}

			if ($closetime <= $opentime) {
				$this->error('开始营业时间必须小于结束营业时间');
				exit();
			}

			if (empty($allow_distance)) {
				$this->error('限制最大距离不能为空');
				exit();
			}

			if (!empty($jump_name)) {
				if (empty($hankowthames)) {
					$this->error('当网站名称不为空时,网站链接不能为空');
					exit();
				}
			}

			if (!empty($hankowthames)) {
				if (empty($jump_name)) {
					$this->error('当网站链接不为空时,网站名称不能为空');
					exit();
				}
			}

			$data = array();
			$data['name'] = $name;
			$data['logo'] = $logo;
			$data['store_type'] = $store_type;
			$data['opentime'] = $opentime;
			$data['closetime'] = $closetime;
			$data['latitude'] = $latitude;
			$data['longitude'] = $longitude;
			$data['remark'] = $remark;
			$data['address'] = $address;
			$data['tel'] = $tel;
			$data['jump_name'] = $jump_name;
			$data['hankowthames'] = $hankowthames;
			$data['type_name'] = serialize($wait_name);
			$data['type_value'] = serialize($wait_type);
			$data['price'] = $price;
			$data['privilege_link'] = $this->_post('privilege_link', 'trim');
			$data['status'] = $status;
			$data['wait_time'] = $wait_time;
			$data['allow_distance'] = $allow_distance;
			$data['need_numbers'] = $this->_post('need_numbers', 'intval');
			$data['need_wait'] = $this->_post('need_wait', 'intval');
			$data['action_id'] = $action_id;
			$data['rank'] = $rank;

			if (empty($id)) {
				$data['token'] = $this->token;
				$data['add_time'] = time();
				$add_id = M('numqueue_store')->add($data);

				if ($add_id) {
					$this->success('添加成功', U('Numqueue/store_list', array('action_id' => $action_id)));
					exit();
				}
				else {
					$this->error('添加失败');
					exit();
				}
			}
			else {
				$exist = M('numqueue_store')->where(array('id' => $id))->find();

				if ($exist) {
					$update = M('numqueue_store')->where(array('id' => $id))->save($data);

					if ($update) {
						$this->success('修改成功', U('Numqueue/store_list', array('action_id' => $action_id)));
						exit();
					}
					else {
						$this->error('修改失败');
						exit();
					}
				}
				else {
					$this->error('未找到修改项');
					exit();
				}
			}
		}

		$id = $this->_get('id', 'intval');
		$store_info = M('numqueue_store')->where(array('id' => $id))->find();

		if (!empty($store_info)) {
			$type_name = unserialize($store_info['type_name']);
			$this->assign('type_name', implode(',', array_values($type_name)));
			$type_value = unserialize($store_info['type_value']);
			$this->assign('type_value', implode(',', array_values($type_value)));
			$this->assign('vo', $store_info);
		}

		$action_id = $this->_get('action_id', 'intval');
		$this->assign('action_id', $action_id);
		$this->assign('token', $this->token);
		$this->display();
	}

	public function create_quickmark_1()
	{
		include './PigCms/Lib/ORG/phpqrcode.php';
		$id = $this->_get('id', 'intval');
		$store_id = $this->_get('store_id', 'intval');
		$token = $this->_get('token', 'trim');
		$url = $this->siteUrl . '/index.php?g=Wap&m=Numqueue&a=admin_login&id=' . $id . '&token=' . $token . '&store_id=' . $store_id;
		QRcode::png($url, false, 1, 11);
	}

	public function create_quickmark()
	{
		$id = $this->_get('id', 'intval');
		$store_id = $this->_get('store_id', 'intval');
		$token = $this->_get('token', 'trim');
		$this->assign('id', $id);
		$this->assign('store_id', $store_id);
		$this->assign('token', $token);
		$this->display();
	}
}

?>
