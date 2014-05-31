<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.1.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Home extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 首页
     */
    public function index() {
		$uid = (int)$this->input->get('uid');
		if ($uid) {
			$this->_space($uid); // 带会员uid参数时进入会员空间界面
		} else {
			$url = MEMBER_URL.SELF.'?c=login&m=index&backurl='.urlencode(dr_now_url());
			if (!$this->uid) $this->member_msg(lang('m-039').$this->member_model->logout(), $url);
			$this->template->assign(array(
				'loginlog' => array_reverse(dr_string2array($this->member['loginlog'])),
				'meta_name' => lang('m-012'),
			));
			$this->template->display(IS_AJAX ? 'main.html' : 'index.html');
		}
    }
	
    /**
     * 会员空间页
     */
	private function _space($uid) {
		
		if (!MEMBER_OPEN_SPACE) $this->member_msg(lang('m-111'));
		
		$this->load->model('space_model');
		$this->load->model('space_category_model');
		$space = $this->space_model->get($uid);
		if (!$space) $this->member_msg(lang('m-234'));
		if (!$space['status']) $this->member_msg(lang('m-235'));
		
		$style = $space['style'] ? $space['style'] : 'default';
		$theme = MEMBER_URL.'templates/'.$style.'/';
		$action = $this->input->get('action');
		$member = $this->member_model->get_member($uid);
		$selected = 0; // 默认选中首页菜单
		$category = $this->space_category_model->get_data(0, $uid, 1);
		
		switch ($action) {
		
			case 'category': // 栏目处理
			
				$id = (int)$this->input->get('id');
				$cat = $category[$id];
				if (!$cat) $this->msg(lang('m-315'));
				
				switch ($cat['type']) {
				
					case 0: // 外链
						if (!$cat['link']) $this->msg(lang('m-316'));
						redirect($cat['link'], 'location', 301);
						return NULL;
						break;
					
					case 1: // 模型
						$model = $this->get_cache('space-model', $cat['modelid']);
						if (!$model) $this->msg(lang('m-317'));
						$template = 'list_'.$model['table'].'.html';
						// 选中顶级栏目
						$temp = explode(',', $cat['pids']);
						$selected = $temp[1] ? $temp[1] : $id;
						break;
					
					case 2: // 单页
						$template = 'page.html';
						// 选中顶级栏目
						$temp = explode(',', $cat['pids']);
						$selected = $temp[1] ? $temp[1] : $id;
						// 单页验证是否存在子栏目
						if ($cat['child']) {
							$temp = explode(',', $cat['childids']);
							if (isset($temp[1]) && $category[$temp[1]]) {
								$id = $temp[1];
								$cat = $category[$id];
							}
						}
						break;
				}
				// 栏目下级或者同级栏目
				$related = $parent = array();
				if ($cat['pid']) {
					foreach ($category as $t) {
						if ($t['pid'] == $cat['pid']) {
							$related[] = $t;
							if ($cat['child']) {
								$parent = $cat;
							} else {
								$parent = $category[$t['pid']];
							}
						}
					}
				} elseif ($cat['child']) {
					$parent = $cat;
					foreach ($category as $t) {
						if ($t['pid'] == $cat['id']) {
							$related[] = $t;
						}
					}
				}
				
				$this->template->assign(array(
					'cat' => $cat,
					'catid' => $id,
					'parent' => $parent,
					'related' => $related,
					'modelid' => $cat['modelid'],
					'urlrule' => dr_space_list_url($uid, $id, TRUE),
				));
				
				if ($cat['title']) {
					$title = $cat['title'];
				} else {
					$title = implode('-', array_reverse(explode('{-}', dr_space_catpos($uid, $id, '{-}', FALSE)))).'-'.$space['name'];
				}
				
				$this->template->assign(array(
					'meta_title' => $title,
					'meta_keywords' => $cat['keywords'],
					'meta_description' => $cat['description'],
				));
				break;
			
			case 'show': // 内容处理
			
				$id = (int)$this->input->get('id');
				$mid = (int)$this->input->get('mid');
				$mod = $this->get_cache('space-model', $mid);
				if (!$mod) $this->msg(lang('m-317'));
				
				$name = $this->db->dbprefix('space_'.$mod['table']).'-space-show-'.$id;
				$data = $this->get_cache_data($name);
				
				if (!$data) {
					$this->load->model('space_content_model');
					$this->space_content_model->tablename = $this->db->dbprefix('space_'.$mod['table']);
					$data = $this->space_content_model->get($uid, $id);
					if (!$data) $this->msg(lang('m-303'));
					if (!$data['status'] && $data['uid'] != $this->uid) $this->msg(lang('m-318'));
					
					$cat = $category[$data['catid']];
					if (!$cat) $this->msg(lang('m-315'));
					
					// 检测转向字段
					foreach ($mod['field'] as $t) {
						if ($t['fieldtype'] == 'Redirect' && $data[$t['fieldname']]) {
							redirect($data[$t['fieldname']], 'location', 301);
							exit;
						}
					}
					
					// 上一篇文章
					$data['prev_page'] = $this->db
											  ->where('catid', $data['catid'])
											  ->where('id<', $id)
											  ->where('status', 1)
											  ->select('title,id,updatetime')
											  ->order_by('id desc')
											  ->limit(1)
											  ->get($this->space_content_model->tablename)
											  ->row_array();
					// 下一篇文章
					$data['next_page'] = $this->db 
											  ->where('catid', $data['catid'])
											  ->where('id>', $id)
											  ->where('status', 1)
											  ->select('title,id,updatetime')
											  ->order_by('id asc')
											  ->limit(1)
											  ->get($this->space_content_model->tablename)
											  ->row_array();
					
					$this->set_cache_data($name, $data, 360000);
				} else {
					$cat = $category[$data['catid']];
					if (!$cat) $this->msg(lang('m-315'));
				}
				
				// 格式化输出自定义字段
				$fields = $mod['field'];
				$fields['inputtime'] = array('fieldtype' => 'Date');
				$fields['updatetime'] = array('fieldtype' => 'Date');
				$data = $this->field_format_value($fields, $data, max(1, (int)$this->input->get('page')));
					
				// 栏目下级或者同级栏目
				$related = $parent = array();
				if ($cat['pid']) {
					foreach ($category as $t) {
						if ($t['pid'] == $cat['pid']) {
							$related[] = $t;
							if ($cat['child']) {
								$parent = $cat;
							} else {
								$parent = $category[$t['pid']];
							}
						}
					}
				} elseif ($cat['child']) {
					$parent = $cat;
					foreach ($category as $t) {
						if ($t['pid'] == $cat['id']) {
							$related[] = $t;
						}
					}
				}
				$template = 'show_'.$mod['table'].'.html';
				// 选中顶级栏目
				$temp = explode(',', $cat['pids']);
				$selected = $temp[1] ? $temp[1] : $cat['id'];
				
				
				$this->template->assign($data);
				$this->template->assign(array(
					'cat' => $cat,
					'catid' => $cat['id'],
					'parent' => $parent,
					'related' => $related,
					'modelid' => $cat['modelid'],
				));
				
				$temp = dr_space_catpos($uid, $cat['id'], '{-}', FALSE);
				$temp = explode('{-}', $temp);
				$catstr = implode('-', array_reverse($temp));
				$this->template->assign(array(
					'meta_title' => ($data['content_title'] ? $data['content_title'].'-' : '').$data['title'].'-'.$catstr.'-'.$space['name'],
					'meta_keywords' => $data['keywords'],
					'meta_description' => dr_strcut(dr_clearhtml($data['content']), 200, ''),
				));
				break;
			
			default: // 首页或者其他自定义页面
				
				$template = $action ? $action.'.html' : 'index.html';
				$this->template->assign(array(
					'meta_title' => $space['title'] ? $space['title'] : $space['name'],
					'meta_keywords' => $space['keywords'],
					'meta_description' => $space['description'],
				));
				break;
		}
		
		// 更新访问量pv
		$this->db
			 ->where('uid',(int)$uid)
			 ->update('space', array('hits' => $space['hits'] + 1));
		
		$this->template->assign(array(
			'space' => $space + $member,
			'theme' => $theme,
			'spaceid' => $uid,
			'tableid' => (int)substr((string)$uid, -1, 1),
			'selected' => $selected,
			'category' => $category,
		));
		
		$this->template->space($style);
		$this->template->display($template);
	}
}