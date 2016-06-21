<?php

class BargainAction extends WapAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->m_bargain = M('bargain');
		$this->m_order = M('bargain_order');
		$this->m_kanuser = M('bargain_kanuser');
		$this->m_userinfo = M('userinfo');
	}

	public function gzhurl()
	{
		$gzhurl = M('Home')->where(array('token' => $this->token))->getField('gzhurl');

		if ($gzhurl == NULL) {
			$this->show('<h1>未设置关注链接</h1>');
		}
		else {
			$this->show('<script>window.location.href=\'' . $gzhurl . '\'</script>');
		}
	}

	public function home()
	{
		$where['token'] = $this->token;

		if ($_POST['name'] != '') {
			$where['name'] = array('like', '%' . $_POST['name'] . '%');
		}

		if (($_GET['type'] == '') || ($_GET['type'] == '0')) {
			$bargain_list = $this->m_bargain->where($where)->select();
		}
		else if ($_GET['type'] == '1') {
			$bargain_list = $this->m_bargain->where($where)->order('addtime desc')->select();
		}
		else if ($_GET['type'] == '2') {
			$bargain_list = $this->m_bargain->where($where)->order('pv desc')->select();
		}

		foreach ($bargain_list as $k => $v) {
			$where_order_paynum['token'] = $this->token;
			$where_order_paynum['bargain_id'] = $v['imicms_id'];
			$where_order_paynum['paid'] = 1;
			$bargain_list[$k]['paynum'] = $this->m_order->where($where_order_paynum)->count();
		}

		$this->assign('bargain_list', $bargain_list);
		$this->display();
	}

	public function index()
	{
		$where['token'] = $this->token;
		$where['imicms_id'] = (int) $_GET['id'];
		$where['state'] = 1;
		$bargain = S($_GET['id'] . 'bargain' . $this->token);

		if ($bargain == '') {
			$bargain = $this->m_bargain->where($where)->find();

			if ($bargain == '') {
				$this->error('没有这个活动');
			}

			$save['pv'] = $bargain['pv'] + 1;
			$update = $this->m_bargain->where($where)->save($save);
			$bargain['pv'] = $bargain['pv'] + 1;
			S($_GET['id'] . 'bargain' . $this->token, $bargain);
		}
		else {
			$save['pv'] = $bargain['pv'] + 1;
			$update = $this->m_bargain->where($where)->save($save);
			$bargain['pv'] = $bargain['pv'] + 1;
		}

		if ($bargain['state'] == 0) {
			$this->error('没有这个活动');
		}

		$bargain['logourl1'] = $this->getLink($bargain['logourl1']);

		if ($bargain['logourl2'] != '') {
			$bargain['logourl2'] = $this->getLink($bargain['logourl2']);
		}

		if ($bargain['logourl3'] != '') {
			$bargain['logourl3'] = $this->getLink($bargain['logourl3']);
		}

		$this->assign('bargain', $bargain);
		$where_order['token'] = $this->token;
		$where_order['wecha_id'] = $this->wecha_id;
		$where_order['bargain_id'] = (int) $_GET['id'];
		$order = $this->m_order->where($where_order)->find();

		if ($order == '') {
			$type = 'noorder';
		}

		$this->assign('type', $type);
		$where_order_paynum['token'] = $this->token;
		$where_order_paynum['bargain_id'] = (int) $_GET['id'];
		$where_order_paynum['paid'] = 1;
		$paynum = $this->m_order->where($where_order_paynum)->count();
		$this->assign('paynum', $paynum);
		$this->display();
	}

	public function dao()
	{
		$where['token'] = $this->token;
		$where['imicms_id'] = (int) $_GET['id'];
		$where['state'] = 1;
		$bargain = S($_GET['id'] . 'bargain' . $this->token);

		if ($bargain == '') {
			$bargain = $this->m_bargain->where($where)->find();

			if ($bargain == '') {
				$this->error('没有这个活动');
			}

			S($_GET['id'] . 'bargain' . $this->token, $bargain);
		}

		if ($bargain['state'] == 0) {
			$this->error('没有这个活动');
		}

		$myorder = $this->m_order->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bargain_id' => (int) $_GET['id'], 'paid' => 1))->find();
		if (($bargain['inventory'] < 1) && ($myorder == '')) {
			$this->error('此商品都被抢完了！');
		}

		$bargain['logourl1'] = $this->getLink($bargain['logourl1']);

		if ($bargain['logourl2'] != '') {
			$bargain['logourl2'] = $this->getLink($bargain['logourl2']);
		}

		if ($bargain['logourl3'] != '') {
			$bargain['logourl3'] = $this->getLink($bargain['logourl3']);
		}

		$this->assign('bargain', $bargain);
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$this->assign('userinfo', $userinfo);
		$where_order1['token'] = $this->token;
		$where_order1['imicms_id'] = (int) $_GET['orderid'];
		$order1 = $this->m_order->where($where_order1)->find();
		if (($order1 == '') || ($order1['wecha_id'] == $this->wecha_id)) {
			$type = 'my';
			$where_order2['token'] = $this->token;
			$where_order2['wecha_id'] = $this->wecha_id;
			$where_order2['bargain_id'] = (int) $_GET['id'];
			$order2 = $this->m_order->where($where_order2)->find();

			if ($order2 == '') {
				$type2 = 'noorder';
			}
			else {
				$this->assign('order', $order2);

				if (time() < $order2['endtime']) {
					$time = $order2['endtime'] - time();
					$hour = floor($time / (60 * 60));

					if ($hour < 48) {
						$hour_y = $time % (60 * 60);
						$minute = floor($hour_y / 60);
						$second = $hour_y % 60;
						$this->assign('hour', $hour);
						$this->assign('minute', $minute);
						$this->assign('second', $second);
					}
					else {
						$this->assign('isday', 'yes');
						$Dday = floor($time / (60 * 60 * 24));
						$Dday_y = $time % (60 * 60 * 24);
						$Dhour = floor($Dday_y / (60 * 60));
						$Dhour_y = $Dday_y % (60 * 60);
						$Dminute = floor($Dhour_y / 60);
						$Dsecond = $Dhour_y % 60;
						$this->assign('day', $Dday);
						$this->assign('hour', $Dhour);
						$this->assign('minute', $Dminute);
						$this->assign('second', $Dsecond);
					}
				}

				$where_kanuser['token'] = $this->token;
				$where_kanuser['orderid'] = $order2['imicms_id'];
				$count_kanuser = $this->m_kanuser->where($where_kanuser)->count();
				$select_kanuser = $this->m_kanuser->where($where_kanuser)->order('addtime')->select();
				$price_kanuser = 0;

				foreach ($select_kanuser as $k => $vo) {
					$price_kanuser = $price_kanuser + $vo['dao'];
					$where_userinfo2['token'] = $this->token;
					$where_userinfo2['wecha_id'] = $vo['friend'];
					$userinfo2 = $this->m_userinfo->where($where_userinfo2)->find();
					$select_kanuser[$k]['wechaname'] = $userinfo2['wechaname'];
					$select_kanuser[$k]['portrait'] = $userinfo2['portrait'];
				}

				$this->assign('count', $count_kanuser);
				$this->assign('dao', $price_kanuser);
				$this->assign('kanuser_list', $select_kanuser);
			}

			$this->assign('type2', $type2);
		}
		else {
			$type = 'nomy';
			$where_kanuser['token'] = $this->token;
			$where_kanuser['wecha_id'] = $order1['wecha_id'];
			$where_kanuser['friend'] = $this->wecha_id;
			$where_kanuser['bargain_id'] = (int) $_GET['id'];
			$kanuser = $this->m_kanuser->where($where_kanuser)->find();

			if ($kanuser == '') {
				$type2 = 'nokan';
				$this->assign('order', $order1);

				if (time() < $order1['endtime']) {
					$time = $order1['endtime'] - time();
					$hour = floor($time / (60 * 60));

					if ($hour < 48) {
						$hour_y = $time % (60 * 60);
						$minute = floor($hour_y / 60);
						$second = $hour_y % 60;
						$this->assign('hour', $hour);
						$this->assign('minute', $minute);
						$this->assign('second', $second);
					}
					else {
						$this->assign('isday', 'yes');
						$Dday = floor($time / (60 * 60 * 24));
						$Dday_y = $time % (60 * 60 * 24);
						$Dhour = floor($Dday_y / (60 * 60));
						$Dhour_y = $Dday_y % (60 * 60);
						$Dminute = floor($Dhour_y / 60);
						$Dsecond = $Dhour_y % 60;
						$this->assign('day', $Dday);
						$this->assign('hour', $Dhour);
						$this->assign('minute', $Dminute);
						$this->assign('second', $Dsecond);
					}
				}

				$where_kanuser2['token'] = $this->token;
				$where_kanuser2['orderid'] = $order1['imicms_id'];
				$count_kanuser = $this->m_kanuser->where($where_kanuser2)->count();
				$select_kanuser = $this->m_kanuser->where($where_kanuser2)->order('addtime')->select();
				$price_kanuser = 0;

				foreach ($select_kanuser as $k => $vo) {
					$price_kanuser = $price_kanuser + $vo['dao'];
					$where_userinfo2['token'] = $this->token;
					$where_userinfo2['wecha_id'] = $vo['friend'];
					$userinfo2 = $this->m_userinfo->where($where_userinfo2)->find();
					$select_kanuser[$k]['wechaname'] = $userinfo2['wechaname'];
					$select_kanuser[$k]['portrait'] = $userinfo2['portrait'];
				}

				$this->assign('count', $count_kanuser);
				$this->assign('dao', $price_kanuser);
				$this->assign('kanuser_list', $select_kanuser);
				$where_userinfo3['token'] = $this->token;
				$where_userinfo3['wecha_id'] = $order1['wecha_id'];
				$userinfo3 = $this->m_userinfo->where($where_userinfo3)->find();
				$this->assign('userinfo2', $userinfo3);
			}
			else {
				$this->assign('order', $order1);

				if (time() < $order1['endtime']) {
					$time = $order1['endtime'] - time();
					$hour = floor($time / (60 * 60));

					if ($hour < 48) {
						$hour_y = $time % (60 * 60);
						$minute = floor($hour_y / 60);
						$second = $hour_y % 60;
						$this->assign('hour', $hour);
						$this->assign('minute', $minute);
						$this->assign('second', $second);
					}
					else {
						$this->assign('isday', 'yes');
						$Dday = floor($time / (60 * 60 * 24));
						$Dday_y = $time % (60 * 60 * 24);
						$Dhour = floor($Dday_y / (60 * 60));
						$Dhour_y = $Dday_y % (60 * 60);
						$Dminute = floor($Dhour_y / 60);
						$Dsecond = $Dhour_y % 60;
						$this->assign('day', $Dday);
						$this->assign('hour', $Dhour);
						$this->assign('minute', $Dminute);
						$this->assign('second', $Dsecond);
					}
				}

				$where_kanuser2['token'] = $this->token;
				$where_kanuser2['orderid'] = $order1['imicms_id'];
				$count_kanuser = $this->m_kanuser->where($where_kanuser2)->count();
				$select_kanuser = $this->m_kanuser->where($where_kanuser2)->order('addtime')->select();
				$price_kanuser = 0;

				foreach ($select_kanuser as $k => $vo) {
					$price_kanuser = $price_kanuser + $vo['dao'];
					$where_userinfo2['token'] = $this->token;
					$where_userinfo2['wecha_id'] = $vo['friend'];
					$userinfo2 = $this->m_userinfo->where($where_userinfo2)->find();
					$select_kanuser[$k]['wechaname'] = $userinfo2['wechaname'];
					$select_kanuser[$k]['portrait'] = $userinfo2['portrait'];
				}

				$this->assign('count', $count_kanuser);
				$this->assign('dao', $price_kanuser);
				$this->assign('kanuser_list', $select_kanuser);
				$where_userinfo3['token'] = $this->token;
				$where_userinfo3['wecha_id'] = $order1['wecha_id'];
				$userinfo3 = $this->m_userinfo->where($where_userinfo3)->find();
				$this->assign('userinfo2', $userinfo3);
				$this->assign('kanuser', $kanuser);
			}

			$this->assign('type2', $type2);
		}

		$this->assign('type', $type);
		$this->display();
	}

	public function operate()
	{
		switch ($_GET['type']) {
		case 'firstdao':
			$where_order['token'] = $this->token;
			$where_order['wecha_id'] = $this->wecha_id;
			$where_order['bargain_id'] = (int) $_GET['id'];
			$order = $this->m_order->where($where_order)->find();

			if ($order == '') {
				$where['token'] = $this->token;
				$where['imicms_id'] = (int) $_GET['id'];
				$bargain = S($_GET['id'] . 'bargain' . $this->token);

				if ($bargain == '') {
					$bargain = $this->m_bargain->where($where)->find();
					S($_GET['id'] . 'bargain' . $this->token, $bargain);
				}

				if (($bargain['qdao'] != '') && ($bargain['qdao'] != 0)) {
					$kan = floor($bargain['qprice'] / $bargain['qdao']);
					if ((1 < $kan) && ($kan < $bargain['qprice'])) {
						$jian = rand(1, $kan - 1);
						$kanzhi = rand(1, $kan);
					}
					else {
						$kanzhi = $kan;
					}
				}
				else {
					$cha = $bargain['original'] - $bargain['minimum'];
					$kan = floor($cha / $bargain['dao']);

					if (1 < $kan) {
						$jian = rand(1, $kan - 1);
						$kanzhi = rand(1, $kan);
					}
					else {
						$kanzhi = $kan;
					}
				}

				$add_order['token'] = $this->token;
				$add_order['wecha_id'] = $this->wecha_id;
				$add_order['bargain_id'] = (int) $_GET['id'];
				$add_order['endtime'] = ($bargain['starttime'] * 3600) + time();
				$add_order['bargain_name'] = $bargain['name'];
				$add_order['bargain_logoimg'] = $bargain['logoimg1'];
				$add_order['bargain_original'] = $bargain['original'];
				$add_order['bargain_minimum'] = $bargain['minimum'];
				$add_order['bargain_nowprice'] = $bargain['original'] - $kanzhi;
				$where_userinfo['token'] = $this->token;
				$where_userinfo['wecha_id'] = $this->wecha_id;
				$userinfo = $this->m_userinfo->where($where_userinfo)->find();
				$add_order['phone'] = $userinfo['tel'];
				$add_order['address'] = $userinfo['address'];
				$add_order['addtime'] = time();
				$id_order = $this->m_order->add($add_order);
				$where_order_orderid['imicms_id'] = $id_order;
				$randnum = rand(1000, 9999);
				$save_order_orderid['orderid'] = $id_order . 'bargain' . time() . $randnum;
				$update_order_orderid = $this->m_order->where($where_order_orderid)->save($save_order_orderid);
				$add_kanuser['token'] = $this->token;
				$add_kanuser['wecha_id'] = $this->wecha_id;
				$add_kanuser['bargain_id'] = (int) $_GET['id'];
				$add_kanuser['orderid'] = $id_order;
				$add_kanuser['friend'] = $this->wecha_id;
				$add_kanuser['dao'] = $kanzhi;
				$add_kanuser['addtime'] = time();
				$id_kanuser = $this->m_kanuser->add($add_kanuser);
				$this->redirect('Bargain/dao', array('token' => $this->token, 'id' => $_GET['id'], 'kanzhi' => $kanzhi));
			}
			else {
				$this->redirect('Bargain/dao', array('token' => $this->token, 'id' => $_GET['id']));
			}

			break;

		case 'friendkan':
			$where_order['token'] = $this->token;
			$where_order['imicms_id'] = (int) $_GET['orderid'];
			$order = $this->m_order->where($where_order)->find();
			$where_kanuser['token'] = $this->token;
			$where_kanuser['wecha_id'] = $order['wecha_id'];
			$where_kanuser['friend'] = $this->wecha_id;
			$where_kanuser['bargain_id'] = (int) $_GET['id'];
			$kanuser = $this->m_kanuser->where($where_kanuser)->find();

			if ($kanuser == '') {
				$where_kanuser2['token'] = $this->token;
				$where_kanuser2['orderid'] = (int) $_GET['orderid'];
				$count = $this->m_kanuser->where($where_kanuser2)->count();
				$where['token'] = $this->token;
				$where['imicms_id'] = (int) $_GET['id'];
				$bargain = S($_GET['id'] . 'bargain' . $this->token);

				if ($bargain == '') {
					$bargain = $this->m_bargain->where($where)->find();
					S($_GET['id'] . 'bargain' . $this->token, $bargain);
				}

				if ($order['bargain_nowprice'] == $bargain['minimum']) {
					$this->show('<script>alert(\'TA的砍价已砍至底价\');window.location.href=\'' . U('Bargain/dao', array('token' => $this->token, 'id' => $_GET['id'], 'orderid' => $_GET['orderid'])) . '\'</script>');
				}
				else if ($bargain['minimum'] < $order['bargain_nowprice']) {
					if (($bargain['qdao'] != '') && ($bargain['qdao'] != 0)) {
						if ($bargain['qdao'] <= $count) {
							$cha_dao = $bargain['dao'] - $bargain['qdao'];

							if ($cha_dao == 1) {
								$kanzhi = $order['bargain_nowprice'] - $bargain['minimum'];
							}
							else if (1 < $cha_dao) {
								$cha_dao2 = $bargain['dao'] - $count;

								if ($cha_dao2 == 1) {
									$kanzhi = $order['bargain_nowprice'] - $bargain['minimum'];
								}
								else {
									$cha_price = $bargain['original'] - $bargain['minimum'] - $bargain['qprice'];
									$kan = floor($cha_price / $cha_dao);

									if (1 < $kan) {
										$jian = rand(1, $kan - 1);
										$kanzhi = rand(1, $kan);
									}
									else {
										$kanzhi = $kan;
									}
								}
							}
						}
						else if ($count < $bargain['qdao']) {
							$cha_dao = $bargain['qdao'] - $count;

							if ($cha_dao == 1) {
								$kanzhi = $bargain['original'] - $bargain['qprice'] - $order['bargain_nowprice']; //自行修复  数值反了
							}
							else if (1 < $cha_dao) {
								$kan = floor($bargain['qprice'] / $bargain['qdao']);

								if (1 < $kan) {
									$jian = rand(1, $kan - 1);
									$kanzhi = rand(1, $kan);
								}
								else {
									$kanzhi = $kan;
								}
							}
						}
					}
					else {
						$cha_dao = $bargain['dao'] - $count;

						if ($cha_dao == 1) {
							$kanzhi = $order['bargain_nowprice'] - $bargain['minimum'];
						}
						else {
							$cha = $bargain['original'] - $bargain['minimum'];
							$kan = floor($cha / $bargain['dao']);

							if (1 < $kan) {
								$jian = rand(1, $kan - 1);
								$kanzhi = rand(1, $kan);
							}
							else {
								$kanzhi = $kan;
							}
						}
					}

					$save_order['bargain_nowprice'] = $order['bargain_nowprice'] - abs($kanzhi); //自行修改 取绝对值
					$update_order = $this->m_order->where($where_order)->save($save_order);
					$add_kanuser['token'] = $this->token;
					$add_kanuser['wecha_id'] = $order['wecha_id'];
					$add_kanuser['bargain_id'] = (int) $_GET['id'];
					$add_kanuser['orderid'] = $order['imicms_id'];
					$add_kanuser['friend'] = $this->wecha_id;
					$add_kanuser['dao'] = abs($kanzhi);  //自行修改 取绝对值
					$add_kanuser['addtime'] = time();
					$id_kanuser = $this->m_kanuser->add($add_kanuser);
		            $this->redirect('Bargain/dao', array('token' => $this->token, 'id' => $_GET['id'], 'orderid' => $_GET['orderid'], 'kanzhi' => abs($kanzhi)));//ABS
				}
			}
			else {
				$this->redirect('Bargain/dao', array('token' => $this->token, 'id' => $_GET['id'], 'orderid' => $_GET['orderid']));
			}

			break;
		}
	}

	public function payuserinfo()
	{
		$where['token'] = $this->token;
		$where['imicms_id'] = (int) $_GET['id'];
		$bargain = S($_GET['id'] . 'bargain' . $this->token);

		if ($bargain == '') {
			$bargain = $this->m_bargain->where($where)->find();
			S($_GET['id'] . 'bargain' . $this->token, $bargain);
		}

		$this->assign('bargain', $bargain);
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$this->assign('userinfo', $userinfo);

		if ($_GET['orderid'] == '') {
			$add_order['token'] = $this->token;
			$add_order['wecha_id'] = $this->wecha_id;
			$add_order['bargain_id'] = (int) $_GET['id'];
			$add_order['endtime'] = 0;
			$add_order['bargain_name'] = $bargain['name'];
			$add_order['bargain_logoimg'] = $bargain['logoimg1'];
			$add_order['bargain_original'] = $bargain['original'];
			$add_order['bargain_minimum'] = $bargain['minimum'];
			$add_order['bargain_nowprice'] = $bargain['original'];
			$add_order['phone'] = $userinfo['tel'];
			$add_order['address'] = $userinfo['address'];
			$add_order['addtime'] = time();
			$id_order = $this->m_order->add($add_order);
			$where_order['token'] = $this->token;
			$where_order['imicms_id'] = $id_order;
			$randnum = rand(1000, 9999);
			$save_order_orderid['orderid'] = $id_order . 'bargain' . time() . $randnum;
			$update_order_orderid = $this->m_order->where($where_order)->save($save_order_orderid);
			$order = $this->m_order->where($where_order)->find();
			$this->assign('order', $order);
		}
		else {
			$where_order['token'] = $this->token;
			$where_order['imicms_id'] = (int) $_GET['orderid'];
			$order = $this->m_order->where($where_order)->find();
			$this->assign('order', $order);
		}

		$this->display();
	}

	public function dobuy()
	{
		$where_order['token'] = $this->token;
		$where_order['imicms_id'] = (int) $_GET['orderid'];
		$save_order['username'] = $_GET['name'];
		$save_order['phone'] = $_GET['phone'];
		$save_order['address'] = $_GET['address'];
		$save_order['addtime'] = time();
		$update_order = $this->m_order->where($where_order)->save($save_order);
		$order = $this->m_order->where($where_order)->find();
		$where['imicms_id'] = $order['bargain_id'];
		$where['token'] = $this->token;
		$inventory = $this->m_bargain->where($where)->getField('inventory');

		if ($inventory < 1) {
			$this->error('此商品都被抢完了！', U('Bargain/home', array('token' => $this->token)));
			exit();
		}

		$save_order2['price'] = $order['bargain_nowprice'];
		$randnum = rand(1000, 9999);
		$save_order2['orderid'] = $_GET['orderid'] . 'bargain' . time() . $randnum;
		$update_order2 = $this->m_order->where($where_order)->save($save_order2);
		$order2 = $this->m_order->where($where_order)->find();
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$save_userinfo['wechaname'] = $_GET['name'];
		$save_userinfo['tel'] = $_GET['phone'];
		$save_userinfo['address'] = $_GET['address'];
		$update_userinfo = $this->m_userinfo->where($where_userinfo)->save($save_userinfo);
		$this->redirect('Alipay/pay', array('token' => $this->token, 'price' => (int) $order2['bargain_nowprice'], 'wecha_id' => $this->wecha_id, 'from' => 'Bargain', 'orderid' => $order2['orderid'], 'single_orderid' => $order2['orderid'], 'notOffline' => 1));
	}

	public function payReturn()
	{
		$where_order['token'] = $this->token;
		$where_order['orderid'] = (int) $_GET['orderid'];
		$order = $this->m_order->where($where_order)->find();
		if (($order['paid'] == 1) && ($order['state2'] == 1)) {
			$this->success('支付成功', U('Bargain/my', array('token' => $this->token)));
		}
		else {
			ThirdPayBargain::index($_GET['orderid'], $order['paytype'], $order['third_id']);
			$this->success('支付成功', U('Bargain/my', array('token' => $this->token)));
		}
	}

	public function my()
	{
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$this->assign('userinfo', $userinfo);
		$this->display();
	}

	public function mybargain()
	{
		$where_order['token'] = $this->token;
		$where_order['wecha_id'] = $this->wecha_id;
		$order_list = $this->m_order->where($where_order)->select();

		foreach ($order_list as $k => $v) {
			$where['token'] = $this->token;
			$where['imicms_id'] = $v['bargain_id'];
			$bargain = S($v['bargain_id'] . 'bargain' . $this->token);

			if ($bargain == '') {
				$bargain = $this->m_bargain->where($where)->find();
				S($v['bargain_id'] . 'bargain' . $this->token, $bargain);
			}

			if ($bargain != '') {
				$order_list[$k]['bargain_name'] = $bargain['name'];
				$order_list[$k]['bargain_logoimg'] = $bargain['logoimg1'];
				$order_list[$k]['bargain_minimum'] = $bargain['minimum'];
				$order_list[$k]['bargain_inventory'] = $bargain['inventory'];
			}

			if (time() < $v['endtime']) {
				$time = $v['endtime'] - time();
				$hour = floor($time / (60 * 60));

				if ($hour < 48) {
					$hour_y = $time % (60 * 60);
					$minute = floor($hour_y / 60);
					$second = $hour_y % 60;
					$order_list[$k]['hour'] = $hour;
					$order_list[$k]['minute'] = $minute;
					$order_list[$k]['second'] = $second;
				}
				else {
					$order_list[$k]['isday'] = 'yes';
					$Dday = floor($time / (60 * 60 * 24));
					$Dday_y = $time % (60 * 60 * 24);
					$Dhour = floor($Dday_y / (60 * 60));
					$Dhour_y = $Dday_y % (60 * 60);
					$Dminute = floor($Dhour_y / 60);
					$Dsecond = $Dhour_y % 60;
					$order_list[$k]['day'] = $Dday;
					$order_list[$k]['hour'] = $Dhour;
					$order_list[$k]['minute'] = $Dminute;
					$order_list[$k]['second'] = $Dsecond;
				}
			}
		}

		$this->assign('order_list', $order_list);
		$this->display();
	}

	public function myorder()
	{
		$where_order['token'] = $this->token;
		$where_order['wecha_id'] = $this->wecha_id;
		$where_order['paid'] = 1;
		$order_list = $this->m_order->where($where_order)->select();

		foreach ($order_list as $k => $v) {
			$where['token'] = $this->token;
			$where['imicms_id'] = $v['bargain_id'];
			$bargain = S($v['bargain_id'] . 'bargain' . $this->token);

			if ($bargain == '') {
				$bargain = $this->m_bargain->where($where)->find();
				S($v['bargain_id'] . 'bargain' . $this->token, $bargain);
			}

			if ($bargain != '') {
				$order_list[$k]['bargain_name'] = $bargain['name'];
				$order_list[$k]['bargain_logoimg'] = $bargain['logoimg1'];
				$order_list[$k]['bargain_minimum'] = $bargain['minimum'];
				$order_list[$k]['bargain_inventory'] = $bargain['inventory'];
			}
		}

		$this->assign('order_list', $order_list);
		$this->display();
	}
}

?>
