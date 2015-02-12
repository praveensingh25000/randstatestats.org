<?php
/******************************************
* @Modified on March 04, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/govtfin/cityfinance.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "cityfinance";
$tablesnamecat			= "cityfinance_cats";
$tablesnamearea			= "cityfinanceareas";
$tablesnamecounty		= "ca_counties";

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

//selecting one more grades
if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);

	$relatedCounty = array();
	
	foreach($statesArray as $stateKey => $statename){
		$columnsArray = array('areacounty' => $statename);
		if(strstr($statename,' ')){
			$stateArray = explode(' ',$statename);			
			$state = $stateArray[0];			
		}else{
			$state = $statename;
		}
		
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea ,$column='areacounty', $state ,'order by areacounty');
		$totalCategoryDetail = $dbDatabase->getAll($dataSqlAllResult);

		if(!empty($totalCategoryDetail))
		$relatedCounty[$statename] = $totalCategoryDetail; 
	}
}

if(!empty($relatedCounty)) {

	//selecting cities
	if(!empty($relatedCounty)){
		
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_one_more_cities']))?$lang['lbl_select_one_more_cities']:'').'</span></p></div>';
		foreach($relatedCounty as $keycounty => $countyDetail){

			$data .= '<div class="form-div"><div class="table-div">';

			$data .= '<p><span class="choose">'.ucfirst(strtolower($keycounty)).'</span>&nbsp;&nbsp;</p>';

			$data .= '<select name="Area[]" class="required" multiple >';

			foreach($countyDetail as $keymain => $county){
				$data .= '<option value="'.trim($county['areacode']).'">'.trim($county['areaname']).'</option>';
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