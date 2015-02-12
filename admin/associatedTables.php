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

$dbname = $dbsource = $description = $miscellaneous = $db_graph = $db_geographic = $db_dataseries = $db_nextupdate = $db_datasource = $db_periodicity = $db_url = $dbsourcelink = $notes = '';

$databaseCategories = $databaseRelatedDatabases = $databaseRelatedDatabasesAdmin = array();

if(isset($_GET['deletetable']) && $_GET['deletetable']!='' && isset($_GET['id']) && $_GET['id']!=''){
	$dbid = trim(base64_decode($_GET['id']));	
	$tablename = trim($_GET['deletetable']);	
	$admin->deleteAssociatedTable($dbid,  $tablename);
	header('location:associatedTables.php?tab=3&id='.base64_encode($dbid));
}

if(!isset($_GET['id']) || (isset($_GET['id']) && $_GET['id']=='')){
	header('location: databases.php');
}

if(isset($_GET['id']) && $_GET['id']!=''){
	
	$dbid = trim(base64_decode($_GET['id']));	
	$databaseDetail = $admin->getDatabase($dbid, true);
	$table = $admin->getDatabaseTables($dbid,true);
	if(!empty($databaseDetail)){
		
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);
		//$table[]		= stripslashes($databaseDetail['table_name']);
		$db_graph = explode(',',$databaseDetail['db_graph']);

		$db_geographic	= stripslashes($databaseDetail['db_geographic']);
		$db_dataseries	= stripslashes($databaseDetail['db_dataseries']);
		$db_nextupdate	= stripslashes($databaseDetail['db_nextupdate']);
		$db_periodicity	= stripslashes($databaseDetail['db_periodicity']);
		$db_datasource	= stripslashes($databaseDetail['db_title']);
		$dbsourcelink	= stripslashes($databaseDetail['db_sourcelink']);
		$notes	= stripslashes($databaseDetail['db_notes']);

		$db_url			= stripslashes($databaseDetail['url']);
		$is_static_form = $databaseDetail['is_static_form'];


		$databaseCategoriesResult = $admin->getAllDatabaseCategories($dbid);
		$dbtotal = $dbDatabase->count_rows($databaseCategoriesResult);
		$dbcategories = $dbDatabase->getAll($databaseCategoriesResult);
		foreach($dbcategories as $key => $dbcatDetail){
			$databaseCategories[] = $dbcatDetail['category_id'];
		}

		$databaseRelatedResult = $admin->getAllDatabaseRelatedDatabases($dbid);
		$dbReltotal = $dbDatabase->count_rows($databaseRelatedResult);
		$dbRelatedDatabases = $dbDatabase->getAll($databaseRelatedResult);
		if(count($dbRelatedDatabases) >0){
			foreach($dbRelatedDatabases as $key => $dbRelDetail){
				$databaseRelatedDatabases[] = $dbRelDetail['related_database_id'];
			}
		}

		$databaseRelatedResultAdmin = $admin->getAllDatabaseRelatedDatabasesAdmin($dbid);
		$dbReltotal = $dbDatabase->count_rows($databaseRelatedResultAdmin);
		$dbRelatedDatabasesAdmin = $dbDatabase->getAll($databaseRelatedResultAdmin);
		if(count($dbRelatedDatabases) >0){
			foreach($dbRelatedDatabasesAdmin as $key => $dbRelDetail){
				$databaseRelatedDatabasesAdmin[] = $dbRelDetail['related_database_id'];
			}
		}

	}
}

if(isset($_POST['updatetable'])){
	
	$dbid = $_POST['dbid'];
	if(isset($_POST['table']))
	{
		//$admin->deletePreviousTables($dbid);
		$insert_array = array();
		foreach($_POST['table'] as $table)
		{
			$tableddetail = $admin->checkTablesAlreadyExist($dbid, $table);
		
			if(empty($tableddetail) && $table!='')
			$insert_array[] = '("' .mysql_real_escape_string($table). '", "' . $dbid. '",now())';			
		}
		if(!empty($insert_array))
		$return = $admin->insertDBTables('searchform_tables', implode(',',$insert_array) , $dbid);
		if($return <=0 ){
			$_SESSION['msgsuccess'] = 'Records has been updated';	
		}
	} else {
		$_SESSION['msgerror'] = 'Please select the tables first';	
	}
	
	header('location:associatedTables.php?tab=3&&id='.base64_encode($dbid));
	exit;
}


