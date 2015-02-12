<?php
/******************************************
* @Modified on Feb 08, 2013, Mar 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh, Pragati Garg
* @URL : http://www.ideafoundation.in
* Dependent on: statsny/energyenv/airquality_us.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = " airqual_us";
$tablesnamearea = "airqual_us_areas";

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




if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
	
	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Category');
	if(mysql_num_rows($dataSqlCat)>0){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_catregories_air_quality']))?$lang['lbl_select_catregories_air_quality']:'').' </span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" MULTIPLE>';
		while($catDetail = mysql_fetch_assoc($dataSqlCat)){
			$data .= '<option value="'.$catDetail['Category'].'">'.$catDetail['Category'].'</option>';	
		}
		$data .= '</SELECT></div></div>';

	}

	//$data .= '<div class="form-div">
					//<p><span class="choose">'.((isset($lang['lbl_select_reporting_stations']))?$lang['lbl_select_reporting_stations']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";
		//$dataSqlAll = $datareader->getUspopestAreasLike($stateCode);
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, $stateAlpaCode, 'order by areaname');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		if(!empty($dataSqlAll)){
			$lblcities = $stateAlpaCode;

			$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_reporting_stations']))?$lang['lbl_select_reporting_stations']:'').'</span>&nbsp;&nbsp;</p></div>';

			$data .= '<div class="form-div">
						<p><span class="choose">'.$stateAlpaCode.':</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';

			foreach($dataSqlAll as $keyArea => $areaDetail){
				$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].' ('.$areaDetail['areacity'].')</option>';	
			}
				
			$data .= '</select></div></div>';
		}
	}
}else{
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>