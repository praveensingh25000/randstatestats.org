<?php
/******************************************
* @Modified on Feb 11, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";

global $db;

if(!isset($_SESSION['weather']) || (isset($_SESSION['weather']) && !isset($_SESSION['weather']['dbid']))){
	header('location: weather.php');
}

$searchedfields = $_SESSION['weather'];

$tablesname = " weathersummaryUS";

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
	$db_datasourcelink	= stripslashes($databaseDetail['db_sourcelink']);
	
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

	$columnDisplaySettings = $admin->getTableColumnsDisplaySettings($dbid, "order by id");

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

		if(isset($columns['yearsasrows']) && $searchedfields['syear']!='' && $searchedfields['eyear']!=''){
			
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
if($columnstobefetched!='')
{
	$columnstobefetched = substr($columnstobefetched, 0, -1);
}else
{
	$columnstobefetched='*';
}


$area = '';
foreach($searchedfields['area'] as $keyArea => $areacode){
	$area .= "'".$areacode."',";
}
$area = substr($area, 0, -1);

$category = '';
foreach($searchedfields['category'] as $keyCat => $catname){
	$category .= "'".$catname."',";
}
$category = substr($category, 0, -1);

$cat1 = '';
$us_statesArray = explode(';', $searchedfields['us_states']);
foreach($us_statesArray as $keyCat => $catname){
	$cat1 .= "'".$catname."',";
}
$cat1 = substr($cat1, 0, -1);

$sql = "select ".$columnstobefetched." from ".$tablesStr." where 1 ".$fieldsStrWhere." and ".$tablesname.".Area in (".$area.") and ".$tablesname.".Cat1 in (".$cat1.") and ".$tablesname.".Category in (".$category.")  ".$joinStr." limit 4000";

$_SESSION['query'] = $sql ;
//echo $sql;

$searchedDataResult = $dbDatabase->run_query($sql);
$searchedData = $dbDatabase->getall($searchedDataResult);
?>

 <!-- container -->
<section id="container">
 <!-- main div -->
 <div class="main-cell">	

		<?php
		if(count($searchedData)>0){
			$firstRow = $searchedData[0];
		?>

			
			<h1 class="left"><?php echo ucfirst($dbname); ?></h1>
			
			<!-- PAGE LINKS -->
			<?php include($DOC_ROOT."/subPageLinks.php"); ?>
			<!-- /PAGE LINKS -->

			<div class="search-table-data">
			<table id="" class="data-table">
				<tbody>
				<tr>
					<?php foreach($firstRow as $keyField => $fieldValue){ ?>
					<th><?php echo ucfirst($keyField); ?></th>
					<?php } ?>
				</tr>
				
				<?php 
			
				$i=0;
				$arrayColumns = array();
				foreach($searchedData as $keyData => $rowData){ 	
					$strGraph = '';
					
				?>
				<tr>
					<?php foreach($rowData as $keyField => $fieldValue){ ?>
					<td align="left">
							<?php include($basedir.'/decimal.php'); ?>			
					</td>
					<?php } ?>
				</tr>
				<?php 
				}
				?>

				</tbody>
			</table>
			</div>
			
	
			<p>&nbsp;</p>

			<?php if(isset($db_datasource) && $db_datasource!=''){ ?>
			<p><strong>Data Source: </strong><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?><a target="_blank" href="<?php echo $db_datasourcelink; ?>"><?php }?><?php echo stripslashes($db_datasource); ?><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?></a><?php } ?>
			</p>
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