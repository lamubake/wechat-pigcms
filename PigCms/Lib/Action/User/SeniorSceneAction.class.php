<?php

class SeniorSceneAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		$this->canUseFunction("SeniorScene");
	}

	public function index()
	{
		$search = $this->_post("search", "trim");
		$where = array("token" => $this->token);

		if ($search) {
			$where["name|keyword"] = array("like", "%" . $search . "%");
		}

		$count = M("Senior_scene")->where($where)->count();
		$Page = new Page($count, 15);
		$list = M("Senior_scene")->where($where)->order("add_time desc")->limit($Page->firstRow . "," . $Page->listRows)->select();
		$this->assign("page", $Page->show());
		$this->assign("list", $list);
		$this->display();
	}

	public function add()
	{
		$keyword_db = M("Keyword");
		$id = $this->_get("id", "intval");
		$where = array("token" => $this->token, "id" => $id);
		$seniorScene_info = M("Senior_scene")->where($where)->find();

		if (IS_POST) {
			if (D("Senior_scene")->create()) {
				if ($seniorScene_info) {
					$up_where = array("token" => $this->token, "id" => $this->_post("id", "intval"));
					M("Senior_scene")->where($where)->save($_POST);
					$this->handleKeyword($this->_post("id", "intval"), "SeniorScene", $this->_post("keyword", "trim"));
					$this->success("修改成功", U("SeniorScene/index", array("token" => $this->token)));
				}
				else {
					$_POST["token"] = $this->token;
					$_POST["add_time"] = time();
					$id = M("Senior_scene")->add($_POST);
					$this->handleKeyword($id, "SeniorScene", $this->_post("keyword", "trim"));
					$this->success("添加成功", U("SeniorScene/index", array("token" => $this->token)));
				}
			}
			else {
				$this->error(D("Senior_scene")->getError());
			}
		}
		else {
			$this->assign("info", $seniorScene_info);
			$this->display();
		}
	}

	public function del()
	{
		$id = $this->_get("id", "intval");
		$where = array("token" => $this->token, "id" => $id);

		if (M("Senior_scene")->where($where)->delete()) {
			M("Keyword")->where(array("token" => $this->token, "pid" => $id, "module" => "SeniorScene"))->delete();
			$this->success("删除", U("SeniorScene/index", array("token" => $this->token)));
		}
	}

	public function highLive()
	{
		$vlist = $this->_get("v", "trim");
		$wxinfo = M("Wxuser")->where(array("uid" => intval(session("uid")), "token" => $this->token))->find();
		$PData = array("uname" => $this->token, "domain" => $_SERVER["HTTP_HOST"], "email" => $wxinfo["qq"], "gzh" => $wxinfo["wxid"], "gzhname" => $wxinfo["wxname"]);
		$key = "Y@2T&9i3l#m8u";
		$tmp = array();

		foreach ($PData as $kk => $vv ) {
			$tmp[] = md5($kk . trim($vv) . $key);
		}

		$key = base64_encode(implode("_", $tmp));
		$PData["key"] = $key;
		$request_url = "http://www.meihua.com/index.php?m=Index&c=login&a=verifyUser";
		$responsearr = $this->httpRequest($request_url, "POST", $PData);
		$tmpdata = trim($responsearr[1]);

		if ($tmpdata == "") {
			$responsearr = $this->httpRequest($request_url, "POST", $PData);
			$tmpdata = trim($responsearr[1]);
		}

		$iframeUrl = "http://www.meihua.com/index.php?m=Index&c=login&a=logNoPwd&logkey=" . $tmpdata;

		if ($vlist == "myscene") {
			$iframeUrl = "http://www.meihua.com/index.php?m=Index&c=login&a=logNoPwd&view=mylist&logkey=" . $tmpdata;
		}

		$this->assign("iframeUrl", $iframeUrl);
		$this->display();
	}
}


?>
