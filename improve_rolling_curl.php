<?php

/**
 * php version: 5.6.17
 */

date_default_timezone_set('asia/shanghai');
error_reporting(0);

$urls = array(
	//taobao
	'https://item.taobao.com/item.htm?spm=a1z10.1-c.w4024-11308530054.1.oOFbdH&id=520625892808&scene=taobao_shop',
	'https://item.taobao.com/item.htm?spm=a1z10.3777-c.w4986-13288965637.2.j19e7O&id=522054508511',
	//jd
	'http://item.jd.com/1256491.html',
	'http://item.jd.com/1385518.html',
);

$options = array(
	CURL_RETURNTRANSFER => 1,
);
$response = rolling_curl($urls, $options);
echo '<pre>';print_r($response);exit;

/**
 * 不再像classic_curl那样等待所有请求完成之后再进行后面的处理
 * 而是边请求边返回边处理
 */
function rolling_curl($urls, $options){
	$queue = curl_multi_init();
	$map = array();

	foreach($urls as $url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt_array($ch, $options); 
		curl_multi_add_handle($queue, $ch);
		$map[$ch] = $url;
	}

	$response = array();

	do{
		while(($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM);

		if($code != CURLM_OK) { break; }

		// a request was just completed -- find out which one
		while($done = curl_multi_info_read($queue)){
			$info = curl_getinfo($done['handle']);
			$error = curl_error($done['handle']);
			$data= curl_multi_getcontent($done['handle']);
			$responses[$map[$done['handle']]] = compact('info','error','data');
			// remove the curl handle that just completed
			curl_multi_remove_handle($queue, $done['handle']);
			curl_close($done['handle']);
		}

		// block for data I / O
		if($active > 0){
			curl_multi_select($queue, 0.5);
		}
	} while ($active);

	curl_multi_close($queue);
	return $responses;
}
