<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.5
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */
	
class Format_model extends CI_Model {

	public $link;
	public $dataname;
	public $tablename;
	public $cache_file;
    
	/**
	 * 构造函数
	 */
    public function __construct() {
        parent::__construct();
		$this->link = $this->site[SITE_ID];
		$this->dataname = $this->db->dbprefix(SITE_ID.'_'.APP_DIR.'_format_data');
		$this->tablename = $this->db->dbprefix(SITE_ID.'_'.APP_DIR.'_format');
    }
	
	/**
	 * 条件查询
	 *
	 * @param	object	$select	查询对象
	 * @param	array	$param	条件参数
	 * @return	array	
	 */
	private function _where(&$select, $param) {
	
		$_param = array();
		$this->cache_file = md5($this->duri->uri(1).$this->uid.SITE_ID.$this->input->ip_address().$this->input->user_agent()); // 缓存文件名称
		
		// 存在POST提交时，重新生成缓存文件
		if (IS_POST) {
			$data = $this->input->post('data');
			$this->cache->file->save($this->cache_file, $data, 3600);
			$param['search'] = 1;
		}
		
		// 存在search参数时，读取缓存文件
		if ($param['search'] == 1) {
			$data = $this->cache->file->get($this->cache_file);
			$_param['search'] = 1;
			if (isset($data['keyword']) && $data['keyword']) {
				$select->like('name', urldecode($data['keyword']));
			}
		}
		
		return $_param;
	}
	
	/**
	 * 数据分页显示
	 *
	 * @param	array	$param	条件参数
	 * @param	intval	$page	页数
	 * @param	intval	$total	总数据
	 * @return	array	
	 */
	public function limit_page($param, $page, $total) {
	
		if (!$total) {
			$select	= $this->link->select('count(*) as total');
			$this->_where($select, $param);
			$data = $select->get($this->tablename)->row_array();
			unset($select);
			$total = (int)$data['total'];
			if (!$total) return array(array(), array('total' => 0));
		}
		
		$select	= $this->link->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1));
		$_param	= $this->_where($select, $param);
		$data = $select->order_by('id ASC')
					   ->get($this->tablename)
					   ->result_array();
		$_param['total'] = $total;
		
		return array($data, $_param);
	}
	
	public function get($id) {
	
		if (!$id) return NULL;
		
		$data = $this->link
					 ->where('id', $id)->limit(1)
					 ->get($this->tablename)
					 ->row_array();
					 
		if (!$data) return NULL;
		$data['catid'] = $data['catid'] ? @explode(',', $data['catid']) : 0;
		
		return $data;
	}
	
	/**
	 * 添加类别
	 *
	 * @param	array	$data
	 * @return	string|id
	 */
	public function add($data) {
		
		// 判断是否有重复类别
		if ($this->link->where('name', $data['name'])->count_all_results($this->tablename)) {
			return lang('my-58');
		}
		$data['catid'] = ','.(string)@implode(',', $data['catid']).',';
		
		// 入库
		$this->link->insert($this->tablename, $data);
		return $this->link->insert_id();
	}
	
	/**
	 * 修改品牌
	 *
	 * @param	array	$data
	 * @return	string|id
	 */
	public function edit($id, $data) {
		
		// 判断是否有重复品牌
		if ($this->link->where('id<>'.$id)->where('name', $data['name'])->count_all_results($this->tablename)) {
			return lang('my-49');
		}
		$data['catid'] = ','.(string)@implode(',', $data['catid']).',';
		// 入库
		$this->link->where('id', $id)->update($this->tablename, $data);
		return $id;
	}
	
	public function get_data($fid) {
	
		$data = $this->link
					 ->where('fid', $fid)
					 ->order_by('displayorder ASC,id ASC')
					 ->get($this->dataname)
					 ->result_array();
		if (!$data) return NULL;
		
		$_data = array();
		foreach ($data as $t) {
			$_data[$t['id']] = $t;
		}
		
		return $_data;
	}
	
	/**
	 * 添加
	 *
	 * @param	array	$data
	 * @return	string|id
	 */
	public function adddata($data) {
	
		$temp = $data;
		$name = explode(PHP_EOL, $data['name']);
		
		foreach ($name as $t) {
			// 入库
			$t = trim($t);
			if ($t) {
				$temp['name'] = $t;
				$this->link->insert($this->dataname, $temp);
			}
		}
		
		return 1;
	}	
	
	/**
	 * 修改
	 *
	 * @param	array	$data
	 * @return	string|id
	 */
	public function editdata($id, $data) {
		
		// 入库
		$this->link->where('id', $id)->update($this->dataname, $data);
		
		return $id;
	}
	
	public function cache() {
		
		$data = $this->link->get($this->tablename)->result_array();
		$cache = array();
		$this->dcache->delete('format-'.SITE_ID);
		
		if (!$data) return NULL;
		
		foreach ($data as $t) {
			$catids = @explode(',', $t['catid']);
			if ($catids) {
				$format = array();
				$top = $this->link
							->where('fid', $t['id'])
							->where('pid', 0)
							->order_by('displayorder ASC, id ASC')
							->get($this->dataname)
							->result_array();
				if ($top) {
					// 遍历顶级分类属性
					foreach ($top as $p) {
						$value = $this->link
									  ->where('pid', $p['id'])
									  ->order_by('displayorder ASC, id ASC')
									  ->get($this->dataname)
									  ->result_array();
						// 当存在子属性时才生成缓存
						if ($value) {
							$format['data'][$p['id']] = $p;
							foreach ($value as $v) {
								$format['data'][$v['id']] = $v;
								$format['list'][$p['id']][] = $v['id'];
							}
						}
					}
				}
				// 直接查询对应的栏目id
				foreach ($catids as $catid) {
					if ($catid) $cache[$catid] = $format;
				}
			}
		}
		
		$this->dcache->set('format-'.SITE_ID, $cache);
		
		return $cache;
	}
	
}