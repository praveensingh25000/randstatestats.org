<?php
/******************************************
* @Modified on March 05, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "teachsalstep";
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

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_district']))?$lang['lbl_choose_one_or_more_district']:'').'</span></p>';
		$data.="<table width='100%' cellspacing='1' cellpadding='1'>";
		
		$allSchool = array_chunk($allSchool, 3, true);
		foreach($allSchool as $keySchool => $schools){
			$data.="<tr>";
			foreach($schools as $keyschool1 => $schoolDetail){
				$data.="<td><input class='required' type = 'checkbox' name = 'cds[]' value = '".$schoolDetail['cdcode']."'> &nbsp;".$schoolDetail['cdname']."</td>";
			}
			$data.="</tr>";
		}
		$data.="</table>";

		$data.="</div>";

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_select_one_or_more_category_teachstep']))?$lang['lbl_select_one_or_more_category_teachstep']:'').'</span></p>';
		$data.="<table width='100%' cellspacing='1' cellpadding='1'>";

		$data.="<tr>";
		
		$resultCategories = $admin->getDistinctColumnValuesUniversal($tablesname , 'Category');
		if(mysql_num_rows($resultCategories)>0){
			$data.="<td width='30%'><b>".((isset($lang['lbl_level']))?$lang['lbl_level']:'')."</b><br>(Required)<br/><select class='required' size='6' multiple='' name='Category[]' >";
			$iCheck = 0;
			while($categoryDetail = mysql_fetch_assoc($resultCategories)){
			
				$data .= '<option value="'.$categoryDetail['Category'].'">'.$categoryDetail['Category'].'</option>';
				$iCheck++;
			}
			$data.="</select></td>";
		}

		$resultLevel = $admin->getDistinctColumnValuesUniversal($tablesname , 'Cat1');
		if(mysql_num_rows($resultLevel)>0){
			$data.="<td width='10%'><b>".((isset($lang['lbl_year_of_service']))?$lang['lbl_year_of_service']:'')."</b><br>(Optional)<br/><select size='6' multiple='' name='Cat1[]' id='S01'>";
			$iCheck = 0;
			while($levelDetail = mysql_fetch_assoc($resultLevel)){
				if($levelDetail['Cat1']!='NA')
				$data .= '<option value="'.$levelDetail['Cat1'].'">'.$levelDetail['Cat1'].'</option>';
			}
			$data.="</select></td>";
		}

		$resultLevel = $admin->getDistinctColumnValuesUniversal($tablesname , 'Cat2');
		if(mysql_num_rows($resultLevel)>0){
			$data.='<td width="30%"><b>'.((isset($lang['lbl_salary_range']))?$lang['lbl_salary_range']:'').'</b><br>(Optional)<br/><select size="6" multiple="" name="Cat2[]" id="YR">';
			$iCheck = 0;
			while($levelDetail = mysql_fetch_assoc($resultLevel)){
				if($levelDetail['Cat2']!='NA')
				$data .= '<option value="'.$levelDetail['Cat2'].'">'.$levelDetail['Cat2'].'</option>';
			}
			$data.="</select></td>";
		}
		$data.="</tr>";


		$data.="</table>";


		$data.="</div>";
		

			
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>