<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/community/weather.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname = " weathersummaryUS";

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
					<p><span class="choose">'.((isset($lang['lbl_select_weather_stations']))?$lang['lbl_select_weather_stations']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);

	$arrayCountiesData = array();
	$categories = array();
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "Cat1";
	
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesname , $column, $stateAlpaCode, 'order by Area');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$arrayCountiesData[$stateAlpaCode] = array();
		foreach($dataSqlAll as $keyArea => $areaDetail){
			$arrayCountiesData[$stateAlpaCode][$areaDetail['Area']] = $areaDetail['Area'];
		}

		$categories[$areaDetail['Category']] = $areaDetail['Category'];
	}

	foreach($arrayCountiesData as $keyStateCounty => $countiesArray){
		$data .= '<div class="form-div">
					<p><span class="choose">'.$stateAlpaCode.' '.((isset($lang['lbl_county']))?$lang['lbl_county']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="area[]" class="required" multiple >';
		
		//$countiesArray = asort($countiesArray);
		foreach($countiesArray as $keyArea => $areaDetail){
			$data .= '<option value="'.$keyArea.'">'.$areaDetail.'</option>';	
		}
			
		$data .= '</select></div></div>';
	}

	//$categories = asort($categories);
	
	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_categories_weather']))?$lang['lbl_select_categories_weather']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div">';
	$data .= '<select name="category[]" class="required" multiple >';
	foreach($categories as $categoryKey => $categoryname){
		$data .= '<option value="'.$categoryname.'">'.$categoryname.'</option>';	
	}
	$data .= '</select></div></div>';

}else{
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>