<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

define('IS_ADMIN', TRUE); // 项目标识
define('FCPATH', dirname(__FILE__).'/'); // 网站根目录
// 判断s参数,“应用程序”文件夹目录
if (isset($_GET['s']) && preg_match('/^[a-z]+$/i', $_GET['s'])) {
	if (is_dir(FCPATH.$_GET['s'])) { // 模块
		define('APPPATH', FCPATH.$_GET['s'].'/');
		define('APP_DIR', $_GET['s']); // 模块目录名称
	} elseif (is_dir(FCPATH.'app/'.$_GET['s'].'/')) { // 应用
		define('APPPATH', FCPATH.'app/'.$_GET['s'].'/');
		define('APP_DIR', $_GET['s']); // 应用目录名称
	}
}
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME)); // 该文件的名称
$_GET['d'] = 'admin'; // 将项目标识作为directory
require('index.php'); // 引入主文件