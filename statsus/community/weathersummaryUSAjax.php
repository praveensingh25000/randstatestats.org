<?php
/******************************************
* @Modified on March 12, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: statsus/community/weathersummaryUS.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname		  = "weathersummaryUS";
$tablesnameStates = "us_states";

$admin = new admin();

$areaDetailArrayCheck = $array= $areaDetailArray = $areaDetailArray1 = $lang = $arrayCountiesData = $categories = array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

if(isset($_REQUEST['countries']) && $_REQUEST['countries']!=''){

	$countries = $_REQUEST['countries'];
	$countriessArrayAll = explode(';',$countries);
	$already = array();

	foreach($countriessArrayAll as $stateKey1 => $countryAlpaCode1){
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesname , 'State', $countryAlpaCode1, 'order by Cat1');			
		if(mysql_num_rows($dataSqlAllResult)>0){
			while($areaDetail = mysql_fetch_assoc($dataSqlAllResult)){
				$areaDetailArrayCheck[$countryAlpaCode1][$areaDetail['Cat1']] = $areaDetail['Cat1'];
			}
		}
	}

	if(!empty($areaDetailArrayCheck)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_select_weather_stations']))?$lang['lbl_select_weather_stations']:'').'</span>&nbsp;&nbsp;</p></div>';
		
		$countries = $_REQUEST['countries'];
		$countriessArray = explode(';',$countries);
		$already = array();

		foreach($areaDetailArrayCheck as $countryAlpaCode => $areaDetailAll){

			$stateDetail = $admin->getRowUniversal($tablesnameStates, 'statecode', $countryAlpaCode);

			$data .= '<div class="form-div"><p><span class="choose">'.$stateDetail['statename'].'</span></p>';

			$dataSqlAllResult = $admin->searchLikeUniversal($tablesname , 'State', $countryAlpaCode, 'order by Cat1');		
			
			$data .= '<select name="Cat1[]" id="state_'.$countryAlpaCode.'" class="selectboxes required" multiple >';
			foreach($areaDetailAll as $areaDetail){
				$data .= '<option value="'.$areaDetail.'">'.$areaDetail.'</option>';
			}
			$data .= '</select>';

			$data .= '<script type="text/javascript">
						$(document).ready(function() {	
							$("#state_'.$countryAlpaCode.'").change(function(){
								var states = "";
								jQuery(".selectboxes").each(function(){ 
									var st =   jQuery(this).val();
									states = states+","+st;
								});
								loader_show();
								jQuery.ajax({
										url: "'.URL_SITE.'/statsus/community/weathersummaryUSAjax.php",
										type: "post",
										data: "dbid='.$dbid.'&states="+states,
										success: function(dataresult){
											
											loader_unshow();
											var obj = jQuery.parseJSON(dataresult);

											if(obj.error == 0){
												jQuery("#countiesDataLoad").html(obj.data);
												jQuery("#timePeriod").show();
												jQuery("#submitButtons").show();
											}else {
												jQuery("#countiesDataLoad").html("");
												jQuery("#timePeriod").hide();
												jQuery("#submitButtons").hide();
											}
										}
									});
							});
						});
					</script>
					';	

			$data .= '</div>';
		}
		$data .= '</div>';

	} else {
		$error = 1;
	}

} elseif(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	$data .= '<div class="form-div">
					<p><span class="choose">please select one or more weather stations.</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(',',$states);
	
	$found = 0;
	
	foreach($statesArray as $stateKey => $stateAlpaCode){
		if($stateAlpaCode!=''){
			$searchStr = $stateAlpaCode;
			$column = "Cat1";
		
			$dataSqlAllResult = $admin->searchLikeUniversal($tablesname , $column, $stateAlpaCode, 'order by Cat1');
			$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);
			
			foreach($dataSqlAll as $areaDetail1){
				$areaDetailArray1[$areaDetail1['Area']] = $areaDetail1['Area'];
			}

			$already = array();
			$arrayCountiesData[$stateAlpaCode] = array();
			
			foreach($areaDetailArray1 as $keyArea => $areaDetail){
				$found = 1;				
				$arrayCountiesData[$stateAlpaCode][$areaDetail] = $areaDetail;
			}
		}
	}
	
	if($found == 1){
		foreach($arrayCountiesData as $keyStateCounty => $countiesArray){
			$data .= '<div class="form-div">
						<p><span class="choose">'.$keyStateCounty.' County</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="area[]" class="required" multiple >';
			
			
			foreach($countiesArray as $keyArea => $areaDetail){
				$data .= '<option value="'.$keyArea.'">'.$areaDetail.'</option>';	
			}
				
			$data .= '</select></div></div>';
		}

		$dataSqlCategories = $admin->getDistinctColumnValuesUniversal($tablesname , 'Category', "");
		if(mysql_num_rows($dataSqlCategories)>0){
			$data .= '<div class="form-div">
							<p><span class="choose">Please select one or more categories.'.((isset($lang['lbl_select_categories_weather']))?$lang['lbl_select_categories_weather']:'').'</span>&nbsp;&nbsp;</p>
							<div class="table-div">';
			$data .= '<select name="category[]" class="required" multiple >';

			

			while($categoryDetail = mysql_fetch_assoc($dataSqlCategories)){
				$data .= '<option value="'.$categoryDetail['Category'].'">'.$categoryDetail['Category'].'</option>';	
			}
			$data .= '</select></div></div>';
		}
	} else {
		$error = 1;
	}

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>