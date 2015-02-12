<?php
/******************************************
* @Modified on Feb 28, 2013
* @Package: Rand
* @Developer: sandeep kumar
#live Site URL For This Page: http://statestats.rand.org/stats/govtfin/local_govt_spending.html
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$tablesname = "federal_funds_for_education_and_related_programs";

$admin = new admin();

$levelArrayDetailMain = $relatedprogram = $relateddepartment = $array= array();

$dbid = $_REQUEST['dbid'];

$databaseDetail = $admin->getDatabase($dbid);

$data = $errorMSG = '';
$error = 0;

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

//selecting one more grades
if(isset($_REQUEST['level']) && $_REQUEST['level']!=''){
	
	$level = $_REQUEST['level'];
	$levelArray = explode(';',$level);
	foreach($levelArray as $stateKey => $levelname){		
		$relatedCounty_res = $admin->searchDistinctUniversalColoumOneArray($tablesname, $displaycolumnnamestr='DISTINCT(department)' ,$columnname='level', $columnnamevalue=$levelname ,'order by department');
		$relateddepartment[$levelname] = $dbDatabase->getAll($relatedCounty_res);
	}

	if(!empty($relateddepartment)) {	

		$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_please_select_agency']))?$lang['lbl_please_select_agency']:'').'</span></p><br>';
		
		foreach($relateddepartment as $KeyLevel => $departmentDetailAll){

			$levelArrayDetailMain[$KeyLevel] = array_chunk($departmentDetailAll,2,true);

			foreach($levelArrayDetailMain as $Key => $departmentDetail){

				//echo "<pre>";print_r($departmentDetail);echo "</pre>";			
			
				$size         = 2;
				$levelname    = $KeyLevel;
				$levelnameids = str_replace(' ','',$KeyLevel);
				$levelnameid  = str_replace('/','',$levelnameids);

				$data .= '<p><span class="choose">'.$levelname.'</span>&nbsp;&nbsp;&nbsp;';
						
				$data .='<input type="checkbox" id="checkalldeparment'.$levelnameid.'">&nbsp;All';			
				$data .='<script type="text/javascript">
					$(document).ready(function() {							
						jQuery("#checkalldeparment'.$levelnameid.'").click(function(){					
							if(jQuery(this).is(":checked")){
								jQuery(".deparment_level_disable").attr("checked", "true");			
							} else {
								jQuery(".deparment_level_disable").removeAttr("checked");
							}
						});
						jQuery(".deparment_level_disable").click(function(){					
							jQuery("#checkalldeparment'.$levelnameid.'").removeAttr("checked");
							
						});
					});
					</script>';			

				$data .='</p>';

				$data .= '<div class="table-div"><table width="auto">';

				foreach($departmentDetail as $keymainAll => $departmentAll){

					$data .= '<tr>';

					foreach($departmentAll as $keymain => $department){
						
						$data.='<td align="left">
								<input value="'.$department['department'].'" type="checkbox" id="deparmentselect_'.$keymain.'" name="department[]" class="deparment_level_disable required" />
								</td>
								<td align="left">'.$department['department'].'</td>
								<td align="left">&nbsp;</td>';
												
						$data .='<script type="text/javascript">
								$(document).ready(function() {
									$("#deparmentselect_'.$keymain.', #checkalldeparment'.$levelnameid.'").live("click", function(){
									
										var department = new Array();
									    jQuery("input:checkbox[name=department[]]:checked").each(function(){
										  department.push(jQuery(this).val());
									    });	
										
										if(department == "" || department == null){
											jQuery("#citiesDataLoadprogram").html("");
											jQuery("#timePeriod").hide();
											jQuery("#submitButtons").hide();
											return true;
										} else {										
											loader_show();
											jQuery("#timePeriod").hide();
											jQuery("#submitButtons").hide();
											jQuery.ajax({
												url: URL_SITE+"/statsus/education/federal_fundsAjax.php",
												type: "post",
												data:  "dbid='.$dbid.'&department="+department+"&levelname='.$levelname.'",
												success: function(dataresult){
													loader_unshow();
													var obj = jQuery.parseJSON(dataresult);
													if(obj.error == "0"){
														jQuery("#citiesDataLoadprogram").html(obj.data);
														jQuery("#timePeriod").show();
														jQuery("#submitButtons").show();
													} else {
														jQuery("#citiesDataLoadprogram").html("");
														jQuery("#timePeriod").hide();
														jQuery("#submitButtons").hide();
													}
												}
											});
										} 
									});
								});
								</script>';
					}
					$data .= '</tr>';
				}
				$data .= '</table></div>';
					
		   }
		}
		$data .= '</div>';
		
	} else {
		$error = 1;
	}

} else if(isset($_REQUEST['department']) && trim($_REQUEST['department'])!='' && trim($_REQUEST['department'])!= null){
	//selecting one more programname
	$levelname  = trim($_REQUEST['levelname']);
	$department = trim($_REQUEST['department']);
	$departmentArray = explode(',',$department);
	foreach($departmentArray as $stateKey => $programname){	
		if($programname!='on'){
		$relatedprogram_res = $admin->searchDistinctUniversalColoumOneArray($tablesname, $displaycolumnnamestr='DISTINCT(program)' ,$columnname='department', $columnnamevalue=$programname ," and level = '".$levelname."' order by program");
		$relatedprogram[$programname] = $dbDatabase->getAll($relatedprogram_res);
		}
	}
	
	if(!empty($relatedprogram)) {

		//choose county
		if(!empty($relatedprogram)){

			$data .= '<div class="form-div"><p><span class="choose">'.((isset($lang['lbl_please_select_agency']))?$lang['lbl_please_select_agency']:'').'</span></p><br>';
			$i=1;
			foreach($relatedprogram as $Keyprogram => $programDetail){
				
				$size          = 2;
				$programname   = $Keyprogram;
				$programnameid = str_replace(' ','',$Keyprogram);

				$data .= '<div class="form-div"><p><span class="choose">'.$programname.'</span>&nbsp;&nbsp;&nbsp;';
						
				if(isset($programDetail) && count($programDetail) > 2){
					
					$size  = 5;
					$data .='<input type="checkbox" id="checkallprogramname'.$programnameid.'">&nbsp;All';			
					$data .='<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery("#checkallprogramname'.$programnameid.'").change(function(){
								var checkedd =  this.checked ? true : false;
								if(checkedd){
									jQuery(".programnameAll'.$programnameid.' option").attr("selected", "selected");
								}else{
									jQuery(".programnameAll'.$programnameid.' option").removeAttr("selected");
								}
							});

							jQuery(".programnameAll'.$programnameid.'").change(function(){							
								jQuery("#checkallprogramname'.$programnameid.'").removeAttr("checked");						
							});
						});
					  </script>';
				}

				$data .='</p>';

				$data .= '<div class="table-div">';
					
				$data .= '<select id="programname'.$i.' required" style="width:400px;" size="'.$size.'" name="program[]" class="programnameAll'.$programnameid.' required" multiple >';			
				foreach($programDetail as $keymain => $program){
					$data .= '<option value="'.$program['program'].'">'.$program['program'].'</option>';	
				}
				$data .= '</select>';			
				
				$data .= '</div></div>';
				$i++;
			}

			$data .= '</div>';
		}
	} else {
		$error = 1;
	}
} else {
	$error = 1;
}

//echo "<pre>";print_r($relatedprogram);echo "</pre>"; die;

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>