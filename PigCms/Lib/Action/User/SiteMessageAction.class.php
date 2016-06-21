<?php

class SiteMessageAction extends UserAction
{
	public function index()
	{
		$where = array("token" => $this->token, "relation" => "0", "status" => empty($_GET["status"]) ? 0 : 1);
		$model = M("SiteMessage");

		if (IS_POST) {
			if ($_POST["token"] != $this->token) {
				exit("no token");
			}

			$ids = implode(",", $_POST["id"]);

			if (empty($ids)) {
				$this->error("请选择");
			}

			if ("delete" == $_POST["type"]) {
				$back = $model->where(array(
	"token" => $this->token,
	"id"    => array("IN", $ids)
	))->delete();

				if ($back == true) {
					$this->success("删除成功", U("SiteMessage/index", array("token" => $this->token, "status" => $_GET["status"])));
				}
				else {
					$this->error("删除失败");
				}
			}
			else {
				$back = $model->where(array(
	"token" => $this->token,
	"id"    => array("IN", $ids)
	))->setField(array("status" => "1"));

				if ($back == true) {
					$this->success("设置已读成功", U("SiteMessage/index", array("token" => $this->token, "status" => $_GET["status"])));
				}
				else {
					$this->error("设置已读失败");
				}
			}
		}
		else {
			$count = $model->where($where)->count();
			$Page = new Page($count, 20);
			$lists = $model->where($where)->order("id DESC")->limit($Page->firstRow . "," . $Page->listRows)->select();
			$this->assign("lists", $lists);
			$this->assign("page", $Page->show());
			$this->display();
		}
	}

	public function del($id)
	{
		$back = M("SiteMessage")->where(array("token" => $this->token, "id" => (int) $id))->delete();

		if ($back == true) {
			$this->success("删除成功", U("SiteMessage/index", array("status" => $_GET["status"])));
		}
		else {
			$this->error("删除失败");
		}
	}

	public function view($id)
	{
		$result = M("SiteMessage")->where(array("token" => $this->token, "id" => (int) $id))->find();

		if (empty($result)) {
			$this->error("非法操作！");
		}

		if (empty($result["status"])) {
			$result["read_time"] = time();
			$result["status"] = 1;
			M("SiteMessage")->save($result);
		}

		$this->assign("result", $result);
		$this->display();
	}

	public function status($id)
	{
		$result = M("SiteMessage")->where(array("token" => $this->token, "id" => (int) $id))->find();

		if (empty($result)) {
			$this->error("非法操作！");
		}

		if (empty($result["read_time"])) {
			$result["read_time"] = time();
		}

		$result["status"] = !$result["status"];
		M("SiteMessage")->save($result);
		$json["html"] = ($result["status"] ? "<span style=\"color:green;\">已读</span>" : "<span style=\"color:red;\">未读</span>");
		exit($json["html"]);
	}
}


?>
