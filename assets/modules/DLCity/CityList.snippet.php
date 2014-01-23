<?php
$params = array_merge($modx->event->params, array(
	'idType' => 'documents',
	'table'=>'city',
	'ignoreEmpty' => '1',
	'controller' => 'onetable',
	'api' => 'id,name',
	'debug' => '0'
));

$json = $modx->runSnippet("DocLister", $params);
$json = jsonHelper::jsonDecode($json);
$out = array();
foreach($json as $item){
	$out[] = $item->name.'=='.$item->id;
}
return implode("||", $out);
?>