<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Search_model extends CI_Model {

	public $link;
	public $tablename;

	/**
	 * 搜索模型类
	 */
    public function __construct() {
        parent::__construct();
		$this->link = $this->site[SITE_ID];
		$this->tablename = $this->db->dbprefix(SITE_ID.'_'.APP_DIR.'_search');
    }
	
	/**
	 * 清除过期缓存
	 *
	 * @param	string	$kw		关键字参数
	 * @param	intval	$page	页数
	 * @param	intval	$total	总数据
	 * @return	array	
	 */
	public function clear($time) {
		$time = $time ? $time : 3600;
		$this->link->where('inputtime<', SYS_TIME - $time)->delete($this->tablename);
	}
	
	/**
	 * 搜索缓存数据
	 *
	 * @param	intval	$id
	 * @param	intval	$page
	 * @return	array
	 */
	public function get($id, $page = 1) {
	
		$data = $this->link
					 ->where('id', $id)
					 ->limit(1)
					 ->get($this->tablename)
					 ->row_array();
		if (!$data) return array();
		
		$data['params'] = dr_string2array($data['params']);
		
		return $data;
	}
	
	/**
	 * 查询数据并设置缓存
	 *
	 * @param	array	$get
	 * @return	array
	 */
	public function set($get) {
	
		// 查询表名称
		$table = $this->db->dbprefix(SITE_ID.'_'.APP_DIR);
		$table_more = $this->db->dbprefix(SITE_ID.'_'.APP_DIR.'_category_data');
		
		// 条件数组
		ksort($get);
		$param = $get;
		$order = $get['order'];
		$keyword = $get['keyword'];
		unset($get['order'], $get['keyword']);
		$module = $this->ci->get_cache('module-'.SITE_ID.'-'.APP_DIR);
		
		// 主表的字段
		$from = '`'.$table.'`';
		$mod_field = $module['field'];
		$mod_field['uid'] = '';
		$mod_field['hits'] = '';
		$mod_field['catid'] = '';
		$mod_field['author'] = '';
		$mod_field['inputtime'] = '';
		$mod_field['updatetime'] = '';
		
		// 搜索关键字条件
		$where = array();
		$where[] = NULL;
		if ($keyword) {
			$where[] = '(`'.$table.'`.`title` LIKE "%'.$this->db->escape_str($keyword).'%" OR `'.$table.'`.`keywords` LIKE "%'.$this->db->escape_str($keyword).'%" OR `'.$table.'`.`description` LIKE "%'.$this->db->escape_str($keyword).'%")';
		}
		
		// 排序条件
		$_order = $order ? explode(',', $order) : array('updatetime');
		
		$order_by = $_order_by = array();
		foreach ($_order as $i => $t) {
			list($order, $by) = explode('_', $t);
			$by = $by ? $by : 'DESC';
			$_order_by[$order] = $by;
		}
		unset($_order);
		
		// 字段过滤
		foreach ($mod_field as $name => $field) {
			if (isset($field['ismain']) && !$field['ismain']) continue;
			
			if (isset($get[$name]) && $get[$name] && $name != 'catid') {
				$where[] = $this->_where($table, $name, $get[$name], $field);
			}
			if (isset($_order_by[$name])) {
				$order_by[] = '`'.$table.'`.`'.$name.'` '.$_order_by[$name];
			}
		}
		
		// 栏目的字段
		if ($get['catid']) {
			$more = FALSE;
			$cat_field = $module['category'][$get['catid']]['field'];
			$where[0] = '`'.$table.'`.`catid`'.($module['category'][$get['catid']]['child'] ? 'IN ('.$module['category'][$get['catid']]['childids'].')' : '='.$get['catid']);
			if ($cat_field) {
				foreach ($cat_field as $name => $field) {
					if (isset($get[$name]) && $get[$name]) {
						$more = TRUE;
						$where[] = $this->_where($table_more, $name, $get[$name], $cat_field);
					}
					if (isset($_order_by[$name])) {
						$more = TRUE;
						$order_by[] = '`'.$table.'`.`'.$name.'` '.$_order_by[$name];
					}
				}
			}
			if ($more) $from.= ' LEFT JOIN `'.$table_more.'` ON `'.$table.'`.`id`=`'.$table_more.'`.`id`';
		}
		
		// 筛选空值
		foreach ($where as $i => $t) {
			if (!$t) unset($where[$i]);
		}
		$where = $where ? 'WHERE '.implode(' AND ', $where) : '';
		
		// 最大数据量
		$limit = (int)$module['setting']['search']['total'] ? ' LIMIT '.(int)$module['setting']['search']['total'] : '';
		
		// 组合sql查询结果
		$sql = "SELECT `{$table}`.`id` FROM {$from} {$where} ORDER BY ".implode(',', $order_by).$limit;
		$id = md5($sql);
		
		// 查询是否存在缓存
		$data = $this->get($id);
		if ($data) return $data;
		
		// 重新生成缓存文件
		$data = $this->link->query($sql)->result_array();
		$contentid = array();
		$get['order'] = $order;
		$get['keyword'] = $keyword;
		
		if ($data) {
			foreach ($data as $t) {
				$contentid[] = $t['id'];
			}
			// 缓存入库
			$this->link->replace($this->tablename, array(
				'id' => $id,
				'catid' => intval($get['catid']),
				'params' => dr_array2string($param),
				'keyword' => $keyword,
				'contentid' => implode(',', $contentid),
				'inputtime' => SYS_TIME
			));
		}
		
		return array(
			'id' => $id,
			'page' => 1,
			'params' => $param,
			'catid' => intval($get['catid']),
			'keyword' => $keyword,
			'contentid' => $contentid ? implode(',', $contentid) : ''
		);
	}
	
	// 条件组合
	private function _where($table, $name, $value, $field) {
		if (strpos($value, '%') === 0 && strrchr($value, '%') === '%') { // like 条件
			return '`'.$table.'`.`'.$name.'` LIKE "'.$this->db->escape_str($value).'"';
		} elseif (preg_match('/[0-9]+,[0-9]+/', $value)) { // BETWEEN 条件
			list($s, $e) = explode(',', $value);
			return '`'.$table.'`.`'.$name.'` BETWEEN '.(int)$s.' AND '.intval($e ? $e : SYS_TIME);
		} elseif (is_numeric($value) && $field['fieldtype'] == 'Linkage') { // 联动菜单
			$data = $this->dcache->get('linkage-'.SITE_ID.'-'.$field['setting']['option']['linkage']);
			if ($data) {
				if ($data[$value]['child']) {
					return '`'.$table.'`.`'.$name.'` IN ('.$data[$value]['childids'].')';
				} else {
					return '`'.$table.'`.`'.$name.'`='.$value;
				}
			}
		} elseif (is_numeric($value)) {
			return '`'.$table.'`.`'.$name.'`='.$value;
		} else {
			return '`'.$table.'`.`'.$name.'`="'.$this->db->escape_str($value).'"';
		}
	}
	
}