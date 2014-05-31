<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/dayrui/libraries/Duri.php
 */

/**
 * URI([模块目录|应用目录/[管理目录|会员目录/]]/控制器/方法/参数1/值1/参数2/值2 ... )
 */
 
class Duri {

	private $app;		// 应用名称
	private $path;		// 模块或者会员目录
	private $class;		// 控制器
	private $param;		// 参数
	private $method;	// 方法
	private $segments;	// uri数组格式段
	private $directory;	// 目录（admin或者member）
	
	/**
     * 构造函数
     */
    public function __construct() {
		
    }

	/**
	 * 初始化uri 
	 *
	 * @param   string $uri
	 * @return  object
	 */
	private function init($uri) {
	
		$this->app = $this->path = $this->class = $this->param = $this->method = $this->segments = $this->directory = '';
		$this->segments	= explode('/', trim($uri, '/'));
		
		foreach ($this->segments as $i => $t) {
			$this->segments[$i] = str_replace(
					array('$',     '(',     ')',     '%28',   '%29'), // Bad
					array('&#36;', '&#40;', '&#41;', '&#40;', '&#41;'), // Good
					$t);
		}
		// 验证uri
		if ($this->segments) $this->_validate();
		
		return $this;
	}
	
	/**
	 * 当前地址的uri
	 *
	 * @param   intval $mark 为1时不输出page/total/search参数
	 * @return  string
	 */
	public function uri($mark = 0, $router = FALSE) {
	
		$ci = &get_instance();
		$uri = '/';
		
		APP_DIR && $uri.= basename(APP_DIR).'/';
		$ci->router->directory && $uri.= $ci->router->directory;
		$ci->router->class && $uri.= $ci->router->class.'/';
		$ci->router->method && $uri.= $ci->router->method.'/';
		
		if ($router == TRUE) return trim($uri, '/');
		
		$uri_string = isset($_SERVER['QUERY_STRING']) && $_SERVER['QUERY_STRING'] ? $_SERVER['QUERY_STRING'] : (strlen($_SERVER['REQUEST_URI']) == 1 || $_SERVER['REQUEST_URI'] == '/'.SELF ? '' : $_SERVER['REQUEST_URI']);
		
		parse_str($uri_string, $uri_array);
		unset($uri_array['s'], $uri_array['d'], $uri_array['c'], $uri_array['m']);
		
		if ($uri_array) {
			foreach ($uri_array as $k => $v) {
				if ($mark && in_array($k, array('page', 'total', 'search'))) continue;
				$uri .= $k.'/'.$v.'/';
			}
		}
		
		return trim($uri, '/');
	}
	
	/**
	 * uri转换ci路由 
	 *
	 * @param   string $uri
	 * @return  array
	 */
	public function uri2ci($uri) {
	
		$uri = trim($uri, '/');
		if (!$uri) return array();
		
		$this->init($uri);
		$data = array();
		
		$this->app && $data['app'] = $this->app;
		$this->path && $data['path'] = $this->path;
		$this->class && $data['class'] = $this->class;
		$this->param && $data['param'] = $this->param;
		$this->method && $data['method'] = $this->method;
		$this->directory && $data['directory'] = $this->directory;
		$this->param && $this->segments && $data['param_str'] = implode('/', $this->segments);
		
		return $data;
	}
	
	
	/**
	 * uri转换URL地址
	 *
	 * @param   string $uri
	 * @return  string
	 */
	public function uri2url($uri) {
	
		$uri = trim($uri, '/');
		if (!$uri) return 'null';
		
		if (strpos($uri, 'http://') === 0) return $uri;
		
		$this->init($uri);
		$_uri = ($this->app ? $this->app : $this->path).'/'.$this->class.'/'.$this->method;
		$_uri = trim(trim($_uri, '/'), '/');
		
		return dr_url($_uri, $this->param);
	}
	
	/**
	 * 验证uri 
	 *
	 * @param   array	$arr
	 * @return  arr
	 */
	private function _validate() {
		if ($this->segments[0] == 'admin' || ($this->segments[0] == 'member' && ($this->app || $this->path))) {
			// 第一个参数是控制器目录(admin)
			$this->directory = array_shift($this->segments);
			$this->class = array_shift($this->segments);
			$this->method = array_shift($this->segments);
			$this->param = $this->_get_param($this->segments);
			return TRUE;
		} elseif (!$this->app && is_dir(FCPATH.'app/'.$this->segments[0])) {
			// 第一个参数是应用目录
			$this->app = array_shift($this->segments);
			// 递归验证
			$this->_validate();
			return TRUE;
		} elseif (!$this->path && is_dir(FCPATH.$this->segments[0])) {
			// 第一个参数是模块目录
			$this->path	= array_shift($this->segments);
			// 递归验证
			$this->_validate();
			return TRUE;
		} elseif (is_file(APPPATH.'controllers/'.$this->segments[0]).'.php') {
			// 第一个参数是控制器
			$this->class = array_shift($this->segments);
			$this->method = array_shift($this->segments);
			$this->param = $this->_get_param($this->segments);
			return TRUE;
		}
		// 第一个参数什么都不是
		return FALSE;
	}
	
	/**
	 * 将剩余uri数组转换成参数数组
	 *
	 * @param   array	$arr
	 * @return  array
	 */
	private function _get_param($arr) {
	
		if (!$arr) return NULL;
		
		$i = 0;
		$param = array();
		
		foreach ($arr as $k => $t) {
			if ($i%2 == 0) $param[$t] = isset($arr[$k+1]) ? $arr[$k+1] : '';
			$i ++;
		}
		
		return $param;
	}
	
}