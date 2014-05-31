<?php

define('DR_PAY_ID', 'alipay');
define('DR_PAY_FILE', 'notify');
require dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'index.php';
$config = $pay['alipay'];
//合作身份者id，以2088开头的16位纯数字
$aliapy_config['partner']      = $config['partner'];
//安全检验码，以数字和字母组成的32位字符
$aliapy_config['key']          = $config['key'];
//签约支付宝账号或卖家支付宝帐户
$aliapy_config['seller_email'] = $config['username'];
//页面跳转同步通知页面路径，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
//return_url的域名不能写成http://localhost/create_direct_pay_by_user_php_utf8/return_url.php ，否则会导致return_url执行无效
echo $aliapy_config['return_url']   = Controller::get_server_name() . Controller::get_base_url()  . 'return_url.php';
//服务器异步通知页面路径，要用 http://格式的完整路径，不允许加?id=123这类自定义参数
$aliapy_config['notify_url']   = Controller::get_server_name() . Controller::get_base_url()  . 'notify_url.php';
//↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
//签名方式 不需修改
$aliapy_config['sign_type']    = 'MD5';
//字符编码格式 目前支持 gbk 或 utf-8
$aliapy_config['input_charset']= 'utf-8';
//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
$aliapy_config['transport']    = 'http';
require_once("alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($aliapy_config);
$verify_result = $alipayNotify->verifyNotify();

if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代
	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
    $out_trade_no	= $_POST['out_trade_no'];	    //获取订单号
    $trade_no		= $_POST['trade_no'];	    	//获取支付宝交易号
    $total_fee		= $_POST['total_fee'];			//获取总价格

	$account = new Pay_accountModel();
	$money   = number_format($total_fee, 2, '.', '');
	$data    = $account->where('status=0 && money= ' . $money . ' AND order_sn=' . $out_trade_no . ' AND userid=' . $member['id'] . ' AND username=?', $member['username'])->select(false);
	if ($data) {
		$pay = new Pay_dataModel();
		$set = array(
			'available' => 'available+' . $money,
			'freeze'    => $data['freeze'],
		);
		$id  = $pay->set($set, $member['id']);
		if ($id) $account->update(array('status'=>1, 'paytime'=>time()), 'id=' . $data['id']);
	}

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        
	echo "success";		//请不要修改或删除
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}