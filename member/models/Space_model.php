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
	
class Space_model extends CI_Model{

	public $cache_file;
    
	/**
	 * 初始化
	 */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 会员空间信息
	 * 
	 * @param	intval	uid
	 * @return	array
	 */
	public function get($uid) {
		
		if (!$uid) return NULL;
		
		$space = $this->db
					  ->where('uid', $uid)
					  ->limit(1)
					  ->get('space')
					  ->row_array();
		if (!$space) return NULL;
		
		return $space;
	}
	
	
	/**
	 * 会员空间信息
	 * 
	 * @param	intval	$uid
	 * @param	array	$data
	 * @return	intval
	 */
	public function update($uid, $data) {
		
		// 空间名称重复
		if ($this->db->where('uid <>', $uid)->where('name', $data['name'])->count_all_results('space')) return 0;
		
		if ($this->db->where('uid', $uid)->count_all_results('space')) {
		
			// 修改资料
			$update = array(
				'logo' => $data['logo'],
				'code' => $data['code'],
				'name' => $data['name'],
				'title' => $data['title'],
				'footer' => $data['footer'],
				'keywords' => $data['keywords'],
				'description' => $data['description'],
				'introduction' => $data['introduction'],
			);
			
			$this->db
				 ->where('uid', $uid)
				 ->update('space', $update);
				 
			return 1;
			
		} else {
		
			// 创建空间
			$verify = (int)$this->ci->get_cache('member', 'setting', 'space', 'verify');
			
			$data['uid'] = $uid;
			$data['hits'] = 0;
			$data['style'] = 'default';
			$data['status'] = $verify ? 0 : 1;
			$data['regtime'] = SYS_TIME;
			
			$this->db->replace('space', $data);
			$this->init($uid);
			
			return $verify ? -1 : 1;
		}
	}
	
	/**
	 * 条件查询
	 *
	 * @param	object	$select	查询对象
	 * @param	array	$param	条件参数
	 * @return	array	
	 */
	private function _where(&$select, $param) {
	
		if (isset($param['keyword']) && $param['keyword']) {
			$select->like('space.name', urldecode($param['keyword']));
		}
		
		if (strlen($param['status']) > 0) {
			$select->where('space.status', (int)$param['status']);
		}
		
		if (isset($param['flag'])) {
			$_param['flag'] = $param['flag'];
			$select->where('space_flag.flag', $param['flag']);
		}
		
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
			$select	= $this->db->select('count(*) as total');
			$this->_where($select, $param);
			$data = $select->get(isset($param['flag']) ? 'space_flag' : 'space')->row_array();
			unset($select);
			$total = (int)$data['total'];
			if (!$total) return array(array(), array('total' => 0));
		}
		
		$select	= $this->db;
		$this->_where($select, $param);
		if (isset($param['flag'])) {
			$data = $select->select('space.*,member.`username`,member.`groupid`')
						   ->from('space_flag')
						   ->join('space', 'space_flag.uid = space.uid', 'left')
						   ->join('member', 'space.uid = member.uid', 'left')
						   ->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1))
						   ->order_by('space.regtime DESC')
						   ->get()
						   ->result_array();

		} else {
			$data = $select->select('space.*,member.`username`,member.`groupid`')
						   ->from('space')
						   ->join('member', 'space.uid = member.uid', 'left')
						   ->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1))
						   ->order_by('space.regtime DESC')
						   ->get()
						   ->result_array();
		}
		$param['total'] = $total;
		
		return array($data, $param);
	}
	
	// 初始化空间
	public function init($uid) {
		
		// 创建单页
		$this->db->insert('space_category', array(
			'uid' => $uid,
			'pid' => 0,
			'type' => 2,
			'name' => '关于我们',
			'body' => '',
			'link' => '',
			'title' => '',
			'showid' => 3,
			'modelid' => 0,
			'keywords' => '',
			'description' => '',
			'displayorder' => 1
		));
		$pid = $this->db->insert_id();
		$this->db->insert('space_category', array(
			'uid' => $uid,
			'pid' => $pid,
			'type' => 2,
			'name' => '空间简介',
			'body' => '<p>FineCMS v2（简称v2）是一款开源的跨平台网站内容管理系统，以“实用+好用”为基本产品理念，提供从内容发布、组织、传播、互动和数据挖掘的网站一体化解决方案。系统基于CodeIgniter框架，具有良好扩展性和管理性，可以帮助您在各种操作系统与运行环境中搭建各种网站模型而不需要对复杂繁琐的编程语言有太多的专业知识，系统采用UTF-8编码，采取(语言-代码-程序)两两分离的技术模式，全面使用了模板包与语言包结构，为用户的修改提供方便，网站内容的每一个角落都可以在后台予以管理，是一套非常适合用做系统建站或者进行二次开发的程序核心。<br /></p>',
			'link' => '',
			'title' => '',
			'showid' => 3,
			'modelid' => 0,
			'keywords' => '',
			'description' => '',
			'displayorder' => 2
		));
		$this->db->insert('space_category', array(
			'uid' => $uid,
			'pid' => $pid,
			'type' => 2,
			'name' => '联系我们',
			'body' => '<p><img src="http://api.map.baidu.com/staticimage?center=104.077889,30.551305&zoom=18&width=530&height=340&markers=104.076658,30.551693" height="340" width="530" /></p><p>FineCMS扣扣咨询：135977378<br />FineCMS电子邮箱：finecms@qq.com</p>',
			'link' => '',
			'title' => '',
			'showid' => 3,
			'modelid' => 0,
			'keywords' => '',
			'description' => '',
			'displayorder' => 3
		));
		$this->db->insert('space_category', array(
			'uid' => $uid,
			'pid' => 0,
			'type' => 0,
			'name' => '技术支持',
			'body' => '',
			'link' => 'http://www.dayrui.com',
			'title' => '',
			'showid' => 3,
			'modelid' => 0,
			'keywords' => '',
			'description' => '',
			'displayorder' => 99
		));
		
		// 按模型创建对应栏目
		$data = $this->db
					 ->get('space_model')
					 ->result_array();
		if ($data) {
			$i = 4;
			foreach ($data as $model) {
				$this->db->insert('space_category', array(
					'uid' => $uid,
					'pid' => 0,
					'type' => 1,
					'name' => $model['name'],
					'body' => '',
					'link' => '',
					'title' => '',
					'showid' => $model['id'] == 5 ? 0 : 3,
					'modelid' => $model['id'],
					'keywords' => '',
					'description' => '',
					'displayorder' => $i
				));
				$i ++;
			}
		}
		
	}
	
	// 删除空间
	public function delete($ids) {
	
		if (!$ids) return NULL;
		
		$this->db->where_in('uid', $ids)->delete('space');
		$model = $this->db->get('space_model')->result_array();
		if ($model) {
			foreach ($model as $t) {
				$this->db->where_in('uid', $ids)->delete('space_'.$t['table']);
			}
		}
		$this->db->where_in('uid', $ids)->delete('space_flag');
		$this->db->where_in('uid', $ids)->delete('space_category');
	}
}