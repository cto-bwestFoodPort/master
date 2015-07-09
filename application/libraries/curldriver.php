<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class CURLDriver {


    public function curlOpt($url, $ssl = FALSE)
    {
    	$options = Array(
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_CONNECTTIMEOUT => 120,
			CURLOPT_TIMEOUT => 120,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'],
			CURLOPT_URL => $url,
			CURLOPT_SSL_VERIFYPEER => $ssl
		);

		$ch = curl_init();
		curl_setopt_array($ch, $options);

		if($data = curl_exec($ch))
		{
			//Do nothing
		}else
		{
			echo curl_error($ch);
		}
		curl_close($ch);

		return $results = json_decode($data, TRUE);
    }
}