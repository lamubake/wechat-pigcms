<?php

class BargainAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		$this->canUseFunction('Bargain');
		$this->m_bargain = M('bargain');
		$this->m_order = M('bargain_order');
		$this->m_kanuser = M('bargain_kanuser');
		$this->m_userinfo = M('userinfo');
	}

	public function index()
	{
		$where['token'] = $this->token;
		$where_page['token'] = $this->token;

		if (!empty($_GET['name'])) {
			$where['name'] = array('like', '%' . $_GET['name'] . '%');
			$where_page['name'] = $_GET['name'];
		}

		import('ORG.Util.Page');
		$count = $this->m_bargain->where($where)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$bargain_list = $this->m_bargain->where($where)->order('addtime desc')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($bargain_list as $k => $v) {
			$where_kanuser['token'] = $this->token;
			$where_kanuser['bargain_id'] = $v['imicms_id'];
			$count_canyu = $this->m_kanuser->where($where_kanuser)->count();
			$where_order['token'] = $this->token;
			$where_order['paid'] = 1;
			$where_order['bargain_id'] = $v['imicms_id'];
			$count_pay = $this->m_order->where($where_order)->count();
			$bargain_list[$k]['count_canyu'] = $count_canyu;
			$bargain_list[$k]['count_pay'] = $count_pay;
		}

		$this->assign('page', $show);
		$this->assign('bargain_list', $bargain_list);
		$this->display();
	}

	public function add()
	{
		$this->display();
	}

	public function doadd()
	{
		if (IS_POST) {
			$_POST['token'] = $this->token;
			$_POST['addtime'] = time();

			if ($this->m_bargain->create() != false) {
				if ($id = $this->m_bargain->add()) {
					$this->handleKeyword($id, 'Bargain', $_POST['keyword'], 0, 0);
					$this->success('活动创建成功', U('Bargain/index', array('token' => $this->token)));
				}
				else {
					$this->error('服务器繁忙,请稍候再试');
				}
			}
			else {
				$this->error($this->m_bargain->getError());
			}
		}
		else {
			$this->error('操作失败');
		}
	}

	public function update()
	{
		$where['token'] = $this->token;
		$where['imicms_id'] = (int) $_GET['id'];
		$bargain = $this->m_bargain->where($where)->find();
		$this->assign('bargain', $bargain);
		$where_order['token'] = $this->token;
		$where_order['bargain_id'] = (int) $_GET['id'];
		$count_order = $this->m_order->where($where_order)->count();
		$this->assign('count_order', $count_order);
		$this->display();
	}

	public function doupdate()
	{
		if (IS_POST) {
			$where['imicms_id'] = $_POST['imicms_id'];
			$where['token'] = $this->token;

			if ($this->m_bargain->create() != false) {
				$update = $this->m_bargain->where($where)->save();
				$id = $_POST['imicms_id'];
				$this->handleKeyword($id, 'Bargain', $_POST['keyword'], 0, 0);
				S($_POST['imicms_id'] . 'bargain' . $this->token, NULL);
				$this->success('活动修改成功', U('Bargain/index', array('token' => $this->token)));
			}
			else {
				$this->error($this->m_bargain->getError());
			}
		}
		else {
			$this->error('操作失败');
		}
	}

	public function order()
	{
		$where_order['token'] = $this->token;
		$where_order['paid'] = 1;
		$where_page['token'] = $this->token;

		if ($_GET['id'] != '') {
			$where_order['bargain_id'] = (int) $_GET['id'];
			$where_page['id'] = $_GET['id'];
		}

		if ($_GET['orderid'] != '') {
			$where_order['imicms_id'] = $_GET['orderid'] - 10000000;
			$where_page['orderid'] = $_GET['orderid'];
		}

		import('ORG.Util.Page');
		$count = $this->m_order->where($where_order)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$order_list = $this->m_order->where($where_order)->order('addtime desc')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($order_list as $k => $v) {
			$where_userinfo['token'] = $this->token;
			$where_userinfo['wecha_id'] = $v['wecha_id'];
			$userinfo = $this->m_userinfo->where($where_userinfo)->find();
			$order_list[$k]['wechaname'] = $userinfo['wechaname'];
			$order_list[$k]['tel'] = $userinfo['tel'];
			$order_list[$k]['address'] = $userinfo['address'];
		}

		$this->assign('page', $show);
		$this->assign('order_list', $order_list);
		$this->display();
	}

	public function kanuser()
	{
		$where_kanuser['token'] = $this->token;
		$where_page['token'] = $this->token;
		$where_kanuser['orderid'] = (int) $_GET['orderid'];
		$where_page['orderid'] = $_GET['orderid'];
		import('ORG.Util.Page');
		$count = $this->m_kanuser->where($where_kanuser)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$kanuser_list = $this->m_kanuser->where($where_kanuser)->order('addtime')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($kanuser_list as $k => $v) {
			$where_userinfo2['token'] = $this->token;
			$where_userinfo2['wecha_id'] = $v['friend'];
			$userinfo2 = $this->m_userinfo->where($where_userinfo2)->find();
			$kanuser_list[$k]['wechaname'] = $userinfo2['wechaname'];
		}

		$this->assign('page', $show);
		$this->assign('kanuser_list', $kanuser_list);
		$where_order['token'] = $this->token;
		$where_order['imicms_id'] = (int) $_GET['orderid'];
		$order = $this->m_order->where($where_order)->find();
		$where['token'] = $this->token;
		$where['imicms_id'] = $order['bargain_id'];
		$bargain = $this->m_bargain->where($where)->find();
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $order['wecha_id'];
		$userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$this->assign('bargain', $bargain);
		$this->assign('userinfo', $userinfo);
		$this->display();
	}

	public function ajax()
	{
		switch ($_POST['type']) {
		case 'state':
			$where['token'] = $_POST['token'];
			$where['imicms_id'] = (int) $_POST['id'];
			$state = $this->m_bargain->where($where)->getField('state');

			if ($state == 1) {
				$save['state'] = 0;
				$data['error'] = 0;
			}
			else {
				$save['state'] = 1;
				$data['error'] = 1;
			}

			$update = $this->m_bargain->where($where)->save($save);
			S($_POST['id'] . 'bargain' . $this->token, NULL);
			$this->ajaxReturn($data, 'JSON');
			break;
		}
	}

	public function operate()
	{
		switch ($_GET['type']) {
		case 'del':
			$where['token'] = $this->token;
			$where['imicms_id'] = (int) $_GET['id'];
			$del = $this->m_bargain->where($where)->delete();
			$this->success('删除成功', U('Bargain/index', array('token' => $this->token)));
			break;

		case 'fahuo':
			$where_order['token'] = $this->token;
			$where_order['imicms_id'] = (int) $_GET['orderid'];
			$state = $this->m_order->where($where_order)->getField('state');

			if ($state == 0) {
				$save_order['state'] = 1;
			}
			else {
				$save_order['state'] = 0;
			}

			$update_order = $this->m_order->where($where_order)->save($save_order);

			if ($state == 0) {
				$order = $this->m_order->where($where_order)->find();
				$orderid = $_GET['orderid'] + 10000000;
				$model = new templateNews();
				$model->sendTempMsg('OPENTM200565259', array('href' => $this->siteUrl . U('Wap/Bargain/myorder', array('token' => $this->token)), 'wecha_id' => $order['wecha_id'], 'first' => '您的订单' . $orderid . '已发货', 'keyword1' => $orderid, 'keyword2' => '无', 'keyword3' => '无', 'remark' => date('Y年m月d日H时i分s秒')));
			}

			$this->redirect('Bargain/order', array('token' => $this->token));
			break;
		}
	}
}

?>
