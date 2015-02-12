<?php
/******************************************
* @Modified on March 04, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/education/lep.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "lep";
$tablesnamecats			= "lep_cats";
$tablesnamecd			= "cd";
$tablesnamecds			= "cds";

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
$allCategoryDetailages_res  = $admin->getTableDataUniversal($tablesnamecats);
$totalCategoryDetail = $dbDatabase->getAll($allCategoryDetailages_res);

if(!empty($totalCategoryDetail)){
	foreach($totalCategoryDetail as $key => $value){
		if($value['catname'] != null)
		$categoryDetailArray[$value['catname']] = array('id' => $value['catcode'], 'name' => $value['catname']);
	}
}

//lbl_choose one or more categories
$totalCategoryDetail_res  = $admin->getTableDataUniversal($tablesname,'LIMIT 2000');
$Cat1DetailDetail = $dbDatabase->getAll($totalCategoryDetail_res);

if(!empty($Cat1DetailDetail)){
	foreach($Cat1DetailDetail as $key => $value)
		$cat1DetailArray[$value['Cat1']] = array('Cat1' => $value['Cat1']);
}

//selecting one more grades
$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesnamecds,'LIMIT 2000');
$stateToNames = $dbDatabase->getAll($allCategoryDetail_res);

if(!empty($stateToNames)){
	foreach($stateToNames as $key => $value){
		if($value['cdscode'] > 59)
		$relatedCounty[$value['cdscode']] = array('id' => $value['cdscode'], 'name' => $value['cdsname']);
	}
}

if(!empty($categoryDetailArray) && !empty($relatedCounty) && !empty($cat1DetailArray)) {

	//choose_one_more_causes Category Coloum
	if(!empty($categoryDetailArray)){

		$data .= '<br /><br><br><div class="form-div pT20">
					<p><span class="choose">'.((isset($lang['lbl_choose_language']))?$lang['lbl_choose_language']:'').'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallCategory">&nbsp;&nbsp;All

					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallCategory").click(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery("#Category option").attr("selected", "selected");
							}else{
								jQuery("#Category option").removeAttr("selected");
							}
						});

						jQuery("#Category option").click(function(){
							jQuery("#checkallCategory").attr("checked", false);
						});
					});
					</script>
				</p>';
		
		$data .= '<select id="Category" name="Category[]" class="required" multiple >';
		
		foreach($categoryDetailArray as $Keycategory => $categoryDetail){
		$data .= '<option value="'.$categoryDetail['id'].'">'.$categoryDetail['name'].'</option>';
		}

		$data .= '</select></div>';
	}

	//selecting one more grades
	if(!empty($cat1DetailArray)){

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_one_more_grades']))?$lang['lbl_select_one_more_grades']:'').'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallCat1">&nbsp;&nbsp;All

					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallCat1").click(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery("#Cat1 option").attr("selected", "selected");
							}else{
								jQuery("#Cat1 option").removeAttr("selected");
							}
						});

						jQuery("#Cat1 option").click(function(){
							jQuery("#checkallCat1").attr("checked", false);
						});
					});
					</script>
					</p>';
		
		$data .= '<select id="Cat1" name="Cat1[]" class="required" multiple >';
		
		foreach($cat1DetailArray as $KeyCat1 => $cat1Detail){
		$data .= '<option value="'.$cat1Detail['Cat1'].'">'.$cat1Detail['Cat1'].'</option>';
		}

		$data .= '</select></div>';
	}

	//selecting cities
	if(!empty($relatedCounty)){
		
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_schools_district']))?$lang['lbl_select_schools_district']:'').'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcds">&nbsp;&nbsp;All

					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallcds").click(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery("#cds option").attr("selected", "selected");
							}else{
								jQuery("#cds option").removeAttr("selected");
							}
						});
						jQuery("#cds option").click(function(){
							jQuery("#checkallcds").attr("checked", false);
						});
					});
					</script>
				</p>';

		$data .= '<div class="table-div">';

		$data .= '<select name="cds[]" id="cds" class="required" multiple >';

		foreach($relatedCounty as $keycounty => $county){
			$data .= '<option value="'.trim($county['id']).'">'.trim($county['name']).'</option>';
		}
			
		$data .= '</select></div></div>';
		
	}
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>