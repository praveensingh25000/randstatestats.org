<?php
/******************************************
* @Modified on April 19, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

global $db;

$searchedfields = $_SESSION['searchedfieldsonestage'];

//echo "<pre>";print_r($searchedfields);echo "</pre>";

if(!isset($searchedfields['dbid'])){
	header('location: index.php');
}

$admin = new admin();

$joinStr = $tablesStr = "";
$fieldsStrWhere = '';
$columnstobefetched = '';

$catname = "";
$yearArray = array();
$dbid = trim($searchedfields['dbid']);
$databaseDetail = $admin->getDatabase($dbid);

$nograph = true;

$quatersArray = $monthssArray = $columnsToBeShownAsGraph = array();

if(!empty($databaseDetail)){
	$_SESSION['databaseDetail'] = $databaseDetail;
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
	$formfootnotes		= stripslashes($databaseDetail['formfootnotes']);
	
	$allDbTables = $admin->getDatabaseTables($dbid);

	foreach($allDbTables as $keyTable => $tableDetail){
		$tablesStr .= $tableDetail['table_name'].", ";
	}
	
	$tablesStr = substr($tablesStr, 0 , -2);

	$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);
	$search_criteria_details = $admin->selectAllSearchCriteria($dbid);
	$joinConditions = $admin->getAllJoinConditions($dbid);


	if(count($joinConditions)>0){
		foreach($joinConditions as $joinKey => $joinDetail){
			$joinStr .= " and ".$joinDetail['primary_table'].".".$joinDetail['primary_table_column']." = ".$joinDetail['foreign_table'].".".$joinDetail['foreign_table_column']." ";
		}
	}

	$columnDisplaySettings = $admin->getTableColumnsDisplaySettings($dbid);

	foreach($columnDisplaySettings as $keyColumnDisplay => $columnSettings){
		
		if($columnSettings['column_name']!='avg' && $columnSettings['column_name']!='Avg' && $columnSettings['column_name']!='Avg.' && $columnSettings['column_name']!='avg.' && $columnSettings['column_name']!='Average' && $columnSettings['column_name']!='average' && trim($columnSettings['column_name']) != 'tot' && trim($columnSettings['column_name']) != 'Tot' ){
			$tableNameSettings	= $columnSettings['table_name'];
			$columnNameSettings = $columnSettings['column_name'];
			$columnstobefetched .= $tableNameSettings.'.'.$columnNameSettings." as `".$columnSettings['display_name']."`,";
		}
	}
	
	if(count($search_criteria_details)>0){

		$columnsWhere = array();

		foreach($search_criteria_details as $searchcriteriakey => $searchCriteriaDetail){
				
			$tableSearchField = $searchCriteriaDetail['belongs_to'];
			$columnSearchField = $searchCriteriaDetail['coloum_name'];
			
			if(isset($searchedfields['field']) && isset($searchedfields['field'][$searchCriteriaDetail['id']]) && !empty($searchedfields['field'][$searchCriteriaDetail['id']])){
			

				if(is_array($searchedfields['field'][$searchCriteriaDetail['id']])){
					
					$columnsWhere[$tableSearchField][$columnSearchField][] = $searchedfields['field'][$searchCriteriaDetail['id']];

				} else if($searchCriteriaDetail['type'] == 'multiple' && $searchCriteriaDetail['control_type'] == 'select'){

					$columnsWhere[$tableSearchField][$columnSearchField][] = $searchedfields['field'][$searchCriteriaDetail['id']];

				} else {

					$columnsWhere[$tableSearchField][$columnSearchField][] = $searchedfields['field'][$searchCriteriaDetail['id']];

				}

			} else if(isset($searchedfields['allow_all']) && isset($searchedfields['allow_all'][$searchCriteriaDetail['id']])){
					$columnsWhere[$tableSearchField][$columnSearchField] = "All";
			}
			
		}
	
		foreach($columnsWhere as $tablename => $columns){
			foreach($columns as $columnname => $columnvalues){
			
				$columnSettings = $admin->getColumnDisplaySettings($dbid, $tablename, $columnname);
				
				if(isset($columnSettings['display_name'])){

					$columnsToBeShownAsGraph[] = $columnSettings['display_name'];
					$columnstobefetched .= $tablename.'.'.$columnname." as `".$columnSettings['display_name']."`,";
				} else {
					$columnsToBeShownAsGraph[] = $columnname;
					$columnstobefetched .= $tablename.'.'.$columnname.",";
				}

				$inStr = '';
				if(is_array($columnvalues)){
					foreach($columnvalues as $columnvaluekey => $valueData){
						
						if(is_array($valueData)){
							
							foreach($valueData as $postedFieldKey => $postedFieldValue){
								if(is_integer($postedFieldValue)){
									$inStr .= mysql_real_escape_string($postedFieldValue).", ";
								} else {
									$inStr .= '"'.mysql_real_escape_string($postedFieldValue).'", ';
								}
							}					
							
						} else {
							$values = explode(';',$valueData);
							foreach($values as $postedFieldKey => $postedFieldValue){
								if(is_integer($postedFieldValue)){
									$inStr .= $postedFieldValue.", ";
								} else {
									$inStr .= "'".mysql_real_escape_string($postedFieldValue)."', ";
								}
							}
						}
					}

					$inStr = substr($inStr, 0, -2);

					if($inStr!=''){
						$fieldsStrWhere .= " and ".$tablename.".".$columnname." in (".$inStr.") ";
					}
				}

			}
		}
	}
	
	$columns = unserialize($timeIntervalSettings['columns']);

	if(isset($searchedfields['syear']) && isset($searchedfields['eyear'])){
		$embedY = '';
		$embedM = '';
		$embedQ = '';
		if($timeIntervalSettings['embed_y'] == 'Y'){
			$embedY = 'y';
		}

		if($timeIntervalSettings['embed_m'] == 'Y'){
			$embedM = 'm';
		}

		if($timeIntervalSettings['embed_q'] == 'Y'){
			$embedQ = 'q';
		}

		if(isset($columns['yearsasrows'])){
	
			if(isset($columns['yearsasrows']['columns']) && $columns['yearsasrows']['columns']!=''){
				foreach($columns['yearsasrows']['columns'] as $tablename => $columnname){

					$tablesResultColumn = $admin->showColumns($tablename);
					$totalColumns = $dbDatabase->count_rows($tablesResultColumn);
					$tableColumns = $dbDatabase->getAll($tablesResultColumn);	
					
					$tableColumnsArray = array();
					foreach($tableColumns as $keyColumnCounter => $columnDetail){
						$tableColumnsArray[] = $columnDetail['Field'];
					}
				

					if(in_array($columnname, $tableColumnsArray) || in_array(strtoupper($columnname), $tableColumnsArray)){
						
						$inStrYear = '';

						for($i=$searchedfields['syear'];$i<=$searchedfields['eyear'];$i++){
							$inStrYear .= $i.",";
							$yearArray[] = $i;
						}

						$inStrYear = substr($inStrYear, 0, -1);
						
						$fieldsStrWhere .= " and ".$tablename.".".$columnname." in (".$inStrYear.") ";

						
					}


				}
			}
				
		} else if(isset($columns['yearsascolumns'])){
			$table = $columns['yearsascolumns'];
			$tablesResultColumn = $admin->showColumns($table);
			$totalColumns = $dbDatabase->count_rows($tablesResultColumn);
			$tableColumns = $dbDatabase->getAll($tablesResultColumn);	
			
			$tableColumnsArray = array();
			foreach($tableColumns as $keyColumnCounter => $columnDetail){
				$tableColumnsArray[] = $columnDetail['Field'];
			}
		
			if(isset($searchedfields['syear']) && isset($searchedfields['eyear'])){
				for($i=$searchedfields['syear'];$i<=$searchedfields['eyear'];$i++){
			
					if(in_array($embedY.''.$i, $tableColumnsArray) || in_array(strtoupper($embedY).''.$i, $tableColumnsArray)){
						
						$yearArray[] = $i;

						if($embedY!=''){
							$columnstobefetched .= $table.'.'.$embedY.''.$i." as '".$i."',";
						} else {
							$columnstobefetched .= $table.'.'.$embedY.''.$i.",";
						}
					}
				}
			}


		}

		if(isset($columns['monthsascolumns'])){
			$table = $columns['monthsascolumns'];
			$tablesResultColumn = $admin->showColumns($table);
			$totalColumns = $dbDatabase->count_rows($tablesResultColumn);
			$tableColumns = $dbDatabase->getAll($tablesResultColumn);	
			
			$tableColumnsArray = array();
			foreach($tableColumns as $keyColumnCounter => $columnDetail){
				$tableColumnsArray[] = $columnDetail['Field'];
			}
			
			if(isset($searchedfields['smonth']) && isset($searchedfields['emonth'])){
				for($i=$searchedfields['smonth'];$i<=$searchedfields['emonth'];$i++){
					
					$col = $i;
					if($i<=9){
						$col = '0'.$i;
					}
					if(in_array($embedM.''.$col, $tableColumnsArray) || in_array(strtoupper($embedM).''.$col, $tableColumnsArray)){
						
						$months = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 =>"Apr", 5 => "May", 6 => "June", 7 => "July", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");

						if($embedM!=''){
							$monthssArray[] = $months[$i];
							$columnstobefetched .= $table.'.'.$embedM.''.$col." as '".$months[$i]."',";
						} else {
							$monthssArray[] = $embedM.''.$col;
							$columnstobefetched .= $table.'.'.$embedM.''.$col.",";
						}
					}
				}
			}
		}

		if(isset($columns['quatersascolumns'])){
			$table = $columns['quatersascolumns'];
			$tablesResultColumn = $admin->showColumns($table);
			$totalColumns = $dbDatabase->count_rows($tablesResultColumn);
			$tableColumns = $dbDatabase->getAll($tablesResultColumn);	
			
			$tableColumnsArray = array();
			foreach($tableColumns as $keyColumnCounter => $columnDetail){
				$tableColumnsArray[] = $columnDetail['Field'];
			}
			
			if(isset($searchedfields['squater']) && isset($searchedfields['equater'])){
				for($i=$searchedfields['squater'];$i<=$searchedfields['equater'];$i++){
					
					$col = $i;
					
					if(in_array($embedQ.''.$col, $tableColumnsArray) || in_array(strtoupper($embedQ).''.$col, $tableColumnsArray)){
						
						$quaters = array(1 => "Q1", 2 => "Q2", 3 => "Q3", 4 =>"Q4");
						
						if($embedM!=''){
							$quatersArray[] = $quaters[$i];
							$columnstobefetched .= $table.'.'.$embedQ.''.$col." as '".$quaters[$i]."',";
						} else {
							$quatersArray[] = $embedQ.''.$col;
							$columnstobefetched .= $table.'.'.$embedQ.''.$col.",";
						}
					}
				}
			}
		}

	}

}

foreach($columnDisplaySettings as $keyColumnDisplay => $columnSettings){
		
	if($columnSettings['column_name']=='avg' || $columnSettings['column_name']=='Avg' || $columnSettings['column_name']=='Avg.' || $columnSettings['column_name']=='avg.' || $columnSettings['column_name'] == 'Average' || $columnSettings['column_name']=='average' || trim($columnSettings['column_name']) == 'tot' || trim($columnSettings['column_name']) == 'Tot'  ){
		$tableNameSettings	= $columnSettings['table_name'];
		$columnNameSettings = $columnSettings['column_name'];
		$columnstobefetched .= $tableNameSettings.'.'.$columnNameSettings." as `".$columnSettings['display_name']."`,";
	}
}

if(!isset($allDbTables) || (isset($allDbTables) && count($allDbTables)<=0)){
	echo "There are no tables associated with this form yet. Please contact administrator of site.";
	exit;
}

$columnstobefetched = substr($columnstobefetched, 0, -1);


if($dbid == 12 && $_SESSION['databaseToBeUse'] == 'rand_usa' ){
	$fieldsStrWhere .= " and RecType = '2' ";
}

$sql = "select ".$columnstobefetched." from ".$tablesStr." where 1 ".$fieldsStrWhere." ".$joinStr." order by inflation.year limit 4000 ";

$_SESSION['query'] = $sql ;

$searchedDataResult = $dbDatabase->run_query($sql);

$dataToBeShown = array();
if((mysql_num_rows($searchedDataResult))>0){
	while($data = mysql_fetch_assoc($searchedDataResult)){
		$dataToBeShown[$data['item_name']][$data['area_name']][$data['Year']][$data['Month']] = $data['Value'];
	}
}

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

?>

 <!-- container -->
<section id="container">
 <!-- main div -->
 <div class="main-cell">	

		<?php
		if((count($rows))>0){
			//$firstRow = $searchedData[0];
			foreach($rows as $key => $data){
				$firstRow = $data;
				break;
			}
		?>

			<h1 class="left"><?php echo ucfirst($dbname); ?></h1>

			<div id="" class="right">
				<ul class="submenu">
					<!-- DOWNLOAD ------>
					<li id="download_link" class="">						
						<a href="downloadInfDynamic.php?type=csv">CSV</a>										
					</li>	
					<li id="download_link" class="">						
						<a href="downloadInfDynamic.php?type=excel">EXCEL</a>						
					</li>	
					<!-- DOWNLOAD ------->

					<!-- PRINT PREVIEW -->
					<li id="print_link" class="">	
						 <div id="aside">
							<!-- <a href="javascript:;" onclick="window.print();">Print</a> -->

							<?php if(isset($_GET['graph']) && ($_GET['graph']=='linegraph' || $_GET['graph']=='bargraph')) { ?>
								<a href="javascript:;" id = "graphPrint" >Print</a>
							<?php }  else { ?>
								<a href="javascript:;" id="simplePrint" >Print</a>
							<?php } ?>

						 </div>
					</li>
					
					<!-- SHARING PAGE -->			
					<li class=""><span class='st_sharethis_custom'>&nbsp;</span>
					</li>
					<!-- /SHARING PAGE -->	

				</ul>
				<div class="clear pT10"> </div>
			</div>
			<div class="clear pT10"></div>

			<div class="search-table-data <?php if((!isset($_GET['graph']))){ echo "toPrint"; } ?>" style="<?php if((isset($_GET['graph']))){  ?> display:none; <?php } ?>" >
			<table id="" class="data-table">

				<thead>
				<tr>
					<?php foreach($firstRow as $keyField => $fieldValue){ ?>
					<th><?php echo ucfirst($keyField); ?></th>
					<?php } ?>
				</tr>
				</thead>
				<tbody>
				<?php 
				$areaArray = $colorsarray = array();
				$colors = array("red", "green", "violet", "maroon", "pink", "yellow", "black", "grey", "BlueViolet", "Chartreuse", "Crimson", "DarkMagenta", "blue", "Darkorange");
				$i=0;
				$arrayColumns = array();
				foreach($rows as $key => $rowData){
					
					$strGraph = '';
				?>
				<tr>
					<?php foreach($rowData as $keyField => $fieldValue){ 
						if(!is_integer($keyField)){
							$strGraph.= $fieldValue.'-';
						}else{
							$arrayColumns[$i][] = (double)$fieldValue;
						}
					?>

					<td align="left">
						<?php include($DOC_ROOT.'decimal.php'); ?>
							<!-- <?php 
							if(is_numeric($fieldValue) && ($fieldValue < 0 || ($fieldValue == 0 && trim(strtolower($keyField))== 'avg.'))) { 
								echo 'NA'; 
							}
							else if(is_numeric($fieldValue) && trim(strtolower($keyField)) != 'year' && trim(strtolower($keyField))!= 'age'){ 
								
								$arrayfield = explode('.',$fieldValue);
							
								if (isset($arrayfield[1]) && $arrayfield[1] >0) {
									echo number_format ($fieldValue, 2);
								} else {
									echo number_format ($fieldValue);
								}

							} else { echo $fieldValue; } ?> -->
					</td>

					<?php } ?>
				</tr>
				<?php 
							
					$strGraph = substr($strGraph,0,-1);
					$areaArray[$i] = $strGraph;

					$colorpick = array_rand($colors);
					$colorsarray[$i] = $colors[$colorpick];

					$i++;
				} 
				
			
				

				$yearQuater = $yearMonth= array();
				if(isset($columns['quatersascolumns']) && isset($columns['yearsasrows']) && isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SQ-SY-EQ-EY' || $timeIntervalSettings['time_format'] == 'SY-EY')){		// When data is as year q1, q2, q3, q4
					$tempYears = array();
					$colorsarray = array();
					foreach($yearArray as $keyY => $year){
						foreach($quatersArray as $keyQ => $quater){
							//$tempYears[] = $quater.'/'.substr($year, -2); 
							$tempYears[] = $quater.'/'.$year; 

							$yearQuater[$year][$quater] = '';
						}
					}
					$yearArray = $tempYears;
					
					$columnsArrayData = array();
					
					$searchedDataResult = $dbDatabase->run_query($sql);

					while($rowData = mysql_fetch_assoc($searchedDataResult)){ 
					
						if( (array_key_exists('Area', $rowData) || array_key_exists('area', $rowData)) && (array_key_exists('Category', $rowData) || array_key_exists('category', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
							
							$area = (array_key_exists('Area', $rowData))?$rowData['Area']:$rowData['area'];
							$category = (array_key_exists('Category', $rowData))?$rowData['Category']:$rowData['category'];
							$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

							$columnsArrayData[$area][$category][$year] = $rowData;

						} else if((array_key_exists('Origin', $rowData) || array_key_exists('origin', $rowData)) && (array_key_exists('Category', $rowData) || array_key_exists('category', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
							
							$origin = (array_key_exists('Origin', $rowData))?$rowData['Origin']:$rowData['origin'];
							$category = (array_key_exists('Category', $rowData))?$rowData['Category']:$rowData['category'];
							$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

							$columnsArrayData[$origin][$category][$year] = $rowData;
						}
						 else if((array_key_exists('State', $rowData) || array_key_exists('state', $rowData)) && (array_key_exists('Category', $rowData) || array_key_exists('category', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
							
							$origin = (array_key_exists('State', $rowData))?$rowData['State']:$rowData['state'];
							$category = (array_key_exists('Category', $rowData))?$rowData['Category']:$rowData['category'];
							$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

							$columnsArrayData[$origin][$category][$year] = $rowData;
						}

					}

					$arrayColumns = array();
					$areaArray = array();
					

					foreach($columnsArrayData as $area => $areaData){
						foreach($areaData as $category => $catData){
							$keyForData = $area.'-'.$category;
							
							$arrayToBeInserted = array();
							$areaArray[] = $keyForData;
							foreach($yearQuater as $year => $yearQ){
								if(array_key_exists($year, $catData)){

									foreach($yearQ as $quat => $quaterVal){
										if(array_key_exists($quat, $catData[$year])){
											$arrayToBeInserted[] = (double)$catData[$year][$quat];
										} else {
											$arrayToBeInserted[] = -0;
										}
									}
								}
							}

							$arrayColumns[] = $arrayToBeInserted;
							$colorpick = array_rand($colors);
							$colorsarray[] = $colors[$colorpick];
						}
					}

			
				} else if(isset($columns['monthsascolumns']) && isset($columns['yearsasrows']) && isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SM-SY-EM-EY')){		// When data is as months m01, m02, m03, m04 .. so on

					
					$tempMonths = array();
					$colorsarray = array();
					foreach($yearArray as $keyY => $year){
						foreach($monthssArray as $keyQ => $month){
							$monthsArr = array_flip($months);
							$monthdigit = $monthsArr[$month];
							if($monthdigit<10){
								$monthdigit = '0'.$monthdigit;
							}
							$tempMonths[] = $year."-".$monthdigit.'-01'; 
							$yearMonth[$year][$month] = '';
						}
					}
					$yearArray = $tempMonths;
										
					$columnsArrayData = array();
					$searchedDataResult = $dbDatabase->run_query($sql);
					while($rowData = mysql_fetch_assoc($searchedDataResult)){ 

						if( (array_key_exists('Area', $rowData) || array_key_exists('area', $rowData) || array_key_exists('Park', $rowData) || array_key_exists('park', $rowData)) && (array_key_exists('Category', $rowData) || array_key_exists('category', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
				
							if(array_key_exists('Area', $rowData) || array_key_exists('area', $rowData)){

								$area = (array_key_exists('Area', $rowData))?$rowData['Area']:$rowData['area'];
								$category = (array_key_exists('Category', $rowData))?$rowData['Category']:$rowData['category'];
								$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

								$columnsArrayData[$area][$category][$year] = $rowData;

							} else if(array_key_exists('Park', $rowData) || array_key_exists('park', $rowData)){

								$area = (array_key_exists('Park', $rowData))?$rowData['Park']:$rowData['park'];
								$category = (array_key_exists('Category', $rowData))?$rowData['Category']:$rowData['category'];
								$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

								$columnsArrayData[$area][$category][$year] = $rowData;
							}else if((array_key_exists('State', $rowData) || array_key_exists('state', $rowData)) && (array_key_exists('Category', $rowData) || array_key_exists('category', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
								
								$origin = (array_key_exists('State', $rowData))?$rowData['State']:$rowData['state'];
								$category = (array_key_exists('Category', $rowData))?$rowData['Category']:$rowData['category'];
								$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

								$columnsArrayData[$origin][$category][$year] = $rowData;
							}
						}

					}

					
					
					$arrayColumns = array();
					$areaArray = array();
					foreach($columnsArrayData as $area => $areaData){
						foreach($areaData as $category => $catData){
							$keyForData = $area.'-'.$category;
							
							$arrayToBeInserted = array();
							$areaArray[] = $keyForData;
							foreach($yearMonth as $year => $yearQ){
								if(array_key_exists($year, $catData)){

									foreach($yearQ as $quat => $quaterVal){
										if(array_key_exists($quat, $catData[$year])){
											$arrayToBeInserted[] = (double)$catData[$year][$quat];
										} else {
											$arrayToBeInserted[] = 0;
										}
									}
								}
							}

							$arrayColumns[] = $arrayToBeInserted;
							$colorpick = array_rand($colors);
							$colorsarray[] = $colors[$colorpick];
						}
					}

			
				} 

				$yearjson = json_encode($yearArray);	// Chart Bottom Labels

				$regionjson = json_encode($areaArray);	// Chat Keys

				$jsoncolumn =  json_encode($arrayColumns); // Chart Data

				$colorjson = json_encode($colorsarray);	// Chart Color Codes

				$jsoncolumn = substr($jsoncolumn, 0, -1);
				$jsoncolumn = substr($jsoncolumn, 1);
				
				
				?>

				</tbody>
			</table>
			</div>
			<!-- graph -->

			<?php include($DOC_ROOT."/graphInclude.php"); ?>

			<!-- Show Notes -->
			<?php include($DOC_ROOT."/showNotes.php"); ?>
			<!-- Show Notes -->

			<p>&nbsp;</p>
			<p class="fontbld">
			<?php if(($dbid == '20' || $dbid == '26') && $_SESSION['databaseToBeUse'] == 'rand_usa'){ ?>
			<strong>Note:&nbsp;</strong>Data returned as persons per square mile.
			<?php } else if($dbid == '12' && $_SESSION['databaseToBeUse'] == 'rand_texas'){ ?>
			<strong>*</strong>=Data withheld to limit disclosure, <strong>X</strong>=Not applicable.
			<?php } else if($dbid == '75' && $_SESSION['databaseToBeUse'] == 'rand_california'){ ?>
			<strong>N</strong>=Number, <strong>R</strong>=rate.
			<?php } else if($dbid == '117' && $_SESSION['databaseToBeUse'] == 'rand_california') { ?>
			<strong>*</strong>=Data masked for cells with less than 16 observations due to HIPAA and/or 42 CFR restrictions.<br/><strong>**</strong>=Column totals not shown when only one row has a cell size that is not displayed due to HIPAA rules.
			<?php } ?>
			</p>

			<p>&nbsp;</p>
			<?php if($db_datasource!=''){ ?>
				<?php if(isset($db_datasource) && $db_datasource!=''){ ?>
				<p><strong>Data Source: </strong><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?><a target="_blank" href="<?php echo $db_datasourcelink; ?>"><?php }?><?php echo stripslashes($db_datasource); ?><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?></a><?php } ?>
				</p>
			<?php } ?>

			<?php } ?>

		<?php } else { ?>
			
			<div class="clear"></div>
			<div class="pT20 txtcenter"><b>No Records Found For This Search Criteria.</b></div>

		<?php } ?>

	 <br/>

	<p>
	<?php echo date('D M d H:i:s Y'); ?>
	</p>		

	</div>
	<!-- /graph -->

</div>
		
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>