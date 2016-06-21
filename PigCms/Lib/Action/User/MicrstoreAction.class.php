<?php

class MicrstoreAction extends UserAction
{
	public $Micrstore_URL;
	public $SALT;

	public function _initialize()
	{
		parent::_initialize();
		$this->canUseFunction('Micrstore');

		if (updateSync::getIfWeidian()) {
			$this->Micrstore_URL = C('weidian_domain') ? C('weidian_domain') : 'http://v.bd001.com';
			$this->SALT = C('encryption') ? C('encryption') : 'pigcms';
		}
		else {
			$this->Micrstore_URL = 'http://v.bd001.com';
			$this->SALT = 'pigcms';
		}
	}

	public function index()
	{
		$set = M('Micrstore_reply')->where(array('token' => $this->token))->find();

		if (IS_POST) {
			if ($set) {
				M('Micrstore_reply')->where(array('token' => $this->token))->save($_POST);
				$this->handleKeyword($set['id'], 'Micrstore', $_POST['keyword']);
				$this->success('保存成功');
			}
			else {
				$_POST['token'] = $this->token;
				$id = M('Micrstore_reply')->add($_POST);
				$this->handleKeyword($id, 'Micrstore', $_POST['keyword']);
				$this->success('保存成功');
			}
		}
		else {
			$get_store_list = $this->get_store_list();
			$this->assign('set', $set);
			$this->assign('get_store_list', $get_store_list);
			$this->display();
		}
	}

	public function api()
	{
		$wxuser = M('Wxuser')->where(array('token' => $this->token))->find();
		$data = array('token' => $this->token, 'site_url' => $this->siteUrl, 'payment_url' => $this->siteUrl . U('Wap/Micrstore/pay'), 'login_url' => $this->siteUrl . U('Wap/Micrstore/login'), 'notify_url' => $this->siteUrl . U('Wap/Micrstore/notify'), 'timestamp' => $wxuser['createtime'], 'wxname' => $wxuser['wxname'], 'server_key' => C('server_key'), 'app_id' => 1);//, 'uniqueID' => updateSync::uniqueID());
		$data = array_map('nulltoblank', $data);
		$sort_data = $data;
		$sort_data['salt'] = $this->SALT;
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		$data['sign_key'] = $sign_key;
		$data['request_time'] = time();
		$url = $this->Micrstore_URL . '/api/oauth.php';
		$return = json_decode($this->curl_post($url, $data), true);

		if ($_GET['dis']) {
			parse_str(parse_url($return['return_url'], PHP_URL_QUERY));
			$store_id = M('Micrstore_reply')->where(array('token' => $this->token))->getField('sid');

			if (!$store_id) {
				$store_list = $this->get_store_list();
				$store_id = intval($store_list[0]['store_id']);
				$store_id = ($store_id ? $store_id : 0);
			}

			$return['return_url'] = $this->Micrstore_URL . '/user.php?c=fx&a=index&token=' . $token . '&sessid=' . $sessid . '&store_id=' . $store_id;
		}

		$this->assign('iframeUrl', $return['return_url']);
		$this->display();
	}

	public function dis()
	{
		$settings = M('Micrstore_reply')->where(array('token' => $this->token))->count();

		if (!$settings) {
			$this->assign('waitSecond', 3);
			$this->success('请先配置微店功能。', U('Micrstore/index'));
			exit();
		}

		if (!$this->get_store_list()) {
			$this->assign('waitSecond', 3);
			$this->success('请先创建店铺。', U('Micrstore/api'));
			exit();
		}

		$this->redirect(U('Micrstore/api', array('dis' => 1)));
	}

	public function withdraw()
	{
		$p = ($this->_get('p', 'intval') ? $this->_get('p', 'intval') : 1);
		$data = array('page_size' => 10, 'p' => $p);
		$return = $this->request_withdraw_port($data);

		if ($return['error_code'] == 0) {
			$insert_data = array();

			foreach ((array) $return['withdrawals'] as $key => $val) {
				$insert_data['imicms_id'] = $val['imicms_id'];
				$insert_data['store_id'] = $val['store_id'];
				$insert_data['opening_bank'] = $val['opening_bank'];
				$insert_data['bank_card'] = $val['bank_card'];
				$insert_data['bank_card_user'] = $val['bank_card_user'];
				$insert_data['withdrawal_type'] = $val['withdrawal_type'];
				$insert_data['add_time'] = $val['add_time'];
				$insert_data['status'] = $val['status'];
				$insert_data['amount'] = $val['amount'];
				$insert_data['complate_time'] = $val['complate_time'];
				$insert_data['bank'] = $val['bank'];
				$insert_data['tel'] = $val['tel'];
				$insert_data['nickname'] = $val['nickname'];
				$insert_data['store'] = $val['store'];
				$insert_data['user'] = $val['user'];
				$exists = M('microsoft_withdraw')->where(array('imicms_id' => $val['imicms_id']))->find();

				if ($exists) {
					$update = M('microsoft_withdraw')->where(array('imicms_id' => $val['imicms_id']))->save($insert_data);
				}
				else {
					$insert_data['token'] = $this->token;
					$insert_data['is_show'] = 1;
					$add = M('microsoft_withdraw')->add($insert_data);
				}
			}
		}

		$page = new Page($return['withdrawal_count'], 10);
		$list = M('microsoft_withdraw')->where(array('token' => $this->token, 'is_show' => 1))->limit($page->firstRow . ',' . $page->listRows)->order('add_time desc')->select();
		$this->assign('list', $list);
		$this->assign('page', $page->show());
		$this->assign('token', $this->token);
		$this->display();
	}

