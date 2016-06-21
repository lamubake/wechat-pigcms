<?php

class DishOutAction extends UserAction
{
	public $_cid = 0;

	public function _initialize()
	{
		parent::_initialize();
		$this->canUseFunction('DishOut');
		$this->_cid = 0 < session('companyid') ? session('companyid') : intval($_GET['cid']);
		$this->_cid = 0 < $this->_cid ? $this->_cid : 0;

		if (empty($this->token)) {
			$this->error('不合法的操作', U('Index/index'));
		}

		if (empty($this->_cid)) {
			$company = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();

			if ($company) {
				$this->_cid = $company['id'];
				session('companyk', md5($this->_cid . session('uname')));
			}
			else {
				$this->error('您还没有添加您的商家信息', U('Company/index', array('token' => $this->token)));
			}
		}
		else {
			$k = session('companyk');
			$company = M('Company')->where(array('token' => $this->token, 'id' => $this->_cid))->find();

			if (empty($company)) {
				$this->error('非法操作', U('Repast/index', array('token' => $this->token)));
			}
			else {
				$username = ($company['isbranch'] ? $company['username'] : session('uname'));

				if (md5($this->_cid . $username) != $k) {
					$this->error('非法操作', U('Repast/index', array('token' => $this->token)));
				}
			}
		}

		$this->assign('ischild', session('companyLogin'));
		$this->assign('cid', $this->_cid);
	}

	public function index()
	{
		$data = M('Dish');
		$where = array('cid' => $this->_cid, 'istakeout' => 1);
		$count = $data->where($where)->count();
		$Page = new Page($count, 20);
		$show = $Page->show();
		$dish = $data->where($where)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$list = $sortList = array();
		$sort = M('Dish_sort')->where(array('cid' => $this->_cid))->select();

		foreach ($sort as $row) {
			$sortList[$row['id']] = $row;
		}

		foreach ($dish as $r) {
			$r['sortName'] = isset($sortList[$r['sid']]['name']) ? $sortList[$r['sid']]['name'] : '';
			$list[] = $r;
		}

		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->display();
	}

	public function dishedit()
	{
		$dataBase = D('Dish');
		$dish_sort = M('Dish_sort');

		if (IS_POST) {
			$id = (isset($_POST['id']) ? intval($_POST['id']) : 0);
			$_POST['ishot'] = isset($_POST['ishot']) ? intval($_POST['ishot']) : 0;
			$_POST['isopen'] = isset($_POST['isopen']) ? intval($_POST['isopen']) : 0;
			$_POST['istakeout'] = isset($_POST['istakeout']) ? intval($_POST['istakeout']) : 0;

			if ($id) {
				if ($dataBase->create() !== false) {
					$temp = M('Dish')->where(array('cid' => $this->_cid, 'id' => $id))->find();
					$action = $dataBase->save();

					if ($action != false) {
						if ($temp['sid'] != $_POST['sid']) {
							$dish_sort->where(array('id' => $_POST['sid'], 'cid' => $this->_cid))->setInc('num', 1);
							$dish_sort->where(array('id' => $temp['sid'], 'cid' => $this->_cid))->setDec('num', 1);
						}

						$this->success('修改成功', U('DishOut/index', array('token' => $this->token, 'cid' => $this->_cid)));
					}
					else {
						$this->success('修改成功', U('DishOut/index', array('token' => $this->token, 'cid' => $this->_cid)));
					}
				}
				else {
					$this->error($dataBase->getError());
				}
			}
			else {
				$this->error('操作失败');
			}
		}
		else {
			$id = (isset($_GET['id']) ? intval($_GET['id']) : 0);
			$dishSort = M('Dish_sort')->where(array('cid' => $this->_cid))->select();

			if (empty($dishSort)) {
				$this->redirect(U('Repast/sortadd', array('token' => $this->token, 'cid' => $this->_cid)));
			}

			$findData = $dataBase->where(array('id' => $id, 'cid' => $this->_cid))->find();
			$this->assign('tableData', $findData);
			$this->assign('dishSort', $dishSort);
			$this->display();
		}
	}

