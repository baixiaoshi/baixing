<?php

/**
 * Sohu
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/dayrui/libraries/OAuth2/Provider/Sohu.php
 */

class OAuth2_Provider_Sohu extends OAuth2_Provider {

	public $name		= 'sohu';
	public $human		= '搜狐微博';
	public $method = 'POST';
	public $uid_key		= 'id';
    public $state_key	= 'wrap_client_state';
        
    public function __construct(array $options = array()) {
		empty($options['scope']) and $options['scope'] = 'basic';
		$options['scope'] = (array) $options['scope'];
		parent::__construct($options);
	}
 
	public function url_authorize() {
		return 'https://api.t.sohu.com/oauth2/authorize';
	}

	public function url_access_token() {
		return 'https://api.t.sohu.com/oauth2/access_token';
	}

	public function get_user_info(OAuth2_Token_Access $token) {
		$url	= 'http://api.t.sohu.com/users/show/id.json?'.http_build_query(array(
			'access_token' => $token->access_token
		));
		$return	= file_get_contents($url);
		$user	= json_decode($return);
		if (array_key_exists('error', $user)) throw new OAuth2_Exception($return);
		// 返回统一的数据格式
		return array(
			'oid'			=> $user->id,
            'oauth'			=> $this->name,
			'avatar'		=> $user->profile_image_url,
			'nickname'		=> $user->name,
			'expire_at'		=> $token->expires,
			'access_token'	=> $token->access_token,
			'refresh_token'	=> $token->refresh_token
		);
	}
}