	public function edit_withdraw()
	{
		if (IS_POST) {
			$id = $this->_post('id', 'intval');
			$imicms_id = $this->_post('imicms_id', 'intval');
			$token = $this->_post('token', 'trim');
			$store_id = $this->_post('store_id', 'trim');
			$bak = $this->_post('bak', 'trim');
			if (empty($id) || empty($token)) {
				$this->error('修改失败,缺少参数');
				exit();
			}

			$status = $this->_post('status', 'intval');
			if (empty($status) || empty($imicms_id)) {
				$this->error('状态值或提箱ID不能为空');
				exit();
			}

			$update = array();
			$update['id'] = $imicms_id;
			$update['store_id'] = $store_id;
			$update['status'] = $status;

			if (!empty($bak)) {
				$update['bak'] = $bak;
			}

			$update_return = $this->request_withdraw_port($update);

			if ($update_return['error_code'] == 0) {
				$this->success('修改提现状态成功', U('Micrstore/withdraw'));
				exit();
			}
			else {
				$this->error('修改提现状态失败：' . $update_return['error_msg']);
				exit();
			}
		}

		$id = $this->_get('id', 'intval');
		$token = $this->_get('token', 'trim');
		if (empty($id) || empty($token)) {
			$this->error('非法操作');
			exit();
		}

		$status = S($this->token . '_withdrawal_status');

		if ($status) {
			$withdrawal_status = $status;
		}
		else {
			$update = array('page_size' => 1, 'p' => 1);
			$update_return = $this->request_withdraw_port($update);
			$withdrawal_status = $update_return['withdrawal_status'];
			S($this->token . '_withdrawal_status', $update_return['withdrawal_status'], 3600);
		}

		$this->assign('withdrawal_status', $withdrawal_status);
		$set = M('microsoft_withdraw')->where(array('id' => $id, 'token' => $token))->find();
		$this->assign('set', $set);
		$this->display();
	}

	public function del_withdraw()
	{
		$id = $this->_get('id', 'intval');
		$withdraw_info = M('microsoft_withdraw')->where(array('id' => $id))->find();

		if (empty($withdraw_info)) {
			$this->error('没有找到删除项');
			exit();
		}

		$del = M('microsoft_withdraw')->where(array('id' => $id))->save(array('is_show' => 2));

		if ($del) {
			$this->success('删除成功', U('Micrstore/withdraw'));
		}
		else {
			$this->error('删除失败');
			exit();
		}
	}

	public function request_withdraw_port($data)
	{
		if (empty($data)) {
			return false;
		}

		$request = array();
		$request = $data;
		$request['token'] = $this->token;
		$request['site_url'] = $this->siteUrl;
		$sort_data = $request;
		$sort_data['salt'] = $this->SALT;
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		$request['sign_key'] = $sign_key;
		$request['request_time'] = time();
		$url = $this->Micrstore_URL . '/api/withdrawal.php';
		$result = json_decode($this->curl_post($url, $request), true);
		return $result;
	}

	public function get_store_list()
	{
		$post_data = array('token' => $this->token, 'site_url' => $this->siteUrl, 'login_url' => $this->siteUrl . U('Wap/Micrstore/login'), 'timestamp' => time());
		$sort_data = $post_data;
		$sort_data['salt'] = $this->SALT;
		ksort($sort_data);
		$sign_key = sha1(http_build_query($sort_data));
		$post_data['sign_key'] = $sign_key;
		$post_data['request_time'] = time();
		$url = $this->Micrstore_URL . '/api/store.php';
		$return = json_decode($this->curl_post($url, $post_data), true);

		if ($return['error_code'] == 0) {
			return $return['stores'];
		}
		else {
			return NULL;
		}
	}

	private function curl_post($url, $post)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
}

?>
