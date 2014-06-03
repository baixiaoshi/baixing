<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.x
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

/**
 * 第三方短信发送接口
 *
 * @param	string	$phone		发送对象，多个手机号码以,分开
 * @param	string	$content	发送内容，限制在40个字以内
 * @return	array	返回格式为：array('status' => 1/0, 'msg' => '成功/失败')
 */
 
function my_sms_send($phone, $content) {
	
	
	return array('status' => 0, 'msg' => '接口未定义，请联系官方订制第三短信接口');
	
}