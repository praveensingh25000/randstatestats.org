<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";

checkSession(true);

$admin = new admin();

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();

if(isset($_POST['updateColumnsSettings'])){
	
	$dbid = $_POST['dbid'];

	if(isset($_POST['column_names']) && count($_POST['column_names']) >0){
		foreach($_POST['column_names'] as $tablename => $tableColumns){
			foreach($tableColumns as $columnname => $columndisplayname){
				if($columndisplayname!=''){
					$displaySettingsId = $admin->insertDisplaySettings($tablename, $columnname, $columndisplayname, $dbid);
				} else {
					$displaySettingsId = $admin->deleteDisplaySettings($tablename, $columnname, $dbid);
				}
			}
		}
	}

	header('location:'.URL_SITE.'/admin/columnSettings.php?tab=7&id='.base64_encode($dbid));
}

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(stripslashes(base64_decode($_GET['id'])));
	$databaseDetail = $admin->getDatabase($dbid);
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);
		

		################################ SELECTING ALL TABLES RELATED TO DB #########################################

		$allDbTables = $admin->getDatabaseTables($dbid);
		
		$tableIds = "";
		foreach($allDbTables as $tablekey => $tabledetail){
			$tableIds .= $tabledetail['table_name'].",";
		}
		
		$tableIds = substr($tableIds, 0, -1);

		################################ SELECTING ALL TABLES RELATED TO DB #########################################
		if(empty($allDbTables))
		{
			$_SESSION['msgerror'] = "Please associate tables to '$dbname' ";
			header('location:'.URL_SITE.'/admin/database.php?tab=3&action=edit&id='.base64_encode($dbid));
		}
		if(isset($allDbTables[0]['table_name']))
		{
			$table = stripslashes($allDbTables[0]['table_name']);
		}
		else
		{
			 $table		= stripslashes($databaseDetail['table_name']);	// have to remove this When all data of DB tables goes to datasebae_tables
		}
	}else{
		header('location: databases.php');
	}
}else{
	header('location: databases.php');
}

if(isset($_GET['table']) && $_GET['table']!=''){		// if only database is selected.
	 $table = trim(stripslashes(base64_decode($_GET['table'])));	
}

$timeIntervalSettings = $admin->getTimeIntervalSettings($dbid);


?>


 <!-- container -->
<section id="container">
	 <!-- main div -->
	 <div class="main-cell">

		<aside class="containerRadmin">
			<?php include_once $basedir."/include/adminLeft.php"; ?>
		</aside>

		<!-- left side -->
		<div class="containerLadmin">
			<div style="clear: both; padding: 15px 0;">
				<fieldset style="border: 1px solid #cccccc; padding: 10px;">
					<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Time Interval Settings for '".$databaseDetail['db_name']."'"; } ?> </legend>
					<?php if(isset($databaseDetail) && !empty($databaseDetail)){ ?>
					<div>
						<a <?php if(isset($_GET['tab']) && $_GET['tab']=='1') { echo 'class="active"';}?> href="database.php?tab=1&action=edit&id=<?php echo base64_encode($dbid); ?>">General Details</a>&nbsp;&nbsp;<a <?php if(isset($_GET['tab']) && $_GET['tab']=='2') { echo 'class="active"';}?> href="database.php?tab=2&action=edit&id=<?php echo base64_encode($dbid); ?>">Graphical Interface</a>&nbsp;&nbsp;<a <?php if(isset($_GET['tab']) && $_GET['tab']=='3') { echo 'class="active"';}?> href="database.php?tab=3&action=edit&id=<?php echo base64_encode($dbid); ?>">Associated Table</a>&nbsp;&nbsp;
						
							<a <?php if(isset($_GET['tab']) && $_GET['tab']=='4') { echo 'class="active"';}?> href="browse.php?tab=4&id=<?php echo base64_encode($dbid); ?>">Browse Data</a>&nbsp;&nbsp;
							<a <?php if(isset($_GET['tab']) && $_GET['tab']=='5'){ echo 'class="active"';}?> href="searchCriteria.php?tab=5&id=<?php echo base64_encode($dbid); ?>">Search Criteria</a>&nbsp;&nbsp;

							<a <?php if(isset($_GET['tab']) && $_GET['tab']=='6'){ echo 'class="active"';}?> href="timeInterval.php?tab=6&id=<?php echo base64_encode($dbid); ?>">Time Interval</a>&nbsp;&nbsp;

							<a <?php if(isset($_GET['tab']) && $_GET['tab']=='7'){ echo 'class="active"';}?> href="columnSettings.php?tab=7&id=<?php echo base64_encode($dbid); ?>">Column Display Settings</a>&nbsp;&nbsp;
					
						<hr><br/>
					</div>
					<?php } ?>


					<form method="post" action="" id="frmTimeInterval">
					

						<div class="pB10">
							<label class="pB10" style="display:block"><b>When on the search results page table headers for columns to be used instead of actual column names</b></label>
							<?php
							foreach($allDbTables as $tablekey => $tabledetail){
								$tablesResultColumn = $admin->showColumns($tabledetail['table_name']);
								$totalColumns = $db->count_rows($tablesResultColumn);
								$tableColumns = $db->getAll($tablesResultColumn);	

								if($totalColumns >0){
							?>
								<fieldset style="border: 1px solid #cccccc; padding: 10px;" class="mB10">
									<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php echo $tabledetail['table_name']; ?></legend>
									<table cellspacing="0" cellpadding="6" border="1" class=" collapse" id="">
										<tr>
											<th bgcolor="#eeeeee">Column Name</th>
											<th bgcolor="#eeeeee">Display Name</th>
										</tr>

										<?php foreach($tableColumns as $keyField => $columnDetail){ 
											
												$columnSettings = $admin->getColumnDisplaySettings($dbid, $tabledetail['table_name'], $columnDetail['Field']);
												$txt = (isset($columnSettings['display_name']) && $columnSettings['display_name']!= $columnDetail['Field'])?$columnSettings['display_name']:'';
										?>
										<tr>
										<td ><?php echo $columnDetail['Field']; ?></td>
										<td ><input type="text" name="column_names[<?php echo $tabledetail['table_name']; ?>][<?php echo $columnDetail['Field']; ?>]" value="<?php echo $txt; ?>" /></td>
										</tr>
										<?php } ?>
										
									</table>
				
								</fieldset>
							<?php
								}
							}
							?>
							<br/>
							<label for="columns[tablename]" generated="true" class="error" style="display:none;">This field is required.</label>
						</div>

						<div class="submit1 submitbtn-div">
							<label for="submit" class="left">
							<?php if(isset($databaseDetail) && !empty($databaseDetail)){ ?>
								<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
								<input type="submit" value="Submit" name="updateColumnsSettings" class="submitbtn" >
								<?php } ?>
							</label>
							<label for="reset" class="right">
								<input type="reset" id="reset" class="submitbtn">
							</label>
						</div>

					</form>


				</fieldset>
			</div>

		 </div>
		<!-- left side -->

		
	</div>
		
</section>
<!-- /container -->
<?php 
include_once $basedir."/include/adminFooter.php";
?>


