<?php

class UnitaryAction extends WapAction
{
	public $utype = array(1 => "手机数码", 2 => "电脑办公", 3 => "家用电器", 4 => "化妆个护", 5 => "钟表首饰", 9999 => "其他商品");
	public $m_unitary;
	public $m_cart;
	public $m_order;
	public $m_lucknum;
	public $m_userinfo;

	public function _initialize()
	{
		parent::_initialize();
		$this->m_unitary = M("unitary");
		$this->m_cart = M("unitary_cart");
		$this->m_order = M("unitary_order");
		$this->m_lucknum = M("unitary_lucknum");
		$this->m_user = M("unitary_user");
		$this->m_userinfo = M("userinfo");
		//$this->assign("wecha_id", $this->wecha_id);
		setcookie("wecha_id",$this->wecha_id);
		echo '1有ID存在吗';
		echo $this->wecha_id;
	
		echo '过来了';
		echo $_COOKIE["wecha_id"];
		$this->wecha_id = $_COOKIE["wecha_id"];
		echo '隔开了';
		echo $this->wecha_id;
		//exit;
	}

	public function wxregister()
	{
		//exit;
		$where_unitary["token"] = $this->token;
		$find_unitary = $this->m_unitary->where($where_unitary)->find();
		$wxregister = $find_unitary["wxregister"];
		$register = $find_unitary["register"];
        echo '2有ID存在吗';
		echo $this->wecha_id;
		
		if (!$this->wecha_id) {
			$this->success("您好，您还没有关注我们的公众号,关注后才能继续喔。", U("Unitary/gzhurl", array("token" => $this->token)));
			exit();
		}
	}

	public function gzhurl()
	{
		$gzhurl = M("Home")->where(array("token" => $this->token))->getField("gzhurl");

		if ($gzhurl == NULL) {
			$this->show("<h1>未设置关注链接</h1>");
		}
		else {
			$this->show("<script>window.location.href='" . $gzhurl . "'</script>");
		}
	}

	public function wxregister2()
	{
		$where_unitary["token"] = $this->token;
		$find_unitary = $this->m_unitary->where($where_unitary)->find();
		$wxregister = $find_unitary["wxregister"];
		$register = $find_unitary["register"];
		if (($wxregister == 1) && $this->wecha_id) {
			$this->memberNotice("", 1);
		}
		else {
			if ((($register == 1) && $this->fans) || (($wxregister == 0) && $this->wecha_id)) {
				$this->memberNotice();
			}
		}
	}

	public function index()
	{
		$this->wxregister();
		$where_unitary["token"] = $this->token;
		$where_unitary["state"] = 1;

		switch ($_GET["type"]) {
		case 1:
			$where_unitary["type"] = 1;
			$typetext = "手机数码";
			break;

		case 2:
			$where_unitary["type"] = 2;
			$typetext = "电脑办公";
			break;

		case 3:
			$where_unitary["type"] = 3;
			$typetext = "家用电器";
			break;

		case 4:
			$where_unitary["type"] = 4;
			$typetext = "化妆个护";
			break;

		case 5:
			$where_unitary["type"] = 5;
			$typetext = "钟表首饰";
			break;

		case 9999:
			$where_unitary["type"] = 9999;
			$typetext = "其他商品";
			break;

		default:
			$typetext = "全部分类";
		}

		$this->assign("typetext", $typetext);

		switch ($_GET["order"]) {
		case "proportion":
			$order = "proportion desc";
			$ordertext = "即将揭晓";
			break;

		case "renqi":
			$order = "renqi desc";
			$ordertext = "人气";
			break;

		case "priceup":
			$order = "price desc";
			$ordertext = "价格<em>(由高到低)</em>";
			break;

		case "pricedown":
			$order = "price";
			$ordertext = "价格<em>(由低到高)</em>";
			break;

		case "addtime":
			$order = "addtime desc";
			$ordertext = "最新";
			break;

		default:
			$order = "proportion desc";
			$ordertext = "即将揭晓";
		}

		$this->assign("ordertext", $ordertext);
		$unitary_list = $this->m_unitary->where($where_unitary)->order($order)->limit(10)->select();
		$this->assign("unitary_list", $unitary_list);

		foreach ($unitary_list as $vo ) {
			$where_cart["unitary_id"] = $vo["id"];
			$where_cart["token"] = $this->token;
			$where_cart["state"] = 1;
			$cart_list = $this->m_cart->where($where_cart)->select();
			$pay_count = 0;

			foreach ($cart_list as $cvo ) {
				$pay_count = $pay_count + $cvo["count"];
			}

			$unitary_count[$vo["id"]] = $pay_count;
		}

		$this->assign("unitary_count", $unitary_count);
		$this->assign("type", $_GET["type"]);
		$this->assign("order", $_GET["order"]);
		$where_cart_count["wecha_id"] = $this->wecha_id;
		$where_cart_count["token"] = $this->token;
		$where_cart_count["state"] = 0;
		$cart_count = $this->m_cart->where($where_cart_count)->count();
		$this->assign("cart_count", $cart_count);
		$this->display();
	}

