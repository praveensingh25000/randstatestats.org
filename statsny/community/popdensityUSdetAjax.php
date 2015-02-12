<?php
/******************************************
* @Modified on Jan 23, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/community/popdensityUSdet.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "uspopdensity";
$tablesnamearea = "fipsplace";

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


if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	

$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_county_city']))?$lang['lbl_choose_county_city']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "state";
		$stateCode = $stateToCode[$stateAlpaCode];
		//$dataSqlAll = $datareader->getUspopestAreasLike($stateCode);
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, $stateAlpaCode);
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = '';

		$data .= '<div class="form-div">
					<p><span class="choose">'.$stateAlpaCode.': </span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="Area[]" class="required" multiple >
			<option value="'.(int)$stateCode.'">State totals</option>';

		foreach($dataSqlAll as $keyArea => $areaDetail){
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].'</option>';
		}
			
		$data .= '</select></div></div>';
	}

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>