<?php
/******************************************
* @Modified on Feb 13, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://tx.rand.org/stats/popdemo/popraceageUSdet.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	array();

$tablesname					= "uspopraceage1_census";
$tablesnamecat				= "uspopraceage1_census_cats";
$tablesnamearea				= "us_states";
$tablesnamecountyarea		= "fips";

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

//categpry Detail
$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesnamecat,'LIMIT 1000');
$totalallCategoryDetail = $dbDatabase->getAll($allCategoryDetail_res);

//age detail
$allCategoryDetailages_res  = $admin->getTableDataUniversal($tablesname,'LIMIT 1000');
$totalallAgeCategoryDetail = $dbDatabase->getAll($allCategoryDetailages_res);

$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesnamecountyarea,'LIMIT 1000');
$CountyCitiesArray = $dbDatabase->getAll($allCategoryDetail_res);

if(!empty($CountyCitiesArray)){
	foreach($CountyCitiesArray as $stateCode => $CountyCity){	
		$countiesArray[$CountyCity['state']][$CountyCity['areacode']]=$CountyCity['areaname'];
	}
}

if(!empty($totalallCategoryDetail) && !empty($totalallAgeCategoryDetail) && !empty($countiesArray)) {
	
	$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_please_choose_race_gender_category']))?$lang['lbl_please_choose_race_gender_category']:'').'</span>&nbsp;&nbsp;</p></div>';
	

	//choose_one_more_causes Category Coloum
	if(!empty($totalallCategoryDetail)){
		
		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['labl_totals_gender']))?$lang['labl_totals_gender']:'').'</span></p>';
		
		$data .= '<select name="Category[]" class="required" multiple >';
		
		foreach($totalallCategoryDetail as $Key => $categoryDetail){
		$data .= '<option value="'.$categoryDetail['catcode'].'">'.$categoryDetail['catname'].'</option>';
		}

		$data .= '</select></div>';
	}

	//lbl_choose one or more categories/Cat1 coloum
	if(!empty($totalallAgeCategoryDetail)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['labl_please_choose_age_group']))?$lang['labl_please_choose_age_group']:'').'</span>&nbsp;&nbsp;</p>';
		
		$data .= '<select name="Cat1[]" class="required" multiple >';
		
		foreach($totalallAgeCategoryDetail as $Key => $catDetail){
		$data .= '<option>'.$catDetail['Cat1'].'</option>';
		}

		$data .= '</select></div>';
	}

	//selecting cities
	if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && !empty($countiesArray)) {	

		//echo "<pre>";print_r($countiesArray);echo "</pre>"; die;

		$states = $_REQUEST['states'];
		$statesArray = explode(';',$states);	
		
		foreach($statesArray as $stateKey => $stateCode){
			foreach($countiesArray as $countyKey => $CountyDetail){
				if($stateCode == $countyKey){
					$relatedCounty[$countyKey] = $CountyDetail;
				}
			}
		}

		//echo "<pre>";print_r($relatedCounty);echo "</pre>"; die;


		if(!empty($relatedCounty)){

			$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['labl_please_choose_one_more_counties']))?$lang['labl_please_choose_one_more_counties']:'').'</span></p></div>';

			foreach($relatedCounty as $stateKey => $stateAlpaCode){	

				//$column = "statecode";	
				//$stateKeyDetail = $admin->getRowUniversal($tablesnamearea, $column, $stateKey);
				
				$lblcities = '';
				if(isset($lang['lbl_counties'])){ 
					
					$lblcities = $lang['lbl_counties'];
					$lblcities = str_replace("#CITY#", $stateKey, $lblcities); 
				} 

				$data .= '<div class="form-div">
							<p><span class="choose">'.$lblcities.'</span>&nbsp;&nbsp;</p>';

				$data .= '<select name="Area[]" class="required" multiple >';

				foreach($stateAlpaCode as $keycounty => $county){
					$data .= '<option value="'.trim($keycounty).'">'.trim($county).'</option>';
				}
					
				$data .= '</select></div>';
			}
		}
		//$data .= '<div class="form-div"><p><input type="checkbox" value="99" name="Area[]"> Include U.S. totals </p></div>';
	}
}else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>