<?php
/******************************************
* @Modified on Feb 08, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* Dependent on: stats/community/crimes_us.php
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";


global $dbDatabase;

$tablesname		= " uscrime_returnA";
$tablesnamearea = "uscrime_agencies";
$tablesnamecat  = "uscrime_returnA_cats";

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

if(isset($_REQUEST['states']) && $_REQUEST['states']!='') {

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Cat1');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	

	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p>
						<span class="choose">'.((isset($lang['lbl_please_select_educational_agencies']))?$lang['lbl_please_select_educational_agencies']:'').' </span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcat1">&nbsp;All
						
						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery("#checkallcat1").click(function(){							
								var checkedd = $("#checkallcat1").is(":checked");
								if(checkedd){
									jQuery(".allcat1 option").attr("selected","selected");
								} else {
									jQuery(".allcat1 option").removeAttr("selected");
								}
							});
						});
						</script>
					</p>';
				
					$data .= '<div class="table-div"><select name="cat1[]" class="allcat1 required" multiple >';

					foreach($dataCat as $CatKey => $catDetail){
						$data .= '<option value="'.$catDetail['Cat1'].'">'.$catDetail['Cat1'].'</option>';	
					}
					$data .= '</select></div></div>';

	}

	$dataSqlCat = $admin->getTableDataUniversal($tablesnamecat, 'order by catname');
	$dataCat = $dbDatabase->getAll($dataSqlCat);	

	if(!empty($dataCat)){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_offense_crime']))?$lang['lbl_select_offense_crime']:'').'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcatcode">&nbsp;All
						
						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery("#checkallcatcode").click(function(){							
								var checkedd = $("#checkallcatcode").is(":checked");
								if(checkedd){
									jQuery(".allcatcode option").attr("selected","selected");
								} else {
									jQuery(".allcatcode option").removeAttr("selected");
								}
							});
						});
						</script>
				</p>';
				
				$data .= '<div class="table-div"><select name="catcode[]" class="allcatcode required" multiple>';
					foreach($dataCat as $CatKey => $catDetail){
						$data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
					}
				$data .= '</select></div></div>';

	}

	if(isset($_REQUEST['states']) && $_REQUEST['states']!=''){

		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_choose_agency_crime']))?$lang['lbl_choose_agency_crime']:'').'</span>&nbsp;</p></div>';

			$states = $_REQUEST['states'];
			$statesArray = explode(';',$states);
			foreach($statesArray as $stateKey => $stateAlpaCode){
				
				$searchStr = $stateAlpaCode;
				$column = "areacode";
				
				$dataSqlAllResult = $admin->searchLikeUniversal($tablesnamearea , $column, $stateAlpaCode, 'order by areaname');
				$dataSqlAll = $dbDatabase->getAll($dataSqlAllResult);	
				
				$lblcities = $stateAlpaCode;

				$data .= '<div class="form-div">
							<p><span class="choose">'.$lblcities.':</span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallareacode_'.$stateKey.'">&nbsp;All
						
							<script type="text/javascript">
							jQuery(document).ready(function(){
								jQuery("#checkallareacode_'.$stateKey.'").click(function(){							
									var checkedd = $("#checkallareacode_'.$stateKey.'").is(":checked");
									if(checkedd){
										jQuery(".allareacode_'.$stateKey.' option").attr("selected","selected");
									} else {
										jQuery(".allareacode_'.$stateKey.' option").removeAttr("selected");
									}
								});
							});
							</script>
						</p>';

				$data .= '<div class="table-div"><select name="areacode[]" class="allareacode_'.$stateKey.' required" multiple >';

				foreach($dataSqlAll as $keyArea => $areaDetail){
					$data .= '<option value="'.$areaDetail['areacode'].'">'.$areaDetail['areaname'].'</option>';	
				}
					
			$data .= '</select></div></div>';
		}
	}
} else {

	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>