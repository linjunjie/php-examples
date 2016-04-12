<?php

/**
 * @Description: 	一个通用的加解密算法	
 * @Datetime: 		2016-04-12 11:13:52
 * @Author: 		linjunjie
 *
 * MCRYPT_ciphername 可以参考这里 : http://php.net/manual/en/mcrypt.ciphers.php
 *
 */

$str = "1234567890123123123";
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
	return str_replace(array('+','-','='), array('43','45','61'), $str);
}

//对于解密之后的特殊字符预先进行处理，先替换成原先的字符，然后再进行解密
function decrypt_special_char_replace($str){
	return str_replace(array('43','45','61'), array('+','-','='), $str);
}

$加密之后 = encrypt($str, $key);

echo "加密之后:" . $加密之后 . PHP_EOL;

$解密之后 = decrypt($加密之后, $key);

echo "解密之后:" . $解密之后 . PHP_EOL;