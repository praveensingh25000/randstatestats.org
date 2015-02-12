<?php
/******************************************
* @Modified on March 04, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/govtfin/cityfinance.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$tablesname				= "cityfinance";
$tablesnamecat			= "cityfinance_cats";
$tablesnamearea			= "cityfinanceareas";
$tablesnamecounty		= "ca_counties";

$admin = new admin();
$user  = new user();

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = $filtervaluesJsonArray = $allCategoryArray = array();

if(isset($_POST['getresults'])){

	$_SESSION['cityfinance'] = $_POST;

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
		header('location: cityfinanceData.php');
		exit;
	}
}

$dbid = 95;
$databaseDetail = $admin->getDatabase($dbid);
if(!empty($databaseDetail)){
	$dbname			= stripslashes($databaseDetail['db_title']);
	$dbsource		= stripslashes($databaseDetail['db_datasource']);
	$description	= stripslashes($databaseDetail['db_description']);
	$miscellaneous	= stripslashes($databaseDetail['db_misc']);
	$nextupdate		= stripslashes($databaseDetail['db_nextupdate']);
	$table			= stripslashes($databaseDetail['table_name']);
	$db_geographic	= stripslashes($databaseDetail['db_geographic']);
	$db_periodicity	= stripslashes($databaseDetail['db_periodicity']);
	$db_dataseries	= stripslashes($databaseDetail['db_dataseries']);
	$db_datasource	= stripslashes($databaseDetail['db_source']);
	$dateupdated	= stripslashes($databaseDetail['date_added']);
	$db_datasourcelink	= stripslashes($databaseDetail['db_sourcelink']);
	$db_url				= stripslashes($databaseDetail['url']);
	$form_type			= 'two_stage';
}else{
	header('location: databases.php');
}

$related_DB = $admin->getAllDatabaseRelatedDatabases($dbid);

$all_search_criteria = $admin->selectAllSearchCriteria($dbid);
$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}

$allCategoryDetail_res  = $admin->getTableDataUniversal($tablesnamecat);
$allCategory = $dbDatabase->getAll($allCategoryDetail_res);
$catstr = '';
foreach($allCategory as $key => $value1){
	if($value1['catcode'] != null)
	$allCategoryArray[] = array('id' => $value1['catcode'], 'name' => $value1['catname']);
	$catstr.= stripslashes(str_replace("'","",$value1['catname'])).'<br>';
}
$filtervaluesJsonallCategory = json_encode($allCategoryArray);

$stateToName_res  = $admin->getTableDataUniversal($tablesnamecounty);
$stateToName = $dbDatabase->getAll($stateToName_res);
$statestr = '';
foreach($stateToName as $key => $value){
	if($value['areaname'] != null)
	$filtervaluesJsonArray[] = array('id' => $value['areaname'], 'name' => $value['areaname']);
	$statestr.= $value['areaname'].'</br>';
}
$filtervaluesJson = json_encode($filtervaluesJsonArray);
//echo "<pre>";print_r($allCategory);echo "</pre>";
//echo "<pre>";print_r($filtervaluesJsonArray);echo "</pre>"; die;
?>

<!-- container -->
<section id="container">
	<section id="inner-content" <?php if(isset($related_DB) && mysql_num_rows($related_DB) <= 0) { ?> class="conatiner-full" <?php } ?>>
		<h2><?php echo ucfirst(stripslashes($dbname)); ?></h2><br />

		<!-- main data div -->
		<div class="categorie-data">
			
			<!-- form basic info details -->
			<?php include($DOC_ROOT."/basicInfo.php"); ?>
			<!-- form basic info details -->

			<form method="post" id="frmPost" name="frmPost" action="" novalidate="novalidate">
				<!-- form -->
				<input type="hidden" name="session_setter" value="cityfinance"/>
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_select_one_more_categories'])){ echo $lang['lbl_select_one_more_categories']; } ?> </span>
						</p>
						<div class="table-div">
							<div class="wdthpercent35 left">
									<input type="text" id="search_criteria_duplicates_cat" name="Category[]"/>
							</div>							
							<div class="wdthpercent20 left pT10 pL10">
								<a onclick="javascript: return popup_window('<?php echo $catstr; ?>');" href="javascript:;">See a list</a>
							</div>
							
						</div>
						
						
					</div>
					
					<!-- /form -->
					<script type="text/javascript">
					$(document).ready(function() {							
						
						$("#search_criteria_duplicates_cat").tokenInput(<?php echo $filtervaluesJsonallCategory; ?>, {	
							preventDuplicates: true						
						});
			
					});					
					</script>
										
					<!-- form -->
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_select_one_more_counties'])){ echo $lang['lbl_select_one_more_counties']; } ?> </span>
						</p>
						<div class="table-div">
							<div class="wdthpercent35 left">
								<input type="text" id="search_criteria_duplicates" name="us_states"/>
							</div>

							<div class="wdthpercent20 left pT10 pL10">
								<a onclick="javascript: return popup_window('<?php echo $statestr; ?>');" href="javascript:;">See a list</a>
							</div>							
						</div>						
					</div>	
					
					<!-- /form -->
					
					<div class="pB10" id="cities_data_load"></div>

					<script type="text/javascript">
					$(document).ready(function() {							
						
						$("#search_criteria_duplicates").tokenInput(<?php echo $filtervaluesJson; ?>, {		
							preventDuplicates: true,
							
							onAdd: function (item) {
								var states = jQuery('#search_criteria_duplicates').val();
								loader_show();
								jQuery('#timePeriod').hide();
								jQuery('#submitButtons').hide();
								jQuery.ajax({
									url: URL_SITE+'/statsca/govtfin/cityfinanceAjax.php',
									type: 'post',
									data: 'dbid=<?php echo $dbid; ?>&states='+states,
									success: function(dataresult){
										loader_unshow();
										var obj = jQuery.parseJSON(dataresult);

										if(obj.error == "0"){
											jQuery('#cities_data_load').html(obj.data);
											jQuery('#timePeriod').show();
											jQuery('#submitButtons').show();
										}else{
											jQuery('#cities_data_load').html('');
											jQuery('#timePeriod').hide();
											jQuery('#submitButtons').hide();
										}
									}
								});
							},
							onDelete: function (item) {
								var states = jQuery('#search_criteria_duplicates').val();
								loader_show();
								jQuery('#timePeriod').hide();
								jQuery('#submitButtons').hide();
								jQuery.ajax({
									url: URL_SITE+'/statsca/govtfin/cityfinanceAjax.php',
									type: 'post',
									data: 'dbid=<?php echo $dbid; ?>&states='+states,
									success: function(dataresult){									
										loader_unshow();
										var obj = jQuery.parseJSON(dataresult);
										if(obj.error == 0){
											jQuery('#cities_data_load').html(obj.data);
											jQuery('#timePeriod').show();
											jQuery('#submitButtons').show();
										}else{
											jQuery('#cities_data_load').html('');
											jQuery('#timePeriod').hide();
											jQuery('#submitButtons').hide();
										}
									}
								});
							}						
						});
			
					});
					
					</script>

					<!---------------- TIME INTERVAL SETTINGS -------------------->
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
						<input value="<?php echo $dbid; ?>" name="dbid" type="hidden" >
						<input type="submit" class="right" name="" value="Reset">
					</div>
					
					</div>
					<!---------------- TIME INTERVAL SETTINGS -------------------->
				
				</form>
			</div>
		</section>
			<!-- /FORM SECTION DATA DIV ------>

	

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