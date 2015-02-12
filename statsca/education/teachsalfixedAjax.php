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

$tablesname				= "teachsalfixed";
$tablesnamecats			= "teachsalfixed_cats";
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

		$allCategoryDetailages_res  = $admin->getTableDataUniversal($tablesnamecats);

		if(mysql_num_rows($allCategoryDetailages_res) >0 ){

			$data .= '<br><div class="form-div">
							<p><span class="choose">'.((isset($lang['lbl_choose_categories_teacher_fixed']))?$lang['lbl_choose_categories_teacher_fixed']:'').'</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcategory">&nbsp;&nbsp;All

							<script type="text/javascript">
							jQuery(document).ready(function(){
								jQuery("#checkallcategory").change(function(){
									var checkedd =  this.checked ? true : false;
									if(checkedd){
										jQuery("#category option").attr("selected", "selected");
									}else{
										jQuery("#category option").removeAttr("selected");
									}
								});
							});
							</script>							
							</p>';
			
			$data .= '<select name="category[]" id="category" class="required" multiple>';
			
			while($categoryDetail = mysql_fetch_assoc($allCategoryDetailages_res)){
			$data .= '<option value = "'.$categoryDetail['catcode'].'">&nbsp;'.$categoryDetail['catname'].'</option>';
			}

			$data .= '</select></div>';

		}


		$i = 0;

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_district']))?$lang['lbl_choose_one_or_more_district']:'').'</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkalldistrict">&nbsp;&nbsp;All
		
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
				$data.="<td><input class='cds required' type= 'checkbox' name='cds[]' value = '".$schoolDetail['cdcode']."'> &nbsp;".$schoolDetail['cdname']."</td>";
			}
			$data.="</tr>";
		}
		$data.="</table>";

		$data.="</div>";

			
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>