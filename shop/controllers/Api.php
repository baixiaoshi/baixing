<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
	
/**
 * Api调用类
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
 
class Api extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 联动菜单调用
	 */
	public function linkage() {
	    $pid = (int)$this->input->get('parent_id');
	    $code = $this->input->get('code');
	    $json = array();
		$linkage = $this->get_cache('linkage-'.SITE_ID.'-'.$code);
		foreach ($linkage as $k => $v) {
			if ($v['pid'] == $pid) {
				$json[] = array('region_id' => $v['id'], 'region_name' => $v['name']);
			}
		}
		echo json_encode($json);	
	}
	
	/**
     * 购买文档
     */
	public function buy() {
		$this->_show_buy();
	}
	
	/**
     * 收藏文档
     */
	public function favorite() {
		$this->api_favorite();
	}
	
	/**
     * 站点间的同步登录
     */
	public function synlogin() {
		$this->api_synlogin();
	}
	
	/**
     * 站点间的同步退出
     */
	public function synlogout() {
		$this->api_synlogout();
	}
	
	/**
     * 自定义信息JS调用
     */
	public function template() {
		$this->api_template();
	}
	
}