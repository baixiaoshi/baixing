<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.2
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class Info extends M_Controller {
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
				'name' => '点击量',
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
				'name' => '发布者',
				'ismain' => 1,
				'fieldtype' => 'Text',
				'fieldname' => 'author',
				'setting' => array(
					'option' => array(
						'width' => 157,
						'value'	=> 'test'
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
				'name' => '发布时间',
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
				'name' => '更新时间',
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
				'name' => '客户IP',
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
    }
    /**
     * 分配一个模板页面
     */
    public function add_info(){

    	$error = $data = array();
		$catid = (int)$this->input->get('catid');
		$result = '';
		if (!$this->is_category_auth($catid, 'add')) $this->admin_msg(lang('160'));
		/**
		 * 分配省份信息
		 */
		$this->load->model('city_model');
		$provinces = $this->city_model->get_provinces();
		$this->template->assign('provinces',$provinces);
		if (IS_POST) {
			//将省市县分割
			$sheng = explode(',', $_POST['data']['provinces']);

			$chengshi = explode(',',$_POST['data']['city']);
			
			$xian = explode(',', $_POST['data']['area']);
			
			$catid = (int)$this->input->post('catid');

			//if (!$this->is_category_auth($catid, 'add')) $this->admin_msg(lang('160'));
			$cate = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category', $catid, 'field');
			$field = $cate ? array_merge($this->field, $cate) : $this->field;
		
			// 设置uid便于校验处理
			$uid = $this->input->post('data[author]') ? get_mymember_id($this->input->post('data[author]')) : 0;
			$_POST['data']['id'] = $id;
			$_POST['data']['uid'] = $uid;
			
			$data = $this->validate_filter($field);
			$data[1]['provinces'] = $sheng[0];
			$data[1]['provalue'] = $sheng[1];
			$data[1]['city'] = $chengshi[0];
			$data[1]['cityvalue'] = $chengshi[1];
			$data[1]['area'] = !empty($_POST['data']['area']) ? $xian[0] : 0;
			$data[1]['areavalue'] = !empty($_POST['data']['area']) ? $xian[1] : '';
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

       $this->template->display('addinfo.html');
    }
}