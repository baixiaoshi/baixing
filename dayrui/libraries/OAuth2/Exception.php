<?php

/**
 * OAuthÒì³£Àà
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class OAuth2_Exception extends Exception {

	public function __construct($message) {
		parent::__construct($message, 0);
	}
	
	public function __toString() {
		return $this->message;
	}
}