	public function indexajax()
	{
		switch ($_POST["type"]) {
		case "unitary_count":
			$where_unitary["token"] = $_POST["token"];
			$where_unitary["state"] = 1;

			switch ($_POST["utype"]) {
			case 1:
				$where_unitary["type"] = 1;
				break;

			case 2:
				$where_unitary["type"] = 2;
				break;

			case 3:
				$where_unitary["type"] = 3;
				break;

			case 4:
				$where_unitary["type"] = 4;
				break;

			case 5:
				$where_unitary["type"] = 5;
				break;

			case 9999:
				$where_unitary["type"] = 9999;
				break;
			}

			switch ($_POST["order"]) {
			case "proportion":
				$order = "proportion desc";
				break;

			case "renqi":
				$order = "renqi desc";
				break;

			case "priceup":
				$order = "price desc";
				break;

			case "pricedown":
				$order = "price";
				break;

			case "addtime":
				$order = "addtime desc";
				break;

			default:
				$order = "proportion desc";
			}

			$unitary_count = $this->m_unitary->where($where_unitary)->order($order)->count();
			echo "{\"count\":\"" . $unitary_count . "\"}";
			break;

		case "more":
			$where_unitary["token"] = $_POST["token"];
			$where_unitary["state"] = 1;

			switch ($_POST["utype"]) {
			case 1:
				$where_unitary["type"] = 1;
				break;

			case 2:
				$where_unitary["type"] = 2;
				break;

			case 3:
				$where_unitary["type"] = 3;
				break;

			case 4:
				$where_unitary["type"] = 4;
				break;

			case 5:
				$where_unitary["type"] = 5;
				break;

			case 9999:
				$where_unitary["type"] = 9999;
				break;
			}

			switch ($_POST["order"]) {
			case "proportion":
				$order = "proportion desc";
				break;

			case "renqi":
				$order = "renqi desc";
				break;

			case "priceup":
				$order = "price desc";
				break;

			case "pricedown":
				$order = "price";
				break;

			case "addtime":
				$order = "addtime desc";
				break;

			default:
				$order = "proportion desc";
			}

			$unitary_list = $this->m_unitary->where($where_unitary)->order($order)->limit($_POST["num"], 10)->select();
			$unitary_count = $this->m_unitary->where($where_unitary)->order($order)->count();

			foreach ($unitary_list as $vo ) {
				$where_cart["unitary_id"] = $vo["id"];
				$where_cart["token"] = $this->token;
				$where_cart["state"] = 1;
				$cart_list = $this->m_cart->where($where_cart)->select();
				$pay_count = 0;

				foreach ($cart_list as $cvo ) {
					$pay_count = $pay_count + $cvo["count"];
				}

				$unitary_count[$vo["id"]] = $pay_count;
			}

			$unitary = "";

			foreach ($unitary_list as $vo ) {
				$unitary .= "<ul class='unitary unitary" . $_POST["i"] . "' unitaryid='" . $vo["id"] . "'><li><span class='gList_l fl'><img src='" . $vo["logopic"] . "'></span><div class='gList_r'><h3 class='gray6'>" . $vo["name"] . "</h3><em class='gray9'>价值：￥" . $vo["price"] . ".00</em><div class='gRate'><div class='Progress-bar'><p class='u-progress'><span style='width: " . $vo["proportion"] . "%;' class='pgbar'><span class='pging'></span></span></p><ul class='Pro-bar-li'><li class='P-bar01'><em>" . $unitary_count[$vo["id"]] . "</em>已参与</li><li class='P-bar02'><em>" . $vo["price"] . "</em>总需人次</li><li class='P-bar03'><em>" . ($vo["price"] - $unitary_count[$vo["id"]]) . "</em>剩余</li></ul></div><a class='cart" . $_POST["i"] . "' unitaryid='" . $vo["id"] . "'><s></s></a></div></div></li></ul>";
			}

			echo "{\"unitary\":\"" . $unitary . "\"}";
			break;

		case "cart":
			//$wecha_id = $_POST["wecha_id"];
			$wecha_id = $_COOKIE["wecha_id"];
			//echo '这就是WECHA';
			//echo $wecha_id;
		//	exit;
			$token = $_POST["token"];
			$unitaryid = $_POST["unitaryid"];
			$where_cart["wecha_id"] = $wecha_id;
			$where_cart["token"] = $token;
			$where_cart["unitary_id"] = $unitaryid;
			$where_cart["state"] = 0;
			$find_cart = $this->m_cart->where($where_cart)->find();

			if ($find_cart == NULL) {
				$add_cart["wecha_id"] = $wecha_id;
				$add_cart["token"] = $token;
				$add_cart["unitary_id"] = $unitaryid;
				$add_cart["count"] = 1;
				$add_cart["state"] = 0;
				$add_cart["addtime"] = time();
				$id_cart = $this->m_cart->add($add_cart);
			}
			else {
				$save_cart["count"] = $find_cart["count"] + 1;
				$update_cart = $this->m_cart->where($where_cart)->save($save_cart);
			}

			$where_cart_count["wecha_id"] = $wecha_id;
			$where_cart_count["token"] = $token;
			$where_cart_count["state"] = 0;
			$cart_count = $this->m_cart->where($where_cart_count)->count();
			echo "{\"count\":\"" . $cart_count . "\"}";
			break;
		}
	}

	public function cart()
	{
		$this->wxregister();
		$where_cart["wecha_id"] = $this->wecha_id;
		//echo '这就是ID了';
		//echo $this->wecha_id;
		//exit;
		$where_cart["token"] = $this->token;
		$where_cart["state"] = 0;
		$cart_list = $this->m_cart->where($where_cart)->select();
		$total = 0;
		$sum = 0;

		foreach ($cart_list as $vo ) {
			$total = $total + $vo["count"];
			$sum++;
			$where_unitary["id"] = $vo["unitary_id"];
			$where_unitary["token"] = $vo["token"];
			$find_unitary = $this->m_unitary->where($where_unitary)->find();
			$unitary[$vo["id"]] = $find_unitary;
			$where_cart2["unitary_id"] = $vo["unitary_id"];
			$where_cart2["token"] = $this->token;
			$where_cart2["state"] = 1;
			$cart_list2 = $this->m_cart->where($where_cart2)->select();
			$pay_count = 0;

			foreach ($cart_list2 as $cvo ) {
				$pay_count = $pay_count + $cvo["count"];
			}

			$unitary_ycount[$vo["id"]] = $find_unitary["price"] - $pay_count;

			if ($unitary_ycount[$vo["id"]] == 0) {
				$del_cart = $this->m_cart->where(array("id" => $vo["id"], "token" => $this->token))->delete();
				$sum = $sum - 1;
			}
			else if ($unitary_ycount[$vo["id"]] < $vo["count"]) {
				$cha = $vo["count"] - $unitary_ycount[$vo["id"]];
				$total = $total - $cha;
				$save_cart["count"] = $unitary_ycount[$vo["id"]];
				$where_save_cart["id"] = $vo["id"];
				$where_save_cart["token"] = $this->token;
				$update_cart = $this->m_cart->where($where_save_cart)->save($save_cart);
			}
		}

		$cart_list3 = $this->m_cart->where($where_cart)->select();
		$this->assign("cart_list", $cart_list3);
		$this->assign("unitary", $unitary);
		$this->assign("total", $total);
		$this->assign("sum", $sum);
		$this->assign("ycount", $unitary_ycount);
		$this->display();
	}

