<?php

require_once FUWU_PATH.'index.php';
class Gateway {
	public function verifygw() {
		$biz_content = HttpRequest::getRequest ( "biz_content" );
		$as = new AlipaySign ();
		$xml = simplexml_load_string ( $biz_content );
		// print_r($xml);
		$EventType = ( string ) $xml->EventType;
		// echo $EventType;
		if ($EventType == "verifygw") {
			require 'config.php';
			// global $config;
			// print_r ( $config );
			$response_xml = "<success>true</success><biz_content>" . $as->getPublicKeyStr($config ['merchant_public_key_file']) . "</biz_content>";
			// echo $response_xml;
			$return_xml = $as->sign_response ( $response_xml, $config ['charset'], $config ['merchant_private_key_file'] );
			file_put_contents ( "log.txt", $return_xml, FILE_APPEND );
			echo $return_xml;
			exit ();
		}
	}
	
}
?>