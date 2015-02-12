<?php
/******************************************
* @Modified on March 05, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/community/traffic.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "traffic";
$tablesnamecd			= "traffic_cats";
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

	$totalareaDetail = $relatedCounty1 = $relatedCounty = array();
	
	foreach($statesArray as $stateKey => $statename){	
		$totalareaDetail[$statename] = $admin->searchDistinctUniversalColoumArray($tablesname ,$column='Area', $statename ,'order by Area');
	}

	if(!empty($totalareaDetail)){
		foreach($totalareaDetail as $stateKey1 => $cat1){
			foreach($cat1 as $stateKey2 => $cat2){
				$relatedCounty[$stateKey1][$cat2['Cat1']]= $cat2['Cat1'];
			}
		}
	}
}

//echo "<pre>";print_r($relatedCounty);echo "</pre>"; die;

if(!empty($categoryDetailArray) && !empty($relatedCounty)) {

	//choose_one_more_causes Category Coloum
	if(!empty($categoryDetailArray)){

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['Lbl_choose_categories']))?$lang['Lbl_choose_categories']:'').'</span></p></div><div class="table-div">';
		
		foreach($categoryDetailArray as $Keycategory => $categoryDetail){
		$data .= '<p class="pT5 pB5 pL10"><input type="checkbox" name="Category[]" class="required" value="'.$categoryDetail['id'].'">&nbsp;&nbsp;'.$categoryDetail['name'].'</option></p>';
		}
		$data .= '</div>';
	}

	//selecting cities
	if(!empty($relatedCounty)){
		
		foreach($relatedCounty as $stateKey => $countyDetail){

			$column = "areacode";	
			$stateKeyDetail = $admin->getRowUniversal($tablesnamecds, $column, $stateKey);

			$lblcities = '';
			if(isset($lang['Lbl_choose_citites_areas'])){ 
				
				$lblcities = $lang['Lbl_choose_citites_areas'];
				$lblcities = str_replace("#COUNTY#", $stateKeyDetail['areaname'], $lblcities); 
			} 
			
			$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.'.'.'</span></p>';		

			$data .= '<div class="table-div">';

			$data .= '<select name="Cat1[]" class="required" multiple >';

			foreach($countyDetail as $key => $county){
				$data .= '<option value="'.trim($county).'">'.trim($county).'</option>';
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