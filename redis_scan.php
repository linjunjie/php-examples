<?php

/**
* php中redis对象的scan方法和redis服务器上使用的scan方法不同
*
* redis:
*
*     redis服务器上使用的scan方法会返回此次指定的match和count，例如：
*     scan 0 match 'the:key:you:want:*' count 10
*     然后可能返回如下：
*     1)"7270"
*     2)(empty list or set)
*     然后你根据这次返回的cursor值7270继续向下寻找
*
* php:
*
*     而php中使用的$redis->scan()方法，会按照你指定的$match一直去寻找
*     如果下一页游标cursor不为0的话，也即没有到底的话：
*	  1.如果匹配到了，则返回匹配到的内容
*	  2.如果没有匹配到，会继续接着往下一页寻找,函数不会有返回值
*     直到游标返回0，结束查找
*
* 总结:
*   
*     最大的区别就是php中的scan如果在下一页没有匹配到内容，则不会返回
*     直到找到匹配到的内容，才会返回值。
*     而redis服务器的scan每次都会根据你设定好的count值返回这次寻找的结果.
*/

$redis = new Redis();
$redis->connect('127.0.0.1','6381',20);
$redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);

$match = 'the:key:you:wang:*';
$count = 10000;

// 1
$result = $redis-scan($it, $match, $count);
echo $it;var_dump($result);exit;

// 2
while ($keys = $redis->scan($it, $match, $count)) {
    print_r($keys);

    // get the keys,so can do what you want
    // mostly use the scan result to del keys
    // because the redis CMD "keys" is terrible.
    // on "keys" cmd could hang on the whole redis server...
    // so replace the "keys" cmd with the "scan"
    $redis -> del($keys);
}
