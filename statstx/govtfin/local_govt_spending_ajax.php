<?php
/******************************************
* @Modified on Feb 12, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/govtfin/govtfin.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "local_govt_spending";
$tablesnamearea = "local_govt_areas";
$tablesnamecat = "local_govt_spending_cats";

$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$stateToCode = array(
"AL" => "01",
"AK" => "02",
"AZ" => "03",
"AR" => "04",
"CA" => "05",
"CO" => "06",
"CT" => "07",
"DE" => "08",
"DC" => "09",
"FL" => "10",
"GA" => "11",
"HI" => "12",
"ID" => "13",
"IL" => "14",
"IN" => "15",
"IA" => "16",
"KS" => "17",
"KY" => "18",
"LA" => "19",
"ME" => "20",
"MD" => "21",
"MA" => "22",
"MI" => "23",
"MN" => "24",
"MS" => "25",
"MO" => "26",
"MT" => "27",
"NE" => "28",
"NV" => "29",
"NH" => "30",
"NJ" => "31",
"NM" => "32",
"NY" => "33",
"NC" => "34",
"ND" => "35",
"OH" => "36",
"OK" => "37",
"OR" => "38",
"PA" => "39",
"RI" => "40",
"SC" => "41",
"SD" => "42",
"TN" => "43",
"TX" => "44",
"UT" => "45",
"VT" => "46",
"VA" => "47",
"WA" => "48",
"WV" => "49",
"WI" => "50",
"WY" => "51",
);


if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($_REQUEST['sector']) && $_REQUEST['sector']!='' && $_REQUEST['sector']!="null" && isset($_REQUEST['cat2']) && $_REQUEST['cat2']!='' && $_REQUEST['cat2']!="null"){

	$formContentData = $admin->getFormContentData($dbid);
	$lang = array();
	foreach($formContentData as $key => $detail){
		$lang[$detail['label_name']] = $detail['label_value'];
	}

	$sql = "SELECT ".$tablesnamecat.".* FROM ".$tablesnamecat." left join ".$tablesname." on ".$tablesname.".Category = ".$tablesnamecat.".catcode where ".$tablesname.".Cat2 = '".trim($_REQUEST['cat2'])."' limit 2000";

	$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);

	$dataCat = $dbDatabase->getAll($resultCountry);	

	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_category_govt_spending']))?$lang['lbl_select_category_govt_spending']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
		}
		$data .= '</select></div></div>';
	}

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;

		$stateCode = $stateToCode[$stateAlpaCode];
		
		$columnsArray['areastate'] = (int)$stateCode;
		if(isset($_REQUEST['sector']) && $_REQUEST['sector']!='' ){
			$columnsArray['areatype'] = trim($_REQUEST['sector']);
		}
			
		$dataSqlAllResult = $admin->searchUniversalArray($tablesnamearea , $columnsArray, "order by areaname");
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = $stateAlpaCode;

		$arrayLabel = array('1' => 'Counties', '2' => 'Cities/Townships' , '3' => 'Fair Field', '4' => 'Special Districts', '5' => 'Independent School Districts/Educ. Service Agency');

		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_counties_govt_spending']))?$lang['lbl_select_counties_govt_spending']:'').' In '.$lblcities.' ('.$arrayLabel[$_REQUEST['sector']].'):</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<select name="areacode[]" class="required" multiple >';

		foreach($dataSqlAll as $keyArea => $areaDetail){
			$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].' ('.$areaDetail['areacounty'].')</option>';	
		}
			
		$data .= '</select></div></div>';
	}
	

} else {
	$error = 1;
	$errorMSG = "Please choose state first";
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>