<?php

/**
 * php version: 5.6.17
 * todo : curl_multi_getcontent return nothing
 */
$urls = array(
	//taobao
	'https://item.taobao.com/item.htm?spm=a1z10.1-c.w4024-11308530054.1.oOFbdH&id=520625892808&scene=taobao_shop',
	'https://item.taobao.com/item.htm?spm=a1z10.3777-c.w4986-13288965637.2.j19e7O&id=522054508511',
	//jd
	'http://item.jd.com/1256491.html',
	'http://item.jd.com/1385518.html',
);

$response = classic_curl($urls,5000);
echo '<pre>';print_r($response);exit;

function classic_curl($urls, $delay){
	$queue = curl_multi_init();
	$map = array();

	foreach($urls as $url){
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		// curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		// curl_setopt($ch, CURLOPT_HEADER, 1);
		// curl_setopt($ch, CURLOPT_NOSIGNAL, true);
		curl_setopt($ch, CURLOPT_NOBODY, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);	// for the curl_multi_getcontent to fetch the return stream
		$map[$url] = $ch;
	}

	$active = null;

	// execute the handles
	do{
		$mrc = curl_multi_exec($queue, $active);
	}while($mrc == CURLM_CALL_MULTI_PERFORM);

	while($active > 0 && $mrc == CURLM_OK){

		// On php 5.3.18+ be aware that curl_multi_select() may return -1 forever until you call curl_multi_exec(). 
		// So add this line below:
		// It means that you should exec curl_multi_exec firstly until it finished then you can step into the curl_multi_select function
		while(curl_multi_exec($queue, $active) == CURLM_CALL_MULTI_PERFORM);

		if(curl_multi_select($queue, 0.5) != -1){
			do{
				$mrc = curl_multi_exec($queue, $active);
			}while($mrc == CURLM_CALL_MULTI_PERFORM);
		}
	}

	// print the result
	$response = array();
	foreach($map as $url => $ch){
		$response[$url] = callback(curl_multi_getcontent($ch), curl_getinfo($ch));
		curl_multi_remove_handle($queue, $ch);
		curl_close($ch);
	}

	curl_multi_close($queue);
	return $response;
}

/**
 * do something
 */
function callback($data, $info) {
    usleep($delay);
    return compact('data','info'); 
}