	public function manageTime()
	{
		$db_dotime = M('dishout_manage');

		if (IS_POST) {
			$zc_sdate = $this->_post('zc_sdate', 'trim');
			$zc_edate = $this->_post('zc_edate', 'trim');
			$wc_sdate = $this->_post('wc_sdate', 'trim');
			$wc_edate = $this->_post('wc_edate', 'trim');
			$permin = intval($this->_post('permin', 'trim'));
			$removing = intval($this->_post('removing', 'trim'));
			$area = htmlspecialchars($this->_post('area', 'trim'), ENT_QUOTES);
			$sendtime = intval($this->_post('sendtime', 'trim'));
			$simage = $this->_post('simage');
			$tourl = $this->_post('tourl');
			$simage = array('img' => $simage, 'tourl' => $tourl);
			$tid = intval($this->_post('tid', 'trim'));
			$data['zc_sdate'] = !empty($zc_sdate) ? strtotime(date('Y-m-d ') . $zc_sdate) : 0;
			$data['zc_edate'] = !empty($zc_edate) ? strtotime(date('Y-m-d ') . $zc_edate) : 0;
			$data['wc_sdate'] = !empty($wc_sdate) ? strtotime(date('Y-m-d ') . $wc_sdate) : 0;
			$data['wc_edate'] = !empty($wc_edate) ? strtotime(date('Y-m-d ') . $wc_edate) : 0;
			if ((0 < $data['zc_edate']) && ($data['zc_edate'] <= $data['zc_sdate'])) {
				$this->error('第一段营业结束时间必须大于第一段开始时间!');
			}

			if ((0 < $data['wc_edate']) && ($data['wc_edate'] <= $data['wc_sdate'])) {
				$this->error('第二段营业结束时间必须大于第二段开始时间!');
			}

			$data['permin'] = 0 < $permin ? $permin : 15;
			if (($data['permin'] < 5) || (60 < $data['permin'])) {
				$this->error('请将外卖送达时间间隔值设置在5-60范围内!');
			}

			$data['removing'] = $removing < 250 ? $removing : 250;
			$data['sendtime'] = $sendtime < 250 ? $sendtime : 250;
			$data['area'] = $area;
			$data['shopimg'] = serialize($simage);

			if (0 < $tid) {
				$action = $db_dotime->where(array('id' => $tid, 'cid' => $this->_cid, 'token' => $this->token))->save($data);
				$this->success('修改成功', U('DishOut/manageTime', array('token' => $this->token, 'cid' => $this->_cid)));
			}
			else {
				$data['cid'] = $this->_cid;
				$data['token'] = $this->token;
				$action = $db_dotime->add($data);

				if ($action != false) {
					$this->success('保存成功', U('DishOut/index', array('token' => $this->token, 'cid' => $this->_cid)));
				}
				else {
					$this->error('保存失败', U('DishOut/manageTime', array('token' => $this->token, 'cid' => $this->_cid)));
				}
			}
		}
		else {
			$tmp = $db_dotime->where(array('cid' => $this->_cid, 'token' => $this->token))->find();
			$shopimg = unserialize($tmp['shopimg']);
			unset($tmp['shopimg']);
			$this->assign('mtime', $tmp);
			$this->assign('simage', $shopimg);
			$this->display();
		}
	}

