<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

function dr_mylang() {
	
}

/**
 * 自定义获取城市信息
 */
function get_city() {
	//获取用户的IP地址
	$ip=$_SERVER["REMOTE_ADDR"];
	//调用淘宝的接口获取省市信息
	$json=file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=218.65.22.20");
	$arr=json_decode($json);
	//获取城市信息
	echo $arr->data->city;
}
/**
 * 通过会员名称取会员id
 *
 * @param	string	$username
 * @return  intval
 */
function get_mymember_id($username) {

	if (!$username) return 0;
	
	$ci	= &get_instance();
	$data = $ci->db
			   ->select('uid')
			   ->where('username', $username)
			   ->limit(1)
			   ->get('member')
			   ->row_array();
			   
	return (int)$data['uid'];
}

/**
 * 打印函数
 */
function dump($data){
	echo '<pre>';
		print_r($data);
	echo '</pre>';
}