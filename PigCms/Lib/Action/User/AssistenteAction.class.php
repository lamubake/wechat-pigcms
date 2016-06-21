<?php

class AssistenteAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		$this->canUseFunction("Assistente");
	}

	public function index()
	{
		$company_staff = M("company_staff");
		$total = $company_staff->where(array("token" => $this->token))->count();
		$Page = new Page($total, 10);
		$list_staff = $company_staff->where(array("token" => $this->token))->limit($Page->firstRow . "," . $Page->listRows)->order("id desc")->select();
		$this->assign("list_staff", $list_staff);
		$this->assign("token", $this->token);
		$this->assign("page", $Page->show());
		$this->display();
	}

	public function add()
	{
		if (IS_POST) {
			$name = $this->_post("name", "trim");
			$username = $this->_post("username", "trim");
			$password = $this->_post("password", "trim");
			$funcs = $this->_post("func");
			$tel = $this->_post("tel", "trim");
			$role = $this->_post("pcorwap");

			if (empty($name)) {
				$this->error("店员姓名不能为空");
				exit();
			}

			if (empty($username)) {
				$this->error("用户名不能为空");
				exit();
			}

			if (empty($funcs)) {
				$this->error("功能模块至少要有一项");
				exit();
			}

			if (empty($role)) {
				$this->error("权限类型至少要选一个");
				exit();
			}

			if (!empty($role[0]) && !empty($role[1])) {
				$pcorwap = 3;
			}
			else {
				$pcorwap = $role[0];
			}

			$data = array();
			$data["name"] = $name;
			$data["username"] = $username;
			$data["tel"] = $tel;
			$data["func"] = implode(",", $funcs);
			$data["password"] = $password;
			$data["pcorwap"] = $pcorwap;
			$data["wecha_id"] = $_POST["wecha_id"];
			$id = $this->_post("id", "intval");

			if (!$this->checkUserName($username, $id)) {
				$this->error("该用户名已存在,请重新输入");
				exit();
			}

			if (empty($id)) {
				if (empty($password)) {
					$this->error("添加时密码不能为空");
					exit();
				}

				$data["token"] = $this->token;
				$data["time"] = time();
				$add = M("company_staff")->add($data);

				if ($add) {
					$this->success("添加成功", U("Assistente/index", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("添加失败");
					exit();
				}
			}
			else {
				if (empty($password)) {
					unset($data["password"]);
				}

				$update = M("company_staff")->where(array("id" => $id))->save($data);

				if ($update) {
					$this->success("修改成功", U("Assistente/index", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("修改失败");
					exit();
				}
			}
		}

		$id = $this->_get("id", "intval");
		$info = D("User_group")->getGroup(array("id" => (int) $_SESSION["gid"]));
		$this->assign("info", $info);

		if (!empty($id)) {
			$staff_info = M("company_staff")->where(array("id" => $id, "token" => $this->token))->find();
			$this->assign("staff_info", $staff_info);
		}

		$func_allow = explode(",", $info["func"]);
		$func = M("Function")->where(array("status" => 1))->field("funname,name,id")->select();
		$not_exists = array("tianqi", "qiushi", "jishuan", "langdu", "jiankang", "kuaidi", "xiaohua", "changtoushi", "peiliao", "liaotian", "mengjian", "yuyinfanyi", "huoche", "gongjiao", "shenfenzheng", "shouji", "yinle", "fujin", "geci", "fanyi", "api", "suanming", "baike", "caipiao", "Zhida", "whois", "Assistente", "groupmessage");
		$isFuwu = $this->check_fuwu_exist();

		if (!$isFuwu) {
			array_push($not_exists, "Fuwu");
		}

		foreach ((array) $func as $key => $val ) {
			if (!in_array($val["funname"], $not_exists) && in_array($val["funname"], $func_allow)) {
				$newfunc[$val["funname"]] = $val;
			}
		}

		$this->assign("func", $newfunc);
		$this->display();
	}

	public function del()
	{
		$id = $this->_get("id", "intval");
		$exists = M("company_staff")->where(array("id" => $id, "token" => $this->token))->find();

		if ($exists) {
			$delete = M("company_staff")->where(array("id" => $id, "token" => $this->token))->delete();

			if ($delete) {
				$this->success("删除成功", U("Assistente/index", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("删除失败");
				exit();
			}
		}
		else {
			$this->error("没有找到删除项");
			exit();
		}

		$this->display();
	}

	private function checkUserName($username, $id)
	{
		if ($username == "") {
			return false;
		}

		if ($id == "") {
			$thisUsername = M("company_staff")->where(array("token" => $this->token, "username" => $username))->count();

			if (0 < $thisUsername) {
				return false;
			}
		}
		else {
			$name = M("company_staff")->where(array("id" => $id))->getField("username");

			if ($name != $username) {
				$thisUsername = M("company_staff")->where(array("token" => $this->token, "username" => $username))->count();

				if (0 < $thisUsername) {
					return false;
				}
			}
		}

		return true;
	}

	public function ajaxCheckname()
	{
		$username = $_POST["username"];
		$id = $_POST["id"];

		if ($id == "") {
			$thisUsername = M("company_staff")->where(array("token" => $this->token, "username" => $username))->count();

			if (0 < $thisUsername) {
				exit("1");
			}
			else {
				exit("0");
			}
		}
		else {
			$name = M("company_staff")->where(array("id" => $id))->getField("username");

			if ($name != $username) {
				$thisUsername = M("company_staff")->where(array("token" => $this->token, "username" => $username))->count();

				if (0 < $thisUsername) {
					exit("1");
				}
				else {
					exit("0");
				}
			}
		}
	}
}


?>
