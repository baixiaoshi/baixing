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
	
class Account extends M_Controller {
	
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }
	
	/**
     * 基本资料
     */
    public function index() {
	
		if ($this->member['groupid'] == 1) $this->member_msg(lang('m-085'));
		
		$error = NULL;
		$field = array(
			'name' => array(
				'name' => lang('m-242'),
				'ismain' => 0,
				'ismember' => 1,
				'fieldname' => 'name',
				'fieldtype' => 'Text',
				'setting' => array(
					'option' => array(
						'width' => 200,
					),
					'validate' => array(
						'xss' => 1,
						'required' => 1,
					)
				)
			),
			'phone' => array(
				'name' => lang('m-243'),
				'ismain' => 0,
				'ismember' => 1,
				'fieldname' => 'phone',
				'fieldtype' => 'Text',
				'setting' => array(
					'option' => array(
						'width' => 200,
					),
					'validate' => array(
						'xss' => 1,
						'check' => '_check_phone',
						'required' => 1,
					)
				)
			),
		);
		
		$MEMBER = $this->get_cache('member');
		// 开启手机认证
		if ($MEMBER['setting']['ismobile']) {
			if ($this->member['ismobile'] && $this->member['phone']) {
				$field['phone']['setting']['validate']['isedit'] = 1;
				$field['phone']['setting']['validate']['formattr'] = '';
			} else {
				$field['check'] = array(
					'name' => lang('m-086'),
					'ismain' => 0,
					'ismember' => 1,
					'fieldname' => 'check',
					'fieldtype' => 'Text',
					'setting' => array(
						'option' => array(
							'width' => 117,
						),
						'validate' => array(
							'xss' => 1,
							'formattr' => ' /><input type="button" class="button" value="'.lang('m-086').'" onclick="dr_send_sms()" name="sms" ',
						)
					)
				);
			}	
		}
		// 可用字段
		if ($MEMBER['field'] && $MEMBER['group'][$this->member['groupid']]['allowfield']) {
			foreach ($MEMBER['field'] as $t) {
				if (in_array($t['fieldname'], $MEMBER['group'][$this->member['groupid']]['allowfield'])) {
					$field[] = $t;
				}
			}
		}
		// 是否是快捷登录注册
		$oauth = !$MEMBER['setting']['regoauth'] && $this->member['groupid'] == 2 && !$this->member['password'] ? TRUE : FALSE;
		
		if (IS_POST && $field) {
		
			$data = $this->validate_filter($field, $this->member);
			
			// 快捷登陆组完善资料
			if ($oauth) {
				$id = $this->member_model->register($this->input->post('member'), NULL, $this->uid);
				if ($id == -1) {
					$data = array('error' => 'username', 'msg' => lang('m-021'));
				} elseif ($id == -2) {
					$data = array('error' => 'email', 'msg' => lang('m-011'));
				} elseif ($id == -3) {
					$data = array('error' => 'email', 'msg' => lang('m-022'));
				} elseif ($id == -4) {
					$data = array('error' => 'username', 'msg' => lang('m-023'));
				} elseif ($id == -5) {
					$data = array('error' => 'username', 'msg' => lang('m-024'));
				} elseif ($id == -6) {
					$data = array('error' => 'username', 'msg' => lang('m-025'));
				} elseif ($id == -7) {
					$data = array('error' => 'username', 'msg' => lang('m-026'));
				} elseif ($id == -8) {
					$data = array('error' => 'username', 'msg' => lang('m-027'));
				} elseif ($id == -9) {
					$data = array('error' => 'username', 'msg' => lang('m-028'));
				}
			}
			
			// 开启手机认证时
			if ($MEMBER['setting']['ismobile']) {
				$v = !isset($data['error']) && isset($field['check']);
				// 号码是否重复
				if ($v && $this->db->where('uid<>', $this->uid)->where('phone', $data[0]['phone'])->count_all_results('member')) {
					$data = array('error' => 'phone', 'msg' => lang('m-089'));
					if (IS_AJAX) exit(dr_json(0, $data['msg'], $data['error'])); // AJAX返回
				}
				
				// 验证码验证
				if ($v && $data[0]['check'] && $data[0]['check'] != $this->member['randcode']) {
					$data = array('error' => 'check', 'msg' => lang('m-090'));
					if (IS_AJAX) exit(dr_json(0, $data['msg'], $data['error'])); // AJAX返回
				} else {
					unset($data[0]['phone']); // 验证成功删除手机号码
				}
			}
			
			if (isset($data['error'])) {
				if (IS_AJAX) exit(dr_json(0, $data['msg'], $data['error'])); // AJAX返回
				$error = $data;
				$data = $this->input->post('data', TRUE);
				$input = $this->input->post('member', TRUE);
			} else {
				$result = $this->member_model->edit($data[0], $data[1]);
				$this->attachment_handle($this->uid, $this->db->dbprefix('member').'-'.$this->uid, $field, $this->member);
				if ($result) {
					// 完善资料积分处理
					if (!$this->db
							  ->where('uid', $this->uid)
							  ->where('type', 0)
							  ->where('mark', 'complete')
							  ->count_all_results('member_scorelog_'.$this->member['tableid'])) {
						$this->member_model->update_score(0, $this->uid, (int)$this->member_rule['complete_experience'], 'complete', "lang,m-058");
					}
					
					// 完善资料虚拟币处理
					if (!$this->db
							  ->where('uid', $this->uid)
							  ->where('type', 1)
							  ->where('mark', 'complete')
							  ->count_all_results('member_scorelog_'.$this->member['tableid'])) {
						$this->member_model->update_score(1, $this->uid, (int)$this->member_rule['complete_score'], 'complete', "lang,m-058");
					}
				}
				$this->member_msg(lang('000'), dr_url('account/index'), 1);
			}
		} else {
			$data = $this->member;
		}
		
		$this->template->assign(array(
			'data' => $data,
			'field' => $field,
			'error' => $error,
			'input' => $input,
			'oauth' => $oauth,
			'myfield' => $this->field_input($field, $data, TRUE, 'uid'),
		));
		$this->template->display('account_index.html');
    }
	
	/**
     * 短信认证验证码发送
     */
	public function sendsms() {
	
		if (get_cookie('send_sms')) exit(dr_json(0, lang('m-091')));
		if ($this->member['ismobile'] && $this->member['phone']) {
			exit(dr_json(0, lang('m-092')));
		}
		
		$mobile = $this->input->get('phone');
		if (strlen($mobile) != 11 || !is_numeric($mobile)) {
			exit(dr_json(0, lang('m-095')));
		}
		// 号码是否重复
		if ($this->db->where('uid<>', $this->uid)->where('phone', $mobile)->count_all_results('member')) {
			exit(dr_json(0, lang('m-089')));
		}
		
		$randcode = rand(1000, 9999);
		$result = $this->member_model->sendsms($mobile, dr_lang('m-088', $randcode));
		if ($result['status']) {
			// 发送成功
			$this->db
				 ->where('uid', $this->uid)
				 ->update('member', array('randcode' => $randcode, 'phone' => $mobile));
			set_cookie('send_sms', 1, 120);
			exit(dr_json(1, lang('m-093')));
		} else {
			// 发送失败
			exit(dr_json(0, $result['msg']));
		}
	}
	
	/**
     * OAuth解绑
     */
	public function jie() {
		
		$id = $this->input->get('id');
		if ($this->ci->get_cache('member', 'setting', 'regoauth')) $this->msg(lang('m-112'));
		if (!$this->member['username'] && !$this->member['password']) $this->msg(lang('000'));
		$this->db->where('uid', $this->uid)->where('oauth', $id)->delete('member_oauth');
		
		// 解绑积分处理
		if (!$this->db
				  ->where('uid', $this->uid)
				  ->where('type', 0)
				  ->where('mark', 'jie_'.$id)
				  ->count_all_results('member_scorelog_'.$this->member['tableid'])) {
			$this->member_model->update_score(0, $this->uid, (int)$this->member_rule['jie_experience'], 'jie_'.$id, "lang,m-060");
		}
		
		// 解绑虚拟币处理
		if (!$this->db
				  ->where('uid', $this->uid)
				  ->where('type', 1)
				  ->where('mark', 'jie_'.id)
				  ->count_all_results('member_scorelog_'.$this->member['tableid'])) {
			$this->member_model->update_score(1, $this->uid, (int)$this->member_rule['jie_score'], 'jie_'.$id, "lang,m-060");
		}
		
		$this->msg(lang('000'), dr_url('account/oauth'), 1, 3);
	}
	
	/**
     * OAuth绑定
     */
	public function bang() {
	
		$appid = $this->input->get('id');
		$oauth = require FCPATH.'config/oauth.php';
		$config	= $oauth[$appid];
		if (!$config) $this->msg(lang('m-047'));
		
		$config['url'] = SITE_URL.'member/index.php?c=account&m=bang&id='.$appid; // 回调地址设置
		$this->load->library('OAuth2');
		
		// OAuth
        $code = $this->input->get('code', TRUE);
		$oauth = $this->oauth2->provider($appid, $config);
		
		if (!$code) { // 登录授权页
			try {
				$oauth->authorize();
			} catch (OAuth2_Exception $e) {
				$this->msg(lang('m-048').' - '.$e);
			}
		} else { // 回调返回数据
			try {
				$token = $oauth->access($code);
	        	$user = $oauth->get_user_info($token);
				
				if (is_array($user)) {
					if ($uid = $this->member_model->OAuth_bang($appid, $user)) {
						$this->msg(dr_lang('m-049', dr_space_url($uid)));
					} else {
						
						// 绑定积分处理
						if (!$this->db
								  ->where('uid', $this->uid)
								  ->where('type', 0)
								  ->where('mark', 'bang_'.$appid)
								  ->count_all_results('member_scorelog_'.$this->member['tableid'])) {
							$this->member_model->update_score(0, $this->uid, (int)$this->member_rule['bang_experience'], 'bang_'.$appid, "lang,m-059");
						}
						
						// 绑定虚拟币处理
						if (!$this->db
								  ->where('uid', $this->uid)
								  ->where('type', 1)
								  ->where('mark', 'bang_'.$appid)
								  ->count_all_results('member_scorelog_'.$this->member['tableid'])) {
							$this->member_model->update_score(1, $this->uid, (int)$this->member_rule['bang_score'], 'bang_'.$appid, "lang,m-059");
						}
						$this->msg(lang('m-050'), dr_url('account/oauth'), 1, 3);
					}
				} else {
					$this->msg(lang('m-051'));
				}
			} catch (OAuth2_Exception $e) {
                $this->msg(lang('m-051').' - '.$e);
			}
		}
	}
	
	/**
     * 登录记录
     */
	public function login() {
		$this->template->assign(array(
			'list' => array_reverse(dr_string2array($this->member['loginlog'])),
		));
		$this->template->display('account_login.html');
	}
	
	/**
     * OAuth
     */
	public function oauth() {
		$this->template->assign(array(
			'list' => $this->member['oauth'],
			'regoauth' => $this->get_cache('member', 'setting', 'regoauth'),
		));
		$this->template->display('account_oauth.html');
	}

	/**
     * 修改密码
     */
    public function password() {
	
		$error = 0;
		
		if (IS_POST) {
		
			$password = $this->input->post('password');
			$password1 = $this->input->post('password1');
			$password2 = $this->input->post('password2');
			
			if (!$password1 || $password1 != $password2) {
				$error = lang('m-054');
			} elseif (md5(md5($password).$this->member['salt'].md5($password)) != $this->member['password']) {
				$error = lang('m-339');
			} else {
				if ($this->get_cache('MEMBER', 'setting', 'ucenter')) {
					$ucresult = uc_user_edit($this->member['username'], $password, $password1, $this->member['email']);
					if($ucresult == -1) $error = lang('m-053');
				}
			}
			
			if ($error === 0) {
				$this->db
					 ->where('uid', $this->uid)
					 ->update('member', array('password' => md5(md5($password1).$this->member['salt'].md5($password1))));
				$this->member_msg(lang('m-052'), dr_url('account/password'), 1);
			}
			
			if (IS_AJAX) exit(dr_json(0, $error)); // AJAX返回
		}
		
		$this->template->assign(array(
			'error' => $error,
		));
		$this->template->display('account_password.html');
    }
	
	/**
     * 密码校验
     */
    public function cpassword() {
		$password = $this->input->post('password');
		echo md5(md5($password).$this->member['salt'].md5($password)) == $this->member['password'] ? lang('m-055') : lang('m-053');
    }
	
	/**
     * 上传头像
     */
    public function avatar() {
	
		$ucenter = '';
		
		if ($this->get_cache('member', 'setting', 'ucenter') && $data = uc_get_user($this->member['username'])) {
			list($ucenter, $username, $email) = $data;
		}
		
		$this->template->assign(array(
			'ucenter' => $ucenter,
		));
		$this->template->display('account_avatar.html');
    }
	
	/**
	 *  上传头像处理
	 *  传入头像压缩包，解压到指定文件夹后删除非图片文件
	 */
	public function upload() {
	
		if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])) exit('0');
		
		$dir = FCPATH.'member/uploadfile/member/'.$this->uid.'/'; // 创建图片存储文件夹
		if (!file_exists($dir)) mkdir($dir, 0777, true);
		$filename = $dir.'avatar.zip'; // 存储flashpost图片
		file_put_contents($filename, $GLOBALS['HTTP_RAW_POST_DATA']);
		
		// 解压缩文件
		$this->load->library('Pclzip');
		$this->pclzip->PclFile($filename);
		if ($this->pclzip->extract(PCLZIP_OPT_PATH, $dir, PCLZIP_OPT_REPLACE_NEWER) == 0) die('Error : ' . $this->pclzip->zip(true));
		
		// 限制文件名称
		$avatararr = array('45x45.jpg', '90x90.jpg');
		
		// 删除多余目录
		$files = glob($dir."*");
		foreach($files as $_files) {
			if (is_dir($_files)) dr_dir_delete($_files);
			if (!in_array(basename($_files), $avatararr)) @unlink($_files);
		}
		
		// 判断文件安全，删除压缩包和非jpg图片
		if($handle = opendir($dir)) {
		    while (false !== ($file = readdir($handle))) {
				if ($file !== '.' && $file !== '..') {
					if (!in_array($file, $avatararr)) {
						@unlink($dir . $file);
					} else {
						$info = @getimagesize($dir . $file);
						if (!$info || $info[2] !=2) {
							@unlink($dir . $file);
						}
					}
				}
		    }
		    closedir($handle);    
		}
		@unlink($filename);
		
		// 上传头像积分处理
		if (!$this->db
				  ->where('uid', $this->uid)
				  ->where('type', 0)
				  ->where('mark', 'avatar')
				  ->count_all_results('member_scorelog_'.$this->member['tableid'])) {
			$this->member_model->update_score(0, $this->uid, (int)$this->member_rule['avatar_experience'], 'avatar', "lang,m-057");
		}
		
		// 上传头像虚拟币处理
		if (!$this->db
				  ->where('uid', $this->uid)
				  ->where('type', 1)
				  ->where('mark', 'avatar')
				  ->count_all_results('member_scorelog_'.$this->member['tableid'])) {
			$this->member_model->update_score(1, $this->uid, (int)$this->member_rule['avatar_score'], 'avatar', "lang,m-057");
		}
		
		// 更新头像
		$this->db
			 ->where('uid', $this->uid)
			 ->update('member', array('avatar' => $this->uid));
		
		exit('1');
	}
	
	/**
     * 会员组升级
     */
	public function upgrade() {
	
		$id = (int)$this->input->get('id');
		
		if ($id) {
			
			$group = $this->get_cache('member', 'group', $id);
			if (!$group) $this->member_msg(lang('m-126'));
			if (!$group['allowapply']) $this->member_msg(lang('m-258'));
			
			if ($id == $this->member['groupid']) {
				// 表示续费
				$time = $this->member['overdue'];
				$renew = TRUE;
			} else {
				$time = 0;
				$renew = FALSE;
			}
			
			if ($group['unit'] == 1) {
				// 虚拟币扣减
				$value = intval($group['price']);
				if ($this->member['score'] - $value < 0) $this->member_msg(dr_lang('m-259', $value, $this->member['score']));
				$this->member_model->update_score(1, $this->uid, -$value, '', "lang,m-260,".$group['name']);
			} else {
				// 人民币扣减
				if ($this->member['money'] - $group['price'] < 0) $this->member_msg(dr_lang('m-267', $group['price'], $this->member['money']));
				$this->load->model('pay_model');
				$this->pay_model->add($this->uid, -$group['price'], "lang,m-260,".$group['name']);
			}
			
			$time = $this->member_model->upgrade($this->uid, $id, $group['limit'], $time);
			$time = $time > 2000000000 ? lang('m-265') : dr_date($time);
			$subject = $renew ? lang('m-263') : lang('m-261');
			$message = dr_lang($renew ? 'm-264' : 'm-262', $this->member['name'] ? $this->member['name'] : $this->member['username'], $group['name'], $time);
			
			// 邮件提醒
			$this->sendmail_queue($this->member['email'], $subject, $message);
			$this->member_msg(dr_lang('m-266', $time), dr_url('account/permission'), 1, 3);
			
		} else {
			
			$data = array();
			$group = $this->get_cache('member', 'group');
			if ($group) {
				foreach ($group as $t) {
					if ($t['allowapply']) {
						$data[$t['id']] = $t;
					}
				}
			}
			
			$this->template->assign(array(
				'group' => $data,
			));
			$this->template->display('account_upgrade.html');
		}
	}
	
	/**
     * 会员组权限
     */
	public function permission() {
		
		$page = (int)$this->input->get('page');
		$groupid = (int)$this->input->get('groupid');
		$levelid = (int)$this->input->get('levelid');
		$groupid = $groupid ? $groupid : $this->member['groupid'];
		
		$group = $this->get_cache('member', 'group', $groupid);
		if (!$group) $this->member_msg(lang('m-126'));
		
		if ($groupid != $this->member['groupid']) {
			$levelid = $levelid ? $levelid : array_rand($group['level']);
		} else {
			$levelid = $levelid ? $levelid : $this->member['levelid'];
		}
		
		$content = NULL;
		$category = array(0 => lang('m-268'), 1 => lang('m-327'));
		$markrule = $groupid < 3 ? $groupid : ($groupid.'_'.$levelid);
		
		if ($page == 0) {
			// 会员的基本权限表格
			$rule = $this->get_cache('member', 'setting', 'permission', $markrule);
			$content = '<table class="dr_table" width="100%" border="0">';
			$content.= '  <tr><td width="200" align="right">'.dr_lang('m-269', SITE_EXPERIENCE).'：&nbsp;</td><td width="100">&nbsp;'.intval($rule['login_experience']).'</td>';
			$content.= '  <td width="200" align="right">'.dr_lang('m-269', SITE_SCORE).'：&nbsp;</td><td>&nbsp;'.intval($rule['login_score']).'</td></tr>';
			$content.= '  <tr><td align="right">'.dr_lang('m-270', SITE_EXPERIENCE).'：&nbsp;</td><td>&nbsp;'.intval($rule['avatar_experience']).'</td>';
			$content.= '  <td align="right">'.dr_lang('m-270', SITE_SCORE).'：&nbsp;</td><td>&nbsp;'.intval($rule['avatar_score']).'</td></tr>';
			$content.= '  <tr><td align="right">'.dr_lang('m-271', SITE_EXPERIENCE).'：&nbsp;</td><td>&nbsp;'.intval($rule['complete_experience']).'</td>';
			$content.= '  <td align="right">'.dr_lang('m-271', SITE_SCORE).'：&nbsp;</td><td>&nbsp;'.intval($rule['complete_score']).'</td></tr>';
			$content.= '  <tr><td align="right">'.dr_lang('m-272', SITE_EXPERIENCE).'：&nbsp;</td><td>&nbsp;'.intval($rule['bang_experience']).'</td>';
			$content.= '  <td align="right">'.dr_lang('m-272', SITE_SCORE).'：&nbsp;</td><td>&nbsp;'.intval($rule['bang_score']).'</td></tr>';
			$content.= '  <tr><td align="right">'.dr_lang('m-273', SITE_EXPERIENCE).'：&nbsp;</td><td>&nbsp;'.intval($rule['jie_experience']).'</td>';
			$content.= '  <td align="right">'.dr_lang('m-273', SITE_SCORE).'：&nbsp;</td><td>&nbsp;'.intval($rule['jie_score']).'</td></tr>';
			$content.= '  <tr><td align="right">'.dr_lang('m-274', SITE_EXPERIENCE).'：&nbsp;</td><td>&nbsp;'.intval($rule['update_experience']).'</td>';
			$content.= '  <td align="right">'.dr_lang('m-274', SITE_SCORE).'：&nbsp;</td><td>&nbsp;'.intval($rule['update_score']).'</td></tr>';
			$content.= '  <tr><td align="right">'.dr_lang('m-323', SITE_EXPERIENCE).'：&nbsp;</td><td>&nbsp;'.intval($rule['download_experience']).'</td>';
			$content.= '  <td align="right">'.dr_lang('m-323', SITE_SCORE).'：&nbsp;</td><td>&nbsp;'.intval($rule['download_score']).'</td></tr>';
			$content.= '  <tr><td align="right">'.lang('m-275').'：&nbsp;</td><td>&nbsp;<img src="'.SITE_URL.'dayrui/statics/images/'.(int)$rule['is_upload'].'.gif"></td>';
			$content.= '  <td align="right">'.lang('m-276').'：&nbsp;</td><td>&nbsp;<img src="'.SITE_URL.'dayrui/statics/images/'.(int)$rule['is_download'].'.gif"></td></tr>';
			$content.= '  <tr><td align="right">'.lang('m-277').'：&nbsp;</td><td>&nbsp;'.($rule['attachsize'] ? $rule['attachsize'].'MB' : lang('m-278')).'</td></tr>';
			$content.= '</table>';
		} elseif ($page == 1) {
			$setting = $this->get_cache('member', 'setting');
			// 会员空间表格
			$content = '
			<table class="dr_table" width="100%" border="0">
			<tr>
				<td width="120" align="right">'.lang('m-330').'：&nbsp;</td>
				<td width="100"><img src="'.SITE_URL.'dayrui/statics/images/'.(int)$setting['editspace'].'.gif"></td>
				<td width="120" align="right">'.lang('m-331').'：&nbsp;</td>
				<td><img src="'.SITE_URL.'dayrui/statics/images/'.(int)$setting['verifyspace'].'.gif"></td>
				<td width="120" align="right">'.lang('m-144').'：&nbsp;</td>
				<td><img src="'.SITE_URL.'dayrui/statics/images/'.(int)$this->get_cache('member', 'group', $this->member['groupid'], 'allowspace').'.gif"></td>
			</tr>
			</table>
			<table class="dr_table" width="100%" border="0">
			<tr>
				<td>'.lang('m-328').'</td>
				<td align="center">'.lang('m-329').'</td>
				<td align="center">'.lang('m-061').'</td>
				<td align="center">'.dr_lang('m-279', SITE_EXPERIENCE).'</td>
				<td align="center">'.dr_lang('m-279', SITE_SCORE).'</td>
			</tr>';
			$model = $this->get_cache('space-model');
			foreach ($model as $t) {
				$rule = $t['setting'][$markrule];
				$content.= '<tr>';
				$content.= '<td>'.$t['name'].'</td>';
				$content.= '<td align="center"><img src="'.SITE_URL.'dayrui/statics/images/'.(int)$rule['use'].'.gif"></td>';
				$content.= '<td align="center"><img src="'.SITE_URL.'dayrui/statics/images/'.($rule['verify'] ? 1 : 0).'.gif"></td>';
				$content.= '<td align="center">'.(int)$rule['experience'].'</td>';
				$content.= '<td align="center">'.(int)$rule['score'].'</td>';
				$content.= '</tr>';
				
			}
			$content.= '</table>';
		}
		
		// 检测可管理的模块
		$module = $this->get_cache('module', SITE_ID);
		if ($module) {
			foreach ($module as $dir) {
				$mod = $this->get_cache('module-'.SITE_ID.'-'.$dir);
				$key = count($category);
				$cat = $mod['category'];
				$category[$key] = $mod['name'];
				// 权限表格
				if ($key == $page && $cat) {
					$content = '<table class="dr_table" width="100%" border="0">';
					$content.= '  <tr>
						<td>'.lang('m-282').'</td>
						<td align="center">'.lang('m-345').'</td>
						<td align="center">'.lang('m-063').'</td>
						<td align="center">'.lang('m-064').'</td>
						<td align="center">'.lang('m-065').'</td>
						<td align="center">'.lang('m-061').'</td>
						<td align="center">'.lang('m-283').'</td>
						<td align="center">'.lang('m-284').'</td>
						<td align="center">'.lang('m-285').'</td>
					  </tr>';
					foreach ($cat as $c) {
						if (!$c['child']) {
							$rule = $c['permission'][$markrule];
							$content.= '<tr>';
							$content.= '<td>'.$c['name'].'('.$c['id'].')</td>';
							$content.= '<td align="center"><img src="'.SITE_URL.'dayrui/statics/images/'.($rule['show'] ? 0 : 1).'.gif"></td>';
							$content.= '<td align="center"><img src="'.SITE_URL.'dayrui/statics/images/'.(int)$rule['add'].'.gif"></td>';
							$content.= '<td align="center"><img src="'.SITE_URL.'dayrui/statics/images/'.(int)$rule['edit'].'.gif"></td>';
							$content.= '<td align="center"><img src="'.SITE_URL.'dayrui/statics/images/'.(int)$rule['del'].'.gif"></td>';
							$content.= '<td align="center"><img src="'.SITE_URL.'dayrui/statics/images/'.($rule['verify'] ? 1 : 0).'.gif"></td>';
							$content.= '<td align="center">'.($rule['postnum'] ? $rule['postnum'] : lang('m-278')).'</td>';
							$content.= '<td align="center">'.($rule['postcount'] ? $rule['postcount'] : lang('m-278')).'</td>';
							$content.= '<td align="center"><a href="javascript:;" onclick="dr_show_more('.$c['id'].')">'.lang('m-285').'</a></td>';
							$content.= '</tr>';
							$content.= '<tr class="dr_clear dr_hide_'.$c['id'].'" style="display:none"><td></td>';
							$content.= '<td colspan="4">'.dr_lang('m-279', SITE_EXPERIENCE).'：'.intval($rule['experience']).'</td>';
							$content.= '<td colspan="4">'.dr_lang('m-279', SITE_SCORE).'：'.intval($rule['score']).'</td>';
							$content.= '</tr>';
						}
						
					}
					$content.= '</table>';
				}
			}
		}
		
		// 检测可管理的应用
		
		$this->template->assign(array(
			'page' => $page,
			'group' => $group,
			'levelid' => $levelid,
			'groupid' => $groupid,
			'content' => $content,
			'category' => $category,
		));
		$this->template->display('account_permission.html');
	}
	
	/**
     * 附件管理
     */
	public function attachment() {
		
		$ext = $this->input->get('ext');
		$table = $this->input->get('module');
		$this->load->model('attachment_model');
		
		if ($this->input->get('action') == 'more') { // ajax更多数据
			$page = max((int)$this->input->get('page'), 1);
			$data = $this->attachment_model->limit($this->uid, $page, $this->pagesize, $ext, $table);
			if (!$data) exit('null');
			$this->template->assign(array(
				'list' => $data
			));
			$this->template->display('account_attachment_data.html');
		} else {
			// 检测可管理的模块
			$module = array();
			$modules = $this->get_cache('module', SITE_ID);
			if ($modules) {
				foreach ($modules as $dir) {
					$mod = $this->get_cache('module-'.SITE_ID.'-'.$dir);
					if ($this->_module_post_catid($mod, $this->markrule)) $module[$dir] = $mod['name'];
				}
			}
			$data = $this->attachment_model->limit($this->uid, 1, $this->pagesize, $ext, $table);
			$acount = $this->get_cache('member', 'setting', 'permission', $this->markrule, 'attachsize');
			$acount = $acount ? $acount : 1024000;
			$ucount = $this->db->select('sum(`filesize`) as total')->where('uid', (int)$this->uid)->limit(1)->get('attachment')->row_array();
			$ucount = (int)$ucount['total'];
			$acount = $acount * 1024 * 1024;
			$scount = max($acount - $ucount, 0);
			$this->template->assign(array(
				'ext' => $ext,
				'list' => $data,
				'table' => $table,
				'module' => $module,
				'acount' => $acount,
				'ucount' => $ucount,
				'scount' => $scount,
				'moreurl' => 'index.php?c='.$this->router->class.'&m='.$this->router->method.'&ext='.$ext.'&action=more'
			));
			$this->template->display('account_attachment_list.html');
		}
	}
	
	// 删除附件
	public function del_attach() {
	
		$id = (int)$this->input->post('id');
		$this->load->model('attachment_model');
		
		if ($this->attachment_model->delete($this->uid, '', $id)) {
			exit(dr_json(1, lang('000')));
		} else {
			exit(dr_json(0, 'Error'));
		}
	}
}