	public function basePrice()
	{
		$db_dotime = M('dishout_manage');

		if (IS_POST) {
			$stype = intval($this->_post('stype', 'trim'));
			$pricing = intval($this->_post('pricing', 'trim'));
			$tid = intval($this->_post('tid', 'trim'));
			$data['stype'] = 0 < $stype ? $stype : 1;
			$data['pricing'] = 0 < $pricing ? $pricing : 0;

			if (0 < $tid) {
				$action = $db_dotime->where(array('id' => $tid, 'cid' => $this->_cid, 'token' => $this->token))->save($data);
				$this->success('修改成功', U('DishOut/basePrice', array('token' => $this->token, 'cid' => $this->_cid)));
			}
			else {
				$data['cid'] = $this->_cid;
				$data['token'] = $this->token;
				$action = $db_dotime->add($data);

				if ($action != false) {
					$this->success('保存成功', U('DishOut/index', array('token' => $this->token, 'cid' => $this->_cid)));
				}
				else {
					$this->error('保存失败', U('DishOut/basePrice', array('token' => $this->token, 'cid' => $this->_cid)));
				}
			}
		}
		else {
			$tmp = $db_dotime->where(array('cid' => $this->_cid, 'token' => $this->token))->find();
			$this->assign('mtime', $tmp);
			$this->display();
		}
	}

	public function myReply()
	{
		$db_dotime = M('dishout_manage');

		if (IS_POST) {
			$data['keyword'] = htmlspecialchars($this->_post('keyword', 'trim'), ENT_QUOTES);
			$data['rtitle'] = htmlspecialchars($this->_post('rtitle', 'trim'), ENT_QUOTES);
			$data['rinfo'] = htmlspecialchars($this->_post('rinfo', 'trim'), ENT_QUOTES);
			$data['picurl'] = htmlspecialchars($this->_post('picurl', 'trim'), ENT_QUOTES);
			$tid = intval($this->_post('tid', 'trim'));

			if (0 < $tid) {
				$action = $db_dotime->where(array('id' => $tid, 'cid' => $this->_cid, 'token' => $this->token))->save($data);
				$this->handleKeyword($tid, 'DishOut', $data['keyword']);
				$this->success('修改成功', U('DishOut/myReply', array('token' => $this->token, 'cid' => $this->_cid)));
			}
			else {
				$data['cid'] = $this->_cid;
				$data['token'] = $this->token;
				$insert_id = $db_dotime->add($data);

				if (0 < $insert_id) {
					$this->handleKeyword($insert_id, 'DishOut', $data['keyword']);
					$this->success('保存成功', U('DishOut/index', array('token' => $this->token, 'cid' => $this->_cid)));
				}
				else {
					$this->error('保存失败', U('DishOut/myReply', array('token' => $this->token, 'cid' => $this->_cid)));
				}
			}
		}
		else {
			$tmp = $db_dotime->where(array('cid' => $this->_cid, 'token' => $this->token))->find();
			if (is_array($tmp) && !empty($tmp)) {
				$tmp['keyword'] = htmlspecialchars_decode($tmp['keyword'], ENT_QUOTES);
				$tmp['rtitle'] = htmlspecialchars_decode($tmp['rtitle'], ENT_QUOTES);
				$tmp['rinfo'] = htmlspecialchars_decode($tmp['rinfo'], ENT_QUOTES);
				$tmp['picurl'] = htmlspecialchars_decode($tmp['picurl'], ENT_QUOTES);
			}
			else {
				$tmp['keyword'] = '外卖';
				$tmp['rtitle'] = '微信外卖';
				$tmp['rinfo'] = '';
				$tmp['picurl'] = $this->staticPath . '/tpl/static/dishout/image/wxdishout.jpg';
			}

			$this->assign('mtime', $tmp);
			$this->display();
		}
	}

