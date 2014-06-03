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
	
class Menu extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign('menu', $this->get_menu(array(
		    lang('012') => 'admin/menu/index',
		    lang('001') => 'admin/menu/cache'
		)));
		$this->load->model('menu_model');
    }
	
	/**
     * 菜单管理
     */
    public function index() {
	
		if (IS_POST) {
		
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			// 可以不用判断权限
			
			if ($this->input->post('action') == 'order') {
				$_data = $this->input->post('data');
				foreach ($ids as $id) {
					$this->db->where('id', $id)->update('admin_menu',  array('displayorder' => (int)$_data[$id]['displayorder']));
				}
				$this->menu_model->cache();
				exit(dr_json(1, lang('014')));
			} else {
				$this->db->where_in('id', $ids)->delete('admin_menu');
				$this->menu_model->cache();
				exit(dr_json(1, lang('014')));
			}
		}
		
		$this->load->library('dtree');
		$this->dtree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$this->dtree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$left = $this->menu_model->get_left_id();
		$data = $this->db
					 ->order_by('displayorder ASC,id ASC')
					 ->get('admin_menu')
					 ->result_array();
		$tree = array();
		
		if ($data) {
			foreach($data as $t) {
				$t['option'] = '';
				if ($this->is_auth('admin/menu/add') && !in_array($t['pid'], $left)) {
					$t['option'].= '<a class="add" title="'.lang('add').'" href="'.dr_dialog_url(dr_url('menu/add', array('pid' => $t['id'])), 'add').'"></a>&nbsp;&nbsp;';
				} else {
					$t['option'].= '<a class="add" style="background:none" href="javascript:;"></a>&nbsp;&nbsp;';
				}
				if ($this->is_auth('admin/menu/edit')) {
					$t['option'].= '<a class="edit" title="'.lang('edit').'" href="'.dr_dialog_url(dr_url('menu/edit', array('id' => $t['id'])), 'edit').'"></a>&nbsp;&nbsp;';
				}
				if ($this->is_auth('admin/menu/del')) {
					$t['option'].= '<a class="del" title="'.lang('del').'" href="javascript:;" onClick="return dr_dialog_del(\''.lang('015').'\',\''.dr_url('menu/del',array('id' => $t['id'])).'\');"></a>&nbsp;&nbsp;';
				}
				$tree[$t['id']] = $t;
			}
		}
		
		$str = "<tr>
					<td align='right'><input name='ids[]' type='checkbox' class='dr_select' value='\$id' />&nbsp;</td>
					<td align='center'><input class='input-text displayorder' type='text' name='data[\$id][displayorder]' value='\$displayorder' /></td>
					<td align='left'>&nbsp;</td>
					<td>\$spacer\$name</td>
					<td align='left'>\$option</td>
				</tr>";
		$this->dtree->init($tree);
		
		$this->template->assign(array(
			'list' => $this->dtree->get_tree(0, $str),
		));
		$this->template->display('menu_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		if (IS_POST) {
			$_data = $this->input->post('data', TRUE);
			if ($this->input->post('_type') == 2) {
				$_data['url'] = '';
			} else {
				$_data['directory'] = $_data['dir'] = $_data['class'] = $_data['method'] = $_data['param'] = '';
			}
			exit(dr_json(1, lang('014'), $this->menu_model->add($_data)));
		}
		
		$top = $this->menu_model->get_top_id();
		$menu_name = $menu_type	= '';
		$data['pid'] = (int)$this->input->get('pid');
		if ($data['pid']) {
			if (in_array($data['pid'], $top)) {
				$menu_type = 0;
				$menu_name = lang('017');
			} else {
				$menu_type = 1;
				$menu_name = lang('018');
			}
		} else {
			$menu_type = 0;
			$menu_name = lang('016');
		}
		
		$this->template->assign(array(
			'data' => $data,
			'menu_url' => 2,
			'menu_name'	=> $menu_name,
			'menu_type'	=> $menu_type
		));
		$this->template->display('menu_add.html');
    }

	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get('admin_menu')
					 ->row_array();
		if (!$data) exit(lang('019'));
		
		if (IS_POST) {
			$_data = $this->input->post('data', TRUE);
			if ($this->input->post('_type') == 2) {
				$_data['url'] = '';
			} else {
				$_data['directory'] = $_data['dir'] = $_data['class'] = $_data['method'] = $_data['param'] = '';
			}
			exit(dr_json(1, lang('014'), $this->menu_model->edit($data, $_data)));
		}
		
		$top = $this->menu_model->get_top_id();
		$uri = $this->duri->uri2ci($data['uri']);
		$uri['dir']	= $uri['app'] ? $uri['app'] : ($uri['path'] ? $uri['path'] : '');
		$menu_name = $menu_type = '';
		$select = '<select name="data[pid]">';
		if ($data['pid']) {
			if (in_array($data['pid'], $top)) { // 分组菜单
				$menu_type = 0;
				$menu_name = lang('017');
				$select = $this->menu_model->parent_select(1, $data['pid']);
			} else { // 链接菜单
				$menu_type = 1;
				$menu_name = lang('018');
				$select = $this->menu_model->parent_select(2, $data['pid']);
			}
		} else { // 顶级菜单
			$menu_type = 0;
			$menu_name = lang('016');
			$select = $this->menu_model->parent_select(0, $data['pid']);
		}
		$this->template->assign(array(
			'uri' => $uri,
			'data' => $data,
			'select' => $select,
			'menu_url' => $data['uri'] ? 2 : 1,
			'menu_name'	=> $menu_name,
			'menu_type'	=> $menu_type
		));
		$this->template->display('menu_add.html');
    }
	
	/**
     * 删除
     */
    public function del() {
		$this->db
			 ->where('id', (int)$this->input->get('id'))
			 ->delete('admin_menu');
		$this->menu_model->cache();
		exit(dr_json(1, lang('014')));
	}
	
	/**
     * 初始化菜单
     */
    public function init() {
		$this->menu_model->init();
		$this->menu_model->cache();
		$this->admin_msg(lang('000'), dr_url('menu/index'), 1);
	}
	
	/**
     * 缓存
     */
    public function cache() {
		$admin = $this->input->get('admin') ? $this->input->get('admin') : $this->input->get('admin');
		$this->menu_model->cache();
		$admin or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
}