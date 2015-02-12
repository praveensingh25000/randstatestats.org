<?php
/******************************************
* @Created  : Sept 16, 2013
* @Package  : RAND
* @Developer: Praveen Singh
* @url		: http://randstatestats.org
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

$admin = new admin();
$user  = new user();

$tablesname     = "children_in_poverty_2003_2011";

$filtervaluesStateJsonArray = $levelArrayDetailMain = $relatedprogram = $relateddepartment = $array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = $StateContent ='';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//selecting one more area
if(isset($_REQUEST['Category']) && $_REQUEST['Category']!=''){
	
	//selecting States	
	$states_res=$admin->getDistinctColumnValuesUniversal($tablesname,$column='State', $columns = "",$limit = "");
	$statesArray = $dbDatabase->getAll($states_res);
	foreach($statesArray as $key => $value){
	 $StateContent .= "<p>".$value['State']."</p>";
	 $filtervaluesStateJsonArray[] = array('id' => trim($value['State']), 'name' => trim($value['State']));
	}

	if(!empty($filtervaluesStateJsonArray)) {

		$filtervaluesareaJson = json_encode($filtervaluesStateJsonArray);
		
		$data .='<script type="text/javascript" src="'.URL_SITE.'/libraries/jquery.tokeninput.js"></script>
				 <link rel="stylesheet" href="'.URL_SITE.'/css/token-input.css" type="text/css" />';
				
		$data .='<div class="form-div">';
		
		$data .='<p>
				   <span class="choose">'.$lang["lbl_please_enter_area"].'&nbsp;&nbsp;&nbsp;<input id="search_criteria_area_all" type="checkbox" value="All" name="allow_all" /></span>
				   
				   <script type="text/javascript">
					$(document).ready(function() {							
						jQuery("#search_criteria_area_all").click(function(){					
							if(jQuery(this).is(":checked")){								
								jQuery("#areasDataLoad").html("");
								var State = jQuery("#search_criteria_area_all").val();
								jQuery("#search_criteria_duplicates_area").tokenInput("toggleDisabled");
								loader_show();
								jQuery("#timePeriod").hide();
								jQuery("#submitButtons").hide();
								
								jQuery.ajax({
									url: URL_SITE+"/statsus/incomes/children_povertyAjax.php",
									type: "post",
									data: "dbid='.$dbid.'&State="+State,
									
									success: function(dataresult){
										loader_unshow();
										var obj = jQuery.parseJSON(dataresult);

										if(obj.error == "0"){
											jQuery("#areasDataLoad").html(obj.data).show();
											jQuery("#timePeriod").show();
											jQuery("#submitButtons").show();
										}else {
											jQuery("#areasDataLoad").html("");
											jQuery("#timePeriod").hide();
											jQuery("#submitButtons").hide();
										}
									}
								});
									
							} else {
								jQuery("#search_criteria_duplicates_area").tokenInput("toggleDisabled");
								jQuery("#areasDataLoad").html("");
								jQuery("#timePeriod").hide();
								jQuery("#submitButtons").hide();
							}
						});
					});
					</script>
				</p>';

		$data .='<div class="left">
						<input class="search_criteria_class_area required" type="text" id="search_criteria_duplicates_area" name="State" />
				</div>

				<div class="left pL10">
						<a id="viewallarea" href="javascript:;">See a list</a>
						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery("#viewallarea").click(function(){
								window.open("'.URL_SITE.'/allValues.php?table='.$tablesname.'&column=State", "popUpWindow","height=400, width=400, left=300, top=100, resizable=yes, scrollbars=yes, toolbar=yes, menubar=no, location=no, directories=no, status=yes");
							});
						});
						</script>
					</div>						
					<div class="clear"></div>

					<script type="text/javascript">
					$(document).ready(function() {							
						
						$("#search_criteria_duplicates_area").tokenInput('.$filtervaluesareaJson.', {  preventDuplicates: true,tokenLimit:5,
							
							onAdd: function (item) {
								var State = jQuery("#search_criteria_duplicates_area").val();
								loader_show();
								jQuery("#areasDataLoad").hide();
								jQuery("#timePeriod").hide();
								jQuery("#submitButtons").hide();
								jQuery.ajax({
									url: URL_SITE+"/statsus/incomes/children_povertyAjax.php",
									type: "post",
									data: "dbid='.$dbid.'&State="+State,
									success: function(dataresult){
										loader_unshow();
										var obj = jQuery.parseJSON(dataresult);

										if(obj.error == "0"){
											jQuery("#areasDataLoad").html(obj.data).show();
											jQuery("#timePeriod").show();
											jQuery("#submitButtons").show();
										}else {
											jQuery("#areasDataLoad").html("");
											jQuery("#timePeriod").hide();
											jQuery("#submitButtons").hide();
										}
									}
								});
							},
							onDelete: function (item) {
								var State = jQuery("#search_criteria_duplicates_area").val();
								loader_show();
								jQuery("#areasDataLoad").hide();
								jQuery("#timePeriod").hide();
								jQuery("#submitButtons").hide();
								jQuery.ajax({
									url: URL_SITE+"/statsus/incomes/children_povertyAjax.php",
									type: "post",
									data: "dbid='.$dbid.'&State="+State,
									success: function(dataresult){
										loader_unshow();
										var obj = jQuery.parseJSON(dataresult);

										if(obj.error == "0"){
											jQuery("#areasDataLoad").html(obj.data).show();
											jQuery("#timePeriod").show();
											jQuery("#submitButtons").show();
										}else {
											jQuery("#areasDataLoad").html("");
											jQuery("#timePeriod").hide();
											jQuery("#submitButtons").hide();
										}
									}
								});
							}
						
						});
			
					});
				
				</script>';


	$data .='</div>';		
		
	} else {
		$error = 1;
	}

} else if(isset($_REQUEST['State']) && $_REQUEST['State']!=''){

	$StateArray = array();

	//selecting related counties
	if(isset($_REQUEST['State']) && $_REQUEST['State']=='All'){

		//selecting States	
		$states_res=$admin->getDistinctColumnValuesUniversal($tablesname,$columnname='State', $columns = "",$limit = "");;
		$statesArray = $dbDatabase->getAll($states_res);
		foreach($statesArray as $key => $value){
			$StateArray[] = trim($value['State']);;
		}
	} else {
			$statesArray = $_REQUEST['State'];
			$StateArray = explode(';',$statesArray);
	}

	foreach($StateArray as $StateKey => $State){
			
		$relatedCounty_res = $admin->searchDistinctUniversalColoumOneArray($tablesname, $displaycolumnnamestr='DISTINCT(County)' ,$columnname='State', $columnnamevalue=$State ,'');
		$relatedCounty[$State] = $dbDatabase->getAll($relatedCounty_res);			
	}
	

	//selecting cities
	if(!empty($relatedCounty)){

		$data .= '<div class="form-div">
				  <p><span class="choose">'.((isset($lang['lbl_please_select_county']))?$lang['lbl_please_select_county']:'').'</span></p></div>';

		foreach($relatedCounty as $countycode => $relatedCountyAll){	

			if(!empty($relatedCountyAll)){

			$countyName   = $countycode;
			$countycodeId = str_replace(' ','',$countycode);

			$data .= '<div class="form-div">
				  <p><span class="choose">'.$countyName.'</span>&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallareaAll_'.$countycodeId.'">&nbsp;All				  
				  <script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallareaAll_'.$countycodeId.'").change(function(){
							var checkedd =  this.checked ? true : false;
							if(checkedd){
								jQuery(".areaAll_'.$countycodeId.' option").attr("selected", "selected");
							}else{
								jQuery(".areaAll_'.$countycodeId.' option").removeAttr("selected");
							}
						});

						jQuery(".areaAll_'.$countycodeId.'").change(function(){							
							jQuery("#checkallareaAll_'.$countycodeId.'").removeAttr("checked");						
						});
					});
				  </script>				  
				  </p></div>';
				  
				  $data .= '<select size="5" name="County[]" class="selectboxsmall areaAll_'.$countycodeId.' required" multiple >';
				  foreach($relatedCountyAll as $keycounty => $County){				
					$data .= '<option value="'.trim($County['County']).'">'.trim($County['County']).'</option>';
				  }				
				  $data .= '</select></div>';
			}
		}

	} else {
		$error = 1;
	}
	
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>