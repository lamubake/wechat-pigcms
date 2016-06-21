<?php

class AutumnsAction extends LotteryBaseAction
{
	public function __construct()
	{
		parent::_initialize();
	}

	public function index()
	{
		$id = $this->_get('id', 'intval');
		$token = $this->token;
		$bid = M('Lottery')->where(array('id' => $id, 'token' => $token))->getField('zjpic');

		if (!$bid) {
			$this->error('不存在的活动');
		}

		$Activity = M('Activity')->where(array('id' => $bid, 'token' => $token, 'type' => 1))->find();

		if (!$Activity) {
			$this->error('不存在的活动');
		}

		if (time() < $Activity['statdate']) {
			$this->error('活动未开始,请在' . date('Y-m-d H:i:s', $Activity['statdate']) . '后再来参加活动!');
		}

		if ($Activity['enddate'] < time()) {
			$this->error('活动已结束');
		}

		if ($Activity['status'] == 0) {
			$this->error('活动已结束');
		}

		$wecha_id = $this->wecha_id;
		$count = M('Activity')->where(array('token' => $token, 'id' => $bid))->getField('joinnum');
		$list = M('Autumns_box')->where(array('token' => $token, 'wecha_id' => $wecha_id, 'bid' => $id))->select();
		$prize = M('Autumns_box')->where(array('isprize' => 1, 'token' => $token, 'bid' => $id))->select();
		$nums = $Activity['fistnums'] + $Activity['secondnums'] + $Activity['thirdnums'] + $Activity['fournums'] + $Activity['fivenums'] + $Activity['sixnums'];
		$lucknums = $Activity['fistlucknums'] + $Activity['secondlucknums'] + $Activity['thirdlucknums'] + $Activity['fourlucknums'] + $Activity['fivelucknums'] + $Activity['sixlucknums'];
		$displayjpnums = $Activity['displayjpnums'];
		$data = $Activity;
		$fans = $this->fans;
		$data['info'] = nl2br($data['info']);
		$data['aginfo'] = nl2br($data['aginfo']);
		$data['endinfo'] = nl2br($data['endinfo']);
		$data['info'] = str_replace('&lt;br&gt;', '<br>', $data['info']);
		$data['aginfo'] = str_replace('&lt;br&gt;', '<br>', $data['aginfo']);
		$data['endinfo'] = str_replace('&lt;br&gt;', '<br>', $data['endinfo']);
		$url = M('Home')->where(array('token' => $token))->getField('gzhurl');
		$focus = $Activity['focus'];
		$weixin = $this->wxuser['weixin'];
		$this->assign('fans', $fans);
		$this->assign('weixin', $weixin);
		$this->assign('focus', $focus);
		$this->assign('url', $url);
		$this->assign('activity', $data);
		$this->assign('displayjpnums', $displayjpnums);
		$this->assign('nums', $nums);
		$this->assign('lucknums', $lucknums);
		$this->assign('linfo', $Activity);
		$this->assign('count', $count);
		$this->assign('list', $list);
		$this->assign('prize', $prize);
		if (($Activity['focus'] == 1) && ($this->isSubscribe() == false)) {
			$this->memberNotice('', 1);
		}
		else {
			if (($Activity['needreg'] == 0) && empty($this->fans['tel'])) {
				$this->memberNotice();
			}
		}

		$this->display();
	}

