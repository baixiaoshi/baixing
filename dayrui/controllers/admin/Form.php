<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.1
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Form extends M_Controller {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$this->load->model('form_model');
    }
	
	/**
     * 管理
     */
    public function index() {
		$this->template->assign(array(
			'list' => $this->form_model->link->get($this->form_model->prefix)->result_array(),
			'menu' => $this->get_menu(array(
				lang('245') => 'admin/form/index',
				lang('add') => 'admin/form/add',
				lang('001') => 'admin/form/cache'
			)),
		));
		$this->template->display('form_index.html');
    }
	
	/**
     * 添加
     */
    public function add() {
	
		if (IS_POST) {
			
			$data = $this->input->post('data', TRUE);
			$result = $this->form_model->add($data);
			
			if ($result === TRUE) {
				$this->form_model->cache();
				$this->admin_msg(lang('000'), dr_url('form/index'), 1);
			}
			
		}
		
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('245') => 'admin/form/index',
				lang('add') => 'admin/form/add',
				lang('001') => 'admin/form/cache'
			)),
			'data' => $data,
			'result' => $result,
		));
		$this->template->assign('menu', $this->get_menu(array(
		    lang('245') => 'admin/form/index',
		    lang('add') => 'admin/form/add',
		    lang('001') => 'admin/form/cache'
		)));
		$this->template->display('form_add.html');
    }
	
	/**
     * 修改
     */
    public function edit() {
	
		$id = (int)$this->input->get('id');
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get($this->form_model->prefix)
					 ->row_array();
		if (!$data) $this->admin_msg(lang('019'));
		
		if (IS_POST) {
			$data = $this->input->post('data', TRUE);
			$this->form_model->edit($id, $data);
			$this->form_model->cache();
			$this->admin_msg(lang('000'), dr_url('form/index'), 1);
		}
		
		$data['setting'] = dr_string2array($data['setting']);
		
		$this->template->assign(array(
			'menu' => $this->get_menu(array(
				lang('245') => 'admin/form/index',
				lang('add') => 'admin/form/add',
				lang('001') => 'admin/form/cache'
			)),
			'data' => $data,
			'result' => $result,
		));
		$this->template->display('form_add.html');
    }
	
	/**
     * 删除
     */
    public function del() {
		$this->form_model->del((int)$this->input->get('id'));
		$this->admin_msg(lang('000'), dr_url('form/index'), 1);
	}
	
	/**
     * 生成表单
     */
    public function toform() {
		
		$id = (int)$this->input->get('id');
		$data = $this->get_cache('form-'.SITE_ID, $id);
		if (!$data) exit('<div style="color:red;padding:20px;">'.lang('247').'<br>&nbsp;</div>');
		
		$string = '<link href="'.SITE_URL.'dayrui/statics/css/table_form.css" rel="stylesheet" type="text/css" />'.PHP_EOL;
		$string.= '<form action="'.SITE_URL.'index.php?c=form_'.SITE_ID.'_'.$id.'" method="post" name="myform" id="myform">'.PHP_EOL;
		$string.= '<table width="100%" class="table_form">'.PHP_EOL;
		$string.= $this->field_input($data['field']).PHP_EOL;
		
		if ($data['setting']['code']) {
			$code = MEMBER_URL.'index.php?c=api&m=captcha&width=100&height=40';
			$string.= '<tr>'.PHP_EOL;
			$string.= '<th width="200"><font color="red">*</font> 验证码：</th>'.PHP_EOL;
			$string.= '<td><input name="code" id="dr_code" class="input-text" type="text" /><img align="absmiddle" style="cursor:pointer;" onclick="this.src=\''.$code.'&\'+Math.random();" src="'.$code.'" /></td>'.PHP_EOL;
			$string.= '</tr>'.PHP_EOL;
		}
		
		$string.= '<tr>'.PHP_EOL;
		$string.= '<th width="200" >&nbsp;</th>'.PHP_EOL;
		$string.= '<td><input class="button" type="submit" name="submit" style="text-align:center;" value="提交" /></td>'.PHP_EOL;
		$string.= '</tr>'.PHP_EOL;
		$string.= '</table>'.PHP_EOL;
		$string.= '</form>'.PHP_EOL;
		
		$string = htmlspecialchars(str_replace(array('					', '				'), '', $string));
		
		echo '<div class="explain-col"><font color="gray">将以下表单代码放到<b>你想显示表单地方</b>，比如首页、单页、内容页等等，你说了算！</font></div><div class="bk10"></div><textarea style="width:500px;height:300px;">'.$string.'</textarea>';
	}
	
	/**
     * 缓存
     */
    public function cache() {
		$this->form_model->cache($this->input->get('site'));
		$this->input->get('admin') or $this->admin_msg(lang('000'), isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', 1);
	}
	
}