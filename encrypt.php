<?php

/**
 *	@Description: 	一个通用的加解密算法	
 * 	@Datetime: 		2016-04-12 11:13:52
 * 	@Author: 		linjunjie
 *	
 * 	需要php安装mcrypt扩展
 * 	MCRYPT_ciphername 可以参考这里 : http://php.net/manual/en/mcrypt.ciphers.php
 *
 */

$str = "1234567890123123123";
$str = "我是谁？who I am? I'm 四大古典算法";
$key = "yourcode";

function encrypt($str, $key){
	return encrypt_special_char_replace(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $str, MCRYPT_MODE_CBC, md5(md5($key)))));
}

function decrypt($ciphertext, $key){
	$ciphertext = decrypt_special_char_replace($ciphertext);
	$ciphertext = base64_decode($ciphertext);
	return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), $ciphertext, MCRYPT_MODE_CBC, md5(md5($key))), "\0");
}

//对于加密之后的特殊字符进行特殊处理
function encrypt_special_char_replace($str){

	//这里暂时将特殊字符全部替换成了Ascii码
	$chars = array('+','-','/','=');
	foreach ($chars as $v) {
		$str = str_replace($v, ord($v), $str);
	}

	return $str;
}

//对于解密之后的特殊字符预先进行处理，先替换成原先的字符，然后再进行解密
function decrypt_special_char_replace($str){

	//将Ascii码替换回去
	//当然，这里有个问题，会将正常加密为正好跟ascii码一样的字符替换了，这个还需要解决
	$chars = array('+','-','/','=');
	foreach ($chars as $v) {
		$str = str_replace(ord($v), $v, $str);
	}

	return $str;

}

$加密之后 = encrypt($str, $key);

echo "加密之后:" . $加密之后 . PHP_EOL;

$解密之后 = decrypt($加密之后, $key);

echo "解密之后:" . $解密之后 . PHP_EOL;