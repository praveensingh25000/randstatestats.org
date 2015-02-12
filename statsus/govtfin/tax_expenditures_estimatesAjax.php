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

$relatedfunction	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchAgeCategoryDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

$tablesname				= "tax_expenditures_estimates";
$tablesnamefunction		= "tax_expenditures_estimates_functions";
$tablesnameprovision	= "tax_expenditures_estimates_provisions";

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

//category Detail
$allCategoryDetail_res     = $admin->getDistinctColumnValuesUniversal($tablesname , $column='Category', $columns = "", $limit = "");
$totalallCategoryDetail    = $dbDatabase->getAll($allCategoryDetail_res);

//selecting all cities
if(isset($_REQUEST['functioncode']) && $_REQUEST['functioncode']!=''){
	
	$functioncodeArray[] = trim($_REQUEST['functioncode']);
		
	foreach($functioncodeArray as $stateKey => $functioncode){
		
		$tableDetailArray_res  = $admin->searchLikeUniversalEqual($tablesnameprovision , $column='procode', $searchStr=$functioncode, $orderby = 'order by proname');
		$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

		if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {
			while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {				
				$relatedfunction[$functioncode][] = array('id' => $tableDetail['id'],'name' => $tableDetail['proname']);			
			}
		}	
	}
}

if(!empty($totalallCategoryDetail) && !empty($relatedfunction)) {
	
	//selecting cities
	if(!empty($relatedfunction)){

		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_Please_choose_provision']))?$lang['lbl_Please_choose_provision']:'').'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallprovision">&nbsp;All
				  
				  <script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallprovision").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".provisionAll option").attr("selected", "selected");
							}else{
								jQuery(".provisionAll option").removeAttr("selected");
							}
						});

						jQuery(".provisionAll").change(function(){							
							jQuery("#checkallprovision").removeAttr("checked");						
						});
					});
				  </script>
				  
				  </p></div>';

		foreach($relatedfunction as $stateKey => $functioncodeAll){	
			$data .= '<select size="5" name="Provision[]" class="provisionAll required" multiple >';
			foreach($functioncodeAll as $keycounty => $functionProvision){				
				$data .= '<option value="'.trim($functionProvision['id']).'">'.trim($functionProvision['name']).'</option>';
			}				
			$data .= '</select></div>';
		}
	}
		
	//choose_one_more_causes Category Coloum
	if(!empty($totalallCategoryDetail)){
		
		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_Please_choose_Category']))?$lang['lbl_Please_choose_Category']:'').'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcategory">&nbsp;All
					
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
		
		foreach($totalallCategoryDetail as $Key => $categoryDetail){
			$data .= '<option value="'.trim($categoryDetail['Category']).'">'.trim($categoryDetail['Category']).'</option>';
		}

		$data .= '</select></div>';
	}
	
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>