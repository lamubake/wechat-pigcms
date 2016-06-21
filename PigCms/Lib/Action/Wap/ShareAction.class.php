<?php

class ShareAction extends WapAction
{
	public function __construct()
	{
		parent::_initialize();
	}

	public function shareData()
	{
		if (isset($_POST['wecha_id']) || isset($_GET['wecha_id'])) {
			$row = array();
			$row['token'] = $this->token;
			$row['wecha_id'] = $this->wecha_id;
			$row['to'] = $this->_post('to') ? $this->_post('to') : $this->_get('to');
			$row['module'] = $this->_post('module') ? $this->_post('module') : $this->_get('module');
			$row['moduleid'] = intval($this->_post('moduleid')) ? intval($this->_post('moduleid')) : intval($this->_get('moduleid'));
			$row['time'] = time();

			if ($this->_post('url')) {
				$row['url'] = $this->_post('url');
			}

			F('s', $row);
			S('s', $row);
			M('share')->add($row);
			$shareSet = M('Share_set')->where(array('token' => $this->token))->find();

			if ($shareSet) {
				$row2 = array();
				$row2['token'] = $this->token;
				$row2['wecha_id'] = $this->wecha_id;
				$where = array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cat' => 98);
				$now = time();
				$year = date('Y', $now);
				$month = date('m', $now);
				$day = date('d', $now);
				$firstSecond = mktime(0, 0, 0, $month, $day, $year);
				$where['time'] = array('gt', $firstSecond);
				$recordsCount = M('Member_card_use_record')->where($where)->count();

				if ($recordsCount < $shareSet['daylimit']) {
					$row2['expense'] = 0;
					$row2['time'] = $now;
					$row2['cat'] = 98;
					$row2['staffid'] = 0;
					$row2['score'] = intval($shareSet['score']);
					M('Member_card_use_record')->add($row2);
					M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->setInc('total_score', $row2['score']);
				}
			}
		}
	}

	public function ShareNum()
	{
		$ShareNumData = explode(',', $_POST['ShareNumData']);
		$table = D($ShareNumData[0]);
		$tableWhere = explode(';', $ShareNumData[1]);
		$tableWhereData = explode(';', $ShareNumData[2]);

		foreach ($tableWhere as $tk => $to) {
			$where[$to] = $tableWhereData[$tk];
		}

		$where['token'] = $_POST['token'] ? $_POST['token'] : $_GET['token'];
		$table_up = $table->where($where)->setInc($ShareNumData[3]);
		$data['error'] = 0;
		$this->ajaxReturn($data, 'JSON');
	}
}

?>
