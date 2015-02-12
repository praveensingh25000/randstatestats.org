<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://tx.rand.org/stats/health/physicians.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	array();

$tablesname = "phyasst";
$tablesnamecat = "phyasst_cats";
$tablesnamearea = "txplaces";
$tablesnamecountyarea = "accup_areas";

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

$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesnamecat);
$totalallCategoryDetail = $dbDatabase->getAll($allCategoryDetail_res);

if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesnamecountyarea);
	$CountyCitiesArray = $dbDatabase->getAll($allCategoryDetail_res);

	if(!empty($CountyCitiesArray)){
		foreach($CountyCitiesArray as $stateCode => $CountyCity){			
			$countiesArray[$CountyCity['countyid']][$CountyCity['countycode']] =$CountyCity['countyname'];
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

if(!empty($totalallCategoryDetail) && !empty($relatedCounty)) {

	//SELECTING CATEGORY
	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_more_categories']))?$lang['lbl_select_more_categories']:'').'</span>&nbsp;&nbsp;</p></div>';
	
	$data .= '<select name="Category[]" class="required" multiple >';
	
	foreach($totalallCategoryDetail as $Key => $categoryDetail){
	$data .= '<option value="'.$categoryDetail['catcode'].'">'.$categoryDetail['catname'].'</option>';
	}

	$data .= '</select></div>';

	//SELECTING CITIES
	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_more_cities']))?$lang['lbl_select_more_cities']:'').'</span>&nbsp;&nbsp;</p></div>';

	foreach($relatedCounty as $stateKey => $stateAlpaCode) {	

		$column = "areacode";	
		$stateKeyDetail = $admin->getRowUniversal($tablesnamearea, $column, $stateKey);
		
		$lblcities = '';
		if(isset($lang['lbl_City_County'])){ 
			
			$lblcities = $lang['lbl_City_County'];
			$lblcities = str_replace("#CITY#", $stateKeyDetail['areacounty'], $lblcities); 
		} 

		$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.'</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		/*$data .= '<select name="Area[]" class="required" multiple >
			<option value="'.$stateKey.'">County totals</option>';*/
		$data .= '<select name="Area[]" class="required" multiple >';

		foreach($stateAlpaCode as $keycounty => $county){
			$data .= '<option value="'.trim($keycounty).'">'.trim($county).'</option>';
		}
			
		$data .= '</select></div></div>';
	}	

	//$data .= '<div class="form-div"><p><input type="checkbox" value="48" name="Area[]" id="S02"> Include state totals</p></div>';
	
}else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>