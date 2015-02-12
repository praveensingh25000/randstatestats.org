<?php
/******************************************
* @Modified on Feb 28, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
* Dependent on: statsus/education/usk12enroll.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "usk12enroll2";
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

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesnameatcat, 'catname', 'catcode');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_category_usk_enroll']))?$lang['lbl_select_category_usk_enroll']:'').'</span>&nbsp;&nbsp;<input type="checkbox" id="checkallcat">&nbsp;All
					
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallcat").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery("#catcode option").attr("selected", "selected");
							}else{
								jQuery("#catcode option").removeAttr("selected");
							}
						});
					});
					</script>
					</p>
					<div class="table-div"><select name="catcode[]" id="catcode" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
		}
		$data .= '</select></div></div>';

	}

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
					<p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_usk_enroll']))?$lang['lbl_choose_one_or_more_usk_enroll']:'').' in '.$_REQUEST['sector'].' '.$lblcities.'.</span>&nbsp;&nbsp;<input type="checkbox" id="checkallcat_'.$stateAlpaCode.'">&nbsp;All
					
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallcat_'.$stateAlpaCode.'").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery("#checkstate_checkallcat_'.$stateAlpaCode.'").removeClass("required");
							}else{
								jQuery("#checkstate_checkallcat_'.$stateAlpaCode.'").addClass("required");
							}
						});
					});
					</script></p>
					<div class="table-div">';

		$data .= '<select name="areacode[]" id="checkstate_checkallcat_'.$stateAlpaCode.'" class="required" multiple >';

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