if(isset($_GET['tab']) && $_GET['tab']>=1 && $_GET['tab']<=10){
	$tab = $_GET['tab'];
} else {
	$tab = 1;
}





if($tab == 3){
$tablesResult = $admin->showAllTables();
$totalTables = $dbDatabase->count_rows($tablesResult);
$tables = $dbDatabase->getAll($tablesResult);
}




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
			<legend style="background: #cccccc; font-size: 14px; padding: 5px;"><?php if(isset($databaseDetail) && !empty($databaseDetail)){ echo "Edit Database '".stripslashes($databaseDetail['db_name'])."'"; } else { echo "Add Database"; } ?> </legend>
				
				<?php include("formNavigation.php"); ?>
				
				<div id="associatedTablesDetails">
		
					<div <?php if(isset($_GET['subaction'])){ ?> style="display:none;" <?php } ?>>
						
						<?php if(isset($table) && count($table)>0){ ?>
						<div>
							<table width="100%" cellspacing="6" cellpadding="6">
								<tr>
									<td><a href="?tab=4&action=edit&subaction=createtable&id=<?php echo base64_encode($dbid); ?>">Create new table</a></td>
									<td><a href="#associateTables" id="attachTables" onclick="javascript: return showDiv('associateTables');">Attach another table</a></td>

									<?php if(isset($table) && count($table)>1){ ?>
									<td><a href="joinConditions.php?id=<?php echo base64_encode($dbid); ?>&tab=3">Set Join Conditions</a>
									</td>
									<?php } ?>

								</tr>
							</table>
						</div>
						<?php } else { ?>
						<div>
							<p>You can either select one of the pre-existing tables or <a href="?tab=4&action=edit&subaction=createtable&id=<?php echo base64_encode($dbid); ?>">Create a new table(s)</a>
						</div>
						<?php } ?>

						<?php if(count($table)>0){ ?>
						<div class="pT10">
						<table cellspacing="0" cellpadding="4" border="1" class="collapse" id="grid_view" width="100%">
						<tbody>
							<tr>
								<th bgcolor="#eeeeee">Table Name</th>
								<th bgcolor="#eeeeee">Actions</th>
							</tr>
							<?php foreach($table as $key => $tablename){ ?>
							<tr>
								<td><?php echo $tablename; ?></td>
								<td><a href="<?php echo URL_SITE; ?>/admin/columns.php?id=<?php echo base64_encode($dbid);?>=&table=<?php echo base64_encode($tablename);?>">View Structure</a>&nbsp;&nbsp;<a href="<?php echo URL_SITE; ?>/admin/browse.php?id=<?php echo base64_encode($dbid);?>=&table=<?php echo base64_encode($tablename);?>">Browse Data</a>
								
								<?php if($databaseDetail['is_static_form']!='Y'){ ?>
								&nbsp;&nbsp;<a href="?deletetable=<?php echo $tablename;?>&tab=3&action=edit&id=<?php echo base64_encode($dbid);?>" onclick="javascript: return confirm('Are you sure you want to delete?');">Delete Table</a>
								<?php } ?>
								
								</td>
							</tr>
							<?php } ?>
						</tbody>
						</table>
						</div>
						<?php } ?>
						
						<div  id="associateTables" style="padding: 10px 0;<?php if(isset($table) && count($table)>1){ ?> display:none; <?php } ?>" >
							<form id="frmAssociatedTables" name="frmAssociatedTables" method="post">
								<table width="100%" cellspacing="6" cellpadding="6">
									<tr>
										<td width="40%">
											<p><b><?php if(isset($table) && count($table)>0){ echo "All Tables"; } else { echo "Select Existing Table(s)"; } ?></b></p>
											<select name="" multiple size="15" id="chooseTables">
												<option value=""> -- Select Table --</option>
												<?php if($totalTables>0) { foreach($tables as $keyTable => $tablename){ 
												$col = "Tables_in_".$dbDatabase->DBDATABASE."";
													 if(isset($table) && !in_array($tablename[$col],$table)) { 
												?>
												<option value="<?php echo $tablename[$col]; ?>" ><?php echo $tablename[$col]; ?></option>
												<?php } } } ?>
											</select>
										</td>
										<td width="10%"><A HREF="#next" id="nextTable">&#62;&#62;</a><br/><br/><A HREF="#prev" id="prevTable">&#60;&#60;</a></td>
										<td width="40%">
											<p><b>Associated Tables</b></p>
											<select name="table[]" multiple size="15" id="selectedTables">
												<option value=""> -- Select Table From left Side --</option>
												<?php if($totalTables>0) { foreach($tables as $keyTable => $tablename){ 
												$col = "Tables_in_".$dbDatabase->DBDATABASE."";
												 if(isset($table) && in_array($tablename[$col],$table)) { 
												?>
												<option value="<?php echo $tablename[$col]; ?>" selected="selected"><?php echo $tablename[$col]; ?></option>
												<?php } } }?>
											</select>
										
										</td>
									</tr>
								</table>

								<script type="text/javascript">
								

								jQuery(document).ready(function(){
									jQuery('#prevTable').click(function(){
										jQuery('#selectedTables option:selected').each( function() {
											$('#chooseTables').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
											$(this).remove();
											sortDropDownListByText('chooseTables');
											
										});                         // options have no value..?
									});

									jQuery('#nextTable').click(function(){
										jQuery('#chooseTables option:selected').each( function() {
											$('#selectedTables').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
											$(this).remove();
											sortDropDownListByText('selectedTables');
										});                         // options have no value..
									});

								})
								</script>
								<div class="submit1 submitbtn-div">
									<label for="submit" class="left">
									<?php if(isset($databaseDetail) && !empty($databaseDetail)){ ?>
										<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
										<input type="submit" value="Submit" name="updatetable" class="submitbtn" >
										<?php } ?>
									</label>
									<label for="reset" class="right">
										<input type="reset" id="reset" class="submitbtn">
									</label>
								</div>
							</form>
						</div>

						<div class="clear">&nbsp;</div>

					</div>
			
					
					<?php if(isset($_GET['subaction']) && ($_GET['subaction'] == 'createtable' || $_GET['subaction']== 'updatetable')){ 
						$tables_res = $admin->showAllTables();
						while($table_names = mysql_fetch_assoc($tables_res))
						{
							
							$fieldnametobefetched = 'Tables_in_'.$dbDatabase->DBDATABASE;
							$table_name[] = $table_names[$fieldnametobefetched];
						}
						$table_name = json_encode($table_name);
						
						?>
					<div>
						<form id="frmAllDatabase" name="frmAllDatabase" onsubmit="return saveTable();" action="uploadCSV.php" method="post" enctype="multipart/form-data">
							
							<?php if(isset($_GET['subaction']) && $_GET['subaction'] == 'createtable'){ ?>
							<p>Table Name<em>*</em></p>
							<div style="padding-top: 10px;">
								<input type="text" name="tablename" class="required"/>
								<br/><br/>
							</div>
							<?php } ?>
															
							<p>Upload CSV File<em>*</em></p>
							<div style="padding-top: 10px;">
								<input type="file" accept="csv" name="tableFileUpload" id="tableFileUpload" class="required accept"/>
								<br/>(Only CSV File)<br/>
							</div>
							
							<input type="hidden" name="subaction" value="<?php echo $_GET['subaction']; ?>" />
							
							
							<div class="submit1 submitbtn-div">
								<label for="submit" class="left">
								<?php if(isset($databaseDetail) && !empty($databaseDetail)){ ?>
									<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
									<input type="submit" value="Submit" name="updatetable" class="submitbtn" >
									<?php } ?>
								</label>
								<label for="reset" class="right">
									<input type="reset" id="reset" class="submitbtn">
								</label>
								<div style="display:none;" id="table_name_json"><?php echo $table_name; ?></div>
							</div>

						</form>
					</div>
					<?php } ?>


				</div>

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


