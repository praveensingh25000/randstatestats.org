<?php
/******************************************
* @Modified on March 07, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/education/private.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "private";
$tablesnamecats			= "private_cats";
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


//$allCategoryDetail_res  = $admin->getAllDataJoinColumnValuesUniversal($tablesnamecd,"distinct(cdname), cdcode", "co = '".$_REQUEST['states']."'");
//$totalSchools = mysql_num_rows($allCategoryDetail_res);
//$allSchool = $dbDatabase->getAll($allCategoryDetail_res);

require_once("privateSchools.php");

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($PrivateSchools)) {

		if($_REQUEST['states']<10){
			$_REQUEST['states'] = '0'.$_REQUEST['states'];
		}

		if(isset($PrivateSchools[$_REQUEST['states']])){
			$states = $PrivateSchools[$_REQUEST['states']];
		
			$allSchool =  $PrivateSchools[$_REQUEST['states']];

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

					$schoolcodes = explode(' ',$schoolDetail);
					$schoolcodestr = '';
					foreach($schoolcodes as $key => $schoolcode){
						$schoolcodestr .= $districtcode.$schoolcode.',';
					}
					$schoolcodestr = substr($schoolcodestr, 0, -1);
					
					$data .= '<select name="schooldistrict[]" class="" multiple >';
						$data .= '<option value="'.$districtDetail['cdcode'].'0000000" >District Totals </option>';

					$districts  = $admin->searchDistinctUniversalColoumINArray($tablesnamecds ,'*', 'cdscode', $schoolcodestr, 'order by cdsname');

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
		}
			
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>