<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/education/us_dist_finances.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = " usdistfinance";
$tablesnamearea = "usdistfinance_areas";
$tablenamecat = " usdistfinance_cats";

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


$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablenamecat, 'catname', "catcode");
$dataCat = $dbDatabase->getAll($dataSqlCat);	
if(!empty($dataCat)){
	$data .= '<div class="form-div">
				<p><span class="choose">'.((isset($lang['lbl_please_select_categories']))?$lang['lbl_please_select_categories']:'').'</span>&nbsp;&nbsp;</p>
				<div class="table-div"><select name="category[]" class="required" multiple >';
	foreach($dataCat as $CatKey => $catDetail){
		$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
	}
	$data .= '</select></div></div>';

}


if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_please_select_educational_agencies']))?$lang['lbl_please_select_educational_agencies']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";
		$stateCode = $stateToCode[$stateAlpaCode];
		//$dataSqlAll = $datareader->getUspopestAreasLike($stateCode);
		$columnsArray = array('areast' => $stateAlpaCode);
			if(isset($_REQUEST['level']) && $_REQUEST['level']!='' && $_REQUEST['level']!='null'){
			$columnsArray['arealevel'] = $_REQUEST['level'];
		}
		$dataSqlAllResult = $admin->searchLikeUniversalArray($tablesnamearea , $columnsArray, 'order by areaname');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = $stateAlpaCode;
		
		if(count($dataSqlAll)>0){
			$data .= '<div class="form-div">
						<p><span class="choose">'.$lblcities.':</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';

			foreach($dataSqlAll as $keyArea => $areaDetail){
				$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].' ('.$areaDetail['areacounty'].')</option>';	
			}
				
			$data .= '</select></div></div>';
		} else {
			$error = 1;
			$errorMSG = ((isset($lang['lbl_please_choose_other_agency']))?$lang['lbl_please_choose_other_agency']:'');
			$data = "";
		}
	}
}


$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>