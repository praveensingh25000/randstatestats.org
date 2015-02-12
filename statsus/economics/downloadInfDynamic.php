<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

if(!isset($_SESSION['searchedfieldsonestage'])){
	header('location: infData.php');
	exit;
}

$searchedfields = $_SESSION['searchedfieldsonestage'];

$dbid = trim($searchedfields['dbid']);

$sql = $_SESSION['query'];
$admin = new admin();
$searchedDataResult = $dbDatabase->run_query($sql);

$dataToBeShown = array();

if((mysql_num_rows($searchedDataResult))>0){
	while($data = mysql_fetch_assoc($searchedDataResult)){
		$dataToBeShown[$data['item_name']][$data['area_name']][$data['Year']][$data['Month']] = $data['Value'];
	}
}


$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);

$rows = array();
$i=0;

$monthsNames = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 =>"Apr", 5 => "May", 6 => "June", 7 => "July", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");

foreach($dataToBeShown as $keyItemName => $itemAreas){
	foreach($itemAreas as $keyAreaName => $years){
		foreach($years as $keyYear => $months){
			$rows[$i]['Category'] = $keyItemName;
			$rows[$i]['Area'] = $keyAreaName;
			$rows[$i]['year'] = $keyYear;
			

			for($k=$searchedfields['smonth'];$k<=$searchedfields['emonth'];$k++){
				if($timeIntervalSettings['embed_m'] == 'Y'){
						if($k>9){
							$monthStr = 'm'.$k;
						} else {
							$monthStr = 'm0'.$k;
						}
						$monthName = $monthsNames[$k];
						if(isset($months[$monthStr])){
							$rows[$i][$monthName] = $months[$monthStr];
						} else if(isset($months[strtoupper($monthStr)])){
							$rows[$i][$monthName] = $months[strtoupper($monthStr)];
						} else {
							$rows[$i][$monthName] = 'N/A';
						}
				}
			}
			foreach($months as $keyMonth => $value ){
				if($timeIntervalSettings['embed_m'] == 'Y'){
					$month = (int)substr($keyMonth,1);
				} else {
					$month = (int)$keyMonth;
				}
				if($month >12){
					$rows[$i]['Avg.'] = $value;
					break;
				} 
			}

			if(!isset($rows[$i]['Avg.'])){
				$rows[$i]['Avg.'] = 'N/A';
			}

			$i++;
		}
	}
}

$headerColumns = array();

$dbid = $_SESSION['databaseDetail']['id'];

if(isset($dbid) && ($dbid != '')){
	$databaseDetail = $admin->getDatabase($dbid);
	$dbname			= stripslashes($databaseDetail['db_title']);
	$dbsource		= stripslashes($databaseDetail['db_datasource']);
	$description	= stripslashes($databaseDetail['db_description']);
	$miscellaneous	= stripslashes($databaseDetail['db_misc']);
	$nextupdate		= stripslashes($databaseDetail['db_nextupdate']);
	$table			= stripslashes($databaseDetail['table_name']);
	$db_geographic	= stripslashes($databaseDetail['db_geographic']);
	$db_periodicity	= stripslashes($databaseDetail['db_periodicity']);
	$db_dataseries	= stripslashes($databaseDetail['db_dataseries']);
	$db_datasource	= stripslashes($databaseDetail['db_source']);
	$dateupdated	= stripslashes($databaseDetail['date_added']);
	$db_datasourcelink	= stripslashes($databaseDetail['db_sourcelink']);

} else {
	
	$db_datasourcelink	= (isset($_SESSION['databaseDetail']['db_sourcelink']))?stripslashes($_SESSION['databaseDetail']['db_sourcelink']):'';
	$dbsource			= (isset($_SESSION['databaseDetail']['db_datasource']))?stripslashes($_SESSION['databaseDetail']['db_datasource']):'';

}

if(count($rows)>0){

	$firstRow = $rows[0];
	
	foreach($rows as $key => $data){
		$firstRow = $data;
		break;
	}

	foreach($firstRow as $keyField => $fieldValue){
		$headerColumns[] = ucfirst($keyField);
	} 


	$text[] = count($rows)." of ".count($rows)." allowed matches returned.";
	$date[] = date('D M d H:i:s Y');

	if(isset($_GET['type']) && $_GET['type']=='csv') {

		$dbsourcetext = "Source: Rand ".$siteMainDBDetail['database_label'].', based on '.$db_datasource;
		if($db_datasourcelink != ''){
			$dbsourcetext .= '( '.$db_datasourcelink.' )';
		}

		$dbArraySource[] = $dbsourcetext;


		if(isset($_SESSION['databaseDetail'])){
			$filename = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $_SESSION['databaseDetail']['db_name']);
			$filename = str_replace(' ', '-', $filename);
		} else {
			$filename = "data";
		}

		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename='.$filename.'.csv');

		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');

		fputcsv($output, $headerColumns); ////output the column headings
		
		foreach($rows as $keyData => $rowData){
			$rows = array();
			foreach($rowData as $keyField => $fieldValue){ 
				$rows[] = $fieldValue; 
			}
			fputcsv($output, $rows);
		}
		
		fputcsv($output, $dbArraySource);

		fputcsv($output, $text);

		fputcsv($output, $date);
	}else{	
		
		$dbsourcetext = "Source: Rand ".$siteMainDBDetail['database_label'].', based on ';
		if($db_datasourcelink != ''){
			$dbsourcetext .= '<a href="'.$db_datasourcelink.'">';
		}

		$dbsourcetext .= $db_datasource;

		if($db_datasourcelink != ''){
			$dbsourcetext .= '</a>';
		}

		$dbArraySource[] = $dbsourcetext;

		if(isset($_SESSION['databaseDetail'])){
			$filename = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $_SESSION['databaseDetail']['db_name']);
			$filename = str_replace(' ', '-', $filename);
		} else {
			$filename = "data";
		}

		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment;Filename=".$filename.".xls");
		
		

		echo "<html>";
		echo "<body>";
		echo "<table border='1'>";

		echo "<tr>";

		foreach($rows as $key => $data){
			$firstRow = $data;
			break;
		}

		foreach($firstRow as $keyField => $fieldValue){
		echo "<th>".ucfirst($keyField)."</th>";
		}	
		echo "</tr>";
		
		foreach($rows as $keyData => $rowData){
			echo "<tr>";
			foreach($rowData as $keyField => $fieldValue){ 
				echo "<td>".$fieldValue."</td>";
			}	
		echo "</tr>";
		}

		echo "</table>";

		echo "<table border='0'>";

		echo "<tr><td></td></tr>";

		echo "<tr><td>".$dbsourcetext."</td></tr>";

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
}
?>