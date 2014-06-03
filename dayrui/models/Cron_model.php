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
	
class Cron_model extends CI_Model {

	/**
	 * 任务队列模型类
	 */
    public function __construct() {
        parent::__construct();
    }
	
	/**
	 * 队列类型
	 */
	public function get_type() {
		return array(
			2 => lang('192'),
			4 => lang('338'),
			1 => lang('109'),
			3 => lang('324'),
		);
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
			if ($data['type']) {
				$select->where('type', (int)$data['type']);
			}
		}
		
		return $_param;
	}
	
	/**
	 * 数据分页显示
	 *
	 * @param	string	$kw		关键字参数
	 * @param	intval	$page	页数
	 * @param	intval	$total	总数据
	 * @return	array	
	 */
	public function limit_page($param, $page, $total) {
	
		if (!$total) {
			$select	= $this->db->select('count(*) as total');
			$_param = $this->_where($select, $param);
			$data = $select->get('cron_queue')->row_array();
			unset($select);
			$total = $_param['total'] = (int)$data['total'];
			if (!$_param['total']) return array(array(), $_param);
		}
		
		$select	= $this->db->limit(SITE_ADMIN_PAGESIZE, SITE_ADMIN_PAGESIZE * ($page - 1));
		$_param = $this->_where($select, $param);
		$data = $select->order_by('id DESC')
					   ->get('cron_queue')
					   ->result_array();
					   
		$_param['total'] = (int)$total;
		
		return array($data, $_param);
	}
	
	/**
	 * 添加一个任务
	 *
	 * @param	intval	$type	类型
	 * @param	array	$value	参数值
	 * @return	bool
	 */
	public function add($type, $value) {
	
		if (!$type || !$value) return FALSE;
		
		$this->db->insert('cron_queue', array(
			'type' => $type,
			'value' => dr_array2string($value),
			'error' => '',
			'status' => 0,
			'inputtime' => SYS_TIME,
			'updatetime' => 0,
		));
		
	}
	
	/**
	 * 执行任务
	 *
	 * @param	array	$data
	 * @return	bool
	 */
	public function execute($data) {
	
		if (!$data) return FALSE;
		
		switch ($data['type']) {
		
			case 1: // 邮件发送
				$result = $this->_execute_mail($data['value']);
				break;
				
			case 2: // QQ微博分享
				$result = $this->_execute_qqshare($data['value']);
				break;
				
			case 3: // 短信发送
				$result = $this->_execute_sms($data['value']);
				break;
				
			case 4: // 新浪微博分享
				$result = $this->_execute_sinashare($data['value']);
				break;
	
		}
		
		$status = $data['status'] + 1;
		
		// 执行成功时或者失败次数超过9次时，删除任务
		if ($result === TRUE || $status >= 9) {
			$this->db->where('id', (int)$data['id'])->delete('cron_queue');
		} else {
			$this->db->where('id', (int)$data['id'])->update('cron_queue', array(
				'error' => $result,
				'status' => $status,
				'updatetime' => SYS_TIME,
			));
		}
		
		return $result;
	}
	
	/**
	 * 邮件发送
	 *
	 * @param	array	$data
	 * @return  Bool|String
	 */
	public function _execute_mail($data) {
		
		$data = dr_string2array($data);
		if (!$data) return '执行值不存在';
		
		$cache = $this->dcache->get('email');
		if (!$cache) return '貌似你还没有配置邮件服务器';
		
		$this->load->library('Dmail');
		
		foreach ($cache as $mail) {
		
			$this->dmail->set(array(
				'host' => $mail['host'],
				'user' => $mail['user'],
				'pass' => $mail['pass'],
				'port' => $mail['port'],
				'from' => $mail['user'],
			));
			
			if ($this->dmail->send($data['tomail'], $data['subject'], $data['message'])) return TRUE;
		}
		
		return $this->dmail->error();
	}
	
	/**
	 * QQ微博分享
	 *
	 * @param	array	$data
	 * @return  Bool|String
	 */
	public function _execute_qqshare($data) {
		
		$data = dr_string2array($data);
		if (!$data) return '执行值不存在';
		
		$config = require FCPATH.'config/oauth.php';
		if (!isset($config['qq']) || !isset($config['qq']['key'])) return 'QQ配置文件不正确或者Key不存在';
		
		// 查询OAuth2授权信息
		$auth = $this->db
					 ->where('uid', (int)$data['uid'])
					 ->where('oauth', 'qq')
					 ->limit(1)
					 ->get('member_oauth')
					 ->row_array();
		if (!$auth) return '会员uid:'.$data['uid'].' 授权信息不存在';
		
		$thumb = $data['thumb'];
		$content = $data['title'].' '.$data['url'];
		
		if (function_exists('curl_init')) { // curl方式
			$par = 'access_token='.$auth['access_token'].'&oauth_consumer_key='.$config['qq']['key'].'&openid='.$auth['oid'];
			$par.= '&format=xml&content='.$content;
			$headers = array();
			$pic = '';
			if ($thumb) {
				// 强图片转为临时目录
				$tmp = dr_catcher_data($thumb);
				if ($tmp) {
					$ext = trim(strrchr($thumb, '.'), '.');
					$ext = in_array($ext, array('jpg', 'png', 'gif')) ? $ext : 'jpg';
					$pic = FCPATH.'cache/attach/'.md5($thumb).'.'.$ext;
					file_put_contents($pic, $tmp);
					$headers[] = 'Expect: ';
					$tmp = array();
					foreach (explode('&', $par) as $v) {
						$t = explode('=', $v);
						$tmp[$t[0]] = $t[1];
					}
					$tmp['pic'] = '@'.$pic;
					$par = $tmp;
				}
			}
			$cur = curl_init('https://graph.qq.com/t/'.($pic ? 'add_pic_t ' : 'add_t'));
			curl_setopt($cur, CURLOPT_POST, 1);
			curl_setopt($cur, CURLOPT_POSTFIELDS, $par);
			curl_setopt($cur, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($cur, CURLOPT_HEADER, 0);
			curl_setopt($cur, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($cur, CURLOPT_RETURNTRANSFER, 1);
			if ($headers) curl_setopt($cur, CURLOPT_HTTPHEADER, $headers);
			$rec = curl_exec($cur);
			curl_close($cur);
			if ($pic) @unlink($pic);
			if (strpos($rec, '<msg>ok</msg>') === false) {
				//fail
				return $rec;
			} else {
				//success
				return TRUE;
			}
		} else {
			return 'QQ微博分享提示：服务器不支持CURL函数';
		}
	}
	
	/**
	 * 新浪微博分享
	 *
	 * @param	array	$data
	 * @return  Bool|String
	 */
	public function _execute_sinashare($data) {
	
		$data = dr_string2array($data);
		if (!$data) return '执行值不存在';
		
		$config = require FCPATH.'config/oauth.php';
		if (!isset($config['sina']) || !isset($config['sina']['key'])) return 'QQ配置文件不正确或者Key不存在';
		
		// 查询OAuth2授权信息
		$auth = $this->db
					 ->where('uid', (int)$data['uid'])
					 ->where('oauth', 'sina')
					 ->limit(1)
					 ->get('member_oauth')
					 ->row_array();
		if (!$auth) return '会员uid:'.$data['uid'].' 授权信息不存在';
		
		
		require FCPATH.'dayrui/libraries/Share/Sina.php';
		$auth = new SaeTClientV2($config['sina']['key'], $config['sina']['secret'], $auth['access_token']);
		
		if ($data['thumb']) {
			$call = $auth->upload($data['title'].' '.$data['url'], $data['thumb']);
		} else {
			$call = $auth->update($data['title'].' '.$data['url']);
		}
		
		return isset($call['id']) && $call['id'] ? TRUE : $call;
	}
	
	/**
	 * 短信发送
	 *
	 * @param	array	$data
	 * @return  Bool|String
	 */
	public function _execute_sms($data) {
		
		$data = dr_string2array($data);
		if (!$data) return '执行值不存在';
		
		$file = FCPATH.'config/sms.php';
		if (!is_file($file)) return '你都还没有配置短信账号呢';
		
		$result = $this->member_model->sendsms($data['mobile'], $data['content']);
		if ($result === FALSE) {
			 return '短信发送失败';
		} else {
			if ($result['status']) return TRUE;
			return $result['msg'];
		}
	}
}