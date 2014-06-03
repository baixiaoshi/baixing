<?php
@header("Content-Type: text/xml");
error_reporting(0);
include_once('./Common/functions.php');
if(!isset($_GET['vtype'])&&!isset($_GET['u'])&&isset($_GET['url'])){
	$ids='http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"]; 
	$ids=urldecode($ids);
	$ids=end(explode('url=',$ids));
	$arr=explode('_',$ids);
	$np=2;
	switch($arr[0]){
		case __CQ__:
			$pid='3';
			setcookie("pidcookie", $pid, time()+360000);
			break;
		case __GQ__:
			$pid='2';
			setcookie("pidcookie", $pid, time()+360000);
			break;
		case __BQ__:
			$pid='1';
			setcookie("pidcookie", $pid, time()+360000);
			break;
		default:
			$pid=$np;
			isset($_COOKIE["pidcookie"])&&$pid=$_COOKIE["pidcookie"];
			!$pid&&$pid=$np;
			break;
	}
	if(strstr($ids,'http://')==false){
		/*if(count($arr)==3){
			$id=$arr[1];
		}else{
			$id=$arr[0];
		}*/
		$type=end($arr);
		$id=strtr($ids,array(__BQ__."_" => "", __GQ__."_" => "", __CQ__."_" => "", "_".$type => ""));
		if(strpos($ids,'_wd')){
			switch($type){
			case 'wd1':
				$type='youku';
				break;
			case 'wd2':
				$type='tudou';
				break;
			case 'wd3':
				$type='letv';
				break;
			case 'wd4':
				$type='56';
				break;
			case 'wd5':
				$type='ku6';
				break;
			default:
				break;
			}
		}
	}else{
		if(strpos($ids,'_http://')){
			$url=str_replace($arr[0].'_','',$ids);
		}else{
			$url=$ids;
		}

		/*if(count($arr)==2){
			$url=$arr[1];
		}else{
			$url=$arr[0];
		}*/
		include_once('./Common/vids.php');
		$data=getvideoid($url);
		$id=$data['id'];
		$type=$data['type'];
	}
}else{
	if(isset($_GET['vtype'])){
		$type=$_GET['vtype'];
		$id=$_GET['vid'];
	}elseif(isset($_GET['u'])){
		$ids = base64_decode($_GET['u']);
		if(preg_match("/^[a-zA-Z0-9-_]{4,41}\.[a-z0-9]{2,12}$/",$url)){
			list($id, $type)=explode('.', $ids);
		}else{
			include_once('./Common/vids.php');
			$data = getvideoid($url);
			if($data['status']<0){
				echo '无法识别的url';
				die;
			}
		}
	}
	
}
if(isset($type)){
	if($type){
		$type=ucfirst(strtolower($type));
		$filename='./Models/'.$type.'Model.php';
		if(file_exists($filename)){
			include_once($filename);
		}else{
			include_once('./Models/UrlModel.php');
		}
	}else{
		include_once('./Models/UrlModel.php');
	}
}else{
	include_once('./Models/UrlModel.php');
}
if(isset($id)){
	if($id){
		$t=getvideo($id,$pid);
		echo get_xml($t);
		die;
	}else{
		echo '错误的调用参数';
		die;
	}
}else{
	echo '错误的调用参数';
	die;
}
?>