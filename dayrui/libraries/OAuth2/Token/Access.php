<?php

/**
 * OAuth Token_Access类
 * Dayrui Website Management System
 *
 * @since		version 2.0.4
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class OAuth2_Token_Access extends OAuth2_Token {

	/**
	 * @var  string  access_token
	 */
	protected $access_token;

	/**
	 * @var  int  expires
	 */
	protected $expires;

	/**
	 * @var  string  refresh_token
	 */
	protected $refresh_token;

	/**
	 * @var  string  uid
	 */
	protected $uid;

	/**
	 * 设置“持有人”、有效期等
	 *
	 * @param   array  $options   token options
	 */
	public function __construct(array $options = null) {
		if (!isset($options[$options['access_token_key']])) throw new OAuth2_Exception('');
		$this->access_token = $options[$options['access_token_key']];
        // uid值
		isset($options[$options['uid_key']]) and $this->uid = $options[$options['uid_key']];
		isset($options['x_mailru_vid']) and $this->uid = $options['x_mailru_vid'];
		
		// 到期时间
		isset($options['expires_in']) and $this->expires = time() + ((int) $options['expires_in']);
		isset($options['expires']) and $this->expires = time() + ((int) $options['expires']);
		isset($options['refresh_token']) and $this->refresh_token = $options['refresh_token'];
	}

	/**
	 * 返回 token key
	 *
	 * @return  string
	 */
	public function __toString() {
		return (string) $this->access_token;
	}
}