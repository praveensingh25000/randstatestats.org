<?php
/******************************************
* @Modified on March 04, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "perpupil";
$tablesnamecd			= "cd_new";
$tablesnamecounties		= "ca_counties";

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


//Select one or more schools within any district
$allCategoryDetail_res  = $admin->getAllDataJoinColumnValuesUniversal($tablesnamecd,"distinct(cdname), cdcode", "co = '".$_REQUEST['states']."'");
$totalSchools = mysql_num_rows($allCategoryDetail_res);
$allSchool = $dbDatabase->getAll($allCategoryDetail_res);

if($totalSchools>0 && $_REQUEST['states']!='') {
		$i = 0;

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_district']))?$lang['lbl_choose_one_or_more_district']:'').'</span>&nbsp;&nbsp;<label for="cds[]" generated="true" class="error" style="display:none;">This field is required.</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkalldistrict">&nbsp;&nbsp;All
		
		<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("#checkalldistrict").click(function(){
				jQuery(".cds").attr("checked", this.checked);
			});
			jQuery(".cds").click(function(){
				jQuery("#checkalldistrict").attr("checked", false);
			});
		});
		</script>
		
		</p>';

		$data.="<table width='100%' cellspacing='1' cellpadding='1'>";
		
		$allSchool = array_chunk($allSchool, 3, true);
		foreach($allSchool as $keySchool => $schools){
			$data.="<tr>";
			foreach($schools as $keyschool1 => $schoolDetail){
				$data.="<td><input type ='checkbox' name='cds[]' class='cds required' value = '".$schoolDetail['cdcode']."'> &nbsp;".$schoolDetail['cdname']."</td>";
			}
			$data.="</tr>";
		}
		$data.="</table>";

		$data.="</div>";

		$data .= '<div class="form-div"><p><input type="checkbox" value="'.$_REQUEST['states'].'" name="allstates">&nbsp;'.((isset($lang['lbl_include_all_districts']))?$lang['lbl_include_all_districts']:'').'</p><p><input type="checkbox" value="districtavg" name="districtavg">&nbsp;'.((isset($lang['lbl_county_district_avg']))?$lang['lbl_county_district_avg']:'').'</p><p><input type="checkbox" value="statedistrictavg" name="statedistrictavg">&nbsp;'.((isset($lang['lbl_state_district_avg']))?$lang['lbl_state_district_avg']:'').'</p>';
		$data.="</div>";

	

			
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>