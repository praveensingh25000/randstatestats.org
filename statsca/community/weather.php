<?php
/******************************************
* @Modified on Feb 08, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
#live Site URL For This Page: http://ca.rand.org/stats/community/weather.html
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$admin = new admin();
$user  = new user();

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['getresults'])){

	$_SESSION['weather'] = $_POST;

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
		header('location: weatherData.php');
		exit;
	}
}

$dbid = 41;
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

$tablesname = " weathersummaryUS";
$column = "Cat1";
$countiesResult = $admin->getDistinctColumnValuesUniversal($tablesname , $column);
$allCounties = $dbDatabase->getAll($countiesResult);

foreach($allCounties as $key => $value)
$filtervaluesJsonArray[] = array('id' => $value['Cat1'], 'name' => $value['Cat1']);
$filtervaluesJson = json_encode($filtervaluesJsonArray);

?>

<!-- container -->
<section id="container">
	<section id="inner-content" <?php if(mysql_num_rows($related_DB)<=0){ ?> class="conatiner-full" <?php } ?>>
		<h2><?php echo ucfirst(stripslashes($dbname)); ?></h2>
		<br />
		<!-- main data div -->
		<div class="categorie-data">
			<?php if(!empty($dbsource)){?><p><strong>Summary: </strong> <?php echo stripslashes($dbsource); ?></p><?php } ?>
			<div class="additional">
				<div id="add">
					<a title="Click to expand section" class="plus" id="togglebutton" href="#"></a>
					<a href="#">Additional background</a>
				</div>
				<!-- Hide - show div -->
				<div class="additional-deatil" id="additional-deatil">
					<p><?php echo ucfirst($description); ?> </p>
					<p><?php echo ucfirst($miscellaneous); ?> </p>
				</div>
				<!-- / Hide - show div -->
				<!-- Time -->
				<div class="clear"></div>
				<!-- /Time -->
			</div>
			
			<?php if(!empty($db_geographic)) { ?>
				<p class="pB5"><strong>Geographic Coverage: </strong> <?php echo stripslashes($db_geographic); ?></p> 
			<?php } ?>
			<?php if(!empty($db_periodicity)) { ?>
				<p class="pB5"><strong>Periodicity: </strong> <?php echo stripslashes($db_periodicity); ?></p> 
			<?php } ?>
			<?php if(!empty($db_dataseries)) { ?>
				<p class="pB5"><strong>Series Begins: </strong> <?php echo stripslashes($db_dataseries); ?></p> 
			<?php } ?>
			<?php if(!empty($db_datasource)) { ?>
				<p class="pB5"><strong>Data Source: </strong> <?php echo stripslashes($db_datasource); ?></p> 
			<?php } ?>

			<div class="time-div">
				<p>
					<b>Updated:</b><?php echo date('M. d, Y', strtotime($dateupdated)); ?> <?php if($nextupdate!='0000-00-00'){ ?>
					<span class="pL20"><b>Next update:</b> <?php echo date('M. d, Y', strtotime($nextupdate)); ?> </span> <?php } ?>
				</p>
			</div>

			<form method="post" id="frmPost" name="frmPost" action="" novalidate="novalidate">
				<!-- form -->
				<input type="hidden" name="session_setter" value="weather" />

				<div class="form-div">
					<p>
					   <span class="choose"><?php if(isset($lang['lbl_choose_counties_weather'])){ echo $lang['lbl_choose_counties_weather']; } ?></span>&nbsp;&nbsp;
						<?php if(isset($lang['lbl_coutnies_A_Z'])){ echo $lang['lbl_coutnies_A_Z']; } ?>
					</p>
					<div class="table-div">
						<input type="text" id="search_criteria_duplicates" name="us_states"/>
					</div>
				  </div>
				<!-- /form -->

				<div class="pB10" id="citiesDataLoad">
				</div>

				<script type="text/javascript">
				$(document).ready(function() {							
					
					$("#search_criteria_duplicates").tokenInput(<?php echo $filtervaluesJson; ?>, { preventDuplicates: true,
						
						onAdd: function (item) {
							var states = jQuery('#search_criteria_duplicates').val();
							//jQuery('#citiesDataLoad').html('Loading Cities Data Please Wait');
							loader_show();
							jQuery('#timePeriod').hide();
							jQuery('#submitButtons').hide();
							jQuery.ajax({
								url: '<?php echo URL_SITE; ?>/statsca/community/weatherAjax.php',
								type: 'post',
								data: 'dbid=<?php echo $dbid; ?>&states='+states,
								success: function(dataresult){
									//jQuery('#citiesDataLoad').html('');
									loader_unshow();
									var obj = jQuery.parseJSON(dataresult);

									if(obj.error == "0"){
										jQuery('#citiesDataLoad').html(obj.data);
										jQuery('#timePeriod').show();
										jQuery('#submitButtons').show();
									}else {
										jQuery('#citiesDataLoad').html('');
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
								url: '<?php echo URL_SITE; ?>/statsca/community/weatherAjax.php',
								type: 'post',
								data: 'dbid=<?php echo $dbid; ?>&states='+states,
								success: function(dataresult){
									loader_unshow();
									var obj = jQuery.parseJSON(dataresult);
									if(obj.error == 0){
										jQuery('#citiesDataLoad').html(obj.data);
										jQuery('#timePeriod').show();
										jQuery('#submitButtons').show();
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

						<?php if(isset($columns['syear']) && isset($columns['eyear']) && $columns['syear']!='' && $columns['eyear']!=''){ ?>
						<div class="time-select">
							<label for="smonth">Start Year</label>
							<br />
						   <select id="syear" size="1" name="syear">
								<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
								<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
								<?php } ?>
							
							</select>
						</div>
						<?php } ?>

						<div class="time-select">
							<label for="smonth">End Month</label>
							<br />
							<select id="emonth" size="1" name="emonth">
								<?php for($i=$columns['smonth'];$i<=$columns['emonth'];$i++){ ?>
								<option value="<?php echo $i; ?>" <?php if($i == $columns['emonth']){ echo "selected='selected'"; } ?> ><?php echo $months[$i]; ?></option>
								<?php } ?>
							</select>
						</div>

						<?php if(isset($columns['syear']) && isset($columns['eyear']) && $columns['syear']!='' && $columns['eyear']!=''){ ?>
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
		