	public function mybox()
	{
		$id = $this->_get('id', 'intval');
		$token = $this->token;
		$wecha_id = $this->wecha_id;
		$bid = M('Lottery')->where(array('id' => $id, 'token' => $token))->getField('zjpic');

		if (!$bid) {
			$this->error('不存在的活动');
		}

		$Activity = M('Activity')->where(array('id' => $bid, 'token' => $token, 'type' => 1))->find();

		if (!$Activity) {
			$this->error('不存在的活动');
		}

		if ($Activity['enddate'] < time()) {
			$this->error('活动已结束');
		}

		if ($Activity['status'] == 0) {
			$this->error('活动已结束');
		}

		if ($wecha_id == '') {
			$this->error('错误操作');
		}

		$data = M('Autumns_box')->where(array('bid' => $id, 'token' => $token, 'wecha_id' => $wecha_id))->count();

		if ($data == 0) {
			$this->error('亲，你还没有领取礼盒，快去领一个吧!');
		}

		$box = M('Autumns_box')->where(array('token' => $token, 'bid' => $id, 'wecha_id' => $wecha_id))->order('id DESC')->select();
		$count = M('Autumns_open')->where(array('token' => $token, 'wecha_id' => $wecha_id, 'lid' => $id))->order('bid DESC')->select();
		$this->assign('linfo', $Activity);
		$this->assign('count', $count);
		$this->assign('box', $box);
		$this->display();
	}

	public function box()
	{
		$id = (int) $this->_get('id');
		$token = $this->token;
		$wecha_id = $this->wecha_id;
		$bid = M('Lottery')->where(array('id' => $id, 'token' => $token))->getField('zjpic');

		if (!$bid) {
			$this->error('不存在的活动');
		}

		$Activity = M('Activity')->where(array('id' => $bid, 'token' => $token, 'type' => 1))->find();

		if (!$Activity) {
			$this->error('不存在的活动');
		}

		if ($Activity['enddate'] < time()) {
			$this->error('活动已结束');
		}

		if ($Activity['status'] == 0) {
			$this->error('活动已结束');
		}

		$data = M('userinfo')->where(array('wecha_id' => $wecha_id, 'token' => $token))->find();
		$this->assign('data', $data);
		$this->assign('linfo', $Activity);
		$this->display();
	}

	public function box_add()
	{
		$wecha_id = $this->wecha_id;
		$id = $this->_GET('id', 'intval');
		$token = $this->token;
		$bid = M('Lottery')->where(array('id' => $id, 'token' => $token))->getField('zjpic');
		$time = M('Autumns_box')->where(array('token' => $token, 'wecha_id' => $wecha_id, 'bid' => $id))->count();
		$times = M('Activity')->where(array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid))->getField('canrqnums');

		if ($times != 0) {
			if (($time == $times) || ($times < $time)) {
				$this->error('活动期间每人只能领取' . $times . '个礼盒！');
			}
		}

