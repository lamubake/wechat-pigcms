<?php

class CutpriceAction extends UserAction
{
	public $m_cutprice;

	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction('Cutprice');
		$this->m_cutprice = M('cutprice');
		$this->m_order = M('cutprice_order');
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
		$count = $this->m_cutprice->where($where)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$cutprice_list = $this->m_cutprice->where($where)->order('addtime desc')->limit($page->firstRow . ',' . $page->listRows)->select();
		$this->assign('page', $show);

		foreach ($cutprice_list as $k => $v) {
			$stoptime = (($v['startprice'] - $v['stopprice']) / $v['cutprice']) * $v['cuttime'] * 60;
			$stoptime = ceil($stoptime);
			$cutprice_list[$k]['stoptime'] = $stoptime + $v['starttime'];
			$where_order['token'] = $this->token;
			$where_order['cid'] = $v['imicms_id'];
			$cutprice_list[$k]['paycount'] = $this->m_order->where($where_order)->count();
		}

		$this->assign('cutprice_list', $cutprice_list);
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
			$_POST['starttime'] = strtotime($_POST['starttime']);

			if ($this->m_cutprice->create() != false) {
				if ($id = $this->m_cutprice->add()) {
					$this->handleKeyword($id, 'Cutprice', $_POST['keyword'], 0, 0);
					$this->success('活动创建成功', U('Cutprice/index', array('token' => $this->token)));
				}
				else {
					$this->error('服务器繁忙,请稍候再试');
				}
			}
			else {
				$this->error($this->m_cutprice->getError());
			}
		}
		else {
			$this->error('操作失败');
		}
	}

	public function update()
	{
		$where['token'] = $this->token;
		$where['imicms_id'] = intval($_GET['id']);
		$cutprice = $this->m_cutprice->where($where)->find();
		$this->assign('cutprice', $cutprice);
		$this->display();
	}

	public function doupdate()
	{
		if (IS_POST) {
			$where['imicms_id'] = $_POST['imicms_id'];
			$where['token'] = $this->token;
			$_POST['starttime'] = strtotime($_POST['starttime']);

			if ($this->m_cutprice->create() != false) {
				$update = $this->m_cutprice->where($where)->save();
				$id = $_POST['imicms_id'];
				$this->handleKeyword($id, 'Cutprice', $_POST['keyword'], 0, 0);
				$this->success('活动修改成功', U('Cutprice/index', array('token' => $this->token)));
			}
			else {
				$this->error($this->m_cutprice->getError());
			}
		}
		else {
			$this->error('操作失败');
		}
	}

	public function order()
	{
		$where_order['token'] = $this->token;
		$where_page['token'] = $this->token;

		if ($_GET['id'] != '') {
			$where_order['cid'] = (int) $_GET['id'];
			$where_page['id'] = $_GET['id'];
		}

		if ($_GET['orderid'] != '') {
			$where_order['orderid'] = $_GET['orderid'];
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
			$where['token'] = $this->token;
			$where['imicms_id'] = $v['cid'];
			$cutprice = $this->m_cutprice->where($where)->find();
			$order_list[$k]['cutprice_name'] = $cutprice['name'];
		}

		$this->assign('page', $show);
		$this->assign('order_list', $order_list);
		$this->display();
	}

	public function operate()
	{
		switch ($_GET['type']) {
		case 'del':
			$where['token'] = $this->token;
			$where['imicms_id'] = (int) $_GET['id'];
			$del = $this->m_cutprice->where($where)->delete();
			$this->success('删除成功', U('Cutprice/index', array('token' => $this->token)));
			break;

		case 'qxfahuo':
			$where_order['token'] = $this->token;
			$where_order['imicms_id'] = (int) $_GET['orderid'];
			$save_order['fahuo'] = 0;
			$update_order = $this->m_order->where($where_order)->save($save_order);
			$this->redirect('Cutprice/order', array('token' => $this->token));
			break;

		case 'delorder':
			$where_order['token'] = $this->token;
			$where_order['imicms_id'] = (int) $_GET['orderid'];
			$del_order = $this->m_order->where($where_order)->delete();
			$this->success('删除订单成功', U('Cutprice/order', array('token' => $this->token, 'id' => $_GET['cid'])));
			break;
		}
	}

	public function updateorder()
	{
		$where_order['token'] = $this->token;
		$where_order['imicms_id'] = (int) $_GET['orderid'];
		$order = $this->m_order->where($where_order)->find();
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $order['wecha_id'];
		$userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$order['address'] = $userinfo['address'];
		$this->assign('order', $order);
		$this->display();
	}

	public function doupdateorder()
	{
		$where_order['token'] = $this->token;
		$where_order['imicms_id'] = (int) $_POST['orderid'];
		$order = $this->m_order->where($where_order)->find();
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $order['wecha_id'];
		$save_userinfo['address'] = $_POST['address'];
		$update_userinfo = $this->m_userinfo->where($where_userinfo)->save($save_userinfo);

		if ($_POST['paid'] == 1) {
			$save_order['paid'] = 1;
		}
		else {
			$save_order['paid'] = 0;
			$save_order['fahuo'] = 0;
			$save_order['fahuoid'] = '';
			$save_order['fahuoname'] = '';
		}

		$update_order = $this->m_order->where($where_order)->save($save_order);
		$this->success('修改成功', U('Cutprice/order', array('token' => $this->token, 'id' => $_POST['cid'])));
	}

	public function ajax()
	{
		switch ($_POST['type']) {
		case 'fahuo':
			$where_order['token'] = $_POST['token'];
			$where_order['imicms_id'] = $_POST['orderid'] * 1;
			$save_order['fahuo'] = 1;
			$save_order['fahuoname'] = $_POST['fahuoname'];
			$save_order['fahuoid'] = $_POST['fahuoid'];
			$update_order = $this->m_order->where($where_order)->save($save_order);
			$order = $this->m_order->where($where_order)->find();
			$model = new templateNews();
			$model->sendTempMsg('OPENTM200565259', array('href' => $this->siteUrl . U('Cutprice/myorder', array('token' => $this->token)), 'wecha_id' => $order['wecha_id'], 'first' => '您的订单' . $order['orderid'] . '已发货', 'keyword1' => $order['orderid'], 'keyword2' => $order['fahuoname'], 'keyword3' => $order['fahuoid'], 'remark' => date('Y年m月d日H时i分s秒')));
			$data['error'] = 0;
			$this->ajaxReturn($data, 'JSON');
			break;
		}
	}
}

?>
