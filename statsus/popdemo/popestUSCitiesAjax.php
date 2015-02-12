<?php
/******************************************
* @Modified on Feb 28, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
* live Site URL For This Page: http://statestats.rand.org/stats/popdemo/popestUSdet.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname     = "uspopest";
$tablesnamearea = "uspopest_areas";

//$datareader = new datareader();
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
					<p><span class="choose">'.((isset($lang['lbl_choose_cities']))?$lang['lbl_choose_cities']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areacode";
		$stateCode = $stateToCode[$stateAlpaCode];
		//$dataSqlAll = $datareader->getUspopestAreasLike($stateCode);
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, (int)$stateCode, "order by trim(areaname)");
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = '';
		if(isset($lang['lbl_cities_in'])){ 
			
			$lblcities = $lang['lbl_cities_in'];
			$lblcities = str_replace("#CINCODE#", $stateAlpaCode, $lblcities); 
		} 

		$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.'</span>&nbsp;&nbsp;&nbsp;&nbsp;';
					
		/*$data .= '<input type="checkbox" id="checkallarea_'.$stateKey.'">&nbsp;All
		
		
		<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallarea_'.$stateKey.'").click(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".areaAll_'.$stateKey.'").removeClass("required");
							}else{
								jQuery(".areaAll_'.$stateKey.'").addClass("required");
							}
						});

						jQuery(".areaAll_'.$stateKey.'").click(function(){
							jQuery(".areaAll_'.$stateKey.'").addClass("required");
							jQuery("#checkallarea_'.$stateKey.'").removeAttr("checked");				
						});
					});
					</script>
		'	;	*/			
					
					/*<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallarea_'.$stateKey.'").click(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".areaAll_'.$stateKey.' option").attr("selected", "selected");
							}else{
								jQuery(".areaAll_'.$stateKey.' option").removeAttr("selected");
							}
						});

						jQuery(".areaAll_'.$stateKey.'").click(function(){
							jQuery("#checkallarea_'.$stateKey.'").removeAttr("checked");				
						});
					});
					</script>';*/

			$data .= '</p>';

		$data .= '<div class="table-div">';

		$data .= '<select name="Area[]" class="areaAll_'.$stateKey.' required" multiple >
					<option value="'.$stateCode.'">State totals</option>';

					foreach($dataSqlAll as $keyArea => $areaDetail){
						$explodeComma = explode(",", $areaDetail['areaname']);
						if(isset($explodeComma[1])){
							$statc = trim($explodeComma[1]);
							if(strtolower($stateAlpaCode) == strtolower($statc)){
							$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].'</option>';
							}
						}
					}
			
		$data .= '</select></div></div>';
	}

	$data .= '<div class="form-div"><p><input type="checkbox" value="00" name="Area[]" id="S02">Include U.S. totals<//p></div>';

} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>