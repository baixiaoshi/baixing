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
 
class Api extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }

    /**
	 * 更新浏览数
	 */
	public function hits() {
	
	    $id = (int)$this->input->get('id');
	    $dir = $this->input->get('module');
		$name = 'hits'.$module.SITE_ID.$id;
		$hits = (int)$this->get_cache_data($name);
		
		if (!$hits) {
			$data = $this->site[SITE_ID]
						 ->where('id', $id)
						 ->select('hits')
						 ->limit(1)
						 ->get($this->db->dbprefix(SITE_ID.'_'.$dir))
						 ->row_array();
			$hits = (int)$data['hits'];
		}
		
		$hits++;
		$this->set_cache_data($name, $hits, (int)$this->get_cache('module-'.SITE_ID.'-'.$dir, 'setting', 'show_cache'));
		
		$this->site[SITE_ID]
			 ->where('id', $id)
			 ->update($this->db->dbprefix(SITE_ID.'_'.$dir), array('hits' => $hits));
		
		exit("document.write('$hits');");
	}
	
	/**
	 * 发送桌面快捷方式
	 */
	public function desktop() {
		
		$site = (int)$this->input->get('site');
		$module = $this->input->get('module');
		
		if ($site && !$module) {
			$url = $this->SITE[$site]['SITE_URL'];
			$name = $this->SITE[$site]['SITE_NAME'].'.url';
		} elseif ($site && $module) {
			$mod = $this->get_cache('module-'.$site.'-'.$module);
			$url = $mod['url'];
			$name = $mod['name'].'.url';
		}  else {
			$url = $this->SITE[SITE_ID]['SITE_URL'];
			$name = $this->SITE[SITE_ID]['SITE_NAME'].'.url';
		}
		
		$data = "
		[InternetShortcut]
		URL={$url}
		IconFile={$url}favicon.ico
		Prop3=19,2
		IconIndex=1
		";
		$mime = 'application/octet-stream';
		
		header('Content-Type: "' . $mime . '"');
		header('Content-Disposition: attachment; filename="' . $name . '"');
		header("Content-Transfer-Encoding: binary");
		header('Expires: 0');
		header('Pragma: no-cache');
		header("Content-Length: " . strlen($data));
		echo $data;
	}
	
	/**
	 * 伪静态测试
	 */
	public function test() {
		header('Content-Type: text/html; charset=utf-8');
		echo '服务器支持伪静态';
	}
	
	/**
	 * 自定义数据调用
	 */
	public function data() {
		$param = $this->input->get('param');
		$format = $this->input->get('format');
		$callback = $this->input->get('callback');
		$data = $this->template->list_tag($param);
		if ($format == 'xml') {
			header('Content-Type: text/xml');
			echo dr_array2xml($data, FALSE);
		} else {
			$data = json_encode($data);
			if ($callback) {
				echo $callback . '(' . $data . ')';
			} else {
				echo $data;
			}
		}
	}
}
