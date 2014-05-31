<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
 
class A_Model extends CI_Model {

    /**
     * 应用模型继承类
     */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 删除模块时调用
	 *
	 * @param	string	$module	模块目录
	 * @param	intval	$siteid	站点id，默认为全部站点
	 * @return  string
	 */
	public function delete_for_module($module, $siteid) {
	
	}
	
	/**
	 * 删除模块内容时调用
	 *
	 * @param	string	$module	模块目录
	 * @param	intval	$siteid	站点id，默认为全部站点
	 * @return  string
	 */
	public function delete_for_cid($cid, $module) {
	
	}
	
	/**
	 * 删除会员时调用
	 *
	 * @param	intval	$uid	会员uid
	 * @return  string
	 */
	public function delete_for_uid($uid) {
	
	}
	
	/**
	 * 将应用菜单安装至后台菜单中
	 *
	 * @param	string	$dir	应用目录名称
	 * @param	intval	$id		应用id
	 * @return  void
	 */
	public function install_admin_menu($dir, $id) {
		
	}
	
	/**
	 * 将应用菜单安装至会员菜单中
	 *
	 * @param	string	$dir	应用目录名称
	 * @param	intval	$id		应用id
	 * @return  void
	 */
	public function install_member_menu($dir, $id = 0) {
		
	}
	
}