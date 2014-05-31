<?php
define ("__CQ__", "cq");
define ("__GQ__", "gq");
define ("__BQ__", "bq");
$hosturl= $_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
list($hosturl,$end)=explode('?',$hosturl);
define ("__HOSTURL__", 'http://'.$hosturl);
unset($end,$hosturl);
function get_xml($data){
	$urls=$data['urls'];
	$vars=$data['vars'];
	$urllist='';
	foreach($urls as $key=>$value){
		$urllist.='		<video>'.chr(13);
		$urllist.="			<file><![CDATA[".$urls[$key]['url']."]]></file>".chr(13);
		if(isset($urls[$key]['sec'])){
			if(!isset($urls[$key]['size']))$urls[$key]['size']=0;
			$urllist.="			<size>".$urls[$key]['size']."</size>".chr(13);
			$urllist.="			<seconds>".$urls[$key]['sec']."</seconds>".chr(13);
		}
		$urllist.='		</video>'.chr(13);
	}
	$urllist2 = '';
	$urllist2.='<?xml version="1.0" encoding="utf-8"?>'.chr(13);
	$urllist2.='	<player>'.chr(13);
	$urllist2.='	<flashvars>'.chr(13);
	$urllist2.='		'.$vars.''.chr(13);
	$urllist2.='	</flashvars>'.chr(13);
	$urllist2.=$urllist;
	$urllist2.='	</player>';
	echo $urllist2;
}
function getip() {
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
	{$ip = getenv('HTTP_CLIENT_IP');}
	elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
	{$ip = getenv('HTTP_X_FORWARDED_FOR');}
	elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
	{$ip = getenv('REMOTE_ADDR');}
	elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
	{$ip = $_SERVER['REMOTE_ADDR'];}
	return preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : false;
}
function get_curl_contents($url,$header=0,$nobody=0,$ipopen=0){
		if(!function_exists('curl_init')) die('php.ini未开启php_curl.dll');
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $url);
		curl_setopt($c, CURLOPT_HEADER, $header);
		curl_setopt($c, CURLOPT_NOBODY, $nobody);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		$ipopen==0&&curl_setopt($c, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:'.$_SERVER["REMOTE_ADDR"], 'CLIENT-IP:'.$_SERVER["REMOTE_ADDR"]));
		$content = curl_exec($c);
		curl_close($c);
	return $content;
}
function urldebug($url,$off = false){//如果不希望往服务器回传数据，请自己把$off的值改为true
	$data['status'] = -1;
	$data['msg'] = '该地址不能正常解析，已经记录，会在最短的时间内解决该问题';
	$data['url'] = $url;
	if($off == false){
		$url= 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		$out = 'http://debug.flv.pw/urldebug.php?url='.base64_encode($url);
		if($out != '1'){
			$data['msg'] = '该地址不能正常解析，地址记录无法正常记入数据库';
		}
	}
	echo json_encode($data);
	die;
}
?>