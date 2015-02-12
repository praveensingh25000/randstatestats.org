<?php
/******************************************
* @Modified on Dec 26, 2012,Jan 24 2013
* @Package: Rand
* @Developer: Saket Bisht,Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/header.php";

if(isset($_SESSION['data'])) { 	unset($_SESSION['data']); }

$admin = new admin();
$user = new user();
$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(!isset($_GET['id'])) {
	$id=36;
	$_GET['id']= trim(base64_encode($id));
}

if(isset($_POST['getresults'])){

	$_SESSION['searchedfields'] = $_POST;

	if(isset($_SESSION['user']['email']) && $_SESSION['user']['email'] !='')					 
		$user_email=$_SESSION['user']['email'];
	else
		$user_email=$_SESSION['user']['username'];
	
	$userDetail		=	$user->selectUserProfile($user_email);
	$validity_on	=	$admin->Validity($userDetail['id'],$user_email);
	
	if(isset($validity_on) && $validity_on == '0') {
		$_SESSION['msgsuccess'] = '0';
		header('location: plansubscriptions.php');
		exit;
	}else{
		header('location: showSearchedData.php');
		exit;
	}
}

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(base64_decode($_GET['id']));
	$databaseDetail = $admin->getDatabase($dbid);
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);
		$table		= stripslashes($databaseDetail['table_name']);
	}else{
		//header('location: databases.php');
	}

	$related_DB = $admin->getAllDatabaseRelatedDatabases($dbid);
	
}else{
	header('location: index.php');
}
$all_search_criteria = $admin->selectAllSearchCriteria($dbid);
$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);
?>
 <!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">

	
		<!-- left side -->
		<div class="containerL">
			<h1><?php echo ucfirst($dbname); ?></h1>
			<p>
				<strong>Contains:</strong> <?php echo $dbsource; ?>
			</p>
			<!-- -->
			<div class="additional">
				<div id="add">
				<a title="Click to expand section" class="plus" id="togglebutton" href="#"></a>
				<a href="#">Additional background</a></div>
				<div class="content-main" id="content" style="background: none repeat scroll 0% 0% rgb(255, 255, 204); display: none;">
					<p><?php echo ucfirst($description); ?> </p>
					<p><?php echo ucfirst($miscellaneous); ?> </p>
				</div>
			</div>
			<div style="clear: both; padding: 15px 0;">
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
				<legend style="background: #cccccc; font-size: 14px; padding: 5px;">Set your Search Criteria</legend>
			
				<form method="post" id="mainsearchformPost" name="mainsearchformPost" action="">

				<?php foreach($all_search_criteria as $keymain => $search_content) { ?>
					<p>
						<b><?php echo $search_content['label_name']?></b>&nbsp;&nbsp;
						
						<?php if($search_content['allow_all_values'] == 'Y'){?>

							<input id="search_criteria_duplicates_all-<?php echo $search_content['id'];?>" type="checkbox" value="Y" name="allow_all[<?php echo $search_content['id'];?>]" /> All
							
							<?php
							if($search_content['control_type'] == 'select'){
							?>
							<script type="text/javascript">
							$(document).ready(function() {							
								jQuery("#search_criteria_duplicates_all-<?php echo $search_content['id'];?>").click(function(){
								
									if(jQuery(this).is(":checked")){
										jQuery(".search_criteria_duplicates_<?php echo $search_content['id'];?>").attr('disabled', 'true');

										jQuery("#token-input-search_criteria_duplicates_<?php echo $search_content['id'];?>").addClass('token-input-disabled');
									} else {
										jQuery("#token-input-search_criteria_duplicates_<?php echo $search_content['id'];?>").removeClass('token-input-disabled');
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
					<div style="padding: 10px 0;">

						<?php if($search_content['control_type'] == 'select') { ?>
						
							<input class="field_required_div_<?php echo $keymain;?> search_criteria_duplicates_<?php echo $search_content['id'];?>" type="text" id="search_field_required_div_<?php echo $keymain;?>" name="field[<?php echo $search_content['id'];?>]" />
									
							<?php
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
									$(".search_criteria_duplicates_<?php echo $search_content['id'];?>").tokenInput(<?php echo $filtervaluesJson; ?>, { preventDuplicates: true });
								});							
								</script>						
							<?php } else { ?>
								<script type="text/javascript">
								$(document).ready(function() {							
									$(".search_criteria_duplicates_<?php echo $search_content['id'];?>").tokenInput("<?php echo URL_SITE; ?>/searchForm.php?table=<?php echo $search_content['belongs_to'];?>&column=<?php echo $search_content['coloum_name'];?>", {
											preventDuplicates: true,
											//method: 'POST',
											animateDropdown: false
										
									});
								});								
								</script>
							<?php } ?>

							<script type="text/javascript">
							<!--
								$(document).ready(function() {
								$("#token-input-search_field_required_div_<?php echo $keymain;?>").click(function(){
									jQuery(".fieldrequired").remove();
								});							
							});
							//-->
							</script>

						<?php } else if($search_content['control_type'] == 'radio'){ ?>

						<input id="search_field_required_div_<?php echo $keymain;?>" class="field_required_div_<?php echo $keymain;?> search_criteria_duplicates_<?php echo $search_content['id'];?>" type="radio" name="field[<?php echo $search_content['id'];?>]" />

						<script type="text/javascript">
						<!--
							$(document).ready(function() {
							$(".search_criteria_duplicates_<?php echo $search_content['id'];?>").click(function(){
								jQuery(".fieldrequired").remove();
							});							
						});
						//-->
						</script>
						
						<?php } else if($search_content['control_type'] == 'checkbox'){ 
							
							$tablename = $search_content['belongs_to'];
							$column = $search_content['coloum_name'];
							//$searchFieldData = $admin->getTableData($tablename);

							$searchFieldData = $admin->getTableColumnData($tablename, $column);
							
							if(count($searchFieldData)>0){?>
								<div style="height: 190px;overflow-y:scroll;border: 1px solid #cccccc;">
									<table cellpadding="4" cellspacing="4">
										<?php
										$arraySearchField = array_chunk($searchFieldData, 4);
										foreach($arraySearchField as $keySearchField => $tableRows) { ?>
											<tr>
												<?php 
												foreach($tableRows as $keySearchField => $tableRow)
												{ ?>
													<td>
														<input id="search_field_required_div_<?php echo $keymain;?>" type="checkbox" class="field_required_div_<?php echo $keymain;?> search_criteria_duplicates_<?php echo $search_content['id'];?>" name="field[<?php echo $search_content['id'];?>][]" value="<?php echo $tableRow[$column]; ?>"/>&nbsp;<?php echo $tableRow[$column]; ?>
													</td>
												<?php } ?>
											</tr>
										<?php } ?>
									</table>
								</div>
								<script type="text/javascript">
								<!--
									$(document).ready(function() {
									$(".search_criteria_duplicates_<?php echo $search_content['id'];?>").click(function(){
										jQuery(".fieldrequired").remove();
									});							
								});
								//-->
								</script>
								<?php } ?>
						<?php } ?>
					</div>
					<?php } ?>


					<?php if(isset($timeIntervalSettings) && $timeIntervalSettings['time_format'] == 'SY-EY'){							
						$time_format = $timeIntervalSettings['time_format'];
						$columns = unserialize($timeIntervalSettings['columns']);
					?>
					<table summary="Select the start month and year, and the end month and year." border="0">
						<tbody>
							<tr>
								<th rowspan="2" style="padding-right: 15px;" valign="middle">Define a Time Period for Data</th>
								<th class="rblue"><label for="syear">Start Year</label></th>
								<th class="rblue"><label for="eyear">End Year</label></th>
							</tr>
							<tr>
								<td>
									<div id="begYear"><select id="syear" size="1" name="syear">
										<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
										<?php } ?>
									
									</select>
									</div>
								</td>
								<td>
									<div id="endYear"><select id="eyear" size="1" name="eyear">
										<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($i == $columns['eyear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
										<?php } ?>
									</select>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<br>

					<?php } ?>

					<?php if(isset($timeIntervalSettings) && $timeIntervalSettings['time_format'] == 'SM-SY-EM-EY'){ 	
							
						$time_format = $timeIntervalSettings['time_format'];
						$columns = unserialize($timeIntervalSettings['columns']);	
						
						$months = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 =>"Apr", 5 => "May", 6 => "June", 7 => "July", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
						
					?>
					<table summary="Select the start month and year, and the end month and year." border="0">
						<tbody>
							<tr>
								<th rowspan="2" style="padding-right: 15px;" valign="middle">Define a Time Period for Data</th>
								<th class="rblue"><label for="smonth">Start Month</label></th>
								<th class="rblue"><label for="syear">Start Year</label></th>
								<th class="rblue"><label for="emonth">End Month</label></th>
								<th class="rblue"><label for="eyear">End Year</label></th>
							</tr>
							<tr>
								<td>
								<div id="begYear"><select id="smonth" size="1" name="smonth">
									<?php for($i=$columns['smonth'];$i<=$columns['emonth'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['smonth']){ echo "selected='selected'"; } ?> ><?php echo $months[$i]; ?></option>
									<?php } ?>
								
								</select>
								</div>
								</td>
								<td>
									<div id="begYear"><select id="syear" size="1" name="syear">
										<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
										<?php } ?>
									
									</select>
									</div>
								</td>
								<td><div id="begYear"><select id="emonth" size="1" name="emonth">
									<?php for($i=$columns['smonth'];$i<=$columns['emonth'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['emonth']){ echo "selected='selected'"; } ?> ><?php echo $months[$i]; ?></option>
									<?php } ?>
								
								</select>
								</div></td>
								<td>
									<div id="endYear"><select id="eyear" size="1" name="eyear">
										<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($i == $columns['eyear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
										<?php } ?>
									</select>
									</div>
								</td>
								
							</tr>
						</tbody>
					</table>
					<br>

					<?php } ?>

					<?php if(isset($timeIntervalSettings) && $timeIntervalSettings['time_format'] == 'SQ-SY-EQ-EY'){ 					
						$time_format = $timeIntervalSettings['time_format'];
						$columns = unserialize($timeIntervalSettings['columns']);						
						$quaters = array(1 => "1st", 2 => "2nd", 3 => "3rd", 4 =>"4th");			
					?>
					<table summary="Select the start month and year, and the end month and year." border="0">
						<tbody>
							<tr>
								<th rowspan="2" style="padding-right: 15px;" valign="middle">Define a Time Period for Data</th>
								<th class="rblue"><label for="smonth">Start Quarter</label></th>
								<th class="rblue"><label for="syear">Start Year</label></th>
								<th class="rblue"><label for="emonth">End Quarter</label></th>
								<th class="rblue"><label for="eyear">End Year</label></th>
							</tr>
							<tr>
								<td>
								<div id="begYear"><select id="squater" size="1" name="squater">
									<?php for($i=$columns['squater'];$i<=$columns['equater'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['squater']){ echo "selected='selected'"; } ?> ><?php echo $quaters[$i]; ?></option>
									<?php } ?>
								
								</select>
								</div>
								</td>
								<td>
									<div id="begYear"><select id="syear" size="1" name="syear">
										<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($i == $columns['syear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
										<?php } ?>
									
									</select>
									</div>
								</td>
								<td><div id="begYear"><select id="equater" size="1" name="equater">
									<?php for($i=$columns['squater'];$i<=$columns['equater'];$i++){ ?>
									<option value="<?php echo $i; ?>" <?php if($i == $columns['equater']){ echo "selected='selected'"; } ?> ><?php echo $quaters[$i]; ?></option>
									<?php } ?>
								
								</select>
								</div></td>
								<td>
									<div id="endYear"><select id="eyear" size="1" name="eyear">
										<?php for($i=$columns['syear'];$i<=$columns['eyear'];$i++){ ?>
										<option value="<?php echo $i; ?>" <?php if($i == $columns['eyear']){ echo "selected='selected'"; } ?> ><?php echo $i; ?></option>
										<?php } ?>
									</select>
									</div>
								</td>
								
							</tr>
						</tbody>
					</table>
					<br>

					<?php } ?>
					
					<?php
					if(!empty($all_search_criteria)){
					?>

					<div class="submit1 submitbtn-div">
						<label for="submit" class="left">
							<input onclick="javascript: return checkLoginUser('<?php if(isset($_SESSION['user'])) echo "true"; else echo 'false'; ?>','<?php echo $all_search_criteria[0]['control_type'];?>','0');" value="Submit" name="getresults" class="submitbtn" type="submit">
							<input value="<?php echo $dbid; ?>" name="dbid" type="hidden" >
						</label>
						<label for="reset" class="right">
							<input id="reset" class="submitbtn" type="reset">
						</label>
					</div>
					<?php } ?>

				  </form>

				  <!-- DISPLAY POPUP LOGINFROM -->
				  <div id="popup_login_div" class="displaynone">
				  <?php require_once($DOC_ROOT.'login_popup.php');?>
				  </div>
				  <!-- DISPLAY POPUP LOGINFROM -->

				</fieldset>
			</div>
		</div>
		<!-- left side -->
		<!-- right side -->
		<aside class="containerR">
			<h2>Related database</h2>
			<ul>
				<?php if(mysql_num_rows($related_DB)>0){ while($r_DB = mysql_fetch_assoc($related_DB)) {
					$db_detail = $admin->getDatabase($r_DB['related_database_id']);	
				?>
				<li><a href="dbDetails.php?id=<?php echo base64_encode($db_detail['id']);?>"><?php echo ucfirst($db_detail['db_name']);?></a></li>
				<?php } } else { echo '<li>No Related Database Found</li>';} ?>
				
			</ul>
		</aside>
		<!-- /right side -->


		
	
<!-- /container -->

</div>
		
</section>
<?php
include_once $basedir."/include/footer.php";
?>