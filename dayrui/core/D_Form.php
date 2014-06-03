<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class D_Form extends M_Controller {
	
	public $mid;
	public $form;
	private $field;
	private $uriprefix;
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$form = str_replace('form_', '', $this->router->class);
		list($sid, $mid) = explode('_', $form);
		
		$this->mid = (int)$mid;
		$this->form = $this->get_cache('form-'.(int)$sid, $this->mid);
		
		if (!$this->form) {
			if (!IS_ADMIN) {
				exit($this->call_msg(lang('m-304')));
			} elseif (IS_AJAX) {
				exit(dr_json(0, lang('247')));
			} else {
				$this->admin_msg(lang('247'));
			}
		}
		
		$this->load->model('form_model');
		$this->field = array(
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
			'inputip' => array(
				'name' => lang('106'),
				'ismain' => 1,
				'fieldname' => 'inputip',
				'fieldtype' => 'Text',
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
		$this->uriprefix = 'admin/form_'.SITE_ID.'_'.$this->mid.'/';
    }
	
	/**
     * 内容维护
     */
	protected function _listc() {
		
		if (!$this->is_auth('admin/form/listc')) {
			if (IS_AJAX) {
				exit('<img src='.SITE_URL.'member/statics/js/skins/icons/error.png>'.dr_lang('049', 'admin/form/listc'));
			} else {
				$this->admin_msg(dr_lang('049', 'admin/form/listc'));
			}
		}
	
		if (IS_POST && $this->input->post('action')) {
			if ($this->input->post('action') == 'del') {
				// 删除
				$this->load->model('attachment_model');
				$_ids = $this->input->post('ids');
				foreach ($_ids as $id) {
					$this->form_model
						 ->link
						 ->where('id', (int)$id)
						 ->delete($this->form_model->prefix.'_'.$this->form['table']);
					$this->attachment_model->delete_for_table($this->form_model->prefix.'_'.$this->form['table'].'-'.$id);
				}
			} else if ($this->input->post('action') == 'order') {
				// 修改
				$_ids = $this->input->post('ids');
				$_data = $this->input->post('data');
				foreach ($_ids as $id) {
					$this->form_model
						 ->link
						 ->where('id', (int)$id)
						 ->update($this->form_model->prefix.'_'.$this->form['table'], $_data[$id]);
				}
				unset($_ids, $_data);
			}
			exit(dr_json(1, lang('000')));
		}
		
		// 根据参数筛选结果
		$param = $this->input->get(NULL);
		unset($param['s'],$param['c'],$param['m'],$param['d'],$param['page']);
		if ($this->input->post('search')) {
			$search = $this->input->post('data');
			$param['keyword'] = $search['keyword'];
			$param['start'] = $search['start'];
			$param['end'] = $search['end'];
		}
		
		// 数据库中分页查询
		list($data, $total)	= $this->form_model->limit_page($this->form['table'], $param, max((int)$this->input->get('page'), 1), (int)$this->input->get('total'));
		
		$param['total'] = $total;
		
		$this->template->assign(array(
			'mid' => $this->mid,
			'menu' => $this->get_menu(array(
				lang('245') => 'admin/form/index',
				$this->form['name'] => $this->uriprefix.'index',
				lang('add') => $this->uriprefix.'add'
			)),
			'list' => $data,
			'form' => 'form_'.SITE_ID.'_'.$this->mid,
			'param'	=> $param,
			'pages'	=> $this->get_pagination(dr_url($this->router->class.'/index', $param), $param['total']),
		));
		$this->template->display(is_file(FCPATH.'dayrui/templates/admin/form_'.$this->mid.'.html') ? 'form_'.$this->mid.'.html' : 'form_listc.html');
    }
	
	/**
     * 添加内容
     */
	protected function _addc() {
		
		if (!$this->is_auth('admin/form/listc')) {
			if (IS_AJAX) {
				exit('<img src='.SITE_URL.'member/statics/js/skins/icons/error.png>'.dr_lang('049', 'admin/form/listc'));
			} else {
				$this->admin_msg(dr_lang('049', 'admin/form/listc'));
			}
		}
		
		if (IS_POST) {
			
			$data = $this->validate_filter($this->form['field'] + $this->field);
			
			// 验证出错信息
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} else {
			
				// 设定文档默认值
				$data[1]['displayorder'] = 0;
				
				// 发布文档
				if (($id = $this->form_model->addc($this->form['table'], $data[1])) != FALSE) {
					// 附件归档到文档
					$this->attachment_handle($this->uid, $this->form_model->prefix.'_'.$this->form['table'].'-'.$id, $this->form['field']);
					$this->member_msg(lang('000'), dr_url($this->router->class.'/index'), 1);
				}
			}
			$data = $data[1];
			unset($data['id']);
		}
		
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('245') => 'admin/form/index',
				$this->form['name'] => $this->uriprefix.'index',
				lang('add') => $this->uriprefix.'add/'
			)),
			'error' => $error,
			'myfield' => $this->field_input($this->form['field'] + $this->field, $data)
		));
		$this->template->display('form_addc.html');
	}
	
	/**
     * 修改内容
     */
	protected function _editc() {
		
		if (!$this->is_auth('admin/form/listc')) {
			if (IS_AJAX) {
				exit('<img src='.SITE_URL.'member/statics/js/skins/icons/error.png>'.dr_lang('049', 'admin/form/listc'));
			} else {
				$this->admin_msg(dr_lang('049', 'admin/form/listc'));
			}
		}
		
		$id = (int)$this->input->get('id');
		$table = $this->form_model->prefix.'_'.$this->form['table'];
		$data = $this->form_model->link->where('id', (int)$id)->get($table)->row_array();
		if (!$data) $this->admin_msg(lang('019'));
			
		if (IS_POST) {
			$data = $this->validate_filter($this->form['field'] + $this->field);
			// 验证出错信息
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} else {
				// 发布文档
				if (($id = $this->form_model->editc($id, $this->form['table'], $data[1])) != FALSE) {
					// 附件归档到文档
					$this->attachment_handle($this->uid, $table.'-'.$id, $this->form['field']);
					$this->attachment_replace($this->uid, $id, $table);
					$this->member_msg(lang('000'), dr_url($this->router->class.'/index'), 1);
				}
			}
			$data = $data[1];
			unset($data['id']);
		}
		
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('245') => 'admin/form/index',
				$this->form['name'] => $this->uriprefix.'index',
				lang('add') => $this->uriprefix.'add',
				lang('edit') => $this->uriprefix.'edit/id/'.$id,
			)),
			'error' => $error,
			'myfield' => $this->field_input($this->form['field'] + $this->field, $data)
		));
		$this->template->display('form_addc.html');
	}
	
	/**
     * 提交内容扩展数据
     */
    protected function _post() {
		
		if ($this->form['setting']['code'] && !$this->check_captcha('code')) exit($this->call_msg(lang('m-000')));
		
		$data = $this->validate_filter($this->form['field']);
			
		// 验证出错信息
		if (isset($data['error'])) exit($this->call_msg($data['msg']));
		
		$data[1]['inputip'] = $this->input->ip_address();
		$data[1]['inputtime'] = SYS_TIME;
		$data[1]['displayorder'] = 0;
		
		$this->load->model('form_model');
		$this->form_model->addc($this->form['table'], $data[1]);
		
		if ($this->form['setting']['send'] && $this->form['setting']['template']) {
			extract($data[1]);
			// 兼容php5.5
			if (version_compare(PHP_VERSION, '5.5.0') >= 0) {
				$content = preg_replace_callback("/{(.*)}/U", "\$\\1", $this->form['setting']['template']);
			} else {
				$content = preg_replace("/{(.*)}/Ue", "\$\\1", $this->form['setting']['template']);
			}
			$this->sendmail_queue($this->form['setting']['send'], dr_lang('m-306', $this->form['name']), nl2br($content));
		}
		
		$this->call_msg(lang('m-305'), 1);
    }
	
	/**
     * 回调方法
     */
	protected function call_msg($msg, $code = 0) {
		
		$url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		
		if (IS_AJAX) {
			exit(dr_json($code, $msg, $url)); // AJAX请求时返回json格式
		} else {
			if ($code) {
				$this->msg($msg, $url, 1); // 成功
			} else {
				$this->msg($msg); // 错误
			}
		}
	}
	
}