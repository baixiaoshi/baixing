<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.6
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class D_Home_Form extends M_Controller {

	public $fid; // 表单id
	public $cid; // 内容id
	protected $form; // 表单
	protected $table; // 表单表
	protected $cdata; // 内容数据
	protected $field; // 全部字段
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		// 表单验证
		$this->fid = (int)trim(strrchr($this->router->class, '_'), '_');
		$this->form = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'form', $this->fid);
		if (!$this->form) $this->msg(lang('mod-108'));
		// 内容验证
		$this->cid = (int)$this->input->get('cid');
		$this->cdata = $this->get_cache_data('show'.APP_DIR.SITE_ID.$this->cid);
		if (!$this->cdata) {
			$this->load->model('content_model');
			$this->cdata = $this->content_model->get($this->cid);
		}
		if (!$this->cdata) $this->msg(dr_lang('mod-30', $this->cid));
		$this->table = SITE_ID.'_'.APP_DIR.'_form_'.$this->fid;
		// 投稿权限验证
		$rule = $this->form['permission'][$this->markrule];
		// 禁用权限
		if ($rule['disabled']) $this->msg(lang('mod-101'));
		// 每日发布数量检查
		if ($rule['postnum']) {
			$total = $this->link
						  ->where('uid', $this->uid)
						  ->where('DATEDIFF(from_unixtime(inputtime),now())=0')
						  ->count_all_results($this->table);
			if ($total >= $rule['postnum']) $this->msg(dr_lang('mod-102', $rule['postnum']));
		}
		// 投稿总数检查
		if ($rule['postcount']) {
			$total = $this->db
						  ->where('uid', $this->uid)
						  ->count_all_results($this->table);
			if ($total >= $rule['postcount']) $this->msg(dr_lang('mod-103', $rule['postcount']));
		}
		// 虚拟币检查
		if ($rule['score'] + $this->member['score'] < 0) $this->msg(dr_lang('mod-104', abs($rule['score']), $this->member['score']));
		$this->load->model('mform_model');
	}

    /**
     * 添加
     */
    public function index() {
	
		$mod = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR);
		$cat = $mod['category'][$this->cdata['catid']];
		$post = $error = null;
		
		if (IS_POST) {
			// 验证码
			if ($this->form['setting']['code'] && !$this->check_captcha('code')) {
				$post = $this->input->post('data', TRUE);
				$error = array('error' => 'code', 'msg' => lang('m-000'));
			} else {
				// 设置uid便于校验处理
				$_POST['data']['uid'] = $this->uid;
				$data = $this->validate_filter($this->form['field']);
				if (isset($data['error'])) {
					$error = $data;
					$post = $this->input->post('data', TRUE);
				} else {
					$data[1]['cid'] = $this->cid;
					$data[1]['uid'] = $this->uid;
					$data[1]['url'] = $this->cdata['url'];
					$data[1]['title'] = $this->cdata['title'];
					$data[1]['author'] = $this->member['username'] ? $this->member['username'] : 'guest';;
					$data[1]['inputip'] = $this->input->ip_address();
					$data[1]['inputtime'] = SYS_TIME;
					if ($id = $this->_add($data[1]) && $this->uid) {
						$rule = $this->form['permission'][$this->markrule];
						// 积分处理
						if ($rule['experience']) {
							$this->member_model->update_score(0, $this->uid, $rule['experience'], '', $this->form['name']);
						}
						// 虚拟币处理
						if ($rule['score']) {
							$this->member_model->update_score(1, $this->uid, $rule['score'], '', $this->form['name']);
						}
						// 操作成功处理附件
						$this->attachment_handle($this->uid, $this->table.'-'.$id, $this->form['field']);
					}
					$this->msg(lang('mod-105'), $this->cdata['url'], 1);
				}
			}
		}
		
		// 格式化输出自定义字段
		$fields = $mod['field'];
		$fields = $cat['field'] ? array_merge($fields, $cat['field']) : $fields;
		$fields['inputtime'] = array('fieldtype' => 'Date');
		$fields['updatetime'] = array('fieldtype' => 'Date');
		$data = $this->field_format_value($fields, $this->cdata, 1);
		
		$tpl = APPPATH.'templates/'.SITE_TEMPLATE.'/form_'.$this->fid.'.html';
		$this->template->assign($data);
		$this->template->assign(array(
			'tpl' => str_replace(FCPATH, '/', $tpl),
			'code' => $this->form['setting']['code'],
			'form' => $this->form,
			'result' => $error,
			'myfield' => $this->field_input($this->form['field'], $post, FALSE),
			'meta_title' => $this->form['name'].SITE_SEOJOIN.$data['title'].SITE_SEOJOIN.$cat['name'].SITE_SEOJOIN.MODULE_NAME.SITE_SEOJOIN.SITE_NAME
		));
		$this->template->display(is_file($tpl) ? basename($tpl) : 'form.html');
	}
	
	// 添加入库
	protected function _add($data) {
		// 入库
		$this->link->insert($this->table, $data);
		if (($id = $this->link->insert_id()) && ($user = dr_member_info($this->cdata['uid']))) {
			$murl = dr_member_url(APP_DIR.'/'.$this->router->class.'/listc', array('cid' => $this->cdata['id']));
			$title = dr_lang('mod-106', $this->cdata['title'], $this->form['name']);
			// 邮件提醒
			if ($this->form['setting']['email']) {
				$this->sendmail_queue($user['email'], $title, dr_lang('mod-107', $this->cdata['title'], $this->form['name'], $murl, $murl));
			}
			// 短信提醒
			if ($this->form['setting']['sms'] && $user['phone']) {
				$this->member_model->sendsms($user['phone'], $title);
			}
			// 添加提醒
			$this->member_model->add_notice($this->cdata['uid'], 3, '<a href="'.$murl.'">'.$title.'</a>');
		}
		return $id;
	}
	
}