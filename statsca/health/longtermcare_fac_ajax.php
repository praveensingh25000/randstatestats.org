<?php
/******************************************
* @Modified on April 5, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193/
* live Site URL For This Page: statsca/health/longtermcare_fac.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";
require_once('data.php');

global $dbDatabase;

$tablesname				= "longtermcare_fac";
$tablesnamecats			= "longtermcare_cats";
$tablesnamearea			= "longtermcare_fac_areas";

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

	$resultSectors = $admin->getDistinctColumnValuesUniversal($tablesnamecats , 'catcode', 'catname');
	if(mysql_num_rows($resultSectors)>0){
		$data .= '<div class="form-div">
		<p><span class="choose">'.$lang['lbl_enter_cats_hos'].'</span>&nbsp;&nbsp;</p>
		<div class="table-div"><div class="table-div"><select name="category[]" class="required" multiple>';
		while($detailRow = mysql_fetch_assoc($resultSectors)){
			$data .= '<option value="'.$detailRow['catcode'].'" >'.$detailRow['catname'].'</option>';
		}
		$data .= '</select></div></div></div>';
	}
	
	$statesArray = explode(';',$_POST['ca_counties']);

	foreach($statesArray as $stateKey => $stateAlpaCode){
			

			$data .= '<div class="form-div">
						<p><span class="choose">'.$lang['lbl_more_fac'].' ('.$CACounties[$stateAlpaCode].')</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';
		
			$allHospitalsResult = $admin->searchDistinctUniversalColoumArray($tablesnamearea , 'areacity', $CACounties[$stateAlpaCode], 'order by areacity, areaname ');
			if(count($allHospitalsResult)>0){
				foreach($allHospitalsResult as $vkval){
					$data .= '<option value="'.$vkval['areacode'].'">'.ucwords(strtolower($vkval['areacity'].': '.$vkval['areaname'])).'</option>';
				}
			}

			
			$data .= '</select></div></div>';

	}
	

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>