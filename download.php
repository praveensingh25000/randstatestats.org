<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/actionHeader.php";

$region = '';
$areaArray = explode(',', $_SESSION['search']['blah']);
foreach($areaArray as $key => $regionname){
	$region .= "'".$regionname."',";
}

$region = substr($region, 0 , -1);

$asylem = new asylem();

$resource = $asylem->getResultRegion($region);

$resourceAll = $asylem->getResultRegionAll();

$searchedTotalRows = $db->count_rows($resource);

$totalRows = $db->count_rows($resourceAll);

$array = array();

$yearArray = array();
for($i=$_SESSION['search']['syear'];$i<=$_SESSION['search']['eyear'];$i++){ 
	$yearArray[] = $i;
}

if($searchedTotalRows >0){ 
	$searchedData = $db->getAll($resource);
}

$columnsYear = array();
if($searchedTotalRows >0){ 
	foreach($searchedData as $keyRegion => $rowData){
		if(empty($columnsYear)){
			foreach($rowData as $keyColumn => $value){
				$columnsYear[] = $keyColumn;
			}
			break;
		}
	}
}

$columsnData = array();
if($searchedTotalRows >0){ 
	foreach($searchedData as $keyRegion => $rowData){
		
		$columsnData[$rowData['Region']] = array();
		for($i=$_SESSION['search']['syear'];$i<=$_SESSION['search']['eyear'];$i++){
			if(in_array($i, $columnsYear)){ 
				
				$columsnData[$rowData['Region']][] = (int)$rowData[$i];  
			} else {
				$columsnData[$rowData['Region']][] = "NA"; 
			} 
		}
	}
}

//output the column headings
$headerColumns = array('Region');
for($i=$_SESSION['search']['syear'];$i<=$_SESSION['search']['eyear'];$i++){ 
	$headerColumns[] = $i;
}

//output the row data
foreach($columsnData as $region => $regions){
	$rowData = array();
	$rowData[] = $region;
	foreach($regions as $keyYear => $regioncolumnval){
		$rowData[] = $regioncolumnval;
	}
}

//echo "<pre>";print_r($columsnData);die;

//discription of the doc
$text[] = $searchedTotalRows." of ".$totalRows." allowed matches returned.";
$date[] = date('D M d H:i:s Y');

if(isset($_GET['type']) && $_GET['type']=='csv') {
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=data.csv');

	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	fputcsv($output, $headerColumns); ////output the column headings

	fputcsv($output, $rowData); ////output the rows data

	fputcsv($output, $text);

	fputcsv($output, $date);
}else{	
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment;Filename=table.xls");

	echo "<html>";
	echo "<body>";
	echo "<table border='1'>";

	echo "<tr>";
	foreach($headerColumns as $columns){
	echo "<th>".$columns."</th>";
	}	
	echo "</tr>";

	echo "<tr>";
	foreach($rowData as $rows){
	echo "<td>".$rows."</td>";
	}	
	echo "</tr>";

	echo "</table>";

	echo "<table border='0'>";

	echo "<tr><td></td></tr>";

	echo "<tr>";
	foreach($text as $textdesctiption){
	echo $textdesctiption;
	}	
	echo "</tr>";

	echo "<tr>";
	foreach($date as $textdate){
	echo $textdate;
	}	
	echo "</tr>";

	echo "</table>";
	echo "</body>";
	echo "</html>";
}
?>