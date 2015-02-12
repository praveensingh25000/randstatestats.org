<?php
/******************************************
* @Modified on Feb 27, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
* Dependent on: statsus/community/popdensityUSdet.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname		  = "uspopdensity";
$tablesnamecounty = "states";
$tablesnamearea   = "fips";

$admin = new admin();

$areaDetailArrayAll = $array= $areaDetailArray = $areaDetailArray1 = $lang = $arrayCountiesData = $categories = array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//selecting all cities
if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
	
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);	
	
	foreach($statesArray as $stateKey => $stateCode){
		
		$tableDetailArray_res  = $admin->searchLikeUniversalEqual($tablesnamearea , $column='state', $searchStr=$stateCode, $orderby = " and areacode NOT LIKE '%000' order by areaname");
		$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

		if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {

			while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {				
				$relatedCounty[$tableDetail['state']][] = array('id' => $tableDetail['areacode'],'name' => $tableDetail['areaname']);			
			}
		}	
	}
}

//selecting cities
if(!empty($relatedCounty)){

	$data .= '<div class="form-div">
				<p><span class="choose">'.((isset($lang['lbl_choose_county_city']))?$lang['lbl_choose_county_city']:'').'</span></p></div>';

	foreach($relatedCounty as $stateKey => $stateAlpaCode){	

		$column = "state";	
		$stateKeyDetail = $admin->getRowUniversal($tablesnamecounty, $column, $stateKey);
		
		$lblcities = '';
		if(isset($lang['lbL_counties'])){ 
			
			$lblcities = $lang['lbL_counties'];
			$lblcities = str_replace("#CITY#", $stateKey, $lblcities); 
		} 

		$data .= '<div class="form-div">
				  <p><span class="choose">'.$stateKey.'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

				  if(isset($stateAlpaCode) && count($stateAlpaCode) > 3){					
				  $data .='<input type="checkbox" id="checkallconties_'.$stateKey.'">&nbsp;All';
				  }
				
				  $data .= '<script type="text/javascript">
							 jQuery(document).ready(function(){
								jQuery("#checkallconties_'.$stateKey.'").change(function(){
									var checkedd =  this.checked ? true : false;
									if(checkedd){
										jQuery(".selectallconties_'.$stateKey.' option").attr("selected", "selected");
									}else{
										jQuery(".selectallconties_'.$stateKey.' option").removeAttr("selected");
									}
								});

								jQuery(".selectallconties_'.$stateKey.'").change(function(){							
									jQuery("#checkallconties_'.$stateKey.'").removeAttr("checked");						
								});
							});
					   </script>';
		$data .= '</p>';

		$data .= '<select name="Area[]" class="selectallconties_'.$stateKey.' required" multiple>';
		
		foreach($stateAlpaCode as $keycounty => $county){
			if(isset($stateKey) && $county['id']!='11001') {
				if(isset($stateKeyDetail['statename']) && trim($stateKeyDetail['statename']) == trim($county['name'])) {
					$data .= '<option value="'.trim($county['id']).'"> State totals </option>';
				} else {
					$data .= '<option value="'.trim($county['id']).'">'.trim($county['name']).'</option>';
				}
			}
		}
			
		$data .= '</select></div>';
	}
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>