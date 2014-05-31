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
	
class Field_model extends CI_Model {

	private $link;
	
	/**
	 * 字段模型类
	 */
    public function __construct() {
        parent::__construct();
        $this->link = $this->site[SITE_ID];
	}
	
	/**
	 * 所有数据
	 *
	 * @return	void
	 */
	public function get_data() {
		$data = $this->db
					 ->where('relatedid', $this->relatedid)
					 ->where('relatedname', $this->relatedname)
					 ->order_by('displayorder ASC,id ASC')
					 ->get('field')
					 ->result_array();
		if (!$data) return NULL;
		foreach ($data as $i => $t) {
			$t['setting'] = dr_string2array($t['setting']);
			$data[$i] = $t;
		}
		return $data;
	}
	
	/**
	 * 数据
	 *
	 * @param	int	$id
	 * @return	array
	 */
	public function get($id) {
		$data = $this->db
					 ->where('id', (int)$id)
					 ->where('relatedid', $this->relatedid)
					 ->where('relatedname', $this->relatedname)
					 ->limit(1)
					 ->get('field')
					 ->row_array();
		if (!$data) return NULL;
		$data['setting'] = dr_string2array($data['setting']);
		return $data;
	}
	
	/**
	 * 添加字段
	 *
	 * @param	array	$data
	 * @param	string	$sql
	 * @return	void
	 */
	public function add($data, $sql) {
		$data['setting'] = dr_array2string($data['setting']);
		$data['issystem'] = 0;
		$data['issearch'] = (int)$data['issearch'];
		$data['ismember'] = (int)$data['ismember'];
		$data['disabled'] = (int)$data['disabled'];
		$data['relatedid'] = $this->relatedid;
		$data['relatedname'] = $this->relatedname;
		$data['displayorder'] = (int)$data['displayorder'];
		$this->db->insert('field', $data); // 入库字段表
		if ($sql) $this->update_table($sql, $data['ismain']);
	}
	
	/**
	 * 修改字段
	 *
	 * @param	array	$_data	旧数据
	 * @param	array	$data	新数据
	 * @param	string	$sql	执行该操作的sql语句
	 * @return	string
	 */
	public function edit($_data, $data, $sql) {
		if (!$_data || !$data) return NULL;
		// 如果字段类型、长度变化时，分别更新各站点
		if ($data['setting']['option']['fieldtype'] != $_data['setting']['option']['fieldtype'] ||
		$data['setting']['option']['fieldlength'] != $_data['setting']['option']['fieldlength']) {
			$this->update_table($sql, $_data['ismain']);
		}
		$data['setting'] = dr_array2string($data['setting']);
		$data['issearch'] = (int)$data['issearch'];
		$this->db // 更新字段表
			 ->where('id', $_data['id'])
			 ->update('field', $data);
	}
	
	/**
	 * 分别更新各站点的表结构
	 *
	 * @param	string	$sql		执行该操作的sql语句
	 * @param	intval	$ismain		是否主表
	 * @return	void
	 */
	public function update_table($sql, $ismain) {
		if (!$sql) return NULL;
		switch($this->relatedname) {
			case 'module': // 模块字段
				$table = $this->link->dbprefix(SITE_ID.'_'.$this->data['dirname']); // 主表名称
				if ($ismain) { // 更新主表 格式: 站点id_名称
					$this->link->query(str_replace('{tablename}', $table, $sql));
				} else { // 更新副表 格式: 名称_站点id_data_副表id
					for ($i = 0; $i < 125; $i ++) {
						if (!$this->link->query("SHOW TABLES LIKE '%".$table.'_data_'.$i."%'")->row_array()) break;
						$this->link->query(str_replace('{tablename}', $table.'_data_'.$i, $sql)); //执行更新语句
					}
				}
				break;
			case 'member': // 会员字段
				$table = $this->db->dbprefix('member_data'); // 会员表名称
				$this->link->query(str_replace('{tablename}', $table, $sql));
				break;
			case 'space': // 会员空间模型字段
				$table = $this->link->dbprefix('space_'.$this->data['table']); // 主表名称
				$this->link->query(str_replace('{tablename}', $table, $sql)); //执行更新语句
				break;
			default:
				if (strpos($this->relatedname, 'form') === 0) {
					// 内表单字段
					$table = $this->link->dbprefix(SITE_ID.'_form_'.$this->data['table']); // 主表名称
					$this->link->query(str_replace('{tablename}', $table, $sql));
				} elseif (strpos($this->relatedname, 'mform') === 0) {
					// 内容表单字段
					$table = $this->link->dbprefix(SITE_ID.'_'.$this->data['dirname'].'_form_'.$this->relatedid); // 主表名称
					$this->link->query(str_replace('{tablename}', $table, $sql));
				} elseif (strpos($this->relatedname, 'extend') === 0) {
					// 内容扩展字段
					$table = $this->link->dbprefix(SITE_ID.'_'.$this->data['dirname']); // 主表名称
					for ($i = 0; $i < 100; $i ++) {
						if (!$this->link->query("SHOW TABLES LIKE '%".$table.'_extend_'.$i."%'")->row_array()) break;
						$this->link->query(str_replace('{tablename}', $table.'_extend_'.$i, $sql)); //执行更新语句
					}
				} else {
					// 模块栏目分类
					$table = $this->link->dbprefix(SITE_ID.'_'.$this->data['module'].'_category_data'); // 主表名称
					if ($ismain) { // 更新主表 格式: 站点id_名称
						$this->link->query(str_replace('{tablename}', $table, $sql));
					} else { // 更新副表 格式: 名称_站点id_data_副表id
						for ($i = 0; $i < 100; $i ++) {
							if (!$this->link->query("SHOW TABLES LIKE '%".$table.'_'.$i."%'")->row_array()) break;
							$this->link->query(str_replace('{tablename}', $table.'_'.$i, $sql)); //执行更新语句
						}
					}
				}
				break;
		}
	}
	
