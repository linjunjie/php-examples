<?php

/**
 * 只接受一个请求的单进程socket服务器
 */
date_default_timezone_set('asia/shanghai');

$socket = stream_socket_server("tcp://0.0.0.0:12345", $errno, $errstr);

if (!$socket) {
	echo "$errstr ($errno)<br/>\n";
} else {
	while(1){
		while($client = @stream_socket_accept($socket)){
			print "accepted " . stream_socket_get_name($client, true) . "\n";
			if(pcntl_fork() == 0){		//创建子进程
				$request = fread($client, 1024);
				$response = 'hello world' . "\r\n";
				fwrite($client, $response);		//输出到客户端
				sleep(5);
				fwrite($client, "client http header : \r\n" . $request . "\r\n");
				fclose($client);				//关闭客户端
				exit(0);		//退出子进程
			}
		}
	}
}
fclose($socket);
