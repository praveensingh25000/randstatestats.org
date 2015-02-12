<?php
/******************************************
* @Modified on March 05, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://statestats.rand.org/stats/popdemo/us_births.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "us_births";
$tablesnamearea			= "us_births_areas";
$tablesnamecounty		= "states";

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
$allCategoryDetailages_res  = $admin->getDistinctColumnValuesUniversal($tablesname , $column='Category', $columns = "", $limit = "");
$totalCategoryDetail = $dbDatabase->getAll($allCategoryDetailages_res);
$totalCategoryDetailArray=array_chunk($totalCategoryDetail,5,true);

//selecting one more grades
if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);

	$relatedCounty = array();
	
	foreach($statesArray as $stateKey => $statename){	
		$relatedCounty[$statename] = $admin->searchDistinctUniversalColoumArray($tablesnamearea ,$column='state', $statename ,'order by areaname');
	}	 
}

//echo "<pre>";print_r($relatedCounty);echo "</pre>"; die;

if(!empty($totalCategoryDetailArray) && !empty($relatedCounty)) {

	//choose_one_more_causes Category Coloum
	if(!empty($totalCategoryDetailArray)){

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lb_chose_category']))?$lang['lb_chose_category']:'').'</span></p></div><div class="table-div"><table width="100%">';
		
		foreach($totalCategoryDetailArray as $Keycategory => $categoryDetail){
			$data .= '<tr >';
			foreach($categoryDetail as $Keycategory1 => $category){
			$data .= '<td><input type="checkbox" name="Category[]" class="required" value="'.$category['Category'].'">&nbsp;&nbsp;'.$category['Category'].'</td>';
			}
			$data .= '</tr>';
		}
		$data .= '</table></div>';
	}

	//selecting cities
	if(!empty($relatedCounty)){
		
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lb_chose_counties']))?$lang['lb_chose_counties']:'').'</span></p></div>';	
		
		foreach($relatedCounty as $keymain => $countyDetail){

			$column = "statecode";	
			$stateKeyDetail = $admin->getRowUniversal($tablesnamecounty, $column, $keymain);

			$lblcities = '';
			if(isset($lang['lb_chose_county_area'])){ 
				
				$lblcities = $lang['lb_chose_county_area'];
				$lblcities = str_replace("#COUNTY#", $keymain, $lblcities); 
			} 
			
			$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.'</span></p>';

			$data .= '<div class="table-div">';

			$data .= '<select name="Area['.$stateKeyDetail['statename'].'][]" class="required" multiple >';

			foreach($countyDetail as $keymain => $county){
				if($county['areacode'] != 0){
					$data .= '<option value="'.trim($county['areacode']).'">'.trim($county['areaname']).'</option>';
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