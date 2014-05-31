<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.5
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class Format extends M_Controller {

	private $field;
	private $data_field;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->data_field = $this->field = array(
			'name' => array(
				'ismain' => 1,
				'fieldname' => 'name',
				'fieldtype' => 'Text',
				'setting' => array(
					'option' => array(
						'width' => 273
					),
					'validate' => array(
						'required' => 1
					)
				)
			)
		);
		$this->data_field['displayorder'] = array(
			'ismain' => 1,
			'fieldname' => 'displayorder',
			'fieldtype'	=> 'Text',
			'setting' => array(
				'option' => array(
					'width' => 40,
					'value' => '0'
				)
			)
		);
		$this->load->model('format_model');
    }

    /**
     * 首页
     */
    public function index() {
	
		if (IS_POST && $this->input->post('action')) {
			
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			
			if ($this->input->post('action') == 'del') {
				if (!$this->is_auth(APP_DIR.'admin/format/del')) exit(dr_json(0, lang('160')));
				$this->link
					 ->where_in('id', $ids)
					 ->delete($this->format_model->tablename);
				$this->link
					 ->where_in('fid', $ids)
					 ->delete($this->format_model->dataname);
				$this->format_model->cache();
				exit(dr_json(1, lang('000')));
			} else {
				if (!$this->is_auth(APP_DIR.'admin/format/edit')) exit(dr_json(0, lang('160')));
				$_data = $this->input->post('data');
				foreach ($ids as $id) {
					$this->link
						 ->where('id', $id)
						 ->update($this->format_model->tablename, $_data[$id]);
				}
				$this->format_model->cache();
				exit(dr_json(1, lang('000')));
			}			
		}
	
		// 根据参数筛选结果
		$param = array();
		if ($this->input->get('search')) $param['search'] = 1;
		
		// 数据库中分页查询
		list($data, $param)	= $this->format_model->limit_page($param, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		
		if ($this->input->get('search')) {
			$_param = $this->cache->file->get($this->format_model->cache_file);
		} else {
			$_param = $this->input->post('data');
		}
		$_param = $_param ? $param + $_param : $param;
		
		$this->template->assign(array(
			'list' => $data,
			'pages'	=> $this->get_pagination(dr_url(APP_DIR.'/format/index', $param), $param['total']),
			'param'	=> $_param,
			'menu' => $this->get_menu(array(
				lang('mod-43') => APP_DIR.'/admin/format/index',
				lang('add') => APP_DIR.'/admin/format/add_js',
				lang('001') => APP_DIR.'/admin/format/cache'
			))
		));
		$this->template->display('format_index.html');
    }
	
	/**
     * add
     */
    public function add() {
		
		if (IS_POST) {
		
			$data = $this->validate_filter($this->field);
			$data[1]['catid'] = $this->input->post('catid');
			if (!$data[1]['catid']) exit(dr_json(0, lang('cat-22'), 'catid'));
			if (isset($data['error'])) exit(dr_json(0, $data['msg'], $data['error']));
			
			$format = $this->format_model->add($data[1]);
			if (is_numeric($format)) {
				$this->format_model->cache();
				exit(dr_json(1, lang('000')));
			} else {
				exit(dr_json(0, $format, 'name'));
			}
		}
		
		$format = array();
		$_format = $this->link->get($this->format_model->tablename)->result_array();
		if ($_format) {
			foreach ($_format as $t) {
				$catid = explode(',', $t['catid']);
				foreach ($catid as $i) {
					if ($i) $format[$i] = $i;
				}
			}
		}
		
		$this->template->assign(array(
			'select' => $this->_category2($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), 0, 'name=\'catid[]\' multiple style="width:280px;height:200px;"', $format),
			'field' => $this->field
		));
		$this->template->display('format_add.html');
    }
	
	/**
     * add
     */
    public function edit() {
		
		$id = (int)$this->input->get('id');
		$data = $this->format_model->get($id);
		if (!$data) exit(lang('019'));
		
		if (IS_POST) {
		
			$data = $this->validate_filter($this->field);
			$data[1]['catid'] = $this->input->post('catid');
			if (!$data[1]['catid']) exit(dr_json(0, lang('cat-22'), 'catid'));
			if (isset($data['error'])) exit(dr_json(0, $data['msg'], $data['error']));
			
			$format = $this->format_model->edit($id, $data[1]);
			if (is_numeric($format)) {
				$this->attachment_handle($this->uid, $this->format_model->tablename.'-'.$format, $this->field);
				$this->format_model->cache();
				exit(dr_json(1, lang('000')));
			} else {
				exit(dr_json(0, $format, 'name'));
			}
		}
		
		$format = array();
		$_format = $this->link->get($this->format_model->tablename)->result_array();
		if ($_format) {
			foreach ($_format as $t) {
				if ($t['id'] != $id) {
					$catid = explode(',', $t['catid']);
					foreach ($catid as $i) {
						if ($i) $format[$i] = $i;
					}
				}
			}
		}
		
		$this->template->assign(array(
			'data' => $data,
			'select' => $this->_category2($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'),$data['catid'],'name=\'catid[]\' multiple style="width:280px;height:200px;"', $format),
			'field' => $this->field
			
		));
		$this->template->display('format_add.html');
    }
	
	/**
     * 规格属性
     */
    public function data() {
	
		$fid = (int)$this->input->get('fid');
		
		if (IS_POST && $this->input->post('action')) {
			
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			
			if ($this->input->post('action') == 'del') {
				if (!$this->is_auth(APP_DIR.'admin/format/del')) exit(dr_json(0, lang('160')));
				$this->link
					 ->where('fid', $fid)
					 ->where_in('id', $ids)
					 ->delete($this->format_model->dataname);
				$this->format_model->cache();
				exit(dr_json(1, lang('000')));
			} else {
				if (!$this->is_auth(APP_DIR.'admin/format/edit')) exit(dr_json(0, lang('160')));
				$_data = $this->input->post('data');
				foreach ($ids as $id) {
					$this->link
						 ->where('id', $id)
						 ->update($this->format_model->dataname, $_data[$id]);
				}
				$this->format_model->cache();
				exit(dr_json(1, lang('000')));
			}			
		}
		
		$this->load->library('dtree');
		$this->dtree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$this->dtree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$data = $this->format_model->get_data($fid);
		$tree = array();
		
		if ($data) {
			foreach($data as $t) {
				$t['option'] = '';
				if ($this->is_auth(APP_DIR.'/admin/format/adddata') && $t['pid'] == 0) {
					$t['option'] .= '<a class="add" title="'.lang('add').'" href="'.dr_dialog_url(dr_url(APP_DIR.'/format/adddata', array('fid'=>$fid, 'pid'=>$t['id'])), 'add').'"></a>&nbsp;&nbsp;';
				}
				if ($this->is_auth(APP_DIR.'/admin/format/editdata')) {
					$t['option'] .= '<a class="edit" title="'.lang('edit').'" href="'.dr_dialog_url(dr_url(APP_DIR.'/format/editdata', array('fid'=>$fid, 'id'=>$t['id'])), 'edit').'"></a>&nbsp;&nbsp;';
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
			'fid' => $fid,
			'list' => $this->dtree->get_tree(0, $str),
			'menu' => $this->get_menu(array(
				lang('back') => APP_DIR.'/admin/format/index',
				lang('mod-43') => APP_DIR.'/admin/format/data/fid/'.$fid,
				lang('001') => APP_DIR.'/admin/format/cache'
			)),
		));
		$this->template->display('format_data.html');
    }
	
	/**
     * add规格属性
     */
    public function adddata() {
	
		$fid = (int)$this->input->get('fid');
		$pid = (int)$this->input->get('pid');
		if (!$fid) exit(lang('mod-41'));
		
		if (IS_POST) {
		
			$data = $this->validate_filter($this->data_field);
			$data[1]['fid'] = $fid;
			$data[1]['pid'] = $pid;
			if (isset($data['error'])) exit(dr_json(0, $data['msg'], $data['error']));
			
			$format = $this->format_model->adddata($data[1]);
			if (is_numeric($format)) {
				$this->format_model->cache();
				exit(dr_json(1, lang('000')));
			} else {
				exit(dr_json(0, $format, 'name'));
			}
		}
		
		$this->template->assign(array(
			'field' => $this->data_field
		));
		$this->template->display('format_adddata.html');
	}
	
	/**
     * edit规格属性
     */
    public function editdata() {
	
		$id = (int)$this->input->get('id');
		$fid = (int)$this->input->get('fid');
		if (!$fid) exit(lang('mod-41'));
		$data = $this->link
					 ->where('id', $id)
					 ->where('fid', $fid)
					 ->limit(1)
					 ->get($this->format_model->dataname)
					 ->row_array();
		if (!$data) exit(lang('019'));
		
		if (IS_POST) {
		
			$data = $this->validate_filter($this->data_field);
			$data[1]['fid'] = $fid;
			$data[1]['pid'] = $this->input->post('pid');
			if (isset($data['error'])) exit(dr_json(0, $data['msg'], $data['error']));
			
			$format = $this->format_model->editdata($id, $data[1]);
			if (is_numeric($format)) {
				$this->format_model->cache();
				exit(dr_json(1, lang('000')));
			} else {
				exit(dr_json(0, $format, 'name'));
			}
		}
		
		$select = '<select name="pid"><option value="0"> '.lang('mod-44').' </option>';
		$top = $this->link
					->where('pid', 0)
					->where('fid', $fid)
					->get($this->format_model->dataname)
					->result_array(); 
		if ($top) {
			foreach ($top as $t) {
				$select.= '<option value="'.$t['id'].'" '.($t['id'] == $data['pid'] ? 'selected' : '').'> '.$t['name'].' </option>';
			}
		}
		$select.= '</select>';
		
		$this->template->assign(array(
			'data' => $data,
			'field' => $this->data_field,
			'select' => $select,
		));
		$this->template->display('format_adddata.html');
	}
	
	/**
     * cache
     */
    public function cache() {
		$admin = (int)$this->input->get('admin');
		$this->format_model->cache();
		$admin or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
	
	/**
	 * 栏目选择
	 *
	 * @param array			$data		栏目数据
	 * @param intval/array	$id			被选中的ID，多选是可以是数组
	 * @param string		$str		属性
	 * @param array			$format		栏目数据
	 * @return string
	 */
	public function _category2($data, $id = 0, $str = '', $format) {
		
		$cache = md5(dr_array2string($data).dr_array2string($id).dr_array2string($format).$str.$default.$this->member['uid']);
		//if ($cache_data = $this->get_cache_data($cache)) return $cache_data;
		
		$tree = array();
		$string = '<select '.$str.'>';
		
		if ($default) $string .= "<option value='0'>$default</option>";
		
		if (is_array($data)) {
			foreach($data as $t) {
				// 选中操作
				$t['selected'] = @in_array($t['id'], $id) ? 'selected' : '';
				// 是否可选子栏目
				$t['html_disabled'] = $t['child'] != 0 ? 1 : 0;
				// 是否含有属性
				if (isset($format[$t['id']])) continue;
				$tree[$t['id']] = $t;
			}
		}
		
		$str = "<option value='\$id' \$selected>\$spacer \$name</option>";
		$str2 = "<optgroup label='\$spacer \$name'></optgroup>";
		
		$this->load->library('dtree');
		$this->dtree->init($tree);
		
		$string .= $this->dtree->get_tree_category(0, $str, $str2);
		$string .= '</select>';
		
		if ($tree) $this->set_cache_data($cache, $string, 7200);
		
		return $string;
	}
}