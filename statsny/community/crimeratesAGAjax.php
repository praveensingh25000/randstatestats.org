<?php
/******************************************
* @Modified on May 03, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live Site URL For This Page: http://ny.rand.org/stats/community/crimeratesAG.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

$tablesname = "crimes";

global $dbDatabase;

//$datareader = new datareader();
$admin = new admin();

$array= $searchCat1DetailArray = $searchCategoryDetailArray = array();

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

	$statesArray = explode(';',$_REQUEST['states']);

	foreach($statesArray as $statekey => $statesname){	

		$column = "catcode";
		
		$tableDetailArray_res = $admin->searchDistinctUniversalColoumOneArray($tablesname ,$displaycolumnnamestr='cat1,category', $columnname='area', $columnnamevalue=trim($statesname), $orderby = '');
		$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

		if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {

			while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {

				if($tableDetail['category']!='' && $tableDetail['category']!=NULL){
					$searchCategoryDetailArray[$tableDetail['category']]= $tableDetail['category'];
				}
				
				if($tableDetail['cat1']!='' && $tableDetail['cat1']!=NULL){
					$searchCat1DetailArray[$statesname][$tableDetail['cat1']] = $tableDetail['cat1'];
				}
			}
		}
	}
}

if(!empty($searchCat1DetailArray) && !empty($searchCategoryDetailArray)) {

	//choose Category Coloum
	if(!empty($searchCategoryDetailArray)){

		$data .= '<div class="form-div">
				  <p><span class="choose"> '.((isset($lang['lbl_Please_choose_one_or_more_categories']))?$lang['lbl_Please_choose_one_or_more_categories']:'').'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcategory">&nbsp;All
					
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallcategory").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".categoryAll option").attr("selected", "selected");
							}else{
								jQuery(".categoryAll option").removeAttr("selected");
							}
						});
						jQuery(".categoryAll").click(function(){								
							jQuery("#checkallcategory").removeAttr("checked");					
						});
					});
					</script></p>';

	    $data .= '<div class="table-div"><select name="category[]" class="categoryAll required" multiple >';

		foreach($searchCategoryDetailArray as $Keycategory => $category){			
			$data .= '<option value="'.trim($category).'">'.trim($category).' rate </option>';
		}		
			
		$data .= '</select></div></div>';		
	}

	//selecting cities
	if(!empty($searchCat1DetailArray)){
		
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_Please_choose_one_or_more_agencies']))?$lang['lbl_Please_choose_one_or_more_agencies']:'').'</span></p></div>';

		foreach($searchCat1DetailArray as $keymainstate => $countyDetail){

			$lblcities = '';
			if(isset($lang['lbl_counties'])){ 
				
				$lblcities = $lang['lbl_counties'];
				$lblcities = str_replace("#COUNTY#", $keymainstate, $lblcities); 
			} 
			
			$data .= '<div class="form-div"><p><span class="choose">'.$lblcities.'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcat1_'.$keymainstate.'">&nbsp;All
					
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallcat1_'.$keymainstate.'").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".cat1_'.$keymainstate.' option").attr("selected", "selected");
							}else{
								jQuery(".cat1_'.$keymainstate.' option").removeAttr("selected");
							}
						});
						jQuery(".cat1_'.$keymainstate.'").click(function(){								
							jQuery("#checkallcat1_'.$keymainstate.'").removeAttr("checked");					
						});
					});
					</script>
					</p>';

			$data .= '<div class="table-div">';

			$data .= '<select name="cat1[]" class="cat1_'.$keymainstate.' required" multiple >';

			foreach($countyDetail as $keymains => $county){
				$data .= '<option value="'.trim($county).'">'.trim($county).'</option>';
			}			
			$data .= '</select></div></div>';
		}
	}

	//include state total
	$data .= '<div class="form-div"><p><span class="choose"><input type="checkbox" value="New York State" name="include_state_totals">&nbsp;&nbsp;Include state totals</span></p></div>';
	
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>