	public function orders()
	{
		$status = ($this->_get('status') ? intval($this->_get('status', 'trim')) : 0);
		$t = ($this->_get('t') ? intval($this->_get('t', 'trim')) : 0);
		$fd = ($this->_get('fd') ? $this->_get('fd', 'trim') : '');
		$ischild = session('companyLogin');
		$ischild = ($ischild ? intval($ischild) : 0);
		$dish_order = M('Dish_order');
		$fstatus = 0;
		$pstatus = 0;
		$falg = false;
		if (($ischild != 1) && ($fd == 'on')) {
			$companys = M('Company')->where('token =\'' . $this->token . '\' AND (`isbranch`=1 AND `display`=1)')->field('id,token,name')->select();
			$Cyarrs = array();
			$cidsarr = '';

			if (!empty($companys)) {
				foreach ($companys as $vv) {
					$Cyarrs[$vv['id']] = $vv;
				}

				$cidsarr = array_keys($Cyarrs);
				$where = 'token="' . $this->_session('token') . '" AND cid in(' . implode(',', $cidsarr) . ') AND comefrom="dishout"';

				switch ($status) {
				case 1:
					if ($t == 1) {
						$where .= ' AND paid="0"';
						$pstatus = 1;
					}
					else if ($t == 2) {
						$where .= ' AND isuse="0"';
						$fstatus = 1;
					}

					break;

				case 2:
					if ($t == 1) {
						$where .= ' AND paid=1';
						$pstatus = 2;
					}
					else if ($t == 2) {
						$where .= ' AND isuse=1';
						$fstatus = 2;
					}

					break;

				default:
					break;
				}
			}

			$falg = true;
			$this->assign('companys', $Cyarrs);
		}
		else {
			$where = array('token' => $this->_session('token'), 'cid' => $this->_cid, 'comefrom' => 'dishout', 'isdel' => 0);

			switch ($status) {
			case 1:
				if ($t == 1) {
					$where['paid'] = 0;
					$pstatus = 1;
				}
				else if ($t == 2) {
					$where['isuse'] = 0;
					$fstatus = 1;
				}

				break;

			case 2:
				if ($t == 1) {
					$where['paid'] = 1;
					$pstatus = 2;
				}
				else if ($t == 2) {
					$where['isuse'] = 1;
					$fstatus = 2;
				}

				break;

			default:
				break;
			}
		}

		$count = (!empty($where) ? $dish_order->where($where)->count() : 0);
		$Page = new Page($count, 20);
		$show = $Page->show();
		$orders = (!empty($where) ? $dish_order->where($where)->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select() : false);
		$this->assign('orders', $orders);
		$this->assign('status', $pstatus);
		$this->assign('fstatus', $fstatus);
		$this->assign('page', $show);

		if ($falg) {
			$this->display('fdorders');
		}
		else {
			$this->display();
		}
	}

	public function saleLog($data)
	{
		$log_db = M('Dishout_salelog');
		$tmplog = $log_db->where(array('order_id' => $data['oid']))->find();

		if (!empty($tmplog)) {
			return false;
		}

		$Dishcompany = M('Dish_company')->where(array('cid' => $data['cid']))->find();
		$kconoff = $Dishcompany['kconoff'];
		unset($Dishcompany);
		$tmparr = array('token' => $this->token, 'cid' => $data['cid'], 'order_id' => $data['oid'], 'paytype' => $data['paytype']);
		$mDishSet = $this->getDishMainCompany($this->token);

		if (!empty($data['dish'])) {
			$DishDb = M('Dish');

			foreach ($data['dish'] as $kk => $vv) {
				$did = (isset($vv['did']) ? $vv['did'] : $kk);
				$dishofcid = $data['cid'];
				if (($mDishSet['cid'] != $data['cid']) && ($mDishSet['dishsame'] == 1)) {
					$dishofcid = $mDishSet['cid'];
					$kconoff = $mDishSet['kconoff'];
				}

				$tmpdish = $DishDb->where(array('id' => $did, 'cid' => $dishofcid))->find();
				if ($kconoff && !empty($tmpdish) && (0 < $tmpdish['instock'])) {
					$DishDb->where(array('id' => $did, 'cid' => $dishofcid))->setDec('instock', $vv['num']);
				}

				$logarr = array('did' => $did, 'nums' => $vv['num'], 'unitprice' => $vv['price'], 'money' => $vv['num'] * $vv['price'], 'dname' => $vv['name'], 'addtime' => $data['time'], 'addtimestr' => date('Y-m-d H:i:s', $data['time']), 'comefrom' => 0);
				$savelogarr = array_merge($tmparr, $logarr);
				$log_db->add($savelogarr);
			}
		}
	}

