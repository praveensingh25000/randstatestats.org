<?php
/******************************************
* @Modified on April 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/education/api1999.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "api";
$tablesnamecats			= "api_cats";
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

require_once('apiD.php');
require_once('sch.php');

if(isset($_REQUEST['cats']) && $_REQUEST['cats']!='') {
	//lbl_choose one or more categories

	$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_choose_categories']))?$lang['lbl_choose_categories']:'').'</span>&nbsp;&nbsp;</p></div>';

	$cats = explode(';', $_REQUEST['cats']);
	foreach($cats as $keyC => $catc){
		if(isset($Category) && isset($Category[$catc])){
		
			$data .= '<div><p class="pB5">'.$CatTitles[$catc].'</p><select name="Category[]" class="required" multiple="">';
			
			foreach($Category[$catc] as $subC => $subcatName){
			$data .= '<option value = "'.$subC.'">'.$subcatName.'</option>';
			}

			$data .= '</select></div>';

		}
	}
}


//Select one or more schools within any district
//$allCategoryDetail_res  = $admin->getAllDataJoinColumnValuesUniversal($tablesnamecd,"distinct(cdname), cdcode", "co = '".$_REQUEST['states']."'");
//$totalSchools = mysql_num_rows($allCategoryDetail_res);
//$allSchool = $dbDatabase->getAll($allCategoryDetail_res);

if($_REQUEST['states']!='' && isset($SchDist)) {
		$i = 0;
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_schools_in_district']))?$lang['lbl_select_schools_in_district']:'').'</span>&nbsp;&nbsp;</p>';

		$data.="<table width='100%' cellspacing='1' cellpadding='1'>";
		if($_REQUEST['states'] < 10){
			$_REQUEST['states'] = "0".$_REQUEST['states'];
		}
		$allSchool =  explode(' ',$SchDist[$_REQUEST['states']]);
		$allSchool = array_chunk($allSchool, 3, true);
		foreach($allSchool as $keySchool => $schools){
			$data.="<tr>";
			foreach($schools as $keyschool1 => $schoolcode){
				
				$schoolcode = (int)$_REQUEST['states'].$schoolcode;
				$schoolDetail = $admin->getRowUniversal($tablesnamecd, 'cdcode', $schoolcode);
				
				$data.="<td>";
				$data .= '<div class="form-div">
							<p><span class="choose">'.$schoolDetail['cdname'].'</span></p>';

			
				
				$data .= '<select name="schooldistrict[]" class="required" multiple >';
				
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
		$data.="</table></div>";
			
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>