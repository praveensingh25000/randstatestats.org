<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/education/schoolfin.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "enrollrace";
$tablesnamecat= "enrollrace_cats";
$tableCounties = "counties_even";
$tableDistricts = "counties_districts";

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


	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesnamecat, 'catname', "catcode");
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_categories_school_enrollment']))?$lang['lbl_select_categories_school_enrollment']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="catcode[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
		}
		$data .= '</select></div></div>';

	}

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_districts_enrollment']))?$lang['lbl_select_categories_school_enrollment']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";

		$columnsArray = array('distcode' => $stateAlpaCode);
		
		$dataSqlAllResult = $admin->searchLikeUniversalArray($tableDistricts , $columnsArray, 'order by distname');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		

		$countDetail = $admin->getRowUniversal($tableCounties, 'areacode', (int)$stateAlpaCode);
		
		if(count($dataSqlAll)>0){
			$data .= '<div class="form-div">
						<p><span class="choose">'.$countDetail['areaname'].':</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';

			foreach($dataSqlAll as $keyArea => $areaDetail){
				$data .= '<option value="'.$areaDetail['distcode'].'">'.$areaDetail['distname'].'</option>';	
			}
				
			$data .= '</select></div></div>';
		} else {
			$error = 1;
			//$errorMSG = ((isset($lang['lbl_please_choose_other_agency']))?$lang['lbl_please_choose_other_agency']:'');
			$data = "";
		}
	}
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>