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

$dbid = 1;

$admin = new admin();

$databaseDetail = $admin->getDatabase($dbid);
if(!empty($databaseDetail)){
	$dbname		= stripslashes($databaseDetail['db_name']);
	$dbsource	= stripslashes($databaseDetail['db_source']);
	$description= stripslashes($databaseDetail['db_description']);
	$miscellaneous	= stripslashes($databaseDetail['db_misc']);
	$table		= stripslashes($databaseDetail['table_name']);
	$db_graph = $databaseDetail['db_graph'];
	$stringSearch = $_REQUEST['q'];

	$resource = $admin->searchLikeUniversal($table , 'Region', $stringSearch);
	$totalRows = $db->count_rows($resource);
	$array = array();

	if($totalRows >0){
		$countries = $db->getAll($resource);
		foreach($countries as $key => $country)
		$array[] = array('id' => $country['Region'], 'name' => $country['Region']);
	}

	//$array = array(0 => array('id' => '123', 'name' => "Slurms MacKenzie"), 1 => array('id' => '555', 'name' => "Bob Hoskins"));
	echo json_encode($array);
}


?>