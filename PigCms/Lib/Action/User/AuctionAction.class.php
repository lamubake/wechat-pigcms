<?php

class AuctionAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction("Auction");
	}

	public function index()
	{
		$where["token"] = $this->token;
		$where_page["token"] = $this->token;

		if (!empty($_GET["search"])) {
			$map["name"] = array("like", "%" . $_GET["search"] . "%");

			if (strlen($_GET["search"]) == 15) {
				$search = substr($_GET["search"], 8);
				$search = "1" . $search;
				$search = ($search * 1) - 10000000;
			}

			$map["id"] = $search;
			$map["_logic"] = "or";
			$where["_complex"] = $map;
			$where_page["search"] = $_GET["search"];
		}

		if ($_GET["del"] == 1) {
			$where["is_del"] = 1;
			$where_page["del"] = 1;
		}
		else {
			$where["is_del"] = 0;
		}

		import("ORG.Util.Page");
		$count = M("auction")->where($where)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val ) {
			$pagethis->parameter .= "$key=" . urlencode($val) . "&";
		}

		$show = $page->show();
		$list = M("auction")->where($where)->order("addtime desc")->limit($page->firstRow . "," . $page->listRows)->select();

		foreach ($list as $k => $v ) {
			$lastprice = M("auction_toprice")->where(array("token" => $this->token, "pid" => $v["id"]))->order("price desc")->find();
			$list[$k]["price"] = $lastprice["price"];

			if ($v["is_open"] == 0) {
				if (time() < $v["start"]) {
					$list[$k]["state"] = 1;
				}
				else {
					if (($v["end"] < time()) || ($v["state"] != 0)) {
						$list[$k]["state"] = 2;
						if (($v["end"] < time()) && ($lastprice != NULL) && ($lastprice["time"] < $v["end"])) {
							$lastprice["time"] = $v["end"];
							M("auction_toprice")->where(array("token" => $this->token, "pid" => $v["id"], "id" => $lastprice["id"]))->save(array("time" => $v["end"]));
						}

						$list[$k]["again"] = 0;

						if ($lastprice["orderid"] != 0) {
							$order = M("auction_order")->where(array("token" => $this->token, "id" => $lastprice["orderid"]))->find();
							if ((($v["nobuytime"] * 60 * 60) < (time() - $lastprice["time"])) && ($order["paid"] == 0)) {
								$list[$k]["again"] = 1;
							}
						}
						else if ($lastprice != NULL) {
							if (($v["nobuytime"] * 60 * 60) < (time() - $lastprice["time"])) {
								$list[$k]["again"] = 1;
							}
						}
					}
					else {
						$list[$k]["state"] = 3;
					}
				}
			}
			else {
				$list[$k]["state"] = 0;
			}
		}

		$this->assign("page", $show);
		$this->assign("list", $list);
		$this->display();
	}

	public function set()
	{
		$id = intval($_GET["id"]);
		$auction = M("auction")->where(array("token" => $this->token, "id" => $id))->find();

		if (IS_POST) {
			$set["token"] = $this->token;
			$set["keyword"] = $_POST["keyword"];
			$set["wxpic"] = $_POST["wxpic"];
			$set["name"] = $_POST["name"];
			$set["wxtitle"] = $_POST["wxtitle"];
			$set["wxinfo"] = $_POST["wxinfo"];
			$set["start"] = strtotime($_POST["start"]);
			$set["end"] = strtotime($_POST["end"]);
			$set["info"] = $_POST["info"];
			$set["logo"] = $_POST["logo"];
			$set["startprice"] = intval($_POST["startprice"]);
			$set["addprice"] = intval($_POST["addprice"]);
			$set["fixedprice"] = intval($_POST["fixedprice"]);
			$set["referenceprice"] = intval($_POST["referenceprice"]);
			$set["postage"] = intval($_POST["postage"]);
			$set["nobuytime"] = intval($_POST["nobuytime"]);
			$set["is_attention"] = intval($_POST["is_attention"]);
			$set["is_reg"] = intval($_POST["is_reg"]);
			$set["is_open"] = intval($_POST["is_open"]);
			$set["settime"] = time();
			$pic = $_POST["pic"];

			if ($auction) {
				$del_pic = M("auction_pic")->where(array("token" => $this->token, "pid" => $id))->delete();

				foreach ($pic as $pv ) {
					$add_pic["token"] = $this->token;
					$add_pic["pid"] = $id;
					$add_pic["pic"] = $pv;
					$id_pic = M("auction_pic")->add($add_pic);
				}

				$update_auction = M("auction")->where(array("token" => $this->token, "id" => $id))->save($set);
				$this->handleKeyword($id, "Auction", $this->_post("keyword", "trim"));
				S($id . "Auction" . $this->token . "pic", NULL);
				$this->success("修改成功", U("Auction/index", array("token" => $this->token)));
			}
			else {
				$set["addtime"] = time();
				$id = M("auction")->add($set);

				foreach ($pic as $pv ) {
					$add_pic["token"] = $this->token;
					$add_pic["pid"] = $id;
					$add_pic["pic"] = $pv;
					$id_pic = M("auction_pic")->add($add_pic);
				}

				$this->handleKeyword($id, "Auction", $this->_post("keyword", "trim"));
				$this->success("添加成功", U("Auction/index", array("token" => $this->token)));
			}
		}
		else {
			$this->assign("set", $auction);
			$pic_list = M("auction_pic")->where(array("token" => $this->token, "pid" => $id))->select();
			$picnum = count($pic_list);
			$this->assign("picnum", $picnum);
			$this->assign("pic_list", $pic_list);
			$this->display();
		}
	}

	public function isdel()
	{
		$id = intval($_GET["id"]);
		$auction = M("auction")->where(array("token" => $this->token, "id" => $id))->find();

		if ($auction["is_del"] == 0) {
			$save["is_del"] = 1;
			$msg = "删除";
		}
		else {
			$save["is_del"] = 0;
			$msg = "恢复";
		}

		$update_auction = M("auction")->where(array("token" => $this->token, "id" => $id))->save($save);
		$this->success($msg . "成功", U("Auction/index", array("token" => $this->token)));
	}

	public function data()
	{
		$id = intval($_GET["id"]);
		$auction = M("auction")->where(array("token" => $this->token, "id" => $id))->find();
		$this->assign("auction", $auction);
		$list = M("auction_toprice")->where(array("token" => $this->token, "pid" => $id))->order("price desc")->select();

		foreach ($list as $lk => $lv ) {
			$list[$lk]["userinfo"] = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $lv["wecha_id"]))->find();

			if ($lk < 1) {
				$list[$lk]["order"] = M("auction_order")->where(array("token" => $this->token, "wecha_id" => $lv["wecha_id"], "auctionid" => $lv["pid"], "topriceid" => $lv["id"]))->find();
			}
		}

		if ((($auction["end"] < time()) || ($auction["state"] != 0)) && ($list != NULL)) {
			$lastprice = $list[0];
			if (($auction["end"] < time()) && ($lastprice != NULL) && ($lastprice["time"] < $auction["end"])) {
				$lastprice["time"] = $auction["end"];
				M("auction_toprice")->where(array("token" => $this->token, "pid" => $id, "id" => $lastprice["id"]))->save(array("time" => $auction["end"]));
			}

			$list[0]["state"] = 0;

			if ($lastprice["orderid"] != 0) {
				$order = M("auction_order")->where(array("token" => $this->token, "id" => $lastprice["orderid"]))->find();
				if ((($auction["nobuytime"] * 60 * 60) < (time() - $lastprice["time"])) && ($order["paid"] == 0)) {
					$list[0]["state"] = 1;
				}
			}
			else if ($lastprice != NULL) {
				if (($auction["nobuytime"] * 60 * 60) < (time() - $lastprice["time"])) {
					$list[0]["state"] = 1;
				}
			}
		}

		$this->assign("list", $list);
		$this->display();
	}

	public function deltoprice()
	{
		$id = intval($_GET["id"]);
		$toprice = M("auction_toprice")->where(array("token" => $this->token, "id" => $id))->find();

		if (0 < $toprice["orderid"]) {
			M("auction_order")->where(array("token" => $this->token, "id" => $toprice["orderid"]))->delete();
		}

		M("auction_toprice")->where(array("token" => $this->token, "id" => $id))->delete();
		$this->success("删除成功", U("Auction/data", array("token" => $this->token, "id" => $_GET["auctionid"])));
	}

	public function tixing()
	{
		$model = new templateNews();
		$toprice = M("auction_toprice")->where(array("token" => $this->token, "id" => intval($_GET["id"])))->find();
		$auction = M("auction")->where(array("token" => $this->token, "id" => intval($_GET["auctionid"])))->find();

		if (15 < mb_strlen($auction["name"], "utf8")) {
			$goodsname = mb_substr($auction["name"], 0, 15, "utf-8") . "...";
		}
		else {
			$goodsname = $auction["name"];
		}

		$userinfo = M("userinfo")->where(array("token" => $this->token, "wecha_id" => $toprice["wecha_id"]))->find();
		$auction_orderid = 10000000 + $auction["id"];
		$auction_orderid = substr($auction_orderid, 1);
		$auction_orderid = date("Ymd", $toprice["addtime"]) . $auction_orderid;
		$model->sendTempMsg("TM00184", array("href" => $this->siteUrl . U("Wap/Auction/order", array("token" => $this->token)), "wecha_id" => $toprice["wecha_id"], "first" => "尊敬的【" . $userinfo["wechaname"] . "】:您的“" . $goodsname . "”拍品已竞拍成功，若超过" . $auction["nobuytime"] . "小时未付款系统将自动关闭您的此次竞拍。", "ordertape" => date("Y年m月d日H时i分s秒", $toprice["time"]), "ordeID" => $auction_orderid, "remark" => ""));
		$this->success("提醒成功", U("Auction/data", array("token" => $this->token, "id" => $_GET["auctionid"])));
	}

	public function fahuo()
	{
		$model = new templateNews();

		if (IS_POST) {
			M("auction_order")->where(array("token" => $this->token, "id" => intval($_POST["orderid"])))->save(array("state" => 1, "expressname" => $_POST["fahuoname"], "expressnum" => $_POST["fahuoid"]));
			$order = M("auction_order")->where(array("token" => $this->token, "id" => intval($_POST["orderid"])))->find();
			$auction = M("auction")->where(array("token" => $this->token, "id" => $order["auctionid"]))->find();

			if (15 < mb_strlen($auction["name"], "utf8")) {
				$goodsname = mb_substr($auction["name"], 0, 15, "utf-8") . "...";
			}
			else {
				$goodsname = $auction["name"];
			}

			$toprice = M("auction_toprice")->where(array("token" => $this->token, "id" => $order["topriceid"]))->find();
			$auction_orderid = 10000000 + $auction["id"];
			$auction_orderid = substr($auction_orderid, 1);
			$auction_orderid = date("Ymd", $toprice["addtime"]) . $auction_orderid;
			$model->sendTempMsg("OPENTM200565259", array("href" => $this->siteUrl . U("Wap/Auction/order", array("token" => $this->token)), "wecha_id" => $order["wecha_id"], "first" => "您竞拍到的“" . $goodsname . "”已发货", "keyword1" => $auction_orderid, "keyword2" => $order["expressname"], "keyword3" => $order["expressnum"], "remark" => date("Y年m月d日H时i分s秒")));
			$data["error"] = 0;
			$this->ajaxReturn($data, "JSON");
		}
	}

	public function isagain()
	{
		$id = intval($_GET["id"]);
		M("auction")->where(array("token" => $this->token, "id" => $id))->save(array("state" => 0));
		$auction = M("auction")->where(array("token" => $this->token, "id" => $id))->find();

		if ($auction["end"] < time()) {
			M("auction")->where(array("token" => $this->token, "id" => $id))->save(array("end" => time() + (60 * 60 * 24)));
		}

		M("auction_toprice")->where(array("token" => $this->token, "pid" => $id))->delete();
		M("auction_order")->where(array("token" => $this->token, "auctionid" => $id))->delete();
		$this->success("已重新开始", U("Auction/index", array("token" => $this->token)));
	}
}


?>
