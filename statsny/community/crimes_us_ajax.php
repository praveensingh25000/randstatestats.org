<?php
/******************************************
* @Modified on Feb 08, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/community/crimes_us.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = " uscrime_returnA";
$tablesnamearea = "uscrime_agencies";
$tablesnamecat = "uscrime_returnA_cats";

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

$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Cat1');
$dataCat = $dbDatabase->getAll($dataSqlCat);	
if(!empty($dataCat)){
	$data .= '<div class="form-div">
				<p><span class="choose">'.((isset($lang['lbl_please_select_educational_agencies']))?$lang['lbl_please_select_educational_agencies']:'').' </span>&nbsp;&nbsp;</p>
				<div class="table-div">';
	foreach($dataCat as $CatKey => $catDetail){
		$data .= '<input type="checkbox" name="cat1[]" class="required" value="'.$catDetail['Cat1'].'">'.$catDetail['Cat1'].'<br/>';	
	}
	$data .= '</div></div>';

}

$dataSqlCat = $admin->getTableDataUniversal($tablesnamecat, 'order by catname');
$dataCat = $dbDatabase->getAll($dataSqlCat);	
if(!empty($dataCat)){
	$data .= '<div class="form-div">
				<p><span class="choose">'.((isset($lang['lbl_select_offense_crime']))?$lang['lbl_select_offense_crime']:'').'</span>&nbsp;&nbsp;</p>
				<div class="table-div"><select name="catcode[]" class="required" multiple >';
	foreach($dataCat as $CatKey => $catDetail){
		$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
	}
	$data .= '</select></div></div>';

}

if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_agency_crime']))?$lang['lbl_choose_agency_crime']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areacode";
		$stateCode = $stateToCode[$stateAlpaCode];
		//$dataSqlAll = $datareader->getUspopestAreasLike($stateCode);
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, $stateAlpaCode, 'order by areaname');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = $stateAlpaCode;

		$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.':</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="areacode[]" class="required" multiple >';

		foreach($dataSqlAll as $keyArea => $areaDetail){
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].'</option>';	
		}
			
		$data .= '</select></div></div>';
	}
}else{
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>