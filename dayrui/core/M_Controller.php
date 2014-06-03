<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

require FCPATH.'dayrui/core/D_Common.php';

class M_Controller extends D_Common {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }
	
	/**
     * 网站的纯域名
     */
	protected function _get_site_domain() {
	
		if (strpos(SITE_URL, '.') !== FALSE) {
			$domain = explode('.', rtrim(str_replace('http://', '', SITE_URL), '/'));
			$domian_1 = array_pop($domain);
			$domian_2 = array_pop($domain);
			$sitedomain = $domian_2 .'.'. $domian_1;
		} else {
			$sitedomain = '';
		}
		
		return $sitedomain;
	}
}