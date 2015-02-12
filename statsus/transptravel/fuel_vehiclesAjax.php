<?php
/******************************************
* @Created on Oct 04, 2013
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://randstatestats.org/statsus/transptravel/fuel_vehicles.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$admin = new admin();

$vehicleTypesArray	= $fuelTypeArray = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchAgeCategoryDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$fuelTypeArrayJson = $areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

$tablesname		= "alternative_fuel_vehicles_2003_2011";

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);

$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

if(isset($_REQUEST['selectfuel']) && isset($_REQUEST['fuelType']) && $_REQUEST['fuelType']!='') {

	//selecting related counties
	if(isset($_REQUEST['fuelType']) && $_REQUEST['fuelType']=='All'){

		//selecting All fuelType	
		$fuelType_res = $admin->getDistinctColumnValuesUniversal($tablesname,$columnname='fuelType', $columns = "",$limit = "");
		$fuelTypesArray = $dbDatabase->getAll($fuelType_res);
		foreach($fuelTypesArray as $key => $value){
			$fuelTypeArray[]  = trim($value['fuelType']);
		}
	} else {
			$fuelType      = $fuelTypeArrayJson	= $_REQUEST['fuelType'];
			$fuelTypeArray = explode(';',$fuelType);
	}

	if(!empty($fuelTypeArray)){			

		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_please_enter_vehicle_type']))?$lang['lbl_please_enter_vehicle_type']:'').'</span></p>';

		foreach($fuelTypeArray as $key => $fuelTypecode){
			
			$columnnamevaluestr1='vehicleType';
			$totalallvehicleTypeDetailArray_res  = $admin->searchDistinctUniversalColArray($tablesname ,$columnnamevaluestr1,$columnname='fuelType' ,$fuelTypecode, $orderby = '');
			$totalvehicleTypeDetailArray = mysql_num_rows($totalallvehicleTypeDetailArray_res);

			if(isset($totalvehicleTypeDetailArray) && $totalvehicleTypeDetailArray > 0) {

				$fuelTypecodeIDs = strtolower(str_replace(' ','',$fuelTypecode));
				$fuelTypecodeID  = preg_replace('/[^A-Za-z0-9\-]/', '', $fuelTypecodeIDs); 
				$outputDIV       = "<div id='".$fuelTypecodeID."' class='".$fuelTypecodeID."'></div>";	
				
				$data .= '<div class="form-div"><p><span class="choose">'.$fuelTypecode.'</span>&nbsp;&nbsp;</p>';
					
				$data .= '<div class="table-div">';

				$data .= '<select size=5 style="width:390px;" id="vehicleTypeid_'.$key.'" name="vehicleType[]" class="required" multiple >';

				while($totalvehicleTypeDetail = mysql_fetch_assoc($totalallvehicleTypeDetailArray_res)) {

					$data .= '<option value="'.trim($totalvehicleTypeDetail['vehicleType']).'">'.trim($totalvehicleTypeDetail['vehicleType']).'</option>';					
				}

				$data .= '<script type="text/javascript">
							jQuery(document).ready(function() {
					  
								jQuery("#vehicleTypeid_'.$key.'").change(function(){
									
									var vehicleType = new Array();								
									jQuery("#vehicleTypeid_'.$key.' :selected").each(function(){
										vehicleType.push(jQuery(this).val());
									});			
									
									if(vehicleType !="" && vehicleType != null) {

										var fuelType = "'.$fuelTypecode.'";
										var fuelTypeArrayJSON = "'.$fuelTypeArrayJson.'";
										
										loader_show();										
										jQuery("#timePeriod").hide();
										jQuery("#submitButtons").hide();
										jQuery.ajax({
											url: URL_SITE+"/statsus/transptravel/fuel_vehiclesAjax.php",
											type: "post",
											data: "dbid='.$dbid.'&selectvehicleType=1&fuelType="+fuelType+"&vehicleType="+vehicleType+"&fuelTypeArray="+fuelTypeArrayJSON,
												
											success: function(dataresult){
												loader_unshow();
												var obj = jQuery.parseJSON(dataresult);
												if(obj.error == 0){
													if(!jQuery("#'.$fuelTypecodeID.'").hasClass("'.$fuelTypecodeID.'")){
													 jQuery("#citiesDataLoad").append("'.$outputDIV.'");
													}
													jQuery("#'.$fuelTypecodeID.'").html(obj.data);
													jQuery("#timePeriod").show();
													jQuery("#submitButtons").show();
													jQuery(".showengineConfiguration").show();
												} else {
													jQuery("#statesDataLoad").hide();
													jQuery("#'.$fuelTypecodeID.'").hide();
													jQuery("#timePeriod").hide();
													jQuery("#submitButtons").hide();
													jQuery(".showengineConfiguration").hide();
												}
											}
										});	
										
									} else {									
										jQuery("#statesDataLoad").hide();
										jQuery("#'.$fuelTypecodeID.'").hide();
										jQuery("#timePeriod").hide();
										jQuery("#submitButtons").hide();
									}
								});
							});
				  
						  </script>';

				$data .= '</select></div></div>';				
			}			
		}

		$data .= '</div>';

		$data .= '<div class="showengineConfiguration form-div" style="display:none;">
				  <p><span class="choose">'.((isset($lang['lbl_please_enter_engine_type']))?$lang['lbl_please_enter_engine_type']:'').'</span></p></div>';

	} else {
		$error = 1;
	}

} else if(isset($_REQUEST['selectvehicleType']) && isset($_REQUEST['fuelType']) && $_REQUEST['fuelType']!='' && isset($_REQUEST['vehicleType']) && $_REQUEST['vehicleType']!='') {

	//selecting related vehicleType
	if(isset($_REQUEST['vehicleType']) && $_REQUEST['vehicleType']=='All'){

	} else {
			$fuelType			= $_REQUEST['fuelType'];
			$vehicleTypestr		= $_REQUEST['vehicleType'];
			$vehicleTypesArray	= explode(',',$vehicleTypestr);
	}

	if(!empty($vehicleTypesArray)){

		$data .= '<div class="fontbld" style="padding: 5px 0 10px !important;">';

		foreach($vehicleTypesArray as $key => $vehicleTypecode){

			$engineConfiguration_res = $admin->searchDistinctUniversalColArray($tablesname ,$columnname='engineConfiguration', $columnnamefield='vehicleType', $vehicleTypecode, $orderby = " and fuelType = '".$fuelType."' ");
			$totalengineConfiguration = mysql_num_rows($engineConfiguration_res);

			if(isset($totalengineConfiguration) && $totalengineConfiguration > 0) {

				$data .= '<p><span class="choose">'.$fuelType.' / '.$vehicleTypecode.'</span>&nbsp;&nbsp;</p>';
					
				$data .= '<div class="table-div">';

				$data .= '<select size="3" style="width:390px;" id="" name="engineConfiguration[]" class="engineConfiguration required" multiple >';

				while($totalengineConfigurationDetail = mysql_fetch_assoc($engineConfiguration_res)) {
					$data .= '<option value="'.trim($totalengineConfigurationDetail['engineConfiguration']).'">'.trim($totalengineConfigurationDetail['engineConfiguration']).'</option>';					
				}			

				$data .= '</select></div>';				
			}
		}

		$data .= '<script type="text/javascript">
				$(document).ready(function() {							
					jQuery(".engineConfiguration").change(function(){										
						jQuery("#statesDataLoad").show();
					});
				});
				</script>';

		$data .= '</div>';

	} else {
		$error = 1;
	}

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>