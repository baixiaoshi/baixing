<?php

/**
 * Dayrui Website Management System
 *
 * @since		version 2.0.0
 * @author		Dayrui <dayrui@gmail.com>
 * @license     http://www.dayrui.com/license
 * @copyright   Copyright (c) 2011 - 9999, Dayrui.Com, Inc.
 */

class D_Order extends M_Controller {
	
	public $is_quantity = TRUE; // 是否更新库存
	protected $is_num = TRUE; // 是否购买数量验证
	protected $is_format = TRUE; // 是否商品格式筛选（SKU）
	protected $is_freight = TRUE; // 是否加入运送方式
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		$module = $this->get_cache('module-'.SITE_ID.'-'.APP_DIR);
		if (!$module) $this->admin_msg(lang('m-148'));
		$this->load->library('cart');
		$this->load->model('order_model');
    }
	
	/**
     * 购物车商品数量
     */
    public function total() {
		exit("document.write('{$this->cart->total_items()}');");
	}
	
	/**
     * 加入购物车
     */
    protected function _add_cart() {
		$iid = (int)$this->input->get('iid');
		$num = (int)$this->input->get('num');
		$fid = $this->input->get('fid');
		$data = array(
		   'id' => $iid.'_'.$fid,
		   'qty' => $num,
		   'name' => 'finecms',
		   'price' => 2.0,
		);
		$this->cart->insert($data);
		echo $this->cart->total_items();
		exit;
    }
	
	/**
     * 移出购物车
     */
    protected function _del_cart() {
		$this->cart->update(array(
		   'qty' => 0,
		   'rowid' => $this->input->post('id'),
		));
    }
	
	/**
	 * 格式化商品信息
	 *
	 * @param	array	$data
	 * @return	array
	 */
	private function _format_item($data) {
	
		$this->load->model('content_model');
		if ($this->is_format) {
			$FORMAT = $this->get_cache('format-'.SITE_ID);
			if (!$FORMAT) {
				$this->load->model('format_model');
				$FORMAT = $this->format_model->cache();
			}
		}
		
		$list = array();
		$amount = 0;
		
		foreach ($data as $key => $t) {
		
			list($id, $fid) = explode('_', $t['id']); // 分解商品id与规格
			
			if (isset($item[$id]) && $item[$id]) {
				$row = $item[$id];
			} else {
				$row = $item[$id] = $this->content_model->get_item_data($id); // 商品信息
			}
			if (!$row) continue;
			
			$num = (int)$t['qty']; // 购买数量
			$price = $row['price']; // 商品价格
			$format = ''; // 商品规格信息
			$quantity = (int)$row['quantity']; // 商品库存数量
			$discount = 0; // 促销价格
			
			if ($this->is_format && $fid) {
				// 根据商品规格计算价格和库存
				$_fid = explode('-', $fid);
				foreach ($_fid as $f) {
					$format.= ','.$FORMAT[$row['catid']]['data'][$f]['name'];
				}
				$price = (float)$row['format']['price'][$fid];
				$quantity = (int)$row['format']['quantity'][$fid];
			}
			
			if ($this->is_num) {
				$num = $num > $quantity ? $quantity : $num;
				if ($num <= 0 ) continue; // 数量不足时退出
			}
			
			// 计算促销价格，判断促销时间
			if ($row['discount'] && SYS_TIME >= $row['discount']['star'] && SYS_TIME <= $row['discount']['end']) {
				$z = (float)$row['discount'][$this->member['mark']];
				$discount = $z ? $price * ($z/10) : 0;
				$total = $discount * $num;
			} else {
				$total = $price * $num; // 当前商品的总价格
			}
			
			$amount+= $total; // 整个订单的总价格
			
			$list[$t['id']] = array(
				'id' => $id,
				'key' => $key,
				'num' => $num,
				'fid' => $fid ? $fid: 0,
				'uid' => $row['uid'],
				'url' => $row['url'],
				'catid' => $row['catid'],
				'title' => $row['title'],
				'thumb' => $row['thumb'],
				'price' => $price,
				'total' => $total,
				'author' => $row['author'],
				'format' => trim($format, ','),
				'_format' => $row['format'],
				'freight' => isset($row['freight']) ? dr_string2array($row['freight']) : NULL,
				'tableid' => $row['tableid'],
				'quantity' => $quantity,
				'discount' => $discount,
			);
		}
		
		return array($list, $amount);
	}
	
	/**
     * 购物车
     */
    protected function _home_cart() {
	
		$item = $list = array();
		$data = $this->cart->contents();
		
		if (IS_POST) {
			// 生成订单信息
			$url = dr_url('order/buy');
			$fid = $iid = $num = array();
			$post = $this->input->post('key');
			foreach ($post as $key => $_num) {
				list($_iid, $_fid) = explode('_', $data[$key]['id']);
				if ($_iid) {
					$iid[] = $_iid;
					$fid[] = $_fid;
					$num[] = $_num;
				}
			}
			$url.= '&iid='.@implode(',', $iid).'&fid='.@implode(',', $fid).'&num='.@implode(',', $num);
			header('Location: '.$url);
			exit;
		}
		
		if ($data) list($list, $amount) = $this->_format_item($data);
		
		$this->template->assign(array(
			'list' => $list,
			'amount' => number_format($amount, 2),
			'meta_title' => lang('my-00')
		));
        $this->template->display('cart.html');
    }
	
	/**
	 * 确认订单
	 */
	protected function _buy() {
	
		// 登录验证
    	if (!$this->uid) $this->msg(lang('m-039'), MEMBER_URL.SELF.'?c=login&m=index&backurl='.urlencode(dr_now_url()));
		
		// 分析url参数
		$iid = $this->input->get('iid');
		$fid = $this->input->get('fid');
		$num = $this->input->get('num');
		if (!$iid) $this->msg(lang('my-01'));
		if ($this->is_num && !$num) $this->msg(lang('my-02'));
		
		// 组装订单数据
		$_iid = explode(',', $iid);
		$_num = explode(',', $num);
		$_fid = $fid ? explode(',', $fid) : 0;
		$data = array();
		foreach ($_iid as $key => $id) {
			if ($id) {
				$data[$key] = array(
					'id' => $id.(isset($_fid[$key]) && $_fid[$key] ? '_'.$_fid[$key] : '_0'), // id标识由【商品id_规格参数】组成
					'qty' => $this->is_num ? max((int)$_num[$key], 1) : 1, // 购买数量
				);
			}
		}
		list($item, $amount) = $this->_format_item($data);
		
		if (!$item) $this->msg(lang('my-03')); // 数量不足时提示
		
		// 按卖家归类
		$data = array();
		foreach ($item as $key => $t) {
			if ($this->uid != $t['uid']) $data[$t['uid']][] = $t; // 不能购买自己的商品
		}
		
		if (!$data) $this->msg(lang('my-04')); // 筛选出非自己的商品时提示
		
		// 按卖家计算运单及总价格
		$list = array();
		foreach ($data as $uid => $value) {
			$price = 0;
			$freight = array(1 => 0, 2 => 0, 3 => 0);
			foreach ($value as $t){
				$price+= ($t['discount'] ? $t['discount'] : $t['price']) * $t['num']; // 计算订单总价格
				// 支持运费时才计算运费
				if ($this->is_freight) {
					if ($t['freight'] && $t['freight']['type'] == 0) {
						// 按订单
						$freight[1] += $t['freight'][1];
						$freight[2] += $t['freight'][2];
						$freight[3] += $t['freight'][3];
					} elseif ($t['freight'] && $t['freight']['type'] == 1) {
						// 按数量
						$freight[1] += $t['freight'][1] * $t['num'];
						$freight[2] += $t['freight'][2] * $t['num'];
						$freight[3] += $t['freight'][3] * $t['num'];
					}
				}
			}
			$list[] = array(
				'uid' => $t['uid'],
				'data' => $value,
				'price' => $price,
				'author' => $t['author'],
				'freight' => $freight,
			);
		}
		
		if (IS_POST) {
	
			if (!$this->check_captcha('code')) $this->msg(lang('m-000')); // 验证码验证
			$post = $this->input->post('data');
			
			// 支持运费时才验证收货地址
			if ($this->is_freight) {
				if (!$post['address']) $this->msg(lang('my-05')); // 收货地址不存在
				$address = $this->db
								->where('uid', $this->uid)
								->where('id', (int)$post['address'])
								->get('member_address')
								->row_array();
				if (!$address) $this->msg(lang('my-05')); // 收货地址不存在
			}
			
			$total = $score = 0;
			$orderid = '';
			$success = FALSE;
			
			// 根据运送方式重新计算总价
			foreach ($list as $shop) {
			
				$uid = $shop['uid'];
				$freight = $price = 0;
				
				if ($this->is_freight) {
					// 按选择的运费计算总价格
					$shipping = $post['freight'][$uid] ? $post['freight'][$uid] : 1;
					foreach ($shop['data'] as $t) {
						$_freight = (float)$t['freight'][$shipping];
						$price += $t['total'] + $_freight;
						$freight += $_freight;
					}
				} else {
					$shipping = '';
					foreach ($shop['data'] as $t) {
						$price += $t['total'];
					}
				}
				
				// 计算虚拟币兑换
				if ($post['score']) {
					$score = (int)abs($post['score']);
					$score = $score > $this->member['score'] ? $this->member['score'] : $score;
					// 计算需要多少虚拟币
					$_score = $price * SITE_CONVERT;
					$score = $_score > $score ? $score : $_score;
					// 计算虚拟币可兑换多少RMB
					$price = $price - $score/SITE_CONVERT;
				}
				
				// 更新至订单表中
				$order = array(
					'uid' => $this->uid,
					'city' => isset($address['city']) ? $address['city'] : '',
					'name' => isset($address['name']) ? $address['name'] : '',
					'score' => $score,
					'price' => $price,
					'items' => dr_array2string($shop['data']),
					'phone' => isset($address['phone']) ? $address['phone'] : '',
					'gbook' => isset($post['gbook'][$uid]) ? $post['gbook'][$uid] : '',
					'status' => 1,
					'zipcode' => isset($address['zipcode']) ? $address['zipcode'] : '',
					'address' => isset($address['address']) ? $address['address'] : '',
					'selluid' => $uid,
					'freight' => $freight,
					'sendnote' => '',
					'sendtime' => 0,
					'username' => $this->member['username'],
					'sellname' => $shop['author'],
					'shipping' => $shipping,
					'inputtime' => SYS_TIME,
					'successtime' => 0,
				);
				
				// 统计总价
				$total+= $price;
				/*
				if ($id = $this->order_model->add_order($order)) {
					if ($id < 0) {
						$success = TRUE; // 返回结果为TRUE时表示该订单已经付款或者是免费订单
					} else {
						$orderid .= $id.',';
					}
				}
				*/
				if ($id = $this->order_model->add_order($order)) $orderid.= $id.',';
			}
			
			$this->cart->destroy(); // 清空购物车
			$orderid = trim($orderid, ',');
			if (!$orderid) $this->msg(lang('my-06')); // 订购失败
			//if (!$orderid && $success) $this->msg(lang('m-208'), MEMBER_URL.'index.php?s='.APP_DIR.'&c=order', 1); // 购买成功无需付款
			
			// 更新订单到支付记录表中
			if ($this->member['money'] >= $total) {
				// 在余额中扣除
				$this->load->model('pay_model');
				$this->pay_model->add_for_buy($total, $orderid);
				$ids = explode(',', $orderid);
				foreach ($ids as $id) {
					$this->order_model->order_success($id, 1);
				}
				$this->msg(lang('my-14'), MEMBER_URL.'index.php?s='.APP_DIR.'&c=order', 1); // 购买成功无需付款
			} else {
				$this->msg(lang('my-13'), MEMBER_URL.'index.php?c=pay&m=add&money='.($total - $this->member['money']), 1);
			}
		}
		
		$address = $this->is_freight ? $this->db
											->where('uid', $this->uid)
											->order_by('default desc')
											->get('member_address')
											->result_array() : NULL;
		$this->template->assign(array(
			'list' => $list,
			'address' => $address,
			'meta_title' => lang('my-07')
		));
		$this->template->display('buy.html');
	}
	
	/**
	 * 添加收货地址
	 */
	public function add_address() {
	
		$field = $this->order_model->get_address_field();
		
		if (IS_POST) {
			$data = $this->validate_filter($field);
			if (isset($data['error'])) exit(dr_json(0, $data['msg'], $data['error']));
			$id = $this->order_model->add_address($data[1]);
			$code = '<tr><td width="6%" style="text-align:center"><input type="radio" name="data[address]" value="'.$id.'" /></td> <td id="dr_address_'.$id.'" onclick="dr_edit_address('.$id.')">'.dr_linkagepos('address', $data[1]['city'], '&nbsp;&nbsp;', NULL).'&nbsp;&nbsp;'.$data[1]['address'].'&nbsp;&nbsp;('.$data[1]['name'].')&nbsp;&nbsp;'.$data[1]['phone'].'</td></tr>';
			exit(dr_json(1, $code, $id));
		}
		
		$this->load->helper('system');
		$this->template->assign(array(
			'data' => $data,
			'field' => $field,
		));
		$this->template->display('address.html');
	}
	
	/**
	 * 修改收货地址
	 */
	public function edit_address() {
	
		$id = (int)$this->input->get('id');
		$data = $this->order_model->get_address($id);
		$field = $this->order_model->get_address_field();
		
		if (IS_POST) {
			$data = $this->validate_filter($field);
			if (isset($data['error'])) exit(dr_json(0, $data['msg'], $data['error']));
			$id = $this->order_model->edit_address($id, $data[1]);
			$code = dr_linkagepos('address', $data[1]['city'], '&nbsp;&nbsp;', NULL).'&nbsp;&nbsp;'.$data[1]['address'].'&nbsp;&nbsp;('.$data[1]['name'].')&nbsp;&nbsp;'.$data[1]['phone'];
			exit(dr_json(1, $code, $id));
		}
		
		$this->load->helper('system');
		$this->template->assign(array(
			'data' => $data,
			'field' => $field,
		));
		$this->template->display('address.html');
	}
	
	
	// 会员中心记录
	protected function _member($type) {
		
		// 按用户分类查询
		if ($type) {
			$this->order_model->link->where('selluid', $this->uid);
		} else {
			$this->order_model->link->where('uid', $this->uid);
		}
		// id筛选
		$id = (int)$this->input->get('id');
		if ($id) {
			$this->order_model->link->where('id', $id);
		} else {
			// 时间筛选
			switch ($this->input->get('where')) {
				case 1: // 一月内
					$this->order_model->link->where('inputtime BETWEEN '.strtotime('-30 day').' AND '. SYS_TIME);
					break;
				case 2: // 半年内
					$this->order_model->link->where('inputtime BETWEEN '.strtotime('-180 day').' AND '. SYS_TIME);
					break;
				case 3: // 一年内
					$this->order_model->link->where('inputtime BETWEEN '.strtotime('-365 day').' AND '. SYS_TIME);
					break;
				case 4: // 三年内
					$this->order_model->link->where('inputtime BETWEEN '.strtotime('-1000 day').' AND '. SYS_TIME);
					break;
				default: // 默认一周内
					$this->order_model->link->where('inputtime BETWEEN '.strtotime('-7 day').' AND '. SYS_TIME);
					break;
			}
			// 状态筛选
			if ($this->input->get('status')) $this->order_model->link->where('status', (int)$this->input->get('status'));
			// 名称筛选
			if ($this->input->get('name')) {
				if ($type) {
					$this->order_model->link->where('username', $this->input->get('name'));
				} else {
					$this->order_model->link->where('sellname', $this->input->get('name'));
				}
			}
		}
		$this->order_model->link->order_by('inputtime DESC');
		if ($this->input->get('action') == 'more') { // ajax更多数据
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->order_model
						 ->link
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get($this->order_model->tablename)
						 ->result_array();
			if (!$data) exit('null');
			$this->template->assign('list', $data);
			$this->template->display('order_data.html');
		} else {
			$this->template->assign(array(
				'type' => $type,
				'list' => $this->order_model
							   ->link
							   ->limit($this->pagesize)
							   ->get($this->order_model->tablename)
							   ->result_array(),
				'method' => $this->router->method,
				'moreurl' => 'index.php?s='.APP_DIR.'&c='.$this->router->class.'&m='.$this->router->method.'&action=more',
                'meta_name' => $type ? lang('my-08') : lang('my-09'),
			));
			$this->template->display('order_index.html');
		}
	}
	
	// 买家付款
	protected function _member_pay() {
		
		$id = (int)$this->input->get('id');
		$data = $this->order_model->get_order($id);
		if (!$data) $this->member_msg(lang('my-10'));
		
		if ($data['uid'] != $this->uid) $this->member_msg(lang('my-11'));
		if ($data['status'] != 1) $this->member_msg(lang('my-12'));
		if ($data['price'] > $this->member['money']) $this->msg(lang('my-13'), MEMBER_URL.'index.php?c=pay&m=add&money='.($data['price'] - $this->member['money']), 1);
		
		// 在余额中扣除
		$this->load->model('pay_model');
		$this->pay_model->add_for_buy($total, array($id));
		$this->order_model->order_success($id, 1);
		$this->msg(lang('my-14'), MEMBER_URL.'index.php?s='.APP_DIR.'&c=order&m=show&id='.$id, 1);
	}
	
	// 买家确认收货
	protected function _member_confirm() {
	
		$id = (int)$this->input->get('id');
		$data = $this->order_model->get_order($id);
		if (!$data) $this->member_msg(lang('my-10'));
		
		if ($data['uid'] != $this->uid) $this->member_msg(lang('my-11'));
		if ($data['status'] != 3) $this->member_msg(lang('my-12'));
		
		$this->order_model->order_success($id, 3);
		$this->msg(lang('my-15'), MEMBER_URL.'index.php?s='.APP_DIR.'&c=order&m=show&id='.$id, 1);
	}
	
	// 卖家发货
	protected function _member_send() {
		
		$id = (int)$this->input->get('id');
		$data = $this->order_model->get_order($id);
		if (!$data) exit("<div style=\"padding:50px\">".lang('my-10')."</div>");
		
		if ($data['status'] != 2) exit("<div style=\"padding:50px\">".lang('my-12')."</div>");
		
		if (IS_POST) {
			$this->order_model->order_send($id, $data['items'], $this->input->post('sendnote'));
			// 通知
			$this->member_model->add_notice($data['uid'], 3, dr_lang('my-40', $this->member['username'], $id));
			exit(dr_json(1, 1, 1));
		}
		
		switch ($data['shipping']) {
			case 1:
				$shipping = lang('my-16');
				break;
			case 2:
				$shipping = lang('my-17');
				break;
			case 3:
				$shipping = lang('my-18');
				break;
			default:
				$shipping = lang('my-19');
				break;
		}
		
		$this->template->assign('shipping', $shipping);
		$this->template->display('order_send.html');
	}
	
	// 卖家改价
	protected function _member_price() {
		
		$id = (int)$this->input->get('id');
		$data = $this->order_model->get_order($id);
		if (!$data) exit("<div style=\"padding:50px\">".lang('my-10')."</div>");
		
		if ($data['status'] != 1) exit("<div style=\"padding:50px\">".lang('my-12')."</div>");
		
		if (IS_POST) {
			$price = abs((float)$this->input->post('price'));
			if ($price - $data['price'] != 0) {
				$this->order_model->link->where('id', $id)->update($this->order_model->tablename, array('price' => $price));
				// 改价通知
				$this->member_model->add_notice($data['uid'], 3, dr_lang('my-39', $this->member['username'], $id, $data['price'], $price));
			}
			exit(dr_json(1, 1, 1));
		}
		
		$this->template->assign('price', $data['price']);
		$this->template->display('order_price.html');
	}
	
	// 订单详情
	protected function _member_show() {
	
		$id = (int)$this->input->get('id');
		$data = $this->order_model->get_order($id);
		if (!$data) $this->member_msg(lang('my-10'));
		
		// 订单信息只能相关的卖家与买家才能查看
		if ($data['selluid'] != $this->uid && $data['uid'] != $this->uid) $this->member_msg(lang('my-11'));
		
		switch ($data['shipping']) {
			case 1:
				$shipping = lang('my-16');
				break;
			case 2:
				$shipping = lang('my-17');
				break;
			case 3:
				$shipping = lang('my-18');
				break;
			default:
				$shipping = lang('my-19');
				break;
		}
		
		$this->template->assign(array(
			'id' => $id,
			'data' => $data,
			'type' => $data['selluid'] == $this->uid ? 1 : 0,
			'back' => $data['selluid'] == $this->uid ? dr_url(APP_DIR.'/order/sell') : dr_url(APP_DIR.'/order/index'),
			'meta_name' => lang('my-20'),
		));
		$this->template->display('order_show.html');
	}
	
	// 订单商品是否评价
	protected function _member_isreview() {
		
		$fid = $this->input->get('fid');
		$oid = (int)$this->input->get('oid');
		$iid = (int)$this->input->get('iid');
		
		$data = $this->order_model
					 ->link
					 ->where('oid', $oid)
					 ->where('uid', $this->uid)
					 ->select('review,id')
					 ->limit(1)
					 ->get($this->order_model->indexname)
					 ->row_array();
		if (!$data) exit('');
		
		$html = '<a href="'.dr_member_url(APP_DIR.'/order/review', array('id'=>$oid)).'">'.($data['review'] ? lang('my-21') : lang('my-22')).'</a>';
		echo 'document.write(\'' . $html . '\');';
		$this->output->enable_profiler(FALSE);
	}
	
	// 订单商品评价
	protected function _member_review() {
		
		$id = (int)$this->input->get('id');
		$data = $this->order_model->get_item_review($id);
		if (!$data) $this->msg(lang('my-10')); // 订单不存在
		
		$this->config->load('setting');
		$review = $this->config->item('review'); // 配置文件中的点评选项
		
		$error = '';
		if (IS_POST) {
			$post = $this->input->post('data', TRUE);
			foreach ($data as $t) {
				if ($post[$t['key']]['content']) {
					$st = round(10/5); // 十分制
					$avgsort = 0; // 评价总分
					foreach ($review as $rid => $name) {
						$post[$t['key']]['value'][$rid] = min(5, (int)$post[$t['key']]['value'][$rid]);
						if (!$post[$t['key']]['value'][$rid]) {
							$error = dr_lang('my-23', $name);
							break;
						}
						$avgsort+= round($post[$t['key']]['value'][$rid] / 1 * $st, 1);
					}
					if (!$error) {
						$avgsort = round($avgsort/count($review), 1); // 评价总分
						$this->order_model->link->replace($this->order_model->reviewname, array(
							'id' => $t['index'],
							'uid' => $this->uid,
							'iid' => $t['iid'],
							'item' => dr_array2string($t['item']),
							'value' => dr_array2string($post[$t['key']]['value']),
							'author' => $this->member['username'],
							'avgsort' => $avgsort,
							'content' => $post[$t['key']]['content'],
							'inputtime' => SYS_TIME,
						));
						// 更新索引表的标示
						$this->order_model->link
										  ->where('id', $t['index'])
										  ->update($this->order_model->indexname, array('review' => 1));
						// 统计商品的总分
						$this->order_model->update_review($t['iid']);
						$this->msg(lang('my-24'), dr_url(APP_DIR.'/order/review', array('id' => $id)), 1);
					}
				} else {
					$error = lang('my-25');
				}
			}
		}
		
		$this->template->assign(array(
			'id' => $id,
			'data' => $data,
			'back' => dr_url(APP_DIR.'/order/index'),
			'error' => $error,
			'review' => $review,
			'meta_name' => lang('my-26')
		));
		$this->template->display('order_review.html');
	}
	
}