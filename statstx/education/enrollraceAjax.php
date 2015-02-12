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
$tableCounties = "distschools";

$admin = new admin();

$array= $dataAll = array();

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
			  <p><span class="choose">'.((isset($lang['lbl_select_districts_enrollment']))?$lang['lbl_select_districts_enrollment']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$columnsArray = array('County' => $stateAlpaCode);
		
		$dataSqlAllResult = $admin->searchLikeUniversalArray($tableCounties , $columnsArray, 'order by District');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);
		
		if(count($dataSqlAll)>0){

			foreach($dataSqlAll as $datavalues){
				if(trim($datavalues['District']) == trim($datavalues['School'])) {
				$dataAll[$datavalues['District']]=array('id' => $datavalues['School_Code'],'name'=> $datavalues['District']);
				}
			}

			$data .= '<div class="form-div">
						<p><span class="choose">'.$stateAlpaCode.':</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';
			$already = array();
			foreach($dataAll as $keyArea => $areaDetail){
					
					$areaArray=explode(' ',$areaDetail['name']);
					if(!empty($areaArray) && count($areaArray) =='2' && ($areaArray[1]=='ISD' || $areaArray[1]=='isd')) {
						$areaname=ucfirst(strtolower($areaArray[0])).' '.$areaArray[1];
					} else if(!empty($areaArray) && count($areaArray) =='3' && ($areaArray[2]=='ISD' || $areaArray[2]=='isd')) {
						$areaname=ucfirst(strtolower($areaArray[0])).' '.ucfirst(strtolower($areaArray[1])).' '.$areaArray[2];
					} else {
						$areaname = ucwords(strtolower($areaDetail['name']));
					}

					$data .= '<option value="'.$areaDetail['id'].'">'.$areaname.'</option>';
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