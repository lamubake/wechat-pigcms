<?php
class SentimentAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction('Sentiment');
	}

	public function index()
	{
		$where['token'] = $this->token;
		$where_page['token'] = $this->token;

		if (!empty($_GET['name'])) {
			$where['title'] = array('like', '%' . $_GET['name'] . '%');
			$where_page['name'] = $_GET['name'];
		}

		import('ORG.Util.Page');
		$count = M('Sentiment')->where($where)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$list = M('Sentiment')->where($where)->order('addtime desc')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($list as $k => $v) {
			$share_count = 0;
			$user_list = M('Sentiment_user')->where(array('token' => $this->token, 'pid' => $v['id']))->field('share_num')->select();

			foreach ($user_list as $vo) {
				$share_count = $share_count + $vo['share_num'];
			}

			$list[$k]['share_count'] = $share_count;

			if ($v['is_open'] == 0) {
				if (time() < $v['start']) {
					$list[$k]['state'] = 1;
				}
				else if ($v['end'] < time()) {
					$list[$k]['state'] = 2;
				}
				else {
					$list[$k]['state'] = 3;
				}
			}
			else {
				$list[$k]['state'] = 0;
			}
		}

		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->display();
	}

	public function set()
	{
		if ($this->wxuser['oauth'] != 1) {
			$this->error('本活动必须开启网页授权！', U('User/Auth/index', array('token' => $this->token)));
		}
		else if ($this->wxuser['oauthinfo'] != 1) {
			$this->error('本活动必须选择获取昵称头像等所有信息！', U('User/Auth/index', array('token' => $this->token)));
		}

		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'id' => $id);
		$Sentiment = M('Sentiment')->where($where)->find();

		if (IS_POST) {
			$set['token'] = $this->token;
			$set['keyword'] = $_POST['keyword'];
			$set['reply_pic'] = $_POST['reply_pic'];
			$set['title'] = $_POST['title'];
			$set['fxtitle'] = $_POST['fxtitle'];
			$set['fxinfo'] = $_POST['fxinfo'];
			$set['intro'] = $_POST['intro'];
			$set['info'] = $_POST['info'];
			$set['is_sms'] = intval($_POST['is_sms']);
			$set['is_attention'] = intval($_POST['is_attention']);
			$set['is_reg'] = intval($_POST['is_reg']);
			$set['is_open'] = intval($_POST['is_open']);
			$set['start'] = strtotime($_POST['start']);
			$set['end'] = strtotime($_POST['end']);
			$set['is_nosex'] = intval($_POST['is_nosex']);
			$set['opposite_sex'] = $_POST['opposite_sex'];
			$set['same_sex'] = $_POST['same_sex'];
			$set['no_sex'] = $_POST['no_sex'];
			$set['man_label'] = implode(',', $_POST['man_label']);
			$set['woman_label'] = implode(',', $_POST['woman_label']);
			$set['name_zhi'] = $_POST['name_zhi'];
			$set['rank_num'] = intval($_POST['rank_num']);
			$news_imgurl = $_POST['news_imgurl'];
			$news_title = $_POST['news_title'];
			$news_url = $_POST['news_url'];
			$prize_title = $_POST['prize_title'];
			$prize_imgurl = $_POST['prize_imgurl'];
			$prize_num = $_POST['prize_num'];

			if ($Sentiment) {
				$del_news = M('Sentiment_news')->where(array('token' => $this->token, 'pid' => $id))->delete();
				$del_prize = M('Sentiment_prize')->where(array('token' => $this->token, 'pid' => $id))->delete();

				foreach ($news_imgurl as $nk => $nv) {
					$add_news['token'] = $this->token;
					$add_news['pid'] = $id;
					$add_news['imgurl'] = $nv;
					$add_news['title'] = $news_title[$nk];
					$add_news['url'] = $news_url[$nk];
					$id_news = M('Sentiment_news')->add($add_news);
				}

				foreach ($prize_title as $pk => $pv) {
					$add_prize['token'] = $this->token;
					$add_prize['pid'] = $id;
					$add_prize['title'] = $pv;
					$add_prize['imgurl'] = $prize_imgurl[$pk];
					$add_prize['num'] = $prize_num[$pk];
					$id_prize = M('Sentiment_prize')->add($add_prize);
				}

				$update_Sentiment = M('Sentiment')->where($where)->save($set);
				$this->handleKeyword($id, 'Sentiment', $this->_post('keyword', 'trim'));
				S($id . 'Sentiment' . $this->token, NULL);
				S($id . 'Sentiment' . $this->token . 'news', NULL);
				S($id . 'Sentiment' . $this->token . 'prize', NULL);
				$this->success('修改成功', U('Sentiment/index', array('token' => $this->token)));
			}
			else {
				$set['addtime'] = time();
				$id = M('Sentiment')->add($set);

				foreach ($news_imgurl as $nk => $nv) {
					$add_news['token'] = $this->token;
					$add_news['pid'] = $id;
					$add_news['imgurl'] = $nv;
					$add_news['title'] = $news_title[$nk];
					$add_news['url'] = $news_url[$nk];
					$id_news = M('Sentiment_news')->add($add_news);
				}

				foreach ($prize_title as $pk => $pv) {
					$add_prize['token'] = $this->token;
					$add_prize['pid'] = $id;
					$add_prize['title'] = $pv;
					$add_prize['imgurl'] = $prize_imgurl[$pk];
					$add_prize['num'] = $prize_num[$pk];
					$id_prize = M('Sentiment_prize')->add($add_prize);
				}

				$this->handleKeyword($id, 'Sentiment', $this->_post('keyword', 'trim'));
			$this->success('添加成功', U('Sentiment/index', array('token' => $this->token)));
			}
		}
		else {
			$this->assign('start_date', date('Y-m-d', time()));
			$this->assign('end_date', date('Y-m-d', strtotime('+1 month')));
			$this->assign('set', $Sentiment);
			$news_list = M('Sentiment_news')->where(array('token' => $this->token, 'pid' => $id))->order('id')->select();
			$prize_list = M('Sentiment_prize')->where(array('token' => $this->token, 'pid' => $id))->order('id')->select();
			$newsnum = count($news_list);
			$prizenum = count($prize_list);
			$man_label = explode(',', $Sentiment['man_label']);
			$woman_label = explode(',', $Sentiment['woman_label']);
			$this->assign('man_label', $man_label);
			$this->assign('woman_label', $woman_label);
			$this->assign('news_list', $news_list);
			$this->assign('prize_list', $prize_list);
			$this->assign('newsnum', $newsnum);
			$this->assign('prizenum', $prizenum);
			$this->display();
		}
	}

	public function del()
	{
		$id = $this->_get('id', 'intval');
		$keyword = M('Sentiment')->where(array('token' => $this->token, 'id' => $id))->getField('keyword');
		$this->handleKeyword($id, 'Sentiment', $keyword, 0, 1);

		if (M('Sentiment')->where(array('token' => $this->token, 'id' => $id))->delete()) {
			M('Sentiment_news')->where(array('token' => $this->token, 'pid' => $id))->delete();
			M('Sentiment_prize')->where(array('token' => $this->token, 'pid' => $id))->delete();
			$this->success('删除成功', U('Sentiment/index', array('token' => $this->token)));
		}
	}

	public function rank()
	{
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'pid' => $id, 'is_join' => 1);
		$count = M('Sentiment_user')->where($where)->count();
		$Page = new Page($count, 15);
		$list = M('Sentiment_user')->where($where)->order('help_count desc,addtime asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		foreach ($list as $key => $val) {
			$user_info = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $val['wecha_id']))->find();
			$list[$key]['nickname'] = $user_info['wechaname'] ? $user_info['wechaname'] : $user_info['truename'];
			$list[$key]['nickname'] = $list[$key]['nickname'] ? $list[$key]['nickname'] : '匿名';
			$list[$key]['mobile'] = $user_info['tel'] ? $user_info['tel'] : $val['tel'];
			$list[$key]['mobile'] = $list[$key]['mobile'] ? $list[$key]['mobile'] : '无';
		}

		$this->assign('list', $list);
		$this->assign('page', $Page->show());
		$this->display();
	}

	public function rank_del()
	{
		$id = $this->_get('id', 'intval');
		$user = M('Sentiment_user')->where(array('token' => $this->token, 'id' => $id))->find();
		M('sentiment_label')->where(array('token' => $this->token, 'pid' => $user['pid'], 'wecha_id' => $user['wecha_id']))->delete();
		M('sentiment_label_helps')->where(array('token' => $this->token, 'pid' => $user['pid'], 'label_wecha_id' => $user['wecha_id']))->delete();

		if (M('Sentiment_user')->where(array('token' => $this->token, 'id' => $id))->delete()) {
			$this->success('删除成功');
		}
	}
}

?>
