<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Block extends M_Controller {

	private $link;
	private $field;
	private $tablename;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign('menu', $this->get_menu(array(
		    lang('203') => 'admin/block/index',
		    lang('add') => 'admin/block/add_js',
		    lang('001') => 'admin/block/cache'
		)));
		$this->link = $this->site[SITE_ID];
		$this->tablename = $this->db->dbprefix(SITE_ID.'_block');
		$this->field = array(
			'name' => array(
				'ismain' => 1,
				'fieldname' => 'name',
				'fieldtype' => 'Text',
				'setting' => array(
					'option' => array(
						'width' => 200,
					),
					'validate' => array(
						'required' => 1,
					)
				)
			),
			'content' => array(
				'ismain' => 1,
				'fieldname' => 'content',
				'fieldtype'	=> 'Textarea',
				'setting' => array(
					'option' => array(
						'width' => '370',
						'height' => 250,
					)
				)
			),
		);
    }
	
	/**
     * 管理
     */
    public function index() {
	
		if (IS_POST) {
		
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			if (!$this->is_auth('admin/block/del')) exit(dr_json(0, lang('160')));
			
            $this->link->where_in('id', $ids)->delete($this->tablename);
			exit(dr_json(1, lang('014')));
		}
		
		$this->template->assign('list', $this->link->get($this->tablename)->result_array());
		$this->template->display('block_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		if (IS_POST) {
		
			$data = $this->validate_filter($this->field);
			if (isset($data['error'])) exit(dr_json(0, $data['msg'], $data['error']));
			
			$this->link
				 ->insert($this->tablename, $data[1]);
			$this->cache(1);
			
			exit(dr_json(1, lang('014'), ''));
		}
		
		$this->template->assign(array(
			'field' => $this->field,
        ));
		$this->template->display('block_add.html');
    }

	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->link
					 ->where('id', $id)
					 ->limit(1)
					 ->get($this->tablename)
					 ->row_array();
		if (!$data) exit(lang('019'));
		
		if (IS_POST) {
		
			$data = $this->validate_filter($this->field);
			if (isset($data['error'])) exit(dr_json(0, $data['msg'], $data['error']));
			
			$this->link
				 ->where('id',(int) $id)
				 ->update($this->tablename, $data[1]);
			$this->cache(1);
			
			exit(dr_json(1, lang('014'), ''));
		}
		
		$this->template->assign(array(
			'data' => $data,
			'field' => $this->field,
        ));
		$this->template->display('block_add.html');
    }
	
    /**
     * 缓存
     */
    public function cache($update = 0) {
	
		$site = $this->input->get('site') ? $this->input->get('site') : SITE_ID;
		$this->clear_cache('block-'.$site);
		$this->dcache->delete('block-'.$site);
		
		$data = $this->link->get($this->tablename)->result_array();
		if ($data) {
			$cache = array();
			foreach ($data as $t) {
				$cache[$t['id']] = array(
					1 => $t['name'],
					0 => stripos($t['content'], '</script>') ? $t['content'] : nl2br($t['content']),
				);
			}
			$this->dcache->set('block-'.$site, $cache);
		}
		
		((int)$this->input->get('admin') || $update) or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
}