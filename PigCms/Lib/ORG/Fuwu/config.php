<?php
$config = array (
		'alipay_public_key_file' => dirname ( __FILE__ ) . "/alipay_rsa_public_key.pem",
		'merchant_private_key_file' => dirname ( __FILE__ ) . "/rsa_private_key.pem",
		'merchant_public_key_file' => dirname ( __FILE__ ) . "/rsa_public_key.pem",		
		'charset' => "GBK",
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
		'app_id' => "" 
);