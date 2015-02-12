<?php
/******************************************
* @Created on Dec 16, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193
* @live Site URL For This Page: http://tx.rand.org/stats/economics/airport_us.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "airport_operations";
$tablesnameairlines = "airlines";
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


if(isset($_REQUEST['airlines']) && $_REQUEST['airlines']!=''){

	
	$states = $_REQUEST['airlines'];
	$statesArray = explode(';',$states);
	
	$statesstr = "";
	foreach($statesArray as $keySt => $airline){
		$statesstr .= "'".$airline."',";
	}

	$statesstr = substr($statesstr, 0, -1);

	$categories_res = $admin->searchDistinctUniversalColArrayIN($tablesname, 'category', 'airline_code', $statesstr, " order by trim(category) ");
	$categories	  = $dbDatabase->getAll($categories_res);

	
	if(count($categories)>0){
		$data .= ' <div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_airport_categories']))?$lang['lbl_choose_airport_categories']:'').'</span>&nbsp;&nbsp;</p><div class="table-div">';
		$data .= '<select name="category[]" class="required" multiple >';
		foreach($categories as $keyCat => $catDetail){
			
			$data .= '<option value="'.$catDetail['category'].'">'.$catDetail['category'].'</option>';
			
		}
		$data .= '</select></div></div>';
	}


	foreach($statesArray as $stateKey => $stateAlpaCode){
	
		$stateDetail = $admin->getRowUniversal($tablesnameairlines, "airline_code", $stateAlpaCode);

		$dataSqlAllResultAirports = $admin->searchDistinctUniversalColArray($tablesname , "origin_airport_id", "airline_code", $stateAlpaCode);
		
		$airportcodes = "";
		while($airportDetail = mysql_fetch_assoc($dataSqlAllResultAirports)){
			$airportcodes .= $airportDetail['origin_airport_id'].", ";
		}

		$airportcodes = substr($airportcodes, 0, -2);

		$dataSqlAllResult = $admin->searchDistinctUniversalColoumINArray($tablesnamearea ,"Code,  airport_name", "Code", $airportcodes, $orderby = ' order by trim(airport_name)');
		
		$data .= ' <div class="form-div">
                            <p>
                               <span class="choose">'.$lang['lbl_choose_airport'].' for ('.$stateDetail['name'].')</span>&nbsp;&nbsp;
                           
                            </p>
                            <div class="table-div">';

		$data .= '<select name="areacode[]" multiple class="required">';

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