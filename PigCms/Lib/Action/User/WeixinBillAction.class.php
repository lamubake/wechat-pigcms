<?php

class WeixinBillAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();

		if (!$this->token) {
			exit();
		}
	}

	public function index()
	{
		$data_weixin_bill = D('Weixin_bill');

		if (IS_POST) {
			$key = (isset($_POST['key']) ? htmlspecialchars($_POST['key']) : 'orderid');
			$searchkey = (isset($_POST['searchkey']) ? htmlspecialchars($_POST['searchkey']) : '');
		}

		if ($key && $searchkey) {
			$where[$key] = array('like', '%' . $searchkey . '%');
			$this->assign('key', $key);
			$this->assign('searchkey', $searchkey);
		}

		$where['token'] = $this->token;
		$count = $data_weixin_bill->where($where)->count();
		$page = new Page($count, 25);
		$bill_list = $data_weixin_bill->where($where)->order('`imicms_id` DESC')->limit($page->firstRow . ',' . $page->listRows)->select();
		$bill_count = array('total' => 0, 'this' => 0, 'other' => 0, 'plat' => 0);

		if (!empty($bill_list)) {
			$plats = array('本账号', '平台账号', '其他账号');

			foreach ($bill_list as &$value) {
				$from = strtolower($value['from']);

				switch ($from) {
				case 'repast':
					$value['from'] = '餐饮';
					break;

				case 'store':
					$value['from'] = '商城';
					break;

				case 'hotels':
					$value['from'] = '酒店';
					break;

				case 'business':
					$value['from'] = '商业';
					break;

				case 'groupon':
					$value['from'] = '团购';
					break;

				case 'dishout':
					$value['from'] = '外卖';
					break;

				case 'business':
					$value['from'] = '行业应用';
					break;

				case 'card':
					$value['from'] = '会员卡充值';
					break;

				case 'medical':
					$value['from'] = '微医疗';
					break;

				case 'unitary':
					$value['from'] = '一元购';
					break;

				case 'livingcircle':
					$value['from'] = '生活圈';
					break;

				case 'bargain':
					$value['from'] = '砍价';
					break;

				case 'crowdfunding':
					$value['from'] = '众筹';
					break;

				case 'seckill':
					$value['from'] = '秒杀';
					break;

				case 'micrstore':
					$value['from'] = '微店';
					break;

				case 'drppayment':
					$value['from'] = '商城分销';
					break;

				case 'cutprice':
					$value['from'] = '降价拍';
					break;
				}

				$value['plat_type_name'] = $plats[$value['plat_type']];
				$value['price'] = str_replace(',','',($value['price']));//必须将字符串中的逗号去掉
				$bill_count['total'] += $value['price'];

				if ($value['plat_type'] == 0) {
					$bill_count['this'] += $value['price'];
				}
				else if ($value['plat_type'] == 1) {
					$bill_count['plat'] += $value['price'];
				}
				else if ($value['plat_type'] == 2) {
					$bill_count['other'] += $value['price'];
				}
			}
		}

		$total_bill = array();
		$total_list = $data_weixin_bill->field('FORMAT(sum(price), 2) as money, plat_type')->where($where)->group('`plat_type`')->select();
		foreach ($total_list as $tl) {
			$tl['money'] = str_replace(',','',($tl['money']));//必须将字符串中的逗号去掉
			$total_bill['total'] += $tl['money'];
			if ($tl['plat_type'] == 0) {
				$total_bill['this'] = $tl['money'];
				
			}
			else if ($tl['plat_type'] == 1) {
				$total_bill['plat'] = $tl['money'];
			}
			else if ($tl['plat_type'] == 2) {
				$total_bill['other'] = $tl['money'];
			}
		}
		$this->assign('page', $page->show());
		$this->assign('bill_list', $bill_list);
		$this->assign('bill_count', $bill_count);
		$this->assign('total_bill', $total_bill);
		$this->display();
	}
}

?>
