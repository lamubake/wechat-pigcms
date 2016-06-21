<?php

class CompanyAction extends UserAction
{
	public $token;
	public $isBranch;
	public $company_model;
	private $_business_type = array("Repast" => "订餐", "Store" => "商城", "DishOut" => "外卖", "Hotels" => "酒店");

	public function _initialize()
	{
		parent::_initialize();
		$this->token = session("token");
		$this->assign("token", $this->token);

		if ($this->token != $_GET["token"]) {
			exit();
		}

		$this->isBranch = 0;
		if (isset($_GET["isBranch"]) && intval($_GET["isBranch"])) {
			$this->isBranch = 1;
		}

		$this->assign("isBranch", $this->isBranch);
		$this->company_model = M("Company");
	}

	public function index()
	{
		$where = array("token" => $this->token);

		if ($this->isBranch) {
			$where["id"] = intval($_GET["id"]);
			$where["isbranch"] = 1;
		}
		else {
			$where["isbranch"] = 0;
		}

		$thisCompany = $this->company_model->where($where)->find();
		$max_id = $this->company_model->where(array("token" => $this->token))->max("id");

		if (IS_POST) {
			$_POST["password"] = (isset($_POST["password"]) && $_POST["password"] ? md5(trim($_POST["password"])) : "");
			$_POST["business_type"] = implode(",", $_POST["business_type"]);

			if (empty($_POST["tel"])) {
				$this->error("电话不能为空");
			}

			if (empty($_POST["logourl"])) {
				$this->error("Logo地址不能为空");
			}

			if ($_POST["avg_price"] == "") {
				$this->error("人均消费不能为空");
			}
			else if (!is_numeric($_POST["avg_price"])) {
				$this->error("人均消费请输入数字");
			}

			if (empty($_POST["business_type"]) && $this->isBranch) {
				$this->error("经营类型不能为空");
			}

			$jump_url = "index";

			if (!$thisCompany) {
				if (empty($_POST["name"])) {
					$this->error("名称不能为空");
				}

				if (empty($_POST["province"]) || empty($_POST["city"]) || empty($_POST["district"])) {
					$this->error("门店地址省市地区不能为空");
				}

				if ($_POST["longitude"] == "") {
					$this->error("经度不能为空");
				}

				if ($_POST["latitude"] == "") {
					$this->error("纬度不能为空");
				}

				if (empty($_POST["address"])) {
					$this->error("详细地址不能为空");
				}

				if ($this->isBranch) {
					if (empty($_POST["username"])) {
						$this->error("分支登陆账号不能为空");
					}

					if (empty($_POST["password"])) {
						$this->error("分支登陆密码不能为空");
					}

					$jump_url = "branches";
				}

				$_POST["sid"] = uniqid();
				$_POST["available_state"] = 2;
				$_POST["add_time"] = $_SERVER["REQUEST_TIME"];
				$company_id = $this->company_model->add($_POST);

				if ($company_id) {
					$coupons = new WechatCoupons($this->wxuser);
					$res = $coupons->addCompany($_POST);
					S($this->token . "_shoplist", NULL);
					$this->success("添加成功", U("Company/" . $jump_url, array("token" => $this->token, "isBranch" => $this->isBranch)));
					exit();
				}
				else {
					$this->error("添加失败");
				}
			}
			else {
				if (($this->wxuser["winxintype"] == 3) && (($thisCompany["available_state"] == 2) || ($thisCompany["available_state"] == 0))) {
					$this->error("门店还在审核中");
				}

				$amap = new amap();
				if (!$thisCompany["amapid"] && ($thisCompany["longitude"] == $_POST["longitude"])) {
					$locations = $amap->coordinateConvert($thisCompany["longitude"], $thisCompany["latitude"]);
					$_POST["longitude"] = $locations["longitude"];
					$_POST["latitude"] = $locations["latitude"];
				}

				if (!$thisCompany["amapid"]) {
					$ampaid = $amap->create($_POST["name"], $_POST["longitude"] . "," . $_POST["latitude"], $_POST["tel"], $_POST["address"]);
					$_POST["amapid"] = intval($ampaid);
				}
				else {
					$amap->update($thisCompany["amapid"], $_POST["name"], $_POST["longitude"] . "," . $_POST["latitude"], $_POST["tel"], $_POST["address"]);
				}

				if ($this->company_model->create()) {
					if (empty($_POST["password"])) {
						unset($_POST["password"]);
					}

					$jump_url = ($this->isBranch ? "branches" : "index");

					if ($thisCompany["logourl"] == $_POST["logourl"]) {
						unset($_POST["logourl"]);
					}

					$_POST["location_id"] = $thisCompany["location_id"];
					$update = $this->company_model->where($where)->save($_POST);

					if ($update) {
						$coupons = new WechatCoupons($this->wxuser);
						$res = $coupons->updatepoi($_POST);
						$this->success("修改成功", U("Company/" . $jump_url, array("token" => $this->token, "isBranch" => $this->isBranch)));
						exit();
					}
					else {
						$this->error("修改失败");
					}
				}
				else {
					$this->error($this->company_model->getError());
				}
			}
		}
		else {
			if (S($this->token . "_category_list") != "") {
				$category_list = S($this->token . "_category_list");
			}
			else {
				$category_list = $this->category_list();
				S($this->token . "_category_list", $category_list);
			}

			$this->assign("category_list", $category_list["category_list"]);
			$thisCompany["business_type"] = explode(",", $thisCompany["business_type"]);
			$this->assign("winxintype", $this->wxuser["winxintype"]);
			$this->assign("set", $thisCompany);
			$this->display();
		}
	}

