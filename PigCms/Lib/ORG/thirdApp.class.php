<?php

class thirdApp
{
	public $name;

	public function __construct()
	{
		$this->serverUrl = getUpdateServer();
		$this->key = trim(C("server_key"));
		$this->topdomain = trim(C("server_topdomain"));

		if (!$this->topdomain) {
			$this->topdomain = $this->getTopDomain();
		}

		$this->token = $token;
	}

	public function modules()
	{
		return array("music", "yinle", "mengjian", "kuaidi", "tianqi", "baike", "geci", "suanming", "dianying", "feiji");
	}

	public function yinle($name)
	{
		return $this->music($name);
	}

	public function music($name)
	{
		if (C("emergent_mode")) {
			return array("亲爱的，由于版权问题音乐功能暂未开放", "text");
		}

		$name = implode("", $name);
		$url = $this->serverUrl . "server.php?m=server&c=thirdApp&a=music&key=" . $this->key . "&domain=" . $this->topdomain . "&name=" . $name;
		$rt = $this->curlGet($url);

		if (strpos($rt, "ttp")) {
			return array(
	array($name, $name, $rt, $rt),
	"music"
	);
		}
		else {
			return array("亲爱的，由于版权问题音乐功能暂未开放", "text");
		}
	}

	public function feiji($data)
	{
		$data = array_merge($data);

		if (count($data) < 2) {
			return array("正确使用方法：明天北京飞拉萨的飞机", "text");
		}

		$info = $data[2] . $data[1] . "飞" . $data[0] . "的飞机 ";
		$url = apiServer::getApiUrltu() . "?key=" . apiServer::getApiKeyID()["key"] . "&info=" . $info;
		$json = $this->curlGet($url);

		if (empty($json)) {
			return array("哎呀，暂时没找到" . $data[1] . "--" . $data[0] . "的飞机", "text");
		}

		$datas = json_decode($json, true);

		if ($datas["list"]) {
			$str .= "\n【Hi，以下是为您找到航班信息】";

			foreach ($datas["list"] as $lists ) {
				$str .= "\n『航班』{$lists["flight"]}";

				if ($lists["route"]) {
					$str .= "\n 航班路线 {$lists["route"]}";
				}

				if ($lists["state"]) {
					$str .= "\n 航班状态 {$lists["state"]}";
				}

				$str .= "\n『起』{$lists["starttime"]} -『达』{$lists["endtime"]}";
				$str .= "\n*****************************";
			}
		}
		else {
			return array("哎呀，暂时没找到" . $data[1] . "--" . $data[0] . "的飞机", "text");
		}

		return $str;
	}

	public function dianying($name)
	{
		$name = implode("", $name);

		if (empty($name)) {
			return "温馨提醒您正确的使用方法是[电影+电影名] \n 比如：电影饥饿游戏 或者 电影现在热播";
		}

		$url = apiServer::getApiUrltu() . "?key=" . apiServer::getApiKeyID()["key"] . "&info=电影" . $name;
		$rt = $this->curlGet($url);
		$data = json_decode($rt, true);

		if ($data["text"]) {
			$stxt = $data["text"] . "<a href='{$data["url"]}'>$name</a>";
			return array($stxt, "text");
		}
		else {
			return "哇，电影库都木有你要看的片";
		}
	}

	public function gongjiao($data)
	{
		$data = array_merge($data);

		if (count($data) < 2) {
			$this->error_msg();
			return false;
		}

		$json = file_get_contents("http://www.twototwo.cn/bus/Service.aspx?format=json&action=QueryBusByLine&key=a3f88d7c-86b6-4815-9dae-70668fc1f0d5&zone=" . $data[0] . "&line=" . $data[1]);
		$data = json_decode($json);
		$xianlu = $data->Response->Head->XianLu;
		$xdata = get_object_vars($xianlu->ShouMoBanShiJian);
		$xdata = $xdata["#cdata-section"];
		$piaojia = get_object_vars($xianlu->PiaoJia);
		$xdata = $xdata . " -- " . $piaojia["#cdata-section"];
		$main = $data->Response->Main->Item->FangXiang;
		$xianlu = $main[0]->ZhanDian;
		$str = "【本公交途经】\n";

		for ($i = 0; $i < count($xianlu); $i++) {
			$str .= "\n" . trim($xianlu[$i]->ZhanDianMingCheng);
		}

		return $str;
	}

	public function suanming($name)
	{
		$name = implode("", $name);

		if (empty($name)) {
			return "温馨提醒您正确的使用方法是[算命+姓名]";
		}

		$url = apiServer::getApiUrltu() . "?key=" . apiServer::getApiKeyID()["key"] . "&info=算命" . $name;
		$rt = $this->curlGet($url);
		$data = json_decode($rt, true);

		if ($data["text"]) {
			return array($data["text"], "text");
		}
		else {
			$data = require_once CONF_PATH . "suanming.php";
		}

		$num = mt_rand(0, 80);
		return $name . "\n" . trim($data[$num]);
	}

