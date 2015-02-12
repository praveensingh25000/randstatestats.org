<?php
/******************************************
* @Created on Nov 25, 2013
* @Package: RAND
* @Developer: Praveen Singh
* @URL : raju@randstatestats.org:/usr/local/apache/htdocs/statsus/economics/gdp.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$admin = new admin();

$CategorysArray	= $industrynameArray = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchAgeCategoryDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$industrynameArrayJson = $areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

$tablesname		= "gdp_by_state_1997_2012";

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);

$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

if(isset($_REQUEST['selectindustryname']) && isset($_REQUEST['industry_name']) && $_REQUEST['industry_name']!='') {

	//selecting related counties
	if(isset($_REQUEST['industry_name']) && $_REQUEST['industry_name']=='All'){

		//selecting All industry_name	
		$fuelType_res = $admin->getDistinctColumnValuesUniversal($tablesname,$columnname='industry_name', $columns = "",$limit = "");
		$fuelTypesArray = $dbDatabase->getAll($fuelType_res);
		foreach($fuelTypesArray as $key => $value){
			$industrynameArray[]  = trim($value['industry_name']);
		}
	} else {
			$fuelType      = $industrynameArrayJson	= $_REQUEST['industry_name'];
			$industrynameArray = explode(';',$fuelType);
	}

	if(!empty($industrynameArray)){			

		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_please_enter_category']))?$lang['lbl_please_enter_category']:'').'</span></p>
				  <input type="hidden" value="" id="selectedcategories" class="required">
				  ';

		foreach($industrynameArray as $key => $industrynamecode){
			
			$columnnamevaluestr1='Category';
			$totalallindustryDetailArray_res  = $admin->searchDistinctUniversalColArray($tablesname ,$columnnamevaluestr1,$columnname='industry_name' ,$industrynamecode, $orderby = '');
			$totalallindustryDetailArray = mysql_num_rows($totalallindustryDetailArray_res);

			if(isset($totalallindustryDetailArray) && $totalallindustryDetailArray > 0) {

				$industrynamecodeIDS = strtolower(str_replace(' ','',$industrynamecode));
				$industrynamecodeID  = preg_replace('/[^A-Za-z0-9\-]/', '', $industrynamecodeIDS); 
				$outputDIV       = "<div id='".$industrynamecodeID."' class='".$industrynamecodeID."'></div>";	
				
				$data .= '<div class="form-div"><p><span class="choose">'.$industrynamecode.'</span>&nbsp;&nbsp;</p>';
					
				$data .= '<div class="table-div">';

				$data .= '<select size="5" style="width:390px;" id="Categoryid_'.$key.'" name="Category[]" class="" multiple >';

				while($totalindustryTypeDetail = mysql_fetch_assoc($totalallindustryDetailArray_res)) {

					$data .= '<option value="'.trim($totalindustryTypeDetail['Category']).'">'.trim($totalindustryTypeDetail['Category']).'</option>';					
				}

				$data .= '<script type="text/javascript">
							jQuery(document).ready(function() {
					  
								jQuery("#Categoryid_'.$key.'").change(function(){

									var selectedcategories = jQuery("#selectedcategories").val();

									var Category = new Array();
									
									jQuery("#Categoryid_'.$key.' :selected").each(function(){
										Category.push(jQuery(this).val());
										
										selectedcategories  = selectedcategories +"," + jQuery(this).val();
									});	
									
									jQuery("#selectedcategories").val(selectedcategories);
									
									if(Category !="" && Category != null) {
										var industry_name = "'.$industrynamecode.'";					
										loader_show();										
										jQuery("#timePeriod").hide();
										jQuery("#submitButtons").hide();
										jQuery.ajax({											
											url: URL_SITE+"/statsus/economics/gdpAjax.php",
											type: "post",
											data: "dbid='.$dbid.'&selectCategory=1&industry_name="+industry_name+"&Category="+Category,
												
											success: function(dataresult){
												loader_unshow();
												var obj = jQuery.parseJSON(dataresult);
												if(obj.error == 0){
													if(!jQuery("#'.$industrynamecodeID.'").hasClass("'.$industrynamecodeID.'")){
													 jQuery("#citiesDataLoad").append("'.$outputDIV.'");
													}
													jQuery("#'.$industrynamecodeID.'").html(obj.data);
													jQuery("#timePeriod").show();
													jQuery("#submitButtons").show();
													jQuery(".showStates").show();
												} else {												
													jQuery("#'.$industrynamecodeID.'").hide();
													jQuery("#timePeriod").hide();
													jQuery("#submitButtons").hide();
													jQuery(".showStates").hide();
												}
											}
										});	
										
									} else {										
										jQuery("#'.$industrynamecodeID.'").hide();
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

		$data .= '<div class="showStates form-div" style="display:none;">
				  <p><span class="choose">'.((isset($lang['lbl_please_enter_states']))?$lang['lbl_please_enter_states']:'').'</span></p></div>';

	} else {
		$error = 1;
	}

} else if(isset($_REQUEST['selectCategory']) && isset($_REQUEST['industry_name']) && $_REQUEST['industry_name']!='' && isset($_REQUEST['Category']) && $_REQUEST['Category']!='') {

	//selecting related Category
	if(isset($_REQUEST['Category']) && $_REQUEST['Category']=='All'){

	} else {
			$industry_name		= $_REQUEST['industry_name'];
			$Categorystr		= $_REQUEST['Category'];
			$CategorysArray	    = explode(',',$Categorystr);
	}

	if(!empty($CategorysArray)){

		$data .= '<div class="fontbld" style="padding: 5px 0 10px !important;">';
		
		$states = array();
		foreach($CategorysArray as $key => $Categorycode){

			$states_res = $admin->searchDistinctUniversalColArray($tablesname ,$columnname='States', $columnnamefield='Category', $Categorycode, $orderby = " and industry_name = '".$industry_name."' ");
			$totalStates = mysql_num_rows($states_res);

			if(isset($totalStates) && $totalStates > 0) {

				while($totalStatesDetail = mysql_fetch_assoc($states_res)) {
					$states[trim($totalStatesDetail['States'])] = trim($totalStatesDetail['States']);
				}	
							
			}
		}
							
		$data .= '<div class="table-div pB20">';

		$data .= '<select size="5" style="width:390px;" id="" name="States[]" class="States required" multiple >';

		foreach($states as $state => $statevalue){
			$data .= '<option value="'.trim($state).'">'.trim($state).'</option>';					
		}			

		$data .= '</select></div>';	

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