<?php
/******************************************
* @Modified on Feb 27, 2013
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://50.62.142.193
* Dependent on: statsus/community/crimes_us.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname		= " uscrime_asr";
$tablesnamearea = "uscrime_agencies";
$tablesnamecat  = "uscrime_asr_cats";

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

	$dataSqlCat = $admin->getDistinctColumnValuesUniversal($tablesname, 'Cat1');
	if(mysql_num_rows($dataSqlCat)>0){
		$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_select_offense_type']))?$lang['lbl_select_offense_type']:'').' </span>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcat1">&nbsp;All
						
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
					while($catDetail = mysql_fetch_assoc($dataSqlCat)){
						$data .= '<option value="'.$catDetail['Cat1'].'">'.$catDetail['Cat1'].'</option>';	
					}
					$data .= '</select></div></div>';

	}

	$dataSqlCat = $admin->getTableDataUniversal($tablesnamecat, 'order by catname');
	if(mysql_num_rows($dataSqlCat)>0){
		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_select_offense_categories_asc']))?$lang['lbl_select_offense_categories_asc']:'').'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallcatcode">&nbsp;All
						
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
				  
				  while($catDetail = mysql_fetch_assoc($dataSqlCat)){
				  $data .= '<option value="'.$catDetail['catcode'].'">'.$catDetail['catname'].'</option>';	
				  }
				  $data .= '</select></div></div>';

	}

	$data .= '<div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_please_choose_agencies']))?$lang['lbl_please_choose_agencies']:'').'</span>&nbsp;&nbsp;</p></div>';

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
} else {
	$error=1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>