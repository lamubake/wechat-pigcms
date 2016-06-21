<?php

class SeckillAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		$type = "Seckill";
		$this->assign("type", $type);
		$this->canUseFunction($type);
	}

	public function index()
	{
		$data = D("seckill_action");
		$type = filter_var($this->_get("type"), FILTER_SANITIZE_STRING);
		$where = array("action_token" => session("token"));
		$count = $data->where($where)->count();
		$Page = new Page($count, 20);
		$show = $Page->show();
		$patterns = array();
		$patterns[0] = "/m=Seckill/";
		$replacements = array();
		$replacements[0] = "m=Seckill&type=$type";
		$show = preg_replace($patterns, $replacements, $show);
		$busines = $data->where($where)->limit($Page->firstRow . "," . $Page->listRows)->order("action_id desc")->select();
		$this->assign("page", $show);
		$this->assign("action", $busines);
		$this->assign("token", $this->token);
		$this->display();
	}

	public function shop_detail()
	{
		$data = D("seckill_base_shop");
		$type = filter_var($this->_get("type"), FILTER_SANITIZE_STRING);
		$where = array("action_id" => (int) $_GET["aid"]);
		$count = $data->where($where)->count();
		$Page = new Page($count, 20);
		$show = $Page->show();
		$patterns = array();
		$patterns[0] = "/m=Seckill/";
		$replacements = array();
		$replacements[0] = "m=Seckill&type=$type";
		$show = preg_replace($patterns, $replacements, $show);
		$busines = $data->where($where)->limit($Page->firstRow . "," . $Page->listRows)->select();
		$this->assign("page", $show);
		$this->assign("action", $busines);
		$this->assign("token", $this->token);
		$this->display();
	}

	public function action_add()
	{
		$keyword_model = M("Keyword");
		$action = M("seckill_action");
		if (isset($_GET["id"]) && !empty($_GET["id"])) {
			$result = $action->where(array("action_id" => (int) $_GET["id"]))->find();

			if ($result) {
				$this->assign("op", "修改");
				$this->assign($result);
			}
			else {
				$this->assign("op", "添加");
			}
		}
		else {
			$this->assign("op", "添加");
		}

		if (IS_POST) {
			if (!is_numeric($_POST["share_time_min"])) {
				$this->error("分享时间请填写数字类型！");
				exit();
			}
			else if (!is_numeric($_POST["share_time_max"])) {
				$this->error("分享时间请填写数字类型！");
				exit();
			}

			$sdate = strtotime($_POST["action_sdate"] . " " . $_POST["action_sdate_h"] . ":" . $_POST["action_sdate_i"]);
			$edate = strtotime($_POST["action_edate"] . " " . $_POST["action_edate_h"] . ":" . $_POST["action_edate_i"]);

			if ($edate < $sdate) {
				$this->error("活动结束时间小于开始时间！");
				exit();
			}

			if (isset($_POST["id"]) && !empty($_POST["id"])) {
				$data = array("action_id" => (int) $_POST["id"], "action_name" => $_POST["action_name"], "action_sdate" => strtotime($_POST["action_sdate"] . " " . $_POST["action_sdate_h"] . ":" . $_POST["action_sdate_i"]), "action_edate" => strtotime($_POST["action_edate"] . " " . $_POST["action_edate_h"] . ":" . $_POST["action_edate_i"]), "rand_min_time" => $_POST["share_time_min"], "rand_max_time" => $_POST["share_time_max"], "action_rule" => $_POST["action_rule"], "action_token" => $this->token, "action_is_attention" => $_POST["action_is_attention"], "action_is_reg" => $_POST["action_is_reg"], "action_open" => $_POST["action_open"], "action_header_img" => $_POST["action_header_img"], "reply_title" => $_POST["reply_title"], "reply_content" => $_POST["reply_content"], "reply_pic" => $_POST["reply_pic"], "reply_keyword" => $_POST["reply_keyword"]);
				$this->handleKeyword($this->_post("id", "intval"), "Seckill", $this->_post("reply_keyword", "trim"));
				if ($action->save($data) || $info) {
					$this->success("修改成功", U("index", array("token" => session("token"), "type" => "seckill")));
					exit();
				}
				else {
					$this->error("无修改", U("index", array("token" => session("token"), "type" => "seckill")));
					exit();
				}
			}
			else {
				$action->create();
				$action->action_sdate = strtotime($_POST["action_sdate"] . " " . $_POST["action_sdate_h"] . ":" . $_POST["action_sdate_i"]);
				$action->action_edate = strtotime($_POST["action_edate"] . " " . $_POST["action_edate_h"] . ":" . $_POST["action_edate_i"]);
				$action->action_key = $this->get_key();
				$action->rand_min_time = $_POST["share_time_min"];
				$action->rand_max_time = $_POST["share_time_max"];
				$action->action_token = $this->token;
				$action->action_is_attention = $_POST["action_is_attention"];
				$action->action_is_reg = $_POST["action_is_reg"];
				$action->action_open = $_POST["action_open"];

				if ($res = $action->add()) {
					$this->handleKeyword($res, "Seckill", $this->_post("reply_keyword", "trim"));
					$this->success("添加成功", U("index", array("token" => session("token"), "type" => "seckill")));
					exit();
				}
			}
		}

		$this->assign("now", time());
		$this->display();
	}

	public function action_del()
	{
		$id = (int) $_GET["id"];
		$token = session("token");

		if (M("seckill_action")->where(array("action_id" => $id, "action_token" => $token))->delete()) {
			$this->handleKeyword(intval($id), "Seckill", "", "", 1);
			$base = M("seckill_base_shop");
			$thum = M("seckill_shop_thum");
			$arr = $base->field("shop_id")->where(array("action_id" => $id))->select();

			foreach ($arr as $value ) {
				$thum->where(array("shop_id" => $value["shop_id"]))->delete();
			}

			$base->where(array("action_id" => $id))->delete();
			$this->success("删除活动成功！", U("index", array("token" => session("token"), "type" => "seckill")));
		}
	}

	public function shop_add()
	{
		$action = M("seckill_base_shop");
		if (isset($_GET["sid"]) && !empty($_GET["sid"])) {
			$result = $action->where(array("shop_id" => (int) $_GET["sid"]))->find();

			if ($result) {
				$tdata = M("seckill_shop_thum")->where(array("shop_id" => $_GET["sid"]))->select();
				$this->assign("op", "修改");
				$this->assign($result);
				$this->assign("tdata", $tdata);
			}
			else {
				$this->assign("op", "添加");
			}
		}
		else {
			$this->assign("op", "添加");
		}

		if (IS_POST) {
			if (!is_numeric($_POST["shop_num"])) {
				$this->error("商品库存只能为数字类型！");
				exit();
			}
			else if (!is_numeric($_POST["shop_price"])) {
				$this->error("商品价格是数字类型！");
				exit();
			}
			else if (!is_numeric($_POST["shop_tran"])) {
				$this->error("商品运费是数字类型！");
				exit();
			}

			if (isset($_POST["sid"]) && !empty($_POST["sid"])) {
				$data1 = array("shop_id" => $_POST["sid"], "action_id" => $_POST["aid"], "shop_name" => $_POST["shop_name"], "shop_num" => $_POST["shop_num"], "shop_price" => $_POST["shop_price"], "shop_tran" => $_POST["shop_tran"], "shop_open" => $_POST["shop_open"], "shop_detail" => $_POST["shop_detail"], "shop_img" => $_POST["shop_big"][0]);
				$info = M("seckill_shop_thum")->field("id")->where(array("shop_id" => $_POST["sid"]))->select();

				foreach ($_POST["shop_big"] as $key => $value ) {
					$data2 = array("shop_thum" => $value, "shop_big" => $value);

					if (!empty($info[$key])) {
						$res[] = M("seckill_shop_thum")->where(array("id" => $info[$key]["id"]))->save($data2);
					}
					else {
						$data2["shop_id"] = (int) $_POST["sid"];
						$res[] = M("seckill_shop_thum")->add($data2);
					}
				}

				if ($action->save($data1) || $res[0] || $res[1]) {
					$this->success("修改成功", U("shop_detail", array("token" => session("token"), "type" => "seckill", "aid" => $_POST["aid"])));
					exit();
				}
				else {
					$this->error("无修改", U("shop_detail", array("token" => session("token"), "type" => "seckill", "aid" => $_POST["aid"])));
					exit();
				}
			}
			else {
				$_POST["shop_img"] = $_POST["shop_big"][0];
				$action->create();
				$action->action_id = $_POST["aid"];

				if ($sid = $action->add()) {
					foreach ($_POST["shop_big"] as $value ) {
						$data2 = array("shop_id" => $sid, "shop_thum" => $value, "shop_big" => $value);
						M("seckill_shop_thum")->add($data2);
					}

					$this->success("添加成功", U("shop_detail", array("token" => session("token"), "type" => "seckill", "aid" => $_POST["aid"])));
					exit();
				}
			}
		}

		$this->display();
	}

	public function shop_del()
	{
		$id = (int) $_GET["sid"];
		$aid = (int) $_GET["aid"];
		$token = M("seckill_action")->where(array("action_id" => $aid))->getField("action_token");
		$action_id = M("seckill_base_shop")->where(array("shop_id" => $id))->getField("action_id");
		if (($token != session("token")) || ($action_id != $aid)) {
			$this->error("参数错误！");
			exit();
		}

		if (M("seckill_base_shop")->delete($id)) {
			$thum = M("seckill_shop_thum");
			$thum->where(array("shop_id" => $id))->delete();
			$this->success("删除商品成功！", U("shop_detail", array("token" => session("token"), "type" => "seckill", "aid" => $aid)));
		}
	}

	public function order()
	{
		$data = D("seckill_action");
		$type = filter_var($this->_get("type"), FILTER_SANITIZE_STRING);
		$where = array("action_token" => session("token"));
		$count = $data->where($where)->count();
		$Page = new Page($count, 20);
		$show = $Page->show();
		$patterns = array();
		$patterns[0] = "/m=Seckill/";
		$replacements = array();
		$replacements[0] = "m=Seckill&type=$type";
		$show = preg_replace($patterns, $replacements, $show);
		$busines = $data->where($where)->limit($Page->firstRow . "," . $Page->listRows)->select();
		$this->assign("page", $show);
		$this->assign("action", $busines);
		$this->assign("token", $this->token);
		$this->display();
	}

	public function order_man()
	{
		$aid = $_GET["aid"];
		$model = new Model();
		$data = M("seckill_book");
		$type = filter_var($this->_get("type"), FILTER_SANITIZE_STRING);
		$where = "select count(*) as cc from " . C("DB_PREFIX") . "seckill_base_shop as a left join " . C("DB_PREFIX") . "seckill_book as b on a.shop_id = b.book_sid where b.token = '$this->token' and b.book_aid = $aid";
		$count = $model->query($where);
		$Page = new Page($count[0]["cc"], 20);
		$show = $Page->show();
		$patterns = array();
		$patterns[0] = "/m=Seckill/";
		$replacements = array();
		$replacements[0] = "m=Seckill&type=$type";
		$show = preg_replace($patterns, $replacements, $show);
		$sql = "select a.shop_name ,a.shop_id ,a.shop_price, a.shop_img, b.orderid, b.paid, b.price,b.true_name,b.paytype,b.book_id,b.book_time from " . C("DB_PREFIX") . "seckill_base_shop as a left join " . C("DB_PREFIX") . "seckill_book as b on a.shop_id = b.book_sid where b.token = '$this->token' and b.book_aid = $aid order by b.paid , b.book_time desc limit $Page->firstRow,$Page->listRows";
		$busines = $model->query($sql);

		foreach ($busines as $key => $val ) {
			$paytype = $this->get_paytype($val["paytype"]);
			$busines[$key]["ptype"] = $paytype;
		}

		$this->assign("page", $show);
		$this->assign("book", $busines);
		$this->assign("token", $this->token);
		$this->display();
	}

	public function order_detail()
	{
		$bid = $_GET["bid"];
		$book = M("seckill_book");
		$sql = "select a.*, b.* from " . C("DB_PREFIX") . "seckill_book as a left join " . C("DB_PREFIX") . "seckill_base_shop as b on a.book_sid = b.shop_id where a.book_id = $bid";
		$Model = new Model();
		$data = $Model->query($sql);
		$type = $this->get_paytype($data[0]["paytype"]);
		$this->assign("type", $type);
		$this->assign($data[0]);
		$this->display();
	}

	public function paid()
	{
		if (IS_POST) {
			$paid = (int) $_POST["paid"];
			$book_id = (int) $_POST["book_id"];
			$data = array("book_id" => $book_id, "paid" => $paid);

			if (M("seckill_book")->save($data)) {
				echo json_encode(array("status" => 1));
				exit();
			}
			else {
				echo json_encode(array("status" => 0));
				exit();
			}
		}
	}

	private function get_key($length)
	{
		$str = substr(md5(time() . mt_rand(1000, 9999)), 0, $length);
		return $str;
	}

	private function get_paytype($paytype)
	{
		$type = "";

		switch ($paytype) {
		case "alipay":
			$type = "支付宝";
			break;

		case "weixin":
			$type = "微信支付";
			break;

		case "tenpay":
			$type = "财付通[wap手机]";
			break;

		case "tenpayComputer":
			$type = "财付通[即时到帐]";
			break;

		case "yeepay":
			$type = "易宝支付";
			break;

		case "allinpay":
			$type = "通联支付";
			break;

		case "daofu":
			$type = "货到付款";
			break;

		case "dianfu":
			$type = "到店付款";
			break;

		case "chinabank":
			$type = "网银在线";
			break;

		case "CardPay":
			$type = "会员卡支付";
			break;

		default:
			break;
		}

		return $type;
	}
}


?>