	public function cartajax()
	{
		switch ($_POST["type"]) {
		case "delorder":
			$where_order["wecha_id"] = $_POST["wecha_id"];
			$where_order["token"] = $_POST["token"];
			$where_order["paid"] = 0;
			$order_list = $this->m_order->where($where_order)->select();

			if ($order_list != NULL) {
				foreach ($order_list as $vo ) {
					$where_cart["wecha_id"] = $_POST["wecha_id"];
					$where_cart["token"] = $_POST["token"];
					$where_cart["order_id"] = $vo["orderid"];
					$save_cart["order_id"] = 0;
					$update_cart = $this->m_cart->where($where_cart)->save($save_cart);
				}

				$del_order = $this->m_order->where($where_order)->delete();
				$data["error"] = 1;
			}
			else {
				$data["error"] = 0;
			}

			$this->ajaxReturn($data, "JSON");
			break;

		case "click_cart_count":
			$where_cart["id"] = $_POST["cid"];
			$where_cart["token"] = $_POST["token"];
			$find_cart = $this->m_cart->where($where_cart)->find();
			$unitaryid = $this->m_cart->where($where_cart)->getField("unitary_id");
			$where_unitary["id"] = $unitaryid;
			$where_unitary["token"] = $_POST["token"];
			$find_unitary = $this->m_unitary->where($where_unitary)->find();
			$where_cart2["unitary_id"] = $unitaryid;
			$where_cart2["token"] = $_POST["token"];
			$where_cart2["state"] = 1;
			$cart_list = $this->m_cart->where($where_cart2)->select();
			$pay_count = 0;

			foreach ($cart_list as $vo ) {
				$pay_count = $pay_count + $vo["count"];
			}

			$unitary_ycount = $find_unitary["price"] - $pay_count;

			if ($unitary_ycount == 0) {
				$del_cart = $this->m_cart->where($where_cart)->delete();
				$data["error"] = 1;
				$data["text"] = "此商品已结束";
			}
			else if ($find_cart == NULL) {
				$data["error"] = 1;
				$data["text"] = "此商品已结束";
			}
			else {
				$data["error"] = 0;
				$data["ycount"] = $unitary_ycount;
			}

			$this->ajaxReturn($data, "JSON");
			break;

		case "cart_count_change":
			$count = abs(intval($_POST["cart_count"]));
			$where_cart["id"] = $_POST["cid"];
			$where_cart["token"] = $_POST["token"];
			$unitaryid = $this->m_cart->where($where_cart)->getField("unitary_id");
			$where_unitary["id"] = $unitaryid;
			$where_unitary["token"] = $_POST["token"];
			$find_unitary = $this->m_unitary->where($where_unitary)->find();
			$where_cart2["state"] = 1;
			$where_cart2["unitary_id"] = $unitaryid;
			$where_cart2["token"] = $_POST["token"];
			$cart_list = $this->m_cart->where($where_cart2)->select();
			$pay_count = 0;

			foreach ($cart_list as $vo ) {
				$pay_count = $pay_count + $vo["count"];
			}

			$unitary_ycount = $find_unitary["price"] - $pay_count;

			if ($unitary_ycount < $count) {
				$count = $unitary_ycount;
				$save_cart["count"] = $count;
				$dale = "yes";
			}
			else {
				$save_cart["count"] = $count;
			}

			$update_cart = $this->m_cart->where($where_cart)->save($save_cart);
			$where_cart3["wecha_id"] = $_POST["wecha_id"];
			$where_cart3["token"] = $_POST["token"];
			$where_cart3["state"] = 0;
			$cart_list2 = $this->m_cart->where($where_cart3)->select();
			$total = 0;

			foreach ($cart_list2 as $vo ) {
				$total = $total + $vo["count"];
			}

			$find_cart = $this->m_cart->where($where_cart)->find();
			if (($find_cart == NULL) || ($unitary_ycount == 0)) {
				if ($unitary_ycount == 0) {
					$del_cart = $this->m_cart->where(where_cart)->delete();
				}

				$data["error"] = 2;
				$data["text"] = "此商品已结束";
			}
			else if ($_POST["cart_count"] == "") {
				$data["error"] = 1;
				$data["cart_count"] = "";
				$data["text"] = "请填写数字";
				$data["total"] = $total;
				$data["ycount"] = $unitary_ycount;
			}
			else {
				if ($dale == "yes") {
					$data["error"] = 1;
					$data["text"] = "不能超过" . $unitary_ycount . "人次";
				}
				else {
					$data["error"] = 0;
				}

				$data["cart_count"] = $count;
				$data["total"] = $total;
				$data["ycount"] = $unitary_ycount;
			}

			$this->ajaxReturn($data, "JSON");
			break;

		case "cart_del":
			$where_cart["id"] = $_POST["cid"];
			$where_cart["token"] = $_POST["token"];
			$del_cart = $this->m_cart->where($where_cart)->delete();

			if (0 < $del_cart) {
				$where_cart2["wecha_id"] = $_POST["wecha_id"];
				$where_cart2["token"] = $_POST["token"];
				$where_cart2["state"] = 0;
				$cart_list = $this->m_cart->where($where_cart2)->select();
				$total = 0;
				$sum = 0;

				foreach ($cart_list as $vo ) {
					$total = $total + $vo["count"];
					$sum++;
				}

				$data["error"] = 0;
				$data["total"] = $total;
				$data["sum"] = $sum;
			}
			else {
				$data["error"] = 1;
			}

			$this->ajaxReturn($data, "JSON");
			break;

		case "buy":
			$where_user["wecha_id"] = $_POST["wecha_id"];
			$where_user["token"] = $_POST["token"];
			$find_user = $this->m_user->where($where_user)->find();

			if ($find_user == NULL) {
				$data["error"] = 2;
				$this->ajaxReturn($data, "JSON");
				exit();
			}

			$where_cart["wecha_id"] = $_POST["wecha_id"];
			$where_cart["token"] = $_POST["token"];
			$where_cart["state"] = 0;
			$cart_count = $this->m_cart->where($where_cart)->count();

			if ($cart_count != $_POST["cnum"]) {
				$data["error"] = 1;
				$data["text"] = "商品有变动";
			}
			else {
				$cart_list = $this->m_cart->where($where_cart)->select();

				foreach ($cart_list as $vo ) {
					$where_unitary["id"] = $vo["unitary_id"];
					$where_unitary["token"] = $_POST["token"];
					$find_unitary = $this->m_unitary->where($where_unitary)->find();
					$where_cart2["state"] = 1;
					$where_cart2["token"] = $_POST["token"];
					$where_cart2["unitary_id"] = $unitaryid;
					$cart_list2 = $this->m_cart->where($where_cart2)->select();
					$pay_count = 0;

					foreach ($cart_list2 as $vo ) {
						$pay_count = $pay_count + $vo["count"];
					}

					$unitary_ycount = $find_unitary["price"] - $pay_count;
					if (($unitary_ycount < $vo["count"]) || ($unitary_ycount == 0)) {
						$data["error"] = 1;

						if ($unitary_ycount == 0) {
							$del_cart = $this->m_cart->where(array("id" => $vo["id"]))->delete();
						}

						$data["text"] = "商品有变动";
						$this->ajaxReturn($data, "JSON");
						exit();
					}
					else {
						$data["error"] = 0;
					}
				}
			}

			$this->ajaxReturn($data, "JSON");
			break;
		}
	}

	public function goods()
	{
		$this->wxregister();
		$where_unitary["id"] = $_GET["unitaryid"];
		$where_unitary["token"] = $this->token;
		$find_unitary = $this->m_unitary->where($where_unitary)->find();

		switch ($find_unitary["state"]) {
		case "":
			$this->error("此商品已删除", U("Unitary/index", array("token" => $this->token)));
			exit();
			break;

		case "0":
			$this->error("此商品已暂停", U("Unitary/index", array("token" => $this->token)));
			exit();
			break;

		case 2:
			$this->redirect("Unitary/goodsover", array("token" => $this->token, "unitaryid" => $_GET["unitaryid"]));
			exit();
			break;
		}

		if ($find_unitary["fistpic"] != NULL) {
			$unitary_pic[] = $find_unitary["fistpic"];
		}

		if ($find_unitary["secondpic"] != NULL) {
			$unitary_pic[] = $find_unitary["secondpic"];
		}

		if ($find_unitary["thirdpic"] != NULL) {
			$unitary_pic[] = $find_unitary["thirdpic"];
		}

		if ($find_unitary["fourpic"] != NULL) {
			$unitary_pic[] = $find_unitary["fourpic"];
		}

		if ($find_unitary["fivepic"] != NULL) {
			$unitary_pic[] = $find_unitary["fivepic"];
		}

		if ($find_unitary["sixpic"] != NULL) {
			$unitary_pic[] = $find_unitary["sixpic"];
		}

		$this->assign("unitary", $find_unitary);
		$this->assign("unitary_pic", $unitary_pic);
		$where_cart["state"] = 1;
		$where_cart["unitary_id"] = $_GET["unitaryid"];
		$where_cart["token"] = $this->token;
		$cart_list = $this->m_cart->where($where_cart)->select();
		$pay_count = 0;

		foreach ($cart_list as $vo ) {
			$pay_count = $pay_count + $vo["count"];
		}

		$this->assign("pay_count", $pay_count);
		$where_cart2["token"] = $this->token;
		$where_cart2["wecha_id"] = $this->wecha_id;
		$where_cart2["state"] = 0;
		$cart_count = $this->m_cart->where($where_cart2)->count();
		$this->assign("cart_count", $cart_count);
		$this->display();
	}

