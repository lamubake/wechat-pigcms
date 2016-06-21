<?php

class Message
{
	public function Message($biz_content, $FuwuToken)
	{
		$UserInfo = $this->getNode($biz_content, 'UserInfo');
		$FromUserId = $this->getNode($biz_content, 'FromUserId');
		$AppId = $this->getNode($biz_content, 'AppId');
		$CreateTime = $this->getNode($biz_content, 'CreateTime');
		$Content = $this->getNode($biz_content, 'Content');
		$MsgType = $this->getNode($biz_content, 'MsgType');
		$EventType = $this->getNode($biz_content, 'EventType');
		$AgreementId = $this->getNode($biz_content, 'AgreementId');
		$ActionParam = $this->getNode($biz_content, 'ActionParam');
		$AccountNo = $this->getNode($biz_content, 'AccountNo');
		$push = new PushMsg();

		if ($EventType) {
			switch ($EventType) {
			case 'click':
				$et = '';
				$Content = $ActionParam;
				$etkey = '';
				break;

			case 'enter':
				$ap = json_decode($ActionParam, true);

				if ($ap['scene']['sceneId'] != '') {
					$et = 'SCAN';
					$ap = json_decode($ActionParam, true);
					$etkey = $ap['scene']['sceneId'];
				}
				else {
					exit('error');
				}

				break;

			default:
				$et = 'subscribe';
				$etkey = '';
				break;
			}
		}

		$ApiData = array('ali' => 1, 'token' => $FuwuToken, 'eventType' => $et, 'fromUserName' => $FromUserId, 'keyword' => $Content, 'EventKey' => $etkey);
		$weixin = new WeixinAction();
		$rt2 = $weixin->index($ApiData);

		if ($rt2[1] == 'text') {
			$text_msg = $push->mkTextMsg($rt2[0]);
			$biz_content = $push->mkTextBizContent($FromUserId, $text_msg);
			$return_msg = $push->sendRequest($biz_content);
		}
		else if ($rt2[1] == 'news') {
			$image_text_msg = array();

			foreach ($rt2[0] as $news) {
				$searchArr = array('微信');
				$replaceArr = array('支付宝服务窗');
				$news[0] = str_replace($searchArr, $replaceArr, $news[0]);
				$news[1] = str_replace($searchArr, $replaceArr, $news[1]);
				$linkUrl = $news[3];
				$urlInfos = parse_url($linkUrl);
				$p1 = explode('&', $urlInfos['query']);
				$params = array();

				foreach ($p1 as $p1item) {
					$p2 = explode('=', $p1item);

					if ($p2[0] != 'wecha_id') {
						$params[$p2[0]] = $p2[1];
					}
				}

				$parmsStr = '';
				$andStr = '';
				$params['wecha_id'] = 'z_' . md5($FromUserId);
				$params['ali'] = 1;

				foreach ($params as $pk => $pv) {
					$parmsStr .= $andStr . $pk . '=' . $pv;
					$andStr = '&';
				}

				$linkUrlArr = explode('?', $linkUrl);
				$linkUrl = $linkUrlArr[0] . '?' . $parmsStr;
				$a = $push->mkImageTextMsg($news[0], $news[1], $linkUrl, $news[2], 'loginAuth');
				array_push($image_text_msg, $a);
			}

			$biz_content = $push->mkImageTextBizContent($FromUserId, $image_text_msg);
			$return_msg = $push->sendMsgRequest($biz_content);
		}
		else {
			$text_msg = $push->mkTextMsg($rt2[0] . 'zz');
			$biz_content = $push->mkTextBizContent($FromUserId, $text_msg);
			$return_msg = $push->sendRequest($biz_content);
		}
	}

	public function getNode($xml, $node)
	{
		$xml = '<?xml version="1.0" encoding="GBK"?>' . htmlspecialchars_decode($xml);
		$dom = new DOMDocument('1.0', 'GBK');
		$return = $dom->loadXML($xml);
		$event_type = $dom->getElementsByTagName($node);
		return $event_type->item(0)->nodeValue;
	}
}

require_once FUWU_PATH . 'PushMsg.php';
require_once './PigCms/Lib/Action/Home/WeixinAction.class.php';

?>
