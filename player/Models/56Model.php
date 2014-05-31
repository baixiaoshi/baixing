<?php
function getvideo($id,$pid=2){
	$hz='_56';
	$pidarrs[]='normal';
	$pidarrs[]='wvga';
	$pidarrs[]='super';
	$content=get_curl_contents('http://vxml.56.com/json/'.$id.'/?src=out');
	$data=json_decode($content);
	$rfiles=$data->info->rfiles;
	for($i=0;$i<count($rfiles);$i++){
		$type=$rfiles[$i]->type;
		$urls[$type]=$rfiles[$i]->url;
	}
	//print_r($urls);die;
	$pido = '';
	if(isset($urls['super']))$pido = '3';
	if(!$pido){if($urls['wvga'])$pido = '2';}
	if(!$pido){
		if($urls['qvga'])$pido = '2';
		$pidarrs[1] = 'qvga';
	}
	if(!$pido){
		if($urls['vga'])$pido = '2';
		$pidarrs[1] = 'vga';
	}
	if(!$pido){
		if($urls['clear'])$pido = '2';
		$pidarrs[1] = 'clear';
	}
	if(!$pido)$pido = '1';
	switch($pido){
		case '1':
			$qvars='bq_'.$id.$hz;
			break;
		case '2':
			$qvars='bq_'.$id.$hz.'|gq_'.$id.$hz;
			break;
		case '3':
			$qvars='bq_'.$id.$hz.'|gq_'.$id.$hz.'|cq_'.$id.$hz;
			break;
		default:
			$qvars=$id.$hz;
			break;
	}
	$pid=min($pid,$pido);
	if($pid == 2){
		!isset($urls[$pidarrs[1]])&&$pidarrs[1] = 'clear';
		!isset($urls[$pidarrs[1]])&&$pidarrs[1] = 'qvga';
		!isset($urls[$pidarrs[1]])&&$pidarrs[1] = 'vga';
	}
	$urllist['urls'][0]['url']=$urls[$pidarrs{$pid-1}];
	$urllist['vars']='{h->3}{a->'.$qvars.'}{f->'.__HOSTURL__.'?url=[$pat'.($pid-1).']}';
	return $urllist;
}
?>