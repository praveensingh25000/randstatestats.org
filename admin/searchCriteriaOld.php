<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Saket Bisht
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";
checkSession(true);

$admin = new admin();

$dbname = $dbsource = $description = $miscellaneous = '';

$databaseCategories = $databaseRelatedDatabases = array();


###################################### CODE TO UPDATE SEARCH CRITERIA INTO DATABASE ############
if(isset($_POST['update']))
{

	$dbid = base64_decode($_POST['dbid']);
	$label = $_POST['label'];
	$search_id = base64_decode($_POST['search_id']);
	$type = $_POST['type'];

	$allow_all = (isset($_POST['allow_all']))?'Y':'N';

	$control_type = $_POST['control_type'];
	$tables_selected = $_POST['tables'];
	//echo '<pre>';print_R($_POST);die;
	$admin->updateSearchCriteria($label,$type, $control_type, $allow_all, $search_id);			// updating search criteria details
	
	$admin->deleteAllSearchCriteriaValues($search_id);			// deleting its all values from search_criteria_coloums
	if(isset($_POST['tables'])){		// check if user did not select any rows of associated tables
			
		foreach($tables_selected as $table_name_key=>$table_name){
			if(isset($_POST[$table_name])) {
				$sql_='';
				$table_coloums = $_POST[$table_name]; // WE HAVE COLOUMS ARRAY WHICH WAS SELECTED BY USER 
				foreach($table_coloums as $key=>$tc){
					$sql[] = '("' . mysql_real_escape_string($table_name) . '", "' . mysql_real_escape_string($tc) . '", "' . mysql_real_escape_string($search_id) . '")';
				}
			}
		}
		if(isset($sql))
			$admin->saveSearchCriteriaTables(implode(',',$sql));		// Saving new values in search_criteria_coloums
	}
	$_SESSION['msgsuccess'] = "Update successful ";

	header('location:'.URL_SITE.'/admin/searchCriteria.php?action=edit&edit='.base64_encode($search_id).'&tab=5&id='.base64_encode($dbid));
		
	
}
###################################### CODE TO UPDATE SEARCH CRITERIA INTO DATABASE ############


###################################### CODE TO SAVE SEARCH CRITERIA INTO DATABASE ############
if(isset($_POST['getresults']))
{
	$dbid = stripslashes(base64_decode($_POST['dbid']));
	$tables_selected = $_POST['tables'];
	
	$allow_all = (isset($_POST['allow_all']))?'Y':'N';
	$label = $_POST['label'];
	$type = $_POST['type'];	
	$control_type = $_POST['control_type'];	
	$search_id = $admin->saveSearchCriteria($label, $type, $control_type, $allow_all, $dbid);
	if(isset($_POST['tables']))
	{
		foreach($tables_selected as $table_key=>$table_name)
		{
			$sql_='';
			$table_coloums = $_POST[$table_name]; // WE HAVE COLOUMS ARRAY WHICH WAS SELECTED BY USER 
			foreach($table_coloums as $key=>$tc)
			{
				$sql[] = '("' . mysql_real_escape_string($table_name) . '", "' . mysql_real_escape_string($tc) . '", "' . mysql_real_escape_string($search_id) . '")';
			}
		}
		$admin->saveSearchCriteriaTables(implode(',',$sql));
	}
	$_SESSION['msgsuccess'] = "Search Criteria has been saved ";
	header('location:'.URL_SITE.'/admin/searchCriteria.php?tab=5&id='.base64_encode($dbid));
}

###################################### CODE TO SAVE SEARCH CRITERIA INTO DATABASE ENDS HERE ############
if(isset($_GET['id']) && $_GET['id']!=''){

	$dbid = trim(base64_decode($_GET['id']));
	$databaseDetail = $admin->getDatabase($dbid);
	
	if(!empty($databaseDetail)){
		$dbname		= stripslashes($databaseDetail['db_name']);
		$dbsource	= stripslashes($databaseDetail['db_source']);
		$description= stripslashes($databaseDetail['db_description']);
		$miscellaneous	= stripslashes($databaseDetail['db_misc']);
		################################ SELECTING ALL TABLES RELATED TO DB #########################################

		//$table = $admin->getDatabaseTables($dbid);
		$getDbTables1 = $admin->getDatabaseTables($dbid);
		$getDbTables = array_chunk($getDbTables1,4);
		################################ SELECTING ALL TABLES RELATED TO DB #########################################
		if(empty($getDbTables))
		{
			$_SESSION['msgerror'] = "Please associate tables to '$dbname' ";
			header('location:'.URL_SITE.'/admin/database.php?tab=3&action=edit&id='.base64_encode($dbid));
		}
		
	}else{
		header('location: databases.php');
	}
}else{
	header('location: databases.php');
}


?>
<!-- container -->
<section id="container">
	 <div class="main-cell">
		<aside class="containerRadmin">
			<?php include_once $basedir."/include/adminLeft.php"; ?>
		</aside>

		<?php
		if(isset($_GET['action']))
		{
			$action = $_GET['action'];
		}
		else
		{
			$action='all';
		}
		switch($action){
			case 'add':
				include($DOC_ROOT.'admin/searcCriteriaAction.php');
				break;
			case 'edit':
				## SELECTING SEARCH CRITERIA DETAIL IF EXIST
				
				$edit = base64_decode($_GET['edit']);
				$search_criteria = $admin->selectSearchCriteriaDetails($edit);

			
				## SELECTING SEARCH CRITERIA DETAIL IF EXIST
				include($DOC_ROOT.'admin/searcCriteriaAction.php');
				break;
			case 'delete':
				$admin->deleteSearchCriteria(base64_decode($_GET['delete']));
				$_SESSION['msgsuccess']='Search Criteria has been deleted';
				
				header("location: ".URL_SITE."/admin/searchCriteria.php?tab=5&id=".$_GET['id']);
				break;
			case 'all':
				//echo '<pre>';
				$search_criteria_details = $admin->selectAllSearchCriteria($dbid);
				include($DOC_ROOT.'admin/showAllCriteria.php');
				break;
			default:
				$_SESSION['msgsuccess']='No Action Given';
				header('location: databases.php');
				break;

		}
		
		
		?>
	 </div>
</section>

<?php 
include_once $basedir."/include/adminFooter.php";
?>
