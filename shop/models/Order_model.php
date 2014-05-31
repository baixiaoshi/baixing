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
	
class Order_model extends CI_Model {

	public $link;
	public $payname; // 支付记录表名称
	public $tablename; // 订单表名称
	public $indexname; // 订单索引表名称

	/**
	 * 订单模型类
	 */
    public function __construct() {
        parent::__construct();
		$this->link = $this->site[SITE_ID];
		$this->payname = $this->db->dbprefix('member_paylog_'.(int)substr((string)$this->uid, -1, 1));
		$this->tablename = $this->db->dbprefix(SITE_ID.'_'.APP_DIR.'_order');
		$this->indexname = $this->tablename.'_index';
		$this->reviewname = $this->tablename.'_review';
    }
	
	/**
	 * 收货地址字段
	 *
	 * @return	array
	 */
	public function get_address_field() {
		return  array(
			'city' => array(
				'ismain' => 1,
				'ismember' => 1,
				'fieldname' => 'city',
				'fieldtype' => 'Linkage',
				'setting' => array(
					'option' => array(
						'linkage' => 'address',
					),
					'validate' => array(
						'required' => 1
					)
				)
			),
			'zipcode' => array(
				'ismain' => 1,
				'ismember' => 1,
				'fieldtype' => 'Text',
				'fieldname' => 'zipcode',
				'setting' => array(
					'option' => array(
						'width' => 100,
					),
					'validate' => array(
						'required' => 1
					)
				)
			),
			'address' => array(
				'ismain' => 1,
				'ismember' => 1,
				'fieldtype' => 'Text',
				'fieldname' => 'address',
				'setting' => array(
					'option' => array(
						'width' => 250,
					),
					'validate' => array(
						'xss' => 1,
						'required' => 1
					)
				)
			),
			'name' => array(
				'ismain' => 1,
				'ismember' => 1,
				'fieldname' => 'name',
				'fieldtype' => 'Text',
				'setting' => array(
					'option' => array(
						'width' => 100,
					),
					'validate' => array(
						'xss' => 1,
						'required' => 1
					)
				)
			),
			'phone' => array(
				'ismain' => 1,
				'ismember' => 1,
				'fieldtype' => 'Text',
				'fieldname' => 'phone',
				'setting' => array(
					'option' => array(
						'width' => 150,
					),
					'validate' => array(
						'xss' => 1,
						'required' => 1
					)
				)
			),
			'default' => array(
				'ismain' => 1,
				'ismember' => 1,
				'fieldtype'	=> 'Radio',
				'fieldname' => 'default',
				'setting' => array(
					'option' => array(
						'value' => '0',
						'options' => lang('my-27').'|1'.PHP_EOL.lang('my-28').'|0',
					)
				)
			),
		);
	}
	
	/**
	 * 订单详情
	 *
	 * @param	intval	$id
	 * @return	array	$data
	 */
	public function get_order($id) {
	
		if (!$id) return NULL;
		
		$data = $this->link
					 ->where('id', $id)
					 ->limit(1)
					 ->get($this->tablename)
					 ->row_array();
		if (!$data) return NULL;
		
		$data['items'] = dr_string2array($data['items']);
		
		return $data;
	}
	
	/**
	 * 订单状态
	 *
	 * @param	intval	$id
	 * @param	array	$items
	 * @param	intval	$status
	 * @return	intval
	 */
	private function _get_order_status($id, $items, $status) {
		
		if ($status == 1) {
		
			$_status = array();
			$category = $this->ci->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category');
			
			foreach ($items as $t) {
				$_status[] = (int)$category[$t['catid']]['setting']['status'];
			}
			
			switch (min($_status)) {
			
				case 0:
					return 2; // 按照正常流程，下一步：等待发货
				
				case -1:
					$this->order_success($id, 2); // 
					return 3;
				
				case 1:
					$this->order_success($id, 3); // 直接交易完成
					return 4;
			}
		}
		
		return $status + 1;
	}
	
	// 更新库存
	private function _update_quantity($items) {
		
		if (!$items) return NULL;
		
		foreach ($items as $item) {
			if ($item['fid'] && $item['_format']) {
				$_format = $item['_format'];
				$_format['quantity'][$item['fid']] -= $item['num'];
				$this->link
					 ->where('id', $item['id'])
					 ->update($this->db->dbprefix(SITE_ID.'_'.APP_DIR.'_data_'.$item['tableid']), array('format' => dr_array2string($_format)));
			} else {
				$this->link
					 ->where('id', $item['id'])
					 ->set('quantity', 'quantity-'.$item['num'], FALSE)
					 ->update($this->db->dbprefix(SITE_ID.'_'.APP_DIR));
			}
		}
	}
	
