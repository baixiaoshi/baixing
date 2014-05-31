<?php
	header("Content-type: text/html; charset=utf-8");
	//获取用户的IP地址
	$ip=$_SERVER["REMOTE_ADDR"];
	//调用淘宝的接口获取省市信息
	$json=file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=218.65.22.20");
	$arr=json_decode($json);
	//获取城市信息
	echo $arr->data->city;
?>