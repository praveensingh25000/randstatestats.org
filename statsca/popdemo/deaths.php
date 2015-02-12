<?php
/******************************************
* @Modified on Nov 05, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/popdemo/deaths.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

if(!isset($_SESSION['databaseToBeUse']) || ( isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse'] != 'rand_california')){
	$_SESSION['errormsg'] = "Unauthorized access to the database or incorrect database selected for this.";
	header('location: '.URL_SITE.'/index.php');
	exit;
}


$tablesname		= "deaths";
$tablesnamecat	= "deaths_cats";
$tablecounties	= "ca_counties";

$tablezip1		= "deathszip1";
$tablezip2		= "deathszip2";

$admin  = new admin();
$user   = new user();

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['getresults'])){

	$_SESSION['deaths'] = $_POST;

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
			header('location: deathsData.php?dbc='.$dbnameurl.'');			
			exit;
		} else {
		header('location: deathsData.php');
		exit;
		}
	}
}

$dbid = 75;
$databaseDetail = $admin->getDatabase($dbid);
if(!empty($databaseDetail)){
	
	$dbname				= stripslashes($databaseDetail['db_title']);
	$dbsource			= stripslashes($databaseDetail['db_datasource']);
	$description		= stripslashes($databaseDetail['db_description']);
	$miscellaneous		= stripslashes($databaseDetail['db_misc']);
	$nextupdate			= stripslashes($databaseDetail['db_nextupdate']);
	$table				= stripslashes($databaseDetail['table_name']);
	$db_geographic		= stripslashes($databaseDetail['db_geographic']);
	$db_periodicity		= stripslashes($databaseDetail['db_periodicity']);
	$db_dataseries		= stripslashes($databaseDetail['db_dataseries']);
	$db_datasource		= stripslashes($databaseDetail['db_source']);
	$dateupdated		= stripslashes($databaseDetail['date_added']);
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
$stateToName = $dbDatabase->getAll($allCategoryDetail_res);

foreach($stateToName as $key => $value)
$filtervaluesJsonArray[] = array('id' => $value['catcode'], 'name' => $value['catname']);
$filtervaluesJson = json_encode($filtervaluesJsonArray);

unset($stateToName);

$allcountiesDetail_res  = $admin->getTableDataUniversal($tablecounties);
$countyToName = $dbDatabase->getAll($allcountiesDetail_res);

foreach($countyToName as $key => $value)
$filtervaluesJsonCountyArray[] = array('id' => $value['areacode'], 'name' => $value['areaname']);

$filtervaluesCountyJson = json_encode($filtervaluesJsonCountyArray);

unset($countyToName);

$filtervaluesJsonZipArray = array();

$allzipDetail_res  = $admin->getDistinctColumnValuesUniversal($tablezip1 , 'Area', "");
$zipToName = $dbDatabase->getAll($allzipDetail_res);

foreach($zipToName as $key => $value)
$filtervaluesJsonZipArray[] = array('id' => $value['Area'], 'name' => $value['Area']);

$allzipDetail_res  = $admin->getDistinctColumnValuesUniversal($tablezip2 , 'Area', "");
$zipToName = $dbDatabase->getAll($allzipDetail_res);

foreach($zipToName as $key => $value)
$filtervaluesJsonZipArray[] = array('id' => $value['Area'], 'name' => $value['Area']);

$filtervalueszipsJson = json_encode($filtervaluesJsonZipArray);
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

			<form method="post" id="frmPost" name="frmPost" action="" >
				
					<!-- form -->
					<input type="hidden" name="session_setter" value="deaths">
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_please_enter_a_category'])){ echo $lang['lbl_please_enter_a_category']; } ?> </span>
						</p>
						<div class="table-div">
							<input type="text" id="search_criteria_duplicates" name="categories" class="required"/>
						</div>
					</div>

					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_please_enter_an_area'])){ echo $lang['lbl_please_enter_an_area']; } ?> </span>
						</p>
						<div class="table-div">
							<input type="text" id="area_search_criteria_duplicates_area" name="areas" class="required"/>
						</div>
					</div>

					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_enter_zip_codes'])){ echo $lang['lbl_enter_zip_codes']; } ?> </span>
						</p>
						<div class="table-div">
							<input type="text" id="zip_search_criteria_duplicates_zip" name="zips" class="required"/>
						</div>
					</div>

					<!-- /form -->
					
					<div class="pB10" id="cities_data_load"></div>

					<script type="text/javascript">
					$(document).ready(function() {							
						$("#search_criteria_duplicates").tokenInput(<?php echo $filtervaluesJson; ?>, {		
							preventDuplicates: true,	
						});
						
						$("#area_search_criteria_duplicates_area").tokenInput(<?php echo $filtervaluesCountyJson; ?>, {		
							preventDuplicates: true,	
						});

						$("#zip_search_criteria_duplicates_zip").tokenInput(<?php echo $filtervalueszipsJson; ?>, {		
							preventDuplicates: true,	
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

						<!---------------- TIME INTERVAL SETTINGS -------------------->

						<div class="submitbtn-div" id="submitButtons">
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
<?php
include_once $basedir."/include/footerHtml.php";
?>