	/**
	 * 订单发货
	 *
	 * @param	intval	$id		订单id
	 * @param	array	$items	商品数组
	 * @param	string	$note	发货备注
	 * @return	status
	 */
	public function order_send($id, $items, $note) {
	
		if (!$id) return NULL;
		
		$this->link->where('id', $id)->update($this->tablename, array(
			'sendtime' => SYS_TIME,
			'sendnote' => $note,
		));
		$this->order_success($id, 2);
		
	}
	
	/**
	 * 订单操作成功
	 *
	 * @param	intval	$id		订单id
	 * @param	intval	$status	当前状态代码
	 * @param	bool	$mark	订单入库时的操作
	 * @return	status
	 */
	public function order_success($id, $status, $mark = FALSE) {
	
		if (!$id) return NULL;
		
		$data = $this->get_order($id);
		if (!$data) return NULL;
		
		switch ($status) {
			
			case 0: // 当前状态
				break;
				
			case 1: // 当前状态是1（下单成功）
				if ($data['price'] > 0) { // 价格存在时进行扣减处理
					if ($this->is_quantity) $this->_update_quantity($data['items']); // 更新库存
					// 虚拟币扣减
					if ($data['score']) {
						$this->db // 更新虚拟币
							 ->set('score', 'score-'.abs($data['score']), FALSE)
							 ->where('uid', $data['uid'])
							 ->update($this->db->dbprefix('member'));
							 
						$this->db->insert($this->db->dbprefix('member_scorelog_'.$this->member['tableid']), array(
							'uid' => $data['uid'],
							'type' => 1,
							'mark' => '',
							'value' => -$data['score'],
							'note' => '购物，订单：<a href="index.php?s='.APP_DIR.'&c=order&m=show&id='.$id.'">'.$id.'</a>',
							'inputtime' => SYS_TIME,
						));
					}
				}
				
				$status = $this->_get_order_status($id, $data['items'], 1);
				$this->link
					 ->where('id', $id)
					 ->update($this->tablename, array('status' => $status));
				
				break;
				
			case 2: // 当前状态是2（支付成功，等待发货）时，按照发货流程转换状态 
				$status = $this->_get_order_status($id, $data['items'], 2);
				$this->link
					 ->where('id', $id)
					 ->update($this->tablename, array('status' => $status));
				break;
				
			case 3: // 交易成功时，按照发货流程转换状态 
				// 虚拟币打入卖家账户
				$tableid = (int)substr((string)$data['selluid'], -1, 1);
				if ($data['score']) {
					$this->db // 更新虚拟币
						 ->set('score', 'score+'.abs($data['score']), FALSE)
						 ->where('uid', $data['selluid'])
						 ->update('member');
					$this->db->insert($this->db->dbprefix('member_scorelog_'.$tableid), array(
						'uid' => $data['selluid'],
						'type' => 1,
						'mark' => '',
						'note' => '卖出商品，订单：<a href="'.MEMBER_URL.'index.php?s='.APP_DIR.'&c=order&m=show&id='.$id.'" target="_blank">'.$id.'</a>',
						'value' => $data['score'],
						'inputtime' => SYS_TIME,
					));
				}
				if ($data['price'] > 0) {
					// 金钱打入卖家账户
					$this->db
						 ->where('uid', $data['selluid'])
						 ->set('money', 'money+'.$data['price'], FALSE)
						 ->update('member');
					// 更新记录表 
					$this->db->insert($this->db->dbprefix('member_paylog_'.$tableid), array(
						'uid' => $data['selluid'],
						'type' => 0,
						'note' => '卖出商品，订单：<a href="'.MEMBER_URL.'index.php?s='.APP_DIR.'&c=order&m=show&id='.$id.'" target="_blank">'.$id.'</a>',
						'order' => ','.$id.',',
						'value' => $data['price'],
						'status' => 1,
						'module' => APP_DIR,
						'inputtime' => SYS_TIME
					));
					// 买家账户金钱删除冻结数据
					$this->db
						 ->where('uid', $this->uid)
						 ->set('freeze', 'freeze-'.$data['price'], FALSE)
						 ->update('member');
				}
				// 更新订单状态
				$this->link->where('id', $id)->update($this->tablename, array('status' => 4, 'successtime' => SYS_TIME));
				// 更新订单索引表
				$category = $this->ci->get_cache('module-'.SITE_ID.'-'.APP_DIR, 'category');
				foreach ($data['items'] as $t) {
					if ($t['id']) {
						$this->link->replace($this->indexname, array(
							'oid' => $id,
							'iid' => $t['id'],
							'uid' => $this->uid,
							'fid' => $t['fid'] ? $t['fid'] : 0,
							'review' => 0
						));
						$exp = (int)$category[$t['catid']]['permission'][$this->member['mark']]['buy_experience'];
						$score = (int)$category[$t['catid']]['permission'][$this->member['mark']]['buy_score'];
						$note = '购物，商品：<a href="'.$t['url'].'" target="_blank">'.$t['id'].'</a>';
						// 积分检查
						if ($exp) $this->member_model->update_score(0, $this->uid, $exp, '', $note);
						// 虚拟币
						if ($score) $this->member_model->update_score(1, $this->uid, $score, '', $note);
						// 更新成交量
						$this->link
							 ->where('id', $t['id'])
							 ->set('volume', 'volume+'.$t['num'], FALSE)
							 ->update($this->link->dbprefix(SITE_ID.'_'.APP_DIR));
					}
				}
				// 订单交易成功邮件通知买家
				$url = MEMBER_URL.'index.php?s='.APP_DIR.'&c=order&m=show&id='.$id;
				$this->ci->sendmail_queue($this->member['email'], lang('my-29'), dr_lang('my-30', $id, $url, $url));
				break;
		}
		
		return $status;
	}
	
