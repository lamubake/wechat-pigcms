<?php

class CardAction extends UserAction
{
	public $cats;
	public $game;

	public function _initialize()
	{
		parent::_initialize();
		$this->card = new card($this->token);
		$this->cats = $this->card->cardCats();
		$this->assign('cats', $this->cats);
	}

	public function index()
	{
		$where = array('token' => $this->token);
		$count = M('Cards')->where($where)->count();
		$Page = new Page($count, 15);
		$list = M('Cards')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('count', $count);
		$this->assign('page', $Page->show());
		$this->assign('list', $list);
		$this->display();
	}

	public function delCard()
	{
		$id = (isset($_GET['id']) ? intval($_GET['id']) : 0);
		$thisItem = M('Cards')->where(array('id' => $id, 'token' => $this->token))->find();
		$cardid = $thisItem['cardid'];
		$thisCard = $this->card->getCard(intval($cardid));
		$this->card->cardSelfSet($thisCard['id'], $id, '');
		M('Cards')->where(array('id' => $id, 'token' => $this->token))->delete();
		$this->handleKeyword($id, 'vCard', $data['keyword'], $precisions = 0, $delete = 1);
		$this->success('删除成功', U('Card/index'));
	}

	public function cardSet()
	{
		$id = (isset($_GET['id']) ? intval($_GET['id']) : 0);
		$this->assign('id', $id);

		if ($id) {
			$thisItem = M('cards')->where(array('id' => $id, 'token' => $this->token))->find();
			$cardid = $thisItem['cardid'];
		}
		else {
			$cardid = intval($_GET['cardid']);
		}

		$thisCard = $this->card->getCard(intval($cardid));
		$selfs = $this->card->cardSelfs($thisCard['id']);

		if (IS_POST) {
			$rt = $this->card->config($this->token, $this->wxuser['wxname'], $this->wxuser['wxid']);
			$data = array('token' => $this->token, 'title' => $this->_post('title'), 'intro' => $this->_post('intro'), 'keyword' => $this->_post('keyword'), 'picurl' => $this->_post('picurl'), 'time' => time(), 'cardid' => $thisCard['id']);
			$selfValues = array();
			$jsonStr = '{';

			if ($selfs) {
				$comma = '';

				foreach ($selfs as $s) {
					$selfValues['self_' . $s['id']] = $this->_post('self_' . $s['id']);
					$jsonStr .= $comma . '"self_' . $s['id'] . '":"' . $selfValues['self_' . $s['id']] . '"';
					$comma = ',';
				}
			}

			$jsonStr .= '}';
			$data['selfinfo'] = $jsonStr;

			if (!isset($_POST['id'])) {
				$usercardid = M('Cards')->add($data);
			}
			else {
				$usercardid = intval($_POST['id']);
				M('Cards')->where(array('id' => $usercardid))->save($data);
			}

			$this->handleKeyword($usercardid, 'vCard', $data['keyword'], $precisions = 0, $delete = 0);
			$this->card->cardSelfSet($thisCard['id'], $usercardid, $selfValues);
			$this->success('设置成功', U('Card/index'));
		}
		else {
			$this->assign('thisCard', $thisCard);

			if (!$id) {
				$thisItem = array();
				$thisItem['title'] = $thisCard['title'];
				$thisItem['intro'] = $thisCard['intro'];
				$thisItem['keyword'] = $thisCard['title'];
			}

			if ($id) {
				if ($selfs) {
					$selfValues = json_decode($thisItem['selfinfo'], 1);
					$i = 0;

					foreach ($selfs as $s) {
						$selfs[$i]['value'] = $selfValues['self_' . $s['id']];

						if ($selfs[$i]['value']) {
							$selfs[$i]['defaultvalue'] = $selfs[$i]['value'];
						}

						$i++;
					}
				}
			}

			$this->assign('selfs', $selfs);
			$this->assign('info', $thisItem);
			$this->display();
		}
	}

	public function cardLibrary()
	{
		if (isset($_GET['catid'])) {
			$catid = intval($_GET['catid']);

			foreach ($this->cats as $key => $val) {
				if (is_array($val['down'])) {
					foreach ($val['down'] as $kk => $vv) {
						if ($vv['cid'] == $catid) {
							$topid = $vv['top_id'];
						}
					}
				}
			}
		}
		else {
			$topid = $catid = 0;
		}

		$this->assign('topid', $topid);
		$cards = $this->card->cardList($catid);
		$this->assign('cards', $cards);
		$this->assign('catid', $catid);
		$this->display();
	}
}

?>
