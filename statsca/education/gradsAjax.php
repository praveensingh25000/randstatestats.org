<?php
/******************************************
* @Modified on March 07, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/education/grads.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "grads";
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

	$data .= '<br><div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_categories']))?$lang['lbl_select_categories']:'').'</span>&nbsp;&nbsp;</p></div>';
	
	$data .= '<div>';
	
	while($categoryDetail = mysql_fetch_assoc($allCategoryDetailages_res)){
	$data .= '<input type="checkbox" class="required" name="Category[]" value = "'.$categoryDetail['Category'].'">&nbsp;'.$categoryDetail['Category'].'<br/>';
	}

	$data .= '</div>';

}


//Select one or more schools within any district
//$allCategoryDetail_res  = $admin->getAllDataJoinColumnValuesUniversal($tablesnamecd,"distinct(cdname), cdcode", "co = '".$_REQUEST['states']."'");
//$totalSchools = mysql_num_rows($allCategoryDetail_res);
//$allSchool = $dbDatabase->getAll($allCategoryDetail_res);

require_once("gradsInclude.php");

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($CDS)) {
		$i = 0;

		if($_REQUEST['states']<10){
			$_REQUEST['states'] = '0'.$_REQUEST['states'];
		}

		if(isset($CDS[$_REQUEST['states']])){

			$states = $CDS[$_REQUEST['states']];
		
			$allSchool =  $CDS[$_REQUEST['states']];

			$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_select_schools_in_district']))?$lang['lbl_select_schools_in_district']:'').'</span>&nbsp;&nbsp;</p></div>';

			$data.="<table width='100%' cellspacing='1' cellpadding='1'>";
			
			$allSchool = array_chunk($allSchool, 3, true);
			foreach($allSchool as $keySchool => $schools){
				$data.="<tr>";
				foreach($schools as $keyschool1 => $schoolDetail){

					$districtcode = (int)$_REQUEST['states'].$keyschool1;
					$districtDetail =  $admin->getRowUniversal($tablesnamecd, 'cdcode', $districtcode);

					$data.="<td>";
					$data .= '<div class="form-div">
								<p><span class="choose">'.$districtDetail['cdname'].'</span></p>';

					$data .= '<select name="schooldistrict[]" class="required" multiple >';
					$data .= '<option value="'.$districtDetail['cdcode'].'0000000" >District Totals </option>';
				
					foreach($schoolDetail as $keyddd => $schoolcode){
							$schoolcodestr = $districtcode.$keyddd;
							$data .= '<option value="'.$schoolcodestr.'">'.$schoolcode.'</option>';
					}

					$data.="</select>";

					$data .= '</div>';

					$data.="</td>";
				
				}
				$data.="</tr>";
			}
			$data.="</table>";
		}
			
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>