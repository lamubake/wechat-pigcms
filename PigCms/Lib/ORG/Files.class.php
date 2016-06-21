<?php

class Files
{
	public function index($url, $size, $type, $users_id, $token, $wecha_id = 0, $sync_url = '', $media_id = '')
	{
		$url_array = explode('.', $url);
		$add['type'] = $type ? $type : $url_array[count($url_array) - 1];
		$add['size'] = $size;
		$add['url'] = $url;
		$add['users_id'] = $users_id ? $users_id : 0;
		$add['token'] = $token ? $token : 0;
		$add['wecha_id'] = $wecha_id ? $wecha_id : 0;
		$add['upload_ip'] = get_client_ip();
		$add['time'] = time();
		$add['sync_url'] = $sync_url;
		$add['media_id'] = $media_id;
		$Files_id = M('Files')->add($add);
	}
}


?>