	/*
	 * 判断同表字段否存在
	 *
	 * @param	string	$name	字段名称
	 * @param	intval	$int	字段id
	 * @return	int
	 */
	public function exitsts($name) {
		if (!$name)	return 1;
		switch($this->relatedname) {
			case 'module': // 模块字段
				$_field	= $this->db
							   ->where('fieldname', $name)
							   ->where('relatedid', $this->relatedid)
							   ->where('relatedname', $this->relatedname)
							   ->count_all_results('field');
				break;
			case 'member': // 会员字段
				$_field	= $this->db
							   ->where('fieldname', $name)
							   ->where('relatedname', 'member')
							   ->count_all_results('field');
				break;
			case 'space': // 会员空间模型字段
				$_field	= $this->db
							   ->where('fieldname', $name)
							   ->where('relatedid', $this->relatedid)
							   ->where('relatedname', 'space')
							   ->count_all_results('field');
				break;
			default: 
				if (strpos($this->relatedname, 'form') === 0) {
					// 内表单字段
					$_field	= $this->db
								   ->where('fieldname', $name)
								   ->where('relatedid', $this->relatedid)
								   ->where('relatedname', 'form')
								   ->count_all_results('field');
				} elseif (strpos($this->relatedname, 'extend') === 0) {
					// 内容扩展字段
					$_field	= $this->db
								   ->where('fieldname', $name)
								   ->where('relatedid', $this->relatedid)
								   ->where('relatedname', 'extend')
								   ->count_all_results('field');
				} elseif (strpos($this->relatedname, 'mform') === 0) {
					// 内容表单字段
					$_field	= $this->db
								   ->where('fieldname', $name)
								   ->where('relatedid', $this->relatedid)
								   ->where('relatedname', $this->relatedname)
								   ->count_all_results('field');
				}  else {
					// 模块栏目分类
					list($module, $siteid) = explode('-', $this->relatedname);
					$_field	= $this->db
								   ->where('fieldname', $name)
								   ->where('relatedname', $this->relatedname)
								   ->count_all_results('field');
					if (!$_field) {
						$module = get_module($module, $siteid);
						$_field = isset($module['field'][$name]) ? 1 : 0; 
					}
				}
				break;
		}
		return $_field ? 1 : 0;
	}
	
	/**
     * 删除字段
	 *
	 * @param	array	$ids
	 * @return	bool
     */
	public function del($ids) {
		if (!$ids) return FALSE;
		if (!is_array($ids)) $ids = array($ids);
		foreach ($ids as $id) {
			$data = $this->get($id);
			$field = $this->dfield->get($data['fieldtype']);
			if ($data['issystem'] == 0 && $field) {
				// 非系统字段才支持删除
				$this->db->where('id', $id)->delete('field');
				$sql = $field->drop_sql($data['fieldname']);
				if ($sql) $this->update_table($sql, $data['ismain']); // 需要分别更新各站点
			}
		}
		return TRUE;
	}
}