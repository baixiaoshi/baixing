<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.2
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class linkage extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->load->model('linkage_model');
    }
	
	/**
     * index
     */
    public function index() {
		if (IS_POST) {
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			if (!$this->is_auth('admin/linkage/del')) exit(dr_json(0, lang('160')));
			$this->db->where_in('id', $ids)->delete('linkage');
			foreach ($ids as $key) {
				$this->db->query('DROP TABLE `'.$this->db->dbprefix('linkage_data_'.$key).'`');
			}
			exit(dr_json(1, lang('014')));
		}
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('185') => APP_DIR.'/admin/linkage/index',
				lang('add') => APP_DIR.'/admin/linkage/add_js',
			)),
			'list' => $this->linkage_model->get_data()
		));
		$this->template->display('linkage_index.html');
	}
	
	/**
     * 添加
     */
    public function add() {
		if (IS_POST) {
			$result = $this->linkage_model->add($this->input->post('data', TRUE));
			$result ? exit(dr_json(0, $result['error'], $result['name'])) : exit(dr_json(1, lang('000')));
		}
		$this->template->display('linkage_add.html');
    }
	
	/**
     * 修改
     */
    public function edit() {
		$id = (int)$this->input->get('id');
		$data = $this->linkage_model->get($id);
		if (!$data)	exit(lang('019'));
		if (IS_POST) {
			$result	= $this->linkage_model->edit($id, $this->input->post('data', TRUE));
			$result ? exit(dr_json(0, $result['error'], $result['name'])) : exit(dr_json(1, lang('000')));
		}
		$this->template->assign(array(
			'data' => $data,
		));
		$this->template->display('linkage_add.html');
	}
	
    /**
     * 菜单
     */
    public function data() {
		$key = (int)$this->input->get('key');
		$pid = (int)$this->input->get('pid');
		$link = $this->linkage_model->get($key);
		if (!$link)	$this->admin_msg(lang('187'));
		if (IS_POST) {
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			if ($this->input->post('action') == 'order') {
				$data = $this->input->post('data');
				foreach ($ids as $id) {
					$this->db
						 ->where('id', (int)$id)
						 ->update('linkage_data_'.$key, $data[$id]);
				}
				exit(dr_json(1, lang('014')));
			} else {
				if (!$this->is_auth(APP_DIR.'/admin/linkage/index')) exit(dr_json(0, lang('160')));
				$delete = '';
				foreach ($ids as $id) {
					$data = $this->db
								 ->where('id', $id)
								 ->limit(1)
								 ->get('linkage_data_'.$key)
								 ->row_array();
					if ($data['childids']) $delete.= $data['childids'].',';
				}
				$delete = trim($delete, ',');
				if ($delete) {
					$this->db->query("delete from {$this->db->dbprefix('linkage_data_'.$key)} where id in ($delete)");
					$this->linkage_model->repair($key);
				}
				exit(dr_json(1, lang('014')));
			}
		}
		$this->template->assign(array(
			'key' => $key,
			'pid' => $pid,
			'list' => $this->linkage_model->get_list_data($link, $pid),
			'menu' => $this->get_menu(array(
				lang('back') => APP_DIR.'/admin/linkage/index',
				lang('185') => APP_DIR.'/admin/linkage/data/key/'.$key,
				lang('add') => APP_DIR.'/admin/linkage/adds/key/'.$key.'_js'
			)),
		));
		$this->template->display('linkage_data.html');
    }
	
	/**
     * 添加
     */
    public function adds() {
		$pid = (int)$this->input->get('pid');
		$key = (int)$this->input->get('key');
		$link = $this->linkage_model->get($key);
		if (!$link)	exit(lang('187'));
		if (IS_POST) {
			$result	= $this->linkage_model->adds($key, $this->input->post('data'));
			$result ? exit(dr_json(0, $result['error'], $result['name'])) : exit(dr_json(1, lang('000')));
			exit;
		}
		$this->template->assign(array(
			'select' => $this->select_linkage($this->linkage_model->get_list_data($link), $pid, 'name=\'data[pid]\'', lang('188')),
		));
		$this->template->display('linkage_adds.html');
	}
	
	/**
     * 修改
     */
    public function edits() {
		$id = (int)$this->input->get('id');
		$key = (int)$this->input->get('key');
		$link = $this->linkage_model->get($key);
		if (!$link)	exit(lang('187'));
		$data = $this->linkage_model->gets($id, $key);
		if (!$data)	exit(lang('019'));
		if (IS_POST) {
			$edit = $this->input->post('data');
			$edit['pid'] = $edit['pid'] == $id ? $data['pid'] : $edit['pid'];
			$result	= $this->linkage_model->edits($key, $id, $edit);
			$result ? exit(dr_json(0, $result['error'], $result['name'])) : exit(dr_json(1, lang('000')));
			exit;
		}
		$this->template->assign(array(
			'data' => $data,
			'select' => $this->select_linkage($this->linkage_model->get_list_data($link), $data['pid'], 'name=\'data[pid]\'', lang('188')),
		));
		$this->template->display('linkage_adds.html');
	}
	
	/**
     * 导入联动数据
     */
    public function import() {
		if ($this->input->get('admin')) {
			// 导入数据
			$this->sql_query(str_replace(
				'{dbprefix}',
				$this->db->dbprefix,
				file_get_contents(FCPATH.'cache/install/linkage.sql')
			));
			$this->admin_msg(lang('014'), dr_url('linkage/index'), 1);
		} else {
			$this->admin_msg('Import ... ', dr_url('linkage/import', array('admin' => 1)), 2);
		}
	}
	
	/**
     * 缓存
     */
    public function cache() {
		$site = $this->input->get('site') ? $this->input->get('site') : SITE_ID;
		$this->linkage_model->cache($site);
		(int)$this->input->get('admin') or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
	
	/**
	 * 栏目选择
	 *
	 * @param array			$data		栏目数据
	 * @param intval/array	$id			被选中的ID，多选是可以是数组
	 * @param string		$str		属性
	 * @param string		$default	默认选项
	 * @return string
	 */
	public function select_linkage($data, $id = 0, $str = '', $default = ' -- ') {
		$tree = array();
		$string = '<select '.$str.'>';
		if ($default) $string .= "<option value='0'>$default</option>";
		if (is_array($data)) {
			foreach($data as $t) {
				// 选中操作
				$t['selected'] = '';
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