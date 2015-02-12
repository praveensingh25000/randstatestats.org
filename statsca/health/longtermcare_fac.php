<?php
/******************************************
* @Modified on Modified on April 5, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://50.62.142.193/
* live Site: http://ca.rand.org/stats/health/longtermcare_fac.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

require_once('data.php');

$admin = new admin();
$user  = new user();

$tablesname				= "longtermcare_fac";
$tablesnamearea			= "longtermcare_fac_areas";

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['getresults'])){

	$_SESSION['longtermcare_fac'] = $_POST;

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
		header('location: longtermcare_fac_data.php');
		exit;
	}
}

$dbid = 200;
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

$related_DB = $admin->getAllDatabaseRelatedDatabases($dbid);
	

$all_search_criteria = $admin->selectAllSearchCriteria($dbid);
$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);

$formContentData = $admin->getFormContentData($dbid);
$lang = array();
foreach($formContentData as $key => $detail){
	$lang[$detail['label_name']] = $detail['label_value'];
}	

foreach($CACounties as $key => $value){
	if($key != '00')
	$stateToNamefiltervaluesJsonArray[] = array('id' => $key, 'name' => $value);
}
$filtervaluesJsonState = json_encode($stateToNamefiltervaluesJsonArray);


//$filtervaluesJson = json_encode($filtervaluesJsonArray);
//echo "<pre>";print_r($stateallmain);echo "</pre>";
?>

<!-- container -->
<section id="container">
	<section id="inner-content">
		<h2><?php echo ucfirst(stripslashes($dbname)); ?></h2>
		<br />
		<!-- main data div -->
		<div class="categorie-data">
			
			<!-- Form Basic Info Details -->
			<?php include($DOC_ROOT."/basicInfo.php"); ?>
			<!-- Form Basic Info Details -->

			<form method="post" id="frmPost" name="frmPost" action="" novalidate="novalidate">
				
				<!-- form -->
				<input type="hidden" name="session_setter" value="avgwagenaicsUS">
				<div class="form-div">
					<p>
					   <span class="choose"><?php echo $lang['LBL_ENTER_COUNTY_LONG']; ?></span>&nbsp;&nbsp;
					</p>
					<div class="table-div">	

						<p><input id="ca_counties" class="required" type="input"  name="ca_counties"/></p>

						<script type="text/javascript">
						$(document).ready(function() {							
							$("#ca_counties").tokenInput( <?php echo $filtervaluesJsonState ; ?>, { 
								preventDuplicates: true,
								tokenLimit: 3,
								onAdd: function (item) {
											var area      = jQuery('#ca_counties').val();
										
											loader_show();
											jQuery('#timePeriod').hide();
											jQuery('#submitButtons').hide();

											jQuery.ajax({								
												type: 'post',
												data: jQuery("#frmPost").serialize(),
												url: URL_SITE+'/statsca/health/longtermcare_fac_ajax.php?dbid=<?php echo $dbid; ?>',
												
												success: function(dataresult){
													loader_unshow();
													var obj = jQuery.parseJSON(dataresult);

													if(obj.error == "0"){
														jQuery('#citiesDataLoad').html(obj.data);
														jQuery('#timePeriod').show();
														jQuery('#submitButtons').show();
													}else{
														jQuery('#citiesDataLoad').html('');
														jQuery('#timePeriod').hide();
														jQuery('#submitButtons').hide();
													}
												}
											});							
										
								},
								onDelete: function (item) {
										var area      = jQuery('#ca_counties').val();
								
										jQuery.ajax({								
											type: 'post',
											data: jQuery("#frmPost").serialize(),
											url: URL_SITE+'/statsca/health/longtermcare_fac_ajax.php?dbid=<?php echo $dbid; ?>',
											
											success: function(dataresult){
												loader_unshow();
												var obj = jQuery.parseJSON(dataresult);

												if(obj.error == "0"){
													jQuery('#citiesDataLoad').html(obj.data);
													jQuery('#timePeriod').show();
													jQuery('#submitButtons').show();
												}else{
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
				  </div>
				<!-- /form -->

				<div class="pB10" id="citiesDataLoad"></div>

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
			
			</form>

		</div>

	</section>

	<?php 
	if(mysql_num_rows($related_DB)>0){ 
	?>
	<section id="inner-sidebar">
	   <?php require_once $basedir."/relatedForms.php"; ?>
   </section>
   <?php } ?>

</section>
<?php
include_once $basedir."/include/footerHtml.php";
?>
		