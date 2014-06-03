<?php

/**
 * Google
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/dayrui/libraries/OAuth2/Provider/Google.php
 */

class OAuth2_Provider_Google extends OAuth2_Provider {

	public $method			= 'POST';
	public $scope_seperator = ' ';

	public function url_authorize() {
		return 'https://accounts.google.com/o/oauth2/auth';
	}

	public function url_access_token() {
		return 'https://accounts.google.com/o/oauth2/token';
	}

	public function __construct(array $options = array()) {
		empty($options['scope']) and $options['scope'] = array(
			'https://www.googleapis.com/auth/userinfo.profile', 
			'https://www.googleapis.com/auth/userinfo.email'
		);
		$options['scope'] = (array) $options['scope'];
		parent::__construct($options);
	}

	public function access($code, $options = array()) {
		if ($code === null) throw new OAuth2_Exception('Expected Authorization Code from '.ucfirst($this->name).' is missing');
		return parent::access($code, $options);
	}

	public function get_user_info(OAuth2_Token_Access $token) {
		$url = 'https://www.googleapis.com/oauth2/v1/userinfo?alt=json&'.http_build_query(array(
			'access_token' => $token->access_token,
		));
		$user = json_decode(file_get_contents($url), true);
		// 返回统一的数据格式
		return array(
			'oid'			=> $user['id'],
            'oauth'			=> 'google',
			'avatar'		=> (isset($user['picture'])) ? $user['picture'] : null,
			'nickname'		=> url_title($user['name'], '_', true),
			'expire_at'		=> $token->expires,
			'access_token'	=> $token->access_token,
			'refresh_token'	=> $token->refresh_token
		);
	}
}