	/**
	 * 添加订单
	 *
	 * @param	array	$data
	 * @return	id
	 */
	public function add_order($data) {
	
		if (!$data) return NULL;
		
		$this->link->insert($this->tablename, $data);
		return $this->db->insert_id();
	}
	
	/**
	 * 添加收货地址
	 *
	 * @param	array	$data
	 * @return	id
	 */
	public function add_address($data) {
	
		if (!$data) return NULL;
		
		if ($data['default']) {
			$this->db
				 ->where('uid', $this->uid)
				 ->update('member_address', array('default' => 0));
		}
		
		$data['uid'] = $this->uid;
		$this->db->insert('member_address', $data);
		
		return $this->db->insert_id();
	}
	
	/**
	 * 修改收货地址
	 *
	 * @param	intval	$id
	 * @param	array	$data
	 * @return	intavl
	 */
	public function edit_address($id, $data) {
	
		if (!$data || !$id) return NULL;
			 
		if ($data['default']) {
			$this->db
				 ->where('uid', $this->uid)
				 ->update('member_address', array('default' => 0));
		}
		
		$this->db
			 ->where('id', $id)
			 ->where('uid', $this->uid)
			 ->update('member_address', $data);
		
		return $id;
	}
	
	/**
	 * 获取单个收货地址
	 *
	 * @param	intval	$id
	 * @return	array
	 */
	public function get_address($id) {
	
		if (!$id) return NULL;
		
		return $this->db
					->where('id', $id)
					->where('uid', $this->uid)
					->limit(1)
					->get('member_address')
					->row_array();
	}
	
	/**
	 * 订单商品评价信息
	 *
	 * @param	intval	$id
	 * @return	array
	 */
	public function get_item_review($id) {
		
		if (!$id) return NULL;
		
		$order = $this->link
					  ->where('id', (int)$id)
					  ->select('items')
					  ->limit(1)
					  ->get($this->tablename)
					  ->row_array();
		if (!$order || !$order['items']) return NULL;
		
		$data = array();
		$items = dr_string2array($order['items']);
		foreach ($items as $t) {
			// 查询对应的评论数据
			$index = $this->link
						  ->where('oid', (int)$id)
						  ->where('uid', (int)$this->uid)
						  ->where('iid', (int)$t['id'])
						  ->where('fid', (int)$t['fid'])
						  ->limit(1)
						  ->get($this->indexname)
						  ->row_array();
			if ($index) {
				$review = $this->link
							   ->where('id', (int)$index['id'])
							   ->limit(1)
							   ->get($this->reviewname)
							   ->row_array();
				$data[] = array(
					'oid' => $index['oid'],
					'iid' => $index['iid'],
					'fid' => $index['fid'],
					'key' => $index['oid'].'-'.$index['iid'].'-'.$index['fid'],
					'item' => $t,
					'index' => $index['id'],
					'review' => $index['review'],
					'avgsort' => $review['avgsort'],
					'content' => $review['content'],
				);
			}
		}
		
		return $data;
	}
	
	// 更新商品表中的总评论分数
	public function update_review($iid) {
		
		if (!$iid) return NULL;
		
		$sql = "SELECT avg(b.avgsort) as avg FROM `{$this->indexname}` as a, `{$this->reviewname}` as b WHERE a.id=b.id AND a.iid={$iid} AND a.review=1 GROUP BY a.iid";
		$data = $this->link->query($sql)->row_array();
		if (!$data || $data['avg'] <= 0) return NULL;
		
		$this->link
			 ->where('id', (int)$iid)
			 ->update(SITE_ID.'_'.APP_DIR, array('review' => round($data['avg'], 1)));
		
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
				$select->where('id', intval($data['keyword']));
			}
			if (isset($data['start']) && $data['start'] && $data['start'] != $data['end']) {
				$select->where('inputtime BETWEEN '.$data['start'].' AND '. $data['end']);
			}
			if (isset($data['name']) && $data['name']) {
				$select->like('username', $data['name']);
			}
			if (isset($data['status']) && $data['status']) {
				$select->where('status', $data['status']);
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
		$data = $select->order_by('id DESC')
					   ->get($this->tablename)
					   ->result_array();
		$_param['total'] = $total;
		
		return array($data, $_param);
	}
	
}