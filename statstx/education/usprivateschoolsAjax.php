<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/education/us_perpupil.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname = "usprivateschools";
$tablesnamearea = "usprivateschool_areas";

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

	//if(isset($_REQUEST['sector']) && $_REQUEST['sector']!=''){
		//$columnsArrayCat = array('SchoolType' => $_REQUEST['sector']);
		//$dataSqlAllResult = $admin->searchLikeUniversalArray($tablesname , $columnsArrayCat, 'order by Category', "limit 2000");
	//} else {
		$dataSqlAllResult = $admin->getDistinctColumnValuesUniversal($tablesname, 'Category', "", "limit 4000");
	//}

	$dataCat = $dbDatabase->getAll($dataSqlAllResult);	

	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_please_choose_category_privatesch']))?$lang['lbl_please_choose_category_privatesch']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['Category'].'">'.$catDetail['Category'].'</option>';	
		}
		$data .= '</select></div></div>';
	}

	/*
	$sector = $_REQUEST['sector'];
	$sectorArray = explode(';',$sector);

	$sectorArray_out=array();

	if(!empty($sectorArray)){
		$sectorArrapop=array_slice($sectorArray,0,-1);
		foreach($sectorArrapop as $sectorkey => $sectorCode){
			if($sectorkey !=''){
				$sectorArray_out[]=$sectorCode;
			}
		}
	}

	//echo "<pre>";print_r($sectorArray_out);echo "</pre>";die;
	*/

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";
		$stateCode = $stateToCode[$stateAlpaCode];

		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, $stateAlpaCode, 'order by areaname');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = $stateAlpaCode;

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_school_in']))?$lang['lbl_choose_school_in']:'').' '.$lblcities.':</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="areacode[]" class="required" multiple >';

		foreach($dataSqlAll as $keyArea => $areaDetail){
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].' ('.$areaDetail['areacity'].', '.$areaDetail['areazip'].'; '.$areaDetail['areacnty'].' Co.)</option>';	
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