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

class D_Admin_Home extends M_Controller {

	public $where; // 管理角色条件筛选
	protected $field; // 自定义字段+含系统字段
	protected $verify; // 审核流程
	protected $sysfield; // 系统字段
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->load->library('Dfield', array(APP_DIR));
		$this->sysfield = array(
			'hits' => array(
				'name' => lang('244'),
				'ismain' => 1,
				'fieldname' => 'hits',
				'fieldtype' => 'Text',
				'setting' => array(
					'option' => array(
						'value'	=> 0,
						'width' => 157,
					)
				)
			),
			'author' => array(
				'name' => lang('101'),
				'ismain' => 1,
				'fieldtype' => 'Text',
				'fieldname' => 'author',
				'setting' => array(
					'option' => array(
						'width' => 157,
						'value'	=> $this->admin['username']
					),
					'validate' => array(
						'tips' => lang('102'),
						'check' => '_check_member',
						'required' => 1,
						'formattr' => ' /><input type="button" class="button" value="'.lang('103').'" onclick="dr_dialog_member(\'author\')" name="user"',
					)
				)
			),
			'inputtime' => array(
				'name' => lang('104'),
				'ismain' => 1,
				'fieldtype' => 'Date',
				'fieldname' => 'inputtime',
				'setting' => array(
					'option' => array(
						'width' => 140
					),
					'validate' => array(
						'required' => 1,
						'formattr' => '',
					)
				)
			),
			'updatetime' => array(
				'name' => lang('105'),
				'ismain' => 1,
				'fieldtype' => 'Date',
				'fieldname' => 'updatetime',
				'setting' => array(
					'option' => array(
						'width' => 140
					),
					'validate' => array(
						'required' => 1,
						'formattr' => '',
					)
				)
			),
			'inputip' => array(
				'name' => lang('106'),
				'ismain' => 1,
				'fieldtype' => 'Text',
				'fieldname' => 'inputip',
				'setting' => array(
					'option' => array(
						'width' => 157,
						'value' => $this->input->ip_address()
					),
					'validate' => array(
						'formattr' => ' /><input type="button" class="button" value="'.lang('107').'" onclick="dr_dialog_ip(\'inputip\')" name="ip"',
					)
				)
			)
		);
		$this->where = NULL;
		$field = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'field');
		
		$this->field = $field ? array_merge($field, $this->sysfield) : $this->sysfield;
		if ($this->admin['adminid'] > 1) $this->verify = $this->_get_verify();
	}

    /**
     * 管理
     */
    public function index() {
		if (IS_POST && !$this->input->post('search')) {
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			switch($this->input->post('action')) {
				case 'del':
					foreach ($ids as $id) {
						$data = $this->link
									 ->where('id', (int)$id)
									 ->select('id,uid,catid,tableid')
									 ->limit(1)
									 ->get($this->content_model->prefix)
									 ->row_array();
						if ($data && $this->is_category_auth($data['catid'], 'del')) {
							$this->content_model->delete_for_id((int)$data['id'], (int)$data['uid'], (int)$data['catid'], (int)$data['tableid']);
						}
					}
					exit(dr_json(1, lang('000')));
					break;
				case 'order':
					$_data = $this->input->post('data');
					foreach ($ids as $id) {
						$this->link
							 ->where('id', $id)
							 ->update($this->content_model->prefix, $_data[$id]);
					}
					exit(dr_json(1, lang('000')));
					break;
				case 'move':
					$catid = $this->input->post('catid');
					if (!$catid) exit(dr_json(0, lang('cat-20')));
					if (!$this->is_auth(APP_DIR.'/admin/home/edit') || !$this->is_category_auth($catid, 'edit')) exit(dr_json(0, lang('160')));
					$this->content_model->move($ids, $catid);
					exit(dr_json(1, lang('000')));
					break;
				case 'flag':
					if (!$this->is_auth(APP_DIR.'/admin/home/edit')) exit(dr_json(0, lang('160')));
					$flag = $this->input->post('flagid');
					$this->content_model->flag($ids, $flag);
					exit(dr_json(1, lang('000')));
					break;
				default :
					exit(dr_json(0, lang('000')));
					break;
			}
		}
		// 筛选结果
		$param = array();
		if ($this->input->get('flag')) $param['flag'] = (int)$this->input->get('flag');
		if ($this->input->get('catid')) $catid = $param['catid'] = (int)$this->input->get('catid');
		if ($this->input->get('search')) $param['search'] = 1;
		// 数据库中分页查询
		list($list, $param)	= $this->content_model->limit_page($param, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		// 统计推荐位数量
		$_menu[lang('mod-01')] = $catid ? APP_DIR.'/admin/home/index/catid/'.$catid : APP_DIR.'/admin/home/index';
		$flag = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'setting', 'flag');
		if ($flag) {
			foreach ($flag as $id => $t) {
				if ($t['name']) {
					$_menu["{$t['name']}(".$this->content_model->flag_total($id, $catid).")"] = $catid ? 
					APP_DIR.'/admin/home/index/flag/'.$id.'/catid/'.$catid : 
					APP_DIR.'/admin/home/index/flag/'.$id;
				}
			}
		}
		// 模块应用嵌入
		$app = array();
		$data = $this->get_cache('app');
		if ($data) {
			foreach ($data as $dir) {
				$a = $this->get_cache('app-'.$dir);
				if (isset($a['module'][APP_DIR]) && isset($a['related']) && $a['related']) {
					$app[] = array(
						'url' => dr_url($dir.'/content/index'),
						'name' => $a['name'],
						'field' => $a['related'],
					);
				}
			}
		}
		// 模块表单嵌入
		$form = array();
		$data = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'form');
		if ($data) {
			foreach ($data as $t) {
				$form[] = array(
					'url' => dr_url(APP_DIR.'/form_'.SITE_ID.'_'.$t['id'].'/index'),
					'name' => $t['name'],
				);
			}
		}
		// 搜索参数
		if ($this->input->get('search')) {
			$_param = $this->cache->file->get($this->content_model->cache_file);
		} else {
			$_param = $this->input->post('data');
		}
		$_param = $_param ? $param + $_param : $param;
		
		$_menu["<font color=red><b>".lang('mod-02')."</b></font>"] = $this->input->get('catid') ? APP_DIR.'/admin/home/add/catid/'.$this->input->get('catid') : APP_DIR.'/admin/home/add';
		$this->template->assign(array(
			'app' => $app,
			'form' => $form,
			'list' => $list,
            'menu' => $this->get_menu($_menu),
			'flag' => isset($param['flag']) ? $param['flag'] : '',
			'field' => $this->field,
			'flags' => $flag,
			'param'	=> $_param,
			'pages'	=> $this->get_pagination(dr_url(APP_DIR.'/home/index', $param), $param['total']),
			'extend' => $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'extend'),
			'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), $catid, 'name=\'catid\'', ' --- ', 1, 1)
		));
		$this->template->display('content_index.html');
    }
    
    /**
     * 审核
     */
    public function verify() {
		
		if ($this->admin['adminid'] > 1 && !$this->verify) $this->admin_msg(lang('337'));
		
		if (IS_POST && $this->input->post('action') != 'search') {
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('013')));
			if ($this->admin['adminid'] > 1) {
				// 非管理员角色只能操作自己审核的
				$status = array();
				foreach ($this->verify as $t) {
					$status+=$t['status'];
				}
				$where = '`status` IN ('.implode(',', $status).')';
			} else {
				$where = '';
			}
			switch($this->input->post('action')) {
				case 'del': // 删除
					$this->load->model('attachment_model');
					foreach ($ids as $id) {
						$_where = $where ? $where.' AND `id`='.(int)$id : '`id`='.(int)$id;
						$data = $this->link // 主表状态
									 ->where($_where)
									 ->select('uid,catid')
									 ->limit(1)
									 ->get($this->content_model->prefix.'_index')
									 ->row_array();
						if ($data) {
							// 删除数据
							$index = $this->content_model->del_verify($id);
							// 删除表对应的附件
							$this->attachment_model->delete_for_table($this->content_model->prefix.'_verify-'.$id);
						}
					}
					exit(dr_json(1, lang('000')));
					break;
				case 'flag': // 标记
					$flag = $this->input->post('flagid');
					if (!$flag) exit(dr_json(0, lang('013')));
					foreach ($ids as $id) {
						$_where = $where ? $where.' AND `id`='.(int)$id : '`id`='.(int)$id;
						if ($flag > 0) {
							// 通过审核
							$data = $this->link // 更改主表状态
										 ->where($_where)
										 ->select('uid,catid,status')
										 ->limit(1)
										 ->get($this->content_model->prefix.'_index')
										 ->row_array();
							if ($data['status'] < 9) {
								$status = $this->_get_verify_status($data['uid'], $data['catid'], $data['status']);
								if ($status == 9) {
									// 审核通过
									$verify = $this->content_model->get_verify($id);
									// 筛选字段
									$data = array();
									foreach ($this->field as $field) {
										$data[$field['ismain']][$field['fieldname']] = $verify[$field['fieldname']];
									}
									$data[1]['id'] = $data[0]['id'] = $id;
									$data[1]['uid'] = $verify['uid'];
									$data[1]['author'] = $verify['author'];
									$data[1]['catid'] = $verify['catid'];
									$data[1]['inputip'] = $verify['inputip'];
									$data[1]['status'] = $status;
									$data[1]['url'] = dr_show_url($this->get_cache('module-'.SITE_ID.'-'.APP_DIR), $data[1]);
									// 主表更新
									if ($this->link->where('id', (int)$id)->count_all_results($this->content_model->prefix)) {
										$this->link->where('id', (int)$id)->update($this->content_model->prefix, $data[1]);
									} else {
										$data[1]['hits'] = 0;
										$this->link->replace($this->content_model->prefix, $data[1]);
									}
									$this->link // 副表
										 ->replace($this->content_model->prefix.'_data_'.floor($id/50000), $data[0]);
									$this->link // 审核表
										 ->where('id', $id)
										 ->delete($this->content_model->prefix.'_verify');
									// 更新tag表
									if (isset($data[1]['keywords']) && $data[1]['keywords']) $this->content_model->update_tag($data[1]['keywords']);
									$this->attachment_replace($verify['uid'], $id, $this->content_model->prefix);
									$member = $this->member_model->get_base_member($uid);
									$category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $verify['catid']);
									$rule = $category['permission'][$member['markrule']];
									$mark = $this->content_model->prefix.'-'.$id;
									// 积分处理
									if ($rule['experience']) {
										$this->member_model->update_score(0, $verify['uid'], $rule['experience'], $mark, "lang,m-151,{$category['name']}", 1);
									}
									// 虚拟币处理
									if ($rule['score']) {
										$this->member_model->update_score(1, $verify['uid'], $rule['score'], $mark, "lang,m-151,{$category['name']}", 1);
									}
									// 审核通过处理信息
									$this->content_model->verify_notice($id, $data);
								} else {
									// 下级审核
									$this->link // 更改主表状态
										 ->where($_where)
										 ->update($this->content_model->prefix, array('status' => $status));
									$this->link // 更改索引表状态
										 ->where($_where)
										 ->update($this->content_model->prefix.'_index', array('status' => $status));
									$this->link // 更改审核表状态
										 ->where($_where)
										 ->update($this->content_model->prefix.'_verify', array('status' => $status));
								}
							}
						} else {
							// 拒绝审核
							$this->link // 更改主表状态
								 ->where($_where)
								 ->update($this->content_model->prefix, array('status' => 0));
							$this->link // 更改索引表状态
								 ->where($_where)
								 ->update($this->content_model->prefix.'_index', array('status' => 0));
							$this->link // 更改审核表状态
								 ->where($_where)
								 ->update($this->content_model->prefix.'_verify', array(
									'status' => 0,
									'backuid' => (int)$this->uid,
									'backinfo' => dr_array2string(array(
										'uid' => $this->uid,
										'author' => $this->admin['username'],
										'rolename' => $this->admin['role']['name'],
										'optiontime' => SYS_TIME,
										'backcontent' => $this->input->post('backcontent')
									))
								 ));
						}
					}
					exit(dr_json(1, lang('000')));
					break;
				default:
					exit(dr_json(0, lang('047')));
					break;
			}
		}
		$param = array();
        $param['status'] = (int)$this->input->get('status');
        if ($this->admin['adminid'] == 1) {
			// 管理员角色列出所有审核流程
			$where = '`status`='.$param['status'];
			for ($i = 0; $i < 9; $i++) {
				$total = (int)$this->db->where('status', $i)->count_all_results($this->content_model->prefix.'_verify');
    		    $_menu[lang('05'.$i).' ('.$total.')'] = APP_DIR.'/admin/home/verify/status/'.$i;
    		}
        } else {
			// 非管理员角色列出自己审核的
			$status = array();
			foreach ($this->verify as $t) {
				$status+=$t['status'];
    		}
			if ($param['status']) {
				$where = '`status` IN ('.implode(',', $status).')';
			} else {
				$where = '`status`=0 AND `backuid`='.$this->uid;
			}
        }
		// 栏目筛选
		if ($this->input->get('catid')) {
			$param['catid'] = (int)$this->input->get('catid');
			$where .= ' AND `catid` = '.$param['catid'];
		}
		// 获取总数量
		$param['total'] = $total = $this->input->get('total') ? $this->input->get('total') : $this->link->where($where)->count_all_results($this->content_model->prefix.'_verify');
		$page = max(1, (int)$this->input->get('page'));
		$data = $this->link
                     ->select('id,catid,author,content,inputtime,status')
					 ->where($where)
					 ->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1))
					 ->order_by('inputtime DESC, id DESC')
					 ->get($this->content_model->prefix.'_verify')
					 ->result_array();
					 
		if ($this->admin['adminid'] > 1) {
			// 被退回
			$_total = $this->link
						   ->where('`status`=0 AND `backuid`='.$this->uid)
						   ->count_all_results($this->content_model->prefix.'_verify');
			$_menu[lang('050').' ('.$_total.')'] = APP_DIR.'/admin/home/verify';
			// 我的审核
			$_total = $this->link
						   ->where_in('status', $status)
						   ->count_all_results($this->content_model->prefix.'_verify');
			$_menu[lang('120').' ('.$_total.')'] = APP_DIR.'/admin/home/verify/status/1';
		}
		
		$this->template->assign(array(
			'list' => $data,
            'menu' => $this->get_menu($_menu),
			'param'	=> $param,
			'pages'	=> $this->get_pagination(dr_url(APP_DIR.'/home/verify', $param), $param['total'])
		));
		$this->template->display('content_verify.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		$error = $data = array();
		$catid = (int)$this->input->get('catid');
		$result = '';
		if (!$this->is_category_auth($catid, 'add')) $this->admin_msg(lang('160'));
		
		if (IS_POST) {
			$catid = (int)$this->input->post('catid');
			if (!$this->is_category_auth($catid, 'add')) $this->admin_msg(lang('160'));
			$cate = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid, 'field');
			$field = $cate ? array_merge($this->field, $cate) : $this->field;
			
			// 设置uid便于校验处理
			$uid = $this->input->post('data[author]') ? get_member_id($this->input->post('data[author]')) : 0;
			$_POST['data']['id'] = $id;
			$_POST['data']['uid'] = $uid;
			$data = $this->validate_filter($field);
			$backurl = $this->input->post('backurl');
			
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} elseif (!$catid) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'catid', 'msg' => lang('cat-22'));
			} else {
				$data[1]['uid'] = $uid;
				$data[1]['catid'] = $catid;
				$data[1]['status'] = 9;
				if (($id = $this->content_model->add($data)) != FALSE) {
					$mark = $this->content_model->prefix.'-'.$id;
					$member = $this->member_model->get_base_member($uid);
					$category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid);
					$rule = $category['permission'][$member['markrule']];
					// 积分处理
					if ($rule['experience']) {
						$this->member_model->update_score(0, $uid, $rule['experience'], $mark, "lang,m-151,{$category['name']}", 1);
					}
					// 虚拟币处理
					if ($rule['score']) {
						$this->member_model->update_score(1, $uid, $rule['score'], $mark, "lang,m-151,{$category['name']}", 1);
					}
					// 操作成功处理附件
					$this->attachment_handle($data[1]['uid'], $mark, $field);
					// 创建静态页面链接
					$create = MODULE_HTML ? dr_module_create_show_file($id, 1) : '';
					if ($this->input->post('action') == 'back') {
						$this->admin_msg(lang('000').($create ? "<script src='".$create."'></script>" : ''), $backurl, 1, 0);
					} else {
						unset($data);
						$result = lang('000');
					}
				}
			}
		}
		$this->template->assign(array(
			'page' => max((int)$this->input->post('page'), 0),
			'data' => $data,
            'menu' => $this->get_menu(array(
                lang('back') => $backurl ? $backurl : ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : APP_DIR.'/admin/home/index/catid/'.$catid),
                lang('mod-02') => APP_DIR.'/admin/home/add'
            )),
			'catid' => $catid,
			'error' => $error,
			'result' => $result,
			'create' => $create,
			'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), $catid, 'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"', '', 1, 1),
			'backurl' => $backurl ? $backurl : $_SERVER['HTTP_REFERER'],
			'myfield' => $this->field_input($this->field, $data, TRUE),
		));

		$this->template->display('content_add.html');
	}
	
	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->content_model->get($id);
		$catid = $data['catid'];
		$error = array();
		$result = '';
		
		if (!$data) $this->admin_msg(lang('019'));
		if (!$this->is_category_auth($catid, 'edit')) $this->admin_msg(lang('160'));
		
		if (IS_POST) {
			$_data = $data;
			$catid = (int)$this->input->post('catid');
			if (!$this->is_category_auth($catid, 'edit')) $this->admin_msg(lang('160'));
			$cate = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid, 'field');
			$field = $cate ? array_merge($this->field, $cate) : $this->field;
			// 设置uid便于校验处理
			$uid = $this->input->post('data[author]') ? get_member_id($this->input->post('data[author]')) : 0;
			$_POST['data']['id'] = $id;
			$_POST['data']['uid'] = $uid;
			$data = $this->validate_filter($field, $_data);
			$backurl = $this->input->post('backurl');
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} elseif (!$catid) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'catid', 'msg' => lang('cat-22'));
			} else {
				$data[1]['uid'] = $uid;
				$data[1]['catid'] = $catid;
				$data[1]['status'] = 9;
				$this->content_model->edit($_data, $data);
				// 操作成功处理附件
				$this->attachment_handle($data[1]['uid'], $this->content_model->prefix.'-'.$id, $field, $_data);
				$this->admin_msg(lang('000').(MODULE_HTML ? dr_module_create_show_file($id) : ''), $backurl, 1, 0);
			}
		}
		
		$data['updatetime'] = SYS_TIME;
		$this->template->assign(array(
			'page' => max((int)$this->input->post('page'), 0),
			'data' => $data,
            'menu' => $this->get_menu(array(
                lang('back') => $backurl ? $backurl : ($_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : APP_DIR.'/admin/home/index/catid/'.$catid),
                lang('mod-02') => APP_DIR.'/admin/home/add/catid/'.$catid
            )),
			'catid' => $catid,
			'error' => $error,
			'result' => $result,
			'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), $data['catid'], 'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"', '', 1, 1),
			'backurl' => $backurl ? $backurl : $_SERVER['HTTP_REFERER'],
			'myfield' => $this->field_input($this->field, $data, TRUE)
		));
		$this->template->display('content_add.html');
    }
    
    /**
     * 修改审核文档
     */
    public function verifyedit() {
		$id = (int)$this->input->get('id');
		$data = $this->content_model->get_verify($id);
		$catid = $data['catid'];
		$error = array();
		if (!$data) $this->admin_msg(lang('019'));
		if (IS_POST) {
			$_data = $data;
			$cate = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid, 'field');
			$field = $cate ? array_merge($this->field, $cate) : $this->field;
			// 设置uid便于校验处理
			$uid = $this->input->post('data[author]') ? get_member_id($this->input->post('data[author]')) : 0;
			$_POST['data']['id'] = $id;
			$_POST['data']['uid'] = $uid;
			$data = $this->validate_filter($field, $_data);
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} elseif (!$catid) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'catid', 'msg' => lang('cat-22'));
			} elseif (!$this->input->post('flagid')) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'flagid', 'msg' => lang('161'));
			} else {
				$data[1]['uid'] = $uid;
				$data[1]['catid'] = $catid;
                if ($this->input->post('flagid') == 1) {
                    $data[1]['status'] = $this->_get_verify_status($_data['uid'], $catid, $_data['status']);
                } else {
                    $data[1]['status'] = 0;
                    $data[1]['back'] = $this->input->post('back');
                }
				$this->content_model->edit($_data, $data);
				// 操作成功处理附件，当审核成功状态时才对旧附件进行删除操作
				$mark = $this->content_model->prefix.'-'.$id;
				$this->attachment_handle($data[1]['uid'], $mark, $field, $_data, $data[1]['status'] == 9 ? TRUE : FALSE);
				if ($data[1]['status'] == 9) {
					$member = $this->member_model->get_base_member($uid);
					$category = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid);
					$rule = $category['permission'][$member['markrule']];
					// 积分处理
					if ($rule['experience']) {
						$this->member_model->update_score(0, $uid, $rule['experience'], $mark, "lang,m-151,{$category['name']}", 1);
					}
					// 虚拟币处理
					if ($rule['score']) {
						$this->member_model->update_score(1, $uid, $rule['score'], $mark, "lang,m-151,{$category['name']}", 1);
					}
					// 审核通过处理信息
					$this->content_model->verify_notice($id, $data);
					$this->attachment_replace($data[1]['uid'], $id, $this->content_model->prefix);
					$this->admin_msg(lang('000').(MODULE_HTML ? dr_module_create_show_file($id) : ''), $this->input->post('backurl'), 1);
				}
				$this->admin_msg(lang('000'), $this->input->post('backurl'), 1);
			}
		}
        if ($data['status'] == 0) { // 退回
            $backuri = APP_DIR.'/admin/home/verify/status/0';
        } elseif ($data['status'] > 0 && $data['status'] < 9) {
            $backuri = APP_DIR.'/admin/home/verify/status/'.$data['status'];
        } else {
            $backuri = APP_DIR.'/admin/home/verify/';
        }
		$this->template->assign(array(
			'page' => max((int)$this->input->post('page'), 0),
			'data' => $data,
            'menu' => $this->get_menu(array(
                lang('back') => $backuri,
                lang('edit') => APP_DIR.'/admin/home/verifyedit/id/'.$data['id']
            )),
			'catid' => $catid,
			'error' => $error,
			'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'),$data['catid'],'id=\'dr_catid\' name=\'catid\' onChange="show_category_field(this.value)"','',1),
			'backurl' => $_SERVER['HTTP_REFERER'],
			'myfield' => $this->field_input($this->field, $data, TRUE),
            
		));
		$this->template->display('content_edit.html');
    }
	
	/**
     * 更新URL
     */
	public function url() {
		$cfile = SITE_ID.APP_DIR.$this->uid.$this->input->ip_address().'_content_url';
		if (IS_POST) {
			$catid = $this->input->post('catid');
			$query = $this->link;
			if ($catid) $query->where_in('catid', $catid);
			$data = $query->select('id')
						  ->get($this->content_model->prefix.'_index')
						  ->result_array();
			if ($data) {
				$id = array();
				foreach ($data as $t) {
					$id[] = $t['id'];
				}
				$this->cache->file->save($cfile, $id, 7200); // 缓存搜索结果->id
				$this->mini_msg(dr_lang('132', count($id)), dr_url(APP_DIR.'/home/url', array('todo' => 1)), 2);
			} else {
				$this->mini_msg(lang('133'));
			}
		}
		if ($this->input->get('todo')) {
			$id = $this->cache->file->get($cfile); // 取缓存搜索结果->id
			if (!$id) $this->mini_msg(lang('134'));
			$page = max(1, (int)$this->input->get('page'));
			$psize = 50;
			$total = count($id);
			$tpage = ceil($total / $psize); // 总页数
			if ($page > $tpage) { // 更新完成删除缓存
				$this->cache->file->delete($cfile);
				$this->mini_msg(lang('000'), NULL, 1); 
			}
			$module = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR);
			$table = $this->content_model->prefix;
			$data = $this->link
						 ->where_in('id', $id)
						 ->limit($psize, $psize * ($page - 1))
						 ->order_by('id DESC')
						 ->get($table)
						 ->result_array();
			foreach ($data as $t) {
				$url = dr_show_url($module, $t);
				$this->link->update($table, array('url' => $url), 'id='.$t['id']);
				if ($module['extend']) {
					$extend = $this->link
								   ->where('cid', $t['id'])
								   ->order_by('id DESC')
								   ->get($table.'_extend')
								   ->result_array();
					if ($extend) {
						$tableid = (int)$extend['tableid'];
						foreach ($extend as $e) {
							$row = $this->link
										->where('id', (int)$e['id'])
										->get($table.'_extend_'.$tableid)
										->row_array();
							if ($row) {
								$url = dr_extend_url($module, $row);
								$this->link->update($table.'_extend_'.$tableid, array('url' => $url), 'id='.(int)$e['id']);
							}
						}
					}
				}
			}
			$this->mini_msg(dr_lang('135', "$tpage/$page"), dr_url(APP_DIR.'/home/url', array('todo' => 1, 'page' => $page + 1)), 2, 0);
		} else {
			$this->template->assign(array(
				'menu' => $this->get_menu(array(
					lang('136') => APP_DIR.'/admin/home/url',
					lang('001') => 'admin/module/cache'
				)),
				'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), 0, 'name=\'catid[]\' multiple style="width:200px;height:250px;"', lang('151')),
			));
			$this->template->display('content_url.html');
		}
	}
	
	/**
     * 生成静态
     */
	public function html() {
	
		$mod = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR);
		if (!$mod['html']) {
			$html = 1;
		} elseif (SITE_ID > 1 && !$mod['domain']) {
			$html = 2;
		} else {
			$rule = FALSE;
			foreach ($mod['category'] as $t) {
				if ($t['setting']['urlmode']) {
					$rule = TRUE;
					break;
				}
			}
			$html = $rule ? 0 : 3;
		}
		
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('html-621') => APP_DIR.'/admin/home/html',
			)),
			'html' => $html,
			'extend' => $mod['extend'] ? 1 : 0,
			'select' => $this->select_category($this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category'), 0, 'name=\'data[catid]\'', '全部'),
		));
		$this->template->display('content_html.html');
	}
	
	/**
     * 清除静态文件
     */
	public function clear() {
		
		$type = (int)$this->input->get('type');
		$page = (int)$this->input->get('page');
		$total = (int)$this->input->get('total');
		
		if ($page == 0 && !$total) {
			if ($type == 1) {
				$this->link->where('type', 3);
			} else {
				$this->link->where('type <>', 3);
			}
			$total = $this->link->count_all_results($this->content_model->prefix.'_html');
			$this->mini_msg('正在统计静态文件数量...', dr_url(APP_DIR.'/home/clear', array('type' => $type, 'page' => 1, 'total' => $total)));
		}
		$pagesize = 100;// 每次清除数量
		$count = ceil($total/$pagesize); // 计算总页数
		if ($page > $count) {
			$this->mini_msg('全部清除完成');
		}
		if ($type == 1) {
			$this->link->where('type', 3);
		} else {
			$this->link->where('type <>', 3);
		}
		$data = $this->link
					 ->select('filepath,id')
					 ->limit($pagesize, $pagesize * ($page - 1))
					 ->get($this->content_model->prefix.'_html')
					 ->result_array();
		$this->content_model->delete_html_file($data);
		$next = $page + 1;
		$this->mini_msg("共{$total}个文件，共需清理{$count}次，每次删除{$pagesize}个，正在进行第{$next}次...", dr_url(APP_DIR.'/home/clear', array('type' => $type, 'page' => $next, 'total' => $total)), 2, 0);
	}
}