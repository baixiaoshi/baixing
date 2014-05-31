<?php

/**
 * Dayrui Website Management System
 * 
 * @since			version 2.0.0
 * @author			Dayrui <dayrui@gmail.com>
 * @license     	http://www.dayrui.com/license
 * @copyright		Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
 * 默认路由配置（不允许更改）
 */
 
$route['([0-9]+)'] = 'home/index/uid/$1';
$route['([0-9]+)/category-([0-9]+)-([0-9]+)\.html'] = 'home/index/uid/$1/action/category/id/$2/page/$3';
$route['([0-9]+)/category-([0-9]+)\.html'] = 'home/index/uid/$1/action/category/id/$2';
$route['([0-9]+)/show-([0-9]+)-([0-9]+)-([0-9]+)\.html'] = 'home/index/uid/$1/action/show/id/$2/mid/$3/page/$4';
$route['([0-9]+)/show-([0-9]+)-([0-9]+)\.html'] = 'home/index/uid/$1/action/show/id/$2/mid/$3';
$route['404_override'] = '';
$route['default_controller'] = 'home';

if (is_file(APPPATH.'config/rewrite.php')) require APPPATH.'config/rewrite.php';

/**
 * 自定义路由
 */
 
//$route['自定义路由正则规则']	= '指向的路由URI（必须是v2的URI规则：控制器/方法/参数1/参数1的值/参数2/参数2的值...）';

