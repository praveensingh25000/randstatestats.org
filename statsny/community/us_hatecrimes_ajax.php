<?php
/******************************************
* @Modified on Feb 08, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: statsny/community/us_hatecrimes.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "us_hatecrimes";


//$datareader = new datareader();
$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
	
	
	$formContentData = $admin->getFormContentData($dbid);
	$lang = array();
	foreach($formContentData as $key => $detail){
		$lang[$detail['label_name']] = $detail['label_value'];
	}

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Cat1');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_please_select_bias_category']))?$lang['lbl_please_select_bias_category']:'').' </span>&nbsp;&nbsp;</p>
					<div class="table-div">';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<input type="checkbox" name="cat1[]" class="required" value="'.$catDetail['Cat1'].'">'.$catDetail['Cat1'].'<br/>';	
		}
		$data .= '</div></div>';

	}

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_agencies_cities_counties']))?$lang['lbl_select_agencies_cities_counties']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "Area";;
		//$dataSqlAll = $datareader->getUspopestAreasLike($stateCode);
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesname , $column, $stateAlpaCode, 'order by Category');
		
		$lblcities = $stateAlpaCode;
		$already = array();
		$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.':</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="areacode[]" class="required" multiple >';

		while($areaDetail = mysql_fetch_assoc($dataSqlAllResult)){
			if(!in_array($areaDetail['Category'], $already))
			$data .= '<option value="'.$areaDetail['Category'].'">'.$areaDetail['Category'].'</option>';	

			$already[] = $areaDetail['Category'];
		}
			
		$data .= '</select></div></div>';
	}
}else{
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>