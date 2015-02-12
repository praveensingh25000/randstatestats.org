<?php
/******************************************
* @Modified on Aug 01, 2013
* @Package: Rand
* @Developer: Praveen Singh
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedfunction	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchAgeCategoryDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

$tablesname             = "budget_outlays_by_function_subfunction";
$tablesnamefunction     = "budget_outlays_by_function";
$tablesnamesubfunction  = "budget_outlays_by_subfunction";

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

//selecting all cities
if(isset($_REQUEST['funcode']) && $_REQUEST['funcode']!=''){

	$functioncodeArray = explode(';',$_REQUEST['funcode']);
	
	foreach($functioncodeArray as $stateKey => $functioncodeone){

		$functioncode = trim($functioncodeone);		
		$tableDetailArray_res  = $admin->searchLikeUniversalEqual($tablesnamesubfunction , $column='funcode', $searchStr=$functioncode, $orderby = 'order by subfunname');
		$totaltableDetailArray = mysql_num_rows($tableDetailArray_res);

		if(isset($totaltableDetailArray) && $totaltableDetailArray > 0) {
			while($tableDetail = mysql_fetch_assoc($tableDetailArray_res)) {				
				$relatedfunction[$functioncode][] = array('id' => $tableDetail['subfuncode'],'name' => $tableDetail['subfunname']);			
			}
		}	
	}
}

if(!empty($relatedfunction)) {
	
	//selecting cities
	if(!empty($relatedfunction)){

		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_please_enter_a_subfunction']))?$lang['lbl_please_enter_a_subfunction']:'').'</span></p></div>';

		foreach($relatedfunction as $funcode => $functioncodeAll){	

			if(!empty($functioncodeAll)){

			$funDetail = $admin->getRowUniversal($tablesnamefunction, 'funcode', $funcode);

			$data .= '<div class="form-div">
				  <p><span class="choose">'.$funDetail['funname'].'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallsubfunction_'.$funcode.'">&nbsp;All				  
				  <script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallsubfunction_'.$funcode.'").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".subfunctionAll_'.$funcode.' option").attr("selected", "selected");
							}else{
								jQuery(".subfunctionAll_'.$funcode.' option").removeAttr("selected");
							}
						});

						jQuery(".subfunctionAll_'.$funcode.'").change(function(){							
							jQuery("#checkallsubfunction_'.$funcode.'").removeAttr("checked");						
						});
					});
				  </script>				  
				  </p></div>';
				  
				  $data .= '<select size="5" name="subfunction[]" class="subfunctionAll_'.$funcode.' required" multiple >';
				  foreach($functioncodeAll as $keycounty => $functionProvision){				
					$data .= '<option value="'.trim($functionProvision['id']).'">'.trim($functionProvision['name']).'</option>';
				  }				
				  $data .= '</select></div>';
			}
		}
	}
	
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>