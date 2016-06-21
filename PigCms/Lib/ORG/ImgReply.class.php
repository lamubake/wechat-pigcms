<?php

class ImgReply
{
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public $action;
	public $keyword;
	public $thisWxUser = array();

	public function __construct($token, $wechat_id, $data, $siteUrl, $key)
	{
		if (empty($key)) {
			return false;
		}

		$this->wechat_id = $wechat_id;
		$this->siteUrl = $siteUrl;
		$this->token = $token;
		$this->thisWxUser = M("Wxuser")->field("appid,appsecret,winxintype")->where(array("token" => $token))->find();
		$this->keyword = $keyword = $key;
		$this->db = M($data["module"]);
		$like["keyword"] = $keyword;
		$like["precisions"] = 1;
		$like["token"] = $this->token;
		$data2 = M("keyword")->where($like)->order("id desc")->find();

		if (!$data2) {
			$like["keyword"] = array("like", "%" . $keyword . "%");
			$like["precisions"] = 0;
			$data2 = M("keyword")->where($like)->order("id desc")->find();
		}

		if (("img" == strtolower($data2["module"])) && (2 < $this->thisWxUser["winxintype"])) {
			$groupid = $this->getGroupId();
			$like2 = "( concat(',',wechat_group,',') LIKE '%,$groupid,%' OR wechat_group = '' ) ";

			if ($like["precisions"]) {
				$like2 .= "AND keyword = '" . $like["keyword"] . "' ";
			}
			else {
				$like2 .= "AND keyword LIKE '" . $like["keyword"][1] . "' ";
			}

			$like2 .= "AND precisions = '" . $like["precisions"] . "' ";
			$like2 .= "AND token = '" . $like["token"] . "' ";
			$like = $like2;
		}

		$this->item = M($data2["module"])->field("id,text,pic,url,title")->limit(9)->order("usort desc")->where($like)->select();
		$this->action = A("Home/Weixin");
	}

	public function getGroupId()
	{
		$apiOauth = new apiOauth();
		$access_token = $apiOauth->update_authorizer_access_token($this->thisWxUser["appid"]);
		$url = "https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=" . $access_token;
		$result = HttpClient::getInstance()->post($url, array("post" => json_encode(array("openid" => $this->wechat_id))));
		$result = json_decode($result);
		return $result->groupid;
	}

	public function index()
	{
		$this->action->api("requestdata", "imgnum");
		$idsWhere = "id in (";
		$comma = "";

		foreach ($this->item as $keya => $infot ) {
			$idsWhere .= $comma . $infot["id"];
			$comma = ",";

			if ($infot["url"] != false) {
				if (!strpos($infot["url"], "http") === false) {
					$url = $this->action->api("getFuncLink", html_entity_decode($infot["url"]));
				}
				else {
					$url = $this->action->api("getFuncLink", $infot["url"]);
				}
			}
			else {
				$url = rtrim($this->siteUrl, "/") . U("Wap/Index/content", array("token" => $this->token, "id" => $infot["id"], "wecha_id" => $this->data["FromUserName"]));
			}

			$url = str_replace(array("{changjingUrl}"), array("http://www.meihua.com"), $url);
			$return[] = array($infot["title"], $this->action->api("handleIntro", $infot["text"]), $infot["pic"], $url);
		}

		$idsWhere .= ")";

		if ($this->item) {
			$this->db->where($idsWhere)->setInc("click");
			return array($return, "news");
		}
		else {
			$open = M("Token_open")->where(array("token" => $this->token))->find();
			$chaFfunction = M("Function")->where(array("funname" => "liaotian"))->find();
			if ((strpos($open["queryname"], "liaotian") === false) || !$chaFfunction["status"]) {
				return $this->action->api("noreplyReturn");
			}
			else {
				$result = $this->action->api("chat", $this->keyword);
				return array($result, "text");
			}
		}
	}
}

echo "\r\n";

?>
