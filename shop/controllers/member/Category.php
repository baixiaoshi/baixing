<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 * @filesource	svn://www.dayrui.net/v2/mall/controllers/member/category.php
 */

class Category extends M_Controller {

	private $category;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->category = $this->get_cache('MODULE-'.SITE_ID.'-'.APP_DIR, 'category');
    }
	
	/**
     * 顶级可用栏目
     */
    public function index() {
	
		$data = array();
        foreach ($this->category as $t) {
			if (!$t['child'] && $t['permission'][$this->member['mark']]['add']) {
				$pids = explode(',', $t['pids']);
				$pid = (int)$pids[1];
				if (isset($this->category[$pid])) {
					$this->category[$pid]['mark'] = 1;
					$data[$pid] = $this->category[$pid];
				}
			}
		}
		
		$this->template->assign(array(
			'id' => 2,
			'list' => $data
		));
		$this->template->display('category_select.html');
    }
	
	/**
     * 可用子栏目
     */
    public function child() {
	
		$id = (int)$this->input->post('id');
		$catid = (int)$this->input->post('catid');
		$data = array();
        foreach ($this->category as $t) {
			if ($catid == $t['pid']) {
				$t['mark'] = $t['child'] ? 1 : $t['permission'][$this->member['mark']]['add'];
				$data[] = $t;
			}
		}
		
		$this->template->assign(array(
			'id' => $id+1,
			'list' => $data
		));
		$this->template->display('category_select.html');
    }
	
}