	private function getDishMainCompany($token)
	{
		$MainC = M('Company')->where(array('token' => $token, 'isbranch' => 0))->find();
		$m_cid = $MainC['id'];
		unset($MainC);
		$mDishC = M('Dish_company')->where(array('cid' => $m_cid))->find();
		unset($m_cid);
		return $mDishC;
	}

	public function orderInfo()
	{
		$id = ($this->_get('id') ? intval($this->_get('id', 'trim')) : 0);
		$fd = ($this->_get('fd') ? $this->_get('fd', 'trim') : '');
		$cidd = ($this->_get('cidd') ? intval($this->_get('cidd', 'trim')) : 0);
		$cid = $this->_cid;
		if (($fd == 'on') && (0 < $cidd)) {
			$cid = $cidd;
			$this->assign('isfd', true);
		}

		$dishOrder = M('Dish_order');

		if ($thisOrder = $dishOrder->where(array('id' => $id, 'cid' => $cid, 'token' => $this->token, 'comefrom' => 'dishout'))->find()) {
			if (IS_POST) {
				$paid = (isset($_POST['paid']) ? intval($_POST['paid']) : 0);
				$isuse = (isset($_POST['isuse']) ? intval($_POST['isuse']) : 0);
				$dishOrder->where(array('id' => $thisOrder['id']))->save(array('paid' => $paid, 'isuse' => $isuse));
				$company = M('Company')->where(array('token' => $this->token, 'id' => $thisOrder['cid']))->find();

				if ($paid) {
					$temp = unserialize($thisOrder['info']);
					$this->saleLog(array('cid' => $cid, 'oid' => $thisOrder['id'], 'paytype' => $thisOrder['paytype'], 'dish' => $temp, 'time' => $thisOrder['time']));
					$op = new orderPrint();
					$msg = array('companyname' => $company['name'], 'des' => htmlspecialchars_decode($thisOrder['des'], ENT_QUOTES), 'companytel' => $company['tel'], 'truename' => htmlspecialchars_decode($thisOrder['name'], ENT_QUOTES), 'tel' => $thisOrder['tel'], 'address' => htmlspecialchars_decode($thisOrder['address'], ENT_QUOTES), 'buytime' => $thisOrder['time'], 'orderid' => $thisOrder['orderid'], 'sendtime' => 0 < $thisOrder['reservetime'] ? $thisOrder['reservetime'] : '尽快送达', 'price' => $thisOrder['price'], 'total' => $thisOrder['total'], 'typename' => '外卖', 'ptype' => $thisOrder['paytype'], 'list' => $temp);
					$msg = ArrayToStr::array_to_str($msg, 1);
					$op->printit($this->token, $this->_cid, 'DishOut', $msg, 1);
				}

				$this->success('修改成功', U('DishOut/orderInfo', array('token' => session('token'), 'id' => $thisOrder['id'])));
			}
			else {
				$payarr = array('alipay' => '支付宝', 'weixin' => '微信支付', 'tenpay' => '财付通[wap手机]', 'tenpaycomputer' => '财付通[即时到帐]', 'yeepay' => '易宝支付', 'allinpay' => '通联支付', 'daofu' => '货到付款', 'dianfu' => '到店付款', 'chinabank' => '网银在线');
				$paystr = strtolower($thisOrder['paytype']);
				$thisOrder['paystr'] = !empty($paystr) && array_key_exists($paystr, $payarr) ? $payarr[$paystr] : '其他';
				$dishList = unserialize($thisOrder['info']);
				$this->assign('thisOrder', $thisOrder);
				$this->assign('dishList', $dishList);
				$this->display();
			}
		}
	}

