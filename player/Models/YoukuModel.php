<?php
function getvideo($id,$pid=2){
	$hz='_youku';
	$pidarrs[] = 'flv';
	$pidarrs[] = 'mp4';
	$pidarrs[] = 'hd2';
	$content=get_curl_contents('http://v.youku.com/player/getPlayList/VideoIDS/'.$id);
	$data=json_decode($content);
	$fileid_=$data->data[0]->streamfileids;
	$fileid2_=$fileid_->hd2;
	$pido='3';
	if (!$fileid2_){
		$fileid2_=$fileid_->mp4;
		$pido='2';
	}
	if (!$fileid2_){
		$fileid2_=$fileid_->flv;
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
	$sk=$pidarrs{$pid-1};
	$fileid2_=$fileid_->$sk;
	$sid=getSid();
	$fileid3_=getfileid($fileid2_,$data->data[0]->seed);
	$filed1_=substr($fileid3_,0,8);
	$filed2_=substr($fileid3_,10);
	$segs=$data->data['0']->segs->$sk;
	$i=0;
	$urllist='';
	foreach($segs AS $seg1 => $v1){
		$AA= strtoupper(dechex($i)).'';
		if(strlen($AA)<2) $AA='0'.$AA;
		$filed_=$filed1_.$AA.$filed2_;
		$k1=$v1->k;
		$k2=$v1->k2;
		$size=$v1->size;
		$seconds=$v1->seconds;
		$sk=str_replace('hd2','flv',$sk);
		$urllist['urls'][$seg1]['url'] = 'http://f.youku.com/player/getFlvPath/sid/00_00/st/'.$sk.'/fileid/'.$filed_.'?K='.$k1.',k2='.$k2;
		$urllist['urls'][$seg1]['size'] = $size;
		$urllist['urls'][$seg1]['sec'] = $seconds;
		$i+=1;
	}
	$urllist['vars']='{h->2}{a->'.$qvars.'}{f->'.__HOSTURL__.'?url=[$pat'.($pid-1).']}';
	return $urllist;
}
function getSid() {
    $sid = time().(rand(0,9000)+10000);
    return $sid;
}
function getfileid($fileId,$seed) {
    $mixed = getMixString($seed);
    $ids = explode("*",$fileId);
    unset($ids[count($ids)-1]);
    $realId = "";
    for ($i=0;$i < count($ids);++$i) {
    $idx = $ids[$i];
    $realId .= substr($mixed,$idx,1);
    }
    return $realId;
}
function getMixString($seed) {
    $mixed = "";
    $source = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ/\\:._-1234567890";
    $len = strlen($source);
    for($i=0;$i< $len;++$i){
    $seed = ($seed * 211 + 30031) % 65536;
    $index = ($seed / 65536 * strlen($source));
    $c = substr($source,$index,1);
    $mixed .= $c;
    $source = str_replace($c, "",$source);
    }
    return $mixed;
}
?>