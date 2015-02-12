<?php
/******************************************
* @Modified on Feb 13, 2013
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* @live URL: http://ca.rand.org/stats/community/crimes.html
********************************************/

$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$tablesname     = "crime";
$tablesnameArea = "crime_areas";
$tablesnameCats = "crime_cats";

$admin						= new admin();
$user						= new user();

$dbname						= $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['getresults'])){

	$_SESSION['crimes'] = $_POST;

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
		header('location: crimesData.php');
		exit;
	}
}

$dbid = 205;
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

$popcontent='';
$allCategoryDetail_res  = $admin->getDistinctColumnValuesUniversal($tablesnameArea , $column='areaname', $columns = "areacode", $limit = "");
$allCategoryDetail = $dbDatabase->getAll($allCategoryDetail_res);
foreach($allCategoryDetail as $key => $value){
	$popcontent .= '<p>'.str_replace("'",'',$value['areaname'])."</p>";
	$filtervaluesJsonArray[] = array('id' => $value['areacode'], 'name' => $value['areaname']);
}
$filtervaluesJson = json_encode($filtervaluesJsonArray);

//echo "<pre>";print_r($allCategoryDetail);echo "</pre>";
?>

<!-- container -->
<section id="container">
	<section id="inner-content" <?php if(isset($related_DB) && mysql_num_rows($related_DB) <= 0) { ?> class="conatiner-full" <?php } ?>>
		<h2><?php echo ucfirst(stripslashes($dbname)); ?></h2><br />

		<!-- main data div -->
		<div class="categorie-data">
			
			<!-- Form Basic Info Details -->
			<?php include($DOC_ROOT."/basicInfo.php"); ?>
			<!-- Form Basic Info Details -->


			<form method="post" id="frmPost" name="frmPost" action="" novalidate="novalidate">
				
					<!-- form -->
					<input type="hidden" name="session_setter" value="crimes">
					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_all_crimes'])){ echo $lang['lbl_all_crimes']; } ?></span>
						</p>
						<div class="table-div">
							<table width="700" cellpadding="6" border="1" summary="" class="collapse">
								<tbody><tr>
								<th width="40%" class="thead">Crime</th>
								<th width="10%" class="thead">Number of offenses</th>
								<th width="20%" class="thead">Number of clearances (offsenses cleared by an arrest)</th>
								<th width="20%" class="thead">Clearance rates</th>
								</tr>
								</tbody>
							</table>
							

								<table width="700" cellpadding="6" border="1" summary="" class="collapse">

								<tbody><tr class="botbar">
								<td width="40%">Aggravated assault</td>
								<td width="10%" align="center">
								<input id="c01" type="checkbox" name="Category[]" class="required" value="90">
								</td>

								<td width="20%" align="center">
								<input id="c01a" type="checkbox" name="Category[]" value="5050">
								</td>

								<td width="20%" align="center">
								<input id="c01b" type="checkbox" name="Category[]" value="5150">
								</td>
								</tr>

								<tr class="botbar">
								<td width="40%">Arson</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="170">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5090">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5190">
								</td>
								</tr>

								<tr class="botbar">
								<td width="40%">Burglary</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="120">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5060">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5160">
								</td>
								</tr>

								<tr class="botbar">
								<td width="40%">California crime index</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="40">
								</td>

								<td width="20%" align="center">N/A</td>

								<td width="20%" align="center">N/A</td>
								</tr>

								<tr class="botbar">
								<td width="40%">FBI crime index</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="20">
								</td>

								<td width="20%" align="center">N/A</td>

								<td width="20%" align="center">N/A</td>
								</tr>

								<tr class="botbar">
								<td width="40%">Forcible rape</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="70">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5030">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5130">
								</td>
								</tr>

								<tr class="botbar">
								<td width="40%">Larceny-theft</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="150">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5080">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5180">
								</td>
								</tr>

								<tr class="botbar">
								<td width="40%">Motor vehicle theft</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="130">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5070">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5170">
								</td>
								</tr>

								<tr class="botbar">
								<td width="40%">Property crimes</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="110">
								</td>

								<td width="20%" align="center">N/A</td>

								<td width="20%" align="center">N/A</td>
								</tr>

								<tr class="botbar">
								<td width="40%">Robbery</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="80">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5040">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5140">
								</td>
								</tr>

								<tr class="botbar">
								<td width="40%">Violent crimes</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="50">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5010">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5110">
								</td>
								</tr>

								<tr class="botbar">
								<td width="40%">Willful homicide</td>
								<td width="10%" align="center">
								<input type="checkbox" name="Category[]" value="60">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5020">
								</td>

								<td width="20%" align="center">
								<input type="checkbox" name="Category[]" value="5120">
								</td>
								</tr>

								<tr class="botbar">
								<td width="40%">
								</td>
								<td width="10%" align="center">All of the above<br>
								<input type="checkbox" name="Category[]" value="90|170|120|40|20|70|130|110|80|50|60">

								</td>

								<td width="20%" align="center">All of the above<br>
								<input type="checkbox" name="Category[]" value="5050|5090|5060|5030|5080|5070|5040|5010|5020">
								</td>

								<td width="20%" align="center">All of the above<br>
								<input type="checkbox" name="Category[]" value="5150|5190|5160|5130|5180|5170|5140|5110|5120">
								</td>
								</tr>

							</tbody></table>

					
						</div>
					</div>

					<div class="form-div">
						<p>
						   <span class="choose"><?php if(isset($lang['lbl_please_choose_area'])){ echo $lang['lbl_please_choose_area']; } ?></span>
						</p>
						<div class="table-div">
							<div class="wdthpercent35 left">
								<input  class="required" type="text" id="search_criteria_duplicates" name="us_states"/>
							</div>
							<div class="pL30 left">&nbsp;&nbsp;<a id="viewall" href="javascript:;" onclick="javascript: return popup_window('<?php echo $popcontent; ?>');">See a list</a></div>
						</div>						

						<script type="text/javascript">
						$(document).ready(function() {							
					
							$("#search_criteria_duplicates").tokenInput(<?php echo $filtervaluesJson; ?>, { preventDuplicates: true });
						});
						</script>
					</div>					

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
					<!---------------- TIME INTERVAL SETTINGS -------------------->
				
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
