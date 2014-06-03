<?php

/**
 * OAuth Token_AuthorizeÀà
 * Dayrui Website Management System
 *
 * @since		version 2.0.4
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class OAuth2_Token_Authorize extends OAuth2_Token {

	/**
	 * @var  string  code
	 */
	protected $code;

	/**
	 * @var  string  redirect_uri
	 */
	protected $redirect_uri;

	/**
	 *
	 * @param   array   token options
	 * @return  void
	 */
	public function __construct(array $options) {
		if (!isset($options['code'])) {
			throw new Exception('Required option not passed: code');
		} elseif (!isset($options['redirect_uri']))  {
            throw new Exception('Required option not passed: redirect_uri');
        }
		$this->code = $options['code'];
		$this->redirect_uri = $options['redirect_uri'];
	}
	
	public function __toString() {
		return (string) $this->code;
	}
}