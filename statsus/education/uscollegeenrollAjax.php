<?php
/******************************************
* @Modified on April 4, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live Site URL For This Page: http://statestats.rand.org/stats/education/uscollegeenroll.html
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "uscollenroll";
$tablesnamearea = "uscoll_areas";
$tablesnameatcat = "uscollenroll_cats";


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

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Cat1');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_category_enroll']))?$lang['lbl_select_category_enroll']:'').'</span>&nbsp;&nbsp;<input type="checkbox" id="checkallcat1">&nbsp;All
					
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallcat1").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery("#catcode1 option").attr("selected", "selected");
							}else{
								jQuery("#catcode1 option").removeAttr("selected");
							}
						});
					});
					</script>
					</p>
					<div class="table-div"><select name="Cat1[]" id="catcode1" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['Cat1'].'">'.$catDetail['Cat1'].'</option>';	
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
			$columnsArray['sector'] = trim($_REQUEST['sector']);
		}
			
		$dataSqlAllResult = $admin->searchLikeUniversalArray($tablesnamearea , $columnsArray, "order by areaname");
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = $stateAlpaCode;

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_one_or_more_usk_enroll']))?$lang['lbl_choose_one_or_more_usk_enroll']:'').'</span>&nbsp;&nbsp;<input type="checkbox" id="checkallcat_'.$stateAlpaCode.'">&nbsp;All
					
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallcat_'.$stateAlpaCode.'").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery("#checkstate_checkallcat_'.$stateAlpaCode.' option").attr("selected", "selected");
							}else{
								jQuery("#checkstate_checkallcat_'.$stateAlpaCode.' option").removeAttr("selected");
							}
						});
					});
					</script></p>
					<div class="table-div">';

		$data .= '<b>'.$_REQUEST['sector'].'</b><br/><select name="areacode[]" id="checkstate_checkallcat_'.$stateAlpaCode.'" class="required" multiple >';

		foreach($dataSqlAll as $keyArea => $areaDetail){
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].'</option>';	
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