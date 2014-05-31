<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/index.php
 */

header('Content-Type: text/html; charset=utf-8');
set_time_limit(0);
error_reporting(E_ALL^E_NOTICE); // 显示错误提示
ini_set('display_errors', TRUE);
ini_set('memory_limit', '1024M');

if (!defined('SELF')) define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME)); // 该文件的名称
if (!defined('FCPATH')) define('FCPATH', str_replace(SELF, '', __FILE__)); // 网站根目录

if (PHP_SAPI === 'cli' || defined('STDIN')) {
	unset($_GET);
	$_GET['c'] = 'cron';
	$_GET['m'] = 'index';
	chdir(dirname(__FILE__));
}

define('EXT', '.php'); // PHP文件扩展名
define('BASEPATH', FCPATH.'dayrui/system/'); // CI框架目录
define('SYSDIR', 'system'); // “系统文件夹”的名称

// 判断s参数,“应用程序”文件夹目录
if (!defined('APP_DIR') && isset($_GET['s']) && preg_match('/^[a-z]+$/i', $_GET['s']) && is_dir(FCPATH.'app/'.$_GET['s'].'/')) {
	define('APPPATH', FCPATH.'app/'.$_GET['s'].'/');
	define('APP_DIR', $_GET['s']); // 应用目录名称
}

$uri = isset($_SERVER['HTTP_X_REWRITE_URL']) && trim($_SERVER['REQUEST_URI'], '/') == SELF ? trim($_SERVER['HTTP_X_REWRITE_URL'], '/') : ($_SERVER['REQUEST_URI'] ? trim($_SERVER['REQUEST_URI'], '/') : NULL);

if ($uri) {
	if (strpos($uri, '?') !== FALSE) {
		$uri = explode('?', $uri);
		$uri = $uri[0];
	}
	if (strpos($uri, SELF) === FALSE && !file_exists(FCPATH.$uri)) {
		if (strpos($uri, '/') !== FALSE) {
			$uri = explode('/', $uri);
			if (is_dir(FCPATH.$uri[0])) {
				define('APPPATH', FCPATH.$uri[0].'/');
				define('APP_DIR', $uri[0]); // 模块目录名称
				unset($uri[0]);
			}
			define('DR_URI', implode('/', $uri)); // 组合URI
		} else {
			define('DR_URI', $uri); // URI
		}
	}
}

if (!defined('APP_DIR')) define('APP_DIR', ''); // 模块/应用目录名称
if (!defined('IS_ADMIN')) define('IS_ADMIN', FALSE); // 后台管理标识
if (!defined('IS_MEMBER')) define('IS_MEMBER', FALSE); // 前端会员标识

if (!defined('APPPATH')) define('APPPATH', FCPATH.'dayrui/'); // “应用程序”文件夹目录
define('VIEWPATH', FCPATH.'dayrui/'); // 定义视图目录，我们把它当做主项目目录
define('ENVIRONMENT', FCPATH.'config'); // 环境配置文件目录
if (!IS_ADMIN && !IS_MEMBER && isset($_GET['d'])) unset($_GET['d']); // 禁止d参数

require BASEPATH.'core/CodeIgniter.php'; // CI框架核心文件