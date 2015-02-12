<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/actionHeader.php";

global $db;

$asylem = new asylem();

$stringSearch = $_REQUEST['q'];
$resource = $asylem->searchCountryLike($stringSearch);

$totalRows = $db->count_rows($resource);

$array = array();

if($totalRows >0){
	$countries = $db->getAll($resource);
	foreach($countries as $key => $country)
	$array[] = array('id' => $country['Region'], 'name' => $country['Region']);
}

//$array = array(0 => array('id' => '123', 'name' => "Slurms MacKenzie"), 1 => array('id' => '555', 'name' => "Bob Hoskins"));
echo json_encode($array);
?>