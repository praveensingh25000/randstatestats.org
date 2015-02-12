<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/education/student.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "student";
$tablesnamecat = "student_cats";
$tableDistricts = "distschools";

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

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesnamecat, 'catname', 'catcode');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_categories_student']))?$lang['lbl_select_categories_student']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
		}
		$data .= '</select></div></div>';

	}


	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_districts_staff_student']))?$lang['lbl_select_districts_staff_student']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";

		$columnsArray = array('County' => $stateAlpaCode);
		
		$dataSqlAllResult = $admin->searchLikeUniversalArray($tableDistricts , $columnsArray, 'order by School');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
				
		if(count($dataSqlAll)>0){
			$data .= '<div class="form-div">
						<p><span class="choose">'.ucfirst(strtolower($stateAlpaCode)).' County:</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';

			foreach($dataSqlAll as $keyArea => $areaDetail){

				$areaArray=explode(' ',$areaDetail['School']);

				if(!empty($areaArray) && count($areaArray) =='2' && ($areaArray[1]=='ISD' || $areaArray[1]=='isd')){
				
					if(!empty($areaArray) && count($areaArray) =='2' && ($areaArray[1]=='ISD' || $areaArray[1]=='isd')) {
						$areaname=ucfirst(strtolower($areaArray[0])).' '.$areaArray[1];
					} else if(!empty($areaArray) && count($areaArray) =='3' && ($areaArray[2]=='ISD' || $areaArray[2]=='isd')) {
						$areaname=ucfirst(strtolower($areaArray[0])).' '.ucfirst(strtolower($areaArray[1])).' '.$areaArray[2];
					} else {
						$areaname = ucwords(strtolower($areaDetail['School']));
					}

					$data .= '<option value="'.$areaDetail['School_Code'].'">'.$areaname.'</option>';	
				}
			}
				
			$data .= '</select></div></div>';
		} else {
			$error = 1;
			//$errorMSG = ((isset($lang['lbl_please_choose_other_agency']))?$lang['lbl_please_choose_other_agency']:'');
			$data = "";
		}
	}
} else {
	$error = 1;
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>