<?php
/******************************************
* @Modified on Jan 23, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: statsny/avgwagenaicsUS.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "avgwage2US";
$tablesnamecats = "avgwage2US_cats";
$tablesnamearea = "fips";

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

$categoriesResult = $admin->getDistinctColumnValuesUniversal($tablesnamecats , 'catname', "catcode");

if(mysql_num_rows($categoriesResult)>0){
	$data .= ' <div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_industry']))?$lang['lbl_choose_industry']:'').'</span>&nbsp;&nbsp;</p><div class="table-div">';
	$data .= '<select name="category[]" class="required" multiple >';
	while($catDetail = mysql_fetch_assoc($categoriesResult)){
		
		$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';
		
	}
	$data .= '</select></div></div>';
}

$categoriesResult = $admin->getDistinctColumnValuesUniversal($tablesname , 'Cat3', "");

if(mysql_num_rows($categoriesResult)>0){
	$data .= ' <div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_industry']))?$lang['lbl_choose_category']:'').'</span>&nbsp;&nbsp;</p><div class="table-div">';
	
	while($catDetail = mysql_fetch_assoc($categoriesResult)){
		
		$data .= '<input type="checkbox" class="required" name="cat3[]" value="'.$catDetail['Cat3'].'">'.$catDetail['Cat3'].'<br/>';
		
	}
	$data .= '</div></div>';
}


if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	

//$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_cities']))?$lang['lbl_choose_cities']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "state";
		$stateCode = $stateToCode[$stateAlpaCode];
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, $stateAlpaCode);

		
		$data .= ' <div class="form-div">
                            <p>
                               <span class="choose">'.$lang['lbl_choose_area_avg'].'</span>&nbsp;&nbsp;
                            <input class="tt" type="checkbox" name="" value="" > All
                            </p>
                            <div class="table-div">';

		$data .= '<select name="areacode[]" multiple >';

		while($areaDetail = mysql_fetch_assoc($dataSqlAllResult)){
		
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].'</option>';
			
		}
			
		$data .= '</select></div></div>';
	}

	//$data .= '<div class="form-div"><p><input type="checkbox" value="'.$states.'" name="areacode[]" id=""> Include State totals</p><p><input type="checkbox" value="US" name="areacode[]" id=""> Include U.S. totals</p></div>';


} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>