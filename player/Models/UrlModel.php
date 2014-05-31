<?php
function getvideo($id,$pid='2'){
	$urllist['urls'][0]['url'] = $id;
	$urllist['vars']='{h->3}{a->'.$id.'}';
	return $urllist;
}
?>