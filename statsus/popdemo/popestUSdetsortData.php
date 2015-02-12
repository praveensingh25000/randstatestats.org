<?php
/******************************************
* @Modified on Feb 28, 2013
* @Package: RAND
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
* @live URL: http://statestats.rand.org/stats/popdemo/usmortality_age.html
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";

global $db;
$admin = new admin();

$joinStr = $tablesStr = "";
$fieldsStrWhere = '';
$columnstobefetched = '';

$nograph =1;

$tablesname					= "uspopest";
$tablesnamearea				= "uspopest_areas";

if(!isset($_SESSION['popestUSdetsort']) || (isset($_SESSION['popestUSdetsort']) && !isset($_SESSION['popestUSdetsort']['dbid']))){
	
	if(isset($_REQUEST['dbc']) && $_REQUEST['dbc']!='') {
		header('location: popestUSdetsort.php?dbc='.$_REQUEST['dbc'].'');			
		exit;
	} else {
		header('location:popestUSdetsort.php');
		exit;
	}
}

$searchedfields = $_SESSION['popestUSdetsort'];

//echo "<pre>";print_r($searchedfields);echo "</pre>";

$catname = "";
$yearArray = array();
$dbid = trim($searchedfields['dbid']);
$databaseDetail = $admin->getDatabase($dbid);


$searchedDatamainArray = $searchedDatamain = $searchedData = $quatersArray = $monthssArray = $columnsToBeShownAsGraph = $searchedDataArray = $searchedDataAll = array();

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
	$db_url						= stripslashes($databaseDetail['url']);
	$form_type					= 'two_stage';
	
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

		$tableNameSettings	= $columnSettings['table_name'];
		$columnNameSettings = $columnSettings['column_name'];
		$columnstobefetched .= $tableNameSettings.'.'.$columnNameSettings." as `".$columnSettings['display_name']."`,";
	}
}

if(!isset($allDbTables) || (isset($allDbTables) && count($allDbTables)<=0)){
	echo "There are no tables associated with this form yet. Please contact administrator of site.";
	exit;
}

if(!empty($searchedfields['syear'])){	
	$columnstobefetched .= "".$tablesname.".Y".$searchedfields['syear']." as `".$searchedfields['syear']."` ";
}

$columnstobefetched = substr($columnstobefetched, 0, -1);

if(!empty($searchedfields['largest_by_name']) || !empty($searchedfields['largest_by_number'])){

	if(!empty($searchedfields['largest_by_name'])) {
		$limitmumber= trim($searchedfields['largest_by_name']); 
	} else if(!empty($searchedfields['largest_by_number'])) { 
		$limitmumber= trim($searchedfields['largest_by_number']); 
	} else { 
		$limitmumber= 10;
	}
	
	$fieldsStrWhere .= " and ".$tablesnamearea.".areaname LIKE '%city%' ORDER BY ".$tablesname.".Y".$searchedfields['syear']." DESC LIMIT ".$limitmumber." ";
}

$sql = "select ".$columnstobefetched." from ".$tablesStr." where 1 ".$joinStr." ".$fieldsStrWhere."";

$_SESSION['query'] = $sql;

$searchedDataResult = $dbDatabase->run_query($sql);
$searchedData = $dbDatabase->getall($searchedDataResult);

//echo "<pre>";print_r($searchedData);echo "</pre>";
?>

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
								$tempMonths[]= $year."-".$monthsArr[$month].'-01'; 
								$yearMonth[$year][$month] = '';
							}
						}
						$yearArray = $tempMonths;											
						$columnsArrayData = array();
						foreach($searchedData as $keyData => $rowData){ 
							
							if((array_key_exists('Area', $rowData) || array_key_exists('Park', $rowData)) && array_key_exists('Category', $rowData) && array_key_exists('Year', $rowData)){
						
								if(array_key_exists('Area', $rowData)){
									$columnsArrayData[$rowData['Area']][$rowData['Category']][$rowData['Year']] = $rowData;
								} else if(array_key_exists('Park', $rowData)){
									$columnsArrayData[$rowData['Park']][$rowData['Category']][$rowData['Year']] = $rowData;
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

<?php include_once $basedir."/include/footerHtml.php"; ?>