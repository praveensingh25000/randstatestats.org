<?php
/******************************************
* @Modified on April 19, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* live Site URL For This Page:		 http://statestats.rand.org/stats/economics/inf.html
********************************************/
$basedir=dirname(__FILE__)."../../..";
include_once $basedir."/include/headerHtml.php";

$admin = new admin();
$user = new user();

$_GET['id'] = base64_encode(14);

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

$form_type = 'one_stage';

if(isset($_POST['getresults'])){

	$_SESSION['searchedfieldsonestage'] = $_POST;

	if(isset($_REQUEST['dbnameurl']) && $_REQUEST['dbnameurl']!='') {
		$dbnameurl = $_REQUEST['dbnameurl'];
	}	
	$user_id		=	$_POST['user_id'];	
	$userDetail		=	$user->getUser($user_id);	
	$validity_on	=	$admin->Validity(trim($userDetail['id']),trim($userDetail['email']));
	
	if(isset($validity_on) && $validity_on == '0') {
		$_SESSION['msgsuccess'] = '0';
		header('location: '.$URL_SITE.'plansubscriptions.php');
		exit;
	}else{
		if(isset($dbnameurl) && $dbnameurl!='') {
			header('location: infData.php?dbc='.$_REQUEST['dbc'].'');			
			exit;
		} else {
			header('location: infData.php');
			exit;
		}
	}
}

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(base64_decode($_GET['id']));
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

	}else{

		$_SESSION['msgalert'] = 23;
		header('location: index.php');
	}

	$related_DB_cat = $admin->getDatabaseCategories($dbid);
	$related_DB		= $admin->getAllRelatedDatabases($dbid,$related_DB_cat['category_id']);
	
}else{
	$_SESSION['msgalert'] = 23;
	header('location: index.php');
}
$all_search_criteria = $admin->selectAllSearchCriteria($dbid);
$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);
?>

 <!-- container -->
