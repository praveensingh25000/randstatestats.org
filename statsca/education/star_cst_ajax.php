<?php
/******************************************
* @Modified on March 07, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/education/star_cst.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "star_1";
$tablesnamecats			= "star_all_cats";
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
$allCategoryDetailages_res  = $admin->getTableDataUniversal($tablesnamecats);

if(mysql_num_rows($allCategoryDetailages_res) >0 ){

	$data .= '<br><div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_cst']))?$lang['lbl_cst']:'').'</span>&nbsp;&nbsp;</p></div>';
	
	$data .= '<div><select name="Category[]" class="required" multiple="">';
	
	while($categoryDetail = mysql_fetch_assoc($allCategoryDetailages_res)){
	$data .= '<option value = "'.$categoryDetail['catcode'].'">'.$categoryDetail['catname'].'</option>';
	}

	$data .= '</select></div>';

}


//Select one or more schools within any district
$allCategoryDetail_res  = $admin->getAllDataJoinColumnValuesUniversal($tablesnamecd,"distinct(cdname), cdcode", "co = '".$_REQUEST['states']."'");
$totalSchools = mysql_num_rows($allCategoryDetail_res);
$allSchool = $dbDatabase->getAll($allCategoryDetail_res);

if($totalSchools>0 && $_REQUEST['states']!='') {
		$i = 0;
		
		$resultCat1 = $admin->getDistinctColumnValuesUniversal($tablesname , 'Cat1');
		$data .="<table width='100%' cellspacing='1' cellpadding='1'><tr><td>";
		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_grade']))?$lang['lbl_grade']:'').'</span></p>';
		$data .= '<select name="Cat1[]" class="required" multiple >';
		if(mysql_num_rows($resultCat1)>0){
			while($cat1Detail = mysql_fetch_assoc($resultCat1)){
				$data .= '<option value="'.$cat1Detail['Cat1'].'">'.$cat1Detail['Cat1'].'</option>';
			}
		}
		$data.="</select></div></td>";
		
		$resultCat2 = $admin->getDistinctColumnValuesUniversal($tablesname , 'Cat2');
		$data .="<td>";
		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_student_type']))?$lang['lbl_student_type']:'').'</span></p>';
		$data .= '<select name="Cat2[]" class="required" multiple >';
		if(mysql_num_rows($resultCat2)>0){
			while($cat2Detail = mysql_fetch_assoc($resultCat2)){
				$data .= '<option value="'.$cat2Detail['Cat2'].'">'.$cat2Detail['Cat2'].'</option>';
			}
		}
		$data.="</select></div></td></tr>";
		$data.="</table>";


		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_schools_in_district']))?$lang['lbl_select_schools_in_district']:'').'</span>&nbsp;&nbsp;</p>
					
					<script type="text/javascript">
						jQuery(document).ready(function(){
							

							jQuery(document).ready(function(){
								jQuery(".schooldistrict").change(function(){
									jQuery("#cdsf").val(jQuery(this).val());
								});
							});
						});
						</script>
					</div>';
		


		$data.="<table width='100%' cellspacing='1' cellpadding='1'>";
		
		$allSchool = array_chunk($allSchool, 3, true);
		foreach($allSchool as $keySchool => $schools){
			$data.="<tr>";
			foreach($schools as $keyschool1 => $schoolDetail){

				$data.="<td>";
				$data .= '<div class="form-div">
							<p><span class="choose">'.$schoolDetail['cdname'].'</span></p>';

			
				
				$data .= '<select name="schooldistrict[]" class="schooldistrict" multiple >';
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
		$data.="</table><div style='display:none;'><input type='text' value='' name='cdsf' id='cdsf' class='required'></div>";
			
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>