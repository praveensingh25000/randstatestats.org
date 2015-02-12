<?php
/******************************************
* @Modified on March 04, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/education/schoolfinSAC.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

$tablesname				= "schoolfinSAC";
$tablesnamecat			= "schoolfinSAC_cats";
$tablesnamearea			= "cityfinanceareas";
$tablesnamecounty		= "ca_counties";

$admin = new admin();

$array= $totalCategoryDetailArray = array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//lbl_choose one or more categories
$allCategoryDetailages_res  = $admin->getTableDataUniversal($tablesnamecat,'LIMIT 2000');
$totalCategoryDetail = $dbDatabase->getAll($allCategoryDetailages_res);

if(!empty($totalCategoryDetail)){
	foreach($totalCategoryDetail as $key => $value)
		$totalCategoryDetailArray[$value['catname']] = array('id' => $value['catcode'],'name' => $value['catname']);
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

if(!empty($totalCategoryDetailArray) && !empty($relatedCounty)) {

	//choose_one_more_causes Category Coloum
	if(!empty($totalCategoryDetailArray)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_please_choose_categories']))?$lang['lbl_please_choose_categories']:'').'</span>&nbsp;&nbsp;</p></div>';
		
		$data .= '<select name="Category[]" class="required" multiple >';
		
		foreach($totalCategoryDetailArray as $Keycategory => $categoryDetail){
		$data .= '<option value="'.$categoryDetail['id'].'">'.$categoryDetail['name'].'</option>';
		}

		$data .= '</select></div>';
	}

	//selecting cities
	if(!empty($relatedCounty)){
		
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_please_choose_districts']))?$lang['lbl_please_choose_districts']:'').'</span></p></div>';
		foreach($relatedCounty as $keycounty => $countyDetail){

			$data .= '<div class="form-div"><div class="table-div">';

			$data .= '<p><span class="choose">'.ucfirst(strtolower($keycounty)).'</span>&nbsp;&nbsp;</p>';

			$data .= '<select name="cds[]" class="required" multiple >';

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