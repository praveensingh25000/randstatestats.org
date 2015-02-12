<?php
/******************************************
* @Modified on Feb 28,26 June, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://tx.rand.org/stats/popdemo/popraceageUSdet.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchAgeCategoryDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

$tablesname					= "multiraceexport";
$tablesnamecat				= "multirace_cats";
$tablesnamearea				= "states";
$tablesnameages				= "multirace_ages";

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

global $db;

//selecting all cities
if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
	
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);	

	$states = implode(",", $statesArray);

	$sqlCat = "SELECT DISTINCT mc.cat_id, mc.cat_name FROM multirace_cats AS mc, multiraceexport AS mr WHERE mc.cat_id = mr.Category AND mr.State IN ( ".$states." )";

	$resultCat = mysql_query($sqlCat);
	
	if(mysql_num_rows($resultCat)>0){
		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_please_choose_race_gender_category']))?$lang['lbl_please_choose_race_gender_category']:'').'</span>&nbsp;&nbsp;</p></div>';

		$data .= '<div class="form-div">
			  <p><span class="choose">'.((isset($lang['labl_totals_gender']))?$lang['labl_totals_gender']:'').'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcategory">&nbsp;All
				
			  <script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery("#checkallcategory").change(function(){
						var checkedd =  this.checked ? true : false;
						if(checkedd){
							jQuery(".categoryall option").attr("selected", "selected");
						}else{
							jQuery(".categoryall option").removeAttr("selected");
						}
					});

					jQuery(".categoryall").change(function(){							
						jQuery("#checkallcategory").removeAttr("checked");						
					});
				});
			  </script>
			</p>';
	
		$data .= '<select name="Category[]" class="categoryall required" multiple >';
	
		while($categoryDetail = mysql_fetch_assoc($resultCat)){
			$data .= '<option value="'.trim($categoryDetail['cat_id']).'">'.trim($categoryDetail['cat_name']).'</option>';
		}
		$data .= '</select></div>';
	}

	//age detail
	$allageDetailages_res      = $admin->getDistinctColumnValuesUniversal($tablesnameages , $column = "age_code", $columns ="age_title");
	$totalallAgeCategoryDetail = $dbDatabase->getAll($allageDetailages_res);

	if(!empty($totalallAgeCategoryDetail)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['labl_please_choose_age_group']))?$lang['labl_please_choose_age_group']:'').'</span>&nbsp;&nbsp;</p>';
		
		$data .= '<select name="Cat1[]" class="required" multiple ><option value="0">Total</option>';
		
		foreach($totalallAgeCategoryDetail as $Key => $catDetail){
		$data .= '<option value="'.$catDetail['age_code'].'">'.$catDetail['age_title'].'</option>';
		}

		$data .= '</select></div>';
	}

	if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
		$states = $_REQUEST['states'];
		$statesArray = explode(';',$states);
		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['labl_please_choose_one_more_counties']))?$lang['labl_please_choose_one_more_counties']:'').'</span></p></div>';

			foreach($statesArray as $stateKey => $statecode){	
				
				$statesResult  = $admin->searchDistinctUniversalColoumOneArray($tablesname ," distinct County, Countyname ", "State", $statecode, ' order by trim(Countyname)');
				
				$lblcities = '';

				$stateDetail = $admin->getRowUniversal($tablesnamearea, 'statecode', $statecode);

				if(isset($lang['lbl_counties'])){ 
					
					$lblcities = $lang['lbl_counties'];
					$lblcities = str_replace("#CITY#", $stateDetail['statename'], $lblcities); 
				} 
				
				if(mysql_num_rows($statesResult)>0){
					$data .= '<div class="form-div">
								<p><span class="choose">'.$lblcities.'</span>&nbsp;&nbsp;</p>';

					$data .= '<select name="Area[]" class="required" multiple >';

					while($county = mysql_fetch_assoc($statesResult)){
						//if($county['id'] <= 57 && count(explode(' ',$county['name']) > 1 )) {
							//$data .= '<option value="0'.trim($county['id']).'"> State totals </option>';
						//} else {
						$data .= '<option value="'.trim($county['County']).'">'.trim($county['Countyname']).'</option>';
						//}
					}
						
					$data .= '</select></div>';
				}
			}
			
	}
}



if( $data == ""){
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>