<?php
/******************************************
* @Created  : Sept 04, 2013
* @Package  : RAND
* @Developer: Praveen Singh
* @url		: http://randstatestats.org
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$admin = new admin();
$user  = new user();

$tablesname     = "federal_food_program_participation_benefits";

$dbname = $dbsource = $description = $miscellaneous = $popcontent = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['getresults'])){

	$_SESSION['food_program'] = $_POST;

	if(isset($_SESSION['user']['email']) && $_SESSION['user']['email'] !='')					 
		$user_email=$_SESSION['user']['email'];
	else
		$user_email=$_SESSION['user']['username'];

	$userDetail		=	$user->selectUserProfile($user_email);	
	$validity_on	=	$admin->Validity($userDetail['id'],$user_email);
	
	if(isset($validity_on) && $validity_on == '0') {
		$_SESSION['msgsuccess'] = '0';
		header('location: '.URL_SITE.'/plansubscriptions.php');
		exit;
	} else {
		if(isset($_REQUEST['dbc']) && $_REQUEST['dbc']!='') {
			header('location: food_programData.php?dbc='.$_REQUEST['dbc'].'');			
			exit;
		} else {
		header('location: food_programData.php');
		exit;
		}
	}
}

$dbid = 226;
$databaseDetail = $admin->getDatabase($dbid);
if(!empty($databaseDetail)){
	
	$dbname						= stripslashes($databaseDetail['db_title']);
	$dbsource					= stripslashes($databaseDetail['db_datasource']);
	$description				= stripslashes($databaseDetail['db_description']);
	$miscellaneous				= stripslashes($databaseDetail['db_misc']);
	$nextupdate					= stripslashes($databaseDetail['db_nextupdate']);
	$table						= stripslashes($databaseDetail['table_name']);
	$db_geographic				= stripslashes($databaseDetail['db_geographic']);
	$db_periodicity				= stripslashes($databaseDetail['db_periodicity']);
	$db_dataseries				= stripslashes($databaseDetail['db_dataseries']);
	$db_datasource				= stripslashes($databaseDetail['db_source']);
	$dateupdated				= stripslashes($databaseDetail['date_added']);
	$db_datasourcelink			= stripslashes($databaseDetail['db_sourcelink']);
	$db_url						= stripslashes($databaseDetail['url']);
	$form_type					= 'two_stage';

}else{
	header('location: databases.php');
}

$related_DB_cat = $admin->getDatabaseCategories($dbid);
$related_DB		= $admin->getAllRelatedDatabases($dbid,$related_DB_cat['category_id']);

$all_search_criteria = $admin->selectAllSearchCriteria($dbid);
$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);

$formContentData = $admin->getFormContentData($dbid);

$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

$areaContent ='';
$areas_res=$admin->getDistinctColumnValuesUniversal($tablesname,$column='Area', $columns = "",$limit = "");
$areaArray = $dbDatabase->getAll($areas_res);
foreach($areaArray as $key => $value){
 $areaContent .= '<p>'.$value['Area']."</p>";
 $filtervaluesJsonArray[] = array('id' => $value['Area'], 'name' => $value['Area']);
}
$filtervaluesJson = json_encode($filtervaluesJsonArray);

$program_res=$admin->getDistinctColumnValuesUniversal($tablesname,$column='Program', $columns = "",$limit = "");
$programArray = $dbDatabase->getAll($program_res);

//echo "<pre>";print_r($filtervaluesJsonArray);echo "</pre>";
//echo "<pre>";print_r($programArray);echo "</pre>"; die;
?>

<!-- container -->
<section id="container">
	<section id="inner-content" <?php if(isset($related_DB) && mysql_num_rows($related_DB) <= 0) { ?> class="conatiner-full" <?php } ?>>
		<h2><?php echo ucfirst(stripslashes($dbname)); ?></h2>
		<br />
		<!-- main data div -->
		<div class="categorie-data">
			
			<!-- Form Basic Info Details -->
			<?php include($DOC_ROOT."/basicInfo.php"); ?>
			<!-- Form Basic Info Details -->

			<form method="post" id="frmPost" name="frmPost" action="" novalidate="novalidate">
				
				<!-- form -->
				<input type="hidden" name="session_setter" value="food_program">
				<div class="form-div">
					<p>
					   <span class="choose"><?php if(isset($lang['lbl_please_choose_program'])){ echo $lang['lbl_please_choose_program']; } ?>
					   &nbsp;&nbsp;&nbsp;<input id="search_criteria_program_all" type="checkbox" value="" name="allow_all" /> All </span>

					   <script type="text/javascript">
						$(document).ready(function() {							
							jQuery("#search_criteria_program_all").click(function(){					
								if(jQuery(this).is(":checked")){
									jQuery(".search_level_program").attr('checked', 'true');								    
									var program = new Array();
									jQuery("input:checkbox[name=Program[]]:checked").each(function(){
									  program.push(jQuery(this).val());
									});	
									jQuery('#areasDataLoad').hide();
									jQuery('.token-input-token').remove();
									if(program!='' && program != null){
										jQuery('#citiesDataLoadprogram').remove();
										loader_show();
										jQuery('#timePeriod').hide();
										jQuery('#submitButtons').hide();
										jQuery.ajax({
											url: URL_SITE+'/statsus/socials/food_programAjax.php',
											type: 'post',
											data: 'dbid=<?php echo $dbid; ?>&program='+program,
											success: function(dataresult){
												loader_unshow();
												var obj = jQuery.parseJSON(dataresult);

												if(obj.error == "0"){
													jQuery('#citiesDataLoad').html(obj.data);
													jQuery('#areasDataLoad').show();
													jQuery('#timePeriod').show();
													jQuery('#submitButtons').show();
												} else {
													jQuery('#citiesDataLoad').html('');
													jQuery('#areasDataLoad').hide();
													jQuery('#timePeriod').hide();
													jQuery('#submitButtons').hide();
												}
											}
										});
									} else {
										jQuery('#citiesDataLoad').html('');
										jQuery('#areasDataLoad').hide();
										jQuery('.token-input-token').remove();				
										jQuery('#timePeriod').hide();
										jQuery('#submitButtons').hide();
									}								
										
								} else {
									jQuery(".search_level_program").removeAttr('checked');
									jQuery('#citiesDataLoad').html('');
									jQuery('#areasDataLoad').hide();
									jQuery('.token-input-token').remove();				
									jQuery('#timePeriod').hide();
									jQuery('#submitButtons').hide();
								}
							});
						});
						</script>
					</p>
					<div class="table-div">
						<?php if(!empty($programArray)) {								
							$programArrayDetail=array_chunk($programArray,2,true);
							?>
							<table width="100%">								
								<?php foreach($programArrayDetail as $keyAll => $valueAll) { ?>
									<tr>
									<?php foreach($valueAll as $key => $value) { ?>
										<td align="left">
											<input id="program_<?php echo $key;?>" type="checkbox" class="search_level_program required" name="Program[]" value="<?php echo $value['Program'];?>" />&nbsp;&nbsp;&nbsp;<?php echo $value['Program']?>
										</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										<td align="left">&nbsp;</td>
										
										<script type="text/javascript">
										$(document).ready(function() {
										   $('#program_<?php echo $key;?>').click(function(){
											    var program = new Array();
												jQuery("input:checkbox[name=Program[]]:checked").each(function(){
												  program.push(jQuery(this).val());
												});	
												jQuery("#search_criteria_program_all").removeAttr('checked');
												jQuery('#areasDataLoad').hide();
												jQuery('.token-input-token').remove();
												if(program!='' && program != null){
													jQuery('#citiesDataLoadprogram').remove();
													loader_show();
													jQuery('#timePeriod').hide();
													jQuery('#submitButtons').hide();
													jQuery.ajax({
														url: URL_SITE+'/statsus/socials/food_programAjax.php',
														type: 'post',
														data: 'dbid=<?php echo $dbid; ?>&program='+program,
														success: function(dataresult){
															loader_unshow();
															var obj = jQuery.parseJSON(dataresult);

															if(obj.error == "0"){
																jQuery('#citiesDataLoad').html(obj.data);
																jQuery('#areasDataLoad').show();
																jQuery('#timePeriod').show();
																jQuery('#submitButtons').show();
															} else {
																jQuery('#citiesDataLoad').html('');
																jQuery('#areasDataLoad').hide();
																jQuery('#timePeriod').hide();
																jQuery('#submitButtons').hide();
															}
														}
													});
												} else {
													jQuery('#citiesDataLoad').html('');
													jQuery('#areasDataLoad').hide();
													jQuery('.token-input-token').remove();				
													jQuery('#timePeriod').hide();
													jQuery('#submitButtons').hide();
												}
											});
										});
										</script>
									<?php } ?>
									</tr>
								<?php } ?>
								</tr>
							</table>
						<?php } ?>							
					</div>
				</div>
				<!-- /form -->			

				<div class="" id="citiesDataLoad"></div>
				<div class="pB10" id="areasDataLoad" style="display:none;">
					
					<div class="form-div">
					<p>
					   <span class="choose"><?php if(isset($lang['lbl_please_choose_area'])){ echo $lang['lbl_please_choose_area']; } ?></span>&nbsp;&nbsp;<input id="search_criteria_areas_all" type="checkbox" value="" name="allow_all" /> All
					</p>

					<div class="left">
						<input class="search_criteria_duplicates_area required" type="text" id="search_criteria_duplicates_area" name="Area" />
					</div>
					<div class="left">
						&nbsp;&nbsp;<a id="viewall" href="javascript:;" onclick="javascript: return popup_window('<?php echo $areaContent; ?>');">See a list</a>
					</div>						
					<div class="clear"></div>

					<script type="text/javascript">
					$(document).ready(function() {
						$("#search_criteria_duplicates_area").tokenInput(<?php echo $filtervaluesJson; ?>, { preventDuplicates: true				
						});
						
						jQuery("#search_criteria_areas_all").click(function(){
							if(jQuery(this).is(":checked")){								
								jQuery(".search_criteria_duplicates_area").tokenInput("toggleDisabled");	
							} else {
								jQuery(".search_criteria_duplicates_area").tokenInput("toggleDisabled");
							}
						});
					});
					</script>

				</div>

				</div>

				<div class="bottom-submit">	
					<?php if(isset($timeIntervalSettings) && $timeIntervalSettings['time_format'] == 'SY-EY'){							
						$time_format = $timeIntervalSettings['time_format'];
						$columns = unserialize($timeIntervalSettings['columns']);
					?>

						<h4>Please choose a time period.</h4>
						<br />
						
						<div class="time-select">
							<label for="smonth">Start Year</label>
							<br />
						   <select id="syear" size="1" name="syear">
							<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
							<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
							<?php } ?>
							</select>
						</div>
					   
						<div class="time-select">
							<label for="smonth">End Year</label>
							<br />
							<select id="eyear" size="1" name="eyear">
								<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
								<option value="<?php echo $i; ?>" <?php if($i == $columns['eyear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="clear"> </div>

					<?php } ?>

					<?php if(isset($timeIntervalSettings) && $timeIntervalSettings['time_format'] == 'SM-SY-EM-EY'){ 	
							
						$time_format = $timeIntervalSettings['time_format'];
						$columns = unserialize($timeIntervalSettings['columns']);	
						
						$months = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 =>"Apr", 5 => "May", 6 => "June", 7 => "July", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
						
					?>
						<h4>Please choose a time period.</h4>
						<br />
						<div class="time-select">
							<label for="smonth">Start Month</label>
							<br />
						   <select id="smonth" size="1" name="smonth">
								<?php for($i=$columns['smonth'];$i<=$columns['emonth'];$i++){ ?>
								<option value="<?php echo $i; ?>" <?php if($i == $columns['smonth']){ echo "selected='selected'"; } ?> ><?php echo $months[$i]; ?></option>
								<?php } ?>
							
							</select>
						</div>
						<div class="time-select">
							<label for="smonth">Start Year</label>
							<br />
						   <select id="syear" size="1" name="syear">
								<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
								<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
								<?php } ?>
							
							</select>
						</div>
						<div class="time-select">
							<label for="smonth">End Month</label>
							<br />
							<select id="emonth" size="1" name="emonth">
								<?php for($i=$columns['smonth'];$i<=$columns['emonth'];$i++){ ?>
								<option value="<?php echo $i; ?>" <?php if($i == $columns['emonth']){ echo "selected='selected'"; } ?> ><?php echo $months[$i]; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="time-select">
							<label for="smonth">End Year</label>
							<br />
							<select id="eyear" size="1" name="eyear">
								<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
								<option value="<?php echo $i; ?>" <?php if($i == $columns['eyear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
								<?php } ?>
							</select>
						</div>
						<div class="clear"> </div>

					<?php } ?>

					<?php if(isset($timeIntervalSettings) && $timeIntervalSettings['time_format'] == 'SQ-SY-EQ-EY'){ 					
						$time_format = $timeIntervalSettings['time_format'];
						$columns = unserialize($timeIntervalSettings['columns']);						
						$quaters = array(1 => "1st", 2 => "2nd", 3 => "3rd", 4 =>"4th");			
					?>
						
							<h4>Please choose a time period.</h4>
							<br />
							<div class="time-select">
								<label for="smonth">Start Quarter</label>
								<br />
								<select id="squater" size="1" name="squater">
									<?php for($i=$columns['squater'];$i<=$columns['equater'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['squater']){ echo "selected='selected'"; } ?> ><?php echo $quaters[$i]; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="time-select">
								<label for="smonth">Start Year</label>
								<br />
								<select id="syear" size="1" name="syear">
									<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="time-select">
								<label for="smonth">End Quarter</label>
								<br />
								<select id="equater" size="1" name="equater">
									<?php for($i=$columns['squater'];$i<=$columns['equater'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['equater']){ echo "selected='selected'"; } ?> ><?php echo $quaters[$i]; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="time-select">
								<label for="smonth">End Year</label>
								<br />
								<select id="eyear" size="1" name="eyear">
									<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['eyear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="clear"> </div>

					<?php } ?>
									
					<div class="submitbtn-div" id="submitButtons" style="display:none;">
						<input onclick="javascript: return checkLoginUser('<?php echo $form_type;?>','<?php if(isset($_SESSION['user'])) echo "true"; else echo 'false'; ?>','<?php echo $db_url;?>','0');" value="Submit" name="getresults" class="submitbtn" type="submit">
						<?php if(isset($_GET['dbc']) && $_GET['dbc']!='') { ?>
						<input value="<?php echo $_GET['dbc'];?>" name="dbnameurl" type="hidden">	
						<?php } ?>
						<input value="<?php echo $dbid; ?>" name="dbid" type="hidden" >
						<input type="submit" class="right" name="" value="Reset">
					</div>

				</div>
			
			</form>

		</div>
	</section>

	<!-- RELATED_DB -->
	<?php 
	if(isset($related_DB) && mysql_num_rows($related_DB) > 0) { ?>
	<section id="inner-sidebar">
	   <?php require_once $basedir."/relatedForms.php"; ?>
   </section>
   <?php } ?>
   <!-- RELATED_DB -->

</section>
<?php
include_once $basedir."/include/footerHtml.php";
?>
		