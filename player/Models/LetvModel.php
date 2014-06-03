<?php
function getvideo($id,$pid=2){
	$hz='_letv';
	$pidarrs[] = '350';
	$pidarrs[] = '1000';
	$pidarrs[] = '720p';
	$content=get_curl_contents('http://www.letv.com/v_xml/'.$id.'.xml');
	preg_match('~<playurl><!\[cdata\[(.*)\]\]></playurl>~iUs',$content,$data);
	$data=$data[1];
	$json=json_decode($data);
	$pido = '';
	if(strpos($data,'"720p"'))$pido = '3';
	if(!$pido){if(strpos($data,'"1000"'))$pido = '2';}
	if(!$pido)$pido = '1';
	switch($pido){
		case '1':
			$qvars=__BQ__.'_'.$id.$hz;
			break;
		case '2':
			$qvars=__BQ__.'_'.$id.$hz.'|'.__GQ__.'_'.$id.$hz;
			break;
		case '3':
			$qvars=__BQ__.'_'.$id.$hz.'|'.__GQ__.'_'.$id.$hz.'|'.__CQ__.'_'.$id.$hz;
			break;
		default:
			$qvars=$id.$hz;
			break;
	}
	$pid=min($pid,$pido);
	$dispatch=$json->dispatch->$pidarrs{$pid-1};
	$urllist['urls'][0]['url']=$dispatch[0];
	$urllist['vars']='{h->1}{a->'.$qvars.'}{f->'.__HOSTURL__.'?url=[$pat'.($pid-1).']}';
	return $urllist;
}
?>