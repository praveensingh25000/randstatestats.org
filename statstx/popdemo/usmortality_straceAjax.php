<?php
/******************************************
* @Modified on Feb 12, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://tx.rand.org/stats/popdemo/usmortality_strace.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	array();

$tablesname				= "usmortality_state";
$tablesnamecat			= "usmortal_cats";
$tablesnamearea			= "us_states";

$admin = new admin();

$allcauses = $array = $categoryDetailcat1 = $categoryDetailcat1 = array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//selecting citites
if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){	

	$states			= $_REQUEST['states'];
	$statesArray	= explode(';',$states);
	$trimmed_array	= array_map('trim',$statesArray);

	if(!empty($trimmed_array)) {
		$statesAll = $statesStr ='';
		foreach($trimmed_array as $states){
			$statesAll.="'".$states."'".',';
		}
		$statesStr = substr($statesAll, 0, -1);

		$state_array_display=implode(',',$trimmed_array);
	}
	
	$tablestr=$tablesname.','.$tablesnamecat;	
	$columDatastr=$tablesnamecat.'.catcode,'.$tablesnamecat.'.catname';
	$searchcolumvalues=$tablesname.'.area IN ('.$statesStr.') and '.$tablesname.'.category='.$tablesnamecat.'.catcode ';
	
	if(!empty($statesArray)){
		$allcauses_res = $admin->getAllDataJoinColumnValuesUniversal($tablestr,$columDatastr,$searchcolumvalues);
		$allcauses_out = $dbDatabase->getAll($allcauses_res);

		foreach($allcauses_out as $Key => $allcausesData){
			$allcauses[$allcausesData['catcode']]	=	$allcausesData;
		}
	}
}

//lbl_choose one or more Cat1/Cat2 coloum
$allCategoryDetailages_res  = $admin->getTableDataUniversal($tablesname, 'limit 4000');

//echo "<pre>";print_r($totalallCat1Cat2Detail);echo "</pre>";
if(mysql_num_rows($allCategoryDetailages_res)>0) {
	while($categoryDetail = mysql_fetch_assoc($allCategoryDetailages_res)){
		$categoryDetailcat1[$categoryDetail['cat1']]	=	array('cat1' => $categoryDetail['cat1']);
		$categoryDetailcat2[$categoryDetail['cat2']]	=	array('cat2' => $categoryDetail['cat2']);
	}
}

//echo "<pre>";print_r($categoryDetailcat1);echo "</pre>";
//echo "<pre>";print_r($categoryDetailcat2);echo "</pre>"; die;

if(!empty($totalallCat1Cat2Detail) && !empty($allcauses) && $state_array_display!='') {

	//selecting please_choose_more_causes
	if(!empty($allcauses)){
		
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_please_choose_more_causes']))?$lang['lbl_please_choose_more_causes']:'').'</span></p>';
	
					foreach($allcauses as $allcauseskey => $allcausesname){			
					$data .= '<p class=""><input name="category[]" class="required" type="checkbox" value="'.trim($allcausesname['catcode']).'" />&nbsp;&nbsp;'.trim($allcausesname['catname']).'</p>';		
					}

		$data .= '</div>';
	}
	
	//cat1 Coloum
	if(!empty($categoryDetailcat1)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_please_Choose_gender_Hispanic orientation']))?$lang['lbl_please_Choose_gender_Hispanic orientation']:'').'</span>&nbsp;&nbsp;</p>';
		
		$data .= '<select name="cat1[]" class="required" multiple>';
		
		foreach($categoryDetailcat1 as $Key => $categoryDetail){
		$data .= '<option value="'.trim($categoryDetail['cat1']).'">'.trim($categoryDetail['cat1']).'</option>';
		}

		$data .= '</select></div>';
	}

	//cat2 Coloum
	if(!empty($categoryDetailcat2)){

		$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_please_Choose_group']))?$lang['lbl_please_Choose_group']:'').'</span>&nbsp;&nbsp;</p>';
		
		$data .= '<select name="cat2[]" class="required" multiple>';
		
		foreach($categoryDetailcat2 as $Key => $categoryDetail){
		$data .= '<option value="'.trim($categoryDetail['cat2']).'">'.trim($categoryDetail['cat2']).'</option>';
		}

		$data .= '</select></div>';
	}	
	
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>