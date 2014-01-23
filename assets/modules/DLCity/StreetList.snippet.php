<?php
$cityID = isset($cityID) ? (int)$cityID : 0;
$pageID = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$selfName = isset($selfName) ? $selfName : '';
$out = $wrap = $cityValue = '';

if(!empty($cityID)){
   $cityValue = $modx->getTemplateVar($cityID, "id", $pageID);
   $cityValue = isset($cityValue['value']) ? $cityValue['value'] : '';
}

if(!empty($selfName)){
   $streetValue = $modx->getTemplateVar($selfName, "id", $pageID);
   $streetID = isset($streetValue['id']) ? $streetValue['id'] : '';
   $streetValue = isset($streetValue['value']) ? $streetValue['value'] : '';
   $js = "<script>
      $('tv".$cityID."').addEvent('change',function(event) {
	if($('tv".$cityID."')) {
		var getStreet = new Ajax('/assets/modules/DLCity/StreetList.php', {method:'post', postBody:'city='+$('tv".$cityID."').value, onComplete:showStreet});
		getStreet.request();
	}
      });
	function showStreet(request) {
		var elm = $('tv".$streetID."');
		if (elm) {elm.innerHTML = request;
		elm.style.display = request!='' ? 'inline' :  'none';
      }
   }</script>";
   $out = $js;
   $wrap = $modx->runSnippet("DocLister", array(
            "controller" => "onetable",
            "table" => "street",
            "idType"=>"documents",
            "documents"=>"",
            "ignoreEmpty"=>"1",
            "streetDefault" => $streetValue,
            "prepare" => function(array $data = array(), DocumentParser $modx, onetableDocLister $_DocLister){
                  $data['selected'] = ($_DocLister->getCFGDef('streetDefault')==$data['id']) ? 'selected="selected"' : '';
                  return $data;
            },
            "addWhereList" => "parent_id=".$cityValue,
            "tpl" => '@CODE: <option value="[+id+]" [+selected+]>[+name+]</option>',
        ));
}
$field_html =  '<select id="tv[+field_id+]" name="tv[+field_id+]" size="1" onchange="documentDirty=true;">'.$wrap.'</select>';
return $out.$field_html;
?>