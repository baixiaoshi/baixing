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
	
class Navigator extends M_Controller {

	private $type;
	private $menu;
	private $filed;
    
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$menu = array();
		$data = explode(',', SITE_NAVIGATOR);
		$this->type = (int)$this->input->get('type');
		foreach ($data as $i => $name) {
			if ($name) {
				$menu[$name] = 'admin/navigator/index/type/'.$i;
				$this->menu[$i] = $name;
			}
		}
		$menu[lang('add')] = 'admin/navigator/add/type/'.$this->type;
		$menu[lang('001')] = 'admin/navigator/cache';
		$this->template->assign('menu', $this->get_menu($menu));
		
		$this->field = array(
			'name' => array(
				'ismain' => 1,
				'fieldname' => 'name',
				'fieldtype' => 'Text',
				'setting' => array(
					'option' => array(
						'width' => 150,
					),
					'validate' => array(
						'required' => 1
					)
				)
			),
			'title' => array(
				'ismain' => 1,
				'fieldname' => 'title',
				'fieldtype'	=> 'Text',
				'setting' => array(
					'option' => array(
						'width' => 300,
					)
				)
			),
			'url' => array(
				'name' => '',
				'ismain' => 1,
				'fieldname' => 'url',
				'fieldtype'	=> 'Text',
				'setting' => array(
					'option' => array(
						'width' => 400,
						'value' => 'http://',
					)
				)
			),
			'thumb' => array(
				'ismain' => 1,
				'fieldname' => 'thumb',
				'fieldtype' => 'File',
				'setting' => array(
					'option' => array(
						'ext' => 'jpg,gif,png',
						'size' => 10,
					)
				)
			),
			'target' => array(
				'ismain' => 1,
				'fieldname' => 'target',
				'fieldtype'	=> 'Radio',
				'setting' => array(
					'option' => array(
						'value' => '1',
						'options' => lang('yes').'|1'.PHP_EOL.lang('no').'|0',
					)
				)
			),
			'show' => array(
				'ismain' => 1,
				'fieldname' => 'show',
				'fieldtype'	=> 'Radio',
				'setting' => array(
					'option' => array(
						'value' => '1',
						'options' => lang('yes').'|1'.PHP_EOL.lang('no').'|0',
					)
				)
			),
			'displayorder' => array(
				'ismain' => 1,
				'fieldname' => 'displayorder',
				'fieldtype'	=> 'Text',
				'setting' => array(
					'option' => array(
						'width' => 74,
						'value' => '0'
					)
				)
			),
		);
		$this->load->model('navigator_model');
    }
    
	/**
     * 管理列表
     */
    public function index() {
		
		if (IS_POST && $this->input->post('ids')) {
			$table = SITE_ID.'_navigator';
			if ($this->input->post('action') == 'del') {
				// 删除
				$ids = $this->input->post('ids');
				$this->db
					 ->where_in('id', $ids)
					 ->delete($table);
				$this->cache(1);
				$this->load->model('attachment_model');
				foreach ($ids as $id) {
					$this->attachment_model->delete_for_table($this->db->dbprefix($table).'-'.$id);
				}
			} else if ($this->input->post('action') == 'order' && $this->is_auth('navigator/edit')) {
				// 修改
				$_ids = $this->input->post('ids');
				$_data = $this->input->post('data');
				foreach ($_ids as $id) {
					$this->db
						 ->where('id', (int)$id)
						 ->update($table, $_data[$id]);
				}
				$this->cache(1);
				unset($_ids, $_data);
			}
			exit(dr_json(1, lang('000')));
		}
		
		$this->load->library('dtree');
		$this->dtree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$this->dtree->nbsp = '&nbsp;&nbsp;&nbsp;';
		
		$tree = array();
		$data = $this->navigator_model->get_data($this->type);
		
		if ($data) {
			foreach($data as $t) {
				$add = dr_url('navigator/add', array('pid' => $t['id'], 'type' => $this->type));
				$edit = dr_url('navigator/edit', array('id' => $t['id'], 'type' => $this->type));
				$t['option'] = '';
				if ($this->is_auth('admin/navigator/add')) {
					$t['option'].= '<a class="add" style="margin-top:3px;" title="'.lang('add').'" href="'.$add.'"></a>';
				}
				if ($this->is_auth('admin/navigator/edit')) {
					$t['option'].= '<a class="edit" style="margin-top:3px;" title="'.lang('edit').'" href="'.$edit.'"></a>';
				}
				$t['option'].= '&nbsp;&nbsp;<a title="'.lang('go').'" href="'.$t['url'].'" target="_blank">'.lang('go').'</a>';
				$tree[$t['id']] = $t;
			}
		}
		
		$str = "<tr class='\$class'>";
		$str.= "<td align='right'><input name='ids[]' type='checkbox' class='dr_select' value='\$id' />&nbsp;</td>";
		$str.= "<td align='left'><input class='input-text displayorder' type='text' name='data[\$id][displayorder]' value='\$displayorder' /></td>";
		$str.= "<td align='left'>\$id</td>";
		if ($this->is_auth('admin/navigator/edit')) {
			$str.= "<td>\$spacer<a href='".dr_url(APP_DIR.'/navigator/edit')."&id=\$id&type=".$this->type."'>\$name</a>  \$parent</td>";
		} else {
			$str.= "<td>\$spacer\$name  \$parent</td>";
		}
		$str.= "<td align='center'>";
		if ($this->is_auth('admin/navigator/edit')) {
			$str.= "<a href='".dr_url('navigator/target')."&id=\$id'><img src='".SITE_URL."dayrui/statics/images/\$target.gif' /></a>";
		} else {
			$str.= "<img src='".SITE_URL."dayrui/statics/images/\$target.gif' />";
		}
		$str.= "</td>";
		$str.= "<td align='center'>";
		if ($this->is_auth('admin/navigator/edit')) {
			$str.= "<a href='".dr_url('navigator/show')."&id=\$id'><img src='".SITE_URL."dayrui/statics/images/\$show.gif' /></a>";
		} else {
			$str.= "<img src='".SITE_URL."dayrui/statics/images/\$show.gif' />";
		}
		$str.= "</td>";
		$str.= "<td align='left'>\$option</td>";
		$str.= "</tr>";
		$this->dtree->init($tree);
		
		$this->template->assign(array(
			'type' => $this->type,
			'list' => $this->dtree->get_tree(0, $str)
		));
		$this->template->display('navigator_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
		
		$pid = (int)$this->input->get('pid');
		
		if (IS_POST) {
			$data = $this->validate_filter($this->field);
			if (isset($data['error'])) {
				$error = $data['msg'];
				$data = $this->input->post('data');
			} else {
				$data[1]['pid'] = $this->input->post('pid');
				$data[1]['type'] = $this->type;
				$id = (int)$this->navigator_model->add($data[1]);
				$this->cache(1);
				$this->attachment_handle($this->uid, $this->navigator_model->tablename.'-'.$id, $this->field);
				$this->admin_msg(lang('000'), dr_url('navigator/index', array('type' => $this->type)), 1);
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'error' => $error,
			'field' => $this->field,
			'select' => $this->_select($this->navigator_model->get_data($this->type), $pid, 'name=\'pid\'', lang('150')),
			'typename' => $this->menu[$this->type],
		));
		$this->template->display('navigator_add.html');
	}
	
	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$nav = $this->navigator_model->get_data($this->type);
		$data = $nav[$id];
		if (!$data) $this->admin_msg(lang('019'));
		
		if (IS_POST) {
			$post = $this->validate_filter($this->field);
			if (isset($post['error'])) {
				$data = $this->input->post('data');
				$error = $post['msg'];
			} else {
				$post[1]['pid'] = $this->input->post('pid');
				$id = (int)$this->navigator_model->edit($id, $post[1]);
				$this->cache(1);
				$this->attachment_handle($this->uid, $this->navigator_model->tablename.'-'.$id, $this->field, $data);
				$this->admin_msg(lang('000'), dr_url('navigator/index', array('type' => $this->type)), 1);
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'error' => $error,
			'field' => $this->field,
			'select' => $this->_select($nav, $data['pid'], 'name=\'pid\'', lang('150')),
			'typename' => $this->menu[$this->type],
		));
		$this->template->display('navigator_add.html');
	}
	
	/**
     * 新窗口打开
     */
    public function target() {
		if ($this->is_auth('admin/navigator/edit')) {
			$id = (int)$this->input->get('id');
			$data = $this->db
						 ->select('target,type')
						 ->where('id', $id)
						 ->limit(1)
						 ->get(SITE_ID.'_navigator')
						 ->row_array();

			$this->db
				 ->where('id', $id)
				 ->update(SITE_ID.'_navigator', array('target' => ($data['target'] == 1 ? 0 : 1)));
			$this->cache(1);
			
			$this->admin_msg(lang('000'), dr_url('navigator/index', array('type' => $data['type'])), 1);
		} else {
			$this->admin_msg(lang('160'));
		}
    }
	
	/**
     * 显示
     */
    public function show() {
		if ($this->is_auth('admin/navigator/edit')) {
			$id = (int)$this->input->get('id');
			$data = $this->db
						 ->select('show,type')
						 ->where('id', $id)
						 ->limit(1)
						 ->get(SITE_ID.'_navigator')
						 ->row_array();

			$this->db
				 ->where('id', $id)
				 ->update(SITE_ID.'_navigator', array('show' => ($data['show'] == 1 ? 0 : 1)));
			$this->cache(1);	 
				 
			$this->admin_msg(lang('000'), dr_url('navigator/index', array('type' => $data['type'])), 1);
		} else {
			$this->admin_msg(lang('160'));
		}
    }
	
	/**
     * 缓存
	 * array(
	 *			'站点id' =>	array(
	 *						'导航类型id' => array(导航数据),
	 *						... ,
	 *					),
	 *			... ,
	 *		)
     */
    public function cache($update = 0) {
	
		$site = $this->input->get('site') ? $this->input->get('site') : SITE_ID;
		$this->navigator_model->repair($site);
		
		$data = $this->db
					 ->where('show', 1)
					 ->order_by('displayorder asc')
					 ->get($site.'_navigator')
					 ->result_array();			 
		// 当前站点有数据时更新缓存
		$this->clear_cache('navigator-'.$site);
		$this->dcache->delete('navigator-'.$site);
		
		if ($data) {
			$cahce = array();
			foreach ($data as $t) {
				$cache[(int)$t['type']][] = $t;
			}
			$this->dcache->set('navigator-'.$site, $cache);
		}
		
		((int)$this->input->get('admin')|| $update) or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
	
	/**
	 * 上级选择
	 *
	 * @param array			$data		数据
	 * @param intval/array	$id			被选中的ID
	 * @param string		$str		属性
	 * @param string		$default	默认选项
	 * @return string
	 */
	private function _select($data, $id = 0, $str = '', $default = ' -- ') {
	
		$tree = array();
		$string = '<select '.$str.'>';
		
		if ($default) $string .= "<option value='0'>$default</option>";
		
		if (is_array($data)) {
			foreach($data as $t) {
				$t['selected'] = ''; // 选中操作
				if (is_array($id)) {
					$t['selected'] = in_array($t['id'], $id) ? 'selected' : '';
				} elseif(is_numeric($id)) {
					$t['selected'] = $id == $t['id'] ? 'selected' : '';
				}
				
				$tree[$t['id']] = $t;
			}
		}
		
		$str = "<option value='\$id' \$selected>\$spacer \$name</option>";
		$str2 = "<optgroup label='\$spacer \$name'></optgroup>";
		
		$this->load->library('dtree');
		$this->dtree->init($tree);
		
		$string .= $this->dtree->get_tree_category(0, $str, $str2);
		$string .= '</select>';
		
		return $string;
	}
}