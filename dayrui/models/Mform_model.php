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
	
class Mform_model extends CI_Model {
	
	/**
	 * 模块表单模型类
	 */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 添加表单
	 * 
	 * @param	array	$data
	 * @return	string|TRUE
	 */
	public function add($data) {
	
		$this->link->insert($this->table, array(
			'name' => $data['name'] ? $data['name'] : 'form',
			'setting' => dr_array2string($data['setting']),
			'disabled' => 0,
			'permission' => dr_array2string($data['permission']),
		));
		
		if ($id = $this->link->insert_id()) {
		
			$name = 'Form_'.SITE_ID.'_'.$id;
			// 管理控制器
			$file = FCPATH.$this->dir.'/controllers/admin/'.$name.'.php';
			if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
			.'require FCPATH.\'dayrui/core/D_Admin_Form.php\';'.PHP_EOL.PHP_EOL
			.'class '.$name.' extends D_Admin_Form {'.PHP_EOL.PHP_EOL
			.'	public function __construct() {'.PHP_EOL
			.'		parent::__construct();'.PHP_EOL
			.'	}'.PHP_EOL
			.'}')) {
				$this->link->where('id', $id)->delete($this->table);
				return dr_lang('243', FCPATH.$this->dir.'/controllers/admin/');
			}
			// 会员控制器
			$file = FCPATH.$this->dir.'/controllers/member/'.$name.'.php';
			if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
			.'require FCPATH.\'dayrui/core/D_Member_Form.php\';'.PHP_EOL.PHP_EOL
			.'class '.$name.' extends D_Member_Form {'.PHP_EOL.PHP_EOL
			.'	public function __construct() {'.PHP_EOL
			.'		parent::__construct();'.PHP_EOL
			.'	}'.PHP_EOL
			.'}')) {
				$this->link->where('id', $id)->delete($this->table);
				return dr_lang('243', FCPATH.$this->dir.'/controllers/member/');
			}
			// 前端发布控制器
			$file = FCPATH.$this->dir.'/controllers/'.$name.'.php';
			if (!file_put_contents($file, '<?php'.PHP_EOL.PHP_EOL
			.'require FCPATH.\'dayrui/core/D_Home_Form.php\';'.PHP_EOL.PHP_EOL
			.'class '.$name.' extends D_Home_Form {'.PHP_EOL.PHP_EOL
			.'	public function __construct() {'.PHP_EOL
			.'		parent::__construct();'.PHP_EOL
			.'	}'.PHP_EOL
			.'}')) {
				$this->link->where('id', $id)->delete($this->table);
				return dr_lang('243', APPPATH.'controllers/');
			}
			// 主表sql
			$sql = "
			CREATE TABLE IF NOT EXISTS `".$this->table.'_'.$id."` (
			  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			  `cid` int(10) unsigned NOT NULL COMMENT '内容id',
			  `uid` mediumint(8) unsigned NOT NULL COMMENT '作者id',
			  `author` varchar(50) NOT NULL COMMENT '作者名称',
			  `inputip` varchar(30) DEFAULT NULL COMMENT '录入者ip',
			  `inputtime` int(10) unsigned NOT NULL COMMENT '录入时间',
			  `title` varchar(255) DEFAULT NULL COMMENT '内容主题',
			  `url` varchar(255) DEFAULT NULL COMMENT '内容地址',
			  `subject` varchar(255) DEFAULT NULL COMMENT '表单主题',
			  PRIMARY KEY `id` (`id`),
			  KEY `cid` (`cid`),
			  KEY `uid` (`uid`),
			  KEY `author` (`author`),
			  KEY `inputtime` (`inputtime`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='".$data['name']."表单数据表';";
			$this->link->query($sql);
			$this->db->insert('field', array(
				'name' => '主题',
				'fieldname' => 'subject',
				'fieldtype' => 'Text',
				'relatedid' => $id,
				'relatedname' => 'mform-'.$this->dir,
				'isedit' => 1,
				'ismain' => 1,
				'ismember' => 1,
				'issystem' => 1,
				'issearch' => 1,
				'disabled' => 0,
				'setting' => dr_array2string(array(
					'option' => array(
						'width' => 300, // 表单宽度
						'fieldtype' => 'VARCHAR', // 字段类型
						'fieldlength' => '255' // 字段长度
					),
					'validate' => array(
						'xss' => 1, // xss过滤
						'required' => 1, // 表示必填
					)
				)),
				'displayorder' => 0,
			));
			// 会员菜单
			
		}
		return FALSE;
	}
	
	/**
	 * 删除
	 * 
	 * @param	intval	$id
	 * @param	intval	$sid
	 */
	public function del($id) {
		
		if (!$id) return NULL;
		
		$this->db // 删除字段
			 ->where('relatedid', (int)$id)
			 ->where('relatedname', 'mform-'.$this->dir)
			 ->delete('field');
		
		$this->link->query('DROP TABLE IF EXISTS `'.$this->table.'_'.$this->dir.'`');
		
		$this->link
			 ->where('id', (int)$id)
			 ->delete($this->table);
		
		$this->load->model('attachment_model');
		$this->attachment_model->delete_for_table($this->table, TRUE);
		$this->attachment_model->delete_for_table($this->table.'_'.$this->dir, TRUE);
		 
		@unlink(FCPATH.$this->dir.'/controllers/Form_'.SITE_ID.'_'.$id.'.php');
		@unlink(FCPATH.$this->dir.'/controllers/admin/Form_'.SITE_ID.'_'.$id.'.php');
		@unlink(FCPATH.$this->dir.'/controllers/member/Form_'.SITE_ID.'_'.$id.'.php');
		
		return NULL;
	}
	
	//-------------------------------------------------------//
	
	
	/**
	 * 获取表单内容
	 *
	 * @param	intval	$id
	 * @return	intavl
	 */
	public function get($id, $fid) {
		
		if (!$fid || !$id) return NULL;
		
		return $this->link
					->where('id', (int)$id)
					->get(SITE_ID.'_'.APP_DIR.'_form_'.(int)$fid)
					->row_array();
	}
}