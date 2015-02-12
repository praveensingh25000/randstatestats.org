<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/education/econdis.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname		= "econdis";
$tableDistricts = "distschools";

$admin = new admin();

$array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	$formContentData = $admin->getFormContentData($dbid);
	$lang = array();
	foreach($formContentData as $key => $detail){
		$lang[$detail['label_name']] = $detail['label_value'];
	}

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Category');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_categories_district_econdis']))?$lang['lbl_select_categories_district_econdis']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['Category'].'">'.$catDetail['Category'].'</option>';	
		}
		$data .= '</select></div></div>';

	}


	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_districts_staff_econdis']))?$lang['lbl_select_districts_staff_econdis']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";

		$columnsArray = array('County' => $stateAlpaCode);
		
		//$dataSqlAllResult = $admin->searchLikeUniversalArray($tableDistricts , $columnsArray, 'order by District');
		
		//added by praveen Singh on 09-07-2013
		$dataSqlAllResult = $admin->searchLikeUniversalArray($tableDistricts , $columnsArray, " and School_Code LIKE '%00' order by District");
		
		if(mysql_num_rows($dataSqlAllResult)>0){
			$alreadyArray = array();
			$data .= '<div class="form-div">
						<p><span class="choose">'.ucfirst(strtolower($stateAlpaCode)).' County:</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';

			while($areaDetail = mysql_fetch_assoc($dataSqlAllResult)){
				if(!in_array($areaDetail['District'], $alreadyArray)){
					$data .= '<option value="'.$areaDetail['School_Code'].'">'.$areaDetail['District'].'</option>';	
				}
				$alreadyArray[$areaDetail['School_Code']] = $areaDetail['District'];
			}
			
			$data .= '</select></div></div>';
		} else {
			$error = 1;
			//$errorMSG = ((isset($lang['lbl_please_choose_other_agency']))?$lang['lbl_please_choose_other_agency']:'');
			$data = "";
		}
	}
} else {
	$error = 1;
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>