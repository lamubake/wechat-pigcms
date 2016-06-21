<?php

class RecognitionDataAction extends UserAction
{
	public $is_info;
	public $is_ali;

	public function _initialize()
	{
		parent::_initialize();

		if (ALI_FUWU_GROUP) {
			$isgostr = '只有认证的服务号或者服务窗才可以使用！';
		}
		else {
			$isgostr = '只有认证的服务号才可以使用！';
		}

		if ((intval($this->wxuser['winxintype']) != 3) && ($this->wxuser['fuwuappid'] == '')) {
			$this->error($isgostr);
			exit();
		}

		if ($_GET['is_info'] == 'y') {
			session('is_info', 'y');
		}
		else if ($_GET['is_info'] == 'n') {
			session('is_info', NULL);
		}

		$this->is_info = session('is_info');
		$this->assign('is_info', $this->is_info);

		if (ALI_FUWU_GROUP) {
			if ($_GET['is_ali'] == 'y') {
				session('is_ali', 'y');
			}
			else if ($_GET['is_ali'] == 'n') {
				session('is_ali', NULL);
			}

			$this->is_ali = session('is_ali');
			$this->assign('is_ali', $this->is_ali);
		}
	}

	public function index()
	{
		if ($this->_get('month') == false) {
			$month = date('m');
		}
		else {
			$month = $this->_get('month');
		}

		$thisYear = date('Y');

		if ($this->_get('year') == false) {
			$year = $thisYear;
		}
		else {
			$year = $this->_get('year');
		}

		$this->assign('month', $month);
		$this->assign('year', $year);
		$is_info = $this->is_info;

		if ($_GET['name'] != '') {
			$user_wecha_id = M('userinfo')->where(array(
	'token'     => $this->token,
	'wechaname' => array('like', '%' . $_GET['name'] . '%')
	))->limit(1)->getField('wecha_id');
			$where = array('token' => $this->token, 'year' => $year, 'month' => $month, 'wecha_id' => $user_wecha_id);
		}
		else {
			$where = array('token' => $this->token, 'year' => $year, 'month' => $month);
		}

		if ($_GET['rid'] != '') {
			$where['rid'] = $_GET['rid'];
			$where_page['rid'] = $_GET['rid'];
		}

		if ($_GET['year'] != '') {
			$where_page['year'] = $_GET['year'];
		}

		if ($_GET['month'] != '') {
			$where_page['month'] = $_GET['month'];
		}

		if ($_GET['name'] != '') {
			$where_page['name'] = $_GET['name'];
		}

		$where['is_ali'] = 0;

		if (ALI_FUWU_GROUP) {
			if ($this->is_ali == 'y') {
				$where['is_ali'] = 1;
			}
		}

		if ($is_info == 'y') {
			$count = M('recognition_data')->where($where)->count();
			$page = new Page($count, 15);

			foreach ($where_page as $key => $val) {
				$page->parameter .= $key . '=' . urlencode($val) . '&';
			}

			$data = M('recognition_data')->where($where)->order('imicms_id desc')->limit($page->firstRow . ',' . $page->listRows)->select();
		}
		else {
			$sdata = M('recognition_data')->where($where)->order('imicms_id desc')->select();

			foreach ($sdata as $sv) {
				if (!in_array($sv['wecha_id'], $wecha_id_array)) {
					$wecha_id_array[] = $sv['wecha_id'];
					$sv['count'] = 1;
					$sdata_s[$sv['wecha_id']] = $sv;
				}
				else {
					$sdata_s[$sv['wecha_id']]['count']++;
				}
			}

			foreach ($sdata_s as $kv) {
				$sdata_s_k[] = $kv;
			}

			$count = count($sdata_s_k);
			$page = new Page($count, 15);

			foreach ($where_page as $key => $val) {
				$page->parameter .= $key . '=' . urlencode($val) . '&';
			}

			$i = $page->firstRow;

			for (; $i < ($page->firstRow + $page->listRows); $i++) {
				if ($sdata_s_k[$i] != '') {
					$data[] = $sdata_s_k[$i];
				}
			}
		}

		foreach ($data as $ik => $iv) {
			$data[$ik]['portrait'] = M('userinfo')->where(array('token' => $iv['token'], 'wecha_id' => $iv['wecha_id']))->getField('portrait');
			$data[$ik]['nickname'] = M('userinfo')->where(array('token' => $iv['token'], 'wecha_id' => $iv['wecha_id']))->getField('wechaname');
			$data[$ik]['rname'] = M('recognition')->where(array('token' => $iv['token'], 'id' => $iv['rid']))->getField('title');
		}

		$this->assign('page', $page->show());
		$this->assign('data', $data);

		if ($_GET['rid'] != '') {
			$rname = M('Recognition')->where(array('token' => $this->token, 'id' => $_GET['rid']))->getField('title');
			$this->assign('rname', $rname);
			$data_d = M('recognition_data')->where($where)->select();

			foreach ($data_d as $dv) {
				if (!in_array($dv['day'], $date_array_d)) {
					$date_array_d[] = $dv['day'];
					$dv_s['day'] = $dv['day'];
					$dv_s['count_d'] = 1;
					$data_s_s[$dv['day']] = $dv_s;
				}
				else {
					$data_s_s[$dv['day']]['count_d']++;
				}

				if (!in_array($dv['wecha_id'], $wecha_id_array2)) {
					$wecha_id_array2[] = $dv['wecha_id'];
					$data_p[$dv['wecha_id']] = $dv;
				}
			}

			foreach ($data_p as $pv) {
				if (!in_array($pv['day'], $date_array_p)) {
					$date_array_p[] = $pv['day'];
					$data_s_s[$pv['day']]['count_p'] = 1;
				}
				else {
					$data_s_s[$pv['day']]['count_p']++;
				}
			}

			function trim_map($val)
			{
				return rtrim($val, ',');
			}

			if ($data_s_s) {
				foreach ($data_s_s as $ssk => $ssv) {
					$charts['xAxis'] .= '"' . $ssv['day'] . '号",';
					$charts['cishu'] .= '"' . $ssv['count_d'] . '",';
					$charts['renshu'] .= '"' . $ssv['count_p'] . '",';
				}
			}
			else {
				$i = 0;

				for (; $i < 30; $i++) {
					$charts['xAxis'] .= '"' . ($i + 1) . '号",';
					$charts['cishu'] .= '"' . rand(1, 100) . '",';
					$charts['renshu'] .= '"' . rand(100, 300) . '",';
				}

				$charts['ifnull'] = 1;
			}

			$charts = array_map('trim_map', $charts);
			$this->assign('charts', $charts);
		}
		else {
			$Recognition = M('recognition')->where(array('token' => $this->token))->select();

			foreach ($Recognition as $vo1) {
				$num1[$vo1['id']]['num'] = 0;
				$num1[$vo1['id']]['title'] = $vo1['title'];
				$num1[$vo1['id']]['display'] = 'no';
				$num2[$vo1['id']]['num'] = 0;
				$num2[$vo1['id']]['title'] = $vo1['title'];
				$num2[$vo1['id']]['display'] = 'no';
			}

			$data111 = M('recognition_data')->where($where)->order('imicms_id')->select();

			foreach ($data111 as $vo2) {
				$num1[$vo2['rid']]['num']++;
				$num1[$vo2['rid']]['display'] = 'yes';
			}

			foreach ($data111 as $sv111) {
				if (!in_array($sv111['wecha_id'], $wecha_id_array111)) {
					$wecha_id_array111[] = $sv111['wecha_id'];
					$sv111['count'] = 1;
					$data222[$sv111['wecha_id']] = $sv111;
				}
				else {
					$data222[$sv111['wecha_id']]['count']++;
				}
			}

			foreach ($data222 as $vo3) {
				$num2[$vo3['rid']]['num']++;
				$num2[$vo3['rid']]['display'] = 'yes';
			}

			asort($num1);
			asort($num2);
			$num1num = 0;

			foreach ($num1 as $num1vo) {
				if ($num1vo['display'] == 'yes') {
					$num1num++;
				}
			}

			if (0 < $num1num) {
				foreach ($num1 as $v1) {
					$cishu['series'] .= '{value:' . $v1['num'] . ', name:\'' . $v1['title'] . '\'},';
				}
			}
			else {
				$cishu = array('ifnull' => 1, 'series' => '{value:' . rand(1, 100) . ', name:\'万能表单\'},{value:' . rand(1, 100) . ', name:\'商城\'},{value:' . rand(1, 100) . ', name:\'全景\'},{value:' . rand(1, 100) . ', name:\'关注\'},{value:' . rand(1, 100) . ', name:\'文本请求\'},{value:' . rand(1, 100) . ', name:\'图文消息\'},{value:' . rand(1, 100) . ', name:\'通用订单\'},{value:' . rand(1, 100) . ', name:\'投票\'},{value:' . rand(1, 100) . ', name:\'婚庆喜帖\'},{value:' . rand(1, 100) . ', name:\'会员卡\'},{value:' . rand(1, 100) . ', name:\'推广活动\'}');
			}

			$num2num = 0;

			foreach ($num2 as $num2vo) {
				if ($num2vo['display'] == 'yes') {
					$num2num++;
				}
			}

			if (0 < $num2num) {
				foreach ($num2 as $v2) {
					$renshu['series'] .= '{value:' . $v2['num'] . ', name:\'' . $v2['title'] . '\'},';
				}
			}
			else {
				$renshu = array('ifnull' => 1, 'series' => '{value:' . rand(1, 100) . ', name:\'万能表单\'},{value:' . rand(1, 100) . ', name:\'商城\'},{value:' . rand(1, 100) . ', name:\'全景\'},{value:' . rand(1, 100) . ', name:\'关注\'},{value:' . rand(1, 100) . ', name:\'文本请求\'},{value:' . rand(1, 100) . ', name:\'图文消息\'},{value:' . rand(1, 100) . ', name:\'通用订单\'},{value:' . rand(1, 100) . ', name:\'投票\'},{value:' . rand(1, 100) . ', name:\'婚庆喜帖\'},{value:' . rand(1, 100) . ', name:\'会员卡\'},{value:' . rand(1, 100) . ', name:\'推广活动\'}');
			}

			$this->assign('cishu', $cishu);
			$this->assign('renshu', $renshu);
		}

		$this->display();
	}
}

?>
