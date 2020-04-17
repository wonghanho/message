<?php 
	var_dump($_POST);



	$redis = new Redis;
	$redis->connect('localhost',6379);
	$code_phone = $redis->get('code_phone');

	var_dump($code_phone);
?>