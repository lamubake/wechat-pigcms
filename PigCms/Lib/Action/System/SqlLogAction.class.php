<?php

class SqlLogAction extends BackAction
{
	public function index()
	{
		$system = A("System/System");
		$system->createSqlLogTable();
		$records = M("SqlLog");
		$where["status"] = (int) $_GET["status"];

		if (!isset($_GET["status"])) {
			$where = array();
		}

		$count = $records->where($where)->count();
		$page = new Page($count, 25);
		$show = $page->show();
		$info = $records->where($where)->limit($page->firstRow . "," . $page->listRows)->order("time DESC")->select();
		$this->assign("page", $show);
		$this->assign("info", $info);
		$this->display();
	}

	public function redo($id)
	{
		$system = A("System/System");
		$model = M("SqlLog")->find($id);
		$time = M("SqlLog")->where(array(
	"time" => array("lt", $model["time"])
	))->order("time DESC")->getField("time");
		$url = $system->server_url . "sqlserver.php?key=" . $system->key . "&excute=1&lastsqlupdate=" . $time . "&domain=" . $system->topdomain . "&dirtype=" . $system->dirtype . "&usingDomain=" . $system->useUrl . "&b=1";
		M("System_info")->where("version>0")->save(array("currentsqlid" => $model["time"]));
		$result = $system->getcontents($url);
		$result = json_decode($result, 1);

		if (intval($result["success"]) < 0) {
			$this->error($result["msg"]);
		}
		else {
			D("SqlLog")->run($result);
			M("System_info")->where("version>0")->save(array("currentsqlid" => 0));
			$this->success("重新升级这条SQL成功");
		}
	}
}


?>
