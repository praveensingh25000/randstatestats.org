<?php
/******************************************
* @Modified on April 12, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/education/fep.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/actionHeader.php";

global $dbDatabase;

$relatedCounty	=	$countiesArray	=	$Cat1DetailArray = $categoryDetailArray = array();

$tablesname				= "fep";
$tablesnamecats			= "fep";
$tablesnamecd			= "cd_new";
$tablesnamecds			= "cds";

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

//lbl_choose one or more categories
$allCategoryDetailages_res  = $admin->getDistinctColumnValuesUniversal($tablesnamecats, 'Category');

if(mysql_num_rows($allCategoryDetailages_res) >0 ){

	$data .= '<br><br><div class="form-div">
					<p><span class="choose">'.((isset($lang['lbl_choose_categories']))?$lang['lbl_choose_categories']:'').'</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" id="checkallCategory">&nbsp;&nbsp;All
		
					<script type="text/javascript">
					jQuery(document).ready(function(){
						jQuery("#checkallCategory").click(function(){
							jQuery(".Category").attr("checked", this.checked);
						});
						jQuery(".Category").click(function(){
							jQuery("#checkallCategory").attr("checked", false);
						});
					});
					</script>					
			</p>
			</div>';
	
	$data .= '<div class="table-div">';
	
	while($categoryDetail = mysql_fetch_assoc($allCategoryDetailages_res)){
	$data .= '<p class="pT5 pB5"><input type="checkbox" class="Category required" name="Category[]" value = "'.$categoryDetail['Category'].'">&nbsp;'.$categoryDetail['Category'].'</p>';
	}

	$data .= '</div>';

}


require_once("schools.php");

if(isset($_REQUEST['states']) && $_REQUEST['states']!='' && isset($SchDist)) {
		$i = 0;

		if($_REQUEST['states']<10){
			$_REQUEST['states'] = '0'.$_REQUEST['states'];
		}

		if(isset($SchDist[$_REQUEST['states']])){

			$states = $SchDist[$_REQUEST['states']];
		
			$allSchool =  $SchDist[$_REQUEST['states']];
			$allSchool = explode(' ',$allSchool);

			$data .= '<div class="form-div">
						<p><span class="choose">'.((isset($lang['lbl_select_schools_in_district']))?$lang['lbl_select_schools_in_district']:'').'</span>&nbsp;&nbsp;<br/><label for="cdsf" generated="true" class="error" style="display:none;">This field is required.</label>
						
						<script type="text/javascript">
						jQuery(document).ready(function(){
							jQuery("#checkallschooldistrict").change(function(){
								var checkedd =  this.checked ? true : false;
								if(checkedd){
									jQuery("#schooldistrict option").attr("selected", "selected");
								}else{
									jQuery("#schooldistrict option").removeAttr("selected");
								}
							});

							jQuery(document).ready(function(){
								jQuery(".schooldistrict").change(function(){
									jQuery("#cdsf").val(jQuery(this).val());
								});
							});
						});
						</script>
						
						</p></div>';

			$data.="<table width='100%' cellspacing='1' cellpadding='1'>";
			
			$allSchool = array_chunk($allSchool, 3, true);
			foreach($allSchool as $keySchool => $schools){
				$data.="<tr>";
				foreach($schools as $keyschool1 => $schoolDetail){

		

					$districtcode = (int)$_REQUEST['states'].$schoolDetail;
					
					$districtDetail =  $admin->getRowUniversal($tablesnamecd, 'cdcode', $districtcode);


					$schoolsDetailResult =  $admin->searchLikeUniversalEqual($tablesnamecds , 'cdcode', $districtcode, ' order by cdsname ');

					$data.="<td>";
					$data .= '<div class="form-div">
								<p><span class="choose">'.$districtDetail['cdname'].'</span></p>';

					$data .= '<select name="schooldistrict[]" class="schooldistrict" multiple >';
					$data .= '<option value="'.$districtDetail['cdcode'].'0000000" >District Totals </option>';
					
					if(mysql_num_rows($schoolsDetailResult)>0){
						while($rowSchool = mysql_fetch_assoc($schoolsDetailResult)){
							if($rowSchool['cdscode'] != $districtDetail['cdcode'] && $rowSchool['cdscode'] != $districtDetail['cdcode'].'0000000')
								$data .= '<option value="'.$rowSchool['cdscode'].'">'.$rowSchool['cdsname'].'</option>';
						}
					}

					$data.="</select>";

					$data .= '</div>';

					$data.="</td>";
				
				}
				$data.="</tr>";
			}
			$data.="</table><div style='display:none;'><input type='text' value='' name='cdsf' id='cdsf' class='required'></div>";
		}
			
} else {
	$error = 1;
}

$jsonData = array('error' => $error, 'message' => $errorMSG, 'data' => $data);
echo json_encode($jsonData);
?>