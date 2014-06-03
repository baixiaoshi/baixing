<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class D_Page extends M_Controller {

	private $field;

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		if (IS_ADMIN) {
			$this->template->assign('menu', $this->get_menu(array(
				lang('152') => APP_DIR.'/admin/page/index',
				lang('add') => APP_DIR.'/admin/page/add',
				lang('001') => APP_DIR.'/admin/page/cache'
			)));
		}
		$this->field = array(
			'name' => array(
				'name' => lang('139'),
				'ismain' => 1,
				'fieldname' => 'name',
				'fieldtype' => 'Text',
				'setting' => array(
					'option' => array(
						'width' => 150,
					),
					'validate' => array(
						'required' => 1,
						'formattr' => 'onblur="d_topinyin(\'dirname\',\'name\');"',
					)
				)
			),
			'dirname' => array(
				'name' => lang('140'),
				'ismain' => 1,
				'fieldname' => 'dirname',
				'fieldtype' => 'Text',
				'setting' => array(
					'option' => array(
						'width' => 150,
					),
					'validate' => array(
						'required' => 1,
					)
				)
			),
			'thumb' => array(
				'name' => lang('141'),
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
			'keywords' => array(
				'name' => lang('143'),
				'ismain' => 1,
				'fieldname' => 'keywords',
				'fieldtype'	=> 'Text',
				'setting' => array(
					'option' => array(
						'width' => 300
					)
				)
			),
			'title' => array(
				'name' => lang('142'),
				'ismain' => 1,
				'fieldname' => 'title',
				'fieldtype'	=> 'Text',
				'setting' => array(
					'option' => array(
						'width' => 300
					)
				)
			),
			'description' => array(
				'name' => lang('144'),
				'ismain' => 1,
				'fieldname' => 'description',
				'fieldtype'	=> 'Textarea',
				'setting' => array(
					'option' => array(
						'width' => 500,
						'height' => 60
					)
				)
			),
			'content' => array(
				'name' => lang('145'),
				'ismain' => 1,
				'fieldname' => 'content',
				'fieldtype'	=> 'Ueditor',
				'setting' => array(
					'option' => array(
						'mode' => 1,
						'width' => '90%',
						'height' => 400,
					)
				)
			),
			'attachment' => array(
				'name' => lang('146'),
				'ismain' => 1,
				'fieldname' => 'attachment',
				'fieldtype' => 'Files',
				'setting' => array(
					'option' => array(
						'ext' => 'jpg,gif,png,ppt,doc,xls,rar,zip',
						'size' => 10,
						'count' => 50,
					)
				)
			),
			'template' => array(
				'name' => lang('147'),
				'ismain' => 1,
				'fieldname' => 'template',
				'fieldtype'	=> 'Text',
				'setting' => array(
					'option' => array(
						'width' => 200,
						'value' => 'page.html'
					)
				)
			),
			'urllink' => array(
				'name' => lang('148'),
				'ismain' => 1,
				'fieldname' => 'urllink',
				'fieldtype'	=> 'Text',
				'setting' => array(
					'option' => array(
						'width' => 300,
						'value' => ''
					)
				)
			),
			'urlrule' => array(
				'name' => lang('149'),
				'ismain' => 1,
				'fieldname' => 'urlrule',
				'fieldtype'	=> 'Text',
				'setting' => array(
					'option' => array(
						'width' => 300
					)
				)
			),
			'displayorder' => array(
				'name' => lang('order'),
				'ismain' => 1,
				'fieldname' => 'displayorder',
				'fieldtype'	=> 'Text',
				'setting' => array(
					'option' => array(
						'width' => 40,
						'value' => '0'
					)
				)
			),
			'show' => array(
				'name' => lang('html-357'),
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
			'getchild' => array(
				'name' => lang('order'),
				'ismain' => 1,
				'fieldtype'	=> 'Radio',
				'fieldname' => 'getchild',
				'setting' => array(
					'option' => array(
						'value' => '1',
						'options' => lang('yes').'|1'.PHP_EOL.lang('no').'|0',
					)
				)
			),
		);
		$this->load->model('page_model');
    }
	
	/**
	 * 单网页输出
	 */
	protected function _page() {
		
		$id = (int)$this->input->get('id');
		$dir = $this->input->get('dir');
		if (!$id && !$dir) $this->msg(lang('m-195'));
		
		$PAGE = $this->dcache->get('page-'.SITE_ID); // 单页缓存
		
		$id = !$id && $dir ? $PAGE['dir'][$dir] : $id;
		$page = APP_DIR ? $PAGE['data'][APP_DIR] : $PAGE['data']['index'];
		$data = $page[$id];
		if (!$data || !$data['show']) $this->msg(lang('m-196'));
		
		// 单页验证是否存在子栏目
		if ($data['child'] && $data['getchild']) {
			$temp = explode(',', $data['childids']);
			if (isset($temp[1]) && $page[$temp[1]]) {
				$id = $temp[1];
				$data = $page[$id];
			}
		}
		
		$data = $this->field_format_value($this->field, $data, max(1, (int)$this->input->get('page'))); // 格式化输出自定义字段
		$join = SITE_SEOJOIN ? SITE_SEOJOIN : '_';
		$title = $data['title'] ? $data['title'] : dr_get_page_pname($id, $join);
		if (isset($data['content_title']) && $data['content_title']) $title = $data['content_title'].$join.$title;

		// 栏目下级或者同级栏目
		$related = $parent = array();
		if ($data['pid']) {
			foreach ($page as $t) {
				if ($t['pid'] == $data['pid']) {
					$related[] = $t;
					if ($data['child']) {
						$parent = $data;
					} else {
						$parent = $page[$t['pid']];
					}
				}
			}
		} elseif ($data['child']) {
			$parent = $data;
			foreach ($page as $t) {
				if ($t['pid'] == $data['id']) {
					$related[] = $t;
				}
			}
		} else {
			$parent = $data;
			$related = $page;
		}
		
		$this->template->assign($data);
		$this->template->assign(array(
			'parent' => $parent,
			'related' => $related,
			'urlrule' => dr_page_url($data, '{page}'),
			'meta_title' => $title,
			'meta_keywords' => trim($data['keywords'].','.SITE_KEYWORDS, ','),
			'meta_description' => $data['description']
		));
        $this->template->display($data['template'] ? $data['template'] : 'page.html');
	}

	/*
	 * 删除
	 */
	protected function admin_delete($ids) {
	
		if (!$ids) return NULL;
		
		// 筛选栏目id
		$catid = '';
		foreach ($ids as $id) {
			$data = $this->page_model
						 ->link
						 ->select('childids')
						 ->where('id', $id)
						 ->limit(1)
						 ->get($this->page_model->tablename)
						 ->row_array();
			$catid.= ','.$data['childids'];
		}
		$catid = explode(',', $catid);
		$catid = array_flip(array_flip($catid));
		$this->load->model('attachment_model');
		
		// 逐一删除
		foreach ($catid as $id) {
			// 删除主表
			$this->page_model
				 ->link
				 ->where('id', $id)
				 ->delete($this->page_model->tablename);
			// 删除附件
			$this->attachment_model->delete_for_table($this->page_model->tablename.'-'.$id);
		}
		
		$this->page_model->cache(SITE_ID);
	}
	
    /**
     * 首页
     */
    protected function admin_index() {
		
		if (IS_POST) {
			
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			
			if ($this->input->post('action') == 'order') {
				$data = $this->input->post('data');
				foreach ($ids as $id) {
					$this->page_model->link->where('id', $id)->update($this->page_model->tablename, $data[$id]);
				}
				$this->page_model->cache(SITE_ID);
				exit(dr_json(1, lang('000')));
			} else {
				if (!$this->is_auth(APP_DIR.'/admin/page/index')) exit(dr_json(0, lang('160')));
				$this->admin_delete($ids);
				exit(dr_json(1, lang('000')));
			}
		}
		
		$this->page_model->repair();
		$this->load->library('dtree');
		$this->dtree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
		$this->dtree->nbsp = '&nbsp;&nbsp;&nbsp;';
		
		$tree = array();
		$data = $this->page_model->get_data();
		
		if ($data) {
			foreach($data as $t) {
				$t['option'] = '<a href="'.$t['url'].'" target="_blank">'.lang('go').'</a>&nbsp;&nbsp;&nbsp;';
				if ($this->is_auth(APP_DIR.'/admin/page/add')) {
					$t['option'] .= '<a href='.dr_url(APP_DIR.'/page/add', array('id' => $t['id'])).'>'.lang('252').'</a>&nbsp;&nbsp;&nbsp;';
				}
				if ($this->is_auth(APP_DIR.'/admin/page/edit')) {
					$t['option'] .= '<a href='.dr_url(APP_DIR.'/page/edit', array('id' => $t['id'])).'>'.lang('253').'</a>&nbsp;&nbsp;&nbsp;';
				}
				$tree[$t['id']] = $t;
			}
		}
		
		$str = "<tr class='\$class'>";
		$str.= "<td align='right'><input name='ids[]' type='checkbox' class='dr_select' value='\$id' />&nbsp;</td>";
		$str.= "<td align='left'><input class='input-text displayorder' type='text' name='data[\$id][displayorder]' value='\$displayorder' /></td>";
		$str.= "<td align='left'>\$id</td>";
		if ($this->is_auth(APP_DIR.'/admin/page/edit')) {
			$str.= "<td>\$spacer<a href='".dr_url(APP_DIR.'/page/edit')."&id=\$id'>\$name</a>  \$parent</td>";
		} else {
			$str.= "<td>\$spacer\$name  \$parent</td>";
		}
		$str.= "<td align='left'>\$dirname</td>";
		$str.= "<td align='left'>\$option</td>";
		$str.= "</tr>";
		$this->dtree->init($tree);
		
		$this->template->assign(array(
			'list' => $this->dtree->get_tree(0, $str),
            'page' => (int)$this->input->get('page')
		));
		$this->template->display('page_index.html');
    }
	
	/**
     * 添加
     */
    protected function admin_add() {
		
		$pid = (int)$this->input->get('id');
		$data = $error = $result = NULL;
		
		if (IS_POST) {
			$data = $this->validate_filter($this->field);
			$data[1]['pid'] = $this->input->post('pid');
			$data[1]['urlrule'] = $this->input->post('urlrule');
			$page = $this->page_model->add($data[1]);
			if (is_numeric($page)) {
				$this->page_model->cache(SITE_ID);
				$this->attachment_handle($this->uid, $this->page_model->tablename.'-'.$page, $this->field);
				if ($this->input->post('action') == 'back') {
					$this->admin_msg(lang('000'), dr_url(APP_DIR.'/page/index'), 1, 0);
				} else {
					$pid = $data[1]['pid'];
					unset($data);
					$result = lang('000');
				}
			} else {
				$data = $this->input->post('data');
				$error = $page;
			}
		}
		
		$this->template->assign(array(
			'page' => 0,
			'data' => $data,
			'error' => $error,
			'field' => $this->field,
			'select' => $this->_select($this->page_model->get_data(), $pid, 'name=\'pid\'', lang('150')),
			'result' => $result,
		));
		$this->template->display('page_add.html');
	}
	
	/**
     * 修改
     */
    protected function admin_edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->page_model->get($id);
		$error = $result = NULL;
		if (!$data)	$this->admin_msg(lang('019'));
		
		if (IS_POST) {
			$post = $this->validate_filter($this->field);
			$post[1]['pid'] = $this->input->post('pid');
			$post[1]['pid'] = $post[1]['pid'] == $id ? $data['pid'] : $post[1]['pid'];
			$post[1]['urlrule'] = $this->input->post('urlrule');
			$post[1]['displayorder'] = $data['displayorder'];
			$page = $this->page_model->edit($id, $post[1]);
			if (is_numeric($page)) {
				$this->page_model->syn($this->input->post('synid'), $post[1]['urlrule']);
				$this->attachment_handle($this->uid, $this->page_model->tablename.'-'.$page, $this->field);
				$this->page_model->cache(SITE_ID);
				$this->admin_msg(lang('000'), dr_url(APP_DIR.'/page/index'), 1, 0);
			} else {
				$error = $page;
			}
		}
		
		$page = $this->page_model->get_data();
		$this->template->assign(array(
			'id' => $id,
			'data' => $data,
			'page' => (int)$this->input->post('page'),
			'field' => $this->field,
			'select' => $this->_select($page, $data['pid'], 'name=\'pid\'', lang('150')),
			'result' => $result,
			'select_syn' => $this->_select($page, 0, 'name=\'synid[]\' multiple style="height:200px;"', '')
		));
		$this->template->display('page_add.html');
	}
	
	
	/**
     * 缓存
     */
    protected function admin_cache() {
		$site = $this->input->get('site') ? $this->input->get('site') : SITE_ID;
		$admin = $this->input->get('admin') ? $this->input->get('admin') : $this->input->get('admin');
		$this->page_model->cache($site);
		$admin or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
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