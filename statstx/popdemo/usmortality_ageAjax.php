<?php
/******************************************
* @Modified on Feb 12, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://tx.rand.org/stats/popdemo/usmortality_age.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	array();

$tablesname				= "usmortal_county";
$tablesnamecat			= "usmortal_cats";
$tablesnamearea			= "usmortality_areas";
$tablesnamecountyarea	= "us_states";

//$datareader = new datareader();
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

//choose_one_more_causes Category Coloum
$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesnamecat,'LIMIT 1000');
$totalallCategoryDetail = $dbDatabase->getAll($allCategoryDetail_res);

//lbl_choose one or more categories/Cat1 coloum
$allCategoryDetailages_res  = $admin->getTableDataUniversal($tablesname,'LIMIT 1000');
$totalallAgeCategoryDetail = $dbDatabase->getAll($allCategoryDetailages_res);

//selecting citites
if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){	

	$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesnamearea,'LIMIT 1000');
	$CountyCitiesArray = $dbDatabase->getAll($allCategoryDetail_res);

	if(!empty($CountyCitiesArray)){
		foreach($CountyCitiesArray as $stateCode => $CountyCity){	
			$countiesArray[$CountyCity['areast']][$CountyCity['areacode']]=$CountyCity['areaname'];
		}
	}

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);	
	
	foreach($statesArray as $stateKey => $stateCode){
		foreach($countiesArray as $countyKey => $CountyDetail){
			if($stateCode == $countyKey){
				$relatedCounty[$countyKey] = $CountyDetail;
			}
		}
	}
}

if(!empty($totalallCategoryDetail) && !empty($totalallAgeCategoryDetail) && !empty($relatedCounty)) {

	//choose_one_more_causes Category Coloum
	if(!empty($totalallCategoryDetail)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_choose_one_more_causes']))?$lang['lbl_choose_one_more_causes']:'').'</span>&nbsp;&nbsp;</p></div>';
		
		$data .= '<select name="Category[]" class="required" multiple >';
		
		foreach($totalallCategoryDetail as $Key => $categoryDetail){
		$data .= '<option value="'.$categoryDetail['catcode'].'">'.$categoryDetail['catname'].'</option>';
		}

		$data .= '</select></div>';
	}

	//lbl_choose one or more categories/Cat1 coloum
	if(!empty($totalallAgeCategoryDetail)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_categories']))?$lang['lbl_choose_one_or_more_categories']:'').'</span>&nbsp;&nbsp;</p></div>';
		
		$data .= '<select name="Cat1[]" class="required" multiple >';
		
		foreach($totalallAgeCategoryDetail as $Key => $catDetail){
		$data .= '<option>'.$catDetail['Cat1'].'</option>';
		}

		$data .= '</select></div>';
	}

	//selecting cities
	if(!empty($relatedCounty)){
		
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_counties']))?$lang['lbl_choose_one_or_more_counties']:'').'</span></p></div>';
	
		foreach($relatedCounty as $stateKey => $stateAlpaCode){	

			$column = "areacode";	
			$stateKeyDetail = $admin->getRowUniversal($tablesnamearea, $column, $stateKey);
			
			$lblcities = '';
			if(isset($lang['lbl_City_County'])){ 
				
				//$lblcities = $lang['lbl_City_County'];
				//$lblcities = str_replace("#CITY#", $stateKeyDetail['areast'], $lblcities); 
			} 

			$data .= '<div class="form-div">
						<p><span class="choose">'.$stateKey.'</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="Area[]" class="required" multiple >';

			foreach($stateAlpaCode as $keycounty => $county){
				$data .= '<option value="'.trim($keycounty).'">'.trim($county).'</option>';
			}
				
			$data .= '</select></div></div>';
		}
	}

	//$data .= '<div class="form-div"><p><input type="checkbox" value="0" name="Area[]"> US totals </p></div>';
	
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>