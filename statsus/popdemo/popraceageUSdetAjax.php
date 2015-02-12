<?php
/******************************************
* @Modified on Feb 28,26 June, 2013
* @Package: Rand
* @Developer: Pragati Garg,Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://statestats.rand.org/stats/popdemo/popraceageUSdet.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	array();

$tablesname					= "uspopraceage1";
$tablesnamecat				= "uspopraceage1_cats";
$tablesnamearea				= "us_states";
$tablesnamecountyarea		= "fips";

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

//categpry Detail
$allCategoryDetail_res     = $admin->getDistinctColumnValuesUniversal($tablesnamecat , $column='catname', $columns = "catcode", $limit = "");
$totalallCategoryDetail    = $dbDatabase->getAll($allCategoryDetail_res);

//age detail
$allageDetailages_res      = $admin->getDistinctColumnValuesUniversal($tablesname , $column="Cat1", $columns ="", $limit = "");
$totalallAgeCategoryDetail = $dbDatabase->getAll($allageDetailages_res);

//selecting all cities
if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
	
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);	
	
	foreach($statesArray as $stateKey => $stateCode){
		
		$tableDetailArray_res  = $admin->searchLikeUniversalEqual($tablesnamecountyarea , $column='state', $searchStr=$stateCode, $orderby = 'and areacode NOT LIKE "%000" order by areacode');
		$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

		if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {

			while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {				
				$relatedCounty[$tableDetail['state']][] = array('id' => $tableDetail['areacode'],'name' => $tableDetail['areaname']);			
			}
		}	
	}
}

//echo "<pre>";print_r($relatedCounty);echo "</pre>"; die;

if(!empty($totalallCategoryDetail) && !empty($totalallAgeCategoryDetail) && !empty($relatedCounty)) {

	$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbL_please_choose_race_gender_category']))?$lang['lbL_please_choose_race_gender_category']:'').'</span>&nbsp;&nbsp;</p></div>';


	//choose_one_more_causes Category Coloum
	if(!empty($totalallCategoryDetail)){
		
		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['Race_Gender_Hispanic_Origin']))?$lang['Race_Gender_Hispanic_Origin']:'').'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcategory">&nbsp;All
					
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
		$data .= '<option value="'.$categoryDetail['catcode'].'">'.$categoryDetail['catname'].'</option>';
		}

		$data .= '</select></div>';
	}

	//lbl_choose one or more categories/Cat1 coloum
	if(!empty($totalallAgeCategoryDetail)){

		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['Please_choose_age_group']))?$lang['Please_choose_age_group']:'').'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				
				  $data .= '<input type="checkbox" id="checkallcat1">&nbsp;All';
					
				  $data .= '<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallcat1").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".checkallcatAll option").attr("selected", "selected");
							}else{
								jQuery(".checkallcatAll option").removeAttr("selected");
							}
						});

						jQuery(".checkallcatAll").change(function(){							
							jQuery("#checkallcat1").removeAttr("checked");						
						});
					});
				  </script>';

		$data .='</p>';
		
		$data .= '<select name="cat1[]" class="checkallcatAll required" multiple >';
		
		foreach($totalallAgeCategoryDetail as $Key => $catDetail){
		$data .= '<option>'.$catDetail['Cat1'].'</option>';
		}

		$data .= '</select></div>';
	}

	//selecting cities
	
	if(!empty($relatedCounty)){

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['Please_choose_one_or_more_counties']))?$lang['Please_choose_one_or_more_counties']:'').'</span></p></div>';

		foreach($relatedCounty as $stateKey => $stateAlpaCode){	

			//$column = "statecode";	
			//$stateKeyDetail = $admin->getRowUniversal($tablesnamearea, $column, $stateKey);
			
			$lblcities = '';
			if(isset($lang['lbL_counties'])){ 
				
				$lblcities = $lang['lbL_counties'];
				$lblcities = str_replace("#CITY#", $stateKey, $lblcities); 
			} 

			$data .= '<div class="form-div">
					  <p><span class="choose">'.$lblcities.'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						
					  $data .='<input type="checkbox" id="checkallconties_'.$stateKey.'">&nbsp;All';
					
					  $data .= '<script type="text/javascript">
								 jQuery(document).ready(function(){
									jQuery("#checkallconties_'.$stateKey.'").change(function(){
										var checkedd =  this.checked ? true : false;
										if(checkedd){
											jQuery(".selectallconties_'.$stateKey.' option").attr("selected", "selected");
										}else{
											jQuery(".selectallconties_'.$stateKey.' option").removeAttr("selected");
										}
									});

									jQuery(".selectallconties_'.$stateKey.'").change(function(){							
										jQuery("#checkallconties_'.$stateKey.'").removeAttr("checked");						
									});
								});
						   </script>';
			$data .= '</p>';

			$data .= '<select name="Area[]" class="selectallconties_'.$stateKey.' required" multiple>';
			
			foreach($stateAlpaCode as $keycounty => $county){
				if($county['id'] <= 57 && count(explode(' ',$county['name']) > 1 )) {
					$data .= '<option value="0'.trim($county['id']).'"> State totals </option>';
				} else {
				$data .= '<option value="'.trim($county['id']).'">'.trim($county['name']).'</option>';
				}
			}
				
			$data .= '</select></div>';
		}
	}
	//$data .= '<div class="form-div"><p><input type="checkbox" value="99" name="Area[]"> Include U.S. totals </p></div>';
	
}else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>