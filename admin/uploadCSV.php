<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";
//echo "<pre>";print_r($_POST);print_r($_FILES);
$admin = new admin();

$dbid = trim($_POST['dbid']);
$databaseDetail = $admin->getDatabase($dbid);

$oldTable = $databaseDetail['table_name'];

$allTables = getTableNames(true);

if(isset($_POST['subaction']) && isset($_FILES['tableFileUpload']) && $_FILES['tableFileUpload']['name']!='' && $_POST['subaction']!='updatetable'){
	$uploadedFile = $_FILES['tableFileUpload']['name'];
	$tempLocation = $DOC_ROOT.'/uploads/temps/'.$uploadedFile;
	@unlink($tempLocation);
	if(move_uploaded_file($_FILES['tableFileUpload']['tmp_name'], $tempLocation)){
	}
	

	if(isset($_POST['tablename'])){
		
		$tablename = str_replace(' ', '_', strtolower(trim($_POST['tablename'])));
		if(in_array($tablename, $allTables)){
			//$_SESSION['msgerror'] = "Table name already exists";
			//header('location: database.php?tab=4&action=edit&subaction='.$_POST['subaction'].'&id='.base64_encode($dbid).'');
			//exit;
		}

		$sqlCreate = "CREATE TABLE ".$tablename."
		(";
	}
	
	$columns = $tablename.'_id int(10) auto_increment, ';
	$columnnames = '';
	if(file_exists($tempLocation)){
		$row = 0;
		if (($handle = fopen($tempLocation, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				//echo "<p> $num fields in line $row: <br /></p>\n";
				
				if($row == 0){

					for ($c=0; $c < $num; $c++) {
						$columns.= "`".str_replace(' ', '_', strtolower($data[$c]))."` text, ";
						$columnnames.= "`".str_replace(' ', '_', strtolower($data[$c]))."`, ";

					}
	
					$columns.=' PRIMARY KEY ('.$tablename.'_id) ';
					$columnnames = substr($columnnames, 0, -2);
					if(isset($_POST['tablename'])){
						$sqlCreate .= $columns." )";
						$resultCreate = $dbDatabase->run_query($sqlCreate);
					}

				} else {
					$sqlinsert = "insert into `".$tablename."` values( ";
					$values = "'', ";
					for ($c=0; $c < $num; $c++) {
						$values .= "'".addslashes($data[$c])."', ";
					}
					$values = substr($values, 0, -2);
					$sqlinsert.=$values." )";
					$resultInsert = $dbDatabase->run_query($sqlinsert);
				}

				$row++;
			}
			fclose($handle);
		}
		//$return = $admin->updateDatabaseTable('table_name', $tablename, $dbid);
		$sql = '("' .mysql_real_escape_string($tablename). '", "' . $dbid. '",now())';
		$admin->insertDBTables('searchform_tables',$sql,$dbid);
	}
} else if(isset($_POST['subaction']) && isset($_FILES['tableFileUpload']) && $_FILES['tableFileUpload']['name']!='' && $_POST['subaction']=='updatetable'){
	
	
	$tablename = trim($databaseDetail['table_name']);
	$tablesResultColumn = $admin->showColumns($tablename);
	$tableColumns = $dbDatabase->getAll($tablesResultColumn);	
	$columns = array();
	foreach($tableColumns as $key => $column){
		$columns[] = $column['Field'];
	}


	$uploadedFile = $_FILES['tableFileUpload']['name'];
	$tempLocation = $DOC_ROOT.'/uploads/temps/'.$uploadedFile;
	@unlink($tempLocation);
	if(move_uploaded_file($_FILES['tableFileUpload']['tmp_name'], $tempLocation)){
	}
	
	$columnsCSV = array();
	if(file_exists($tempLocation)){
		$row = 0;
		if (($handle = fopen($tempLocation, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				
				
				if($row == 0){

					for ($c=0; $c < $num; $c++) {
						$columnname = str_replace(' ', '_', strtolower($data[$c]));
						if(!in_array($columnname, $columns)){
							$sqlAlter = "ALTER TABLE `".$databaseDetail['table_name']."` ADD `".$columnname."` TEXT";  
							$resultAlter = $dbDatabase->run_query($sqlAlter);
							if($resultAlter){
								$columns[] = $columnname;
							}
						}
						$columnsCSV[] = $columnname;
					}

				} else {
					
					$columnsCSVCheck = $columnsCSV[0];
					
					
					$rowCheck = $admin->getRowUniversal($databaseDetail['table_name'], $columnsCSVCheck, addslashes($data[0]));		
					
					$columnsStr = "";
					for ($c=0; $c < $num; $c++) {
					
						$columnsStr.= "`".$columnsCSV[$c]."` = '".addslashes($data[$c])."', ";
					}
					
					$columnsStr = substr($columnsStr, 0, -2);
					if(!empty($rowCheck)){
						$sqlUpdate = "update ".$databaseDetail['table_name']." set ".$columnsStr." where ".$columnsCSVCheck." = '".addslashes($data[0])."'";
						$resultUpdate = $dbDatabase->update($sqlUpdate);
					} else {
						$sqlInsert = "insert into ".$databaseDetail['table_name']." set ".$columnsStr;
						$insertid = $dbDatabase->insert($sqlInsert);
					}					
				}

				$row++;
			}
			fclose($handle);
		}
	}
}

@unlink($tempLocation);
header('location: browse.php?id='.base64_encode($dbid).'&table='.base64_encode($tablename));
exit;
?>