	public function toPrint()
	{
		$id = ($this->_post('oid') ? intval($this->_post('oid', 'trim')) : 0);
		$dishOrder = M('Dish_order');

		if ($thisOrder = $dishOrder->where(array('id' => $id, 'cid' => $this->_cid, 'token' => $this->token, 'comefrom' => 'dishout'))->find()) {
			$company = M('Company')->where(array('token' => $this->token, 'id' => $thisOrder['cid']))->find();
			$temp = unserialize($thisOrder['info']);
			$op = new orderPrint();
			$msg = array('companyname' => $company['name'], 'des' => htmlspecialchars_decode($thisOrder['des'], ENT_QUOTES), 'companytel' => $company['tel'], 'truename' => htmlspecialchars_decode($thisOrder['name'], ENT_QUOTES), 'tel' => $thisOrder['tel'], 'address' => htmlspecialchars_decode($thisOrder['address'], ENT_QUOTES), 'buytime' => $thisOrder['time'], 'orderid' => $thisOrder['orderid'], 'sendtime' => $thisOrder['reservetime'], 'price' => $thisOrder['price'], 'total' => $thisOrder['total'], 'typename' => '外卖', 'list' => $temp);
			$msg = ArrayToStr::array_to_str($msg, $thisOrder['paid']);
			$op->printit($this->token, $this->_cid, 'DishOut', $msg, 1);
			echo json_encode(array('error' => 0));
		}
	}

	public function deleteOrder()
	{
		$id = (isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0);
		$dishOrder = M('Dish_order');

		if ($thisOrder = $dishOrder->where(array('id' => $id, 'cid' => $this->_cid, 'token' => $this->token, 'comefrom' => 'dishout'))->find()) {
			$dishOrder->where(array('id' => $id))->save(array('isdel' => 1));
			$this->success('操作成功', U('DishOut/orders', array('token' => session('token'), 'cid' => $this->_cid)));
		}
	}

	public function Statistics()
	{
		$starttime = $this->_get('stime', 'trim');
		$starttime = (!empty($starttime) ? strtotime($starttime) : 0);
		$endtime = $this->_get('etime', 'trim');
		$endtime = (!empty($endtime) ? strtotime($endtime) : 0);
		$starttime = (0 < $starttime ? $starttime : strtotime(date('Y-m-d') . '00:00:00'));
		$endtime = (0 < $endtime ? $endtime : strtotime(date('Y-m-d H:i:s')));
		$Model = new Model();
		$sqlstr = 'select *,sum(money) as tmoney,sum(nums) as tnums from ' . C('DB_PREFIX') . 'dishout_salelog where comefrom=\'0\' AND cid=' . $this->_cid . ' AND token=\'' . $this->token . '\' AND addtime>=' . $starttime . ' AND addtime<=' . $endtime . ' group by did';
		$tmp = $Model->query($sqlstr);
		$caiarr = array();
		$numsarr = array();
		$moneyarr = array();
		$tnums = 0;
		$tmoney = 0;

		if (!empty($tmp)) {
			foreach ($tmp as $kk => $vv) {
				$caiarr[] = '\'' . $vv['dname'] . '\'';
				$numsarr[] = $vv['tnums'];
				$tnums += $vv['tnums'];
				$moneyarr[] = $vv['tmoney'];
				$tmoney += $vv['tmoney'];
			}
		}
		else {
			$this->assign('nodata', true);
		}

		if (!empty($caiarr)) {
			$caistr = implode(',', $caiarr);
		}
		else {
			$caistr = '';
		}

		if (!empty($numsarr)) {
			$numsstr = implode(',', $numsarr);
		}
		else {
			$numsstr = '';
		}

		if (!empty($moneyarr)) {
			$moneystr = implode(',', $moneyarr);
		}
		else {
			$moneystr = '';
		}

		$this->assign('stime', date('Y-m-d H:i:s', $starttime));
		$this->assign('etime', date('Y-m-d H:i:s', $endtime));
		$this->assign('caistr', $caistr);
		$this->assign('numsstr', $numsstr);
		$this->assign('moneystr', $moneystr);
		$this->assign('tnums', $tnums);
		$this->assign('tmoney', $tmoney);
		$this->display();
	}
}

?>
