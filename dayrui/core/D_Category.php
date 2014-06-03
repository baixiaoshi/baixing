<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class D_Category extends M_Controller {

	private $thumb;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->template->assign('menu', $this->get_menu(array(
			lang('cat-00') => APP_DIR.'/admin/category/index',
			lang('258') => APP_DIR.'/admin/category/url',
		    lang('add') => APP_DIR.'/admin/category/add',
		    lang('001') => 'admin/module/cache/dir/'.APP_DIR
		)));
		$this->thumb = array(
			array(
				'name' => lang('cat-18'),
				'ismain' => 1,
				'fieldtype' => 'File',
				'fieldname' => 'thumb',
				'setting' => array(
					'option' => array(
						'ext' => 'jpg,gif,png',
						'size' => 10,
					)
				)
			)
		);
		$this->load->model('category_model');
    }
	
	/*
	 * 删除
	 */
	public function delete($ids) {
	
		if (!$ids) return NULL;
		
		// 筛选栏目id
		$catid = '';
		$category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category');
		foreach ($ids as $id) {
			$catid.= ','.$category[$id]['childids'];
		}
		
		$catid = explode(',', trim($catid, ','));
		$catid = array_flip(array_flip($catid));
		$data = $this->category_model->link
					 ->select('tableid,catid,uid,id')
					 ->where_in('catid', $catid)
					 ->get($this->content_model->prefix)
					 ->result_array();
		
		// 逐一删除内容
		foreach ($data as $t) {
			$this->content_model->delete_for_id((int)$t['id'], (int)$t['uid'], (int)$t['catid'], (int)$t['tableid']);
		}
		
		// 删除栏目
		$this->category_model->link
			 ->where_in('id', $catid)
			 ->delete($this->category_model->tablename);
	}
	
	/**
     * 获取树结构
     */
	protected function _get_tree($data) {
	
		$tree = array();
		$category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category');
		
		foreach($data as $t) {
			$t['option'] = '<a href="'.$category[$t['id']]['url'].'" target="_blank">'.lang('go').'</a>&nbsp;&nbsp;&nbsp;';
			if ($this->is_auth(APP_DIR.'/admin/cfield/index')) {
				$t['option'] .= '<a href='.$this->duri->uri2url('admin/field/index/rname/'.APP_DIR.'-'.SITE_ID.'/rid/'.$t['id']).'>'.lang('cat-01').'('.(int)count($category[$t['id']]['field']).')</a>&nbsp;&nbsp;&nbsp;';
			}
			if ($this->is_auth(APP_DIR.'/admin/category/add')) {
				$t['option'] .= '<a href='.dr_url(APP_DIR.'/category/add', array('id' => $t['id'])).'>'.lang('254').'</a>&nbsp;&nbsp;&nbsp;';
			}
			if ($this->is_auth(APP_DIR.'/admin/category/edit')) {
				$t['option'] .= '<a href='.dr_url(APP_DIR.'/category/edit', array('id' => $t['id'])).'>'.lang('edit').'</a>&nbsp;&nbsp;&nbsp;';
			}
			if (!$t['child'] && $this->is_auth(APP_DIR.'/admin/home/add')) {
				$t['option'] .= '<a href='.dr_url(APP_DIR.'/home/add', array('catid' => $t['id'])).'><font color=red><b>'.lang('mod-02').'</b></font></a>&nbsp;&nbsp;&nbsp;';
			}
			if (!$t['child']) {
				$t['option'] .= '<a href='.dr_url(APP_DIR.'/home/index', array('catid' => $t['id'])).'>'.lang('admin').'</a>&nbsp;&nbsp;&nbsp;';
			}
			$t['total'] = (int)$category[$t['id']]['total'];
			$tree[$t['id']] = $t;
		}
		
		return $tree;
	}
	
	/**
     * 批量自定义URL
     */
	public function url() {
		$category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category');
		if (IS_POST) {
			$catid = $this->input->post('catid');
			if ($catid) {
				foreach ($catid as $id) {
					$setting = $category[$id]['setting'];
					if ($setting) {
						$setting['urlrule'] = (int)$this->input->post('urlrule');
						$this->link
							 ->where('id', $id)
							 ->update($this->category_model->tablename, array('setting' => dr_array2string($setting)));
					}
				}
				$this->admin_msg(dr_lang('312', count($catid)), dr_url(APP_DIR.'/category/index'), 1, 5);
			} else {
				$error = lang('html-604');
			}
		}
		$this->template->assign(array(
			'error' => $error,
			'select' => $this->select_category($category, 0, 'id=\'dr_catid\' name=\'catid[]\' multiple style="min-width:200px;height:250px;"', ''),
		));
		$this->template->display('category_url.html');
	}
	
    /**
     * 首页
     */
    public function index() {
		if (IS_POST) {
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			if ($this->input->post('action') == 'order') {
				$data = $this->input->post('data');
				foreach ($ids as $id) {
					$this->category_model->link->where('id', $id)->update($this->category_model->tablename, $data[$id]);
				}
				exit(dr_json(1, lang('014')));
			} else {
				if (!$this->is_auth(APP_DIR.'/admin/category/index')) exit(dr_json(0, lang('160')));
				$this->delete($ids);
				exit(dr_json(1, lang('014')));
				
			}
		}
		$this->load->library('dtree');
		$this->category_model->repair();
		$this->dtree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$this->dtree->nbsp = '&nbsp;&nbsp;&nbsp;';
		$tree = array();
		$data = $this->category_model->get_data();
		if ($data) {
			$tree = $this->_get_tree($data);
		}
		$str = "<tr class='\$class'>";
		$str.= "<td align='right'><input name='ids[]' type='checkbox' class='dr_select' value='\$id' />&nbsp;</td>";
		$str.= "<td align='left'><input class='input-text displayorder' type='text' name='data[\$id][displayorder]' value='\$displayorder' /></td>";
		$str.= "<td align='left'>\$id</td>";
		if ($this->is_auth(APP_DIR.'/admin/category/edit')) {
			$str.= "<td>\$spacer<a href='".dr_url(APP_DIR.'/category/edit')."&id=\$id'>\$name</a>  \$parent</td>";
		} else {
			$str.= "<td>\$spacer\$name  \$parent</td>";
		}
		$str.= "<td align='center'>\$letter</td>";
		$str.= "<td align='left'>\$dirname</td>";
		$str.= "<td align='center'>\$total</td>";
		$str.= "<td align='left'>\$option</td>";
		$str.= "</tr>";
		$this->dtree->init($tree);
		$this->template->assign(array(
            'page' => (int)$this->input->get('page'),
			'list' => $this->dtree->get_tree(0, $str),
		));
		$this->template->display('category_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
		$id = (int)$this->input->get('id');
		$data = array();
		$result	= '';
		$data['setting']['template']['list'] = 'list.html';
		$data['setting']['template']['show'] = 'show.html';
		$data['setting']['template']['extend'] = 'extend.html';
		$data['setting']['template']['category'] = 'category.html';
		$data['setting']['template']['pagesize'] = 20;
		$data['setting']['seo']['list_title'] = '[第{page}页{join}]{name}{join}{modname}{join}{SITE_NAME}';
		$data['setting']['seo']['show_title'] = '[第{page}页{join}]{title}{join}{catname}{join}{modname}{join}{SITE_NAME}';
		$data['setting']['seo']['extend_title'] = '{extend}{join}{title}{join}{catname}{join}{modname}{join}{SITE_NAME}';
		if (IS_POST) {
			$tmp = $this->validate_filter($this->thumb);
			$data = $this->input->post('data', TRUE);
			$data['thumb'] = $tmp[1]['thumb'];
			if ($this->input->post('_all') == 1) {
				$names = $this->input->post('names', TRUE);
				$number	= $this->category_model->add_all($names, $data);
				$this->admin_msg(dr_lang('cat-03', $number), dr_url(APP_DIR.'/category/index'), 1);
			} else {
				$result	= $this->category_model->add($data);
				if (is_numeric($result)) {
					$this->attachment_handle($this->uid, $this->category_model->tablename.'-'.$result, $this->thumb);
					$this->admin_msg(lang('014'), dr_url(APP_DIR.'/category/index'), 1);
				}
			}
		}
		$this->template->assign(array(
			'id' => $id,
			'page' => 0,
			'data' => $data,
			'role' => $this->dcache->get('role'),
			'thumb' => $this->field_input($this->thumb, $data, TRUE),
			'result' => $result,
			'extend' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'extend'),
			'select' => $this->select_category($this->category_model->get_data(), $id, 'name=\'data[pid]\'', lang('cat-05')),
		));
		$this->template->display('category_add.html');
	}
	
	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->category_model->get($id);
        $page = (int)$this->input->get('page');
		$result	= '';
		if (!$data)	$this->admin_msg(lang('019'));
		
		if (IS_POST) {
		
			$_data = $data;
            $page = (int)$this->input->post('page');
			$data = $this->input->post('data', TRUE);
			$tmp = $this->validate_filter($this->thumb);
			
			$data['pid'] = $data['pid'] == $id ? $_data['pid'] : $data['pid'];
			$data['rule'] = $this->input->post('rule');
			$data['thumb'] = $tmp[1]['thumb'];
			
			$result	= $this->category_model->edit($id, $data, $_data);
			$this->category_model->syn($data, $_data, $this->input->post('synid'));
			$data['id']	= $id;
			$data['permission'] = $data['rule'];
			$this->attachment_handle($this->uid, $this->category_model->tablename.'-'.$id, $this->thumb, $_data);
			$this->admin_msg(lang('014'), dr_url(APP_DIR.'/category/index'), 1);
		}
		
		$category = $this->category_model->get_data();
		$this->template->assign(array(
			'id' => $id,
			'page' => $page,
			'data' => $data,
			'role' => $this->get_cache('role'),
			'thumb' => $this->field_input($this->thumb, $data, TRUE),
			'extend' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'extend'),
			'result' => $result,
			'select' => $this->select_category($category, $data['pid'], 'name=\'data[pid]\'', lang('cat-05')),
			'select_syn' => $this->select_category($category, 0, 'name=\'synid[]\' multiple style="min-width:150px;height:200px;"', '')
		));
		$this->template->display('category_add.html');
	}
	
	/**
     * Ajax调用栏目附加字段
	 *
	 * @return void
     */
	public function field() {
		$data = dr_string2array($this->input->post('data'));
		$field = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', (int)$this->input->post('catid'), 'field');
		if (!$field) exit('');
		exit($this->field_input($field, $data));
	}
	
	/**
     * 设置规则
     */
    public function rule() {
		
		$id = $this->input->get('id');
		$catid = $this->input->get('catid');
		$data = $this->category_model->get_permission($catid);
		
		if (IS_POST) {					
			$temp = $data[$id];																											
			$value = $this->input->post('data');
			$data[$id] = $value;
			$data[$id]['add'] = $temp['add'];
			$data[$id]['del'] = $temp['del'];
			$data[$id]['show'] = $temp['show'];
			$data[$id]['edit'] = $temp['edit'];
			$data[$id]['forbidden'] = $temp['forbidden'];
			$this->category_model
				 ->link
				 ->where('id', $catid)
				 ->update($this->category_model->tablename, array('permission' => dr_array2string($data)));
			exit;
		}
		
		$html = '<select name="data[verify]"><option value="0"> -- </option>';
		$verify = $this->get_cache('verify');
		if ($verify) {
			foreach ($verify as $t) {
				$html.= '<option value="'.$t['id'].'" '.($data[$id]['verify'] == $t['id'] ? 'selected' : '').'> '.$t['name'].'('.$t['num'].') </option>';
			}
		}
		$html.= '</select>';
		
		$this->template->assign(array(
			'data' => $data[$id],
			'verify' => $html,
			'extend' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'extend')
		));
		$this->template->display('category_rule.html');
    }
}