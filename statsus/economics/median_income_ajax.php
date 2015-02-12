<?php
/******************************************
* @Modified  : Sept 12, 2013
* @Package  : RAND
* @Developer: Praveen Singh
* @url		: http://statestats.rand.org/stats/economics/median_income.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname     = " medianinc";
$tablesnamearea = "medianinc_areas";
$tablesnamecat  = "medianinc_cats";

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

$data .= '<div class="form-div">
		  <p><span class="choose">'.((isset($lang['lbl_choose_metropolitan_area_list']))?$lang['lbl_choose_metropolitan_area_list']:'').'</span>&nbsp;&nbsp;</p></div>';

    if($_REQUEST['states']=='All'){
		
		foreach($stateToName as $key => $value){
		$statesArray[] = $key;
		}

	} else {	

		$states = $_REQUEST['states'];
		$statesArray = explode(';',$states);
	}

	foreach($statesArray as $stateKey => $stateAlpaCode){
		
		$column = "areastate";
		$stateCode = $stateToCode[$stateAlpaCode];
		//$dataSqlAll = $datareader->getUspopestAreasLike($stateCode);
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, $stateAlpaCode, 'order by areaname');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	

		if(!empty($dataSqlAll)) {
		
			$lblcities = $stateAlpaCode;
			if(isset($lang['lbl_cities_in'])){ 
				
				$lblcities = $lang['lbl_cities_in'];
				$lblcities = str_replace("#CINCODE#", $stateAlpaCode, $lblcities); 
			} 

			$data .= '<div class="form-div">
						<p><span class="choose">'.$stateAlpaCode.':</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallareaAll_'.$stateAlpaCode.'">&nbsp;All				  
						  <script type="text/javascript">
							jQuery(document).ready(function(){
								jQuery("#checkallareaAll_'.$stateAlpaCode.'").change(function(){
									var checkedd =  this.checked ? true : false;
									if(checkedd){
										jQuery(".areaAll_'.$stateAlpaCode.' option").attr("selected", "selected");
									}else{
										jQuery(".areaAll_'.$stateAlpaCode.' option").removeAttr("selected");
									}
								});

								jQuery(".areaAll_'.$stateAlpaCode.'").change(function(){							
									jQuery("#checkallareaAll_'.$stateAlpaCode.'").removeAttr("checked");						
								});
							});
						  </script>				  
				  
					</p>';

			$data .='<div class="table-div">';

			$data .= '<select name="areacode[]" class="areaAll_'.$stateAlpaCode.' selectboxbig required" multiple >';

			foreach($dataSqlAll as $keyArea => $areaDetail){
				$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].'&nbsp;('.$areaDetail['areacounty'].')</option>';	
			}
				
			$data .= '</select></div></div>';
		}
	}
	

	$dataSqlCat = $admin->getTableDataUniversal($tablesnamecat, 'order by catname');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	
	if(!empty($dataCat)){
		
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_one_or_more_cat']))?$lang['lbl_select_one_or_more_cat']:'').'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcatcodeAll">&nbsp;All				  
						  <script type="text/javascript">
							jQuery(document).ready(function(){
								jQuery("#checkallcatcodeAll").change(function(){
									var checkedd =  this.checked ? true : false;
									if(checkedd){
										jQuery(".catcodeAll option").attr("selected", "selected");
									}else{
										jQuery(".catcodeAll option").removeAttr("selected");
									}
								});

								jQuery(".catcodeAll").change(function(){							
									jQuery("#checkallcatcodeAll").removeAttr("checked");						
								});
							});
						  </script>
					</p>';
		
		$data .= '<div class="table-div">
				  <select name="catcode[]" class="selectboxbig catcodeAll required" multiple >';
		foreach($dataCat as $CatKey => $catDetail){
			$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
		}
		$data .= '</select></div></div>';

	}

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>