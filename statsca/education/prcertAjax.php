<?php
/******************************************
* @Modified on March 07, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/education/prcert.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "prcert";
$tablesnamecd			= "cd_new";
$tablesnamecds			= "cds";
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

//lbl_choose one or more categories
$allCategoryDetailages_res  = $admin->getDistinctColumnValuesUniversal($tablesname , 'Category');

if(mysql_num_rows($allCategoryDetailages_res) >0 ){

	$data .= '<br/><br/><div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_categories']))?$lang['lbl_select_categories']:'').'</span>&nbsp;&nbsp;&nbsp;&nbsp;<label for="Category[]" generated="true" class="error" style="display:none;">This field is required.</label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallCategory">&nbsp;&nbsp;All
		
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallCategory").click(function(){
							jQuery(".Category").attr("checked", this.checked);
						});
						jQuery(".Category").click(function(){
							jQuery("#checkallCategory").attr("checked", false);
						});
					});
					</script>
		</p></div>';
	
	$data .= '<div><div class="table-div">';
	
	while($categoryDetail = mysql_fetch_assoc($allCategoryDetailages_res)){
	$data .= '<p class="pT5 pB5"><input type="checkbox" class="Category required" name="Category[]" value = "'.$categoryDetail['Category'].'">&nbsp;'.$categoryDetail['Category'].'</p>';
	}

	$data .= '</div></div>';

}else{
	$error = 1;
}


//Select one or more schools within any district
$allCategoryDetail_res  = $admin->getAllDataJoinColumnValuesUniversal($tablesnamecd,"distinct(cdname), cdcode", "co = '".$_REQUEST['states']."'");
$totalSchools = mysql_num_rows($allCategoryDetail_res);
$allSchool = $dbDatabase->getAll($allCategoryDetail_res);

if($totalSchools>0 && $_REQUEST['states']!='') {
		$i = 0;

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_schools_in_district']))?$lang['lbl_select_schools_in_district']:'').'</span>&nbsp;&nbsp;</p></div>';
		


		$data.="<table width='100%' cellspacing='1' cellpadding='1'>";
		
		$allSchool = array_chunk($allSchool, 3, true);
		foreach($allSchool as $keySchool => $schools){
			$data.="<tr>";
			foreach($schools as $keyschool1 => $schoolDetail){

				$data.="<td>";
				$data .= '<div class="form-div">
							<p><span class="choose">'.$schoolDetail['cdname'].'</span></p>';

			
				
				$data .= '<select name="schooldistrict[]" class="required" multiple >';
					$data .= '<option value="'.$schoolDetail['cdcode'].'0000000" >District Totals </option>';
				$districts  = $admin->searchLikeFrontUniversal($tablesnamecds , 'cdscode', $schoolDetail['cdcode'], 'order by cdsname');

				if(mysql_num_rows($districts)>0){
					while($districtDetail = mysql_fetch_assoc($districts)){
						if($districtDetail['cdscode'] != $schoolDetail['cdcode'].'0000000' && $districtDetail['cdscode'] != $schoolDetail['cdcode'])
						$data .= '<option value="'.$districtDetail['cdscode'].'" >'.$districtDetail['cdsname'].'</option>';
					}
				}

				$data.="</select>";

				$data .= '</div>';

				$data.="</td>";
			
			}
			$data.="</tr>";
		}
		$data.="</table>";
			
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>