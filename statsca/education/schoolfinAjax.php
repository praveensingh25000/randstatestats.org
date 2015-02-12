<?php
/******************************************
* @Modified on Feb 11, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: statsca/education/schoolfin.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname			= "schoolfin";
$tablesnamecat		= "schoolfin_cats";
$tablesnamecounty	= "ca_counties";
$tableDistricts		= "cd_new";

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


	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesnamecat, 'catname', 'catcode');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_fund_type']))?$lang['lbl_select_fund_type']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div"><select name="category[]" class="required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
		}
		$data .= '</select></div></div>';

	}

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Cat2');	
	if(mysql_num_rows($dataSqlCat)>0){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_account_type']))?$lang['lbl_select_account_type']:'').'</span>&nbsp;&nbsp;</p>
					<div class="table-div">';
		while($catDetail = mysql_fetch_assoc($dataSqlCat)){
			$data .= '<input type="checkbox" name="Cat2[]" class="required" value="'.$catDetail['Cat2'].'" />&nbsp;'.$catDetail['Cat2'].'&nbsp;<br/>';	
		}
		$data .= '</div></div>';

	}

	

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_geographic_school_fin']))?$lang['lbl_select_geographic_school_fin']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;

		$columnsArray = array('co' => (int)$stateAlpaCode);
		
		$dataSqlAllResult = $admin->searchLikeUniversalArray($tableDistricts , $columnsArray, 'order by cdname');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		

		$countDetail = $admin->getRowUniversal($tablesnamecounty, 'areacode', $stateAlpaCode);
		
		if(count($dataSqlAll)>0){
			$data .= '<div class="form-div">
						<p><span class="choose">'.$countDetail['areaname'].' Districts.</span>&nbsp;&nbsp;</p>
						<div class="table-div">';

			$data .= '<select name="areacode[]" class="required" multiple >';

			foreach($dataSqlAll as $keyArea => $areaDetail){
				if($areaDetail['cdcode']!= (int)$stateAlpaCode)	{
					$areaname = ucwords(strtolower($areaDetail['cdname']));
					$data .= '<option value="'.$areaDetail['cdcode'].'">'.$areaname.'</option>';
				}
			}
				
			$data .= '</select></div></div>';
		} else {
			$error = 1;
			//$errorMSG = ((isset($lang['lbl_please_choose_other_agency']))?$lang['lbl_please_choose_other_agency']:'');
			$data = "";
		}
	}
}



$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>