	public function goodsajax()
	{
		switch ($_POST["type"]) {
		case "docart":
			$where_cart["wecha_id"] = $_POST["wecha_id"];
			$where_cart["token"] = $_POST["token"];
			$where_cart["unitary_id"] = $_POST["id"];
			$where_cart["state"] = 0;
			$find_cart = $this->m_cart->where($where_cart)->find();

			if ($find_cart == NULL) {
				$add_cart["wecha_id"] = $_POST["wecha_id"];
				$add_cart["token"] = $_POST["token"];
				$add_cart["unitary_id"] = $_POST["id"];
				$add_cart["addtime"] = time();
				$add_cart["count"] = 1;
				$add_cart["state"] = 0;
				$id_cart = $this->m_cart->add($add_cart);
			}
			else {
				$save_cart["count"] = $find_cart["count"] + 1;
				$update_cart = $this->m_cart->where($where_cart)->save($save_cart);
			}

			$where_cart2["wecha_id"] = $_POST["wecha_id"];
			$where_cart2["token"] = $_POST["token"];
			$where_cart2["state"] = 0;
			$cart_count = $this->m_cart->where($where_cart2)->count();
			$data["count"] = $cart_count;
			$this->ajaxReturn($data, "JSON");
			break;
		}
	}

	public function buygoods()
	{
		$this->wxregister();
		$where_cart["wecha_id"] = $this->wecha_id;
		$where_cart["token"] = $this->token;
		$where_cart["unitary_id"] = $_GET["unitaryid"];
		$where_cart["state"] = 0;
		$find_cart = $this->m_cart->where($where_cart)->find();

		if ($find_cart == NULL) {
			$add_cart["wecha_id"] = $this->wecha_id;
			$add_cart["token"] = $this->token;
			$add_cart["unitary_id"] = $_GET["unitaryid"];
			$add_cart["addtime"] = time();
			$add_cart["count"] = 1;
			$add_cart["state"] = 0;
			$id_cart = $this->m_cart->add($add_cart);

			if (0 < $id_cart) {
				$this->redirect("Unitary/cart", array("token" => $this->token));
			}
		}
		else {
			$save_cart["count"] = $find_cart["count"] + 1;
			$update_cart = $this->m_cart->where($where_cart)->save($save_cart);

			if (0 < $update_cart) {
				$this->redirect("Unitary/cart", array("token" => $this->token));
			}
		}
	}

	public function goodswhere()
	{
		$where_unitary["id"] = $_GET["unitaryid"];
		$where_unitary["token"] = $this->token;
		$find_unitary = $this->m_unitary->where($where_unitary)->find();
		$save_unitary["renqi"] = $find_unitary["renqi"] + 1;
		$update_unitary = $this->m_unitary->where($where_unitary)->save($save_unitary);

		switch ($find_unitary["state"]) {
		case 1:
			$this->redirect("Unitary/goods", array("token" => $this->token, "unitaryid" => $_GET["unitaryid"]));
			break;

		case "0":
			$this->error("此商品已被暂停", U("Unitary/index", array("token" => $this->token)));
			break;

		case 2:
			$this->redirect("Unitary/goodsover", array("token" => $this->token, "unitaryid" => $_GET["unitaryid"]));
			break;

		default:
			$this->error("此商品不存在", U("Unitary/index", array("token" => $this->token)));
		}
	}

	public function goodsover()
	{
		$this->wxregister();
		$where_unitary["id"] = $_GET["unitaryid"];
		$where_unitary["token"] = $this->token;
		$find_unitary = $this->m_unitary->where($where_unitary)->find();

		switch ($find_unitary["state"]) {
		case "":
			$this->error("此商品已删除", U("Unitary/index", array("token" => $this->token)));
			exit();
			break;

		case "0":
			$this->error("此商品已暂停", U("Unitary/index", array("token" => $this->token)));
			exit();
			break;
		}

		if (time() < $find_unitary["endtime"]) {
			$opentime = $find_unitary["endtime"] - time();
			$opentime_min = floor($opentime / 60);
			$opentime_s = $opentime % 60;
			$this->assign("min", $opentime_min);
			$this->assign("s", $opentime_s);

			if ($find_unitary["fistpic"] != NULL) {
				$unitary_pic[] = $find_unitary["fistpic"];
			}

			if ($find_unitary["secondpic"] != NULL) {
				$unitary_pic[] = $find_unitary["secondpic"];
			}

			if ($find_unitary["thirdpic"] != NULL) {
				$unitary_pic[] = $find_unitary["thirdpic"];
			}

			if ($find_unitary["fourpic"] != NULL) {
				$unitary_pic[] = $find_unitary["fourpic"];
			}

			if ($find_unitary["fivepic"] != NULL) {
				$unitary_pic[] = $find_unitary["fivepic"];
			}

			if ($find_unitary["sixpic"] != NULL) {
				$unitary_pic[] = $find_unitary["sixpic"];
			}

			$this->assign("unitary", $find_unitary);
			$this->assign("unitary_pic", $unitary_pic);
			$where_cart["state"] = 1;
			$where_cart["token"] = $this->token;
			$where_cart["unitary_id"] = $_GET["unitaryid"];
			$cart_list = $this->m_cart->where($where_cart)->select();
			$pay_count = 0;

			foreach ($cart_list as $vo ) {
				$pay_count = $pay_count + $vo["count"];
			}

			$this->assign("pay_count", $pay_count);
			$where_cart2["token"] = $this->token;
			$where_cart2["wecha_id"] = $this->wecha_id;
			$where_cart2["state"] = 0;
			$cart_count = $this->m_cart->where($where_cart2)->count();
			$this->assign("cart_count", $cart_count);
		}
		else {
			$this->assign("unitary", $find_unitary);
			$where_lucknum["unitary_id"] = $find_unitary["id"];
			$where_lucknum["token"] = $this->token;
			$where_lucknum["state"] = 1;
			$where_lucknum["lucknum"] = $find_unitary["lucknum"];
			$find_lucknum = $this->m_lucknum->where($where_lucknum)->find();
			$where_user["token"] = $this->token;
			$where_user["wecha_id"] = $find_lucknum["wecha_id"];
			$find_user = $this->m_user->where($where_user)->find();
			$find_userinfo = $this->m_userinfo->where($where_user)->find();

			if ($find_user["name"] == NULL) {
				$find_lucknum["name"] = $find_userinfo["wechaname"];
			}
			else {
				$find_lucknum["name"] = $find_user["name"];
			}

			$find_lucknum["pic"] = $find_userinfo["portrait"];
			$this->assign("lucker", $find_lucknum);
			$where_cart["unitary_id"] = $find_unitary["id"];
			$where_cart["token"] = $this->token;
			$where_cart["wecha_id"] = $find_lucknum["wecha_id"];
			$where_cart["state"] = 1;
			$cart_list = $this->m_cart->where($where_cart)->select();
			$pay_count = 0;

			foreach ($cart_list as $vo ) {
				$pay_count = $pay_count + $vo["count"];
			}

			$this->assign("pay_count", $pay_count);
		}

		$this->display();
	}

