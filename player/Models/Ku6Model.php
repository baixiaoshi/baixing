<?php
function getvideo($id,$pid=2){
	$pidarrs[] = '';
	$pidarrs[] = '600';
	$pidarrs[] = '1500';
	$hz='_ku6';
	$content=get_curl_contents('http://v.ku6.com/fetchVideo4Player/'.$id.'.html');
	$data=json_decode($content);
	$json=$data->data;
	$srctype=$json->srctype;
	if($srctype){
		$pido='2';
		if($srctype>2){
			$pido='3';
		}
	}else{
		$pido='1';
	}
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
	$vtime=explode(',',$json->vtime);
	$urls=explode(',',$json->f);
	$rate=$pidarrs{$pid-1};
	if($rate){$rate='?rate='.$rate;}
	$j=count($urls);
	if($j==1){
		$urllist['urls'][0]['url']=$urls[0].$rate;
		$urllist['urls'][0]['sec']=$vtime[0];
	}else{
		for($i=0;$i<$j;$i++){
		$urllist['urls'][$i]['url']=$urls[$i].$rate;
		$urllist['urls'][$i]['sec']=$vtime[$i+1];
		}
	}
	$urllist['vars']='{h->3}{q->rate}{a->'.$qvars.'}{f->'.__HOSTURL__.'?url=[$pat'.($pid-1).']}';
	return $urllist;
}
?>