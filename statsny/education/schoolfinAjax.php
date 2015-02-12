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

$tablesname = "schoolfin";
$tablesnamecat= "schoolfin_cats";
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


	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Cat2');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_fund_type']))?$lang['lbl_select_fund_type']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="fundtype[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['Cat2'].'">'.$catDetail['Cat2'].'</option>';	
		}
		$data .= '</select></div></div>';

	}

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'FinanceType');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_account_type']))?$lang['lbl_select_account_type']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div">';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<input type="radio" name="accounttype" class="required" value="'.$catDetail['FinanceType'].'" />&nbsp;'.$catDetail['FinanceType'].'&nbsp;<br/>';	
		}
		$data .= '</div></div>';

	}

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesnamecat, 'catname', "catcode");
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_categories_school_fin']))?$lang['lbl_select_categories_school_fin']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="catcode[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
		}
		$data .= '</select></div></div>';

	}

	$data .= '<div class="form-div">
					<p><span class="choose">Select one or more districts</span>&nbsp;&nbsp;</p></div>';

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