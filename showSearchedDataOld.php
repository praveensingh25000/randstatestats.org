<?php
/******************************************
* @Modified on Jan 11, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/header.php";

//checkSession(false);

global $db;

//checking user is loggedin or not with alert message
//checksession_with_message($sessionType=false,$redirectpage='login.php',$messagetype='msgalert',$msgnumber='5');

$searchedfields = $_SESSION['searchedfields'];

if(!isset($searchedfields['dbid'])){
	header('location: index.php');
}

$admin = new admin();

$joinStr = $tablesStr = "";
$fieldsStrWhere = '';
$columnstobefetched = '';

$catname = "";

$dbid = trim($searchedfields['dbid']);
$databaseDetail = $admin->getDatabase($dbid);
if(!empty($databaseDetail)){
	$dbname		= stripslashes($databaseDetail['db_name']);
	$dbsource	= stripslashes($databaseDetail['db_source']);
	$description= stripslashes($databaseDetail['db_description']);
	$miscellaneous	= stripslashes($databaseDetail['db_misc']);
	
	$allDbTables = $admin->getDatabaseTables($dbid);

	foreach($allDbTables as $keyTable => $tableDetail){
		$tablesStr .= $tableDetail['table_name'].", ";
	}
	
	$tablesStr = substr($tablesStr, 0 , -2);

	$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);
	$search_criteria_details = $admin->selectAllSearchCriteria($dbid);
	$joinConditions = $admin->getAllJoinConditions($dbid);


	/*if(count($joinConditions)>0){
		foreach($joinConditions as $joinKey => $joinDetail){
			$joinStr .= " and ".$joinDetail['primary_table'].".".$joinDetail['primary_table_column']." = ".$joinDetail['foreign_table'].".".$joinDetail['foreign_table_column']." ";
		}
	}*/

	$columnDisplaySettings = $admin->getTableColumnsDisplaySettings($dbid);

	foreach($columnDisplaySettings as $keyColumnDisplay => $columnSettings){

		$tableNameSettings	= $columnSettings['table_name'];
		$columnNameSettings = $columnSettings['column_name'];
		$columnstobefetched .= $tableNameSettings.'.'.$columnNameSettings." as `".$columnSettings['display_name']."`,";
	}
	


	
	if(count($search_criteria_details)>0){
		foreach($search_criteria_details as $searchcriteriakey => $searchCriteriaDetail){
				
			$tableSearchField = $searchCriteriaDetail['belongs_to'];
			$columnSearchField = $searchCriteriaDetail['coloum_name'];
			
			$columnSettings = $admin->getColumnDisplaySettings($dbid, $tableSearchField, $columnSearchField);
			
			if(isset($columnSettings['display_name'])){
				$columnstobefetched .= $tableSearchField.'.'.$columnSearchField." as `".$columnSettings['display_name']."`,";
			} else {
				$columnstobefetched .= $tableSearchField.'.'.$columnSearchField.",";
			}

			if(isset($searchedfields['field']) && isset($searchedfields['field'][$searchCriteriaDetail['id']]) && !empty($searchedfields['field'][$searchCriteriaDetail['id']])){
				
				

				if(is_array($searchedfields['field'][$searchCriteriaDetail['id']])){
					
					$inStr = '';
					foreach($searchedfields['field'][$searchCriteriaDetail['id']] as $postedFieldKey => $postedFieldValue){
						if(is_integer($postedFieldValue)){
							$inStr .= mysql_real_escape_string($postedFieldValue).", ";
						} else {
							$inStr .= '"'.mysql_real_escape_string($postedFieldValue).'", ';
						}
					}

					$inStr = substr($inStr, 0, -2);
					
					if($inStr!=''){
						$fieldsStrWhere .= " and ".$tableSearchField.".".$columnSearchField." in (".$inStr.") ";
					}

				} else if($searchCriteriaDetail['type'] == 'multiple' && $searchCriteriaDetail['control_type'] == 'select'){

					echo "<pre>";
					print_r($searchCriteriaDetail);
					die;
					$values = explode(';',$searchedfields['field'][$searchCriteriaDetail['id']]);
					$inStr = '';

					foreach($values as $postedFieldKey => $postedFieldValue){
						if(is_integer($postedFieldValue)){
							$inStr .= $postedFieldValue.", ";
						} else {
							$inStr .= "'".mysql_real_escape_string($postedFieldValue)."', ";
						}
					}

					$inStr = substr($inStr, 0, -2);
					
					if($inStr!=''){
						$fieldsStrWhere .= " and ".$tableSearchField.".".$columnSearchField." in (".$inStr.") ";
					}


				} else {
					$fieldsStrWhere .= " and ".$tableSearchField.".".$columnSearchField." = '".mysql_real_escape_string(addslashes($searchedfields['field'][$searchCriteriaDetail['id']]))."' ";
				}

			}
			
		}
	}
	
	$columns = unserialize($timeIntervalSettings['columns']);

	//echo "<br/><br/><br/><pre>";
	//print_r($timeIntervalSettings);
	//print_r($columns);
	//print_r($searchedfields);
	//echo "</pre>";

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

				if(in_array($embedY.''.$i, $tableColumnsArray)){

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
				if(in_array($embedM.''.$col, $tableColumnsArray)){
					
					$months = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 =>"Apr", 5 => "May", 6 => "June", 7 => "July", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");

					if($embedM!=''){
						$columnstobefetched .= $table.'.'.$embedM.''.$col." as '".$months[$i]."',";
					} else {
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
				
				if(in_array($embedQ.''.$col, $tableColumnsArray)){
					
					$quaters = array(1 => "Q1", 2 => "Q2", 3 => "Q3", 4 =>"Q4");

					if($embedM!=''){
						$columnstobefetched .= $table.'.'.$embedQ.''.$col." as '".$months[$i]."',";
					} else {
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


$sql = "select ".$columnstobefetched." from ".$tablesStr." where 1 ".$fieldsStrWhere." ".$joinStr." limit 4000";

echo $sql;
die;

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

	

	<h1><?php echo ucfirst($dbname); ?></h1>

	<div style="height: 300px;overflow-y:scroll;margin: 15px;">
	<table cellspacing="0" cellpadding="5" border="1" class="  collapse" id="">
		<tbody>
		<tr>
			<?php foreach($firstRow as $keyField => $fieldValue){ ?>
			<th bgcolor="#eeeeee"><?php echo ucfirst($keyField); ?></th>
			<?php } ?>
		</tr>
		
		<?php foreach($searchedData as $keyData => $rowData){ ?>
		<tr>
			<?php foreach($rowData as $keyField => $fieldValue){ ?>
			<td align="left"><?php echo $fieldValue; ?></td>
			<?php } ?>
		</tr>
		<?php } ?>

		</tbody>
	</table>
	</div>
	<br/>
	<!-- <p class=" pL15"><i><font color="#000000"><?php echo count($searchedData); ?> of <?php echo count($searchedData)+100; ?></font> allowed matches returned.</i></p> -->
	<p>&nbsp;</p>
	<p>Source: RAND Texas (See <a href="statistics.php?id=<?php echo base64_encode($dbid); ?>">Statistics Summary</a> for originating data source.)</p>


<?php } else { ?>
	
	<div class="clear"></div>
	<div class="pT20 pL20"><b>No Records Found</b></div>

<?php } ?>


 <br/>

<p class=" pL15">
<?php echo date('D M d H:i:s Y'); ?>
</p>		



</div>
		
</section>

<?php include_once $basedir."/include/footer.php"; ?>