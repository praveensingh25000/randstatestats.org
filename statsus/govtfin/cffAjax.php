<?php
/******************************************
* @created on Feb 28, 2013
* @Package: Rand
* @Developer: sandeep kumar
* @live URL: http://statestats.rand.org/stats/govtfin/cff.html
********************************************/


$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$totalallcffagencyDetail = $countiesidArray	=	array();

$tablesname					= "cffus";
$tablesnamecat				= "cff_programs";
$tablesnamearea				= "cff_agency";
$tablesnamecountyarea		= "usareas";
$tablestates				= "us_states";

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

//agency detail
$totalallcffagencyDetail_res  = $admin->getTableDataUniversal($tablesnamearea);
$totalallCategoryDetailArray = $dbDatabase->getAll($totalallcffagencyDetail_res);
foreach($totalallCategoryDetailArray as $stateKey => $value) {
	$totalallcffagencyDetail[] = array('id' => $value['agency_code'], 'name' => $value['agency_name']);
}

//selecting cities
if(isset($_REQUEST['states']) && $_REQUEST['states']!='') {	
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);	
	
	foreach($statesArray as $stateKey => $stateCode){
		$column = "id";	
		$statearray= $admin->getRowUniversal($tablestates, $column, $stateCode);
		$countiesArray[$statearray['id']]=$statearray['statename'];
		$countiesidArray[$statearray['id']]=$statearray['id'];
	}
}

//echo "<pre>";print_r($countiesArray);echo "</pre>"; die;

if(!empty($totalallcffagencyDetail) && !empty($countiesArray)) {
	
	$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['labl_Consolidated_Federal_Funds_Statistics']))?$lang['labl_Consolidated_Federal_Funds_Statistics']:'').'</span></p></div>';

	//stateDetail
	if(!empty($countiesArray) && !empty($countiesidArray)) {
		$cities=implode(',',$countiesArray);
		$data.='<p><span class="choose fontbld">'.((isset($lang['lbl_cities']))?$lang['lbl_cities']:'').'</span>&nbsp;&nbsp;&nbsp;<b>'.$cities.'</b></p>';
	
		foreach($countiesidArray AS $values){
			$data.='<input type="hidden" name="Area[]" value="'.$values.'">';
		}
	}

	//agency Coloum
	if(!empty($totalallcffagencyDetail)){
		
		$data .= '<br><div class="table-div">
					<p>
						<span class="choose">'.((isset($lang['lbl_agencies']))?$lang['lbl_agencies']:'').'</span></p>
					<div class="pL10 pB10">';

					foreach($totalallcffagencyDetail as $categoryDetail) { 
						$data.='<input class="required" type="checkbox" value="'.$categoryDetail['id'].'" name="agency[]" />
						<label for="C1">'.ucwords($categoryDetail['name']).'</label>			
						<div class="clear pB5"></div>';
					}
		
		$data .= '</div></div>';
	}
}else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>