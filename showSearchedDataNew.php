<?php
/******************************************
* @Modified on Jan 11, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

global $db;

$searchedfields = $_SESSION['searchedfieldsonestage'];

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

if(!isset($allDbTables) || (isset($allDbTables) && count($allDbTables)<=0)){
	echo "There are no tables associated with this form yet. Please contact administrator of site.";
	exit;
}

$columnstobefetched = substr($columnstobefetched, 0, -1);


$sql = "select ".$columnstobefetched." from ".$tablesStr." where 1 ".$fieldsStrWhere." ".$joinStr." limit 4000";

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
					

					<!-- SHARING PAGE -->	
					
					<li class=""><span class='st_sharethis_custom'>&nbsp;</span></li>
					<!-- /SHARING PAGE -->	
				</ul>
			<div class="clear pT10"> </div>
				<?php } ?>
				<!-- /PAGE LINKS -->
				
				<?php if(isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SQ-SY-EQ-EY' || $timeIntervalSettings['time_format'] == 'SY-EY' || $timeIntervalSettings['time_format'] == 'SM-SY-EM-EY')){ 	?>
				<!-- LINE PIE BAR CHART ------------------------->
				<ul class="submenu">
					<li id="showGrid" <?php if((isset($_GET['graph']) && $_GET['graph']=='gridview') || !isset($_GET['graph'])) echo 'class="current"'; ?>><a href="showSearchedData.php">Grid View</a></li>		

					<li id="showChart"  <?php if(isset($_GET['graph']) && $_GET['graph']=='linegraph') echo 'class="current"'; ?>><a href="?graph=linegraph" >Line Graph</a>
					</li>

					<li id="showChart"  <?php if(isset($_GET['graph']) && $_GET['graph']=='bargraph') echo 'class="current"'; ?>><a href="?graph=bargraph" >Bar Graph</a>
					</li>
								
				</ul>
				<?php }?>
			</div>
			<div class="clear pT10"></div>
			<div class="search-table-data" style="<?php if((isset($_GET['graph']))){  ?> display:none; <?php } ?>">
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
					<td align="left"><?php echo $fieldValue; ?></td>
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
			<?php if((isset($_GET['graph']) && ($_GET['graph']=='linegraph' || $_GET['graph']=='bargraph')) && isset($timeIntervalSettings) && ($timeIntervalSettings['time_format'] == 'SQ-SY-EQ-EY' || $timeIntervalSettings['time_format'] == 'SY-EY' || $timeIntervalSettings['time_format'] == 'SM-SY-EM-EY') ){ 	
				
				$attributesResult = $admin->getDatabaseGraphAttributes($dbid);
				$graphAttributes = array('x_label' => '', 'y_label' => '', 'graph_label' => '');

				if(mysql_num_rows($attributesResult)>0){
					while($graphAttr = mysql_fetch_assoc($attributesResult)){
						$graphAttributesArray[$graphAttr['attribute_name']] = $graphAttr['attribute_value'];
					}
				}
				
				if($_GET['graph'] == 'bargraph' && isset($graphAttributesArray)){
					$graphAttributes = array('x_label' => $graphAttributes['x_axis_bar_label'], 'y_label' => $graphAttributes['y_axis_bar_label'], 'graph_label' => $graphAttributes['graph_bar_label']);
				} else if(isset($graphAttributesArray)){
					$graphAttributes = array('x_label' => $graphAttributes['x_axis_line_label'], 'y_label' => $graphAttributes['y_axis_line_label'], 'graph_label' => $graphAttributes['graph_line_label']);
				}

				$graphtype = ($_GET['graph'] == 'bargraph')?'bars':'lines';
			?>
			<div id="line_chart">
				<h3 class="txtcenter"><?php echo $graphAttributes['graph_label']; ?></h3>
				<!-- <canvas id="line_display_canvas" width="1000" height="500">[No canvas support]</canvas>	 -->
				<table width="100%" cellspacing="4" cellpadding="4">
					<tr>
						<td  width="5%">&nbsp</td>
						<td  colspan="2"><strong>Options:</strong>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" value="0" name = "showgraphoption" class = "showgraphOption" checked>&nbsp;<?php echo ucfirst($graphtype); ?> Only&nbsp;&nbsp;<input type="radio" name = "showgraphoption" value="1" class = "showgraphOption">&nbsp;Points Only&nbsp;&nbsp;<input type="radio" name = "showgraphoption" class = "showgraphOption" value="2">&nbsp;Both</td>
					</tr>

					<tr>
						<td width="5%"><div class="y-axislabel"><?php echo $graphAttributes['y_label']; ?></div></td>
						<td>
							<div id="line_display_canvas" style="width:auto;height:500px;"></div>	
							<div class="x-axislabel"><?php echo $graphAttributes['x_label']; ?></div>
						</td>
					</tr>
					<tr>
						<td width="5%">&nbsp;</td>
						<td>
							<div class="graph-deatail" id="choices">
								<?php
								/*$area_chunk = array_chunk($areaArray,4,true);
								echo "<table cellspacing='6' cellpadding='6'>";
								foreach($area_chunk as $keyM => $areas){
									echo "<tr>";
									foreach($areas as $key=> $area){
										echo "<td><div class='grahp-checkbox' style='background:".$colorsarray[$key]."'>&nbsp;</div><div class='grahp-checkbox-detail'>".$area."</div></td>";
									}
									echo "</tr>";
								}
								echo "</table>";*/
								?>
							</div>
							<div class="clear">&nbsp;</div>
						</td>
					</tr>
				</table>
				<script language="javascript" type="text/javascript" src="<?php echo URL_SITE; ?>/js/jquery.flot.js"></script>
				
				<input type="hidden" id="showPoints" value="0" />
				<script>
					$(function () {


						var datasets = {
						<?php foreach($arrayColumns as $keyAr => $datasetsArray) { 
							
							
							?>
							"checkbox_<?php echo $keyAr; ?>": {
								label: "<?php echo $areaArray[$keyAr]; ?>",
								data: [<?php foreach($datasetsArray as $keyDataset => $dataset) { 
									if($dataset>0){
										echo "[".$yearArray[$keyDataset].",".$dataset."],";
									} else {
										echo "[".$yearArray[$keyDataset].",null],";
									}

								} ?>]
							}<?php if(count($arrayColumns) != $keyAr+1){ echo ","; } ?> 
						<?php } ?>
							
						};

						// hard-code color indices to prevent them from shifting as
						// countries are turned on/off
						var i = 0;
						$.each(datasets, function(key, val) {
							val.color = i;
							++i;
						});
						
						var count = 0;

						// insert checkboxes 
						var choiceContainer = $("#choices");
						$.each(datasets, function(key, val) {
							var stringval = val.label;
							var labelwithoutspaces = stringval.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');

							choiceContainer.append('<div class="serieslabelcontainer"><div class="seriescheckbox"><input type="checkbox" name="' + key +
												   '" checked="checked" id="id' + key + '"></div>' +
												   '<div class="seriescolor ' + labelwithoutspaces + ' " id="color_codes' + key + '" ></div><label class="serieslabel" for="id' + key + '">'
													+ val.label + '</label></div>');
							count++;
						});
						choiceContainer.find("input").click(plotAccordingToChoices);

						
						function plotAccordingToChoices() {
							var data = [];
							
							var showPointsVal = jQuery('#showPoints').val();
							
							var showpoints = false;

							var showgraph = true;

							if(showPointsVal == '1'){
								showpoints = true;
								showgraph = false;
							} else if(showPointsVal == '2'){
								showpoints = true;
								showgraph = true;
							}

							choiceContainer.find("input:checked").each(function () {
								var key = $(this).attr("name");
								if (key && datasets[key])
									data.push(datasets[key]);
							});

							if (data.length > 0)
								var plot = $.plot($("#line_display_canvas"), data, {
									legend: { show: false,  noColumns: 3 },
									yaxis: { min: 0 },
									xaxis: { tickDecimals: 0 },
									series: {
									   <?php echo $graphtype; ?>: { show: showgraph, lineWidth: 1 },
									   points: { show: showpoints,  lineWidth: 1 }

									},
									grid: { hoverable: true, clickable: true }
								});
							
								var series = plot.getData();
								
								 for (var i = 0; i < series.length; ++i){
									var stringval = series[i].label;
									var labelwithoutspaces = stringval.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
									jQuery('.' + labelwithoutspaces + '').html('<div style="border:1px solid #ccc;padding:1px"><div style="width:4px;height:0;border:5px solid ' + series[i].color + ';overflow:hidden"></div></div>');
								} 

								for (var i = 0; i < count; ++i){
									if(jQuery('#idcheckbox_' +  i + '').is(':checked')){
									} else {
										jQuery('#color_codescheckbox_' + i + ' ').html('');
									}
								}
							}

						plotAccordingToChoices();

						jQuery('.showgraphOption').click(function(){
							var value = jQuery(this).val();
							jQuery('#showPoints').val(value);
							plotAccordingToChoices();
						
						});
						
						function showTooltip(x, y, contents) {
							$('<div id="tooltip">' + contents + '</div>').css( {
								position: 'absolute',
								display: 'none',
								top: y + 5,
								left: x + 5,
								border: '1px solid #fdd',
								padding: '2px',
								'background-color': '#fee',
								opacity: 0.80
							}).appendTo("body").fadeIn(200);
						}

						var previousPoint = null;
						$("#line_display_canvas").bind("plothover", function (event, pos, item) {
							$("#x").text(pos.x.toFixed(2));
							$("#y").text(pos.y.toFixed(2));

							
							if (item) {
								if (previousPoint != item.dataIndex) {
									previousPoint = item.dataIndex;
									
									$("#tooltip").remove();
									var x = item.datapoint[0].toFixed(2),
										y = item.datapoint[1].toFixed(2);
									
									showTooltip(item.pageX, item.pageY,
												item.series.label + " of " + parseInt(x) + " = " + y);
								}
							}
							else {
								$("#tooltip").remove();
								previousPoint = null;            
							}
							
						});

					});
				</script>
			</div>
			<?php } ?>

		
			
			<p>&nbsp;</p>

			<?php if($db_datasource!=''){ ?>
			<p>RAND State Statistics - <?php echo $siteMainDBDetail['db_code']; ?>, based on <?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?><a href="<?php echo $db_datasourcelink; ?>"><?php }?><?php echo $db_datasource; ?><?php if(isset($db_datasourcelink) && $db_datasourcelink != ''){ ?></a><?php } ?>
			</p>

			
			<?php } ?>


		<?php } else { ?>
			
			<div class="clear"></div>
			<div class="pT20 txtcenter"><b>No Records Found</b></div>

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