<section id="container">
	<section id="inner-content" <?php if(mysql_num_rows($related_DB)<=0){ ?> class="conatiner-full" <?php } ?>>
		<h2><?php echo ucfirst(stripslashes($dbname)); ?></h2>
		<br />
		<!-- main data div -->
		<div class="categorie-data">

			<?php include($DOC_ROOT."/basicInfo.php"); ?>

			<form method="post" id="mainsearchformPost" name="mainsearchformPost" action="">

				<?php foreach($all_search_criteria as $keymain => $search_content) { ?>

					<div class="form-div">
						<p>
							<span class="choose"><?php echo $search_content['label_name']?></span>&nbsp;&nbsp;
							<?php 
							if($search_content['allow_all_values'] == 'Y'){?>

								<input id="search_criteria_duplicates_all-<?php echo $search_content['id'];?>" type="checkbox" value="Y" name="allow_all[<?php echo $search_content['id'];?>]" /> All
								
								<?php
								if($search_content['control_type'] == 'select'){
								?>
								<script type="text/javascript">
								$(document).ready(function() {							
									jQuery("#search_criteria_duplicates_all-<?php echo $search_content['id'];?>").click(function(){
									
										if(jQuery(this).is(":checked")){
											
											jQuery(".search_criteria_duplicates_<?php echo $search_content['id'];?>").tokenInput("toggleDisabled");

											jQuery("#search_field_required_div_<?php echo $keymain;?>").removeClass("field_required_div_<?php echo $keymain;?>");

											jQuery("#search_field_required_div_<?php echo $keymain;?>").removeClass("required");
											
										
										} else {
											jQuery(".search_criteria_duplicates_<?php echo $search_content['id'];?>").tokenInput("toggleDisabled");
											jQuery("#search_field_required_div_<?php echo $keymain;?>").addClass("field_required_div_<?php echo $keymain;?>");
											jQuery("#search_field_required_div_<?php echo $keymain;?>").addClass("required");
										}

									});
								});
								</script>

								<?php } else if($search_content['control_type'] == 'checkbox'){ ?>

								<script type="text/javascript">
								$(document).ready(function() {							
									jQuery("#search_criteria_duplicates_all-<?php echo $search_content['id'];?>").click(function(){							
										if(jQuery(this).is(":checked")){
											jQuery(".search_criteria_duplicates_<?php echo $search_content['id'];?>").attr('disabled', 'true');			
										} else {
											jQuery(".search_criteria_duplicates_<?php echo $search_content['id'];?>").removeAttr('disabled');
										}
									});
								});
								</script>
								<?php } ?>
							<?php } ?>
						</p>
						<div class="table-div">
							<?php if($search_content['control_type'] == 'select') {	?>
							
							<div class="wdthpercent35 left">
								<input class="selectboxes required field_required_div_<?php echo $keymain;?> search_criteria_duplicates_<?php echo $search_content['id'];?>" type="text" id="search_field_required_div_<?php echo $keymain;?>" name="field[<?php echo $search_content['id'];?>]" />
							</div>

							<div class="wdthpercent20 left">&nbsp;&nbsp;
							
								<?php if($search_content['is_filter'] != 'Y'){ ?>
								<a id="viewall_<?php echo $keymain;?>" href="javascript:;">See a list</a>
								<script type="text/javascript">
								jQuery(document).ready(function(){
									jQuery('#viewall_<?php echo $keymain;?>').click(function(){
										window.open("<?php echo URL_SITE; ?>/allValues.php?table=<?php echo $search_content['belongs_to'];?>&column=<?php echo $search_content['coloum_name'];?>", 'popUpWindow','height=400, width=400, left=300, top=100, resizable=yes, scrollbars=yes, toolbar=yes, menubar=no, location=no, directories=no, status=yes');
									});
								});
								</script>
								<?php } else { 
									
									$filtervalues = unserialize($search_content['filtered_values']);
									$filtervaluesJsonArray = array();
									$filtervaluesStr = '';
									foreach($filtervalues as $key => $value) {
										if(!is_numeric(stripslashes($value)) && preg_replace('/[^a-zA-Z0-9_ -]/s', '', $value)) {				
											$filtervaluesStr .= '<p>'.str_replace("'","",$value)."</p>";	
										}
									}
									?>
									<a onclick="javascript: return popup_window('<?php echo $filtervaluesStr; ?>');" href="javascript:;">See a list</a>
								<?php } ?>

							</div>

							<?php

							if($search_content['type'] == 'single'){
								$tokenLimit = "1";
							} else {
								$tokenLimit = "null";
							}

							if($search_content['is_filter'] == 'Y' && $search_content['filtered_values']!=''){
								$filtervalues = unserialize($search_content['filtered_values']);
								$filtervaluesJsonArray = array();
								$filtervaluesJson = '';
								foreach($filtervalues as $key => $value)
								$filtervaluesJsonArray[] = array('id' => $value, 'name' => $value);
								$filtervaluesJson = json_encode($filtervaluesJsonArray);
								?>
								<script type="text/javascript">
								$(document).ready(function() {							
									$(".search_criteria_duplicates_<?php echo $search_content['id'];?>").tokenInput(<?php echo $filtervaluesJson; ?>, { 
										tokenLimit: <?php echo $tokenLimit; ?>,
										preventDuplicates: true,
										onAdd: function (item) {
											jQuery("#search_field_required_div_<?php echo $keymain;?>").removeClass("required");
										}
									});										
								});							
								</script>						
							<?php } else { ?>
								<script type="text/javascript">
								$(document).ready(function() {							
									$(".search_criteria_duplicates_<?php echo $search_content['id'];?>").tokenInput("<?php echo URL_SITE; ?>/searchForm.php?table=<?php echo $search_content['belongs_to'];?>&column=<?php echo $search_content['coloum_name'];?>", {
											tokenLimit: <?php echo $tokenLimit; ?>,
											preventDuplicates: true,
											//method: 'POST',
											animateDropdown: false,

											onAdd: function (item) {
												jQuery("#search_field_required_div_<?php echo $keymain;?>").removeClass("required");
											}
									});
									
								});								
								</script>
							<?php } ?>

							<script type="text/javascript">
							
								$(document).ready(function() {
								$("#token-input-search_field_required_div_<?php echo $keymain;?>").click(function(){
									jQuery(".fieldrequired").remove();
								});							
							});							
							</script>

						<?php } else if($search_content['control_type'] == 'radio'){ 

							$tablename = $search_content['belongs_to'];
							$column = $search_content['coloum_name'];
							
							$searchFieldData = array();
							if($search_content['is_filter'] == 'Y' && $search_content['filtered_values']!=''){
								$filtervalues = unserialize($search_content['filtered_values']);
								$filtervaluesJsonArray = array();
								$filtervaluesJson = '';
								foreach($filtervalues as $key => $value)
								$searchFieldData[] = array($column => $value);
							} else {							
								//$searchFieldData = $admin->getTableData($tablename);
								$searchFieldData = $admin->getTableColumnData($tablename, $column);
							}						
							
							if(count($searchFieldData)>0) { ?>						

									<table width="100%">
										<?php
										$arraySearchField = array_chunk($searchFieldData, 4);
										foreach($arraySearchField as $keySearchField => $tableRows) { ?>
											<tr>
												<?php 
												foreach($tableRows as $keySearchField => $tableRow)
												{ ?>
													<td>
														<input id="search_field_required_div_<?php echo $keymain;?>" type="radio" class="field_required_div_<?php echo $keymain;?> search_criteria_duplicates_<?php echo $search_content['id'];?>" name="field[<?php echo $search_content['id'];?>][]" value="<?php echo $tableRow[$column]; ?>"/>&nbsp;<?php echo $tableRow[$column]; ?>
													</td>
												<?php } ?>
											</tr>
										<?php } ?>
									</table>
					
								<script type="text/javascript">
							
									$(document).ready(function() {
									$(".search_criteria_duplicates_<?php echo $search_content['id'];?>").click(function(){
										jQuery(".fieldrequired").remove();
									});							
								});
							
								</script>
								<?php } ?>
						
						<?php } else if($search_content['control_type'] == 'checkbox'){
							
							$tablename = $search_content['belongs_to'];
							$column = $search_content['coloum_name'];
							
							$searchFieldData = array();
							if($search_content['is_filter'] == 'Y' && $search_content['filtered_values']!=''){
								$filtervalues = unserialize($search_content['filtered_values']);
								$filtervaluesJsonArray = array();
								$filtervaluesJson = '';
								foreach($filtervalues as $key => $value)
								$searchFieldData[] = array($column => $value);
							} else {			
								
								//$searchFieldData = $admin->getTableData($tablename);
								$searchFieldData = $admin->getTableColumnData($tablename, $column);
							}
							
							if(count($searchFieldData)>0) {?>							

									<table width="100%">
										<?php
										$arraySearchField = array_chunk($searchFieldData, 4);
										foreach($arraySearchField as $keySearchField => $tableRows) { ?>
											<tr>
												<?php 
												foreach($tableRows as $keySearchField => $tableRow) {
													if($tableRow[$column] != ''){ ?>				
													<td>
														<input id="search_field_required_div_<?php echo $keymain;?>" type="checkbox" class="field_required_div_<?php echo $keymain;?> search_criteria_duplicates_<?php echo $search_content['id'];?>" name="field[<?php echo $search_content['id'];?>][]" value="<?php echo $tableRow[$column]; ?>"/>&nbsp;<?php echo $tableRow[$column]; ?>
													</td>
												<?php } ?>
											  <?php } ?>
											</tr>
										<?php } ?>
									</table>
					
								<script type="text/javascript">								
									$(document).ready(function() {
									$(".search_criteria_duplicates_<?php echo $search_content['id'];?>").click(function(){
										jQuery(".fieldrequired").remove();
									});							
								});								
								</script>
								<?php } ?>
						<?php } ?>
						</div>
					</div>
					<!-- /form -->
					<div class="clear"></div>
				<?php } ?>

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
						   <select id="syear" size="1" name="syear" onchange="enablesubmit()">
							<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
							<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
							<?php } ?>
							</select>
						</div>
					   
						<div class="time-select">
							<label for="smonth">End Year</label>
							<br />
							<select id="eyear" size="1" name="eyear" onchange="checkyear()">
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
						   <select id="syear" size="1" name="syear" onchange="enablesubmit()">
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
							<select id="eyear" size="1" name="eyear" onchange="checkyear()">
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
								<select id="syear" size="1" name="syear" onchange="enablesubmit()">
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
								<select id="eyear" size="1" name="eyear" onchange="checkyear()">
									<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['eyear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
									<?php } ?>
								</select>
							</div>
							<div class="clear"> </div>

					<?php } ?>
					
					<?php
					if(!empty($all_search_criteria)){
					?>
					
					<div class="submitbtn-div">
						<input onclick="javascript: return checkLoginUser('<?php echo $form_type;?>','<?php if(isset($_SESSION['user'])) echo "true"; else echo 'false'; ?>','<?php echo $all_search_criteria[0]['control_type'];?>','0');" value="Submit" name="getresults" class="submitbtn" type="submit" id="submittypebtn">
						<input value="<?php echo $dbid; ?>" name="dbid" type="hidden">					
						<?php if(isset($_GET['dbc']) && $_GET['dbc']!='') { ?>
						<input value="<?php echo $_GET['dbc'];?>" name="dbnameurl" type="hidden">	
						<?php } ?>
						<input type="submit" class="right" name="" value="Reset">
					</div>
					<?php } ?>
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
