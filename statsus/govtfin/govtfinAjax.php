<?php
/******************************************
* @created on Feb 28, 2013
* @Package: Rand
* @Developer: sandeep kumar
#live Site URL For This Page: http://statestats.rand.org/stats/govtfin/govt_payroll.html
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "govt_payroll";
$tablesnamearea = "govtpayroll_areas";

$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($_REQUEST['sector']) && $_REQUEST['sector']!='' && $_REQUEST['sector']!="null"){

	$formContentData = $admin->getFormContentData($dbid);
	$lang = array();
	foreach($formContentData as $key => $detail){
		$lang[$detail['label_name']] = $detail['label_value'];
	}


	$dataSqlAllResult = $admin->getDistinctColumnValuesUniversal($tablesname, 'Category', "", "limit 4000");

	$dataCat = $dbDatabase->getAll($dataSqlAllResult);	

	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_categories_govt_fin']))?$lang['lbl_select_categories_govt_fin']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['Category'].'">'.$catDetail['Category'].'</option>';	
		}
		$data .= '</select></div></div>';
	}

	$dataSqlAllResult = $admin->getDistinctColumnValuesUniversal($tablesname, 'Cat2', "", "limit 4000");

	$dataCat = $dbDatabase->getAll($dataSqlAllResult);	

	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_functions_govt_fin']))?$lang['lbl_select_functions_govt_fin']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="cat2[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['Cat2'].'">'.$catDetail['Cat2'].'</option>';	
		}
		$data .= '</select></div></div>';
	}

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";

		$stateDetail = $admin->getRowUniversal($tablesnamearea, 'areacode', $stateAlpaCode.'0000000');

		//$stateCode = $stateToCode[$stateAlpaCode];
		
		$columnsArray['areast'] = $stateAlpaCode;
		if(isset($_REQUEST['sector']) && $_REQUEST['sector']!='' ){
			$columnsArray['areatype'] = trim($_REQUEST['sector']);
		}
			
		$dataSqlAllResult = $admin->searchUniversalArray($tablesnamearea , $columnsArray, "order by areaname");
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = $stateDetail['areaname'];

		$arrayLabel = array('1' => 'Counties', '2' => 'Cities/Townships' , '3' => 'Fair Field', '4' => 'Special Districts', '5' => 'School Districts');

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_one_or_more']))?$lang['lbl_select_one_or_more']:'').' ('.$arrayLabel[$_REQUEST['sector']].') in '.$lblcities.' .</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="areacode[]" class="required" multiple >';

		foreach($dataSqlAll as $keyArea => $areaDetail){
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].'</option>';	
		}
			
		$data .= '</select></div></div>';
	}

	

} else {
	$error = 1;
	$errorMSG = "Please choose state first";
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>