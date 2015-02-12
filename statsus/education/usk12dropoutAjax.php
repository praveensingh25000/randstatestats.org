<?php
/******************************************
* @Modified on Feb 28, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
* Dependent on: statsus/education/usk12dropout.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname = " usk12dropout";
$tablesnamearea = "usk12dropout_areas";

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


$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Category');
$dataCat = $dbDatabase->getAll($dataSqlCat);	
if(!empty($dataCat)){
	$data .= '<div class="form-div">
				<p><span class="choose">'.((isset($lang['lbl_select_offense']))?$lang['lbl_select_offense']:'').'</span>&nbsp;&nbsp;<input type="checkbox" id="checkallcat">&nbsp;All
					
					<script type="text/javascript">
					$(document).ready(function() {							
						jQuery("#checkallcat").click(function(){							
							if(jQuery(this).is(":checked")){
								jQuery(".categoryall").attr("disabled", "true");			
							} else {
								jQuery(".categoryall").removeAttr("disabled");
							}
						});
					});
					</script>
				</p>
				<div class="table-div">
				<table width="100%">';
				
					$arraySearchField = array_chunk($dataCat, 4);
					foreach($arraySearchField as $keySearchField => $tableRows) {
						$data .= '<tr>';
						foreach($tableRows as $keySearchField => $catDetail) {		
							$data .= '<td>';
							$data .= '<input id="category" type="checkbox" class="categoryall required" name="category[]" value="'.$catDetail['Category'].'"/>&nbsp;'.$catDetail['Category'];
							$data .= '</td>';
						} 
						$data .= '</tr>';
					 }
				$data .= '</table>';
	$data .= '</div></div>';

}

if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_educational_agency_dropout']))?$lang['lbl_select_educational_agency_dropout']:'').'</span>&nbsp;&nbsp;</p></div>';

	$states = $_REQUEST['states'];
	$statesArray = explode(';',$states);
	foreach($statesArray as $stateKey => $stateAlpaCode){
		$searchStr = $stateAlpaCode;
		$column = "areast";
		$stateCode = $stateToCode[$stateAlpaCode];
		//$dataSqlAll = $datareader->getUspopestAreasLike($stateCode);
		$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, $stateAlpaCode, 'order by areaname');
		$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
		
		$lblcities = $stateAlpaCode;

		$data .= '<div class="form-div">
					<p><span class="choose">'.$lblcities.':</span>&nbsp;&nbsp;</p>
					<div class="table-div">';

		$data .= '<input id="areacode_duplicates" class="required" type="input" name="areacode" />';
		
		$filtervaluesJsonArray = array();
		foreach($dataSqlAll as $keyArea => $areaDetail){
			//$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].' ('.$areaDetail['areacity'].', '.$areaDetail['areazip'].')</option>';	
			$filtervaluesJsonArray[] = array('id' => $areaDetail['areacode'], 'name' => $areaDetail['areaname']);
		}
		
		$filtervaluesJson = json_encode($filtervaluesJsonArray);

		$data .= '<script type="text/javascript">
		$(document).ready(function() {							
			$("#areacode_duplicates").tokenInput('.$filtervaluesJson.', { 
				preventDuplicates: true,
				onAdd: function (item) {
					jQuery("#areacode_duplicates").removeClass("required");
				}
			});										
		});							
		</script>';	
			
		$data .= '</div></div>';
	}
}else{
	$error=1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>