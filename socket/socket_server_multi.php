<?php

/**
 * 可同时接受多个请求的多进程socket服务器
 */
date_default_timezone_set('asia/shanghai');

$socket = stream_socket_server("tcp://0.0.0.0:12345", $errno, $errstr);

if (!$socket) {
	echo "$errstr ($errno)<br/>\n";
} else {
	for ($i=0; $i < 32; $i++) {		//一次打开32个进程来监听
		if(pcntl_fork() == 0){
			while(1){
				$client = stream_socket_accept($socket);
				if($client == false) continue;
				print "accepted " . stream_socket_get_name($client, true) . "\n";
				$response = 'hello world' . "\r\n";
				fwrite($client, $response);		//输出到客户端
				fclose($client);	//关闭客户端
			}
			exit(0);
		}
	}
}
fclose($socket);