	public function dobuy()
	{
		$this->wxregister();
		$where_cart["state"] = 0;
		$where_cart["token"] = $this->token;
		$where_cart["wecha_id"] = $this->wecha_id;
		$cart_list = $this->m_cart->where($where_cart)->select();
		$where_order["token"] = $this->token;
		$where_order["wecha_id"] = $this->wecha_id;
		$where_order["paid"] = 0;
		$del_order = $this->m_order->where($where_order)->delete();
		$add_order["token"] = $this->token;
		$add_order["wecha_id"] = $this->wecha_id;
		$total = 0;
		$sum = 0;

		foreach ($cart_list as $vo ) {
			$total = $total + $vo["count"];
			$sum++;
			$where_unitary["id"] = $vo["unitary_id"];
			$where_unitary["token"] = $vo["token"];
			$find_unitary = $this->m_unitary->where($where_unitary)->find();
			$where_cart2["unitary_id"] = $vo["unitary_id"];
			$where_cart2["token"] = $this->token;
			$where_cart2["state"] = 1;
			$cart_list2 = $this->m_cart->where($where_cart2)->select();
			$pay_count = 0;

			foreach ($cart_list2 as $cvo ) {
				$pay_count = $pay_count + $cvo["count"];
			}

			$unitary_ycount[$vo["id"]] = $find_unitary["price"] - $pay_count;

			if ($unitary_ycount[$vo["id"]] == 0) {
				$del_cart = $this->m_cart->where(array("id" => $vo["id"], "token" => $this->token))->delete();
				$sum = $sum - 1;
			}
			else if ($unitary_ycount[$vo["id"]] < $vo["count"]) {
				$cha = $vo["count"] - $unitary_ycount[$vo["id"]];
				$total = $total - $cha;
				$save_cart["count"] = $unitary_ycount[$vo["id"]];
				$where_save_cart["id"] = $vo["id"];
				$where_save_cart["token"] = $this->token;
				$update_cart = $this->m_cart->where($where_save_cart)->save($save_cart);
			}
		}

		if ($total <= 0) {
			$this->error("商品被抢了，请重新下单", U("Unitary/index", array("token" => $this->token)));
			exit();
		}

		$add_order["price"] = $total;
		$add_order["addtime"] = time();
		$id_order = $this->m_order->add($add_order);

		if (0 < $id_order) {
			$save_cart["order_id"] = $id_order;
			$save_cart["addtime"] = time();
			$update_cart = $this->m_cart->where($where_cart)->save($save_cart);
			$where_unitary["token"] = $this->token;
			$unitary_list = $this->m_unitary->where($where_unitary)->select();

			foreach ($unitary_list as $vo ) {
				$where_cart3["state"] = 1;
				$where_cart3["unitary_id"] = $vo["id"];
				$cart_list3 = $this->m_cart->where($where_cart3)->select();
				$pay_count = 0;

				foreach ($cart_list3 as $cvo ) {
					$pay_count = $pay_count + $cvo["count"];
				}

				$save_unitary["proportion"] = ($pay_count / $vo["price"]) * 100;
				$update_unitary = $this->m_unitary->where(array("id" => $vo["id"]))->save($save_unitary);
			}

			if (0 < $update_cart) {
				$this->redirect("Alipay/pay", array("token" => $this->token, "price" => $total, "wecha_id" => $this->wecha_id, "from" => "Unitary", "orderid" => $id_order));
			}
		}
	}

