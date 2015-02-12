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

$tablesname           = "physicians";
$tablesnamecat        = "physicians_cats";
$tablesnamearea		  = "txplaces";
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

	$states					= $_REQUEST['states'];
	$statesArray			= explode(';',$states);

	foreach($statesArray as $statesArrayCode){

		$displaycolumnnamestr	= 'countycode,countyname';
		$tableDetailArray_res  = $admin->searchDistinctUniversalColoumOneArray($tablesnamecountyarea ,$displaycolumnnamestr, $columnname='countyid' ,$statesArrayCode, $orderby = '');
		$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

		if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {
			while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {				
				$relatedCounty[$statesArrayCode][] = array('id' => $tableDetail['countycode'] ,'name' => $tableDetail['countyname']);
			}						
		}
	}
}

if(!empty($totalallCategoryDetail) && !empty($relatedCounty)) {

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_more_categories']))?$lang['lbl_select_more_categories']:'').'</span>&nbsp;&nbsp;</p></div>';
	
	$data .= '<select name="Category[]" class="required" multiple >';
	
	foreach($totalallCategoryDetail as $Key => $categoryDetail){
	$data .= '<option value="'.$categoryDetail['catcode'].'">'.$categoryDetail['catname'].'</option>';
	}

	$data .= '</select></div>';

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_one_or_more_cities']))?$lang['lbl_select_one_or_more_cities']:'').'</span>&nbsp;&nbsp;</p></div>';

	foreach($relatedCounty as $stateKey => $stateAlpaCode){	

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

		$data .= '<select name="Area[]" class="required" multiple >';
		//$data .= '<option value="'.$stateKey.'">County totals</option>';
		foreach($stateAlpaCode as $keycounty => $county){
			$data .= '<option value="'.trim($county['id']).'">'.trim($county['name']).'</option>';
		}			
		$data .= '</select></div></div>';
	}		
	
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>