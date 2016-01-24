<?php

$which = 2;

if($which == 3){

function & test(){
	static $b = 0;
	echo ++$b;
	return $b;
}

$a = test();
$a = & test();
$a = 30;
test();
exit;
}


if($which == 2){
/**
 * 返回引用示例2
 */
function & func($a,$b){
	static $result = 0;
	$result += $a + $b;
	echo $result . PHP_EOL;
	return $result;
}
$a = $b = 10;
$c = func($a,$b);
$c = func($a,$b);
$d = & func($a,$b);
$d = 10;
$d = func($a,$b);
exit;
}

if($which == 1){
/**
 * 返回引用示例1
 */
class foo{
	public $value = 20;
	function & getValue(){
		return $this->value;
	}
}

$obj = new foo();
$value = & $obj->getValue();

// test 2:
$obj -> value = 30;
echo $value;exit;

// test 1:
$value = 30;
echo $obj -> value;exit;

}



$a = '100';
$b = &$a;
$b = 200;
echo $a;