	public function payReturn()
	{
		$this->wxregister();
		$where_order["orderid"] = $_GET["orderid"];
		$where_order["token"] = $this->token;
		$find_order = $this->m_order->where($where_order)->find();

		if ($find_order["paid"] == 1) {
			$where_cart["token"] = $this->token;
			$where_cart["wecha_id"] = $this->wecha_id;
			$where_cart["order_id"] = $_GET["orderid"];
			$save_cart["state"] = 1;
			$update_cart = $this->m_cart->where($where_cart)->save($save_cart);

			if (0 < $update_cart) {
				$where_cart["state"] = 1;
			}

			$cart_list = $this->m_cart->where($where_cart)->select();

			foreach ($cart_list as $vo ) {
				$where_cart2["unitary_id"] = $vo["unitary_id"];
				$where_cart2["token"] = $this->token;
				$where_cart2["state"] = 1;
				$cart_list2 = $this->m_cart->where($where_cart2)->select();
				$pay_count = 0;

				foreach ($cart_list2 as $cvo ) {
					$pay_count = $pay_count + $cvo["count"];
				}

				$where_unitary["id"] = $vo["unitary_id"];
				$where_unitary["token"] = $this->token;
				$find_unitary = $this->m_unitary->where($where_unitary)->find();

				for ($j = 0; $j < $find_unitary["price"]; $j++) {
					$price[$vo["unitary_id"]][] = $j;
				}

				for ($i = 0; $i < $vo["count"]; $i++) {
					$add_lucknum["token"] = $this->token;
					$add_lucknum["wecha_id"] = $this->wecha_id;
					$add_lucknum["order_id"] = $_GET["orderid"];
					$add_lucknum["cart_id"] = $vo["id"];
					$add_lucknum["unitary_id"] = $vo["unitary_id"];
					list($s1, $s2) = explode(" ", microtime());
					$mtime = (double) sprintf("%.0f", (floatval($s1) + floatval($s2)) * 1000);
					$add_lucknum["addtime"] = $mtime;
					$where_lucknum["unitary_id"] = $vo["unitary_id"];
					$lucknum_list = $this->m_lucknum->where($where_lucknum)->select();

					foreach ($lucknum_list as $lvo ) {
						if (in_array($lvo["lucknum"], $price[$vo["unitary_id"]])) {
							$k = array_search($lvo["lucknum"], $price[$vo["unitary_id"]]);
							array_splice($price[$vo["unitary_id"]], $k, 1);
						}
					}

					$mylucknum = $price[$vo["unitary_id"]][rand(0, count($price[$vo["unitary_id"]]) - 1)];
					$add_lucknum["lucknum"] = $mylucknum;
					$add_lucknum["state"] = 0;
					$id_lucknum = $this->m_lucknum->add($add_lucknum);
				}

				$save_unitary = NULL;
				$save_unitary["proportion"] = ($pay_count / $find_unitary["price"]) * 100;

				if ($find_unitary["price"] <= $pay_count) {
					$where_lucknum_all["token"] = $this->token;
					$lucknum_all_count = $this->m_lucknum->where($where_lucknum_all)->count();

					if ($lucknum_all_count < 100) {
						$save_unitary["lastnum"] = $lucknum_all_count;
					}
					else {
						$save_unitary["lastnum"] = 100;
					}

					$lucknum_all = $this->m_lucknum->where($where_lucknum_all)->order("addtime desc")->limit($save_unitary["lastnum"])->select();
					$save_unitary["lasttime"] = $lucknum_all[0]["id"];
					$sum = 0;

					foreach ($lucknum_all as $avo ) {
						$thistime = floor($avo["addtime"] / 1000);
						$ms = substr($avo["addtime"], -3);
						$sum = $sum + (date("h", $thistime) . date("i", $thistime) . date("s", $thistime) . $ms);
					}

					$lucknum = fmod($sum, $find_unitary["price"]);
					$save_unitary["lucknum"] = $lucknum;
					$save_unitary["state"] = 2;
					$save_unitary["endtime"] = time() + $find_unitary["opentime"];
					$where_cart3["state"] = 0;
					$where_cart3["token"] = $this->token;
					$where_cart3["unitary_id"] = $vo["unitary_id"];
					$del_cart3 = $this->m_cart->where($where_cart3)->delete();
					$save_unitary["proportion"] = 100;
					$where_lucknum2["unitary_id"] = $vo["unitary_id"];
					$where_lucknum2["token"] = $this->token;
					$where_lucknum2["lucknum"] = $lucknum;
					$where_lucknum2["state"] = 0;
					$save_lucknum2["state"] = 1;
					$update_lucknum2 = $this->m_lucknum->where($where_lucknum2)->save($save_lucknum2);
					$where_lucknum2["state"] = 1;
					$find_lucknum2 = $this->m_lucknum->where($where_lucknum2)->find();
					$model = new templateNews();
					$model->sendTempMsg("TM00785", array("href" => U("Unitary/goodswhere", array("token" => $this->token, "unitaryid" => $vo["unitary_id"])), "wecha_id" => $find_lucknum2["wecha_id"], "first" => "一元夺宝中奖通知", "program" => $find_unitary["name"], "result" => date("Y年m月d日H时i分s秒"), "remark" => "恭喜您在一元夺宝中获得【" . $find_unitary["name"] . "】点击查看"));
				}

				$update_unitary = $this->m_unitary->where($where_unitary)->save($save_unitary);
			}

			$this->redirect("Unitary/payend", array("token" => $this->token, "orderid" => $_GET["orderid"]));
		}
		else {
			$where_cart["token"] = $this->token;
			$where_cart["wecha_id"] = $this->wecha_id;
			$where_cart["order_id"] = $_GET["orderid"];
			$save_cart["state"] = 0;
			$save_cart["order_id"] = 0;
			$update_cart = $this->m_cart->where($where_cart)->save($save_cart);
			$del_order = $this->m_order->where($where_order)->delete();
			$this->error("支付失败", U("Unitary/cart", array("token" => $this->token)));
		}
	}

	public function payend()
	{
		$this->wxregister();
		$where_cart["state"] = 1;
		$where_cart["token"] = $this->token;
		$where_cart["order_id"] = $_GET["orderid"];
		$cart_list = $this->m_cart->where($where_cart)->select();
		$this->assign("cart_list", $cart_list);
		$this->assign("cart_count", count($cart_list));

		foreach ($cart_list as $vo ) {
			$where_unitary["id"] = $vo["unitary_id"];
			$where_unitary["token"] = $this->token;
			$unitary_name = $this->m_unitary->where($where_unitary)->getField("name");
			$name[$vo["id"]] = $unitary_name;
		}

		$this->assign("name", $name);
		$this->display();
	}

	public function mypay()
	{
		$this->wxregister();
		$where_cart["token"] = $this->token;
		$where_cart["wecha_id"] = $this->wecha_id;
		$where_cart["state"] = 1;
		$where_cart["order_id"] = array("neq", "");
		$cart_list = $this->m_cart->where($where_cart)->order("addtime desc")->select();
		$cart_count_ing = 0;
		$cart_count_end = 0;
		$jilu = array();
		$tmparr = array();

		foreach ($cart_list as $k => $vo ) {
			if (array_key_exists($vo["unitary_id"], $tmparr)) {
				$tmpk = $tmparr[$vo["unitary_id"]];
				$cart[$tmpk]["count"] = $cart[$tmpk]["count"] + $vo["count"];
			}
			else {
				$tmparr[$vo["unitary_id"]] = $k;
				$cart[$k] = $vo;
			}
		}

		$this->assign("cart_count", count($cart));

		foreach ($cart as $vo2 ) {
			$where_unitary["id"] = $vo2["unitary_id"];
			$where_unitary["token"] = $this->token;
			$find_unitary = $this->m_unitary->where($where_unitary)->find();

			if ($find_unitary["state"] == 1) {
				$cart_count_ing++;
				$cart_ing[] = $vo2;
			}
			else if ($find_unitary["state"] == 2) {
				$cart_count_end++;
				$cart_end[] = $vo2;
			}
		}

		$this->assign("cart_count_ing", $cart_count_ing);
		$this->assign("cart_count_end", $cart_count_end);

		switch ($_GET["type"]) {
		case "ing":
			foreach ($cart_ing as $vo3 ) {
				$where_unitary["id"] = $vo3["unitary_id"];
				$where_unitary["token"] = $this->token;
				$find_unitary = $this->m_unitary->where($where_unitary)->find();
				$unitary[$vo3["id"]] = $find_unitary;
				$where_cart2["token"] = $this->token;
				$where_cart2["state"] = 1;
				$where_cart2["unitary_id"] = $vo3["unitary_id"];
				$cart_list2 = $this->m_cart->where($where_cart2)->select();
				$pay_count = 0;

				foreach ($cart_list2 as $vo4 ) {
					$pay_count = $pay_count + $vo4["count"];
				}

				$unitary[$vo3["id"]]["pay_count"] = $pay_count;
			}

			$this->assign("unitary", $unitary);
			$this->assign("cart_list", $cart_ing);
			break;

		case "end":
			foreach ($cart_end as $vo3 ) {
				$where_unitary["id"] = $vo3["unitary_id"];
				$where_unitary["token"] = $this->token;
				$find_unitary = $this->m_unitary->where($where_unitary)->find();
				$unitary[$vo3["id"]] = $find_unitary;
				$where_cart2["token"] = $this->token;
				$where_cart2["state"] = 1;
				$where_cart2["unitary_id"] = $vo3["unitary_id"];
				$cart_list2 = $this->m_cart->where($where_cart2)->select();
				$pay_count = 0;

				foreach ($cart_list2 as $vo4 ) {
					$pay_count = $pay_count + $vo4["count"];
				}

				$unitary[$vo3["id"]]["pay_count"] = $pay_count;

				if ($find_unitary["state"] == 2) {
					$where_lucknum["token"] = $this->token;
					$where_lucknum["unitary_id"] = $vo3["unitary_id"];
					$where_lucknum["lucknum"] = $find_unitary["lucknum"];
					$find_lucknum = $this->m_lucknum->where($where_lucknum)->find();
					$where_userinfo["wecha_id"] = $find_lucknum["wecha_id"];
					$where_userinfo["token"] = $this->token;
					$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();
					$find_user = $this->m_user->where($where_userinfo)->find();
					$unitary[$vo3["id"]]["lucker"] = $find_userinfo;

					if ($find_user["name"] != NULL) {
						$unitary[$vo3["id"]]["lucker"]["wechaname"] = $find_user["name"];
					}
				}
			}

			$this->assign("unitary", $unitary);
			$this->assign("cart_list", $cart_end);
			break;

		default:
			foreach ($cart as $vo3 ) {
				$where_unitary["id"] = $vo3["unitary_id"];
				$where_unitary["token"] = $this->token;
				$find_unitary = $this->m_unitary->where($where_unitary)->find();
				$unitary[$vo3["id"]] = $find_unitary;
				$where_cart2["token"] = $this->token;
				$where_cart2["state"] = 1;
				$where_cart2["unitary_id"] = $vo3["unitary_id"];
				$cart_list2 = $this->m_cart->where($where_cart2)->select();
				$pay_count = 0;

				foreach ($cart_list2 as $vo4 ) {
					$pay_count = $pay_count + $vo4["count"];
				}

				$unitary[$vo3["id"]]["pay_count"] = $pay_count;

				if ($find_unitary["state"] == 2) {
					$where_lucknum["token"] = $this->token;
					$where_lucknum["unitary_id"] = $vo3["unitary_id"];
					$where_lucknum["lucknum"] = $find_unitary["lucknum"];
					$find_lucknum = $this->m_lucknum->where($where_lucknum)->find();
					$where_userinfo["wecha_id"] = $find_lucknum["wecha_id"];
					$where_userinfo["token"] = $this->token;
					$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();
					$find_user = $this->m_user->where($where_userinfo)->find();
					$unitary[$vo3["id"]]["lucker"] = $find_userinfo;

					if ($find_user["name"] != NULL) {
						$unitary[$vo3["id"]]["lucker"]["wechaname"] = $find_user["name"];
					}
				}
			}

			$this->assign("unitary", $unitary);
			$this->assign("cart_list", $cart);
		}

		$this->display();
	}

