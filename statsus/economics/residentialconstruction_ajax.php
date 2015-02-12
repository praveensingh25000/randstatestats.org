<?php
/******************************************
* @Created on Jan 09, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193
* @live Site URL For This Page: http://tx.rand.org/stats/economics/airport_us.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "residential_construction";
$tablesnamearea = "fipsplace";
$tablesnamefip = "fips";
$tablesnamestate = "states";
$tablecats = "usconst_cats";

$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;


$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	
	$statesstr = "";
	foreach($statesArray as $keySt => $state){
		$statesstr .= "'".$state."',";
	}

	$statesstr = substr($statesstr, 0, -1);

	$sqlCounties = "select * from ".$tablesnamefip." where state_code IN (".$statesstr.") and val_type = 'C'";
	$resultCounties = $dbDatabase->run_query($sqlCounties);
	$counties	  = $dbDatabase->getAll($resultCounties);

	
	if(count($counties)>0){
		$data .= ' <div class="form-div"><p><span class="choose">Please enter one or more counties.</span>&nbsp;&nbsp;</p><div class="table-div">
		<input type="text" id="search_criteria_duplicates_counties" name="counties"/>
		';
		
		$countyToNamefiltervaluesJsonArray = array();

		foreach($counties as $keyCat => $countyDetail){
			if($countyDetail['areaname']!=NULL){
				$countyToNamefiltervaluesJsonArray[] = array('id' => $countyDetail['county_code'], 'name' => $countyDetail['areaname']);
			}
		}
		
		$filtervaluesJsonState = json_encode($countyToNamefiltervaluesJsonArray);

		$data .= "<script type='text/javascript'>
			$(document).ready(function() {							

			$('#search_criteria_duplicates_counties').tokenInput(".$filtervaluesJsonState.", { preventDuplicates: true,
				tokenLimit: 3,
				onAdd: function (item) {
					var counties = jQuery('#search_criteria_duplicates_counties').val();
					var states = jQuery('#search_criteria_duplicates').val();
					loader_show();
					jQuery('#timePeriod').hide();
					jQuery('#submitButtons').hide();
					jQuery.ajax({
						url: '".URL_SITE."/statsus/economics/residentialconstruction_ajax.php',
						type: 'post',
						data: 'dbid=".$dbid."&counties='+counties+'&stateid='+states,
						success: function(dataresult){
							loader_unshow();
							var obj = jQuery.parseJSON(dataresult);

							if(obj.error == 0){
								jQuery('#citiesDataLoad').html(obj.data);
								jQuery('#timePeriod').show();
								jQuery('#submitButtons').show();
							}else{
								jQuery('#citiesDataLoad').html('');
								jQuery('#timePeriod').hide();
								jQuery('#submitButtons').hide();
							}
						}
					});
				},
				onDelete: function (item) {
					var counties = jQuery('#search_criteria_duplicates_counties').val();
					var states = jQuery('#search_criteria_duplicates').val();
					loader_show();
					jQuery('#timePeriod').hide();
					jQuery('#submitButtons').hide();
					jQuery.ajax({
						url: '".URL_SITE."/statsus/economics/residentialconstruction_ajax.php',
						type: 'post',
						data: 'dbid={$dbid}&counties='+counties+'&stateid='+states, 
						success: function(dataresult){
							loader_unshow();
							var obj = jQuery.parseJSON(dataresult);
							if(obj.error == 0){
								jQuery('#citiesDataLoad').html(obj.data);
								jQuery('#timePeriod').show();
								jQuery('#submitButtons').show();
							}else{
								jQuery('#citiesDataLoad').html('');
								jQuery('#timePeriod').hide();
								jQuery('#submitButtons').hide();
							}
						}
					});
				}

			});

			});
			</script>";

			$data .= '</div></div>';
	}
} else if(isset($_REQUEST['counties']) && $_REQUEST['counties']!=''){
	$counties = $_REQUEST['counties'];
	$countiesArray = explode(';',$counties);

	$stateid = $_REQUEST['stateid'];
	
	$countysstr = "";
	foreach($countiesArray as $keyCu => $county){

		$sqlCities = "select ".$tablesnamearea.".areaname, ".$tablesnamearea.".areacode from ".$tablesnamearea." where areacode = any ( select distinct(area_code) from ".$tablesname." where ".$tablesname.".county_code  = ".$county." and state_code = '".$stateid."' ) order by ".$tablesnamearea.".areaname";
		$resultCities = $dbDatabase->run_query($sqlCities);
		$cities	  = $dbDatabase->getAll($resultCities);

	
		if(count($cities)>0){
			$data .= ' <div class="form-div"><p><span class="choose">Please enter one or more cities/towns.</span>&nbsp;&nbsp;</p><div class="table-div">
			<select name="cities[]" class="required" multiple>
			';
			
			$cityToNamefiltervaluesJsonArray = array();

			foreach($cities as $keyCat => $cityDetail){
				if($cityDetail['areaname']!=NULL){
					$data .= "<option value='".$cityDetail['areacode']."'>".$cityDetail['areaname']."</option>";
				}
			}
					
			$data .= '</select></div></div>';

			

		}
	}
	
	if(count($countiesArray) >0){

		$data .= ' <div class="form-div"><p><span class="choose">Please enter one or more zips seperated by (;).</span>&nbsp;&nbsp;</p><div class="table-div">
		<input type="text" id="zips" name="zips" /></div></div>';

		$categoryResult = $admin->getTableDataUniversal($tablecats , 'catname');
		$categories	  = $dbDatabase->getAll($categoryResult);
		if(count($categories) >0){
			$data .= ' <div class="form-div"><p><span class="choose">Please choose one or more categories.</span>&nbsp;&nbsp;</p><div class="table-div">
			<table>';
			$catArray = array_chunk($categories, 4);
			foreach($catArray as $keyAr => $categories){
				$data .= '<tr>';
				foreach($categories as $catDetail){
					$data .= '<td valign="top"><input type="checkbox" value="'.$catDetail['catcode'].'" name="category[]">&nbsp;'.$catDetail['catname'].'</td>';
				}
				$data .= '</tr>';
			}

			$data .= '</table></div></div>';
			
		}
	}

} else {
	$error = 1;
}


$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>