	public function geci($n)
	{
		$name = implode("", $n);
		$url = apiServer::getApiUrltu() . "?key=" . apiServer::getApiKeyID()["key"] . "&info=" . $name . "的歌词";
		$rt = $this->curlGet($url);
		$data = json_decode($rt, true);

		if ($data["text"]) {
			return array($data["text"], "text");
		}
		else {
			return array("没找到" . $name . "相应的歌词", "text");
		}
	}

	public function baike($name)
	{
		$name = implode("", $name);
		$url = apiServer::getApiUrltu() . "?key=" . apiServer::getApiKeyID()["key"] . "&info=百科" . $name;
		$rt = $this->curlGet($url);
		$data = json_decode($rt, true);

		if ($data["text"]) {
			return array($data["text"], "text");
		}
		else {
			return array("没找到" . $name . "相应百科", "text");
		}
	}

	public function huoche($data, $time)
	{
		$data = array_merge($data);
		$info = $data[1] . "到" . $data[0] . "的火车";
		$url = apiServer::getApiUrltu() . "?key=" . apiServer::getApiKeyID()["key"] . "&info=" . $info;
		$json = $this->curlGet($url);

		if (empty($json)) {
			return array("哎呀，暂时没找到" . $data[1] . "--" . $data[0] . "的火车~", "text");
		}

		$datas = json_decode($json, true);

		if ($datas["list"]) {
			$str .= "\n【Hi，以下是为您找到列车信息】";

			foreach ($datas["list"] as $lists ) {
				$str .= "\n『车次』{$lists["trainnum"]}";
				$str .= "\n『起』{$lists["start"]} -『终』{$lists["terminal"]}";
				$str .= "\n『开』{$lists["starttime"]} -『到』{$lists["endtime"]}";
				$str .= "\n*****************************";
			}
		}
		else {
			return array("哎呀，暂时没找到" . $data[1] . "--" . $data[0] . "的火车~", "text");
		}

		return $str;
	}

	public function mengjian($name)
	{
		if (empty($name)) {
			return array("周公睡着了哦,无法解此梦,这年头神仙也偷懒", "text");
		}

		$name = implode("", $name);
		$url = $this->serverUrl . "server.php?m=server&c=thirdApp&a=dream&key=" . $this->key . "&domain=" . $this->topdomain . "&name=梦见" . $name;
		$rt = $this->curlGet($url);

		if ($rt) {
			return array($rt, "text");
		}
		else {
			return array("周公睡着了啊,无法解此梦,这年头神仙也偷懒", "text");
		}
	}

	public function kuaidi($param)
	{
		if (empty($param[1])) {
			return array("此单号暂无物流信息，请稍后再查", "text");
		}

		$url = "http://m.kuaidi100.com/index_all.html?type=" . strval($param[0]) . "&postid=" . trim($param[1]);
		$link = "<a href='$url'>您好，您查询的【" . strval($param[0]) . "】单号为【" . trim($param[1]) . "】请点击查看详情</a>";
		return array($link, "text");
	}

	public function curlGet($url)
	{
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}

	public function tianqi($name)
	{
		$name = implode("", $name);
		$url = "http://api.map.baidu.com/telematics/v3/weather?location=" . $name . "&output=json&ak=hTXrtTGGcljoOMdf2jZcc1yD";
		$data = $this->curlGet($url);
		$data = json_decode($data, true);
		$str = "";
		$news = array();

		foreach ($data["results"] as $v ) {
			$str = "城市:『" . $v["currentCity"] . "』 PM2.5:『" . $v["pm25"] . "』\n";

			foreach ($v["weather_data"] as $vl ) {
				array_push($news, array($vl["date"] . " " . $vl["weather"] . " " . $vl["wind"] . " " . $vl["temperature"], "", $vl["dayPictureUrl"], $vl["nightPictureUrl"]));
			}

			foreach ($v["index"] as $l ) {
				$str1 .= "☞[" . $l["title"] . "] " . $l["zs"] . " 『" . $l["tipt"] . "』 " . $l["des"] . "\n";
			}

			array_push($news, array($str));
			array_push($news, array($str1));
		}

		return array($news, "news");

		if (!empty($str)) {
			return array($str, "text");
		}
		else {
			return array("哎呀，获取数据超时啦。", "text");
		}
	}

	public function getTopDomain()
	{
		$host = $_SERVER["HTTP_HOST"];
		$host = strtolower($host);

		if (strpos($host, "/") !== false) {
			$parse = @parse_url($host);
			$host = $parse["host"];
		}

		$topleveldomaindb = array("com", "edu", "gov", "int", "mil", "net", "org", "biz", "info", "pro", "name", "museum", "coop", "aero", "xxx", "idv", "mobi", "cc", "me");
		$str = "";

		foreach ($topleveldomaindb as $v ) {
			$str .= ($str ? "|" : "") . $v;
		}

		$matchstr = "[^\.]+\.(?:(" . $str . ")|\w{2}|((" . $str . ")\.\w{2}))\$";

		if (preg_match("/" . $matchstr . "/ies", $host, $matchs)) {
			$domain = $matchs[0];
		}
		else {
			$domain = $host;
		}

		return $domain;
	}
}


?>
