<?php
/******************************************
* @Created on Oct 04, 2013
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://randstatestats.org/statsus/transptravel/fuel_vehicles.php
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$StateContent = $filterfuelTypeJson = $popcontentfuelType = $filterfuealtypeJson = '';

$fuelTypeDetail = array();

$tablesname		= "alternative_fuel_vehicles_2003_2011";

$admin = new admin();
$user  = new user();

if(isset($_POST['getresults'])){

	$_SESSION['fuel_vehicles'] = $_POST;

	if(isset($_REQUEST['dbnameurl']) && $_REQUEST['dbnameurl']!='') {
		$dbnameurl = $_REQUEST['dbnameurl'];
	}	
	$user_id		=	$_POST['user_id'];	
	$userDetail		=	$user->getUser($user_id);	
	$validity_on	=	$admin->Validity(trim($userDetail['id']),trim($userDetail['email']));
	
	if(isset($validity_on) && $validity_on == '0') {
		$_SESSION['msgsuccess'] = '0';
		header('location: '.URL_SITE.'/plansubscriptions.php');
		exit;
	} else {
		if(isset($dbnameurl) && $dbnameurl!='') {
			header('location: fuel_vehiclesData.php?dbc='.$dbnameurl.'');			
			exit;
		} else {
		header('location: fuel_vehiclesData.php');
		exit;
		}
	}
}

$dbid = 244;
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

//For fuel Type Detail
$fuelTypedetail_res  = $admin->getDistinctColumnValuesUniversal($tablesname , $column='fuelType', $columns = "", $limit = "");
$fuelTypeDetail = $dbDatabase->getAll($fuelTypedetail_res);
foreach($fuelTypeDetail as $key => $value){
	$popcontentfuelType .= '<p>'.$value['fuelType']."</p>";
	$filterfuelTypeJsonArray[] = array('id' => $value['fuelType'], 'name' => $value['fuelType']);
}
$filterfuelTypeJson = json_encode($filterfuelTypeJsonArray);

//selecting states
$states_res=$admin->getDistinctColumnValuesUniversal($tablesname,$column='State', $columns = "",$limit = "");
$statesArray = $dbDatabase->getAll($states_res);
foreach($statesArray as $key => $value){
	$StateContent .= "<p>".$value['State']."</p>";
	$filtervaluesStateJsonArray[] = array('id' => trim($value['State']), 'name' => trim($value['State']));
}
$filtervaluesStateJson = json_encode($filtervaluesStateJsonArray);

//echo "<pre>";print_r($filterfuealtypeJsonArray);echo "</pre>";
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
				<input type="hidden" name="session_setter" value="fuel_vehicles">
				<div class="form-div">
					<p>
					   <span class="choose"><?php if(isset($lang['lbl_please_enter_fuel_type'])){ echo $lang['lbl_please_enter_fuel_type']; } ?>&nbsp;&nbsp;&nbsp;<input id="search_criteria_fuelType_all" type="checkbox" value="All" name="allow_all" /> All </span>
					</p>

					<div class="left">
						<input class="search_criteria_class_fuelType required" type="text" id="search_criteria_duplicates_fuelType" name="fuelType" />
					</div>
					<div class="left">
						&nbsp;&nbsp;<a id="viewall" href="javascript:;" onclick="javascript: return popup_window('<?php echo $popcontentfuelType; ?>');">See a list</a>
						<script type="text/javascript">
						$(document).ready(function() {							
							jQuery("#search_criteria_fuelType_all").click(function(){					
								if(jQuery(this).is(":checked")){
									var fuelType = jQuery('#search_criteria_fuelType_all').val();
								    jQuery("#search_criteria_duplicates_fuelType").tokenInput("toggleDisabled");
									loader_show();
									jQuery('#citiesDataLoad').hide();
									jQuery('#timePeriod').hide();
									jQuery('#submitButtons').hide();
									jQuery.ajax({
										url: URL_SITE+'/statsus/transptravel/fuel_vehiclesAjax.php',
										type: 'post',
										data: 'dbid=<?php echo $dbid; ?>&selectfuel=1&fuelType='+fuelType,
										success: function(dataresult){
											loader_unshow();
											var obj = jQuery.parseJSON(dataresult);
											if(obj.error == 0){
												jQuery('#citiesDataLoad').html(obj.data).show();
											} else {
												jQuery('#citiesDataLoad').html('');							
												jQuery('#timePeriod').hide();
												jQuery('#submitButtons').hide();
											}
										}
									});
										
								} else {
									jQuery("#search_criteria_duplicates_fuelType").tokenInput("toggleDisabled");
									jQuery('#citiesDataLoad').html('');
									jQuery('#areasDataLoad').hide();
									jQuery('#timePeriod').hide();
									jQuery('#submitButtons').hide();
								}
							});
						});
						</script>
					</div>						
					<div class="clear"></div>

					<script type="text/javascript">
					$(document).ready(function() {							
						
						$("#search_criteria_duplicates_fuelType").tokenInput(<?php echo $filterfuelTypeJson; ?>, { preventDuplicates: true,
							
							onAdd: function (item) {
								var fuelType = jQuery('#search_criteria_duplicates_fuelType').val();
								loader_show();
								jQuery('#citiesDataLoad').hide();
								jQuery('#timePeriod').hide();
								jQuery("#statesDataLoad").hide();
								jQuery('#submitButtons').hide();
								jQuery.ajax({
									url: URL_SITE+'/statsus/transptravel/fuel_vehiclesAjax.php',
									type: 'post',
									data: 'dbid=<?php echo $dbid; ?>&selectfuel=1&fuelType='+fuelType,
									success: function(dataresult){
										loader_unshow();
										var obj = jQuery.parseJSON(dataresult);
										if(obj.error == 0){
											jQuery('#citiesDataLoad').html(obj.data).show();
										} else {
											jQuery('#citiesDataLoad').html('');
											jQuery('#timePeriod').hide();
											jQuery('#submitButtons').hide();
										}
									}
								});
							},
							onDelete: function (item) {
								var fuelType = jQuery('#search_criteria_duplicates_fuelType').val();
								loader_show();
								jQuery('#citiesDataLoad').hide();
								jQuery("#statesDataLoad").hide();
								jQuery('#timePeriod').hide();
								jQuery('#submitButtons').hide();
								jQuery.ajax({
									url: URL_SITE+'/statsus/transptravel/fuel_vehiclesAjax.php',
									type: 'post',
									data: 'dbid=<?php echo $dbid; ?>&selectfuel=1&fuelType='+fuelType,
									success: function(dataresult){										
										loader_unshow();									
										var obj = jQuery.parseJSON(dataresult);							
										if(obj.error == 0){
											jQuery('#citiesDataLoad').html(obj.data).show();
										} else {
											jQuery('#citiesDataLoad').html('');								
											jQuery('#timePeriod').hide();
											jQuery('#submitButtons').hide();
										}
									}
								});
							}
						
						});
			
					});
				
				</script>

				</div>
				
				<!-- /form -->			

				<div class="" id="citiesDataLoad"></div>
				<div class="" id="statesDataLoad" style="display:none;">			
				
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_please_enter_state'])){ echo $lang['lbl_please_enter_state']; } ?></span>&nbsp;&nbsp;&nbsp;<input id="search_criteria_area_all" type="checkbox" value="All" name="allow_all" /></span>
						   
						   <script type="text/javascript">
							$(document).ready(function() {							
								jQuery("#search_criteria_area_all").click(function(){					
									if(jQuery(this).is(":checked")){								
										jQuery("#search_criteria_duplicates_area").tokenInput("toggleDisabled");
									} else {
										jQuery("#search_criteria_duplicates_area").tokenInput("toggleDisabled");
									}
								});
							});
							</script>
						</p>

						<div class="left">
								<input class="search_criteria_class_area required" type="text" id="search_criteria_duplicates_area" name="State" />
						</div>

						<div class="left pL10">
							&nbsp;&nbsp;<a id="viewall" href="javascript:;" onclick="javascript: return popup_window('<?php echo $StateContent; ?>');">See a list</a>				
						</div>						
						<div class="clear"></div>

						<script type="text/javascript">
						$(document).ready(function() {							
							$("#search_criteria_duplicates_area").tokenInput(<?php echo  $filtervaluesStateJson;?>, {  preventDuplicates: true						
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
						<input onclick="javascript: return checkLoginUser('<?php echo $form_type;?>','<?php echo $checksubmitType; ?>','<?php echo $db_url;?>','0');" value="Submit" name="getresults" class="submitbtn" type="submit">
						<input value="<?php echo $dbid; ?>" name="dbid" type="hidden" >					
						<?php if(isset($_GET['dbc']) && $_GET['dbc']!='') { ?>
						<input value="<?php echo $_GET['dbc'];?>" name="dbnameurl" type="hidden">	
						<?php } ?>							
						<?php if(isset($user_id) && $user_id!='') { ?>
						<input value="<?php echo $user_id;?>" name="user_id" type="hidden">	
						<?php } ?>
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

<?php include_once $basedir."/include/footerHtml.php";?>