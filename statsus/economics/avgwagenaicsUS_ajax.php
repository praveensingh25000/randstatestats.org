<?php
/******************************************
* @Modified on Feb 26, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://50.62.142.193/
* live Site URL For This Page: statsus/avgwagenaicsUS.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

$countyDetailAll = $relatedCounty	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchCatDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

global $dbDatabase;

$tablesname				= "avgwage2US";
$tablesnamepatcat		= "avgwage2US_pat_cats";
$tablesnamecats			= "avgwage2US_cats";
$tablesnamecountyarea	= "states";
$tablesnamearea			= "fips";

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
if(isset($_REQUEST['Sector']) && $_REQUEST['Sector']!='' && isset($_REQUEST['Cat2']) && $_REQUEST['Cat2']!=''){

	$category	 = $_REQUEST['Sector'];
	$categorystr = substr($_REQUEST['Sector'],0,2);

	$categoryArray =$_REQUEST['Cat2'];

	foreach($categoryArray as $stateKey => $categoryCat1){	

		$column = "catcode";	
		$categorynameDetail  = $admin->getRowUniversal($tablesnamepatcat, $column, $category);
		$display_cat_var = 'Category: <b>'.ucwords($categorynameDetail['catename']).'</b>';

		$tableDetailArray_res  = $admin->searchLikeOneEndUniversal($tablesnamecats, $columnname='catcode' ,$categorystr, $orderby = 'LIMIT 100');
		$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

		if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {

			while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {

				$searchCategoryDetailArray[$categoryCat1][$tableDetail['catname']] = array('id' => $tableDetail['catcode'] ,'name' => $tableDetail['catname']);						
			}
		}
	}
}

//common Cat3
$allCat1Detail_res  = $admin->getDistinctColumnValuesUniversal($tablesname,$column='Cat3', $columns = "", $limit = "");
$allCat1Detail = $dbDatabase->getAll($allCat1Detail_res);
foreach($allCat1Detail as $key => $value){
	$searchCatDetail[$value['Cat3']] = array('id' => $value['Cat3'], 'name' => $value['Cat3']);
}

//common areas
//selecting one more grades
if(isset($_REQUEST['us_states']) && $_REQUEST['us_states']!=''){
	
	$statesArray = explode(';',$_REQUEST['us_states']);

	$relatedCounty = array();
	
	foreach($statesArray as $stateKey => $statename){	
		$relatedCounty[$statename] = $admin->searchDistinctUniversalColoumArray($tablesnamearea ,$column='state', $statename ,' and areacode > 59 order by areaname');
	}	 
}

//echo "<pre>";print_r($relatedCounty);echo "</pre>";

if(!empty($searchCategoryDetailArray) && !empty($searchCatDetail) && !empty($relatedCounty)) {

	//choose_one_more_causes Category Coloum
	if(!empty($searchCategoryDetailArray)){

		foreach($searchCategoryDetailArray as $Keycategory => $categoryDetailAll){

			$data .= '<div class="form-div"><p><span class="choose"> '.((isset($lang['lbl_choose_industry']))?$lang['lbl_choose_industry']:'').''.$display_cat_var.', Ownership: '.$Keycategory.'. </span></p></div><div class="table-div">';

			foreach($categoryDetailAll as $Keycat => $categoryDetail){

			$data .= '<p class="pT5 pB5 pL10"><input type="checkbox" name="Category[]" class="required" value="'.$categoryDetail['id'].'">&nbsp;&nbsp;'.$categoryDetail['name'].'</option></p>';
			
			}
			$data .= '</div>';
		}
	}

	//choose Cat3 Coloum
	if(!empty($searchCatDetail)){

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_category']))?$lang['lbl_choose_category']:'').'</span></p></div><div class="table-div">';
		
		foreach($searchCatDetail as $Keycategory => $CatDetail){
		$data .= '<p class="pT5 pB5 pL10"><input type="checkbox" name="Cat3[]" class="required" value="'.$CatDetail['id'].'">&nbsp;&nbsp;'.$CatDetail['name'].'</option></p>';
		}
		$data .= '</div>';
	}

	//selecting cities
	if(!empty($relatedCounty)){
		
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_areas']))?$lang['lbl_choose_areas']:'').'</span></p></div>';
		foreach($relatedCounty as $keymainstate => $countyDetail){

			$stateCode = $stateToCode[$keymainstate];	

			$lblcities = '';
			if(isset($lang['lbl_counties'])){ 
				
				$lblcities = $lang['lbl_counties'];
				$lblcities = str_replace("#COUNTY#", $keymainstate, $lblcities); 
			} 
			
			$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.'</span></p>';

			$data .= '<div class="table-div">';

			$data .= '<select name="Area[]" class="required" multiple >';

			$data .= '<option value="'.$stateCode.'000"> State totals </option>';

			foreach($countyDetail as $keymains => $county){
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