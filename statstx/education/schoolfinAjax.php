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
	if(mysql_num_rows($dataSqlCat)>0){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_account_type']))?$lang['lbl_select_account_type']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div">';
		while($catDetail = mysql_fetch_assoc($dataSqlCat)){
			$data .= '<input type="checkbox" name="accounttype[]" class="required" value="'.$catDetail['FinanceType'].'" />&nbsp;'.$catDetail['FinanceType'].'&nbsp;<br/>';	
		}
		$data .= '</div></div>';

	}

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesnamecat, 'catname', "catcode");
	if(mysql_num_rows($dataSqlCat)>0){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_categories_school_fin']))?$lang['lbl_select_categories_school_fin']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="catcode[]" class="required" multiple >';
		$alreadyCat = array();
		while($catDetail = mysql_fetch_assoc($dataSqlCat)){
			if(!in_array($catDetail['catname'], $alreadyCat)){
			$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
			}

			$alreadyCat[] = $catDetail['catname'];


		}
		$data .= '</select></div></div>';

	}

	$data .= '<div class="form-div">
					<p><span class="choose">Please select one or more districts.</span>&nbsp;&nbsp;</p></div>';

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

				$areaArray=explode(' ',$areaDetail['distname']);
				if(!empty($areaArray) && count($areaArray) =='2' && ($areaArray[1]=='ISD' || $areaArray[1]=='isd')) {
					$areaname=ucfirst(strtolower($areaArray[0])).' '.$areaArray[1];
				} else if(!empty($areaArray) && count($areaArray) =='3' && ($areaArray[2]=='ISD' || $areaArray[2]=='isd')) {
					$areaname=ucfirst(strtolower($areaArray[0])).' '.ucfirst(strtolower($areaArray[1])).' '.$areaArray[2];
				} else {
					$areaname = ucwords(strtolower($areaDetail['distname']));
				}


				$data .= '<option value="'.$areaDetail['distcode'].'">'.$areaname.'</option>';	
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