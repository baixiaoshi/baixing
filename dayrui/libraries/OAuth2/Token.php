<?php

/**
 * OAuth Token类
 * Dayrui Website Management System
 *
 * @since		version 2.0.4
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

abstract class OAuth2_Token {

	/**
	 * 创建一个“持有人”对象
	 *
	 * $token = OAuth2_Token::factory($name);
	 *
	 * @param   string  $name     token type
	 * @param   array   $options  token options
	 * @return  OAuth2_Token
	 */
	public static function factory($name = 'access', array $options = null) {
		$name	= ucfirst(strtolower($name));
		$class	= 'OAuth2_Token_'.$name;
		include_once 'Token/'.$name.'.php';
		return new $class($options);
	}

	public function __get($key) {
		return $this->$key;
	}
	
	public function __isset($key) {
		return isset($this->$key);
	}
}