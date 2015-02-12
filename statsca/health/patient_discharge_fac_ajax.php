<?php
/******************************************
* @Modified on April 5, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193/
* live Site URL For This Page: statsus/patient_discharge_fac.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";
require_once('data.php');

global $dbDatabase;

$tablesname				= "patient_discharge_fac";
$tablesnamecat			= "patient_discharge_cats";
$tablesnamearea			= "patient_discharge_fac_areas";

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

//common Sector
if(isset($_POST['ca_counties']) && $_POST['ca_counties']!=''){

	$resultSectors = $admin->getTableDataUniversal($tablesnamecat , ' order by catname ');
	if(mysql_num_rows($resultSectors)>0){
		$data .= '<div class="form-div">
		<p><span class="choose">'.$lang['lbl_county_category'].'</span>&nbsp;&nbsp;</p>
		<div class="table-div"><div class="table-div"><select name="category[]" class="required" multiple>';
		while($detailRow = mysql_fetch_assoc($resultSectors)){
			$data .= '<option value="'.$detailRow['catcode'].'" >'.$detailRow['catname'].'</option>';
		}
		$data .= '</select></div></div></div>';
	}
	
	$statesArray = explode(';',$_POST['ca_counties']);

	foreach($statesArray as $stateKey => $stateAlpaCode){
		if(isset($countiesFacilities[$stateAlpaCode])){
		

			$data .= '<div class="form-div">
						<p><span class="choose">'.$lang['lbl_select_one_or_more_fac'].' ('.$CACounties[$stateAlpaCode].')</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';

			$valuesArray = explode(' ', $countiesFacilities[$stateAlpaCode]);
			$ids = implode(',',$valuesArray);
			$allHospitalsResult = $admin->searchDistinctUniversalColoumINArray($tablesnamearea ,'*', 'areacode', $ids, ' order by areacity, areaname ');
			if(mysql_num_rows($allHospitalsResult)>0){
				while($vkval = mysql_fetch_assoc($allHospitalsResult)){
					$data .= '<option value="'.$vkval['areacode'].'">'.ucwords(strtolower($vkval['areacity'].': '.$vkval['areaname'])).'</option>';
				}
			}

			
			$data .= '</select></div></div>';
		}
	}
	

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>