	public function my()
	{
		$this->wxregister();
		$where_userinfo["wecha_id"] = $this->wecha_id;
		$where_userinfo["token"] = $this->token;
		$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$find_user = $this->m_user->where($where_userinfo)->find();

		if ($find_user["name"] != NULL) {
			$find_userinfo["wechaname"] = $find_user["name"];
		}

		$this->assign("user", $find_userinfo);
		$this->display();
	}

	public function mypayinfo()
	{
		$this->wxregister();
		$where_unitary["id"] = $_GET["uid"];
		$where_unitary["token"] = $this->token;
		$find_unitary = $this->m_unitary->where($where_unitary)->find();
		$this->assign("unitary", $find_unitary);

		if ($find_unitary == NULL) {
			$this->error("此商品已删除", U("Unitary/mypay", array("token" => $this->token)));
		}

		if ($find_unitary["state"] = 2) {
			$where_lucknum_find["unitary_id"] = $_GET["uid"];
			$where_lucknum_find["token"] = $this->token;
			$where_lucknum_find["state"] = 1;
			$where_lucknum_find["lucknum"] = $find_unitary["lucknum"];
			$find_lucknum = $this->m_lucknum->where($where_lucknum_find)->find();
			$where_userinfo["token"] = $this->token;
			$where_userinfo["wecha_id"] = $find_lucknum["wecha_id"];
			$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();
			$find_user = $this->m_user->where($where_userinfo)->find();

			if ($find_user["name"] == NULL) {
				$find_lucknum["name"] = $find_userinfo["wechaname"];
			}
			else {
				$find_lucknum["name"] = $find_user["name"];
			}

			$this->assign("lucker", $find_lucknum);
		}

		$where_cart["state"] = 1;
		$where_cart["token"] = $this->token;
		$where_cart["unitary_id"] = $_GET["uid"];
		$cart_list = $this->m_cart->where($where_cart)->select();
		$pay_count = 0;

		foreach ($cart_list as $vo ) {
			$pay_count = $pay_count + $vo["count"];
		}

		$this->assign("pay_count", $pay_count);
		$where_lucknum["token"] = $this->token;
		$where_lucknum["wecha_id"] = $this->wecha_id;
		$where_lucknum["unitary_id"] = $_GET["uid"];
		$lucknum_list = $this->m_lucknum->where($where_lucknum)->select();
		$this->assign("lucknum_list", $lucknum_list);
		$this->display();
	}

	public function zhuijia()
	{
		$this->wxregister();
		$add_cart["token"] = $this->token;
		$add_cart["wecha_id"] = $this->wecha_id;
		$add_cart["count"] = abs(intval($_GET["buynum"]));
		if (($add_cart["count"] == 0) || ($add_cart["count"] == "")) {
			$this->error("数量请填写正整数");
		}

		$add_cart["unitary_id"] = $_GET["uid"];
		$id_cart = $this->m_cart->add($add_cart);

		if (0 < $id_cart) {
			$this->redirect("Unitary/cart", array("token" => $this->token));
		}
	}

	public function buyres()
	{
		$this->wxregister();
		$where_cart["unitary_id"] = $_GET["unitaryid"];
		$where_cart["token"] = $this->token;
		$where_cart["state"] = 1;
		$cart_list = $this->m_cart->where($where_cart)->select();

		foreach ($cart_list as $k => $vo ) {
			$where_userinfo["token"] = $this->token;
			$where_userinfo["wecha_id"] = $vo["wecha_id"];
			$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();
			$find_user = $this->m_user->where($where_userinfo)->find();

			if ($find_user["name"] == NULL) {
				$cart_list[$k]["name"] = $find_userinfo["wechaname"];
			}
			else {
				$cart_list[$k]["name"] = $find_user["name"];
			}

			$cart_list[$k]["pic"] = $find_userinfo["portrait"];
		}

		$this->assign("cart_list", $cart_list);
		$this->display();
	}

