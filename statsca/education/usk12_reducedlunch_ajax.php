<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/education/usk12_reducedlunch.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "usk12enroll1";
$tablesnamearea = "usk12enroll_areas";
$tablesnameatcat = "usk12enroll_cats";


$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($_REQUEST['sector']) && $_REQUEST['sector']!='' && $_REQUEST['sector']!="null"){
	
	$formContentData = $admin->getFormContentData($dbid);
	$lang = array();
	foreach($formContentData as $key => $detail){
		$lang[$detail['label_name']] = $detail['label_value'];
	}


	$data .= '<div class="form-div">
			<p><span class="choose">'.((isset($lang['lbl_select_category_reduced_lunch']))?$lang['lbl_select_category_reduced_lunch']:'').'</span>&nbsp;&nbsp;</p>
			<div class="table-div"><select name="catcode[]" class="required" multiple >
			<option value="100"> Free lunch eligible students
			</option><option value="101"> Reduced-price lunch eligible students
			</option><option value="102"> Total free- and reduced-price lunch eligible students
			</option><option value="348"> Total students (sum of all students, all grades)
			</option>
			</select></div></div>';


	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";
		$stateCode = $stateToCode[$stateAlpaCode];
		
		$columnsArray['areast'] = $stateAlpaCode;
		if(isset($_REQUEST['sector']) && $_REQUEST['sector']!='' ){
			$columnsArray['areatype'] = trim($_REQUEST['sector']);
		}
			
		$dataSqlAllResult = $admin->searchLikeUniversalArray($tablesnamearea , $columnsArray, "order by areaname");
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = $stateAlpaCode;

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_reduced_lunch']))?$lang['lbl_choose_one_or_more_reduced_lunch']:'').' In '.$_REQUEST['sector'].' '.$lblcities.':</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="areacode[]" class="required" multiple >';

		foreach($dataSqlAll as $keyArea => $areaDetail){
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].' ('.$areaDetail['areacity'].')</option>';	
		}
			
		$data .= '</select></div></div>';
	}

} else {
	$error = 1;
	$errorMSG = "Please choose state & school type first";
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>