	public function branches()
	{
		$thisCompany = $this->company_model->where(array("token" => $this->token))->order("id ASC")->find();
		$where = array("token" => $this->token);
		$where = array("token" => $this->token, "isbranch" => 1);
		$branches = $this->company_model->where($where)->order("taxis ASC")->select();
		$list = array();

		foreach ($branches as $b ) {
			$b["url"] = $_SERVER["HTTP_HOST"] . "/index.php?m=Index&a=clogin&cid=" . $b["id"] . "&k=" . md5($b["id"] . $b["username"]);
			$t_business = explode(",", $b["business_type"]);
			$b["business_type_name"] = "";
			$pre = "";

			foreach ($t_business as $v ) {
				$b["business_type_name"] .= $pre . $this->_business_type[$v];
				$pre = ",";
			}

			$list[] = $b;
		}

		$this->assign("branches", $list);
		$this->assign("winxintype", $this->wxuser["winxintype"]);
		$this->display();
	}

	public function delete()
	{
		$where = array("token" => $this->token, "id" => intval($_GET["id"]));
		$thisCompany = $this->company_model->where($where)->find();
		$where = array("token" => $this->token, "id" => intval($_GET["id"]));
		$jump = ($_GET["branches"] == 1 ? "branches" : "index");
		$thisCompany = $this->company_model->where($where)->find();
		if (($this->wxuser["winxintype"] == 3) && ($thisCompany["available_state"] == 2)) {
			$this->error("门店还在审核中");
		}

		$rt = $this->company_model->where($where)->delete();

		if ($rt == true) {
			$amap = new amap();
			$amap->delete($thisCompany["amapid"]);

			if ($thisCompany["location_id"] == "") {
				$coupons = new WechatCoupons($this->wxuser);
				$res = $coupons->delpoi($thisCompany["location_id"]);
			}

			$this->success("删除成功", U("Company/" . $jump, array("token" => $this->token, "isBranch" => $_GET["isBranch"])));
			exit();
		}
		else {
			$this->error("服务器繁忙,请稍后再试", U("Company/" . $jump, array("token" => $this->token, "isBranch" => $_GET["isBranch"])));
		}
	}

	public function updateallpoi()
	{
		$company = $this->company_model->where(array(
	"token"           => $this->token,
	"available_state" => array("neq", 3)
	))->select();

		if (!$company) {
			return false;
		}

		$jump = ($_GET["isBranch"] == 1 ? "branches" : "index");
		$wxUser = M("Wxuser")->where(array("token" => $this->token))->find();
		$apiOauth = new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($wxUser["appid"]);
		$url = "https://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token=" . $access_token;
		$json = "{\"begin\":0,\"limit\":50}";
		$res = $apiOauth->https_request($url, $json);
		$business_list = $res["business_list"];

		foreach ($business_list as $key => $val ) {
			if ($val["base_info"]["sid"] != "") {
				$this->company_model->where(array("token" => $this->token, "id" => $val["base_info"]["sid"]))->save(array("location_id" => $val["base_info"]["poi_id"], "available_state" => $val["base_info"]["available_state"]));
			}
		}

		$this->success("更新成功", U("Company/" . $jump, array("token" => $this->token, "isBranch" => $_GET["isBranch"])));
		exit();
	}

	private function category_list()
	{
		$coupons = new WechatCoupons($this->wxuser);
		$res = $coupons->category_list();
		return $res;
	}
}


?>
