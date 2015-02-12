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

if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(base64_decode($_GET['id']));
	$databaseDetail = $admin->getDatabase($dbid);
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);
		################################ SELECTING ALL TABLES RELATED TO DB #########################################

		$allDbTables = $admin->getDatabaseTables($dbid);

		################################ SELECTING ALL TABLES RELATED TO DB #########################################
		if(isset($allDbTables[0]['table_name']))
		{
			$table = stripslashes($allDbTables[0]['table_name']);
		}
		else
		{
			 $table		= stripslashes($databaseDetail['table_name']);	// have to remove this When all data of DB tables goes to datasebae_tables
		}
	}
}

if(isset($_GET['table']) && $_GET['table']!=''){		// if only database is selected.
	 $table = trim(stripslashes(base64_decode($_GET['table'])));	
}

if(isset($_POST['updateColumnsSettings'])){
	
	$dbid = $_POST['dbid'];

	if(isset($_POST['column_names']) && count($_POST['column_names']) >0){
		foreach($_POST['column_names'] as $tablename => $tableColumns){

			$deleteresult = $admin->deleteTableDisplaySettings($tablename, $dbid);
			foreach($tableColumns as $columnname => $columndisplayname){
				
				$displaySettingsId = $admin->deleteDisplaySettings($tablename, $columnname, $dbid);
				if($columndisplayname!=''){
					$displaySettingsId = $admin->insertDisplaySettings($tablename, $columnname, $columndisplayname, $dbid);
				}
				
			}
		}
	}

	header('location:'.URL_SITE.'/admin/columns.php?id='.base64_encode($dbid).'&table='.base64_encode($tablename));
	exit;
}

if(isset($_POST['updatetable'])){
	
	$table = $_POST['tablename'];
	$columnname		= $_POST['columnname'];
	$oldcolumnname	= $_POST['oldcolumnname'];
	$type			= $_POST['field_type'];
	$sqlChange = "ALTER TABLE `".$table."` CHANGE `".$oldcolumnname."` `".$columnname."` ".$type;
	if($_POST['columnlength']!=''){
	$sqlChange.="( ".$_POST['columnlength']." )"; 
	}
	if(isset($_POST['null'])){ 
		$sqlChange.=" NULL ";
	}
	$sqlChange.=" DEFAULT NULL "; 
	$db->run_query($sqlChange);
	$_SESSION['msgsuccess'] = 'Update successful';
	header('location: columns.php?id='.base64_encode($dbid).'&table='.base64_encode($table));
	exit;
}


if(isset($_GET['drop']) && $_GET['drop']!='drop'){
	$resultDrop = $admin->drop($table, trim($_GET['drop']));
	$_SESSION['msgsuccess'] = $table.'\'s structure  has been altered';
	header('location: columns.php?id='.base64_encode($dbid).'&table='.base64_encode($table));
	exit;
}

