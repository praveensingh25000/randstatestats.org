<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/education/leplang.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname = "leplang";

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
					<p><span class="choose">'.((isset($lang['lbl_select_program_catgories_lepg']))?$lang['lbl_select_program_catgories_lepg']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" multiple >';
					foreach($dataCat as $CatKey => $catDetail){
						$data .= '<option value="'.$catDetail['Category'].'">'.ucfirst(strtolower($catDetail['Category'])).'</option>';	
					}
		$data .= '</select></div></div>';

	}

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Cat1');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_gradde_catgories_lepg']))?$lang['lbl_select_gradde_catgories_lepg']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="cat1[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['Cat1'].'">'.$catDetail['Cat1'].'</option>';	
		}
		$data .= '</select></div></div>';

	}


	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_districts_lang']))?$lang['lbl_select_districts_lang']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		
		$dataSqlAllResult = $admin->getDistinctColumnValuesUniversal($tablesname , 'Area', 'County');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		if(count($dataSqlAll)>0){
			$data .= '<div class="form-div">
						<p><span class="choose">'.$stateAlpaCode.':</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="area[]" class="required" multiple >';

			foreach($dataSqlAll as $keyArea => $areaDetail){
				if(strtolower(trim($areaDetail['County'])) == strtolower($stateAlpaCode)){

					$areaArray=explode(' ',$areaDetail['Area']);
					if(!empty($areaArray) && count($areaArray) == '2') {
						$areaname=ucfirst(strtolower($areaArray[0])).' '.$areaArray[1];
					} else {
						$areaname = ucwords(strtolower($areaDetail['Area']));
					}

					$data .= '<option value="'.$areaDetail['Area'].'">'.$areaname.'</option>';
				}
			}
				
			$data .= '</select></div></div>';

		} else {
			$error = 1;
			$data = "";
		}
	}
}else{
	$error = 1;
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>