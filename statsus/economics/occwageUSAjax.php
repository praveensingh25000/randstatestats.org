<?php
/******************************************
* @Modified on March 28, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://statestats.rand.org/stats/economics/occwageUS.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchCatDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

$tablesname				= "occwage2";
$tablesnamepatcat		= "occproj_2011_cats";
$tablesnamecat			= "occwage2_cats";
$tablesnamearea			= "occwage2_areas";
$tablesnamecountyarea	= "us_states";

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

if(isset($_REQUEST['category']) && $_REQUEST['category']!=''){

	$category		 = $_REQUEST['category'];

	$column = "catcode";	
	$categorynameDetail  = $admin->getRowUniversal($tablesnamepatcat, $column, $category);
	$display_cat_var = 'Category: <b>'.ucwords($categorynameDetail['catename']).'</b>';

	$tableDetailArray_res  = $admin->searchLikeFrontUniversal($tablesnamecat, $columnname='catcode' ,$category, $orderby = 'LIMIT 100');
	$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

	if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {

		while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {

			$searchCategoryDetailArray[$tableDetail['catname']] = array('id' => $tableDetail['catcode'] ,'name' => $tableDetail['catname']);						
		}
	}
	
}

$allCat1Detail_res  = $admin->getDistinctColumnValuesUniversal($tablesname,$column='Cat1', $columns = "", $limit = "");
$allCat1Detail = $dbDatabase->getAll($allCat1Detail_res);
foreach($allCat1Detail as $key => $value){
	$searchCatDetail[$value['Cat1']] = array('id' => $value['Cat1'], 'name' => $value['Cat1']);
}

if(!empty($searchCategoryDetailArray) && !empty($searchCatDetail)) {

	//choose_one_more_causes Category Coloum
	if(!empty($searchCategoryDetailArray)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_select_occupational_category']))?$lang['lbl_select_occupational_category']:'').'</span>&nbsp;&nbsp;</p><br><p>'.$display_cat_var.'</p></div>';
		
		$data .= '<select name="Category[]" class="required" multiple >';
		
		foreach($searchCategoryDetailArray as $Key => $categoryDetail){
		$data .= '<option value="'.$categoryDetail['id'].'">'.$categoryDetail['name'].'</option>';
		}

		$data .= '</select></div>';
	}

	//lbl_choose one or more categories/Cat1 coloum
	if(!empty($searchCatDetail)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_select_more_variables']))?$lang['lbl_select_more_variables']:'').'</span>&nbsp;&nbsp;</p></div>';
		
		$data .= '<select name="Cat1[]" class="required" multiple >';
		
		foreach($searchCatDetail as $catDetailKey => $catDetail){
		$data .= '<option>'.$catDetailKey.'</option>';
		}

		$data .= '</select></div>';
	}

	//$data .= '<div class="form-div"><p><input type="checkbox" value="0" name="Area[]"> US totals </p></div>';
	
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>