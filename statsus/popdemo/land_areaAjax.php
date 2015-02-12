<?php
/******************************************
* @Modified on March 05, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://statestats.rand.org/stats/popdemo/us_births.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();
$tablesname		=   "state_and_county_land_area";

$admin			=   new admin();
$user			=   new user();

$relatedCounty = $relatedCountyArray = $array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//selecting one more grades
if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $statename){		
		$relatedCounty_res = $admin->searchDistinctUniversalColoumOneArray($tablesname, $displaycolumnnamestr='DISTINCT(County)' ,$columnname='State', $columnnamevalue=$statename ,'order by County');
		$relatedCounty[$statename] = $dbDatabase->getAll($relatedCounty_res);
	}
}

//echo "<pre>";print_r($totalCategoryDetailArray);echo "</pre>";
//echo "<pre>";print_r($relatedCounty);echo "</pre>"; die;

if(!empty($relatedCounty)) {

	//choose county
	if(!empty($relatedCounty)){

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lb_choose_county']))?$lang['lb_choose_county']:'').'</span></p></div>';
		
		foreach($relatedCounty as $Keycounty => $countyDetail){
			
			$size       = 2;
			$statename  = $Keycounty;
			$countyname = str_replace(' ','',$Keycounty);

			$data .= '<div class="form-div">
					<p><span class="choose">'.$Keycounty.'</span>&nbsp;&nbsp;&nbsp;';
					
			if(isset($countyDetail) && count($countyDetail) > 2){
				
				$size  = 10;
				$data .='<input type="checkbox" id="checkallcategory'.$countyname.'">&nbsp;All';			
				$data .='<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallcategory'.$countyname.'").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".categoryall'.$countyname.' option").attr("selected", "selected");
							}else{
								jQuery(".categoryall'.$countyname.' option").removeAttr("selected");
							}
						});

						jQuery(".categoryall'.$countyname.'").change(function(){							
							jQuery("#checkallcategory'.$countyname.'").removeAttr("checked");						
						});
					});
				  </script>';
			}

			$data .='</p>';

			$data .= '<div class="table-div">';
				
			$data .= '<select style="width:400px;" size="'.$size.'" name="County[]" class="categoryall'.$countyname.' required" multiple >';			
			foreach($countyDetail as $keymain => $county){
				$data .= '<option value="'.$county['County'].'">'.$county['County'].'</option>';
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