		$date = strtotime('today');
		$enddate = strtotime('+1 day');
		$dayWhere = 'wecha_id=\'' . $wecha_id . '\' AND bid=' . $id . ' AND boxdate>' . $date . ' AND boxdate<' . $enddate;
		$list = M('Autumns_box')->where($dayWhere)->count();
		$list = intval($list);
		$day = M('Activity')->where(array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid))->getField('daynums');

		if ($day != 0) {
			if ($day <= $list) {
				$this->error('今天已经领取' . $list . '个礼盒了，请明天再来！');
			}
		}

		$time = time();
		$result = array();
		$mybox = M('Autumns_box');
		$name = M('Userinfo')->where(array('wecha_id' => $wecha_id))->getField('wechaname');

		if (empty($name)) {
			$name = '游客';
		}

		$data['token'] = $token;
		$data['bid'] = $id;
		$data['wecha_id'] = $wecha_id;
		$data['box'] = $this->_GET('box', 'intval');
		$data['name'] = $name;
		$data['boxdate'] = $time;

		if ($mybox->add($data)) {
			$result['err'] = 0;
			$result['info'] = '';
		}

		$open = M('Autumns_open');
		$data['token'] = $token;
		$data['wecha_id'] = $wecha_id;
		$data['lid'] = $id;
		$data['boxdate'] = $time;
		$box = M('Autumns_box')->where(array('token' => $token, 'bid' => $id, 'wecha_id' => $wecha_id))->order('id DESC')->getField('id');
		$data['bid'] = $box;

		if ($open->add($data)) {
			$result['err'] = 0;
			$result['info'] = '';
		}

		echo json_encode($result);
	}

	protected function get_rand($proArr, $total)
	{
		$result = 7;
		$randNum = mt_rand(1, $total);

		foreach ($proArr as $k => $v) {
			if (0 < $v['v']) {
				if (($v['start'] < $randNum) && ($randNum <= $v['end'])) {
					$result = $k;
					break;
				}
			}
		}

		return $result;
	}

	public function boxs()
	{
		if (IS_POST) {
			$bid = $this->_get('id');
			$token = $this->token;
			$id = M('Lottery')->where(array('id' => $bid, 'token' => $token))->getField('zjpic');
			$wecha_id = $this->wecha_id;
			$info = $this->_post('info-prize');
			$mybox = M('Autumns_box');
			$open = M('Autumns_open');
			$lottery_db = M('Activity');
			$lottery = M('Activity')->where(array('token' => $token, 'id' => $id))->find();
			$userinfo = M('userinfo')->where(array('wecha_id' => $wecha_id))->count();
			$joinNum = $lottery['joinnum'];
			$firstNum = intval($lottery['fistnums']) - intval($lottery['fistlucknums']);
			$secondNum = intval($lottery['secondnums']) - intval($lottery['secondlucknums']);
			$thirdNum = intval($lottery['thirdnums']) - intval($lottery['thirdlucknums']);
			$fourthNum = intval($lottery['fournums']) - intval($lottery['fourlucknums']);
			$fifthNum = intval($lottery['fivenums']) - intval($lottery['fivelucknums']);
			$sixthNum = intval($lottery['sixnums']) - intval($lottery['sixlucknums']);
			$multi = intval($lottery['canrqnums']);
			$isopen = M('Autumns_open')->where(array('token' => $token, 'bid' => $info))->getField('isopen');

			if ($isopen != 0) {
				$this->error('这个礼盒已经打开过了！请重新进入我的礼盒查看。', U('Autumns/index', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
			}

			if (($firstNum == 0) && ($secondNum == 0) && ($thirdNum == 0) && ($fourthNum == 0) && ($fifthNum == 0) && ($sixthNum == 0)) {
				$data['isopen'] = 1;
				$open->where(array('bid' => $info, 'token' => $token))->save($data);
				$this->error('没有奖品了，奖品已经被小伙伴们抢光了！', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
			}

			$cheat = M('Lottery_cheat')->where(array('lid' => $bid, 'wecha_id' => $wecha_id))->find();

			if ($cheat != '') {
				$where['bid'] = $cheat['lid'];
				$where['wecha_id'] = $cheat['wecha_id'];
				$where['isprize'] = 1;
				$ispr = M('Autumns_box')->where($where)->count();

				if ($ispr == 0) {
					$prizetype = intval($cheat['prizetype']);
				}
			}
			else {
				$prize_arr = array(
					array('id' => 1, 'prize' => '一等奖', 'v' => $firstNum, 'start' => 0, 'end' => $firstNum),
					array('id' => 2, 'prize' => '二等奖', 'v' => $secondNum, 'start' => $firstNum, 'end' => $firstNum + $secondNum),
					array('id' => 3, 'prize' => '三等奖', 'v' => $thirdNum, 'start' => $firstNum + $secondNum, 'end' => $firstNum + $secondNum + $thirdNum),
					array('id' => 4, 'prize' => '四等奖', 'v' => $fourthNum, 'start' => $firstNum + $secondNum + $thirdNum, 'end' => $firstNum + $secondNum + $thirdNum + $fourthNum),
					array('id' => 5, 'prize' => '五等奖', 'v' => $fifthNum, 'start' => $firstNum + $secondNum + $thirdNum + $fourthNum, 'end' => $firstNum + $secondNum + $thirdNum + $fourthNum + $fifthNum),
					array('id' => 6, 'prize' => '六等奖', 'v' => $sixthNum, 'start' => $firstNum + $secondNum + $thirdNum + $fourthNum + $fifthNum, 'end' => $firstNum + $secondNum + $thirdNum + $fourthNum + $fifthNum + $sixthNum),
					array('id' => 7, 'prize' => '谢谢参与', 'v' => (intval($lottery['allpeople']) * $multi) - ($firstNum + $secondNum + $thirdNum + $fourthNum + $fifthNum + $sixthNum), 'start' => $firstNum + $secondNum + $thirdNum + $fourthNum + $fifthNum + $sixthNum, 'end' => intval($lottery['allpeople']) * $multi)
					);

				foreach ($prize_arr as $key => $val) {
					$arr[$val['id']] = $val;
				}

				if ($lottery['allpeople'] == 1) {
					if ($lottery['fistlucknums'] < $lottery['fistnums']) {
						$prizetype = 1;
						$data['prize'] = $lottery['fist'];
						$data['isprize'] = '1';
						$data['lvprize'] = '一等奖';
						$now = time();
						$data['prizedate'] = date('Y-m-d H:i:s', $now);
						$data['prizedates'] = date('Y-m-d H:i:s', $now + (3600 * 24 * 30));
						$data['sn'] = uniqid();
						$mybox->where(array('id' => $info, 'token' => $token))->save($data);
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('fistlucknums');
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('恭喜你，中奖了！', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
					else {
						$prizetype = '';
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('没有奖品了，奖品已经被小伙伴们抢光了！', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}

					exit();
				}
				else {
					$prizetype = $this->get_rand($arr, (intval($lottery['allpeople']) * $multi) - $joinNum);
				}
			}

			switch ($prizetype) {
			case 1:
				if ($lottery['fistnums'] <= $lottery['fistlucknums']) {
					$prizetype = '';
					$data['isopen'] = 1;
					$open->where(array('bid' => $info, 'token' => $token))->save($data);
					$lottery_db->where(array('id' => $id))->setInc('joinnum');
					$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
				}
				else {
					$prizetype = 1;
					$data['prize'] = $lottery['fist'];
					$data['isprize'] = '1';
					$data['lvprize'] = '一等奖';
					$now = time();
					$data['prizedate'] = date('Y-m-d H:i:s', $now);
					$data['prizedates'] = date('Y-m-d H:i:s', $now + (3600 * 24 * 30));
					$data['sn'] = uniqid();
					$mybox->where(array('id' => $info))->save($data);
					$data['isopen'] = 1;
					$open->where(array('bid' => $info, 'token' => $token))->save($data);
					$lottery_db->where(array('id' => $id))->setInc('fistlucknums');
					$lottery_db->where(array('id' => $id))->setInc('joinnum');
					$this->success('恭喜你，中奖了！', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
				}

				break;

			case 2:
				if ($lottery['secondnums'] <= $lottery['secondlucknums']) {
					$prizetype = '';
					$data['isopen'] = 1;
					$open->where(array('bid' => $info, 'token' => $token))->save($data);
					$lottery_db->where(array('id' => $id))->setInc('joinnum');
					$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
				}
				else {
					if (empty($lottery['second']) && empty($lottery['secondnums'])) {
						$prizetype = '';
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
					else {
						$prizetype = 2;
						$data['prize'] = $lottery['second'];
						$data['isprize'] = '1';
						$data['lvprize'] = '二等奖';
						$now = time();
						$data['prizedate'] = date('Y-m-d H:i:s', $now);
						$data['prizedates'] = date('Y-m-d H:i:s', $now + (3600 * 24 * 30));
						$data['sn'] = uniqid();
						$mybox->where(array('id' => $info))->save($data);
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('secondlucknums');
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('恭喜你，中奖了！', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
				}

				break;

			case 3:
				if ($lottery['thirdnums'] <= $lottery['thirdlucknums']) {
					$prizetype = '';
					$data['isopen'] = 1;
					$open->where(array('bid' => $info, 'token' => $token))->save($data);
					$lottery_db->where(array('id' => $id))->setInc('joinnum');
					$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
				}
				else {
					if (empty($lottery['third']) && empty($lottery['thirdnums'])) {
						$prizetype = '';
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
					else {
						$prizetype = 3;
						$data['prize'] = $lottery['third'];
						$data['isprize'] = '1';
						$data['lvprize'] = '三等奖';
						$now = time();
						$data['prizedate'] = date('Y-m-d H:i:s', $now);
						$data['prizedates'] = date('Y-m-d H:i:s', $now + (3600 * 24 * 30));
						$data['sn'] = uniqid();
						$mybox->where(array('id' => $info))->save($data);
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('thirdlucknums');
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('恭喜你，中奖了！', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
				}

				break;

			case 4:
				if ($lottery['fournums'] <= $lottery['fourlucknums']) {
					$prizetype = '';
					$data['isopen'] = 1;
					$open->where(array('bid' => $info, 'token' => $token))->save($data);
					$lottery_db->where(array('id' => $id))->setInc('joinnum');
					$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
				}
				else {
					if (empty($lottery['four']) && empty($lottery['fournums'])) {
						$prizetype = '';
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
					else {
						$prizetype = 4;
						$data['prize'] = $lottery['four'];
						$data['isprize'] = '1';
						$data['lvprize'] = '四等奖';
						$now = time();
						$data['prizedate'] = date('Y-m-d H:i:s', $now);
						$data['prizedates'] = date('Y-m-d H:i:s', $now + (3600 * 24 * 30));
						$data['sn'] = uniqid();
						$mybox->where(array('id' => $info))->save($data);
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('fourlucknums');
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('恭喜你，中奖了！', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
				}

				break;

			case 5:
				if ($lottery['fivenums'] <= $lottery['fivelucknums']) {
					$prizetype = '';
					$data['isopen'] = 1;
					$open->where(array('bid' => $info, 'token' => $token))->save($data);
					$lottery_db->where(array('id' => $id))->setInc('joinnum');
					$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
				}
				else {
					if (empty($lottery['five']) && empty($lottery['fivenums'])) {
						$prizetype = '';
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
					else {
						$prizetype = 5;
						$data['prize'] = $lottery['five'];
						$data['isprize'] = '1';
						$data['lvprize'] = '五等奖';
						$now = time();
						$data['prizedate'] = date('Y-m-d H:i:s', $now);
						$data['prizedates'] = date('Y-m-d H:i:s', $now + (3600 * 24 * 30));
						$data['sn'] = uniqid();
						$mybox->where(array('id' => $info))->save($data);
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('fivelucknums');
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('恭喜你，中奖了！', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
				}

				break;

			case 6:
				if ($lottery['sixnums'] <= $lottery['sixlucknums']) {
					$prizetype = '';
					$data['isopen'] = 1;
					$open->where(array('bid' => $info, 'token' => $token))->save($data);
					$lottery_db->where(array('id' => $id))->setInc('joinnum');
					$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
				}
				else {
					if (empty($lottery['six']) && empty($lottery['sixnums'])) {
						$prizetype = '';
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
					else {
						$prizetype = 6;
						$data['prize'] = $lottery['six'];
						$data['isprize'] = '1';
						$data['lvprize'] = '六等奖';
						$now = time();
						$data['prizedate'] = date('Y-m-d H:i:s', $now);
						$data['prizedates'] = date('Y-m-d H:i:s', $now + (3600 * 24 * 30));
						$data['sn'] = uniqid();
						$mybox->where(array('id' => $info))->save($data);
						$data['isopen'] = 1;
						$open->where(array('bid' => $info, 'token' => $token))->save($data);
						$lottery_db->where(array('id' => $id))->setInc('sixlucknums');
						$lottery_db->where(array('id' => $id))->setInc('joinnum');
						$this->success('恭喜你，中奖了！', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
					}
				}

				break;

			default:
				$prizetype = '';
				$data['isopen'] = 1;
				$open->where(array('bid' => $info, 'token' => $token))->save($data);
				$this->success('未中奖，请继续努力', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $bid)));
				$lottery_db->where(array('id' => $id))->setInc('joinnum');
				break;
			}

			return $prizetype;
		}
	}

	public function open()
	{
		$token = $this->token;
		$id = $this->_get('id', 'intval');
		$cid = M('Lottery')->where(array('id' => $id, 'token' => $token))->getField('zjpic');

		if (!$cid) {
			$this->error('不存在的活动');
		}

		$bid = $this->_get('bid', 'intval');
		$Activity = M('Activity')->where(array('id' => $cid, 'token' => $token, 'type' => 1))->find();

		if (!$Activity) {
			$this->error('不存在的活动');
		}

		if ($Activity['status'] == 0) {
			$this->error('活动已结束');
		}

		if ($Activity['enddate'] < time()) {
			$this->error('活动已结束');
		}

		$list = M('Autumns_open')->where(array('token' => $token, 'lid' => $id, 'bid' => $bid))->count();

		if (empty($list)) {
			$this->error('错误操作');
		}

		$ip = get_client_ip();
		$uip = M('users')->where(array('id' => $_SESSION['uid']))->getField('lastip');

		if ($ip == $uip) {
			$wecha_id = M('Autumns_open')->where(array('bid' => $bid, 'token' => $token))->getField('wecha_id');
			$this->success('', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $id)));
		}

		$wecha_id = $this->wecha_id;

		if (empty($wecha_id)) {
			$count = M('Autumns_ip')->where(array('token' => $token, 'lid' => $id, 'bid' => $bid, 'ip' => $ip))->count();
		}
		else {
			$count = M('Autumns_ip')->where(array('token' => $token, 'lid' => $id, 'bid' => $bid, 'wecha_id' => $wecha_id))->count();
		}

		$open = M('Autumns_open')->where(array('token' => $token, 'lid' => $id, 'bid' => $bid))->select();
		$time = M('Autumns_open')->where(array('token' => $token, 'lid' => $id, 'bid' => $bid))->getField('time');
		$box = M('Autumns_box')->where(array('token' => $token, 'bid' => $id, 'id' => $bid))->select();
		$optime = M('Activity')->where(array('token' => $token, 'id' => $cid, 'type' => 1))->getField('optime');
		$data = $optime - $time;
		$this->assign('linfo', $Activity);
		$this->assign('count', $count);
		$this->assign('data', $data);
		$this->assign('open', $open);
		$this->assign('box', $box);
		$this->display();
	}

	public function openx()
	{
		$bid = $this->_GET('bid', 'intval');
		$id = $this->_GET('id', 'intval');
		$token = $this->token;
		$cid = M('Lottery')->where(array('id' => $id, 'token' => $token))->getField('zjpic');
		$time = M('Autumns_open')->where(array('token' => $token, 'lid' => $id, 'bid' => $bid))->getField('time');
		$optime = M('Activity')->where(array('token' => $token, 'id' => $cid, 'type' => 1))->getField('optime');
		if (($time == $optime) || ($optime < $time)) {
			$result['err'] = 1;
			$result['info'] = '帮忙失败，这只盒子已经被打开了！';
		}

		$wecha_id = $this->wecha_id;
		$ip = get_client_ip();

		if (empty($wecha_id)) {
			$lists = M('Autumns_ip')->where(array('bid' => $bid, 'ip' => $ip, 'lid' => $id))->find();

			if ($lists) {
				$this->error('您已经帮忙拆过这个礼盒了哦！', U('Autumns/open', array('token' => $token, 'id' => $id, 'bid' => $bid)));
			}
		}
		else {
			$list = M('Autumns_ip')->where(array('bid' => $bid, 'lid' => $id, 'wecha_id' => $wecha_id))->find();

			if ($list) {
				$this->error('您已经帮忙拆过这个礼盒了哦！', U('Autumns/open', array('token' => $token, 'id' => $id, 'bid' => $bid, 'wecha_id' => $wecha_id)));
			}
		}

		if ($time < $optime) {
			$time++;
		}

		$data['time'] = $time;

		if (M('Autumns_open')->where(array('token' => $token, 'lid' => $id, 'bid' => $bid))->save($data)) {
			$result['err'] = 0;
			$result['info'] = '';
		}
		else {
			$result['err'] = 1;
			$result['info'] = '操作失败！';
		}

		$data['wecha_id'] = $wecha_id;
		$data['ip'] = $ip;
		$data['bid'] = $bid;
		$data['lid'] = $id;
		$data['token'] = $token;

		if (M('Autumns_ip')->add($data)) {
			$result['err'] = 0;
			$result['info'] = '你已经帮忙开启过这个礼盒了。';
		}

		exit(json_encode($result));
	}

	public function opbox()
	{
		$id = $this->_GET('id', 'intval');
		$token = $this->token;
		$wecha_id = $this->wecha_id;
		$bid = M('Lottery')->where(array('id' => $id, 'token' => $token))->getField('zjpic');

		if (!$bid) {
			$this->error('不存在的活动');
		}

		$info = $this->_post('info-prize2', 'intval');
		$Activity = M('Activity')->where(array('id' => $bid, 'token' => $token, 'type' => 1))->find();

		if (!$Activity) {
			$this->error('不存在的活动');
		}

		if ($Activity['enddate'] < time()) {
			$this->error('活动已结束');
		}

		if ($Activity['status'] == 0) {
			$this->error('活动已结束');
		}

		$xpic = M('Activity')->where(array('id' => $bid, 'token' => $token, 'type' => 1))->getField('xpic');
		$isprize = M('Autumns_box')->where(array('token' => $token, 'bid' => $id, 'wecha_id' => $wecha_id, 'id' => $info))->find();
		$data = date('Y-m-d H:i:s', $Activity['enddate']);

		if ($data < $isprize['prizedates']) {
			$isprize['prizedates'] = $data;
		}

		$prizes = M('Autumns_box')->where(array('token' => $token, 'bid' => $id, 'wecha_id' => $wecha_id, 'id' => $info))->getField('isprizes');
		$prize = M('Autumns_box')->where(array('token' => $token, 'bid' => $id, 'wecha_id' => $wecha_id, 'id' => $info))->getField('lvprize');
		$lvprize = M('Autumns_box')->where(array('id' => $info, 'bid' => $id, 'wecha_id' => $wecha_id))->getField('isprize');

		if ($lvprize != 1) {
			$this->error('亲，这个礼盒没有中奖啊！');
		}

		$lottery = M('Activity')->where(array('id' => $bid, 'token' => $token))->find();
		$renamesn = ($lottery['renamesn'] ? $lottery['renamesn'] : 'SN码');
		$this->assign('renamesn', $renamesn);
		$sn = M('Autumns_box')->where(array('token' => $token, 'bid' => $id, 'wecha_id' => $wecha_id, 'id' => $info))->getField('sn');
		$this->assign('linfo', $Activity);
		$this->assign('xpic', $xpic);
		$this->assign('sn', $sn);
		$this->assign('prize', $prize);
		$this->assign('isprize', $isprize);
		$this->assign('prizes', $prizes);
		$this->assign('info', $info);
		$this->display();
	}

	public function isprize()
	{
		if (IS_POST) {
			$id = $this->_GET('id', 'intval');
			$token = $this->token;
			$wecha_id = $this->wecha_id;
			$bid = M('Lottery')->where(array('id' => $id, 'token' => $token))->getField('zjpic');
			$info = $this->_post('info', 'intval');
			$date = M('Autumns_box')->where(array('id' => $info, 'bid' => $id, 'wecha_id' => $wecha_id))->getField('prizedates');
			$dates = date('Y-m-d H:i:s', time());

			if ($date < $dates) {
				$this->error('很遗憾，您的奖品已过期！');
			}

			$pass = $this->_POST('info-pass');
			$list = M('Activity')->where(array('token' => $token, 'id' => $bid))->getField('parssword');

			if ($pass == $list) {
				$prizee = M('Autumns_box');
				$data['isprizes'] = '1';
				$data['prtime'] = time();
				$prizee->where(array('id' => $info))->save($data);
				$this->success('兑奖成功！', U('Autumns/mybox', array('token' => $token, 'wecha_id' => $wecha_id, 'id' => $id)));
			}
			else {
				$this->error('兑奖密码错误！');
			}
		}
	}
}

?>
