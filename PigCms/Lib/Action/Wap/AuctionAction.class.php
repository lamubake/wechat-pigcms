<?php

class AuctionAction extends WapAction
{
	public function _initialize()
	{
		parent::_initialize();
		
	}

	public function index()
	{
		$id = intval($_GET["id"]);
		$auction = M("auction")->where(array("token" => $this->token, "id" => $id))->find();

		if ($auction == "") {
			$this->error("此商品不存在");
			exit();
		}

		if ($auction["is_open"] == 1) {
			$this->error("此商品已关闭");
			exit();
		}

		if ($auction["is_del"] == 1) {
			$this->error("此商品已被商家删除");
			exit();
		}

		if (($auction["is_attention"] == 1) && !$this->isSubscribe()) {
			$this->memberNotice("", 1);
		}
		else {
			if (($auction["is_reg"] == 1) && empty($this->fans["tel"])) {
				$this->memberNotice();
			}
		}

		M("auction")->where(array("token" => $this->token, "id" => $id))->setInc("pv", 1);

		if (time() < $auction["start"]) {
			$auction["status"] = 1;
		}
		else {
			if (($auction["end"] < time()) || ($auction["state"] != 0)) {
				$auction["status"] = 2;
				$lastprice = M("auction_toprice")->where(array("token" => $this->token, "pid" => $id))->order("price desc")->find();
				$auction["endtime"] = $lastprice["addtime"];
				if (($auction["end"] < time()) && ($lastprice != NULL) && ($lastprice["time"] < $auction["end"])) {
					M("auction_toprice")->where(array("token" => $this->token, "pid" => $id, "id" => $lastprice["id"]))->save(array("time" => $auction["end"]));
				}
			}
			else {
				$auction["status"] = 0;
			}
		}

		$this->assign("auction", $auction);
		$pic_list = M("auction_pic")->where(array("token" => $this->token, "pid" => $id))->order("id")->select();
		$this->assign("pic_list", $pic_list);
		$this->assign("fans", M("userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->find());
		$toprice_list = M("auction_toprice")->where(array("token" => $this->token, "pid" => $id))->order("price desc")->select();

		foreach ($toprice_list as $pk => $pv ) {
			$userinfo = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $pv["wecha_id"]))->find();
			$toprice_list[$pk]["portrait"] = $userinfo["portrait"];
			$toprice_list[$pk]["wechaname"] = ($userinfo["wechaname"] ? $userinfo["wechaname"] : "匿名");
		}

		$this->assign("toprice_list", $toprice_list);
		$this->display();
	}

	public function toprice()
	{
		if (IS_POST) {
			$lastprice = M("auction_toprice")->where(array("token" => $_POST["token"], "pid" => $_POST["id"]))->order("price desc")->find();
			$auction = M("auction")->where(array("token" => $this->token, "id" => $_POST["id"]))->find();

			if ($_POST["price"] < $lastprice["price"]) {
				$data["error"] = 1;
				$data["msg"] = "有人价格超过你了";
			}
			else if ($_POST["price"] == $lastprice["price"]) {
				$data["error"] = 1;
				$data["msg"] = "此价格有人先出了";
			}
			else if ($auction["state"] == 1) {
				$data["error"] = 1;
				$data["msg"] = "竞拍已结束";
			}
			else {
				$add_toprice["token"] = $_POST["token"];
				$add_toprice["wecha_id"] = $_POST["wecha_id"];
				$add_toprice["pid"] = $_POST["id"];
				$add_toprice["price"] = $_POST["price"];
				$add_toprice["addtime"] = time();
				$add_toprice["time"] = time();
				$id_toprice = M("auction_toprice")->add($add_toprice);
				if ((0 < $auction["fixedprice"]) && ($auction["fixedprice"] <= $_POST["price"])) {
					M("auction")->where(array("token" => $this->token, "id" => $_POST["id"]))->save(array("state" => 1));
				}

				$data["error"] = 0;
			}

			$this->ajaxReturn($data, "JSON");
		}
		else {
			$this->success("出价成功", U("Auction/index", array("token" => $this->token, "id" => $_GET["id"])));
		}
	}

	public function lastprice()
	{
		if (IS_POST) {
			$lastprice = M("auction_toprice")->where(array("token" => $_POST["token"], "pid" => $_POST["id"]))->order("price desc")->find();
			$auction = M("auction")->where(array("token" => $_POST["token"], "id" => $_POST["id"]))->find();
			$data["error"] = 0;
			$data["lastprice"] = ($lastprice["price"] ? $lastprice["price"] : $auction["startprice"]);
			$this->ajaxReturn($data, "JSON");
		}
	}

