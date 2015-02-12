<?php
/******************************************
* @Modified on Jan 23, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/popestUSdet.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "airportsUS";
$tablesnamearea = "airportsUS_areas";

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

$categories = $admin->getTableColumnUniqueValues($tablesname, 'Category');

if(count($categories)>0){
	$data .= ' <div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_airport_categories']))?$lang['lbl_choose_airport_categories']:'').'</span>&nbsp;&nbsp;</p><div class="table-div">';
	$data .= '<select name="category[]" class="required" multiple >';
	foreach($categories as $keyCat => $catDetail){
		
		$data .= '<option value="'.$catDetail['Category'].'">'.$catDetail['Category'].'</option>';
		
	}
	$data .= '</select></div></div>';
}

if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	

//$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_choose_cities']))?$lang['lbl_choose_cities']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areacityst ";
		$stateCode = $stateToCode[$stateAlpaCode];
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, $stateAlpaCode, 'Order by areacityst');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$data .= ' <div class="form-div">
                            <p>
                               <span class="choose">'.$lang['lbl_choose_airport'].'</span>&nbsp;&nbsp;
                           
                            </p>
                            <div class="table-div">';

		$data .= '<select name="areacode[]" multiple class="required">';

		foreach($dataSqlAll as $keyArea => $areaDetail){
			$explodeComma = explode(",", $areaDetail['areacityst']);
			if(isset($explodeComma[1])){
				$statc = trim($explodeComma[1]);
				if(strtolower($stateAlpaCode) == strtolower($statc)){
					$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areacityst'].' - '.$areaDetail['areaname'].'</option>';
				}
			}
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