<?php 

/**
 * Baidu
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/dayrui/libraries/OAuth2/Provider/Baidu.php
 */

class OAuth2_Provider_Baidu extends OAuth2_Provider {

	public $name	= 'baidu';
	public $human	= '百度';
	public $method	= 'POST';
	public $uid_key	= 'uid';

	/**
     * 授权认证登录地址
     */
	public function url_authorize() {
		return 'https://openapi.baidu.com/oauth/2.0/authorize';
	}
	
	/**
     * 授权认证访问地址
     */
	public function url_access_token() {
		return 'https://openapi.baidu.com/oauth/2.0/token';
	}
	
	/**
     * 获取用户信息
     */
	public function get_user_info(OAuth2_Token_Access $token) {
		$url	= 'https://openapi.baidu.com/rest/2.0/passport/users/getLoggedInUser?'.http_build_query(array(
			'access_token' => $token->access_token,
			'uid' => $token->uid,
		));
		$return	= file_get_contents($url);
		$user	= json_decode($return);
      	if (array_key_exists('error', $user)) throw new OAuth2_Exception($return);
		// 返回统一的数据格式
		return array(
			'oid'			=> $user->uid,
            'oauth'			=> $this->name,
			'avatar'		=> 'http://tb.himg.baidu.com/sys/portraitn/item/'.$user->portrait,
			'nickname'		=> $user->uname,
			'expire_at'		=> $token->expires,
			'access_token'	=> $token->access_token,
			'refresh_token' => $token->refresh_token
		);
	}
}