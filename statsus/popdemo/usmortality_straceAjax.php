<?php
/******************************************
* @Modified on Mar 1, 2013
* @Package: Rand
* @Developer: Pragati garg
* @URL : http://www.ideafoundation.in
* @live URL: http://statestats.rand.org/stats/popdemo/usmortality_strace.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchAgeCategoryDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

$tablesname				= "usmortality_state";
$tablesnamecat			= "usmortal_cats";
$tablesnamearea			= "us_states";

$tablesname_main_cats			= "usmortal_main_cats";

$admin = new admin();

$allcauses = $array = $categoryDetailcat1 = $categoryDetailcat1 = array();

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

	$statesArraystr			= implode(',',$statesArray);
	$display_area_name			= '<h4>States&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp;'.$statesArraystr.'</h4>';

	foreach($statesArray as $statesArrayCode){

		$displaycolumnnamestr	= 'Category,Cat1,Cat2';
		$tableDetailArray_res  = $admin->searchDistinctUniversalColoumOneArray($tablesname ,$displaycolumnnamestr, $columnname='Area' ,$statesArrayCode, $orderby = 'LIMIT 600');
		$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

		if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {

			while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {

				$totalallCategoryDetail[$tableDetail['Category']]	=	$tableDetail['Category'];			
				if(!empty($totalallCategoryDetail)) {
					foreach($totalallCategoryDetail as $categoryCode){
						$categoryArrayCodeAll.="'".$categoryCode."'".',';
					}
					$categoryArrayCodeAllstr = substr($categoryArrayCodeAll, 0, -1);
					$columnnamevaluestr2='catcode,catname';
				}
				$searchAge1CategoryDetail[$tableDetail['Cat1']]  =	$tableDetail['Cat1'];
				$searchAge2CategoryDetail[$tableDetail['Cat2']]  =	$tableDetail['Cat2'];
			}
		}
	}
}

if(!empty($searchAge1CategoryDetail) && !empty($searchAge2CategoryDetail)) {


	$data .= '<div class="form-div"><p>'.$display_area_name.'</p></div>';

	$catDetails = $admin->getTableDataUniversal($tablesname_main_cats , ' order by causename ');
	if(mysql_num_rows($catDetails)>0){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_please_choose_more_causes']))?$lang['lbl_please_choose_more_causes']:'').'</span>&nbsp;&nbsp;</p><label for="category[]" generated="true" class="error" style="display: none;">This field is required.</label></div><div class="">';
		
		$data .= '';
		
		while($categoryDetail = mysql_fetch_assoc($catDetails)){
			$data .= '<div class="table-div"><label class=""><input name="category[]" class="" type="checkbox" value="'.$categoryDetail['causecode'].'">&nbsp;'.$categoryDetail['causename'].'</label>&nbsp;<a href="javascript:;" id="showchapters_'.$categoryDetail['causecode'].'">sub-chapters</a><a href="javascript:;" id="closeshowchapters_'.$categoryDetail['causecode'].'" style="display:none;">close</a><br/>';

			$data .= '<script type="text/javascript">
			jQuery(document).ready(function(){
				jQuery("#showchapters_'.$categoryDetail['causecode'].'").click(function(){
					jQuery("#cats_'.$categoryDetail['causecode'].'").show();
					jQuery(this).hide();
					jQuery("#closeshowchapters_'.$categoryDetail['causecode'].'").show();
				});
				jQuery("#closeshowchapters_'.$categoryDetail['causecode'].'").click(function(){
				    jQuery("#cats_'.$categoryDetail['causecode'].'").hide();
					jQuery(this).hide();
					jQuery("#showchapters_'.$categoryDetail['causecode'].'").show();
				});
			});
			
			</script>';
			
			$subcategories = $admin->searchLikeUniversalEqual($tablesnamecat , 'causecode', $categoryDetail['causecode'], ' order by catname ');
			if(mysql_num_rows($subcategories)>0){
				$data .= '<div class="pT5" style="display:none;" id="cats_'.$categoryDetail['causecode'].'">';
				$data .= '<select name="category[]" class="" multiple >';
				while($subCat = mysql_fetch_assoc($subcategories)){
				   $data .= '<option value="'.$subCat['catcode'].'">'.$subCat['catname'].'</option>';
				}
				$data .= '</select></div>';
			}
			$data .='</div>';
		}

		$data .= '</div></div>';
	}

	//lbl_choose one or more categories/Cat1 coloum
	if(!empty($searchAge1CategoryDetail)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_please_Choose_gender_Hispanic_orientation']))?$lang['lbl_please_Choose_gender_Hispanic_orientation']:'').'</span>&nbsp;&nbsp;</p></div>';
		
		$data .= '<select name="cat1[]" class="required" multiple >';
		
		foreach($searchAge1CategoryDetail as $catDetailKey => $catDetail1){
		$data .= '<option>'.$catDetail1.'</option>';
		}

		$data .= '</select></div>';
	}

	//lbl_choose one or more categories/Cat1 coloum
	if(!empty($searchAge2CategoryDetail)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_please_Choose_group']))?$lang['lbl_please_Choose_group']:'').'</span>&nbsp;&nbsp;</p></div>';
		
		$data .= '<select name="cat2[]" class="required" multiple >';
		
		foreach($searchAge2CategoryDetail as $catDetailKey => $catDetail2){
		$data .= '<option>'.$catDetail2.'</option>';
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