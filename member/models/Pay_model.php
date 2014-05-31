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
	
class Pay_model extends CI_Model{

	public $cache_file;
    
	/**
	 * 初始化
	 */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 条件查询
	 *
	 * @param	object	$select	查询对象
	 * @param	array	$param	条件参数
	 * @return	array	
	 */
	private function _card_where(&$select, $param) {
	
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
			if ($data['card']) {
				$select->where('card', $data['card']);
			}
			if (strlen($data['status']) > 0 && !$data['status']) {
				$select->where('uid=0');
			} elseif ($data['status']) {
				$select->where('uid>0');
			}
			if ($data['username']) {
				$select->where('username', $data['username']);
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
	public function card_limit_page($param, $page, $total) {
        
		if (!$total) {
			$select	= $this->db->select('count(*) as total');
			$this->_card_where($select, $param);
			$data = $select->get('member_paycard')->row_array();
			unset($select);
			$total = (int)$data['total'];
			if (!$total) return array(array(), array('total' => 0));
		}
		
		$select	= $this->db->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1));
		$_param	= $this->_card_where($select, $param);
		$data = $select->order_by('inputtime DESC')
					   ->get('member_paycard')
					   ->result_array();
		$_param['total'] = $total;
		
		return array($data, $_param);
	}
	
