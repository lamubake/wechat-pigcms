<?php

class CashbonusAction extends WapAction
{
	public $packet_info;

	public function _initialize()
	{
		parent::_initialize();
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'is_open' => '1', 'id' => $id);
		$this->packet_info = M('Cashbonus')->where($where)->find();

		if (empty($this->packet_info)) {
			$this->error('活动还没有开启');
		}
		
		$this->assign('packet_info', $this->packet_info);
	}

	public function index()
	{
		
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'is_open' => '1', 'id' => $id);
		$packet = M('Cashbonus')->where($where)->find();
		$from = $this->_get('from', 'trim');
		if($packet['model']){
			if ($packet['model'] != $from) {
				$from = 2;
				$this->assign('from', $from);
			}else{
				$this->assign('from', $from);
			}
		}
		if (!$this->isSubscribe()) {
			$this->memberNotice('', 1);
		}
		
		$this->assign('is_start', $this->is_start($this->packet_info['id']));
		$this->display();
	}
	
	public function getPacket()
	{
		$result = array();
		$id = $this->_get('id', 'intval');

		if (empty($this->wecha_id)) {
			$result['err'] = 5;
			$result['msg'] = '请关注公众号“' . $this->wxuser['weixin'] . '”发送“' . $this->packet_info['keyword'] . '”再参加活动！';
			echo json_encode($result);
			exit();
		}
		
		if (!$this->isSubscribe()){
			$result['err'] = 5;
			$result['msg'] = '请先关注公众号“' . $this->wxuser['weixin'] . '”发送“' . $this->packet_info['keyword'] . '”再参加活动！';
			echo json_encode($result);
			exit();
		}

		if ($this->is_start() == 1) {
			$result['err'] = 1;
			$result['msg'] = '活动还没有开始，请耐心等待！';
			echo json_encode($result);
			exit();
		}

		if ($this->is_start() == 2) {
			$result['err'] = 2;
			$result['msg'] = '活动已经结束，敬请关注下一轮活动开始！';
			echo json_encode($result);
			exit();
		}

		$pwhere = array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'pid' => $id);
		$p_count = M('Cashbonus_log')->where($pwhere)->count();

		if ($this->packet_info['get_number'] <= $p_count) {
			$result['err'] = 3;
			$result['msg'] = '已经领过红包了';
			echo json_encode($result);
			exit();
		}

		if (!$this->check_packet_type()) {
			$result['err'] = 4;
			$result['msg'] = '红包已经领光啦，敬请关注下一轮活动开始！';
			echo json_encode($result);
			exit();
		}

		if ($this->packet_info['type'] == '1') {
			$max = $this->packet_info['item_max'];

			if ($this->packet_info['deci'] == 0) {
				$prize = mt_rand(1, $max);
			}
			else if ($this->packet_info['deci'] == 1) {
				$prize = mt_rand(10, $max * 10) / 10;
			}
			else if ($this->packet_info['deci'] == 2) {
				$prize = sprintf('%.2f', mt_rand(100, $max * 100) / 100);
			}

			$prize_name = $prize;
		}
		else if ($this->packet_info['type'] == '2') {
			$unit = $this->packet_info['item_unit'];
			$prize = $this->packet_info['item_unit'];
			$prize_name = $prize;
		}

		$result['err'] = 0;
		$result['msg'] = '恭喜您抽中了 ' . $prize_name . ' 元,快去我的红包查看吧！';
		$log = array();
		$log['token'] = $this->token;
		$log['wecha_id'] = $this->wecha_id;
		$log['pid'] = $id;
		$log['price'] = $prize;
		$log['add_time'] = time();
		$log['type'] = $this->packet_info['type'];
		$log['status'] = 0;
		$log_id = M('Cashbonus_log')->add($log);

		if ($log_id) {
			echo json_encode($result);
			exit();
		}
		else {
			$result['err'] = 5;
			$result['msg'] = '未知错误，请稍后再试';
			$result['type'] = $this->packet_info['type'];
			$result['prize'] = $prize;
			echo json_encode($result);
			exit();
		}
	}
	
	public function is_start($id)
	{
		$now = time();
		$is_start = 0;
		$where = array('token' => $this->token, 'pid' => $id);
		$pcount = M('Cashbonus_log')->where($where)->sum('num');

		if ($now < $this->packet_info['start_time']) {
			$is_start = 1;
		}
		else if ($this->packet_info['end_time'] < $now) {
			$is_start = 2;
		}
		else if (!$this->check_packet_type()) {
			$is_start = 3;
		}

		return $is_start;
	}

	public function check_packet_type()
	{
		$flag = true;
		$where = array('token' => $this->token, 'pid' => $this->packet_info['id']);
		$log = M('Cashbonus_log')->Distinct(true)->field('wecha_id')->where($where)->select();
		$pcount = count($log);
		if (($this->packet_info['people'] == 0) || ($pcount < $this->packet_info['people'])) {
				$sum = $this->packet_info['item_sum'];
				$lsum = M('Cashbonus_log')->where($where)->sum('price');

				if ($sum <= $lsum) {
					$flag = false;
				}
		}
		else {
			$flag = false;
		}

		return $flag;
	}
	
	public function my_packet()
	{

		$packet_id = $this->_get('id', 'intval');
		$wecha_id = $this->wecha_id;
		$where = array('token' => $this->token, 'pid' => $packet_id, 'wecha_id' => $wecha_id);
		$count = M('Cashbonus_log')->where($where)->count();
		$list = M('Cashbonus_log')->where($where)->order('add_time desc')->select();
			foreach ($list as $key => $val) {
				$info = M('Cashbonus')->where(array('token' => $this->token, 'id' => $val['pid']))->find();
				$list[$key]['name'] = $info['title'];
			}
		//dump($list);
		//die;
		$this->assign('list', $list);
		$this->display();
	}

	public function reward_sub()
	{
		$data = array();
		$result = array();
		$where = array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'pid' => $this->_get('id', 'intval'), 'status' => '0');

		if (!M('Cashbonus_log')->where($where)->find()) {
			$result['err'] = 1;
			$result['info'] = '给你玩玩就行了，还真使劲领啊？';
			echo json_encode($result);
			exit();
		}
		
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'pid' => $id, 'wecha_id'=>$this->wecha_id);
		$packet = M('Cashbonus_log')->where($where)->find();
		$pwhere = array('token' => $this->token, 'id' => $id);
		$Cashbonus = M('Cashbonus')->where($pwhere)->find();
		$config = array();
		$config['nick_name'] = $Cashbonus['send_name'];
		$config['send_name'] = $Cashbonus['send_name'];
		$config['wishing'] = $Cashbonus['wishing'];
		$config['act_name'] = $Cashbonus['act_name'];
		$config['remark'] = $Cashbonus['remark'];
		$config['token'] = $this->token;
		$config['openid'] = $this->wecha_id;
		$config['money'] = $packet['price'];
		$hb = new Hongbao($config);
		$result = json_decode($hb->send(), true);
		
		$result['err'] = 1;
		$result['info'] = $result['msg'];
		M('Cashbonus_log')->where($where)->save(array('status' => '1'));
		
		//$result['err'] = 1;
		//$result['info'] = '给你玩玩就行了，还真使劲领啊？？';
		echo json_encode($result);
			
		//echo json_encode($result);
	}
	
	public function rule()
	{
		$this->display();
	}

}

?>

