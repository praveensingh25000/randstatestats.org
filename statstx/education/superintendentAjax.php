<?php
/******************************************
* @Modified on Feb 15, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: tx.rand.org/stats/education/superintendent.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$CountyCitiesArray = $relatedCountyArrays = $totalallCategoryDetailArray = $totalallCategoryDetailArrays = array();

$tablesname		= "superpay";
$tablesnamearea = "distschools";

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

$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesname);
$totalallCategoryDetailArray = $dbDatabase->getAll($allCategoryDetail_res);
foreach($totalallCategoryDetailArray as $stateKey => $totalallCategoryDetails) {
	$totalallCategoryDetail[$totalallCategoryDetails['Category']] = $totalallCategoryDetails['Category'];
}

if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){	

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);

	foreach($statesArray as $stateKey => $statesCode) {
		//$relatedCounty[$statesCode] = $admin->searchDistinctUniversalColoumArray($tablesnamearea , $columnname='County_Code', $statesCode, $orderby = '');

		//added by pragati Garg on 7/11/2013

		$relatedCounty[$statesCode] = $admin->searchDistinctUniversalColoumArray($tablesnamearea , $columnname='County_Code', $statesCode, $orderby = "and School_Code LIKE '%000'");
	}
}else{
	$error =1;
}

if(!empty($totalallCategoryDetail) && !empty($relatedCounty)) {

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select one or more categories']))?$lang['lbl_select one or more categories']:'').'</span>&nbsp;&nbsp;</p></div>';
	
	$data .= '<select name="Category[]" class="required" multiple >';
	
	foreach($totalallCategoryDetail as $Key => $categoryDetail){
	$data .= '<option value="'.$categoryDetail.'">'.$categoryDetail.'</option>';
	}

	$data .= '</select></div>';


	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select one or more districts']))?$lang['lbl_select one or more districts']:'').'</span>&nbsp;&nbsp;</p></div>';
	
	foreach($relatedCounty as $stateKey => $stateAlpaCode) {
		
		$relatedCountyDetail = $admin->getRowUniversal($tablesnamearea, $column='County_Code', $stateKey);

		$lblcities = '';
		if(isset($lang['lbl_county'])){ 
			
			$lblcities = $lang['lbl_county'];
			$lblcities = str_replace("#CITY#", ucwords(strtolower($relatedCountyDetail['County'])), $lblcities); 
		} 
		$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.'</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="cds[]" class="required" multiple >
			<option value="'.$stateKey.'">County totals</option>';

		foreach($stateAlpaCode as $keycounty => $county){
			$data .= '<option value="'.trim($county['School_Code']).'">'.trim($county['District']).'</option>';
		}
			
		$data .= '</select></div></div>';
	}
	
	//$data .= '<div class="form-div"><p><input type="checkbox" value="48" name="cds[]" id="S02"> Include state totals<//p></div>';
	
}else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>