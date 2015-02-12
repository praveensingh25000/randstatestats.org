<?php
/******************************************
* @Modified on March 07, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/education/api.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "api1";
$tablesnamecats			= "api1_cats";
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
					<p><span class="choose">'.((isset($lang['lbl_choose_categories']))?$lang['lbl_choose_categories']:'').'</span>&nbsp;&nbsp;</p></div>';
	
	$data .= '<div><select name="Category[]" class="required" multiple="">';
	
	while($categoryDetail = mysql_fetch_assoc($allCategoryDetailages_res)){
	$data .= '<option value = "'.$categoryDetail['catcode'].'">'.$categoryDetail['catname'].'</option>';
	}

	$data .= '</select></div>';

}


include("districts.php");
include("apicds.php");

if($_REQUEST['states']!='' && isset($Dist) && isset($CDS)) {
		$i = 0;
		
		if($_REQUEST['states']<=9){
			$_REQUEST['states'] = "0".$_REQUEST['states'];
		}
		
		if(isset($CDS[$_REQUEST['states']])){
			$cds = $CDS[$_REQUEST['states']];

			$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_select_schools_in_district']))?$lang['lbl_select_schools_in_district']:'').'</span>&nbsp;&nbsp;
						
						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery(document).ready(function(){
								jQuery(".schooldistrict").change(function(){
									jQuery("#cdsf").val(jQuery(this).val());
								});
							});
						});
						</script>
						</p>';

			$data.="<table width='100%' cellspacing='1' cellpadding='1'>";
			
			$allSchool = array_chunk($cds, 3, true);
			
			$districts = $Dist[$_REQUEST['states']];

			foreach($allSchool as $keySchool => $schools){
				$data.="<tr>";
				foreach($schools as $schoolcode => $schoolscds){
					
					$districtname = $districts[$schoolcode];
					
					$data.="<td>";
					$data .= '<div class="form-div">
								<p><span class="choose">'.$districtname.'</span></p>';

				
					
					$data .= '<select name="schooldistrict[]" class="schooldistrict" multiple >';
					
					foreach($schoolscds as $keycds => $schoolname){
						$cdcodecds = (int)($_REQUEST['states'].$schoolcode.$keycds);
						$data .= '<option value="'.$cdcodecds.'" >'.$schoolname.'</option>';
					}
					

					$data.="</select>";

					$data .= '</div>';

					$data.="</td>";
				
				}
				$data.="</tr>";
			}
			$data.="</table><div style='display:none;'><input type='text' value='' name='cdsf' id='cdsf' class='required'></div></div>";
		}	
			
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>