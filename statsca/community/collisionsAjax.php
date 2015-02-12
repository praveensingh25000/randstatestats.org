<?php
/******************************************
* @Modified on March 05, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/community/collisions.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "trafficcollisions";
$tablesnamecd			= "trafficcollisions_cats";
$tablesnamecds			= "ca_counties";

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

//lbl_choose one or more categories
$allCategoryDetailages_res  = $admin->getTableDataUniversal($tablesnamecd);
$totalCategoryDetail = $dbDatabase->getAll($allCategoryDetailages_res);

if(!empty($totalCategoryDetail)){
	foreach($totalCategoryDetail as $key => $value)
		$categoryDetailArray[$value['catname']] = array('id' => $value['catcode'],'name' => $value['catname']);
}

//selecting one more grades
if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);

	$totalareaDetail = array();
	
	foreach($statesArray as $stateKey => $statename){	
		$totalareaDetail[$statename] = $admin->searchDistinctUniversalColoumArray($tablesname ,$column='Area', $statename ,'order by Area');
	}

	if(!empty($totalareaDetail))
		foreach($totalareaDetail as $stateKey => $cat1){
			foreach($cat1 as $stateKey => $cat2){
				if($cat2['Cat1'] != 'CITY' && $cat2['Cat1'] != 'CITY ROADWAYS'){
				$relatedCounty[$cat2['Cat1']]= $cat2['Cat1'];
				}
			}
	}	 
}

if(!empty($categoryDetailArray) && !empty($relatedCounty)) {

	//choose_one_more_causes Category Coloum
	if(!empty($categoryDetailArray)){

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_spease_choose_category']))?$lang['lbl_spease_choose_category']:'').'</span></p></div><div class="table-div">';
		
		foreach($categoryDetailArray as $Keycategory => $categoryDetail){
		$data .= '<p class="pT5 pB5 pL10"><input type="checkbox" name="Category[]" class="required" value="'.$categoryDetail['id'].'">&nbsp;&nbsp;'.$categoryDetail['name'].'</option></p>';
		}
		$data .= '</div>';
	}

	//selecting cities
	if(!empty($relatedCounty)){
		
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_one_more_cities']))?$lang['lbl_select_one_more_cities']:'').'</span></p></div>';		

		$data .= '<div class="form-div">';

		$data .= '<select name="Cat1[]" class="required" multiple >';

		foreach($relatedCounty as $keymain => $county){

			$areaArray=explode(' ',$county);
			if(!empty($areaArray) && count($areaArray) == '2') {
				$areaname=ucwords(strtolower($areaArray[0].' '.$areaArray[1]));
			} else {
				$areaname = ucwords(strtolower($county));
			}

			$data .= '<option value="'.trim($areaname).'">'.trim($areaname).'</option>';
		}
			
		$data .= '</select></div>';
				
	}
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>