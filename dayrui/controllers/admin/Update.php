<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.2
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
 
class Update extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		//$this->db->db_debug = FALSE;
    }

    /**
     * 2.0.11 更新程序
     */
    public function index() {
	
		$page = (int)$this->input->get('page');
		if (!$page) $this->admin_msg('正在升级数据...', dr_url('update/index', array('page' => $page + 1)), 2);
		
		switch($page) {
			case 1:
				$module_page = $page_rule = array();
				$module = $this->db->get('module')->result_array();
				foreach ($this->site as $id => $t) {
					// 单页
					$page1 = $this->db->get($id.'_page')->result_array();
					if ($page1) {
						foreach ($page1 as $t) {
							if ($t['urlrule'] && $t['urlpage']) {
								$data = array(
									'page' => $t['urlrule'],
									'page_page' => $t['urlpage'],
								);
								$page_rule[md5(dr_array2string($data))] = $data;
							}
						}
					}
					// 模块
					foreach ($module as $m) {
						$category = $this->db->get($id.'_'.$m['dirname'].'_category')->result_array();
						if ($category) {
							foreach ($category as $t) {
								$c = dr_string2array($t['setting']);
								if ($c['urlmode']) {
									$module_page[md5(dr_array2string($c['url']))] = $c['url'];
								}
							}
						}
					}
				}
				if ($page_rule) {
					$i = 1;
					foreach ($page_rule as $t) {
						$this->db->insert('urlrule', array(
							'type' => 0,
							'name' => '单页规则'.$i,
							'value' => dr_array2string($t)
						));
						$i++;
					}
				}
				if ($module_page) {
					$i = 1;
					foreach ($module_page as $t) {
						$this->db->insert('urlrule', array(
							'type' => 1,
							'name' => '模块规则'.$i,
							'value' => dr_array2string($t)
						));
						$i++;
					}
				}
				$this->admin_msg('正在转存URL规则...', dr_url('update/index', array('page' => $page + 1)), 2);
				break;
			default:
				@rename(__FILE__, dirname(__FILE__).'/_update.php');
				$this->admin_msg('升级完成，请更新全站缓存在刷新页面', '', 1);
				break;
		}
    }
}