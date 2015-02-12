<?php
/******************************************
* @Modified on march 14, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$admin = new admin();

$array= array();

if(isset($_REQUEST['table']) && $_REQUEST['table']!='' && isset($_REQUEST['column']) && $_REQUEST['column']!='' ){

	$table		= $_REQUEST['table'];
	$column		= $_REQUEST['column'];

	$searchedData = $admin->getDistinctColumnValuesUniversal($table , $column);
		
	if(mysql_num_rows($searchedData)>0){

		while($dataSearched = mysql_fetch_assoc($searchedData)){
			if(!is_numeric(stripslashes($dataSearched[$column]))){
				echo "<p>".stripslashes($dataSearched[$column])."</p>";
			} else if($table == 'births' && is_numeric(stripslashes($dataSearched[$column]))){
				echo "<p>".stripslashes($dataSearched[$column])."</p>";
			} else if($table == 'drug' && is_numeric(stripslashes($dataSearched[$column]))){
				echo "<p>".stripslashes($dataSearched[$column])."</p>";
			} else if($table == 'deathszips' && is_numeric(stripslashes($dataSearched[$column]))){
				echo "<p>".stripslashes($dataSearched[$column])."</p>";
			}
		}
	}
}

?>