	/*
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
			if (isset($data['start']) && $data['start'] && $data['start'] != $data['end']) {
				$select->where('inputtime BETWEEN '.$data['start'].' AND '. $data['end']);
			}
			if (strlen($data['status']) > 0) {
				$select->where('status', (int)$data['status']);
			}
		}
		
		$select->where('uid', $param['uid']);
		$_param['uid'] = $data['uid'];
		
		return $_param;
	}
	
	/*
	 * 数据分页显示
	 *
	 * @param	array	$param	条件参数
	 * @param	intval	$page	页数
	 * @param	intval	$total	总数据
	 * @return	array	
	 */
	public function limit_page($param, $page, $total) {
        $tableid = (int)substr((string)$param['uid'], -1, 1);
		if (!$total) {
			$select	= $this->db->select('count(*) as total');
			$this->_where($select, $param);
			$data = $select->get('member_paylog_'.$tableid)->row_array();
			unset($select);
			$total = (int)$data['total'];
			if (!$total) return array(array(), array('total' => 0));
		}
		
		$select	= $this->db->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1));
		$_param	= $this->_where($select, $param);
		$data = $select->order_by('inputtime DESC')
					   ->get('member_paylog_'.$tableid)
					   ->result_array();
		$_param['total'] = $total;
		
		return array($data, $_param);
	}
	
	// 购物消费
	public function add_for_buy($money, $order) {
		
			if (!$money || !$order) return FALSE;
		
			// 将变动金额冻结
			$this->db
				 ->where('uid', $this->uid)
				 ->set('money', 'money-'.$money, FALSE)
				 ->set('freeze', 'freeze+'.$money, FALSE)
				 ->update($this->db->dbprefix('member'));
			
			$ids = explode(',', $order);
			$note = array();
			foreach ($ids as $id) {
				$note[] = '<a href="'.MEMBER_URL.'index.php?s='.APP_DIR.'&c=order&m=show&id='.$id.'" target="_blank">'.$id.'</a>';
			}
			$note = implode('&nbsp;&nbsp;&nbsp;', $note);
			// 更新记录表 
			$this->db->insert('member_paylog_'.$this->member['tableid'], array(
				'uid' => $this->uid,
				'value' => -1 * $money,
				'module' => APP_DIR,
				'order' => ','.$order.',',
				'type' => 0,
				'status' => 1,
				'note' => 'lang,m-209,'.$this->module['name'].','.$note,
				'inputtime' => SYS_TIME
			));
			
			return TRUE;
	}
	
	// 充值
	public function add($uid, $value, $note) {
	
		if (!$uid || !$value) return NULL;
		
		// 更新RMB
		$this->db 
			 ->set('money', $value > 0 ? 'money+'.$value : 'money-'.abs($value), FALSE)
			 ->where('uid', $uid)
			 ->update($this->db->dbprefix('member'));
			 
		// 更新记录表 
		$this->db->insert('member_paylog_'.(int)substr((string)$uid, -1, 1), array(
			'uid' => $uid,
			'type' => 0,
			'note' => $note,
			'value' => $value,
			'order' => 0,
			'status' => 1,
			'module' => '',
			'inputtime' => SYS_TIME,
		));
	}
	
	// 卡密充值
	public function add_for_card($id, $money, $card) {
		
			if (!$id || $money < 0) return NULL;
		
			// 更新RMB
			$this->db
				 ->where('uid', $this->uid)
				 ->set('money', 'money+'.$money, FALSE)
				 ->update('member');
			
			// 更新记录表 
			$this->db->insert('member_paylog_'.$this->member['tableid'], array(
				'uid' => $this->uid,
				'type' => 0,
				'note' => 'lang,m-174,'.$card,
				'order' => 0,
				'value' => $money,
				'module' => '',
				'status' => 1,
				'inputtime' => SYS_TIME
			));
			
			// 更新卡密状态
			$this->db->where('id', $id)->update('member_paycard', array(
				'uid' => $this->uid,
				'usetime' => SYS_TIME,
				'username' => $this->member['username'],
			));
			
			return $money;
	}
	
	// 生成充值卡
	public function card($money, $endtime) {
		
		if (!$money || !$endtime) return NULL;
		
		mt_srand((double)microtime() * 1000000 );
		$data = array(
			'uid' => 0,
			'card' => date('Ys').strtoupper(substr(md5(mt_rand(100000, 999999)), 1, 8)).mt_rand(100000, 999999),
			'money' => $money,
			'usetime' => 0,
			'endtime' => $endtime,
			'username' => '',
			'password' => mt_rand(100000, 999999),
			'inputtime' => SYS_TIME,
		);
		
		return $this->db->insert('member_paycard', $data) ? $data : NULL;
	}
	
	// 支付成功，更改状态
	public function pay_success($sn, $money, $note = '') {
		
		list($id, $uid) = explode(',', $sn);
		if (!$id || !$uid) return NULL;
		
		// 查询支付记录 
		$tableid = (int)substr((string)$uid, -1, 1);
		$data = $this->db
					 ->where('id', $id)
					 ->limit(1)
					 ->get('member_paylog_'.$tableid)
					 ->row_array();
		if (!$data) return NULL;
		if ($data['status']) return $data['module'];
		
		// 标示成功
		$this->db->where('id', $id)->update('member_paylog_'.$tableid, array('status' => 1, 'note' => $note));
		
		if (!$data['module']) {
			// 如果是网银充值就直接更新金额
			$this->db
				 ->where('uid', $uid)
				 ->set('money', 'money+'.$money, FALSE)
				 ->update('member');
			return NULL;
		}
		// 订单直接付款
		
		return $data['module'];
	}
	
	// 在线充值
	public function add_for_online($pay, $money, $module = '', $order = 0) {
	
		if (!$pay || $money < 0) return NULL;
		
		// 更新记录表 
		$this->db->insert('member_paylog_'.$this->member['tableid'], array(
			'uid' => $this->uid,
			'note' => '',
			'type' => $pay,
			'value' => $money,
			'order' => $order,
			'status' => 0,
			'module' => $module,
			'inputtime' => SYS_TIME
		));
		
		$id = $this->db->insert_id();
		if (!$id) return NULL;
		$title = $order ? dr_lang('m-180', $order) : lang('m-179');
		
		return method_exists($this, '_get_'.$pay) ? call_user_func_array(array($this, '_get_'.$pay), array($id, $money, $title)) : '';
	}
	
	// 在线付款
	public function pay_for_online($id) {
	
		if (!$id) return NULL;
		
		// 查询支付记录 
		$data = $this->db
					 ->where('id', $id)
					 ->where('uid', $this->uid)
					 ->where('status', 0)
					 ->select('value,type,order')
					 ->limit(1)
					 ->get('member_paylog_'.$this->member['tableid'])
					 ->row_array();
		if (!$data) return NULL;
		
		// 判断订单是否支付过，否则作废
		if ($data['order']) {
			$title = dr_lang('m-180', $order);
		} else {
			$title = lang('m-179');
		}
		
		return method_exists($this, '_get_'.$data['type']) ? call_user_func_array(array($this, '_get_'.$data['type']), array($id, $data['value'], $title)) : '';
	}
	
	// 财付通接口
	private function _get_tenpay($id, $money, $title) {
	
		require APPPATH.'pay/tenpay/classes/RequestHandler.class.php';
		$pay = $this->ci->get_cache('member', 'setting', 'pay', 'tenpay');
		
		/* 创建支付请求对象 */
		$reqHandler = new RequestHandler();
		$reqHandler->init();
		$reqHandler->setKey($pay['key']);
		$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");
		$return_url = SITE_URL.'member/pay/tenpay/return_url.php';
		$notify_url = SITE_URL.'member/pay/tenpay/notify_url.php';
		
		//----------------------------------------
		//设置支付参数 
		//----------------------------------------
		$reqHandler->setParameter("partner", $pay['id']);
		$reqHandler->setParameter("out_trade_no", $id.','.$this->uid);
		$reqHandler->setParameter("total_fee", $money * 100);  //总金额,单位分，所有扩大100倍
		$reqHandler->setParameter("return_url",  $return_url);
		$reqHandler->setParameter("notify_url", $notify_url);
		$reqHandler->setParameter("body", dr_lang('m-178', $this->member['username'], $id));
		$reqHandler->setParameter("bank_type", "DEFAULT"); //银行类型，默认为财付通
		
		//用户ip
		$reqHandler->setParameter("spbill_create_ip", $this->input->ip_address());//客户端IP
		$reqHandler->setParameter("fee_type", "1"); //币种
		$reqHandler->setParameter("subject", $title); //商品名称，（中介交易时必填）
		
		//系统可选参数
		$reqHandler->setParameter("sign_type", "MD5"); //签名方式，默认为MD5，可选RSA
		$reqHandler->setParameter("service_version", "1.0"); //接口版本号
		$reqHandler->setParameter("input_charset", "UTF-8"); //字符集
		$reqHandler->setParameter("sign_key_index", "1"); //密钥序号
		
		//业务可选参数
		$reqHandler->setParameter("attach", ""); //附件数据，原样返回就可以了
		$reqHandler->setParameter("product_fee", ""); //商品费用
		$reqHandler->setParameter("transport_fee", "0"); //物流费用
		$reqHandler->setParameter("time_start", date("YmdHis")); //订单生成时间
		$reqHandler->setParameter("time_expire", ""); //订单失效时间
		$reqHandler->setParameter("buyer_id", ""); //买方财付通帐号
		$reqHandler->setParameter("goods_tag", ""); //商品标记
		$reqHandler->setParameter("trade_mode", "1"); //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
		$reqHandler->setParameter("transport_desc", ""); //物流说明
		$reqHandler->setParameter("trans_type", "1"); //交易类型
		$reqHandler->setParameter("agentid", ""); //平台ID
		$reqHandler->setParameter("agent_type", ""); //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
		$reqHandler->setParameter("seller_id", "");
		
		//请求的URL
		return $reqUrl = $reqHandler->getRequestURL();
	}
	
	// 支付宝接口
	private function _get_alipay($id, $money, $title) {
	
		$pay = $this->ci->get_cache('member', 'setting', 'pay', 'tenpay');
	
		$aliapy_config['key'] = $pay['key'];
		$aliapy_config['partner'] = $pay['id'];
		$aliapy_config['seller_email'] = $pay['username'];
		$aliapy_config['return_url'] = SITE_URL.'member/pay/alipay/return_url.php';
		$aliapy_config['notify_url'] = SITE_URL.'member/pay/alipay/notify_url.php';
		$aliapy_config['sign_type'] = 'MD5';
		$aliapy_config['input_charset']= 'utf-8';
		$aliapy_config['transport'] = 'http';
		require APPPATH.'pay/alipay/alipay_submit.class.php';
		require APPPATH.'pay/alipay/alipay_service.class.php';
		
		/**************************请求参数**************************/
		$out_trade_no = $id.','.$this->uid;
		$subject = $title;
		$body = dr_lang('m-178', $this->member['username'], $id);
		$total_fee = $money;
		//构造要请求的参数数组
		$parameter = array(
				'service'			=> 'create_direct_pay_by_user',
				'payment_type'		=> '1',
				
				'partner'			=> trim($aliapy_config['partner']),
				'_input_charset'	=> trim(strtolower($aliapy_config['input_charset'])),
				'seller_email'		=> trim($aliapy_config['seller_email']),
				'return_url'		=> trim($aliapy_config['return_url']),
				'notify_url'		=> trim($aliapy_config['notify_url']),
				
				'out_trade_no'		=> $out_trade_no,
				'subject'			=> $subject,
				'body'				=> $body,
				'total_fee'			=> $total_fee,
		);
		
		//构造即时到帐接口
		$alipayService = new AlipayService($aliapy_config);
		$html_text = $alipayService->create_direct_pay_by_user($parameter);
		
		return $html_text;
	}
	
	// 网银在线接口
	private function _get_chinabank($id, $money, $title) {
	
		$id = $id.','.$this->uid; // 支付订单id必须由订单id+会员id组成
		$url = SITE_URL.'member/pay/chinabank/return_url.php';
		$pay = $this->ci->get_cache('member', 'setting', 'pay', 'chinabank');
		$md5 = strtoupper(md5($money.'CNY'.$id.$pay['id'].$url.$pay['key']));
		
		$html = '';
		$html.= '<form method="post" name="E_FORM" action="https://pay3.chinabank.com.cn/PayGate">';
		$html.= '<input type="hidden" name="v_mid" value="'.$pay['id'].'">';
		$html.= '<input type="hidden" name="v_oid" value="'.$id.'">';
		$html.= '<input type="hidden" name="v_amount" value="'.$money.'">';
		$html.= '<input type="hidden" name="v_moneytype" value="CNY">';
		$html.= '<input type="hidden" name="v_url" value="'.$url.'">';
		$html.= '<input type="hidden" name="v_md5info" value="'.$md5.'">';
		$html.= '<input type="hidden" name="remark1" value="'.$title.'">';
		$html.= '</form>';
		$html.= '<script>document.forms["E_FORM"].submit();</script>';
		
		return $html;
	}
}