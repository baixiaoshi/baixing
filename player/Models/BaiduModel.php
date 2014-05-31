<?php
function getvideo($id,$pid='2'){
	$hz='_baidu';
	if(strpos($id,'/')){
		$ids=explode('/',$id);
		$pido=count($ids);
		switch($pido){
			case '1':
				$qvars=__BQ__.'_'.$ids[0].$hz;
				break;
			case '2':
				$qvars=__BQ__.'_'.$ids[0].$hz.'|'.__GQ__.'_'.$ids[1].$hz;
				break;
			case '3':
				$qvars=__BQ__.'_'.$ids[0].$hz.'|'.__GQ__.'_'.$ids[1].$hz.'|'.__CQ__.'_'.$ids[2].$hz;
				break;
			default:
				$qvars=$id.$hz;
				break;
		}
		$urllist['vars']='{h->0}{a->'.$qvars.'}{f->'.__HOSTURL__.'?url=[$pat'.($pid-1).']}';
		$id=$ids{$pid-1};
	}else{
		$urllist['vars']='{h->0}{a->'.$id.$hz.'}';
	}
	$urllist['urls'][0]['url']=getbaidu($id);
	return $urllist;
}
function getbaidu($id){
	if(strpos($id,'-')){
		$id=explode('-',$id);
		$url="http://pan.baidu.com/share/link?shareid=".$id[0]."&uk=".$id[1];
	}else{
		$url="http://pan.baidu.com/s/".$id;
	}
	$content=get_curl_contents($url);
	return preg_match('~;;_dlink="(.+)?";~iUs',$content,$getid) ? $getid[1] : '';

	/*
	$tzurl=explode('dlink\":\"',$content);
	$tzurl=explode('\"',$tzurl[1]);
	$tzurl=$tzurl[0];
	return stripslashes(stripslashes($tzurl));
	*/
}
?>