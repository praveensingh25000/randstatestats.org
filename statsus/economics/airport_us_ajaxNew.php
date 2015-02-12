<?php
/******************************************
* @Modified on Dec 6, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193
* @live Site URL For This Page: http://tx.rand.org/stats/economics/airport_us.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "airport_operations";
$tablesnamearea = "airports";
$tablesnamestate = "states";

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


if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	
	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);

	$categories_res = $admin->searchDistinctUniversalColArrayIN($tablesname, 'category', 'origin_state_code', implode(",",$statesArray), " order by trim(category) ");
	$categories	  = $dbDatabase->getAll($categories_res);

	
	if(count($categories)>0){
		$data .= ' <div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_airport_categories']))?$lang['lbl_choose_airport_categories']:'').'</span>&nbsp;&nbsp;<input type="checkbox" id="checkairportall" />&nbsp;All</p><div class="table-div"><script type="text/javascript">
							jQuery(document).ready(function(){
								jQuery("#checkairportall").change(function(){
									if(jQuery("#checkairportall").is(":checked")){
										jQuery("#catallair option").each(function(){
											jQuery(this).attr("selected", "selected");
										});
									} else {
										jQuery("#catallair option").each(function(){
											jQuery(this).removeAttr("selected");
										});
									}
								});
							});
							</script>';
		$data .= '<select name="category[]" id="catallair" class="required" multiple >';
		foreach($categories as $keyCat => $catDetail){
			
			$data .= '<option value="'.$catDetail['category'].'">'.$catDetail['category'].'</option>';
			
		}
		$data .= '</select></div></div>';
	}


	foreach($statesArray as $stateKey => $stateAlpaCode){
	
		$stateDetail = $admin->getRowUniversal($tablesnamestate, "statecode", $stateAlpaCode);

		$stateCode = $stateDetail["state"];

		$dataSqlAllResult = $admin->searchLikeUniversalEqual($tablesnamearea , "State", $stateCode, 'order by trim(airport_name)');
		
		$data .= ' <div class="form-div">
                            <p>
                               <span class="choose">'.$lang['lbl_choose_airport'].' in '.$stateDetail['statename'].'.</span>&nbsp;&nbsp;
                           
                            </p>
                            <div class="table-div">
							
							
							';

		$data .= '<select name="areacode[]" multiple class="required" >';

		while($areaDetail = mysql_fetch_assoc($dataSqlAllResult)){
			
			$data .= '<option value="'.$areaDetail['Code'].'">'.$areaDetail['airport_name'].'</option>';
			
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