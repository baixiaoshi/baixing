<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.6
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
 
class Search extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 搜索跳转
     */
    public function index() {
	
        $param = $this->input->get(NULL, TRUE);
		$module = $param['module'];
		if (!$module) $this->msg(lang('m-101'));
		unset($param['module'], $param['c'], $param['m']);
		
		if ($module == 'space') {
			// 空间搜索
			$space = $this->get_cache('member', 'setting', 'space', 'domain');
			$url = ($space ? $space : SITE_URL.'space/').'index.php?'.http_build_query($param);
		} else {
			// 模块搜索
			$module = $this->get_cache('module-'.SITE_ID.'-'.$module);
			if (!$module) {
				if (!$this->db->where('dirname', $module)->get('module')->row_array()) {
					$this->msg(lang('m-321'));
				}
				$this->msg(lang('m-148'));
			}
			$url = $module['url'].'index.php?c=search&m=index&'.http_build_query($param);
		}
		
		redirect($url, 'refresh');
		
    }

}