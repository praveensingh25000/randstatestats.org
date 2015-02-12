<?php
/******************************************
* @Modified on Dec 06,June 21, 2012
* @Package: Rand
* @Developer: Baljinder Singh<praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."";
include_once $basedir."/include/actionHeader.php";

$footnotesIds ='';

if(isset($_SESSION['query'])){
	if(is_array($_SESSION['query'])){
		$searchedData = $_SESSION['query'];
	} else {
		$sql = $_SESSION['query'];
		$admin = new admin();
		$searchedDataResult = $dbDatabase->run_query($sql);
		$searchedData = $dbDatabase->getall($searchedDataResult);
	}	
} else if($_SESSION['downloaddata']){
	$searchedData = $_SESSION['downloaddata'];	
} else {
	header('Location: ' . $_SERVER['HTTP_REFERER']);
	exit;
}

$searchedTotalRows = count($searchedData);
$totalRows = count($searchedData);

$headerColumns = $rows = array();

$dbid = $_SESSION['databaseDetail']['id'];

$allDbTables = $admin->getDatabaseTables($dbid);

foreach($allDbTables as $keyTable => $tableDetail){
	$tablesArray[] = $tableDetail['table_name'];
}

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
	$formfootnotes = stripslashes($databaseDetail['formfootnotes']);
	$dbnames		= array('id'=> $dbname);

	$formfootnotes = stripslashes($databaseDetail['formfootnotes']);

} else {
	
	$db_datasourcelink	= (isset($_SESSION['databaseDetail']['db_sourcelink']))?stripslashes($_SESSION['databaseDetail']['db_sourcelink']):'';
	$dbsource			= (isset($_SESSION['databaseDetail']['db_datasource']))?stripslashes($_SESSION['databaseDetail']['db_datasource']):'';

}

if(count($searchedData)>0){

	$firstRow = $searchedData[0];
	
	foreach($firstRow as $keyField => $fieldValue){
		$headerColumns[] = ucfirst($keyField);
	} 


	//$text[] = $searchedTotalRows." of ".$totalRows." allowed matches returned.";

	$text[] = $formfootnotes;

	$date[] = date('D M d H:i:s Y');

	if(isset($_GET['type']) && $_GET['type']=='csv') {

		if($db_datasource != ''){
		//added by Pragati Garg on 7/29/2013
		$dbsourcetext = "Data Source: ".$db_datasource;
		if($db_datasourcelink != ''){
			$dbsourcetext .= '( '.$db_datasourcelink.' )';
		}
		}else{
			$dbsourcetext='';
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

		fputcsv($output, $dbnames); //output the form name

		fputcsv($output, array('id'=>'')); //output line break

		fputcsv($output, $headerColumns); ////output the column headings
		
		foreach($searchedData as $keyData => $rowData){

			if(isset($rowData['Footnote'])){
				if($rowData['Footnote']!=''){
					$footnotesIds .= $rowData['Footnote'];
				}
				$footnotesExplodedIds = explode(",",$footnotesIds);
				$footnotesmergedIds = array_unique($footnotesExplodedIds);
				sort($footnotesmergedIds);

				if(isset($footnotesmergedIds) && !empty($footnotesmergedIds)) {
					include($DOC_ROOT.'/federal_footnotes.php');
					foreach($footnotesmergedIds as $mainKey){
						if(array_key_exists($mainKey,$footNotesArray)){
							$footNote[$mainKey] = $mainKey.':'.$footNotesArray[$mainKey];
						}
					}
				}
			}

			$rows = array();
			foreach($rowData as $keyField => $fieldValue){ 

				$decimal_settings = (isset($databaseDetail['decimal_settings']))?stripslashes($databaseDetail['decimal_settings']):'';

				if($decimal_settings != ''){
					if(is_numeric($fieldValue) && ($fieldValue < 0 || ($fieldValue == 0 && trim(strtolower($keyField))== 'avg.'))) { 

						if(isset($dbname) && $dbname == 'Federal Budget-Receipts and Outlays'){
							$rows[] = str_replace('"','',number_format ($fieldValue));
						} else {
							$rows[] = 'NA';
						}
						
					} else if(is_numeric($fieldValue) && trim(strtolower($keyField)) != 'year' && trim(strtolower($keyField))!= 'age' && trim(strtolower($keyField))!= 'establishment age'){
						if(isset($tablesname) && $tablesname == 'total_persons_naturalized'){

							$rows[] = number_format ($fieldValue, $decimal_settings);

						} else {

							$arrayfield = explode('.',$fieldValue);

							if(isset($arrayfield[1])){
								if($decimal_settings == 0)
									$rows[] = number_format(round($fieldValue));
								else
									$rows[] = number_format ($fieldValue, $decimal_settings);
							} else {
								if(is_numeric($fieldValue) && trim(strtolower($keyField)) == 'area') {
									$rows[] = str_replace('"','',$fieldValue);
								} else {
									$rows[] = str_replace('"','',number_format($fieldValue));
								}
							}
						}

					} else {

						if ((strpos($fieldValue, 'city') !== false) && !empty($tablesArray) && (in_array('uspopest',$tablesArray))) {

							$rows[] =$newstr = str_replace($search='(city)', $replace='', $str=$fieldValue);

						} else if (!is_numeric($fieldValue) && $fieldValue != 'NA' && !empty($tablesArray) && ((in_array('graduates',$tablesArray)) || (in_array('enrollrace',$tablesArray)) || (in_array('leplang',$tablesArray)) || (in_array('leppgm',$tablesArray)) || (in_array('econdis',$tablesArray)) || (in_array('student',$tablesArray)) || (in_array('staffsalary2',$tablesArray)) || (in_array('superpay',$tablesArray)) || (in_array('performancedis',$tablesArray)) || (in_array('districtexpend',$tablesArray)) || (in_array('stateexpend',$tablesArray)))) {			
							
							$arrayfield = explode(' ',trim($fieldValue));	
							
							if(isset($arrayfield[1]) && count($arrayfield) > 1 && trim(strtolower($keyField)) != 'category' && trim(strtolower($keyField)) != 'program' && trim(strtolower($keyField)) != 'language' && trim(strtolower($keyField)) != 'grade' && trim(strtolower($keyField)) != 'cat1' && trim(strtolower($keyField)) != 'cat2') {

								$lastelsement	= array_pop($arrayfield);
								$arrayfieldstr	= implode(' ',$arrayfield);	

								if(strtolower($lastelsement) == 'isd'){
									$rows[] = str_replace('"','',ucwords(strtolower($arrayfieldstr)).' '.strtoupper(strtolower($lastelsement)));
								} else {
									$rows[] = str_replace('"','',ucwords(strtolower($arrayfieldstr)).' '.ucfirst(strtolower($lastelsement)));
								}
								
							} else {
								$rows[] = str_replace('"','',ucwords(strtolower($fieldValue)));
							}			
								
						} else {
							$rows[] = str_replace('"','',$fieldValue);
						}
					} 
				} else {

					if(is_numeric($fieldValue) && ($fieldValue < 0 || ($fieldValue == 0 && trim(strtolower($keyField))== 'avg.'))) { 
						$rows[] = 'NA'; 
					}
					else if (is_numeric($fieldValue) && trim(strtolower($keyField)) != 'year' && trim(strtolower($keyField))!= 'age'){ 	
						$rows[] = $fieldValue; 
					} else {
						$rows[] =$fieldValue; 
					}
				}				
			}	

			fputcsv($output, $rows);
		}
		
		fputcsv($output, array('id'=>'')); //output line break
		
		$footnotes[] = strip_tags($formfootnotes);
		
		fputcsv($output, $footnotes);	

		//fputcsv($output, array('id'=>'')); //output line break

		if(!empty($footNote)) {		
		fputcsv($output, $footNote);		
		}
		
		fputcsv($output, array('id'=>'')); //output line break

		fputcsv($output, $dbArraySource);

		//fputcsv($output, $text);
		fputcsv($output, array('id'=>'')); //output line break

		fputcsv($output, $date);

	} else {	
		if($db_datasource != ''){		
			//modified by Praveen Singh on 23-08-2013
			$dbsourcetext = "<b>Data Source:</b> ";
			if($db_datasourcelink != ''){
				$dbsourcetext .= ' <a href="'.$db_datasourcelink.'">'.$db_datasource.'</a>';
			} else {
				$dbsourcetext .= $db_datasource;
			}			
		}else{
			$dbsourcetext='';
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
		echo "<th colspan='2'>".$dbname."</th>";
		echo "</tr>";

		echo "<tr>";
		echo "</tr>";

		echo "<tr>";
		foreach($firstRow as $keyField => $fieldValue){
		echo "<th>".ucfirst($keyField)."</th>";
		}	
		echo "</tr>";
		
		foreach($searchedData as $keyData => $rowData){

			if(isset($rowData['Footnote'])){
				if($rowData['Footnote']!=''){
					$footnotesIds .= $rowData['Footnote'];
				}
				$footnotesExplodedIds = explode(",",$footnotesIds);
				$footnotesmergedIds = array_unique($footnotesExplodedIds);
				sort($footnotesmergedIds);

				if(isset($footnotesmergedIds) && !empty($footnotesmergedIds)) {
					include($DOC_ROOT.'/federal_footnotes.php');
					foreach($footnotesmergedIds as $mainKey){
						if(array_key_exists($mainKey,$footNotesArray)){
							$footNote[$mainKey] = $footNotesArray[$mainKey];
						}
					}
				}
			}

			//echo '<pre>';print_r($footNote);echo '</pre>';

			echo "<tr>";
			foreach($rowData as $keyField => $fieldValue){ 

				$decimal_settings = (isset($databaseDetail['decimal_settings']))?stripslashes($databaseDetail['decimal_settings']):'';

				if($decimal_settings != ''){
					if(is_numeric($fieldValue) && ($fieldValue < 0 || ($fieldValue == 0 && trim(strtolower($keyField))== 'avg.'))) { 

						if(isset($dbname) && $dbname == 'Federal Budget-Receipts and Outlays'){
							echo "<td>".str_replace('"','',number_format ($fieldValue))."</td>";
						} else {
							echo "<td> NA </td>"; 
						}						

					} else if(is_numeric($fieldValue) && trim(strtolower($keyField)) != 'year' && trim(strtolower($keyField))!= 'age' && trim(strtolower($keyField))!= 'establishment age'){
						
						if(isset($tablesname) && $tablesname == 'total_persons_naturalized'){

							echo "<td>".number_format ($fieldValue, $decimal_settings)."</td>";

						} else {

							$arrayfield = explode('.',$fieldValue);

							if(isset($arrayfield[1])){
								if($decimal_settings == 0)
									echo "<td>".number_format(round($fieldValue))."</td>";
								else
									echo "<td>".number_format ($fieldValue, $decimal_settings)."</td>";
							} else {

								if(is_numeric($fieldValue) && trim(strtolower($keyField)) == 'area') {
									echo "<td>".str_replace('"','',$fieldValue)."</td>";
								} else {
									echo "<td>".str_replace('"','',number_format($fieldValue))."</td>";
								}							
							}
						}

					} else {

						if ((strpos($fieldValue, 'city') !== false) && !empty($tablesArray) && (in_array('uspopest',$tablesArray))) {

							$newstr = str_replace($search='(city)', $replace='', $str=$fieldValue);
							echo "<td>".$newstr."</td>";

						} else if(!is_numeric($fieldValue) && $fieldValue != 'NA' && !empty($tablesArray) && ((in_array('graduates',$tablesArray)) || (in_array('enrollrace',$tablesArray)) || (in_array('leplang',$tablesArray)) || (in_array('leppgm',$tablesArray)) || (in_array('econdis',$tablesArray)) || (in_array('student',$tablesArray)) || (in_array('staffsalary2',$tablesArray)) || (in_array('superpay',$tablesArray)) || (in_array('performancedis',$tablesArray)) || (in_array('districtexpend',$tablesArray)) || (in_array('stateexpend',$tablesArray)))) {							
							$arrayfield = explode(' ',trim($fieldValue));							
							if(isset($arrayfield[1]) && count($arrayfield) > 1 && trim(strtolower($keyField)) != 'category' && trim(strtolower($keyField)) != 'program' && trim(strtolower($keyField)) != 'language' && trim(strtolower($keyField)) != 'grade' && trim(strtolower($keyField)) != 'cat1' && trim(strtolower($keyField)) != 'cat2') {
								$lastelsement	= array_pop($arrayfield);
								$arrayfieldstr	= implode(' ',$arrayfield);	
								if(strtolower($lastelsement) == 'isd'){
									echo "<td>".str_replace('"','',ucwords(strtolower($arrayfieldstr)).' '.strtoupper(strtolower($lastelsement)))."</td>";
								} else {
									echo "<td>".str_replace('"','',ucwords(strtolower($arrayfieldstr)).' '.ucfirst(strtolower($lastelsement)))."</td>";
								}
								
							} else {
								echo "<td>".str_replace('"','',ucwords(strtolower($fieldValue)))."</td>";
							}			
								
						} else {
							echo "<td>".str_replace('"','',$fieldValue)."</td>";
						}
					} 

				} else {

					if(is_numeric($fieldValue) && ($fieldValue < 0 || ($fieldValue == 0 && trim(strtolower($keyField))== 'avg.'))) { 
						
						echo "<td> NA </td>"; 
					}
					else if (is_numeric($fieldValue) && trim(strtolower($keyField)) != 'year' && trim(strtolower($keyField))!= 'age'){
						
						echo "<td>".$fieldValue."</td>"; 
						
					} else {

						echo "<td>".$fieldValue."</td>"; 
					}
				}				
			}	
		echo "</tr>";
		}

		echo "</table>";

		echo "<table border='0'>";		

		echo "<tr><td></td></tr>";

		echo "<tr>";
		foreach($text as $textdesctiption){
		echo $textdesctiption;
		}	
		echo "</tr>";

		if(!empty($footNote)) {		
		foreach($footNote as $NoteId=> $NoteDescr){		
		echo "<tr>";
		echo '<strong>'.$NoteId.'&nbsp;:&nbsp;</strong>'.$NoteDescr.'';
		echo "</tr>";	
		}		
		}

		echo "<tr>".$dbsourcetext."</tr>";

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