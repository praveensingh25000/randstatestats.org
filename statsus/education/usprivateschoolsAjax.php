<?php
/******************************************
* @Modified on Feb 27, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://50.62.142.193
* Dependent on: statsus/education/usprivateschools.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = "usprivateschools";
$tablesnamearea = "usprivateschool_areas";


$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($_REQUEST['sector']) && $_REQUEST['sector']!='null'){

	$formContentData = $admin->getFormContentData($dbid);
	$lang = array();
	foreach($formContentData as $key => $detail){
		$lang[$detail['label_name']] = $detail['label_value'];
	}

	//if(isset($_REQUEST['sector']) && $_REQUEST['sector']!=''){
		//$columnsArrayCat = array('SchoolType' => $_REQUEST['sector']);
		//$dataSqlAllResult = $admin->searchLikeUniversalArray($tablesname , $columnsArrayCat, 'order by Category', "limit 2000");
	//} else {
		$dataSqlAllResult = $admin->getDistinctColumnValuesUniversal($tablesname, 'Category', "", "limit 4000");
	//}

	$dataCat = $dbDatabase->getAll($dataSqlAllResult);	

	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_please_choose_category_privatesch']))?$lang['lbl_please_choose_category_privatesch']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['Category'].'">'.$catDetail['Category'].'</option>';	
		}
		$data .= '</select></div></div>';
	}

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";
		$stateCode = $stateToCode[$stateAlpaCode];
		
		global $dbDatabase;
		$sql = "select DISTINCT(Area) from ".$tablesname." where ( SchoolType like '".trim($_REQUEST['sector'])."%' or SchoolType = '".trim($_REQUEST['sector'])."' )";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		$areastr = '';
		while($rowArea = mysql_fetch_assoc($resultCountry)){
			$areastr .= "'".$rowArea['Area']."',"; 
		}

		$areastr = substr($areastr, 0, -1);
		
		$sql = "select * from ".$tablesnamearea." where ( areast like '%".$stateAlpaCode."%' or areast = '".$stateAlpaCode."' ) and ( areacode in (".$areastr.")) order by areaname";

		$dataSqlAllResult = $dbDatabase->run_query($sql, $dbDatabase->conn);

		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = $stateAlpaCode;

		if(!empty($dataSqlAll)){

			$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_choose_school_in']))?$lang['lbl_choose_school_in']:'').' '.$lblcities.'.</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';

			foreach($dataSqlAll as $keyArea => $areaDetail){
			
				$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].' ('.$areaDetail['areacity'].', '.$areaDetail['areazip'].'; '.$areaDetail['areacnty'].' Co.)</option>';	
			}
				
			$data .= '</select></div></div>';
		}else{
			$error = 1;
			//$errorMSG = "Please choose other school type.";
		}
	}

} else {
	$error = 1;
	$errorMSG = "Please choose state first";
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>