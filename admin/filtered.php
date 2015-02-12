<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Saket Bisht
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
require($basedir.'../../include/actionHeader.php');

global $dbDatabase;

$admin = new admin();

$array= array();

if(isset($_REQUEST['table']) && $_REQUEST['table']!='' && isset($_REQUEST['column']) && $_REQUEST['column']!='' && isset($_REQUEST['q']) && $_REQUEST['q']!='')
{
	$table		= $_REQUEST['table'];
	$column		= $_REQUEST['column'];
	$searchStr	= $_REQUEST['q'];

	$searchedData = $admin->searchLikeUniversal($table , $column, $searchStr);
	$dataSearchFilter = $dbDatabase->getAll($searchedData);

	if($dataSearchFilter >0){

		$arrayAll = array();

		foreach($dataSearchFilter as $key => $dataSearched)
		$arrayAll[$dataSearched[$column]] = $dataSearched[$column];

		foreach($arrayAll as $key => $value)
		$array[] = array('id' => $value, 'name' => $value);
	}

}

echo json_encode($array);
?>