$_GET['tab'] = 4;
//ALTER TABLE `asylee` CHANGE `region` `region1` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL 
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
				
				<?php include("formNavigation.php"); ?><BR>

				<?php  if(isset($allDbTables) and !empty($allDbTables)) { ?>
					
					<div class="">
						<?php
						foreach($allDbTables as $tables_all)
						{
							?>
							<a class="<?php if((isset($_GET['table']) && base64_encode(stripslashes($tables_all['table_name']))==$_GET['table']) || ($table==stripslashes($tables_all['table_name']))) { ?> active <?php } ?>" href="<?php echo URL_SITE; ?>/admin/columns.php?id=<?php echo base64_encode($dbid);?>&table=<?php echo base64_encode(stripslashes($tables_all['table_name']));?>"><?php echo ucfirst(stripslashes($tables_all['table_name']));?></a>&nbsp;|&nbsp;
							<?php
						}
						?>
						<div class="right"><A HREF="<?php echo URL_SITE; ?>/admin/columnsOrder.php?tab=3&id=<?php echo base64_encode($dbid);?>">Coloum Ordering</A></div>
					</div>
					<hr>				

				<?php } ?>
				

				<?php 
					$tablename = trim(stripslashes($table));
					$tablesResultColumn = $admin->showColumns($tablename);
					$totalColumns = $db->count_rows($tablesResultColumn);
					$tableColumns = $db->getAll($tablesResultColumn);	

					$columnsDisplaySettings = $admin->getColumnDisplaySettingsOfTable($dbid, $tablename);

					$columnsDisplaySettingsArray = array();
					foreach($columnsDisplaySettings as $key => $det){
						$columnsDisplaySettingsArray[$det['column_name']] = $det['display_name'];
					}

				?>
					<div>
						<p>Structure Of <strong><?php echo $table?></strong></p>
						<?php if(isset($totalColumns) && $totalColumns >0){ ?>


						<?php if(isset($_GET['edit']) && isset($tableColumns[$_GET['edit']])){
							
							$editColumnid = $_GET['edit'];
							$columnDetail = $tableColumns[$editColumnid];
						?>
						
						<form id="frmAlterTable" name="frmAlterTable" method="post">
							<table cellspacing="0" cellpadding="6" border="1" class="collapse" id="grid_view" width="100%">
								<tr>
									<th bgcolor="#eeeeee">Name</th>
									<th bgcolor="#eeeeee">Type</th>
									<th bgcolor="#eeeeee">Length</th>
									<th bgcolor="#eeeeee">Null</th>
									<th bgcolor="#eeeeee">Actions</th>
								</tr>
								<tr>					
									<td align="left">
										<input type="text" style="width:150px" value="<?php echo $columnDetail['Field']; ?>" name="columnname" class="required"/>
										<input type="hidden" value="<?php echo $columnDetail['Field']; ?>" name="oldcolumnname" />
									
									</td>

									

									<td align="left">
									<select  name="field_type" class="column_type">
										<option <?php if(strtoupper($columnDetail['Type']) == "INT"){ echo 'selected="selected"'; }?>>INT</option>
										<option <?php if(strtoupper($columnDetail['Type']) == "VARCHAR"){ echo 'selected="selected"'; }?>>VARCHAR</option>
										<option <?php if(strtoupper($columnDetail['Type']) == "TEXT"){ echo 'selected="selected"'; }?>>TEXT</option>
										<option <?php if(strtoupper($columnDetail['Type']) == "DATE"){ echo 'selected="selected"'; }?>>DATE</option>
										<optgroup label="NUMERIC">
											<option <?php if(strtoupper($columnDetail['Type']) == "TINYINT"){ echo 'selected="selected"'; }?>>TINYINT</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "SMALLINT"){ echo 'selected="selected"'; }?>>SMALLINT</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "MEDIUMINT"){ echo 'selected="selected"'; }?>>MEDIUMINT</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "INT"){ echo 'selected="selected"'; }?>>INT</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "BIGINT"){ echo 'selected="selected"'; }?>>BIGINT</option>
											<option disabled="disabled">-</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "DECIMAL"){ echo 'selected="selected"'; }?>>DECIMAL</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "FLOAT"){ echo 'selected="selected"'; }?>>FLOAT</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "DOUBLE"){ echo 'selected="selected"'; }?>>DOUBLE</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "REAL"){ echo 'selected="selected"'; }?>>REAL</option>
											<option disabled="disabled">-</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "BIT"){ echo 'selected="selected"'; }?>>BIT</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "BOOLEAN"){ echo 'selected="selected"'; }?>>BOOLEAN</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "SERIAL"){ echo 'selected="selected"'; }?>>SERIAL</option>
										</optgroup>
										<optgroup label="DATE and TIME">
											<option <?php if(strtoupper($columnDetail['Type']) == "DATE"){ echo 'selected="selected"'; }?>>DATE</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "DATETIME"){ echo 'selected="selected"'; }?>>DATETIME</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "TIMESTAMP"){ echo 'selected="selected"'; }?>>TIMESTAMP</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "TIME"){ echo 'selected="selected"'; }?>>TIME</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "YEAR"){ echo 'selected="selected"'; }?>>YEAR</option>
										</optgroup>
										<optgroup label="STRING">
											<option <?php if(strtoupper($columnDetail['Type']) == "CHAR"){ echo 'selected="selected"'; }?>>CHAR</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "VARCHAR"){ echo 'selected="selected"'; }?>>VARCHAR</option>
											<option disabled="disabled">-</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "TINYTEXT"){ echo 'selected="selected"'; }?>>TINYTEXT</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "TEXT"){ echo 'selected="selected"'; }?>>TEXT</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "MEDIUMTEXT"){ echo 'selected="selected"'; }?>>MEDIUMTEXT</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "LONGTEXT"){ echo 'selected="selected"'; }?>>LONGTEXT</option>
											<option disabled="disabled">-</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "BINARY"){ echo 'selected="selected"'; }?>>BINARY</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "VARBINARY"){ echo 'selected="selected"'; }?>>VARBINARY</option>
											<option disabled="disabled">-</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "TINYBLOB"){ echo 'selected="selected"'; }?>>TINYBLOB</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "MEDIUMBLOB"){ echo 'selected="selected"'; }?>>MEDIUMBLOB</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "BLOB"){ echo 'selected="selected"'; }?>>BLOB</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "LONGBLOB"){ echo 'selected="selected"'; }?>>LONGBLOB</option>
											<option disabled="disabled">-</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "ENUM"){ echo 'selected="selected"'; }?>>ENUM</option>
											<option <?php if(strtoupper($columnDetail['Type']) == "SET"){ echo 'selected="selected"'; }?>>SET</option>
										</optgroup>
										
									</select>
									
									</td>

									<td align="left">
										<input type="text" style="width:150px" value="" name="columnlength" />									
									</td>

									<td align="left">
									<input type="checkbox" value="YES" <?php if($columnDetail['Null'] == 'YES'){ echo "checked=checked"; } ?> name="null">
									</td>
									<!-- <td align="left"><?php echo $columnDetail['Key']; ?></td>
									<td align="left"><?php echo $columnDetail['Default']; ?></td>
									<td align="left"><?php echo $columnDetail['Extra']; ?></td> -->
									<th bgcolor="#eeeeee">
										<div class="submit1 submitbtn-div">
											<label for="submit" class="left">
											<?php if(isset($databaseDetail) && !empty($databaseDetail)){ ?>
												<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
												<input type="hidden" value="<?php echo $table; ?>" name="tablename"/>
												<input type="submit" value="Submit" name="updatetable" class="submitbtn" >
												<?php } ?>
											</label>
											<label for="reset" class="right">
												<input type="reset" id="reset" class="submitbtn">
											</label>
										</div>
									</th>
								</tr>
							</table>
						</form>
						<br/>
						<?php } ?>

						<form id="" name="" method="post">
						<table cellspacing="0" cellpadding="6" border="1" class="collapse" id="grid_view" width="100%">
							<tbody>
								<tr>
									<th bgcolor="#eeeeee">Name</th>
									<th bgcolor="#eeeeee">Display Name</th>
									<th bgcolor="#eeeeee">Type</th>
									<th bgcolor="#eeeeee">Null</th>
									<!-- <th bgcolor="#eeeeee">Key</th>
									<th bgcolor="#eeeeee">Default</th>
									<th bgcolor="#eeeeee">Extra</th> -->
									<th bgcolor="#eeeeee">Actions</th>
								</tr>
								<?php if($tableColumns >0){ 
									foreach($tableColumns as $key => $columnDetail){
										//ALTER TABLE `asylee` CHANGE `2007` `2008` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL
										$txt = '';
										if(isset($columnsDisplaySettingsArray[$columnDetail['Field']])){
											$txt = $columnsDisplaySettingsArray[$columnDetail['Field']];
										}

								?>
									<tr>
										<td align="left"><?php echo $columnDetail['Field']; ?></td>

										<td><input type="text" name="column_names[<?php echo $tablename; ?>][<?php echo $columnDetail['Field']; ?>]" value="<?php echo $txt; ?>" /></td>

										<td align="left"><?php echo $columnDetail['Type']; ?></td>
										<td align="left"><?php echo $columnDetail['Null']; ?></td>
										<!-- <td align="left"><?php echo $columnDetail['Key']; ?></td>
										<td align="left"><?php echo $columnDetail['Default']; ?></td>
										<td align="left"><?php echo $columnDetail['Extra']; ?></td> -->
										<th bgcolor="#eeeeee"><a href="?edit=<?php echo $key; ?>&id=<?php echo base64_encode($dbid);?>&table=<?php echo base64_encode($table); ?>">Edit</a>&nbsp;&nbsp;<a href="?drop=<?php echo $columnDetail['Field']; ?>&id=<?php echo base64_encode($dbid);?>&table=<?php echo base64_encode($table); ?>" onclick="javascript: return confirm('Are you sure you want to delete this column');">Delete</a></th>
									</tr>

								<?php } if(!isset($_GET['edit'])){ ?>
									
									<tr>
										<td>&nbsp;&nbsp;</td>
										<td ><div class="submit1 submitbtn-div">
											<label for="submit" class="left">
											<?php if(isset($databaseDetail) && !empty($databaseDetail)) { ?>
												<input type="hidden" value="<?php echo $dbid; ?>" name="dbid"/>
												<input type="submit" value="Submit" name="updateColumnsSettings" class="submitbtn" >
												<?php } ?>
											</label>
											<label for="reset" class="right">
												<input type="reset" id="reset" class="submitbtn">
											</label>
										</td>
									</div>
								<?php } }?>

							</tbody>
						</table>
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