	public function goodsresult()
	{
		$this->wxregister();
		$where_unitary["id"] = $_GET["unitaryid"];
		$where_unitary["token"] = $this->token;
		$find_unitary = $this->m_unitary->where($where_unitary)->find();
		$this->assign("unitary", $find_unitary);

		switch ($find_unitary["state"]) {
		case "":
			$this->error("此商品已删除", U("Unitary/index", array("token" => $this->token)));
			exit();
			break;

		case 0:
			$this->error("此商品已删除", U("Unitary/index", array("token" => $this->token)));
			exit();
			break;

		case 1:
			$this->success("此商品还在进行中", U("Unitary/goodswhere", array("token" => $this->token, "unitaryid" => $_GET["unitaryid"])));
			exit();
			break;
		}

		$where_lucknum1["id"] = array("gt", $find_unitary["lasttime"]);
		$where_lucknum1["token"] = $this->token;
		$lucknum_list1 = $this->m_lucknum->where($where_lucknum1)->order("id desc")->limit(5)->select();
		$i1 = 0;

		foreach ($lucknum_list1 as $vo1 ) {
			$where_userinfo["token"] = $this->token;
			$where_userinfo["wecha_id"] = $vo1["wecha_id"];
			$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();
			$find_user = $this->m_user->where($where_userinfo)->find();

			if ($find_user["name"] == NULL) {
				$lucknum_list1[$i1]["name"] = $find_userinfo["wechaname"];
			}
			else {
				$lucknum_list1[$i1]["name"] = $find_user["name"];
			}

			$i1++;
		}

		$this->assign("list1", $lucknum_list1);
		$where_lucknum2["id"] = array("elt", $find_unitary["lasttime"]);
		$where_lucknum2["token"] = $this->token;
		$lucknum_list2 = $this->m_lucknum->where($where_lucknum2)->order("id desc")->limit($find_unitary["lastnum"])->select();
		$i2 = 0;

		foreach ($lucknum_list2 as $vo2 ) {
			$where_userinfo["token"] = $this->token;
			$where_userinfo["wecha_id"] = $vo2["wecha_id"];
			$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();
			$find_user = $this->m_user->where($where_userinfo)->find();

			if ($find_user["name"] == NULL) {
				$lucknum_list2[$i2]["name"] = $find_userinfo["wechaname"];
			}
			else {
				$lucknum_list2[$i2]["name"] = $find_user["name"];
			}

			$i2++;
		}

		$this->assign("list2", $lucknum_list2);
		$sum = 0;

		foreach ($lucknum_list2 as $vo ) {
			$thistime = floor($vo["addtime"] / 1000);
			$ms = substr($vo["addtime"], -3);
			$sum = $sum + (date("h", $thistime) . date("i", $thistime) . date("s", $thistime) . $ms);
		}

		$lucknum = fmod($sum, $find_unitary["price"]);
		$this->assign("sum", $sum);
		$this->assign("lucknum", $lucknum);
		$where_lucknum3["id"] = array("lt", $lucknum_list2[$find_unitary["lastnum"] - 1]["id"]);
		$where_lucknum3["token"] = $this->token;
		$lucknum_list3 = $this->m_lucknum->where($where_lucknum3)->order("id desc")->limit(5)->select();
		$i3 = 0;

		foreach ($lucknum_list3 as $vo3 ) {
			$where_userinfo["token"] = $this->token;
			$where_userinfo["wecha_id"] = $vo2["wecha_id"];
			$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();
			$find_user = $this->m_user->where($where_userinfo)->find();

			if ($find_user["name"] == NULL) {
				$lucknum_list3[$i3]["name"] = $find_userinfo["wechaname"];
			}
			else {
				$lucknum_list3[$i3]["name"] = $find_user["name"];
			}

			$i3++;
		}

		$this->assign("list3", $lucknum_list3);
		$this->display();
	}

	public function myluck()
	{
		$this->wxregister();
		$where_lucknum["token"] = $this->token;
		$where_lucknum["wecha_id"] = $this->wecha_id;
		$where_lucknum["state"] = 1;
		$lucknum_list = $this->m_lucknum->where($where_lucknum)->select();

		foreach ($lucknum_list as $vo ) {
			$where_unitary["token"] = $this->token;
			$where_unitary["id"] = $vo["unitary_id"];
			$find_unitary = $this->m_unitary->where($where_unitary)->find();

			if ($find_unitary["endtime"] < time()) {
				$vo["unitary"] = $find_unitary;
				$luck[] = $vo;
			}
		}

		if ($luck == NULL) {
			$this->assign("nothing", "yes");
		}

		$this->assign("luck", $luck);
		$this->display();
	}

	public function register()
	{
		$this->wxregister();
		$where_user["wecha_id"] = $this->wecha_id;
		$where_user["token"] = $this->token;
		$find_user = $this->m_user->where($where_user)->find();

		if ($find_user != NULL) {
			$this->assign("user", $find_user);
		}

		$this->display();
	}

	public function doregister()
	{
		$this->wxregister();
		$where_user["wecha_id"] = $this->wecha_id;
		$where_user["token"] = $this->token;
		$find_user = $this->m_user->where($where_user)->find();

		if ($find_user == NULL) {
			$add_user["token"] = $this->token;
			$add_user["wecha_id"] = $this->wecha_id;
			$add_user["address"] = $_GET["address"];
			$id_usre = $this->m_user->add($add_user);

			if (0 < $id_usre) {
				$this->success("添加成功", U("Unitary/my", array("token" => $this->token)));
			}
		}
		else {
			$save_user["address"] = $_GET["address"];
			$update_user = $this->m_user->where($where_user)->save($save_user);
			$this->success("修改成功", U("Unitary/my", array("token" => $this->token)));
		}
	}

	public function address()
	{
		$this->wxregister();
		$where_user["wecha_id"] = $this->wecha_id;
		$where_user["token"] = $this->token;
		$find_user = $this->m_user->where($where_user)->find();
		$where_userinfo["token"] = $this->token;
		$where_userinfo["wecha_id"] = $this->wecha_id;
		$find_userinfo = $this->m_userinfo->where($where_userinfo)->find();

		if ($find_user["name"] == NULL) {
			$find_user["name"] = $find_userinfo["wechaname"];
		}

		$find_user["phone"] = $find_userinfo["tel"];
		$this->assign("user", $find_user);
		$this->display();
	}

	public function doaddress()
	{
		$this->wxregister();
		$where_user["wecha_id"] = $this->wecha_id;
		$where_user["token"] = $this->token;
		$find_user = $this->m_user->where($where_user)->find();

		if ($find_user == NULL) {
			$add_user["token"] = $this->token;
			$add_user["wecha_id"] = $this->wecha_id;
			$add_user["address"] = $_GET["address"];
			$add_user["name"] = $_GET["name"];
			$id_usre = $this->m_user->add($add_user);
			$save_userinfo["tel"] = $_GET["phone"];
			$update_userinfo = $this->m_userinfo->where($where_user)->save($save_userinfo);

			if (0 < $id_usre) {
				$this->redirect("Unitary/dobuy", array("token" => $this->token));
			}
		}
		else {
			$save_user["address"] = $_GET["address"];
			$save_user["name"] = $_GET["name"];
			$update_user = $this->m_user->where($where_user)->save($save_user);
			$save_userinfo["tel"] = $_GET["phone"];
			$update_userinfo = $this->m_userinfo->where($where_user)->save($save_userinfo);
			$this->redirect("Unitary/dobuy", array("token" => $this->token));
		}
	}
}


?>
