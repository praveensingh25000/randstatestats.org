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

if(!isset($_SESSION['searchedfieldsuspupilration']) || (isset($_SESSION['searchedfieldsuspupilration']) && !isset($_SESSION['searchedfieldsuspupilration']['dbid']))){
	header('location: usk12_pupilratio.php');
}

$searchedfields = $_SESSION['searchedfieldsuspupilration'];

$tablesname = "usk12enroll1";
$tablesnamearea = "usk12enroll_areas";
$tablesnameatcat = "usk12enroll_cats";

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

$area = implode(',', $searchedfields['areacode']);

$sql = "select ".$columnstobefetched." from ".$tablesStr." where 1 ".$fieldsStrWhere." and ".$tablesnamearea.".areacode in (".$area.")  ".$joinStr." limit 
4000";

$_SESSION['query'] = $sql ;


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

			<?php if(((isset($_GET['graph']) && $_GET['graph']=='gridview')) || !isset($_GET['graph'])){  ?>

			<div id="" class="right">
				<ul class="submenu">
					<!-- DOWNLOAD ------>
					<li id="download_link" class="">						
						<a href="downloadDynamic.php?type=csv">CSV</a>										
					</li>	
					<li id="download_link" class="">						
						<a href="downloadDynamic.php?type=excel">EXCEL</a>						
					</li>	
					<!-- DOWNLOAD ------->

					<!-- PRINT PREVIEW -->
					<li id="print_link" class="">	
						 <div id="aside">
							<a href="javascript:;" onclick="window.print();">Print</a>
						 </div>
					</li>
					<!-- <script type="text/javascript">
						$(function() {
							// Add link for print preview and intialise
							var checkdiv=$("#check_div").hasClass('print-preview');
							if(checkdiv==false)			
							$('#aside').prepend('<a href="javascript:;" id="check_div" class="print-preview">Print this page</a>');			
							$('a.print-preview').printPreview();		
						});	
					</script> -->
					<!-- PRINT PREVIEW -->		

					<!-- SHARING PAGE -->	
					<li class=""><span class='st_sharethis_hcount'></span></li>
					<!-- /SHARING PAGE -->	
				</ul>
			<div class="clear pT10"> </div>
				<?php } ?>
				<!-- /PAGE LINKS -->
				
				<?php if(isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SQ-SY-EQ-EY' || $timeIntervalSettings['time_format'] == 'SY-EY' || $timeIntervalSettings['time_format'] == 'SM-SY-EM-EY')){ 	?>
				<!-- LINE PIE BAR CHART ------------------------->
				<ul class="submenu">
					<li id="showGrid" <?php if((isset($_GET['graph']) && $_GET['graph']=='gridview') || !isset($_GET['graph'])) echo 'class="current"'; ?>><a href="?graph=gridview">Grid View</a></li>		

					<li id="showChart"  <?php if(isset($_GET['graph']) && $_GET['graph']=='linegraph') echo 'class="current"'; ?>><a href="?graph=linegraph" >Line Graph</a>
					</li>
								
				</ul>
				<?php }?>
			</div>
			<div class="clear pT10"></div>
			<div class="search-table-data" style="<?php if((isset($_GET['graph']) && $_GET['graph']!='gridview')){  ?> display:none; <?php } ?>">
			<table id="" class="data-table">
				<tbody>
				<tr>
					<?php foreach($firstRow as $keyField => $fieldValue){ ?>
					<th><?php echo ucfirst($keyField); ?></th>
					<?php } ?>
				</tr>
				
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
						<?php include('decimal.php'); ?>
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
							$tempMonths[] = $month.'/'.substr($year, -2); 
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
			<!-- graph -->
			<div class="main-cell">
			<?php if((isset($_GET['graph']) && $_GET['graph']=='linegraph') && isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SQ-SY-EQ-EY' || $timeIntervalSettings['time_format'] == 'SY-EY' || $timeIntervalSettings['time_format'] == 'SM-SY-EM-EY') ){ 	?>
			<div id="line_chart">
				<h3 class="txtcenter">LINE CHART</h3>
				<canvas id="line_display_canvas" width="1000" height="500">[No canvas support]</canvas>	
				<div class="graph-deatail">
					<?php
					$area_chunk = array_chunk($areaArray,4,true);
					echo "<table cellspacing='6' cellpadding='6'>";
					foreach($area_chunk as $keyM => $areas){
						echo "<tr>";
						foreach($areas as $key=> $area){
							echo "<td><div class='grahp-checkbox' style='background:".$colorsarray[$key]."'>&nbsp;</div><div class='grahp-checkbox-detail'>".$area."</div></td>";
						}
						echo "</tr>";
					}
					echo "</table>";
					?>
				</div>


				<script>
					window.onload = function ()
					{
						
					
						// Create the Line chart object. The arguments are the canvas ID and the data array.
						var line = new RGraph.Line("line_display_canvas", <?php echo $jsoncolumn; ?>);
		
						//line.Set('chart.key', <?php echo $regionjson; ?>);						
						line.Set('chart.linewidth', 1);
						//line.Set('chart.hmargin', 5);
						line.Set('chart.labels', <?php echo $yearjson; ?>);
						line.Set('chart.tooltips', <?php echo $yearjson; ?>);
						line.Set('chart.tickmarks', 'circle');							
						line.Set('chart.gutter.left', 40);
						line.Set('chart.filled', false);
						line.Set('chart.colors.alternate', true);
						line.Set('chart.colors', <?php echo $colorjson; ?>);
						line.Set('chart.zoom.factor', 0.4);
						line.Set('chart.key.interactive', true);

						line.Set('chart.events.click', function (e, shape) {
						
						
						
						});
						
																	
						// Now call the .Draw() method to draw the chart.
						line.Draw();
					}
				</script>
			</div>
			<?php } ?>

			<br/>
			<!-- <p class=" pL15"><i><font color="#000000"><?php echo count($searchedData); ?> of <?php echo count($searchedData)+100; ?></font> allowed matches returned.</i></p> -->
			<p>&nbsp;</p>
			<p>Source: RAND Texas (See <a href="statistics.php?id=<?php echo base64_encode($dbid); ?>">Statistics Summary</a> for originating data source.)</p>


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