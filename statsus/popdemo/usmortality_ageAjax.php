<?php
/******************************************
* @Modified on Feb 28, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
* @Dependent : statsus/popdemo/usmortality_age.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchAgeCategoryDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

$tablesname				= "usmortal_county";
$tablesnamecat			= "usmortal_cats";
$tablesnamearea			= "usmortality_areas";
$tablesnamecountyarea	= "us_states";

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

	$states					= $_REQUEST['states'];
	$statesArray			= explode(';',$states);

	//Causes category
	$displaycolumnnamestr	= 'Category';
	$tableDetailArray_res  = $admin->getDistinctColumnValuesUniversal($tablesname , $displaycolumnnamestr, $columns = "", $limit = "");
	$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

	if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {

		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_choose_one_more_causes']))?$lang['lbl_choose_one_more_causes']:'').'</span>&nbsp;&nbsp;</p></div>';
	
		$data .= '<select name="Category[]" class="required" multiple >';

		while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {
			$categoryDetail= $admin->getRowUniversal($tablesnamecat, $column='catcode', $column=trim($tableDetail['Category']));
			$data .= '<option value="'.$categoryDetail['catcode'].'">'.$categoryDetail['catname'].'</option>';	
		}
		$data .= '</select></div>';
	}

	//Age category
	$displaycolumnnamestr	= 'Cat1';
	$tableDetailArray_res  = $admin->getDistinctColumnValuesUniversal($tablesname , $displaycolumnnamestr, $columns = "", $limit = "");
	$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

	if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {

		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_categories']))?$lang['lbl_choose_one_or_more_categories']:'').'</span>&nbsp;&nbsp;</p></div>';

		$data .= '<select name="Cat1[]" class="required" multiple >';

		while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {
			$data .= '<option value="'.$tableDetail['Cat1'].'">'.$tableDetail['Cat1'].'</option>';
		}
		$data .= '</select>';
	}

	$data .= '<div class="form-div">
			  <p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_counties']))?$lang['lbl_choose_one_or_more_counties']:'').'</span></p></div>';

	foreach($statesArray as $statesArrayCode){		

		$columnnamevaluestr1='areacode,areaname,areast';
		$totalallAreaDetailArray_res  = $admin->searchDistinctUniversalColArray($tablesnamearea ,$columnnamevaluestr1,$columnname='areast' ,$statesArrayCode, $orderby = '');
		$totalAreaDetailArray = mysql_num_rows($totalallAreaDetailArray_res);

		if(isset($totalAreaDetailArray) && $totalAreaDetailArray > 0) {

			$data .= '<div class="form-div"><p><span class="choose">'.$statesArrayCode.'</span>&nbsp;&nbsp;</p>';
				
			$data .= '<div class="table-div">';

			$data .= '<select name="Area[]" class="required" multiple >';

			while($totalAreaDetail = mysql_fetch_assoc($totalallAreaDetailArray_res)) {
				$data .= '<option value="'.trim($totalAreaDetail['areacode']).'">'.trim($totalAreaDetail['areaname']).'</option>';			
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