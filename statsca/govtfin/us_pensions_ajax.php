<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/govtfin/us_pensions.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "us_pension";
$tablesnamearea = "us_pension_areas";

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
					<p><span class="choose">'.((isset($lang['lbl_select_categories_us_pensioner']))?$lang['lbl_select_categories_us_pensioner']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['Category'].'">'.$catDetail['Category'].'</option>';	
		}
		$data .= '</select></div></div>';
	}

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";
		$stateCode = $stateToCode[$stateAlpaCode];
		
		$columnsArray['areast'] = (int)$stateCode;
		if(isset($_REQUEST['sector']) && $_REQUEST['sector']!='' && $_REQUEST['sector']!='all' ){
			$columnsArray['fundtype'] = trim($_REQUEST['sector']);
		}
			
		$dataSqlAllResult = $admin->searchUniversalArray($tablesnamearea , $columnsArray, "order by areaname");
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = $stateAlpaCode;

		$arrayLabel = array('0' => 'State funds', '1' => 'County funds', '2' => 'City/Municipal/Township funds' , '3' => 'Special District/School funds');
		
		$labelPr = (isset($arrayLabel[$_REQUEST['sector']]))?$arrayLabel[$_REQUEST['sector']]:'';

		if(mysql_num_rows($dataSqlAllResult) > 0){

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_one_ore_more_us_pension']))?$lang['lbl_select_one_ore_more_us_pension']:'').' '.$lblcities.' '.$labelPr.':</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="areacode[]" class="required" multiple >';

		foreach($dataSqlAll as $keyArea => $areaDetail){
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].'</option>';	
		}
			
		$data .= '</select></div></div>';
		}else{
			$error= 1; 
		}
	}
} else {
	$error = 1;
	$errorMSG = "Please choose state first";
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>