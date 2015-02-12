<?php
/******************************************
* @Modified on March 19, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://50.62.142.193
* @live Site URL For This Page:	http://ca.rand.org/stats/economics/occproj.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchAgeCategoryDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

$tablesname				    = "occproj_2012";
$tablesnamecatall			= "occwage2_cats_2012";
$tablesnamecat			    = "occproj_2011_cats";
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

if(isset($_REQUEST['Category']) && $_REQUEST['Category']!=''){

	$category				= $_REQUEST['Category'];
	$column					= "catcode";	
	$CategoryNameDetail     = $admin->getRowUniversal($tablesnamecat, $column, $category);
	$categoryName			= $CategoryNameDetail['catename'];
	$tableDetailArray_res   = $admin->searchLikeFrontUniversal($tablesnamecatall ,$column='catcode', $category, $orderby = '');
	$totaltableDetailArray  = mysql_num_rows($tableDetailArray_res);

	if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {
		while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {
			$searchCategoryDetailArray[] = array('id' => $tableDetail['catcode'] ,'name' => $tableDetail['catname']);
		}
	}
}

//echo "<pre>";print_r($searchCategoryDetailArray);die;

if(!empty($searchCategoryDetailArray)) {

	//choose_one_more_causes Category Coloum
	if(!empty($searchCategoryDetailArray)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_category']))?$lang['lbl_category']:'').'&nbsp;&nbsp;'.$categoryName.'.'.'</span>&nbsp;&nbsp;</p>';
		
		$data .= '<div class="table-div"><select name="Category1[]" class="required" multiple>';
		
		foreach($searchCategoryDetailArray as $Key => $categoryDetail){
		$data .= '<option value="'.$categoryDetail['id'].'">'.$categoryDetail['name'].'</option>';
		}

		$data .= '</select></div></div>';
	}

	//$data .= '<div class="form-div"><p><input type="checkbox" value="0" name="Area[]"> US totals </p></div>';
	
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>