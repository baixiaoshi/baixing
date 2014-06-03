<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class D_Tag extends M_Controller {
	
	public $module;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->module = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR);
		if (!$this->module) $this->admin_msg(lang('m-148'));
		$this->load->model('tag_model');
    }
	
	/**
     * tag
     */
	protected function _tag() {
		
		$code = $this->input->get('name', TRUE);
		$this->load->model('tag_model');
		$data = $this->tag_model->tag($code);
		if (!$data) $this->msg(dr_lang('mod-33', $code));
		
		$urlrule = $this->module['setting']['tag']['url_page'] ? $this->module['setting']['tag']['url_page'] : 'index.php?c=tag&name={tag}&page={page}';
		$urlrule = $this->module['url'].str_replace('{tag}', $code, $urlrule);
		
		$sql = 'SELECT * FROM '.$this->content_model->prefix.' WHERE ';
		$tag = $where = array();
		foreach ($data as $t) {
			$tag[] = $t['name'];
			$where[] = '`title` LIKE "%'.$t['name'].'%" OR `keywords` LIKE "%'.$t['name'].'%"';
		}
		$tag = implode(',', $tag);
		$sql.= implode(' OR ', $where).' ORDER BY `updatetime` DESC';
		
		$this->template->assign(array(
			'tag' => $tag,
			'list' => $data,
			'tagsql' => $sql,
			'urlrule' => $urlrule,
			'pagesize' => $this->module['setting']['tag']['pagesize'] ? $this->module['setting']['tag']['pagesize'] : 20,
			'meta_title' => 'Tag:'.$tag.(SITE_SEOJOIN ? SITE_SEOJOIN : '_').$this->module['name'],
			'meta_keywords' => $this->module['setting']['seo']['meta_keywords'],
			'meta_description' => $this->module['setting']['seo']['meta_description']
		));
		$this->template->display('tag.html');
	}
	
	/**
     * 后台菜单
     */
	private function _menu() {
		$this->template->assign('menu', $this->get_menu(array(
			lang('125') => APP_DIR.'/admin/tag/index',
			lang('add') => APP_DIR.'/admin/tag/add_js',
		)));
	}

    /**
     * 管理
     */
    protected function admin_index() {
		
		if (IS_POST) {
			if (!$this->is_auth(APP_DIR.'/admin/tag/del')) exit(dr_json(0, lang('160')));
			$id = $this->input->post('ids');
			if ($id) $this->link->where_in('id', $id)->delete($this->tag_model->tablename);
			exit(dr_json(1, lang('000')));
		}
		
		// 数据库中分页查询
		$kw = $this->input->get('kw') ? $this->input->get('kw') : '';
		list($data, $param)	= $this->tag_model->limit_page($kw, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		
		$this->_menu();
		$this->template->assign(array(
			'mod' => $this->module,
			'list' => $data,
			'param'	=> $param,
			'pages'	=> $this->get_pagination(dr_url(APP_DIR.'/tag/index', $param), $param['total'])
		));
		$this->template->display('tag_index.html');
    }
	
	/**
     * 添加
     */
    protected function admin_add() {
	
		if (IS_POST) {
			$data = $this->input->post('data', TRUE);
			$result	= $this->tag_model->add($data);
			switch ($result) {
				
				case -1:
					exit(dr_json(0, lang('126'), 'name'));
					break;
					
				case -2:
					exit(dr_json(0, lang('127'), 'name'));
					break;
				
				default:
					exit(dr_json(1, lang('000')));
					break;
			}
		}
		
		$this->template->assign(array(
			'data' => array()
		));
		$this->template->display('tag_add.html');
	}
	
	/**
     * 修改
     */
    protected function admin_edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->tag_model->get($id);
		if (!$data) exit(lang('019'));
		
		if (IS_POST) {
			
			$data = $this->input->post('data', TRUE);
			$result	= $this->tag_model->edit($id, $data);
			switch ($result) {
				
				case -1:
					exit(dr_json(0, lang('126')));
					break;
					
				case -2:
					exit(dr_json(0, lang('127')));
					break;
				
				default:
					exit(dr_json(1, lang('000')));
					break;
			}
		}
		
		$this->template->assign(array(
			'id' => $id,
			'data' => $data
		));
		$this->template->display('tag_add.html');
	}
	
	/**
     * 删除
     */
    protected function admin_del() {
		$this->link
			 ->where('id', (int)$this->input->get('id'))
			 ->delete($this->tag_model->tablename);
		exit(dr_json(1, lang('000')));
	}
	
}