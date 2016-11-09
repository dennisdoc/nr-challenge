<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::get('/test/find', function()
{
	header('Content-type: application/json; charset=UTF-8');
	//http://www.cnpq.br/web/guest/licitacoes?p_p_id=licitacoescnpqportlet_WAR_licitacoescnpqportlet_INSTANCE_BHfsvMBDwU0V&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=2&pagina=2&delta=10&registros=1298
	$html=file_get_contents("http://www.cnpq.br/web/guest/licitacoes?p_p_id=licitacoescnpqportlet_WAR_licitacoescnpqportlet_INSTANCE_BHfsvMBDwU0V&p_p_lifecycle=0&p_p_state=normal&p_p_mode=view&p_p_col_id=column-2&p_p_col_pos=1&p_p_col_count=2&pagina=2&delta=10&registros=1298");
	$html= mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
	$html = tidy_repair_string($html);

	$doc = new DomDocument();
	$doc->loadHtml($html);
	$xpath = new DomXPath($doc);
	// Now query the document:
	$array=array();
	$location='//table[@class="table table-bordered table-hover table-striped"]/tbody/tr/td/div';
	foreach ($xpath->query($location.'/h4') as $node) {
	  $object=array();
	  $object['licitacao']=html_entity_decode($node->nodeValue);
	  array_push($array, $object);
	}
	$i=0;
	foreach ($xpath->query($location.'/div/div/p') as $node) {
	  $array[$i]['objeto']=html_entity_decode($node->nodeValue);
	  $i++;
	}
	$i=0;
	foreach ($xpath->query($location.'/div/div/span') as $node) {
	  $array[$i]['publicacoes']=html_entity_decode($node->nodeValue);
	  $i++;
	}
	return json_encode($array);
	
});