	public function address()
	{
		$userinfo = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->find();
		$this->assign("userinfo", $userinfo);
		$this->display();
	}

	public function dobuy()
	{
		M("userinfo")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id))->save(array("truename" => $_GET["name"], "tel" => $_GET["phone"], "address" => $_GET["address"]));
		$id_order = M("auction_order")->add(array("token" => $this->token, "wecha_id" => $this->wecha_id, "name" => $_GET["name"], "tel" => $_GET["phone"], "address" => $_GET["address"], "price" => $_GET["price"] + $_GET["postage"], "auctionid" => $_GET["id"], "topriceid" => $_GET["topriceid"], "addtime" => time()));
		$orderid = $id_order . "AUCTION" . time();
		M("auction_order")->where(array("id" => $id_order))->save(array("orderid" => $orderid));
		M("auction_toprice")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "pid" => $_GET["id"], "id" => $_GET["topriceid"]))->save(array("orderid" => $id_order, "time" => time()));
		$this->redirect("Alipay/pay", array("token" => $this->token, "price" => $_GET["price"] + $_GET["postage"], "wecha_id" => $this->wecha_id, "from" => "Auction", "orderid" => $orderid, "single_orderid" => $orderid, "notOffline" => 1));
	}

	public function payReturn()
	{
		$order = M("auction_order")->where(array("token" => $this->token, "orderid" => $_GET["orderid"]))->find();

		if ($order["thirdpay"] == 1) {
			$this->success("支付成功", U("Auction/order", array("token" => $this->token)));
		}
		else {
			ThirdPayAuction::index($_GET["orderid"], $order["paytype"], $order["third_id"]);
			$this->success("支付成功", U("Auction/order", array("token" => $this->token)));
		}
	}

	public function order()
	{
		if (($_GET["type"] == "") || ($_GET["type"] == 1)) {
			$toprice_list = M("auction_toprice")->where(array("token" => $this->token, "wecha_id" => $this->wecha_id, "orderid" => 0))->select();
			$nopay = array();

			foreach ($toprice_list as $tv ) {
				$lastprice = M("auction_toprice")->where(array("token" => $this->token, "pid" => $tv["pid"]))->order("price desc")->find();
				$auction = M("auction")->where(array("token" => $this->token, "id" => $tv["pid"]))->find();
				if (($lastprice["price"] == $tv["price"]) && (($auction["end"] < time()) || ($auction["state"] == 1))) {
					if (($auction["end"] < time()) && ($tv["time"] < $auction["end"])) {
						$tv["time"] = $auction["end"];
						M("auction_toprice")->where(array("token" => $this->token, "id" => $tv["id"]))->save(array("time" => $auction["end"]));
					}

					$nopay[] = $tv;
				}
			}

			foreach ($nopay as $nk => $nv ) {
				$nopay[$nk]["auction"] = M("auction")->where(array("token" => $this->token, "id" => $nv["pid"]))->find();
			}

			foreach ($nopay as $nko => $nvo ) {
				if (($nvo["auction"]["nobuytime"] * 60 * 60) < (time() - $nvo["time"])) {
					$nopay[$nko]["state"] = 1;
				}
				else {
					$nopay[$nko]["state"] = 0;
				}
			}
		}

		$where_order["token"] = $this->token;
		$where_order["wecha_id"] = $this->wecha_id;

		if ($_GET["type"] == 1) {
			$where_order["paid"] = 0;
		}
		else if ($_GET["type"] == 2) {
			$where_order["paid"] = 1;
			$where_order["state"] = 0;
		}
		else if ($_GET["type"] == 3) {
			$where_order["paid"] = 1;
			$where_order["state"] = 1;
		}
		else if ($_GET["type"] == 4) {
			$where_order["paid"] = 1;
			$where_order["state"] = 2;
		}

		$order_list = M("auction_order")->where($where_order)->order("paid,state")->select();

		foreach ($order_list as $ok => $ov ) {
			$order_list[$ok]["auction"] = M("auction")->where(array("token" => $this->token, "id" => $ov["auctionid"]))->find();
			$order_list[$ok]["auction_toprice"] = M("auction_toprice")->where(array("token" => $this->token, "id" => $ov["topriceid"]))->find();

			if ($ov["paid"] == 0) {
				$order_list[$ok]["status"] = 1;
			}
			else {
				if (($ov["paid"] == 1) && ($ov["state"] == 0)) {
					$order_list[$ok]["status"] = 2;
				}
				else {
					if (($ov["paid"] == 1) && ($ov["state"] == 1)) {
						$order_list[$ok]["status"] = 3;
					}
					else {
						if (($ov["paid"] == 1) && ($ov["state"] == 2)) {
							$order_list[$ok]["status"] = 4;
						}
					}
				}
			}
		}

		foreach ($order_list as $oko => $ovo ) {
			if ((($ovo["auction"]["nobuytime"] * 60 * 60) < (time() - $ovo["auction_toprice"]["time"])) && ($ovo["paid"] == 0)) {
				$order_list[$oko]["auction_toprice"]["state"] = 1;
			}
			else {
				$order_list[$oko]["auction_toprice"]["state"] = 0;
			}
		}

		$this->assign("nopay", $nopay);
		$this->assign("order_list", $order_list);
		$this->display();
	}

	public function orderbuy()
	{
		$order = M("auction_order")->where(array("token" => $this->token, "id" => $_GET["orderid"]))->find();
		$this->redirect("Alipay/pay", array("token" => $this->token, "price" => $order["price"], "wecha_id" => $order["wecha_id"], "from" => "Auction", "orderid" => $order["orderid"], "single_orderid" => $order["orderid"], "notOffline" => 1));
	}

	public function set()
	{
		$id = intval($_GET["id"]);

		switch ($_GET["type"]) {
		case "toprice":
			$toprice = M("auction_toprice")->where(array("token" => $this->token, "id" => $id))->find();
			M("auction")->where(array("token" => $this->token, "id" => $toprice["pid"]))->save(array("state" => 0));
			$auction = M("auction")->where(array("token" => $this->token, "id" => $toprice["pid"]))->find();
			M("auction_toprice")->where(array("token" => $this->token, "id" => $id))->delete();

			if ($auction["end"] < time()) {
				$lastprice_two = M("auction_toprice")->where(array("token" => $this->token, "pid" => $toprice["pid"]))->order("price desc")->find();
				$userinfo = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $lastprice_two["wecha_id"]))->find();
				$auction_orderid = 10000000 + $auction["id"];
				$auction_orderid = substr($auction_orderid, 1);
				$auction_orderid = date("Ymd") . $auction_orderid;
				$model = new templateNews();
				$model->sendTempMsg("TM00184", array("href" => $this->siteUrl . U("Wap/Auction/order", array("token" => $this->token)), "wecha_id" => $lastprice_two["wecha_id"], "first" => "尊敬的【" . $userinfo["wechaname"] . "】:您的“" . $goodsname . "”拍品已竞拍成功，若超过" . $auction["nobuytime"] . "小时未付款系统将自动删除此次竞拍。", "ordertape" => date("Y年m月d日H时i分s秒"), "ordeID" => $auction_orderid, "remark" => ""));
			}

			$this->success("删除成功", U("Auction/order", array("token" => $this->token)));
			break;

		case "order":
			$order = M("auction_order")->where(array("token" => $this->token, "id" => $id))->find();
			M("auction")->where(array("token" => $this->token, "id" => $order["auctionid"]))->save(array("state" => 0));
			$auction = M("auction")->where(array("token" => $this->token, "id" => $order["auctionid"]))->find();
			M("auction_toprice")->where(array("token" => $this->token, "id" => $order["topriceid"]))->delete();
			M("auction_order")->where(array("token" => $this->token, "id" => $id))->delete();

			if ($auction["end"] < time()) {
				$lastprice_two = M("auction_toprice")->where(array("token" => $this->token, "pid" => $order["auctionid"]))->order("price desc")->find();
				$userinfo = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $lastprice_two["wecha_id"]))->find();
				$auction_orderid = 10000000 + $auction["id"];
				$auction_orderid = substr($auction_orderid, 1);
				$auction_orderid = date("Ymd") . $auction_orderid;
				$model = new templateNews();
				$model->sendTempMsg("TM00184", array("href" => $this->siteUrl . U("Wap/Auction/order", array("token" => $this->token)), "wecha_id" => $lastprice_two["wecha_id"], "first" => "尊敬的【" . $userinfo["wechaname"] . "】:您的“" . $goodsname . "”拍品已竞拍成功，若超过" . $auction["nobuytime"] . "小时未付款系统将自动删除此次竞拍。", "ordertape" => date("Y年m月d日H时i分s秒"), "ordeID" => $auction_orderid, "remark" => ""));
			}

			$this->success("删除成功", U("Auction/order", array("token" => $this->token)));
			break;

		case "shouhuo":
			M("auction_order")->where(array("token" => $this->token, "id" => $id))->save(array("state" => 2));
			$this->success("确认成功", U("Auction/order", array("token" => $this->token)));
			break;
		}
	}
}


?>
