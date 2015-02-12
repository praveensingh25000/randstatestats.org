<?php
/******************************************
* @Modified on April 4, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193/
* live Site URL For This Page: statsus/avgwagenaicsUS_qtr.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

$relatedCounty	= $areaCodeAllstr = $countiesArray = $totalallAreaDetail = $totalallCategoryDetail = $searchCatDetail = $totalallCategoryDetailArray = $searchCategoryDetailArray = $searchAreaDetailArray = array();

$areaCodeAll = $statesArrayCodeAll = $columnnamevaluestr = $categoryArrayCodeAll = $categoryArrayCodeAllstr = '';

global $dbDatabase;

$tablesname				= "avgwage2USqtr";
$tablesnamepatcat		= "avgwage2US_pat_cats";
$tablesnamecats			= "avgwage2US_cats";
$tablesnamecountyarea	= "states";
$tablesnamearea			= "fips";

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

//common Sector
if(isset($_REQUEST['Sector']) && $_REQUEST['Sector']!='' && isset($_REQUEST['Cat2']) && count($_POST['Cat2'])>0 && isset($_POST['us_states']) && $_POST['us_states']!='') {

	$cat2Array = $_POST['Cat2'];
	foreach($_POST['Cat2'] as $key => $cat2){
				
		$columnsArray = array('Cat1' => $_POST['Sector'], 'Cat2' => $cat2);

		if(isset($lang['lbl_ownership_sector'])){ 
			$lbl_ownership_sector =  $lang['lbl_ownership_sector'];
			$lbl_ownership_sector = str_replace("#sector#", $_POST['Sector'], $lbl_ownership_sector); 
			$lbl_ownership_sector = str_replace("#owner#", $cat2, $lbl_ownership_sector); 
		} 

		$sql = "SELECT DISTINCT(avc.catcode), avc.catname FROM `avgwage2USqtr` as av left join avgwage2US_cats as avc on av.Category = avc.catcode WHERE Cat1 = '".$_POST['Sector']."' and Cat2 = '".$cat2."' order by avc.catname";
		$resultDat = $dbDatabase->run_query($sql, $dbDatabase->conn);

		if(mysql_num_rows($resultDat)>0){
		
			$data .= '<div class="form-div">
			<p><span class="choose">'.$lbl_ownership_sector.'</span>&nbsp;&nbsp;</p>
			<div class="table-div"><select name="Category[]" class="required" multiple >';		


			while($catDetail = mysql_fetch_assoc($resultDat)){
				if($catDetail['catcode']!='')
				$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
			}

			$data .= '</select></div></div>';
			
		}

	}

	$resultSectors = $admin->getDistinctColumnValuesUniversal($tablesname , 'Cat3');
		if(mysql_num_rows($resultSectors)>0){
			$data .= '<div class="form-div">
			<p><span class="choose">'.((isset($lang['lbl_choose_categories']))?$lang['lbl_choose_categories']:'').'</span>&nbsp;&nbsp;</p>
			<div class="table-div"><div class="table-div"> ';
			while($detailRow = mysql_fetch_assoc($resultSectors)){
				$data .= '<input id="" class="checkbox_check required ownership" type="checkbox" value="'.$detailRow['Cat3'].'" name="Cat3[]"/>&nbsp;'.$detailRow['Cat3'].'<br/>';
			}
			$data .= '</div></div></div>';
		}
	
	$statesArray = explode(';',$_POST['us_states']);

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_metropolitan_area_list']))?$lang['lbl_choose_metropolitan_area_list']:'').'</span>&nbsp;&nbsp;</p></div>';


	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areastate";
		$stateCode = $stateToCode[$stateAlpaCode];	
		
		$lblcities = $stateAlpaCode;
		if(isset($lang['lbl_counties_in'])){ 
			
			$lblcities = $lang['lbl_counties_in']." ".$lblcities;
		} 

		$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.'.</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="Area[]" class="required" multiple >';
		$data .= '<option value="'.$stateCode.'000"> State totals </option>';

		$file = fopen($DOC_ROOT."counties/".$stateAlpaCode."","r") or exit("Unable to open file!");
		while(!feof($file)){
		  $strarray    = explode(' ',preg_replace( '/\s+/', ' ', trim(fgets($file))));
		  $countycode  = end($strarray);
		  $countyArray = array_pop($strarray);
		  $countyname  = implode(" ",$strarray);
     	  $data .= '<option value="'.$countycode.'">'.$countyname.'</option>';		  
		}
			
		$data .= '</select></div></div>';		
	}
	

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>