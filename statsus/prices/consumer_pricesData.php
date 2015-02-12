<?php
/******************************************
* @Modified on July 17, 2013
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";

global $db;

$tablesname				= "consumer_prices";
$tablesnameareas		= "consumer_prices_areas";
$tablesnameitems		= "consumer_prices_items";

$admin = new admin();
$user  = new user();

if(!isset($_SESSION['consumer_prices']) || (isset($_SESSION['consumer_prices']) && !isset($_SESSION['consumer_prices']['dbid']))){
	
	if(isset($_REQUEST['dbc']) && $_REQUEST['dbc']!='') {
		header('location: consumer_prices.php?dbc='.$_REQUEST['dbc'].'');			
		exit;
	} else {
		header('location: consumer_prices.php');
		exit;
	}
}

$searchedfields = $_SESSION['consumer_prices'];

//echo "<pre>";print_r($searchedfields);echo "</pre>";die;

$joinStr = $tablesStr = "";
$fieldsStrWhere = '';
$columnstobefetched = '';

$catname = "";
$yearArray = array();
$dbid = trim($searchedfields['dbid']);
$databaseDetail = $admin->getDatabase($dbid);

if(isset($searchedfields['cpiu']) && $searchedfields['cpiu']=='percent') {
	//Continue
} else {
	//$searchedfields['syear'] = $searchedfields['syear'] - 1;
}

$quatersArray = $monthssArray = $columnsToBeShownAsGraph = array();

if(!empty($databaseDetail)){

	$dbname				= stripslashes($databaseDetail['db_title']);
	$dbsource			= stripslashes($databaseDetail['db_datasource']);
	$description		= stripslashes($databaseDetail['db_description']);
	$miscellaneous		= stripslashes($databaseDetail['db_misc']);
	$nextupdate			= stripslashes($databaseDetail['db_nextupdate']);
	$table				= stripslashes($databaseDetail['table_name']);
	$db_geographic		= stripslashes($databaseDetail['db_geographic']);
	$db_periodicity		= stripslashes($databaseDetail['db_periodicity']);
	$db_dataseries		= stripslashes($databaseDetail['db_dataseries']);
	$db_datasource		= stripslashes($databaseDetail['db_source']);
	$dateupdated		= stripslashes($databaseDetail['date_added']);
	$db_datasourcelink	= stripslashes($databaseDetail['db_sourcelink']);
	$formfootnotes		= stripslashes($databaseDetail['formfootnotes']);
	$db_url				= stripslashes($databaseDetail['url']);
	$form_type			= 'two_stage';
	
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
		
		if($columnSettings['column_name']!='Annual' && $columnSettings['column_name']!='annual' && $columnSettings['column_name']!='avg' && $columnSettings['column_name']!='Avg' && $columnSettings['column_name']!='Avg.' && $columnSettings['column_name']!='avg.' && $columnSettings['column_name']!='Average' && $columnSettings['column_name']!='average' && trim($columnSettings['column_name']) != 'tot' && trim($columnSettings['column_name']) != 'Tot' && trim($columnSettings['column_name']) != 'Total' && trim($columnSettings['column_name']) != 'total'){
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
			
			foreach($columns['yearsasrows']['columns'] as $tablename => $columnname){

				$tablesResultColumn = $admin->showColumns($tablename);
				$totalColumns = $dbDatabase->count_rows($tablesResultColumn);
				$tableColumns = $dbDatabase->getAll($tablesResultColumn);	
				
				$tableColumnsArray = array();
				foreach($tableColumns as $keyColumnCounter => $columnDetail){
					$tableColumnsArray[] = $columnDetail['Field'];
				}
			

				if(in_array($columnname, $tableColumnsArray)){
					
					$inStrYear = '';

					for($i=$searchedfields['syear'];$i<=$searchedfields['eyear'];$i++){
						$inStrYear .= $i.",";
						$yearArray[] = $i;
					}

					$inStrYear = substr($inStrYear, 0, -1);
					
					$fieldsStrWhere .= " and ".$tablename.".".$columnname." in (".$inStrYear.") ";

					
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

		if(isset($columns['monthsascolumns'])){
			$table = $columns['monthsascolumns'];
			$tablesResultColumn = $admin->showColumns($table);
			$totalColumns = $dbDatabase->count_rows($tablesResultColumn);
			$tableColumns = $dbDatabase->getAll($tablesResultColumn);	
			
			$tableColumnsArray = array();
			foreach($tableColumns as $keyColumnCounter => $columnDetail){
				$tableColumnsArray[] = $columnDetail['Field'];
			}
			
			
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

		if(isset($columns['quatersascolumns'])){
			$table = $columns['quatersascolumns'];
			$tablesResultColumn = $admin->showColumns($table);
			$totalColumns = $dbDatabase->count_rows($tablesResultColumn);
			$tableColumns = $dbDatabase->getAll($tablesResultColumn);	
			
			$tableColumnsArray = array();
			foreach($tableColumns as $keyColumnCounter => $columnDetail){
				$tableColumnsArray[] = $columnDetail['Field'];
			}
			
			for($i=$searchedfields['squater'];$i<=$searchedfields['equater'];$i++) {
				
				$col = $i;
				
				if(in_array($embedQ.''.$col, $tableColumnsArray) || in_array(strtoupper($embedQ).''.$col, $tableColumnsArray)) {
					
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

foreach($columnDisplaySettings as $keyColumnDisplay => $columnSettings){

	if($columnSettings['column_name']=='Annual' || $columnSettings['column_name']=='annual' || $columnSettings['column_name']=='avg' || $columnSettings['column_name']=='Avg' || $columnSettings['column_name']=='Avg.' || $columnSettings['column_name']=='avg.' || $columnSettings['column_name'] == 'Average' || $columnSettings['column_name']=='average' || trim($columnSettings['column_name']) == 'tot' || trim($columnSettings['column_name']) == 'Tot' || trim($columnSettings['column_name']) == 'Total' || trim($columnSettings['column_name']) == 'total') {

		$tableNameSettings	= $columnSettings['table_name'];
		$columnNameSettings = $columnSettings['column_name'];
		$columnstobefetched .= $tableNameSettings.'.'.$columnNameSettings." as `".$columnSettings['display_name']."`,";
	}
}

if(!isset($allDbTables) || (isset($allDbTables) && count($allDbTables)<=0)){
	echo "There are no tables associated with this form yet. Please contact administrator of site.";
	exit;
}

$areasStr = $areasAll = $cat1Str = $cat1All = $itemsStr = $itemsAll ='';

$columnstobefetched = substr($columnstobefetched, 0, -1);

if(!empty($searchedfields['cat1'])){	
	$cat1array = $searchedfields['cat1'];
	foreach($cat1array as $cat1s){
		$cat1All.="'".$cat1s."'".',';
	}
	$cat1Str = substr($cat1All, 0, -1);
	$fieldsStrWhere .= " and ".$tablesname.".cat1 IN (".$cat1Str.") ";
}

if(!empty($searchedfields['areas'])){	
	$areasarray = explode(';',$searchedfields['areas']);
	foreach($areasarray as $areas){
		$areasAll.="'".$areas."'".',';
	}
	$areasStr = substr($areasAll, 0, -1);
	$fieldsStrWhere .= " and ".$tablesname.".areas IN (".$areasStr.") ";
}

if(!empty($searchedfields['items'])){	
	$itemsarray = explode(';',$searchedfields['items']);
	foreach($itemsarray as $items){
		$itemsAll.="'".$items."'".',';
	}
	$itemsStr = substr($itemsAll, 0, -1);
	$fieldsStrWhere .= " and ".$tablesname.".items IN (".$itemsStr.") ";
}

if(isset($searchedfields['cpiu']) && $searchedfields['cpiu']=='percent') {
	$sql = "select ".$columnstobefetched." from ".$tablesStr." where 1 ".$fieldsStrWhere."  ".$joinStr." order by consumer_prices.year limit 4000";

	$_SESSION['query'] = $sql ;

} else {

	$columnstobefetched .= ",consumer_prices_areas.area_code as area_code, consumer_prices.items as item_code";

	$sql = "select ".$columnstobefetched." from ".$tablesStr." where 1 ".$fieldsStrWhere."  ".$joinStr." order by consumer_prices.year limit 4000";
}

$searchedDataResult = $dbDatabase->run_query($sql);
$searchedData		= $dbDatabase->getall($searchedDataResult);

if(isset($searchedfields['cpiu']) && $searchedfields['cpiu']=='percent') { ?>

	<!-- container -->
	<section id="container">

		 <!-- main div -->
		 <div class="main-cell">
			<?php
			if(count($searchedData)>0) {
				$firstRow = $searchedData[0];
				?>			
				
				<h1 class="left"><?php echo ucfirst($dbname); ?></h1>
				
				<!-- PAGE LINKS -->
				<?php include($DOC_ROOT."/subPageLinks.php"); ?>
				<!-- /PAGE LINKS -->

				<!-- GRID VIEW -->			
				<div class="search-table-data" style="<?php if((isset($_GET['graph']) && $_GET['graph'] != 'gridview')) { ?> display:none; <?php } ?>">
					
					<table id="" class="data-table">
						<thead>
							<tr>
								<?php foreach($firstRow as $keyField => $fieldValue){ ?>
								<th><?php echo ucfirst($keyField); ?></th>
								<?php } ?>
							</tr>
						</thead>	
							<?php 
							$areaArray = $colorsarray = array();
							$colors = array("red", "green", "violet", "maroon", "pink", "yellow", "black", "grey", "BlueViolet", "Chartreuse", "Crimson", "DarkMagenta", "blue", "Darkorange");
							$i=0;
							$arrayColumns = array();
							
							foreach($searchedData as $keyData => $rowData){ 	
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
									<?php include($DOC_ROOT.'/decimal.php'); ?>								
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
									$tempYears[] = $quater.'/'.substr($year, -2); 
									$yearQuater[$year][$quater] = '';
								}
							}
							$yearArray = $tempYears;
							
							$columnsArrayData = array();
							foreach($searchedData as $keyData => $rowData){ 
								
								if(array_key_exists('Area', $rowData) && array_key_exists('Category', $rowData) && array_key_exists('Year', $rowData)){
									$columnsArrayData[$rowData['Area']][$rowData['Category']][$rowData['Year']] = $rowData;
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
									$tempMonths[]= $year."-".$monthdigit.'-01'; 
									$yearMonth[$year][$month] = '';
								}
							}

							$yearArray = $tempMonths;											
							$columnsArrayData = array();
							foreach($searchedData as $keyData => $rowData){ 
								
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

								} else if((array_key_exists('State', $rowData) || array_key_exists('state', $rowData)) && (array_key_exists('Category', $rowData) || array_key_exists('category', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
									
									$origin = (array_key_exists('State', $rowData))?$rowData['State']:$rowData['state'];
									$category = (array_key_exists('Category', $rowData))?$rowData['Category']:$rowData['category'];
									$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

									$columnsArrayData[$origin][$category][$year] = $rowData;
								} else if((array_key_exists('Item', $rowData) || array_key_exists('item', $rowData)) && (array_key_exists('area', $rowData) || array_key_exists('Area', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
									
									$area = (array_key_exists('Area', $rowData))?$rowData['Area']:$rowData['area'];
									$category = (array_key_exists('Item', $rowData))?$rowData['Item']:$rowData['item'];
									$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

									$columnsArrayData[$area][$category][$year] = $rowData;
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
				<!-- GRID VIEW -->
				
				<!-- GRAPH VIEW -->
				<?php include($DOC_ROOT."/graphInclude.php"); ?>
				<!-- GRAPH VIEW -->

				<!-- Show Notes -->
				<?php include($DOC_ROOT."/showNotes.php"); ?>
				<!-- Show Notes -->

				<br class="clear" />
				<p>&nbsp;</p>		
				<?php if(isset($db_datasource) && $db_datasource!=''){ ?>
				<p><strong>Data Source: </strong><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?><a target="_blank" href="<?php echo $db_datasourcelink; ?>"><?php }?><?php echo stripslashes($db_datasource); ?><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?></a><?php } ?>
				</p>
				<?php } ?>
			
			<?php } else { ?>			
				<div class="clear"></div>
				<div class="pT20 txtcenter"><b>No Records Found For Percent Search Criteria.</b></div>
			<?php } ?>
			
			<br class="clear" />
			<p>
				<?php echo date('D M d H:i:s Y'); ?>
			</p>
		</div>		
	</section>

<?php } else { 

	//$nograph = 1;

	$searchedDatamain = $searchedDataArray = $search_array = $search_array1 = $search_array2 = array();

	if(!empty($searchedData)) {
		
		foreach($searchedData as $keyData => $values2) {
			
			$year = $values2['Year']-1;
			$areacode = $values2['area_code'];
			$itemcode = $values2['item_code'];
			$status = $values2['Status'];
			
			 $Jan =  $Feb = $Mar = $Apr = $May = $June = $July = $Aug = $Sep = $Oct = $Nov = $Dec = $Avg = "NA";

			$sqlPreviousYear = "SELECT consumer_prices_areas.area_name AS `Area` , consumer_prices_items.item_name AS `Item` , consumer_prices.cat1 AS `Status` , consumer_prices.year AS `Year` , consumer_prices.m01 AS 'Jan', consumer_prices.m02 AS 'Feb', consumer_prices.m03 AS 'Mar', consumer_prices.m04 AS 'Apr', consumer_prices.m05 AS 'May', consumer_prices.m06 AS 'June', consumer_prices.m07 AS 'July', consumer_prices.m08 AS 'Aug', consumer_prices.m09 AS 'Sep', consumer_prices.m10 AS 'Oct', consumer_prices.m11 AS 'Nov', consumer_prices.m12 AS 'Dec', consumer_prices.Avg AS `Avg` FROM consumer_prices, consumer_prices_areas, consumer_prices_items WHERE 1 AND consumer_prices.year = '".$year."' AND consumer_prices.cat1 IN ('".$status."') AND consumer_prices.areas = '".$areacode."' AND consumer_prices.items IN ('".$itemcode."') AND consumer_prices.areas = consumer_prices_areas.area_code AND consumer_prices.items = consumer_prices_items.item_code";
			$values1 = $dbDatabase->getRow($sqlPreviousYear, $dbDatabase->conn);

			if(isset($values2['Jan']) && isset($values1['Jan']) && is_numeric($values2['Jan']) && is_numeric($values1['Jan'])){ $Jan = number_format(((($values2['Jan']/$values1['Jan']) - 1)*100),2); }

			if(isset($values2['Feb']) && isset($values1['Feb']) && is_numeric($values2['Feb']) && is_numeric($values1['Feb'])){ $Feb = number_format(((($values2['Feb']/$values1['Feb']) - 1)*100),2); }

			if(isset($values2['Mar']) && isset($values1['Mar']) && is_numeric($values2['Mar']) && is_numeric($values1['Mar'])){ $Mar = number_format(((($values2['Mar']/$values1['Mar']) - 1)*100),2); }

			if(isset($values2['Apr']) && isset($values1['Apr']) && is_numeric($values2['Apr']) && is_numeric($values1['Apr'])){ $Apr = number_format(((($values2['Apr']/$values1['Apr']) - 1)*100),2); }

			if(isset($values2['May']) && isset($values1['May']) && is_numeric($values2['May']) && is_numeric($values1['May'])){ $May = number_format(((($values2['May']/$values1['May']) - 1)*100),2); }

			if(isset($values2['June']) && isset($values1['June']) && is_numeric($values2['June']) && is_numeric($values1['June'])){ $June = number_format(((($values2['June']/$values1['June']) - 1)*100),2); }

			if(isset($values2['July']) && isset($values1['July']) && is_numeric($values2['July']) && is_numeric($values1['July'])){ $July = number_format(((($values2['July']/$values1['July']) - 1)*100),2); }

			if(isset($values2['Aug']) && isset($values1['Aug']) && is_numeric($values2['Aug']) && is_numeric($values1['Aug'])){ $Aug = number_format(((($values2['Aug']/$values1['Aug']) - 1)*100),2); }

			if(isset($values2['Sep']) && isset($values1['Sep']) && is_numeric($values2['Sep']) && is_numeric($values1['Sep'])){ $Sep = number_format(((($values2['Sep']/$values1['Sep']) - 1)*100),2); }

			if(isset($values2['Oct']) && isset($values1['Oct']) && is_numeric($values2['Oct']) && is_numeric($values1['Oct'])){ $Oct = number_format(((($values2['Oct']/$values1['Oct']) - 1)*100),2); }
			if(isset($values2['Nov']) && isset($values1['Nov']) && is_numeric($values2['Nov']) && is_numeric($values1['Nov'])){ $Nov = number_format(((($values2['Nov']/$values1['Nov']) - 1)*100),2); }

			if(isset($values2['Dec']) && isset($values1['Dec']) && is_numeric($values2['Dec']) && is_numeric($values1['Dec'])){ $Dec = number_format(((($values2['Dec']/$values1['Dec']) - 1)*100),2); }

			if(isset($values2['Avg']) && isset($values1['Avg']) && is_numeric($values2['Avg']) && is_numeric($values1['Avg'])){ $Avg = number_format(((($values2['Avg']/$values1['Avg']) - 1)*100),2); }


			$search_array11[$values2['Year']]['Area'] = $values1['Area'];
			$search_array11[$values2['Year']]['Item'] = $values1['Item'];
			$search_array11[$values2['Year']]['Status'] = $values1['Status'];
			$search_array11[$values2['Year']]['Year'] = $values2['Year'];

			if(isset($values2['Jan']) && isset($values1['Jan'])){ 
				if($Jan!='NA'){
					$jan = round($Jan,1)."%";
				} else {
					$jan = 'NA';
				}
				$search_array11[$values2['Year']]['Jan'] = $jan;
			}

			if(isset($values2['Feb']) && isset($values1['Feb'])){ 
				if($Feb!='NA'){
					$feb = round($Feb,1)."%";
				} else {
					$feb = 'NA';
				}
				$search_array11[$values2['Year']]['Feb'] = $feb;
			}

			if(isset($values2['Mar']) && isset($values1['Mar'])){ 
				if($Mar!='NA'){
					$mar = round($Mar,1)."%";
				} else {
					$mar = 'NA';
				}
				$search_array11[$values2['Year']]['Mar'] = $mar;
			}

			if(isset($values2['Apr']) && isset($values1['Apr'])){ 
				if($Apr!='NA'){
					$apr = round($Apr,1)."%";
				} else {
					$apr = 'NA';
				}
				$search_array11[$values2['Year']]['Apr'] = $apr;
			}

			if(isset($values2['May']) && isset($values1['May'])){ 
				if($May!='NA'){
					$may = round($May,1)."%";
				} else {
					$may = 'NA';
				}
				$search_array11[$values2['Year']]['May'] = $may;
			}

			if(isset($values2['June']) && isset($values1['June'])){ 
				if($June!='NA'){
					$june = round($June,1)."%";
				} else {
					$june = 'NA';
				}
				$search_array11[$values2['Year']]['June'] = $june;
			}

			if(isset($values2['July']) && isset($values1['July'])){ 
				if($July!='NA'){
					$july = round($July,1)."%";
				} else {
					$july = 'NA';
				}
				$search_array11[$values2['Year']]['July'] = $july;
			}

			if(isset($values2['Aug']) && isset($values1['Aug'])){ 
				if($Aug!='NA'){
					$aug = round($Aug,1)."%";
				} else {
					$aug = 'NA';
				}
				$search_array11[$values2['Year']]['Aug'] = $aug;
			}

			if(isset($values2['Sep']) && isset($values1['Sep'])){ 
				if($Sep!='NA'){
					$sep = round($Sep,1)."%";
				} else {
					$sep = 'NA';
				}
				$search_array11[$values2['Year']]['Sep'] = $Sep;
			}

			if(isset($values2['Oct']) && isset($values1['Oct'])){
				if($Oct!='NA'){
					$oct = round($Oct,1)."%";
				} else {
					$oct = 'NA';
				}
				$search_array11[$values2['Year']]['Oct'] = $oct;
			}

			if(isset($values2['Nov']) && isset($values1['Nov'])){ 
				if($Nov!='NA'){
					$nov = round($Nov,1)."%";
				} else {
					$nov = 'NA';
				}
				$search_array11[$values2['Year']]['Nov'] = $nov;
			}

			if(isset($values2['Dec']) && isset($values1['Dec'])){ 
				if($Dec!='NA'){
					$dec = round($Dec,1)."%";
				} else {
					$dec = 'NA';
				}
				$search_array11[$values2['Year']]['Dec'] = $dec;
			}

			if(isset($values2['Avg']) && isset($values1['Avg'])){ 
				if($Jan!='NA'){
					$avg = round($Avg,1)."%";
				} else {
					$avg = 'NA';
				}
				$search_array11[$values2['Year']]['Avg'] = $avg;
			}

	   }

	   foreach($search_array11 as $key111 => $values111){
		   if(isset($values111['Jan'])) {
		   $searchedDatamain[] =$values111;
		   }
	   }

	   unset($_SESSION['query']);

	   $_SESSION['query'] = $searchedDatamain;

	   //echo "<pre>";print_r($searchedDatamain);echo "</pre>";

	   if(!empty($searchedDatamain)) { ?>
		
		 <!-- container -->
		 <section id="container">

			 <!-- main div -->
			 <div class="main-cell">
				<?php
				if(count($searchedDatamain)>0) {
					$firstRow = $searchedDatamain[0];
					?>			
					
					<h1 class="left"><?php echo ucfirst($dbname); ?></h1>
					
					<!-- PAGE LINKS -->
					<?php include($DOC_ROOT."/subPageLinks.php"); ?>
					<!-- /PAGE LINKS -->

					<!-- GRID VIEW -->			
					<div class="search-table-data" style="<?php if((isset($_GET['graph']) && $_GET['graph'] != 'gridview')) { ?> display:none; <?php } ?>">
						
						<table id="" class="data-table">
							<thead>
								<tr>
									<?php foreach($firstRow as $keyField => $fieldValue){ ?>
									<th><?php echo ucfirst($keyField); ?></th>
									<?php } ?>
								</tr>
							</thead>	
								<?php 
								$areaArray = $colorsarray = array();
								$colors = array("red", "green", "violet", "maroon", "pink", "yellow", "black", "grey", "BlueViolet", "Chartreuse", "Crimson", "DarkMagenta", "blue", "Darkorange");
								$i=0;
								$arrayColumns = array();
								
								foreach($searchedDatamain as $keyData => $rowData){ 	
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
										<?php include($DOC_ROOT.'/decimal.php'); ?>								
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
										$tempYears[] = $quater.'/'.substr($year, -2); 
										$yearQuater[$year][$quater] = '';
									}
								}
								$yearArray = $tempYears;
								
								$columnsArrayData = array();
								foreach($searchedDatamain as $keyData => $rowData){ 
									
									if(array_key_exists('Area', $rowData) && array_key_exists('Category', $rowData) && array_key_exists('Year', $rowData)){
										$columnsArrayData[$rowData['Area']][$rowData['Category']][$rowData['Year']] = $rowData;
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
										$tempMonths[]= $year."-".$monthdigit.'-01'; 
										$yearMonth[$year][$month] = '';
									}
								}
								
								$yearArray = $tempMonths;											
								$columnsArrayData = array();
								foreach($searchedDatamain as $keyData => $rowData){ 
									
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

									} else if((array_key_exists('State', $rowData) || array_key_exists('state', $rowData)) && (array_key_exists('Category', $rowData) || array_key_exists('category', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
										
										$origin = (array_key_exists('State', $rowData))?$rowData['State']:$rowData['state'];
										$category = (array_key_exists('Category', $rowData))?$rowData['Category']:$rowData['category'];
										$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

										$columnsArrayData[$origin][$category][$year] = $rowData;
									} else if((array_key_exists('Item', $rowData) || array_key_exists('item', $rowData)) && (array_key_exists('area', $rowData) || array_key_exists('Area', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
										
										$area = (array_key_exists('Area', $rowData))?$rowData['Area']:$rowData['area'];
										$category = (array_key_exists('Item', $rowData))?$rowData['Item']:$rowData['item'];
										$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

										$columnsArrayData[$area][$category][$year] = $rowData;
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
					<!-- GRID VIEW -->
					
					<!-- GRAPH VIEW -->
					<?php include($DOC_ROOT."/graphInclude.php"); ?>
					<!-- GRAPH VIEW -->

					<!-- Show Notes -->
					<?php include($DOC_ROOT."/showNotes.php"); ?>
					<!-- Show Notes -->

					<br class="clear" />
					<p>&nbsp;</p>		
					<?php if(isset($db_datasource) && $db_datasource!=''){ ?>
					<p><strong>Data Source: </strong><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?><a target="_blank" href="<?php echo $db_datasourcelink; ?>"><?php }?><?php echo stripslashes($db_datasource); ?><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?></a><?php } ?>
					</p>
					<?php } ?>
				
				<?php } else { ?>			
					<div class="clear"></div>
					<div class="pT20 txtcenter"><b>No Records Found For This Search Criteria.</b></div>
				<?php } ?>
				
				<br class="clear" />
				<p>
					<?php echo date('D M d H:i:s Y'); ?>
				</p>
			</div>		
		 </section>
	   
	   <?php } else { ?>		
	
		<div class="clear"></div>
		<div class="pT20 txtcenter"><b>No Records Found For CPI-U Index Search Criteria.</b></div>

	   <?php }

	} else { ?>		
	
		<div class="clear"></div>
		<div class="pT20 txtcenter"><b>No Records Found For CPI-U Index Search Criteria.</b></div>

	<?php } ?>

<?php } ?>

<?php include_once $basedir."/include/footerHtml.php"; ?>