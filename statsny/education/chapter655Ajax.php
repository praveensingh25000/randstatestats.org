<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/education/chapter655.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "chapter655";
$tablesnameareas = "chapter655_areas";
$tablesnamecats = "chapter655_cats";	

$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($_REQUEST['cat'])){

	$formContentData = $admin->getFormContentData($dbid);
	$lang = array();
	foreach($formContentData as $key => $detail){
		$lang[$detail['label_name']] = $detail['label_value'];
	}
	
	$cat = $_REQUEST['cat'];
	$arrayCat = array(1 => "District Summary Statistics", 2 => "District Test Scores", 3 => "Regents Examinations");

	$catnamec = (isset($arrayCat[$cat]))?$arrayCat[$cat]:'';

	$dataSqlCat = $admin->searchLikeFrontUniversal($tablesnamecats , 'catcode', $cat, ' order by catname');	
	if(mysql_num_rows($dataSqlCat)>0){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_more_categories']))?$lang['lbl_more_categories']:'').'&nbsp;'.$catnamec.'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" multiple >';
		while($catDetail = mysql_fetch_assoc($dataSqlCat)){
			$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
		}
		$data .= '</select></div></div>';

	}


	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_more_districts']))?$lang['lbl_select_more_districts']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;

		$columnsArray = array('areacounty' => $stateAlpaCode);
		
		$dataSqlAllResult = $admin->searchLikeUniversalArray($tablesnameareas , $columnsArray, 'order by areaname');
		
		if(mysql_num_rows($dataSqlAllResult)>0){
			$alreadyArray = array();
			$data .= '<div class="form-div">
						<p><span class="choose">'.ucfirst(strtolower($stateAlpaCode)).' County:</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';

			while($areaDetail = mysql_fetch_assoc($dataSqlAllResult)){
				//if(strtolower(trim($areaDetail['areaname'])) != strtolower($stateAlpaCode))
				$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].'</option>';	
			}
			
			$data .= '</select></div></div>';
		} else {
			$error = 1;
			//$errorMSG = ((isset($lang['lbl_please_choose_other_agency']))?$lang['lbl_please_choose_other_agency']:'');
			$data = "";
		}
	}
} else {
	$error = 1;
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>