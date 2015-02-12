<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/education/usk12dropout.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = " usk12dropout";
$tablesnamearea = "usk12dropout_areas";

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


$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Category');
$dataCat = $dbDatabase->getAll($dataSqlCat);	
if(!empty($dataCat)){
	$data .= '<div class="form-div">
				<p><span class="choose">'.((isset($lang['lbl_select_offense']))?$lang['lbl_select_offense']:'').'</span>&nbsp;&nbsp;</p>
				<div class="table-div"><select name="category[]" class="required" multiple >';
	foreach($dataCat as $CatKey => $catDetail){
		$data .= '<option value="'.$catDetail['Category'].'">'.$catDetail['Category'].'</option>';	
	}
	$data .= '</select></div></div>';

}

if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_educational_agency_dropout']))?$lang['lbl_select_educational_agency_dropout']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";
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
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].' ('.$areaDetail['areacity'].', '.$areaDetail['areazip'].')</option>';	
		}
			
		$data .= '</select></div></div>';
	}

	

}else{
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>