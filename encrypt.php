<?php

// Refer : http://php.net/manual/en/mcrypt.ciphers.php

$str = "1234567890123123123";
$key = "yourcode";

function encrpt($str, $key){
	return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $orderid, MCRYPT_MODE_CBC, md5(md5($key))));
}

function decrypt($ciphertext, $key){
	$ciphertext = base64_decode($ciphertext);
	return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), $ciphertext, MCRYPT_MODE_CBC, md5(md5($key))), "\0");
}

$加密之后 = encrpt($orderid, $key);

echo "加密之后:" . $加密之后;

$解密之后 = decrypt($加密之后, $key);

echo "解密之后:" . $解密之后;