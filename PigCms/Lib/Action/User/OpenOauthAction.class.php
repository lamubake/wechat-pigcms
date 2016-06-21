<?php

class OpenOauthAction
{
	public function index()
	{
		$encryptMsg = file_get_contents('php://input');

		if ($_GET['type'] == 'test') {
		}

		$xml_tree = new DOMDocument();
		$xml_tree->loadXML($encryptMsg);
		$xml_array = $xml_tree->getElementsByTagName('Encrypt');
		$encrypt = $xml_array->item(0)->nodeValue;
		$agentid = 0;

		if (C('agent_version')) {
			$thisAgent = M('agent')->where(array('siteurl' => 'http://' . $_SERVER['HTTP_HOST']))->find();
			$agentid = (isset($thisAgent['id']) ? intval($thisAgent['id']) : 0);
		}

		$account = M('Weixin_account')->where(array('type' => 1, 'agentid' => $agentid))->find();
		import('@.ORG.aes.WXBizMsgCrypt');
		$Prpcrypt = new Prpcrypt($account['encodingAesKey']);
		$postData = $Prpcrypt->decrypt($encrypt, $account['appId']);

		if ($postData[0] != 0) {
			return $postData[0];
		}
		else {
			$msg = $postData[1];
			$xml = new DOMDocument();
			$xml->loadXML($msg);
			$array_a = $xml->getElementsByTagName('InfoType');
			$infoType = $array_a->item(0)->nodeValue;

			if ($infoType == 'unauthorized') {
				$array_b = $xml->getElementsByTagName('AuthorizerAppid');
				$AuthorizerAppid = $array_b->item(0)->nodeValue;
				$where = array('type' => 1, 'appid' => $AuthorizerAppid);
				$save = array('authorizer_access_token' => '', 'authorizer_refresh_token' => '', 'authorizer_expires' => 0);
				M('Wxuser')->where($where)->save($save);
			}
			else if ($infoType == 'component_verify_ticket') {
				$array_e = $xml->getElementsByTagName('ComponentVerifyTicket');
				$component_verify_ticket = $array_e->item(0)->nodeValue;

				if (M('Weixin_account')->where(array('type' => 1, 'agentid' => $agentid))->save(array('component_verify_ticket' => $component_verify_ticket, 'date_time' => time()))) {
					echo 'success';
				}
			}
		}
	}
}


?>
