<?php
/******************************************
* @Modified on Feb 28,26 June, 2013
* @Package: Rand
* @Developer: Pragati Garg,Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://statestats.rand.org/stats/popdemo/popraceageUSdet.html
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";

global $db;

$tablesname					= "uspopraceage1";
$tablesnamecat				= "uspopraceage1_cats";
$tablesnamearea				= "us_states";
$tablesnamecountyarea		= "fips";

if(!isset($_SESSION['popraceageUSdet']) || (isset($_SESSION['popraceageUSdet']) && !isset($_SESSION['popraceageUSdet']['dbid']))){
	
	if(isset($_REQUEST['dbc']) && $_REQUEST['dbc']!='') {
		header('location: popraceageUSdet.php?dbc='.$_REQUEST['dbc'].'');			
		exit;
	} else {
		header('location: popraceageUSdet.php');
		exit;
	}
}

$searchedfields = $_SESSION['popraceageUSdet'];

//echo "<pre>";print_r($searchedfields);echo "</pre>"; die;

$admin = new admin();

$joinStr = $tablesStr = "";
$fieldsStrWhere = '';
$columnstobefetched = '';

$catname = "";
$yearArray = array();
$dbid = trim($searchedfields['dbid']);
$databaseDetail = $admin->getDatabase($dbid);

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
	$db_url						= stripslashes($databaseDetail['url']);
	$form_type					= 'two_stage';

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

		$tableNameSettings	= $columnSettings['table_name'];
		$columnNameSettings = $columnSettings['column_name'];
		$columnstobefetched .= $tableNameSettings.'.'.$columnNameSettings." as `".$columnSettings['display_name']."`,";
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

if(!isset($allDbTables) || (isset($allDbTables) && count($allDbTables)<=0)){
	echo "There are no tables associated with this form yet. Please contact administrator of site.";
	exit;
}

$columnstobefetched = substr($columnstobefetched, 0, -1);

$areasAll = $areaStr = $cat1All = $cat1Str = $categoryAll = $categoryStr = $stateStr = $stateStrAll = '';

$statesArray = explode(';',$searchedfields['us_states']);
if(!empty($statesArray)){
	foreach($statesArray as $states){
		$stateStrAll.="'".$states."'".',';
	}
	$stateStr = substr($stateStrAll, 0, -1);
}

if(!empty($searchedfields['Area'])){
	foreach($searchedfields['Area'] as $states){
		//foreach($states as $stk => $areas)
		$areasAll.="'".$states."'".',';
	}
	$areaStr = substr($areasAll, 0, -1);
	$fieldsStrWhere .= " and ".$tablesname.".area in (".$areaStr.") ";
}

if(!empty($searchedfields['cat1'])){
	$cat1All = $cat1Str='';
	foreach($searchedfields['cat1'] as $cat1s){
		$cat1All.="'".$cat1s."'".',';
	}
	$cat1Str = substr($cat1All, 0, -1);
	$fieldsStrWhere .= " and ".$tablesname.".cat1 IN (".$cat1Str.") ";
}

if(!empty($searchedfields['Category'])){	
	foreach($searchedfields['Category'] as $categories){
		$categoryAll.="'".$categories."'".',';
	}
	$categoryStr = substr($categoryAll, 0, -1);
	$fieldsStrWhere .= " and ".$tablesname.".category IN (".$categoryStr.")  ";
}

$sql = "select ".$columnstobefetched." from ".$tablesStr." where 1 ".$fieldsStrWhere."  ".$joinStr." limit 4000";

$_SESSION['query'] = $sql ;

$searchedDataResult = $dbDatabase->run_query($sql);
//$searchedData = $dbDatabase->getall($searchedDataResult);
?>
<!-- container -->
<section id="container">
 <!-- main div -->
 <div class="main-cell">	

		<?php
		if((mysql_num_rows($searchedDataResult))>0){
			//$firstRow = $searchedData[0];
			while($data = mysql_fetch_assoc($searchedDataResult)){
				$firstRow = $data;
				break;
			}
			?>

			<h1 class="left"><?php echo ucfirst($dbname); ?></h1>

			<?php if($dbid=='122'){
				$nograph='1';
			}?>

			<?php include($DOC_ROOT."/subPageLinks.php"); ?>

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
				$searchedDataResult = $dbDatabase->run_query($sql);
				while($rowData = mysql_fetch_assoc($searchedDataResult)){ 	
					$strGraph = '';
				?>
				<tr>
					<?php foreach($rowData as $keyField => $fieldValue){ 
						if(!is_integer($keyField)){
							$strGraph.= $fieldValue.'-';
						}else{
							$arrayColumns[$i][] = (double)(str_replace(',', '', $fieldValue));
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
											$arrayToBeInserted[] = (double)(str_replace(',', '', $catData[$year][$quat]));
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
						}else if((array_key_exists('County/Area', $rowData) || array_key_exists('County/Area', $rowData)) && (array_key_exists('total', $rowData) || array_key_exists('Total', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
							
						
							$origin = (array_key_exists('County/Area', $rowData))?$rowData['County/Area']:$rowData['County/Area'];
							$category = '';
							$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

							$columnsArrayData[$origin][$category][$year] = $rowData;
						}else if((array_key_exists('Industry', $rowData) || array_key_exists('industry', $rowData)) && (array_key_exists('Year', $rowData) || array_key_exists('year', $rowData))){
							
						
							$origin = (array_key_exists('Industry', $rowData))?$rowData['Industry']:$rowData['industry'];
							$category = '';
							$year = (array_key_exists('Year', $rowData))?$rowData['Year']:$rowData['year'];

							$columnsArrayData[$origin][$category][$year] = $rowData;
						}


					}

					
					
					$arrayColumns = array();
					$areaArray = array();
					foreach($columnsArrayData as $area => $areaData){
						foreach($areaData as $category => $catData){
							
							$keyForData  = $area;

							if($category !=''){
								$keyForData = $area.'-'.$category;
							}
							
							$arrayToBeInserted = array();
							$areaArray[] = $keyForData;
							foreach($yearMonth as $year => $yearQ){
								if(array_key_exists($year, $catData)){

									foreach($yearQ as $quat => $quaterVal){
										if(array_key_exists($quat, $catData[$year])){
											$arrayToBeInserted[] = (double)(str_replace(',', '', $catData[$year][$quat]));
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
			<!-- graph -->

			<!-- Show Notes -->
			<?php include($DOC_ROOT."/showNotes.php"); ?>
			<!-- Show Notes -->

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