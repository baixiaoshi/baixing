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
	
require FCPATH.'dayrui/core/D_Common.php';

class M_Controller extends D_Common {

    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
		if (defined('DR_PAY_ID') && DR_PAY_ID) {
			$this->load->model('pay_model');
			call_user_func(array($this, 'return_'.DR_PAY_ID));
		} else {
			$this->template->assign('newpm', $this->db->where('uid', (int)$this->uid)->get('newpm')->row_array());
		}
    }
	
	/**
     * 空间模型管理
     */
	protected function space_content_index() {
	
		$this->_is_space();
		
		$mid = (int)str_replace('space', '', $this->router->class);
		$model = $this->get_cache('space-model', $mid);
		if (!$model) $this->member_msg(lang('m-290'));
		if (!$model['setting'][$this->markrule]['use']) $this->member_msg(lang('m-307'));
		
		$table = $this->db->dbprefix('space_'.$model['table']);
		
		if (IS_POST && $this->input->post('action') == 'delete') {
		
			$id = (int)$this->input->post('id');
			$this->db->where('id', $id)->delete($table);
			
			$this->load->model('attachment_model');
			$this->attachment_model->delete_for_table($table.'-'.$id); // 删除附件
			
			// 积分处理
			$experience = (int)$model['setting'][$this->markrule]['experience'];
			if ($experience > 0) $this->member_model->update_score(0, $this->uid, -$experience, '', "delete");
			// 虚拟币处理
			$score = (int)$model['setting'][$this->markrule]['score'];
			if ($score > 0) $this->member_model->update_score(1, $this->uid, -$score, '', "delete");
			
			exit(dr_json(1, lang('000'), $id));
			
		} elseif (IS_POST && $this->input->post('action') == 'remove') {
		
			$ids = $this->input->post('ids', TRUE);
			if (!$ids) exit(dr_json(0, lang('019')));
			
			$catid = (int)$this->input->post('catid');
			if ($catid) {
				$this->db
					 ->where_in('id', $ids)
					 ->update($table, array(
						'catid' => $catid
					 ));
			} else {
				exit(dr_json(0, lang('m-300')));
			}
			
			exit(dr_json(1, lang('000')));
		}
		
		$this->load->model('space_category_model');
		$category = $this->space_category_model->get_data($mid);
		
		$this->db->where('uid', (int)$this->uid);
		$kw = $this->input->get('kw', TRUE);
		$order = $this->input->get('order', TRUE);
		if ($kw) $this->db->like('title', $kw);
		$this->db->order_by($order ? $order : 'updatetime DESC');
		
		if ($this->input->get('action') == 'search') {
			// ajax搜索数据
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->db
						 ->limit($this->pagesize, $this->pagesize * ($page - 1))
						 ->get($table)
						 ->result_array();
			if (!$data) exit('null');
			$this->template->assign(array(
				'kw' => $kw,
                'list' => $data,
				'category' => $category,
            ));
			$this->template->display(is_file(FCPATH.'member/templates/'.MEMBER_TEMPLATE.'/space_'.$model['table'].'_data.html') ? 'space_'.$model['table'].'_data.html' : 'space_content_data.html');
		} else {
			$this->template->assign(array(
				'kw' => $kw,
				'mid' => $mid,
				'list' => $this->db
							   ->limit($this->pagesize)
							   ->get($table)
							   ->result_array(),
				'select' => $this->select_space_category($category, 0, 'name=\'catid\'', '  --  ', 1),
				'dclass' => $this->router->class,
				'category' => $category,
				'searchurl' => "index.php?c={$this->router->class}&m=index&action=search"
			));
			$this->template->display(is_file(FCPATH.'member/templates/'.MEMBER_TEMPLATE.'/space_'.$model['table'].'_index.html') ? 'space_'.$model['table'].'_index.html' : 'space_content_index.html');
		}
	}

	/**
     * 添加空间模型内容
     */
	protected function space_content_add() {
	
		$this->_is_space();
		
		$mid = (int)str_replace('space', '', $this->router->class);
		$model = $this->get_cache('space-model', $mid);
		if (!$model) $this->member_msg(lang('m-290'));
		if (!$model['setting'][$this->markrule]['use']) $this->member_msg(lang('m-307'));
		
		$this->load->model('space_content_model');
		$this->load->model('space_category_model');
		$category = $this->space_category_model->get_data($mid);
		$this->space_content_model->tablename = $this->db->dbprefix('space_'.$model['table']);
		
		// 虚拟币检查
		$score = (int)$model['setting'][$this->markrule]['score'];
		if ($score && $score + $this->member['score'] < 0) $this->member_msg(dr_lang('m-302', abs($score), $this->member['score']));
		
		if (IS_POST) {
			
			// 栏目参数
			$catid = (int)$this->input->post('catid');
			
			// 设置uid便于校验处理
			$_POST['data']['uid'] = $this->uid;
			$_POST['data']['author'] = $this->member['username'];
			$_POST['data']['inputtime'] = $_POST['data']['updatetime'] = SYS_TIME;
			$data = $this->validate_filter($model['field']);
			
			// 验证出错信息
			if (isset($data['error'])) {
				$error = $data;
				$data = $this->input->post('data', TRUE);
			} elseif (!$catid) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'catid', 'msg' => lang('m-300'));
			} elseif ($category[$catid]['child'] || $category[$catid]['modelid'] != $mid) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'catid', 'msg' => lang('m-301'));
			} else {
			
				// 设定文档默认值
				$data[1]['uid'] = $this->uid;
				$data[1]['catid'] = $catid;
				$data[1]['status'] = (int)$model['setting'][$this->markrule]['verify'] ? 0 : 1;
				$data[1]['author'] = $this->member['username'];
				$data[1]['inputtime'] = $data[1]['updatetime'] = SYS_TIME;
				$data[1]['displayorder'] = $data[1]['hits'] = 0;
				
				// 发布文档
				if (($id = $this->space_content_model->add($data[1])) != FALSE) {
					$mark = $this->space_content_model->tablename.'-'.$id;
					if ($data[1]['status']) {
						// 积分处理
						$experience = (int)$model['setting'][$this->markrule]['experience'];
						if ($experience) $this->member_model->update_score(0, $this->uid, $experience, $mark, "lang,m-151,{$category[$catid]['name']}", 1);
						// 虚拟币处理
						$score = (int)$model['setting'][$this->markrule]['score'];
						if ($score) $this->member_model->update_score(1, $this->uid, $score, $mark, "lang,m-151,{$category[$catid]['name']}", 1);
					}
					// 附件归档到文档
					$this->attachment_handle($this->uid, $mark, $model['field']);
					$this->attachment_replace($this->uid, $id, $this->space_content_model->tablename);
					$this->member_msg(lang('000'), dr_member_url($this->router->class.'/index'), 1);
				}
			}
			
			if (IS_AJAX) exit(dr_json(0, $error['msg'], $error['error']));
			
			$data = $data[1];
			unset($data['id']);
		}
		
		$this->template->assign(array(
			'purl' => dr_url($this->router->class.'/add'),
			'error' => $error,
			'verify' => 0,
			'select' => $this->select_space_category($category, (int)$data['catid'], 'name=\'catid\'', NULL, 1),
			'listurl' => dr_url($this->router->class.'/index'),
			'myfield' => $this->field_input($model['field'], $data, TRUE),
			'meta_name' => lang('m-299'),
			'model_name' => $model['name'],
		));
		$this->template->display(is_file(FCPATH.'member/templates/'.MEMBER_TEMPLATE.'/space_'.$model['table'].'_add.html') ? 'space_'.$model['table'].'_add.html' : 'space_content_add.html');
	}
	
	/**
     * 修改空间模型内容
     */
	protected function space_content_edit() {
		
		$this->_is_space();
		
		$id = (int)$this->input->get('id');
		$mid = (int)str_replace('space', '', $this->router->class);
		$model = $this->get_cache('space-model', $mid);
		if (!$model) $this->member_msg(lang('m-290'));
		if (!$model['setting'][$this->markrule]['use']) $this->member_msg(lang('m-307'));
		
		$this->load->model('space_category_model');
		$this->load->model('space_content_model');
		$category = $this->space_category_model->get_data($mid);
		$this->space_content_model->tablename = $this->db->dbprefix('space_'.$model['table']);
		$data = $this->space_content_model->get($this->uid, $id);
		if (!$data) $this->member_msg(lang('m-303'));
		
		if (IS_POST) {
			
			// 栏目参数
			$catid = (int)$this->input->post('catid');
			
			// 设置uid便于校验处理
			$_POST['data']['updatetime'] = SYS_TIME;
			$post = $this->validate_filter($model['field']);
			
			// 验证出错信息
			if (isset($post['error'])) {
				$error = $post;
				$data = $this->input->post('data', TRUE);
			} elseif (!$catid) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'catid', 'msg' => lang('m-300'));
			} elseif ($category[$catid]['child'] || $category[$catid]['modelid'] != $mid) {
				$data = $this->input->post('data', TRUE);
				$error = array('error' => 'catid', 'msg' => lang('m-301'));
			} else {
			
				// 设定文档默认值
				$post[1]['catid'] = $catid;
				$post[1]['status'] = (int)$model['setting'][$this->markrule]['verify'] ? 0 : 1;
				$post[1]['updatetime'] = SYS_TIME;
				
				// 修改文档
				if (($id = $this->space_content_model->edit($id, $data['uid'], $post[1])) != FALSE) {
					$this->attachment_handle($this->uid, $this->space_content_model->tablename.'-'.$id, $model['field'], $data, $post[1]['status'] ? TRUE : FALSE);
					$this->member_msg(lang('000'), dr_member_url($this->router->class.'/index'), 1);
				}
			}
			
			if (IS_AJAX) exit(dr_json(0, $error['msg'], $error['error']));
			
			$data = $data[1];
			unset($data['id']);
		}
		
		$this->template->assign(array(
			'purl' => dr_url($this->router->class.'/edit', array('id'=>$id)),
			'error' => $error,
			'verify' => 0,
			'select' => $this->select_space_category($category, (int)$data['catid'], 'name=\'catid\'', NULL, 1),
			'listurl' => dr_url($this->router->class.'/index'),
			'myfield' => $this->field_input($model['field'], $data, TRUE),
			'meta_name' => lang('m-299'),
			'model_name' => $model['name'],
		));
		$this->template->display(is_file(FCPATH.'member/templates/'.MEMBER_TEMPLATE.'/space_'.$model['table'].'_add.html') ? 'space_'.$model['table'].'_add.html' : 'space_content_add.html');
	}
	
	/**
     * 判断当前空间是否可以使用
     */
	protected function _is_space($return = FALSE) {
	
		if (!MEMBER_OPEN_SPACE) $this->member_msg(lang('m-111'));
	
		// 判断会员组是否可以使用
		if (!$this->member['group']['allowspace']) {
			if ($return) {
				return FALSE;
			} else {
				$this->member_msg(lang('m-342'));
			}
		}
		
		// 空间状态判断
		$data = $this->db
					 ->select('status')
					 ->where('uid', (int)$this->uid)
					 ->limit(1)
					 ->get('space')
					 ->row_array();
					 
		if (!$data) {
			if ($return) {
				return FALSE;
			} else {
				$this->member_msg(lang('m-234'));
			}
		}
		
		if (!$data['status']) {
			if ($return) {
				return FALSE;
			} else {
				$this->member_msg(lang('m-235'));
			}
		}
	}
	
	/**
	 * 栏目选择
	 *
	 * @param array			$data		栏目数据
	 * @param intval/array	$id			被选中的ID，多选是可以是数组
	 * @param string		$str		属性
	 * @param string		$default	默认选项
	 * @param intval		$onlysub	只可选择子栏目
	 * @param intval		$is_push	是否验证权限
	 * @return string
	 */
	public function select_space_category($data, $id = 0, $str = '', $default = ' -- ', $onlysub = 0, $is_push = 0) {
		
		$cache = md5(dr_array2string($data).$id.$str.$default.$onlysub.$is_push);
		if ($cache_data = $this->cache->file->get($cache)) return $cache_data;
		
		$tree = array();
		$string = '<select '.$str.'>';
		
		if ($default) $string .= "<option value='0'>$default</option>";
		
		if (is_array($data)) {
		
			foreach($data as $t) {
			
				// 选中操作
				$t['selected'] = '';
				if (is_array($id)) {
					$t['selected'] = in_array($t['id'], $id) ? 'selected' : '';
				} elseif(is_numeric($id)) {
					$t['selected'] = $id == $t['id'] ? 'selected' : '';
				}
				
				// 是否可选子栏目
				$t['html_disabled'] = !empty($onlysub) && $t['child'] != 0 ? 1 : 0;
				
				$tree[$t['id']] = $t;
			}
		}
		
		$str = "<option value='\$id' \$selected>\$spacer \$name</option>";
		$str2 = "<optgroup label='\$spacer \$name'></optgroup>";
		
		$this->load->library('dtree');
		$this->dtree->init($tree);
		
		$string .= $this->dtree->get_tree_category(0, $str, $str2);
		$string .= '</select>';
		
		$this->cache->file->save($cache, $string, 7200);
		
		return $string;
	}
	
	// 财付通付款返回
	private function return_tenpay() {
		
		$pay = $this->get_cache('member', 'setting', 'pay', 'tenpay');
		
		if (DR_PAY_FILE == 'return') {
			
			require APPPATH.'pay/tenpay/classes/ResponseHandler.class.php';
			require APPPATH.'pay/tenpay/classes/function.php';
			
			/* 创建支付应答对象 */
			$resHandler = new ResponseHandler();
			$resHandler->setKey($pay['key']);

			//判断签名
			if($resHandler->isTenpaySign()) {
				//通知id
				$notify_id = $resHandler->getParameter("notify_id");
				//商户订单号
				$out_trade_no = $resHandler->getParameter("out_trade_no");
				//财付通订单号
				$transaction_id = $resHandler->getParameter("transaction_id");
				//金额,以分为单位
				$total_fee = $resHandler->getParameter("total_fee");
				//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
				$discount = $resHandler->getParameter("discount");
				//支付结果
				$trade_state = $resHandler->getParameter("trade_state");
				//交易模式,1即时到账
				$trade_mode = $resHandler->getParameter("trade_mode");
				
				if ("1" == $trade_mode ) {
					if ( "0" == $trade_state){ 
						//------------------------------
						//处理业务开始
						//------------------------------
						$money = number_format(($total_fee / 100), 2, '.', '');
						$module = $this->pay_model->pay_success($out_trade_no, $money, '财付通订单号：'.$transaction_id);
						//------------------------------
						//处理业务完毕
						//------------------------------	
						$url = $module ? MEMBER_URL.'index.php?s='.$module.'&c=order&m=index' : MEMBER_URL.'index.php?c=pay';
						$this->pay_msg("即时到帐支付成功($money)", $url, 1);
					} else {
						//当做不成功处理
						$this->pay_msg('即时到帐支付失败');
					}
				} else {
					// 交易模式错误，只能是即时到帐
					$this->pay_msg('交易模式错误，只能是即时到帐');
				}
			} else {
				$this->pay_msg('认证签名失败：'.$resHandler->getDebugInfo());
			}
		} else {
			
			require (APPPATH."pay/tenpay/classes/ResponseHandler.class.php");
			require (APPPATH."pay/tenpay/classes/RequestHandler.class.php");
			require (APPPATH."pay/tenpay/classes/client/ClientResponseHandler.class.php");
			require (APPPATH."pay/tenpay/classes/client/TenpayHttpClient.class.php");
			require (APPPATH."pay/tenpay/classes/function.php");

			$key = $pay['key'];
			$partner = $pay['id'];

			/* 创建支付应答对象 */
			$resHandler = new ResponseHandler();
			$resHandler->setKey($key);

			//判断签名
			if ($resHandler->isTenpaySign()) {

				//通知id
				$notify_id = $resHandler->getParameter("notify_id");

				//通过通知ID查询，确保通知来至财付通
				//创建查询请求
				$queryReq = new RequestHandler();
				$queryReq->init();
				$queryReq->setKey($key);
				$queryReq->setGateUrl("https://gw.tenpay.com/gateway/simpleverifynotifyid.xml");
				$queryReq->setParameter("partner", $partner);
				$queryReq->setParameter("notify_id", $notify_id);

				//通信对象
				$httpClient = new TenpayHttpClient();
				$httpClient->setTimeOut(5);
				//设置请求内容
				$httpClient->setReqContent($queryReq->getRequestURL());

				//后台调用
				if ($httpClient->call()) {
					//设置结果参数
					$queryRes = new ClientResponseHandler();
					$queryRes->setContent($httpClient->getResContent());
					$queryRes->setKey($key);

					if ($resHandler->getParameter("trade_mode") == "1") {
						//判断签名及结果（即时到帐）
						//只有签名正确,retcode为0，trade_state为0才是支付成功
						if ($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" && $resHandler->getParameter("trade_state") == "0") {

							//取结果参数做业务处理
							$out_trade_no = $resHandler->getParameter("out_trade_no");
							//财付通订单号
							$transaction_id = $resHandler->getParameter("transaction_id");
							//金额,以分为单位
							$total_fee = $resHandler->getParameter("total_fee");
							//如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
							$discount = $resHandler->getParameter("discount");
					
							//------------------------------
							//处理业务开始
							//------------------------------
							
							$money = number_format(($total_fee / 100), 2, '.', '');
							$this->pay_model->pay_success($out_trade_no, $money, '财付通订单号：'.$transaction_id);
							
							//------------------------------
							//处理业务完毕
							//------------------------------
							echo "success";exit;
					
						} else {
							//错误时，返回结果可能没有签名，写日志trade_state、retcode、retmsg看失败详情。
							echo "fail";exit;
						}
					}
				} else {
					//通信失败
					echo "fail";exit;
				}
			} else  {
				echo "<br/>" . "认证签名失败" . "<br/>";
				echo $resHandler->getDebugInfo() . "<br>";
			}
		}
		exit;
	}
	
	// 支付宝付款返回
	private function return_alipay() {
	
		$pay = $this->get_cache('member', 'setting', 'pay', 'tenpay');
		
		if (DR_PAY_FILE == 'return') {
			
			require APPPATH.'pay/alipay/alipay_notify.class.php';
			//合作身份者id，以2088开头的16位纯数字
			$aliapy_config['partner'] = $pay['id'];
			//安全检验码，以数字和字母组成的32位字符
			$aliapy_config['key'] = $pay['key'];
			//签约支付宝账号或卖家支付宝帐户
			$aliapy_config['seller_email'] = $pay['username'];
			//页面跳转同步通知页面路径，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
			//return_url的域名不能写成http://localhost/create_direct_pay_by_user_php_utf8/return_url.php ，否则会导致return_url执行无效
			$aliapy_config['return_url'] = SITE_URL.'member/pay/alipay/return_url.php';
			//服务器异步通知页面路径，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
			$aliapy_config['notify_url'] = SITE_URL.'member/pay/alipay/notify_url.php';
			//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
			//签名方式 不需修改
			$aliapy_config['sign_type'] = 'MD5';
			//字符编码格式 目前支持 gbk 或 utf-8
			$aliapy_config['input_charset']= 'utf-8';
			//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
			$aliapy_config['transport'] = 'http';
			//计算得出通知验证结果
			$alipayNotify = new AlipayNotify($aliapy_config);
			$verify_result = $alipayNotify->verifyReturn();
			if ($verify_result) {//验证成功
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//请在这里加上商户的业务逻辑程序代码
				//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
				//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
				$out_trade_no = $_GET['out_trade_no'];	//获取订单号
				$trade_no = $_GET['trade_no']; //获取支付宝交易号
				$total_fee = $_GET['total_fee']; //获取总价格
				if ($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
					$money = number_format($total_fee, 2, '.', '');
					$module = $this->pay_model->pay_success($out_trade_no, $money, '支付宝交易号：'.$trade_no);
					$url = $module ? MEMBER_URL.'index.php?s='.$module.'&c=order&m=index' : MEMBER_URL.'index.php?c=pay';
					$this->pay_msg("即时到帐支付成功($money)", $url, 1);
				} else {
				   $this->pay_msg("trade_status=".$_GET['trade_status']);
				}
			} else {
				//验证失败
				$this->pay_msg('验证失败');
			}
			
		} else {
			
			//合作身份者id，以2088开头的16位纯数字
			$aliapy_config['partner'] = $pay['id'];
			//安全检验码，以数字和字母组成的32位字符
			$aliapy_config['key'] = $pay['key'];
			//签约支付宝账号或卖家支付宝帐户
			$aliapy_config['seller_email'] = $pay['username'];
			//页面跳转同步通知页面路径，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
			//return_url的域名不能写成http://localhost/create_direct_pay_by_user_php_utf8/return_url.php ，否则会导致return_url执行无效
			$aliapy_config['return_url'] = SITE_URL.'member/pay/alipay/return_url.php';
			//服务器异步通知页面路径，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
			$aliapy_config['notify_url'] = SITE_URL.'member/pay/alipay/notify_url.php';
			//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
			//签名方式 不需修改
			$aliapy_config['sign_type'] = 'MD5';
			//字符编码格式 目前支持 gbk 或 utf-8
			$aliapy_config['input_charset']= 'utf-8';
			//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
			$aliapy_config['transport']    = 'http';
			require APPPATH.'pay/alipay/alipay_notify.class.php';

			//计算得出通知验证结果
			$alipayNotify = new AlipayNotify($aliapy_config);
			$verify_result = $alipayNotify->verifyNotify();

			if($verify_result) {//验证成功
				/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
				//请在这里加上商户的业务逻辑程序代
				
				//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
				//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
				$out_trade_no = $_POST['out_trade_no'];	    //获取订单号
				$trade_no = $_POST['trade_no'];	    	//获取支付宝交易号
				$total_fee = $_POST['total_fee'];			//获取总价格

				$money = number_format($total_fee, 2, '.', '');
				$orderid = $this->pay_model->pay_success($out_trade_no, $money, '支付宝交易号：'.$trade_no);
				echo "success"; //请不要修改或删除
			} else {
				//验证失败
				echo "fail";
			}
		}
		exit;
	}
	
	// 网银在线付款返回
	private function return_chinabank() {
	
		$pay = $this->get_cache('member', 'setting', 'pay', 'chinabank');
		
		if (DR_PAY_FILE == 'return') {
			$v_oid =trim($_POST['v_oid']); // 商户发送的v_oid定单编号   
			$v_pmode =trim($_POST['v_pmode']); // 支付方式（字符串）   
			$v_pstatus =trim($_POST['v_pstatus']); //  支付状态 ：20（支付成功）；30（支付失败）
			$v_pstring =trim($_POST['v_pstring']); // 支付结果信息 ： 支付完成（当v_pstatus=20时）；失败原因（当v_pstatus=30时,字符串）； 
			$v_amount =trim($_POST['v_amount']); // 订单实际支付金额
			$v_moneytype =trim($_POST['v_moneytype']); //订单实际支付币种
			$v_md5str =trim($_POST['v_md5str' ]); //拼凑后的MD5校验值  
			$md5string = strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$pay['key'])); // 重新计算md5的值
			// 判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理	
			if ($v_md5str == $md5string) { 
				if($v_pstatus == "20") {
					//支付成功，可进行逻辑处理！
					//商户系统的逻辑处理（例如判断金额，判断支付状态，更新订单状态等等）......
					$module = $this->pay_model->pay_success($v_oid, $v_amount, '银行订单编号：'.$_POST['v_idx']);
					$url = $module ? MEMBER_URL.'index.php?s='.$module.'&c=order&m=index' : MEMBER_URL.'index.php?c=pay';
					$this->pay_msg("网银在线充值成功($v_amount)", $url, 1);
				} else {
					$this->pay_msg("支付失败");
				}
			} else {
				$this->pay_msg("校验失败,数据可疑");
			}
		}
	}
	
	/**
     * 付款提示消息显示
	 *
	 * @param	string	$msg	提示信息
	 * @param	string	$url	转向地址
	 * @param	intval	$mark	标示符号1：成功；0：失败；2：等待
     * @return  void
     */
	private function pay_msg($msg, $url = '', $mark = 0) {
		$this->template->assign(array(
			'url' => $url,
			'msg' => $msg,
			'mark' => $mark,
		));
		$this->template->display('pay_msg.html');
		exit;
	}
	
	/**
	 * 验证会员名称
	 *
	 * @param	string	$username
	 * @return	NULL
	 */
	protected function is_username($username) {
		
		if (!$username) return lang('m-008');
		
		$setting = $this->get_cache('member', 'setting');
		if ($setting['regnamerule'] && !preg_match($setting['regnamerule'], $username)) return lang('m-008');
		if ($setting['regnotallow'] && @in_array($username, explode(',', $setting['regnotallow']))) return lang('m-010');
		
		return NULL;
	}
	
	/**
	 * 验证Email
	 *
	 * @param	string	$email
	 * @return	NULL
	 */
	protected function is_email($email) {
		
		if (!$email) return lang('m-011');
		
		if (!preg_match('/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/', $email)) return lang('m-011');
		
		return NULL;
	}
	
	/**
	 * 本地会员空间
	 *
	 * @return	array
	 */
	protected function get_local_space() {
		
		$this->load->helper('directory');
		$file = directory_map(FCPATH.'member/templates/', 1);
		$data = array();
		if ($file) {
			foreach ($file as $t) {
				$t = basename($t);
				$config = FCPATH.'member/templates/'.$t.'/config.php';
				if (!in_array($t, array('admin', 'member')) && is_file($config)) {
					$data[$t] = require $config;
				}
			}
		}
		return $data;
	}
}