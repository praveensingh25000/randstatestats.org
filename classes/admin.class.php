<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

class admin extends TempAdmin {

	# Function to return the countries matching with the passed string
	function login($username, $password){
		global $db;
		$sql			= "select * from admins where username = '".$username."' and password = '".$password."' and is_active = '1'";
		$adminDetail	= $db->getRow($sql);
		return $adminDetail;
	}

	function userDetail($id){
		global $db;
		$sql			= "select * from users where id = '".$id."'";
		$adminDetail	= $db->getRow($sql);
		return $adminDetail;
	}
	
	//Function to getall main databases
	function getMainDatabases($active = 'A'){
		
		global $db;

		if($active != 'A'){
			$sql = "select * from `databases` where is_active = '".$active."'  order by db_code";
		} else {
			$sql = "select * from `databases` order by db_code";
		}

		return $db->getAll($db->run_query($sql));
	}

	function getMainDbByIdDetail($dbid){
		global $db;
		$sql = "select * from `databases` where id = '".$dbid."'  order by db_code";
		$dbDetail	= $db->getRow($sql);
		return $dbDetail;
	}

	function getMultipleUsers($parentid){
		global $db;
		$sql = "select * from `users` where parent_user_id = '".$parentid."' and is_deleted = '0' order by name";
		$users	= $db->getAll($db->run_query($sql));
		return $users;
	}
	
	function getMainDbDetail($dbname){
		global $db;
		$sql = "select * from `databases` where databasename = '".$dbname."'  order by db_code";
		$dbDetail	= $db->getRow($sql);
		return $dbDetail;
	}

	# Function to get adminDetails by userid
	function adminDetails($id){
		global $db;
		$sql			= "select * from admins where id = '".$id."' and is_active = '1'";
		return$adminDetail	= $db->getRow($sql);		
	}

	# Function to change username
	function changeUsername($username, $id){
		global $db;
		$sql			= "update admins set username = '".$username."' where id = '".$id."'";
		$return			= $db->update($sql);
		return true;
	}
	function getParentCategories(){
		global $dbDatabase;
		$this->sql="SELECT * from categories WHERE parent_id = '0' and is_active = '1' order by trim(category_title)";
		return $this->resource = $dbDatabase->run_query($this->sql);
	}

	function getAllParentCategories(){
		global $db;
		$this->sql="SELECT * from category WHERE parent_id = '0' and is_active = '1' order by orderby ASC";
		return $this->resource = $db->run_query($this->sql);
	}

	function getorderedParentCategory(){
		global $db;
		$this->categoryAll = array();
		$this->sql      ="SELECT * from category WHERE parent_id = '0' and is_active = '1' order by orderby ASC";
		$this->resource = $db->run_query($this->sql);
		$this->category	= $db->getAll($this->resource);

		if(!empty($this->category)) {
			foreach($this->category as $this->categorys){
				if($this->categorys['is_feature'] == '1'){
					$this->categoryAll['is_feature'][$this->categorys['id']] = $this->categorys;
				} else {
					$this->categoryAll['un_feature'][$this->categorys['id']] =$this->categorys;
				}
			}
		}
		return $this->categoryAll;
	}

	function getAllFeaturedUnfeaturedCats($featured=1){
		global $db;		
		$this->sql="SELECT * from category WHERE is_feature= ".$featured." and is_active = '1' order by orderby ASC";
		return $this->resource = $db->run_query($this->sql);
	}

	function databases(){
		global $db;
		$sql = "SELECT * from databases ";
		return $db->getAll($dbDatabase->run_query($sql));
	}

	function showAllTables(){
		global $dbDatabase;
		$sql			= "show tables";
		$allTables		= $dbDatabase->run_query($sql);
		return $allTables;
	}

	function showColumns($tablename){
		global $dbDatabase;
		$sql			= "show columns from ".$tablename;
		$allColumns		= $dbDatabase->run_query($sql);
		return $allColumns;
	}

	function showAllParentCategories(){
		global $dbDatabase;
		$sql			= "select * from categories where parent_id = '0' order by trim(category_title)";
		$categoriesResult	= $dbDatabase->run_query($sql);
		return $categoriesResult;
	}

	function showAllCategories($status=0){
		global $dbDatabase;
		$parent ='';

		if($status == 1){ $parent=' where parent_id !=0 '; }
		$sql			= "select * from categories ".$parent." and is_active = '1' order by trim(category_title)";
		$categoriesResult	= $dbDatabase->run_query($sql);
		return $categoriesResult;
	}

	function showAllActiveDeactiveDatabases($is_active=1){
		global $dbDatabase;
		$sql			= "select * from `searchforms` where is_active = '".$is_active."' order by db_name ";
		$categoriesResult	= $dbDatabase->run_query($sql);
		return $categoriesResult;
	}

	function showAllActiveDeactiveDatabasesAll($is_active=1){
		$sql			= "select * from `searchforms` where is_active = '".$is_active."' order by trim(db_name)";		
		return $sql;
	}

	function getCategory($catid){
		global $dbDatabase;
		$sql			= "select * from categories where id = '".$catid."' ";
		$catDetail		= $dbDatabase->getRow($sql);
		return $catDetail;
	}

	function getPatCategory($catid){
		global $db;
		$sql			= "select * from category where id = '".$catid."' ";
		$catDetail		= $db->getRow($sql);
		return $catDetail;
	}

	function bulkActiveDeactive($ids, $active = '0', $parent='0'){
		if($parent == '0'){
			global $dbDatabase;
			$sql			= "update categories set is_active = '".$active."' where id in (".$ids.")";
			$return			= $dbDatabase->update($sql);
		}else if($parent = '1'){
			global $db;
			$sql			= "update category set is_active = '".$active."' where id in (".$ids.")";
			$return			= $db->update($sql);
		}
		return $return;
	}

	function deleteCategories($ids){
		global $dbDatabase;
		$sql			= "delete from categories where id in (".$ids.") OR parent_id in (".$ids.")";
		$return			= $dbDatabase->delete($sql, $dbDatabase->conn);
		return $return;
	}

	function deleteCategory($id){
		global $dbDatabase;
		$sql			= "delete from categories where id = '".$id."' OR parent_id = '".$id."'";
		$return			= $dbDatabase->delete($sql, $dbDatabase->conn);
		return $return;
	}

	function insertCategory($catname, $parent, $description){
		global $dbDatabase;		
		$sql			= "insert into categories set is_active = '1', parent_id = '".$parent."', category_title = '".$catname."', description = '".$description."', date_added = now()";
		$catid			= $dbDatabase->insert($sql);
		return $catid;
	}

	function updateCategory($catname, $parent, $description, $id){
		global $dbDatabase;		
		$sql			= "update categories set parent_id = '".$parent."',category_title = '".$catname."', description = '".$description."'  where id = '".$id."'";
		$return			= $dbDatabase->update($sql);
		return $return;
	}

	//updated by Praveen Singh on 20-05-2013
	function insertintoCategory($catname, $parent, $description){
		global $dbDatabase,$db;

		if($parent == '0'){ 
			$tablename	= 'category';
			$sql		= "insert into ".$tablename." set is_active = '1', parent_id = '".$parent."', category_title = '".mysql_real_escape_string($catname)."', description = '".mysql_real_escape_string($description)."', date_added = now()";
			$catid		= $db->insert($sql, $db->conn);
		} else {
			$tablename = 'categories';
			$sql	   = "insert into ".$tablename." set is_active = '1', parent_id = '".$parent."', category_title = '".mysql_real_escape_string($catname)."', description = '".mysql_real_escape_string($description)."', date_added = now()";
			$catid	   = $dbDatabase->insert($sql, $dbDatabase->conn);
		};

		return $catid;
	}

	//updated by Praveen Singh on 20-05-2013
	function updateintoCategory($catname, $parent, $description, $id){
		global $dbDatabase,$db;

		if($parent == '0'){ 
			$tablename = 'category';
			$sql	   = "update ".$tablename." set parent_id = '".$parent."',category_title = '".$catname."', description = '".$description."'  where id = '".$id."'";
			$return	   = $db->update($sql,$db->conn);
		} else { 
			$tablename = 'categories'; 
			$sql	   = "update ".$tablename." set parent_id = '".$parent."',category_title = '".$catname."', description = '".$description."'  where id = '".$id."'";
			$return	   = $dbDatabase->update($sql,$dbDatabase->conn);
		};
		
		return $return;
	}

	/********* Database table related function ********/
	function showAllDatabases($orderby = 'db_name', $sort = 'asc', $is_active = ""){
		global $dbDatabase;
		
		if($is_active >= 0){
			$sql	= "select * from `searchforms` where is_active = '".$is_active."' order by trim(".$orderby.") ".$sort." ";
		} else {
			$sql	= "select * from `searchforms` order by trim(".$orderby.") ".$sort."";
		}
		$categoriesResult	= $dbDatabase->run_query($sql);
		return $categoriesResult;
	}

	function getDatabase($dbid, $active = false){
		global $dbDatabase;
		if($active){
			$sql			= "select * from `searchforms` where id = '".$dbid."' ";
		} else {
			$sql			= "select * from `searchforms` where id = '".$dbid."' and is_active = '1'";
		}

		$databaseDetail	= $dbDatabase->getRow($sql);
		return $databaseDetail;
	}

	function bulkActiveDeactiveDatabase($ids, $active = '0'){
		global $dbDatabase, $db;
		$sql			= "update `searchforms` set is_active = '".$active."' where id in (".$ids.")";
		$return			= $dbDatabase->update($sql);

		if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse'] == 'rand_usa') {
			$sql_db		= "update common_forms set is_display = '".$active."' where form_id in (".$ids.") and select_dbname='".$_SESSION['databaseToBeUse']."' ";
			$return		= $db->update($sql_db);
		}

		return $return;
	}

	function deleteDatabases($ids){
		global $dbDatabase;
		$sql			= "delete from `searchforms` where id in (".$ids.")";
		$return			= $dbDatabase->delete($sql);
		return $return;
	}

	function deleteDatabase($id){
		global $dbDatabase;

		$sql			= "delete from searchform_category where database_id = '".$id."'";
		$return			= $dbDatabase->delete($sql);

		$sql			= "delete from `searchforms` where id = '".$id."'";
		$return			= $dbDatabase->delete($sql);
		return $return;
	}

	function insertDatabase($dbname, $dbsource, $description, $db_misc, $dbtitle, $dbsourcelink, $db_geographic, $db_dataseries, $db_nextupdate, $db_periodicity, $notes, $db_datasource = "", $db_lastupdate, $show_decimal, $formfootnotes){
		
		global $dbDatabase;

		if(isset($_POST['share_status']) && $_POST['share_status']!='') {			
			$sharevar=', share_status='.$_POST['share_status'];
		} else {
			$sharevar='';
		}

		if($db_lastupdate == ''){
			$db_lastupdate = date('Y-m-d');
		}

		$sql			= "insert into `searchforms` set is_active = '0', db_name = '".$dbname."', db_source = '".$dbsource."', db_description = '".$description."', db_misc = '".$db_misc."', db_geographic = '".$db_geographic."', db_sourcelink = '".$dbsourcelink."', db_dataseries = '".$db_dataseries."', db_nextupdate = '".$db_nextupdate."', db_title = '".$dbtitle."', db_periodicity = '".$db_periodicity."', db_notes = '".$notes."',  db_datasource = '".$db_datasource."',date_added = '".$db_lastupdate."', formfootnotes = '".$formfootnotes."', decimal_settings = '".$show_decimal."'  ".$sharevar." ";
		$dbid			= $dbDatabase->insert($sql);
		return $dbid;
	}

	function getCategoryForms($cat, $active = 1, $status = false){
		
		global $dbDatabase;
		if($status){
			$sql = "SELECT * FROM `searchforms` WHERE is_active = '".$active."' and is_internal= '0' and id = any( SELECT database_id FROM `searchform_category` WHERE category_id = '".$cat."' OR category_id = any( SELECT id FROM categories WHERE parent_id = '".$cat."')) order by db_name";
		} else {
			$sql = "SELECT * FROM `searchforms` WHERE is_active = '".$active."' and id = any( SELECT database_id FROM `searchform_category` WHERE category_id = '".$cat."' OR category_id = any( SELECT id FROM categories WHERE parent_id = '".$cat."')) order by db_name";
		}
		$databasesResult	= $dbDatabase->run_query($sql);
		return $databasesResult;
	}

	function updateForm($db_geographic, $db_dataseries, $db_nextupdate, $db_datasource, $db_periodicity, $dbid){
		global $dbDatabase;
		$sql			= "update `searchforms` set db_geographic = '".$db_geographic."', db_dataseries = '".$db_dataseries."', db_nextupdate = '".$db_nextupdate."', db_datasource = '".$db_datasource."', db_periodicity = '".$db_periodicity."' where id = '".$dbid."'";
		$return			= $dbDatabase->update($sql);
		return $return;
	}

	function updateDatabase($dbname, $dbsource, $description, $miscellaneous, $dbtitle, $dbsourcelink, $db_geographic, $db_dataseries, $db_nextupdate, $db_periodicity, $notes, $dbid, $db_datasource, $db_lastupdate, $show_decimal, $formfootnotes){

		global $dbDatabase;

		if(isset($_POST['share_status']) && $_POST['share_status']!='') {			
			$sharevar=', share_status='.$_POST['share_status'];
		} else {
			$sharevar='';
		}

		if($db_lastupdate == ''){
			$db_lastupdate = date('Y-m-d');
		}

		$sql			= "update `searchforms` set db_name = '".$dbname."', db_source = '".$dbsource."', db_description = '".$description."', db_misc = '".$miscellaneous."', db_geographic = '".$db_geographic."', db_sourcelink = '".$dbsourcelink."', db_dataseries = '".$db_dataseries."', db_nextupdate = '".$db_nextupdate."', db_title = '".$dbtitle."', db_periodicity = '".$db_periodicity."', db_notes = '".$notes."', db_datasource = '".$db_datasource."', date_added = '".$db_lastupdate."',  formfootnotes = '".$formfootnotes."', decimal_settings = '".$show_decimal."'  ".$sharevar." where id = '".$dbid."'";
		$return			= $dbDatabase->update($sql);
		return $return;
	}

	function updateDatabaseTable($column, $columnvalue, $dbid){
		global $dbDatabase;
		$sql			= "update `searchforms` set  `".$column."` = '".$columnvalue."' where id = '".$dbid."'";
		$return			= $dbDatabase->update($sql);
		return $return;
	}

	function getDatabaseGraphAttributes($dbid){
		global $dbDatabase;
		$sql	= "select * from `searchform_graph_attributes` where dbid = '".$dbid."'";
		$result = $dbDatabase->run_query($sql);
		return $result;
	}

	//DONE BY PKS ON 12/24/2012
	function updateDatabasegraphTable($delete_graph_type, $dbid){
		global $dbDatabase;

		foreach($delete_graph_type as $values)
		{
			$sql		= "delete from `searchform_graph_attributes` where dbid = '".$dbid."' and graph_type='".$values."' ";
			$dbDatabase->delete($sql);
		}
		return $db;
	}

	//DONE BY PKS ON 12/24/2012
	function updateDatabaseGraphAttributes($dbid){
		
		global $dbDatabase;

		$graph_type = trim($_POST['graph']);

		if($graph_type=='bar'){
			
			$x_axis_bar_label	= $_POST['bar-x-axis'];
			$y_axis_bar_label	= $_POST['bar-y-axis'];
			$graph_bar_label	= $_POST['graph-bar-label'];

			$attributes = array('x_axis_bar_label' => $x_axis_bar_label, 'y_axis_bar_label' => $y_axis_bar_label, 'graph_bar_label' => $graph_bar_label);
		}

		if($graph_type=='line'){

			$x_axis_line_label	= $_POST['line-x-axis'];
			$y_axis_line_label	= $_POST['line-y-axis'];
			$graph_line_label	= $_POST['graph-line-label'];

			$attributes = array('x_axis_line_label' => $x_axis_line_label, 'y_axis_line_label' => $y_axis_line_label, 'graph_line_label' => $graph_line_label);
		}

		if($graph_type=='pie'){

			$pie_graph_label	= $_POST['pie-graph-label'];

			$attributes = array('pie_graph_label' => $pie_graph_label);		
		}

		$return=0;

		if(!empty($attributes))
		{
			$sql		= "delete from `searchform_graph_attributes` where dbid = '".$dbid."' and graph_type='".$graph_type."' ";
			$dbDatabase->delete($sql);

			foreach($attributes as $attributename => $attributevalue){
				$sql		= "insert into `searchform_graph_attributes` set  graph_type = '".$graph_type."', attribute_name = '".$attributename."', attribute_value = '".$attributevalue."', dbid = '".$dbid."'";
				$return		= $dbDatabase->insert($sql);
			}
		}

		return $return;
	}

	function updateDatabaseCategories($catgoryArray, $dbid){
		global $dbDatabase;
		
		$sqldelete	= "delete from searchform_category where database_id = '".$dbid."'";
		$return		= $dbDatabase->run_query($sqldelete);

		foreach($catgoryArray as $type => $categories){
			foreach($categories as $key => $catid){
				$sql	= "insert into `searchform_category` set database_id = '".$dbid."', category_id = '".$catid."', cat_type = '".$type."', date_added = now()";
				$dbcatid	= $dbDatabase->insert($sql,$dbDatabase->conn);
			}
		}

		return true;
	}

	function getAllDatabaseCategories($dbid){
		global $dbDatabase;
		$sql			= "select * from `searchform_category` where database_id = '".$dbid."'";
		$categoriesResult	= $dbDatabase->run_query($sql);
		return $categoriesResult;
	}

	function getDatabaseCategories($dbid){
		global $dbDatabase,$db;

		$rowDetail = array();
		$sql			= "select * from `searchform_category` where database_id = '".$dbid."' and cat_type = 'p' ";
		$rowDetail	= $dbDatabase->getRow($sql, $dbDatabase->conn);
		return $rowDetail;
	}

	function getDatabaseCategory($dbid){

		global $dbDatabase;
		$subcatDetail = array();		
		$sql = "SELECT categories.id,categories.category_title,categories.parent_id,categories.is_active FROM categories LEFT JOIN searchform_category on categories.id = searchform_category.category_id WHERE searchform_category.database_id = '".$dbid."' and categories.parent_id !='0' ";
		$subcatDetail = $dbDatabase->getRow($sql);			
		return $subcatDetail;		
	}

	function getDatabaseCategorySub($dbid){

		global $dbDatabase;
		$subcatDetail = array();		
		$sql = "SELECT categories.id,categories.category_title,categories.parent_id,categories.is_active FROM categories JOIN searchform_category on categories.id = searchform_category.category_id WHERE searchform_category.database_id = '".$dbid."' and categories.parent_id !='0' and categories.is_active ='1' and searchform_category.cat_type='s' ";
		$subcatDetail = $dbDatabase->getRow($sql);			
		return $subcatDetail;		
	}

	function updateDatabaseRelatedDatabases($databaseArray, $dbid){
		global $dbDatabase;
		$maincatDetail = array();
		$sql			= "delete from searchform_related_database where database_id = '".$dbid."'";
		$return			= $dbDatabase->delete($sql);
		$query			= "select * from `searchform_category` where database_id='".$dbid."' and cat_type='p'";
		$maincatDetail = $dbDatabase->getRow($query);

		if(!empty($maincatDetail)){
			foreach($databaseArray as $parent_id => $dbrelidAll){
				foreach($dbrelidAll as $key => $dbrelid){
					//$sql			= "insert into `searchform_related_database` set database_id = '".$dbid."', related_database_id = '".$dbrelid."', parent_id = '".$parent_id."',date_added = now()";
					$sql			= "insert into `searchform_related_database` set database_id = '".$dbid."', related_database_id = '".$dbrelid."', parent_id = '".$maincatDetail['category_id']."',date_added = now()";
					$dbcrid			= $dbDatabase->insert($sql);
				}
			}
		}
		return true;
	}

	/******* Function to add form tags in the table ****************/
	function updateFormTags($tags, $dbid){
		global $dbDatabase;
		$sql			= "delete from searchform_tags where database_id = '".$dbid."'";
		$return			= $dbDatabase->delete($sql,$dbDatabase->conn);

		$tagsArray = explode(';', $tags);
		foreach($tagsArray as $key => $tag){
			$sql			= "insert into `searchform_tags` set database_id = '".$dbid."', tags = '".trim($tag)."'";
			$dbcrid			= $dbDatabase->insert($sql,$dbDatabase->conn);
		}
		return true;
	}
	/******* Function to add form tags in the table Ends Here *******/

	/******* Function to get form tags in the table ****************/
	function getFormTags($dbid){
		global $dbDatabase;
		$sql			= "select * from searchform_tags where database_id = '".$dbid."' order by id asc";
		$result = $dbDatabase->run_query($sql);
		return $result;
	}
	/******* Function to get form tags in the table Ends Here *******/



	function updateDatabaseRelatedDatabasesAdmin($databaseArray, $dbid){
		global $dbDatabase;
		$sql			= "delete from searchform_related_database_admin where database_id = '".$dbid."'";
		$return			= $dbDatabase->delete($sql);

		foreach($databaseArray as $key => $dbrelid){
			$sql			= "insert into searchform_related_database_admin set database_id = '".$dbid."', related_database_id = '".$dbrelid."', date_added = now()";
			$dbcrid			= $dbDatabase->insert($sql);
		}
		return true;
	}

	function getAllDatabaseRelatedDatabases($dbid){
		global $dbDatabase;
		$sql	= "select sd.* from `searchform_related_database` as sd, searchforms AS sf where database_id = '".$dbid."' and sf.id = sd.related_database_id and sf.is_active = '1'";
		$databasesRelatedResult	= $dbDatabase->run_query($sql);
		return $databasesRelatedResult;
	}

	function getAllRelatedDatabases($dbid,$parent_id){
		global $dbDatabase;
		$sql	= "select sd.* from `searchform_related_database` as sd, searchforms AS sf where sd.database_id = '".$dbid."' and sd.parent_id = '".$parent_id."' and sf.id = sd.related_database_id and sf.is_active = '1'";
		$databasesRelatedResult	= $dbDatabase->run_query($sql);
		return $databasesRelatedResult;
	}

	function getAllDatabaseRelatedDatabasesAdmin($dbid){
		global $dbDatabase;
		$sql			= "select * from `searchform_related_database_admin` where database_id = '".$dbid."'";
		$databasesRelatedResult	= $dbDatabase->run_query($sql);
		return $databasesRelatedResult;
	}

	function drop($tablename, $columnname){
		global $dbDatabase;
		$sql	= "alter table `".$tablename."` drop column `".$columnname."`";
		$databaseResult	= $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $databaseResult;
	}

	function getDataSQL($tablename){
		$sql = "select * from `".$tablename."` limit 6000";
		return $sql;
	}

	
	function deleteUniversal($table, $column, $value){
		global $dbDatabase;
		$sql			= "delete from `".$table."` where `".$column."` = '".$value."'";
		$return			= $dbDatabase->delete($sql, $dbDatabase->conn);
		return $return;
	}

	function getRowUniversal($table, $column, $value){
		global $dbDatabase;
		$sql			= "select * from `".$table."` where `".$column."` = '".$value."'";
		$rowDetail	= $dbDatabase->getRow($sql, $dbDatabase->conn);
		return $rowDetail;
	}

	function updateColumnsUniversal($tablename, $columns, $where){
		global $dbDatabase;
		$columnsStr = "";
		foreach($columns as $columnname => $value){
			$columnsStr.= "`".$columnname."` = '".addslashes($value)."', ";
		}
		$columnsStr = substr($columnsStr, 0, -2);
		$sql			= "update `".$tablename."` set  ".$columnsStr." where ".$where."";
		$return			= $dbDatabase->update($sql, $dbDatabase->conn);
		return $return;
	}

	function getTableDataUniversal($table , $orderby = ''){
		global $dbDatabase;
		$sql = "select * from ".$table." ".$orderby."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function searchLikeUniversalEqual($table , $column, $searchStr, $orderby = ''){
		global $dbDatabase;
		$sql = "select * from ".$table." where ( ".$column." = '".$searchStr."' ) ".$orderby."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function searchLikeUniversal($table , $column, $searchStr, $orderby = ''){
		global $dbDatabase;
		$sql = "select * from ".$table." where ( ".$column." like '%".$searchStr."%' or ".$column." = '".$searchStr."' ) ".$orderby."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function searchLikeOneEndUniversal($table , $column, $searchStr, $orderby = ''){
		global $dbDatabase;
		$sql = "select * from ".$table." where ( ".$column." like '".$searchStr."%' or ".$column." = '".$searchStr."' ) ".$orderby."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function searchDistinctLikeUniversal($table , $column, $searchStr, $orderby = ''){
		global $dbDatabase;
		$sql = "select DISTINCT(".$column.") from ".$table." where ( ".$column." like '%".$searchStr."%' or ".$column." = '".$searchStr."' ) ".$orderby."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}


	function searchLikeFrontUniversal($table , $column, $searchStr, $orderby = ''){
		global $dbDatabase;
		$sql = "select * from ".$table." where ( ".$column." like '".$searchStr."%' or ".$column." = '".$searchStr."' ) ".$orderby."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function searchLikeFrontLeftUniversal($table , $column, $searchStr, $orderby = ''){
		global $dbDatabase;
		$sql = "select * from ".$table." where ".$column." LIKE '%".$searchStr."' ".$orderby."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function searchLikeUniversalArray($table , $columnsArray, $orderby = '', $limit = ''){
		global $dbDatabase;
		$where = "";
		foreach($columnsArray as $columnname => $searchstr){
			$where .= " and ( ".$columnname." like '%".$searchstr."%' or ".$columnname." = '".$searchstr."' )";
		}
		$sql = "select * from ".$table." where 1 ".$where." ".$orderby." ".$limit."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}


	function searchLikeUniversalArrayAll($table ,$colounname, $columnsArray, $orderby = '', $limit = ''){
		global $dbDatabase;
		$where = "";
		foreach($columnsArray as $columnname => $searchstr){
			$where .= " and ( ".$columnname." like '".$searchstr."%' or ".$columnname." = '".$searchstr."' )";
		}
		$sql = "select DISTINCT ".$colounname." from ".$table." where 1 ".$where." ".$orderby." ".$limit."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function searchUniversalArray($table , $columnsArray, $orderby = '', $limit = ''){
		global $dbDatabase;
		$where = "";
		foreach($columnsArray as $columnname => $searchstr){
			$where .= " and ( ".$columnname." = '".$searchstr."' )";
		}
		$sql = "select * from ".$table." where 1 ".$where." ".$orderby." ".$limit."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function searchDistinctUniversalArray($table ,$columnsstr, $columnsArray, $orderby = '', $limit = ''){
		global $dbDatabase;
		$where = "";
		foreach($columnsArray as $columnname => $searchstr){
			$where .= " and ( ".$columnname." LIKE '%".$searchstr."%' )";
		}
		$sql = "select DISTINCT ".$columnsstr." from ".$table." where 1 ".$where." ".$orderby." ".$limit."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function searchDistinctUniversalArrayAll($table ,$columnsstr, $searchcolumnname1, $searchcolumnname2, $columnsArray1, $columnsArray2, $orderby = '', $limit = '') {
		global $dbDatabase;
		$where1 = $where2 = $where1a = $where2a = '';

		$where = "";
		if(!empty($columnsArray1)){
			foreach($columnsArray1 as $key => $searchstr1){
				$where1a .= " ".$searchcolumnname1." LIKE '".$searchstr1."%' and ";
			}
		}

		if(!empty($columnsArray2)){
			foreach($columnsArray2 as $key => $searchstr2){
				$where2a .= " ( ".$searchcolumnname2." LIKE '%".$searchstr2."' ) or ";
			}			
		}

		$where1 = substr($where1a,0,-4);		
		$where2 = substr($where2a,0,-4);

		$sql = "select DISTINCT ".$columnsstr." from ".$table." where 1 and ".$where1." and (".$where2.") ".$orderby." ".$limit."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function searchDistinctUniversalColoumArray($table , $columnname, $columnnamevalue, $orderby = ''){
		global $dbDatabase;
		$sql = "select * from ".$table." where ".$columnname." = '".$columnnamevalue."' ".$orderby."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		$relatedCounty = $dbDatabase->getAll($resultCountry);
		return $relatedCounty;
	}

	function searchDistinctUniversalColoumOneArray($table ,$displaycolumnnamestr, $columnname, $columnnamevalue, $orderby = ''){
		global $dbDatabase;
		$sql = "select ".$displaycolumnnamestr." from ".$table." where ".$columnname." = '".$columnnamevalue."' ".$orderby."";
		$resultCountryres = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountryres;
	}

	function searchDistinctUniversalColArray($table ,$columnname, $columnnamefield, $columnnamevalue, $orderby = ''){
		global $dbDatabase;
		$sql = "select DISTINCT ".$columnname." from ".$table." where ".$columnnamefield." = '".$columnnamevalue."' ".$orderby."";
		$resultCountryres = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountryres;
	}

	function searchDistinctUniversalColArrayIN($table ,$columnname, $columnnamefield, $columnnamevalue, $orderby = ''){
		global $dbDatabase;
		$sql = "select DISTINCT ".$columnname." from ".$table." where ".$columnnamefield." IN (".$columnnamevalue.") ".$orderby."";
		$resultCountryres = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountryres;
	}

	function searchDistinctUniversalColoumINArray($table ,$displaycolumnnamestr, $columnname, $columnnamevaluestr, $orderby = ''){
		global $dbDatabase;
		$sql = "select ".$displaycolumnnamestr." from ".$table." where ".$columnname." IN (".$columnnamevaluestr.") ".$orderby."";
		$resultCountryres = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountryres;
	}

	function searchDistinctUniversalColoumLikeArray($table ,$displaycolumnnamestr, $columnname, $columnnamevaluestr, $orderby = ''){
		global $dbDatabase;
		$sql = "select ".$displaycolumnnamestr." from ".$table." where ".$columnname." LIKE '%".$columnnamevaluestr."%' ".$orderby."";
		$resultCountryres = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountryres;
	}

	function getDistinctColumnValuesUniversal($table , $column, $columns = "", $limit = ""){
		global $dbDatabase;
		if($columns==''){
			$sql = "select DISTINCT ".$column." from ".$table." where ".$column."!= '' order by ".$column." ".$limit."";
		} else {
			$sql = "select DISTINCT ".$column.", ".$columns." from ".$table." where ".$column."!= '' order by ".$column." ".$limit."";
		}

		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	function getDistinctColumnValuesFruits($table , $column, $category="", $limit = ""){
		global $dbDatabase;
		if($category!=''){
			$sql = "select DISTINCT ".$column." from ".$table." where Category= '".$category."' order by ".$column." ".$limit."";
		} else {
			$sql = "select DISTINCT ".$column.", ".$columns." from ".$table." where Category= '".$category."' order by ".$column." ".$limit."";
		}

		$resultCatValues = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCatValues;
	}

	function getAllDataJoinColumnValuesUniversal($tablestr,$columDatastr,$searchcolumvalues){
		global $dbDatabase;
		$sql = "select ".$columDatastr." from ".$tablestr." where ".$searchcolumvalues."";
		$resultCountry = $dbDatabase->run_query($sql, $dbDatabase->conn);
		return $resultCountry;
	}

	//adde on 12/26/2012

	function changePassword($pw, $id){
		global $db;
		$sql			= "update admins set password = '".$pw."' where id = '".$id."'";
		$return			= $db->update($sql);
		return true;
	}

	function adminForgotPassword($email,$newpassword)
	{  global $db;
		$sql = "update admins set password = '".$newpassword."' where email = '".$email."'";
		$adminDetail	= $db->getRow($sql);
		return $adminDetail;
	}

	function generatePassword($length=6, $strength=0) 
	{
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if($strength & 1) 
		{
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) 
		{
			$vowels .= "AEUY";
		}
		if ($strength & 4) 
		{
			$consonants .= '23456789';
		}
		if ($strength & 8)
		{
			$consonants .= '@#$%';
		}
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) 
		{
			if ($alt == 1)
			{
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} 
			else
			{
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}

	/************************ Plans functions ****************************/
	function showAllPlans(){
		global $db;
		$sql			= "select * from user_types where user_type_id = '0' order by id";
		$plansResult	= $db->run_query($sql);
		return $plansResult;
	}

	function getPlan($planid){
		global $db;
		$sql			= "select * from subscription_plans where id = '".$planid."' ";
		$planDetail		= $db->getRow($sql);
		return $planDetail;
	}
	function deletePlans($ids){
		global $db;
		$sql			= "delete from user_types where id IN (".$ids.")";
		$return			= $db->delete($sql);
		return $return;
	}

	function deletePlan($id){
		global $db;
		$sql			= "delete from subscription_plans where id = '".$id."'";
		$return			= $db->delete($sql);
		return $return;
	}

	function insertPlan($name,$description,$amount,$validity){
		global $db;
		$sql			= "insert into subscription_plans set plan_name = '".$name."',description = '".$description."',amount = '".$amount."',validity = '".$validity."', date_added = now()";
		$catid			= $db->insert($sql);
		return $catid;
	}

	function updatePlan($name,$description,$amount,$validity, $id){
		global $db;
		$sql	= "update subscription_plans set plan_name = '".$name."',description = '".$description."',amount = '".$amount."',validity = '".$validity."' where id = '".$id."'";
		$return			= $db->update($sql);
		return $return;
	}

	function getAllTables($id) {
		global $dbDatabase;
		$sql = "SELECT table_name from `searchforms` where id='".$id."'";
		return $dbDatabase->getAll($dbDatabase->run_query($sql));
	}

	function deletePreviousTables($dbid) {
		global $dbDatabase;
		$sql = "Delete From searchform_tables WHERE database_id='".$dbid."'";
		$dbDatabase->run_query($sql);
	}

	function deleteAssociatedTable($dbid,  $tablename){
		global $dbDatabase;

		$sql = "Delete From columns_display_settings WHERE database_id='".$dbid."' and table_name = '".$tablename."'";
		$dbDatabase->run_query($sql);

		$sql = "Delete From searchform_tables WHERE database_id='".$dbid."' and table_name = '".$tablename."'";
		$dbDatabase->run_query($sql);
	}

	function checkTablesAlreadyExist($id, $tablename) {
		global $dbDatabase;
		$sql = "SELECT * from searchform_tables where database_id = '".$id."' and table_name = '".$tablename."'";
		$tableDetail		= $dbDatabase->getRow($sql, $dbDatabase->conn);
		return $tableDetail;
	}

	function insertDBTables($table_name,$data,$dbid) {
		global $dbDatabase;
		$sql = "INSERT INTO `".$table_name."` (table_name,database_id,created_on) VALUES ".$data;
		$dbDatabase->insert($sql);
	}

	function getDatabaseTables($dbid,$only_name=false) {
			global $dbDatabase;
			$return = array();
			$sql = "SELECT id,table_name FROM `searchform_tables` WHERE database_id='".$dbid."'";			
			$res = $dbDatabase->run_query($sql);
			while($data = mysql_fetch_assoc($res))
			{
				if($only_name==true)
				{
					$return[] = stripslashes($data['table_name']);
				}
				else
				{
					$return[] = $data;
				}
			}
			return $return;
	}

	function getTableDetails($tbl)		// serch detal by id 
	{
		global $dbDatabase;
		$sql = "SELECT * from `searchform_tables` WHERE id='".$tbl."'";
		return $dbDatabase->getRow($sql);
	}

/************************************** User Type Functions*****************************************/

	function insertUserType($user_type){
		global $db;

		$sql = "SELECT * from user_types WHERE user_type = '".trim($user_type)."' ";
		$userTypeDetail = $db->getRow($sql);

		if(empty($userTypeDetail)){
			$sql		= "insert into user_types set user_type = '".mysql_real_escape_string($user_type)."', created= now()";
			$typeid		= $db->insert($sql);
			$typeid		= array('nouser',$typeid);
		} else {
			$typeid		= array('hasuser',$userTypeDetail['id']);
		}		
		return $typeid;
	}
	function updateUserType($user_type,$id){
		global $db;
		$sql	= "update user_types set user_type = '".$user_type."', modified= now() where id = '".$id."'";
		$return			= $db->update($sql);
		return $return;
	}
	function showAllUserTypes($status=1){

		global $db;
		if(isset($status) && $status==1) { $condition=' and is_active = 1';} else { $condition='';}
		$sql			= "select * from user_types where user_type_id=0 ".$condition." order by id";
		$typeResult	= $db->run_query($sql);
		return $typeResult;
	}

	function getUserType($typeid){
		global $db;
		$sql			= "select * from user_types where id = '".$typeid."' ";
		$typeDetail		= $db->getRow($sql);
		return $typeDetail;
	}
	function insertUserSubType($user_type,$type_id){
		global $db;
		$sql			= "insert into user_types set user_type = '".$user_type."',user_type_id= '".$type_id."',created= now()";
		$typeid			= $db->insert($sql);
		return $typeid;
	}
	function showAllUserSubTypes(){
		global $db;
		$sql			= "select * from user_types where user_type_id!=0 order by id";
		$typeResult	= $db->run_query($sql);
		return $typeResult;
	}

	function getUserSubType($typeid){
		global $db;
		$sql			= "select * from user_sub_types where id = '".$typeid."' ";
		$typeDetail		= $db->getRow($sql);
		return $typeDetail;
	}
	function updateUserSubType($user_type,$id){
		global $db;
		$sql	= "update user_types set user_type = '".$user_type."',modified=now() where id = '".$id."'";
		$return			= $db->update($sql);
		return $return;
	}

	function updateTimeInterval($time_format, $embed_y, $embed_m, $embed_q, $year_as, $month_as, $quater_as, $columns, $dbid){
		global $dbDatabase;

		$sql			= "delete from time_interval_settings where database_id = '".$dbid."'";
		$return			= $dbDatabase->delete($sql);

		$sql			= "insert into time_interval_settings set database_id = '".$dbid."', time_format = '".$time_format."',							embed_y = '".$embed_y."', embed_m = '".$embed_m."', embed_q = '".$embed_q."', years_as = '".$year_as."',						months_as =	'".$month_as."', quaters_as = '".$quater_as."',columns =														'".mysql_real_escape_string($columns)."', date_added= now()";
		$timeintervalid	= $dbDatabase->insert($sql);
		return $timeintervalid;
	}


	############ Functions related to the join conditions associated with database tables ########
	function insertJoinConditions($primary_table, $primary_table_column, $foreign_table, $foreign_table_column, $dbid){
		global $dbDatabase;
		
		$sql			= "select * from table_joins where database_id = '".$dbid."' and primary_table = '".$primary_table."' and						primary_table_column  = '".$primary_table_column."' and foreign_table = '".$foreign_table."' and							foreign_table_column =	'".mysql_real_escape_string($foreign_table_column)."'";
		$getDetail		= $dbDatabase->getRow($sql);
		
		if(empty($getDetail)){
			$sql			= "insert into table_joins set database_id = '".$dbid."', primary_table = '".$primary_table."',									primary_table_column  = '".$primary_table_column."', foreign_table = '".$foreign_table."',									foreign_table_column =	'".mysql_real_escape_string($foreign_table_column)."', date_added= now()";

			$joinconditionid	= $dbDatabase->insert($sql);
		} else {
			$joinconditionid = $getDetail['id']; 
		}
		return $joinconditionid;
	}

	function getAllJoinConditions($dbid){
		global $dbDatabase;
		$sql = "SELECT * from table_joins where database_id = '".$dbid."'";
		return $dbDatabase->getAll($dbDatabase->run_query($sql));
	}

	function deleteJoinConditions($id){
		global $dbDatabase;
		$sql			= "delete from table_joins where id = '".$id."'";
		$return			= $dbDatabase->delete($sql);
	}

	############ Functions related to the join conditions associated with database tables Ends Here ########

	############ Functions related to the column display settings ########
	
	function deleteTableDisplaySettings($tablename, $dbid){
		global $dbDatabase;

		$sql			= "delete from columns_display_settings where database_id = '".$dbid."' and table_name = '".$tablename."'";
		$return			= $dbDatabase->delete($sql);
		return $displaySettingsId;
	}

	function deleteDisplaySettings($tablename, $columnname, $dbid){
		global $dbDatabase;

		$sql			= "delete from columns_display_settings where database_id = '".$dbid."' and table_name = '".$tablename."' and column_name	= '".$columnname."'";
		$return			= $dbDatabase->delete($sql);
		return $displaySettingsId;
	}

	function insertDisplaySettings($tablename, $columnname, $displayname, $dbid){
		global $dbDatabase;
		$sql			= "delete from columns_display_settings where database_id = '".$dbid."' and table_name = '".$tablename."' and column_name	= '".$columnname."'";
		$return			= $dbDatabase->delete($sql);
		$sql			= "insert into columns_display_settings set database_id = '".$dbid."', table_name = '".$tablename."',column_name	= '".$columnname."', display_name = '".$displayname."', date_added= now()";
		$displaySettingsId	= $dbDatabase->insert($sql);
		return $displaySettingsId;
	}

	function getColumnDisplaySettingsOfTable($dbid, $tablename){
		global $dbDatabase;
		$sql = "SELECT * from columns_display_settings where database_id = '".$dbid."' and table_name = '".$tablename."'";
		return $dbDatabase->getAll($dbDatabase->run_query($sql));
	}

	function getColumnDisplaySettings($dbid, $tablename, $columnname){
		global $dbDatabase;
		$sql = "SELECT * from columns_display_settings where database_id = '".$dbid."' and table_name = '".$tablename."' and column_name	= '".$columnname."'";
		return $dbDatabase->getRow($sql);
	}
	
	//FUNCTION ADDED BY PKS ON 1/17/2013
	function selectallsubCategory($parent_id){
		global $dbDatabase;
		$sub_category=array();
		$sql = "select * from categories where parent_id = ".$parent_id." order by trim(category_title) ";
		$categoriesResult	= $dbDatabase->run_query($sql);
		$all_sub_categories = $dbDatabase->getAll($categoriesResult);

		if(!empty($all_sub_categories)){			
			foreach($all_sub_categories as $subcategory){
				$sub_category[]=$subcategory['id'];
			}
		}
		return $sub_category;		
	}

	function selectAllCategory($parent_ids){
		global $dbDatabase;
		$sql = "select * from categories where parent_id IN (".$parent_ids.") order by trim(category_title)";
		$categoriesResult	= $dbDatabase->run_query($sql);
		return $categoriesResult;	
	}
	
	//FUNCTION ADDED BY PKS ON 1/17/2013
	function showAllDatabasesCategory($sub_category_ids=NULL){
		global $dbDatabase;		
		$sql_cat			= "select * from `searchform_category` where category_id IN  (".$sub_category_ids.") order by trim(category_id)";
		$categoriesResult	= $dbDatabase->run_query($sql_cat);
		$database_categories = $dbDatabase->getAll($categoriesResult);

		if(!empty($database_categories)) {
			$database_ids_array =array();

			foreach($database_categories as $values){
				$database_ids_array[]=$values['database_id'];
			}
			$database_ids=implode(',',$database_ids_array);
		}else{
			$database_ids=0;
		}
		$sql	= "select * from `searchforms` where id IN  (".$database_ids.") order by trim(db_name)";
		$categoriesResult	= $dbDatabase->run_query($sql);
		return $categoriesResult;
	}

	############ Functions related to the column display settings Ends Here ########

	############ Function related to adding of rows and colums added by PKS on 1/21/2013 ######

	function addrowUniversal($tablename){
		global $dbDatabase;
		$columnsStr = "";
		foreach($_POST['rows'] as $columnname => $value){
			$columnsStr.= "`".$columnname."` = '".addslashes($value)."', ";
		}
		$columnsStr = substr($columnsStr, 0, -2);
		$sql			= "insert into `".$tablename."` set  ".$columnsStr." ";
		$insertid			= $dbDatabase->insert($sql);
		return $insertid;
	}

	function addColUniversal($tablename){
		global $dbDatabase;
		if(isset($_POST['columnname'])) $columnname		=	$_POST['columnname'];
		if(isset($_POST['field_type'])) $field_type		=	$_POST['field_type'];
		if(isset($_POST['columnlength'])) $columnlength	=	$_POST['columnlength'];
		if(isset($_POST['null'])) $null					=	$_POST['null']; 
		
		$sqlChange = "ALTER TABLE `".$tablename."` ADD `".$columnname."` ".$field_type;
		
		if(isset($_POST['columnlength']) && $_POST['columnlength'] !='') {
		$sqlChange.="( ".$_POST['columnlength']." )"; 
		}
		if(isset($_POST['null']) && $_POST['null']!=''){ 
		$sqlChange.=" NULL ";
		}
		$sqlChange.=" DEFAULT NULL "; 

		$update		= $dbDatabase->run_query($sqlChange);
		return $update;
	}

	######### --/Function related to adding of rows and colums added by PKS on 1/21/2013 ######


	/********* Functions for contact resources in array *****/
	
	function getContactResources($dbid){
		global $dbDatabase;
		$sql = "SELECT * from searchform_contact_resources where database_id = '".$dbid."'";
		return $dbDatabase->getAll($dbDatabase->run_query($sql));
	}

	function changeStatusBulkContact($ids, $active = '1'){
		global $dbDatabase;
		$sql			= "update searchform_contact_resources set status = '".$active."' where cid in (".$ids.")";
		$return			= $dbDatabase->update($sql);
		return $return;
	}

	function deleteBulkContacts($ids){
		global $dbDatabase;
		$sql			= "delete from searchform_contact_resources where cid in (".$ids.")";
		$return			= $dbDatabase->update($sql);
		return $return;
	}

	function getContactResourceDetail($id){
		global $dbDatabase;
		$sql = "SELECT * from searchform_contact_resources where cid = '".$id."'";
		return $dbDatabase->getRow($sql);
	}

	function insertContactResource($name, $organisation, $phnno, $email, $address, $remarks, $dbid){
		global $dbDatabase;
		$sql			= "insert into searchform_contact_resources set status = '0', name = '".$name."', organisation = '".$organisation."', phnno = '".$phnno."', email = '".$email."', address = '".$address."', remarks = '".$remarks."', database_id = '".$dbid."', date_added = now()";
		$cid			= $dbDatabase->insert($sql);
		return $cid;
	}

	function updateContactResource($name, $organisation, $phnno, $email, $address, $remarks, $dbid, $cid){
		global $dbDatabase;
		$sql			= "update searchform_contact_resources set status = '1', name = '".$name."', organisation = '".$organisation."', phnno = '".$phnno."', email = '".$email."', address = '".$address."', remarks = '".$remarks."', database_id = '".$dbid."' where cid = '".$cid."'";
		$return			= $dbDatabase->update($sql);
		return $return;
	}

	/********* Functions for contact resources in array *****/

	function getFormContentData($dbid){
		global $dbDatabase;
		$sql = "SELECT * from searchform_display_content where database_id = '".$dbid."'";
		return $dbDatabase->getAll($dbDatabase->run_query($sql));
	}

	function updateFormContentData($dbid, $variablename, $variablevalue){
		global $dbDatabase;
		$sql = "update searchform_display_content set label_value = '".mysql_real_escape_string($variablevalue)."' where database_id = '".$dbid."' and label_name = '".$variablename."'";
		$return			= $dbDatabase->update($sql);
		return $return;
	}

	function insertFormLabelData($dbid, $variablename, $variablevalue){
		global $dbDatabase;
		$sql = "insert into searchform_display_content set label_value = '".mysql_real_escape_string($variablevalue)."', label_name = '".$variablename."', database_id = '".$dbid."'";
		$return			= $dbDatabase->insert($sql);
		return $return;
	}

	function deleteFormLabel($dbid, $label_id){
		global $dbDatabase;
		$sql = "Delete From searchform_display_content WHERE database_id='".$dbid."' and id = '".$label_id."'";
		$dbDatabase->run_query($sql);
	}

	function getTableColumnUniqueValues($tablename, $columnname, $SORT = 'ASC'){
		global $dbDatabase;
		$sql = "SELECT DISTINCT ".$columnname." from ".$tablename." order by ".$columnname." ".$SORT."";
		return $dbDatabase->getAll($dbDatabase->run_query($sql));
	}

	function contactUs($name,$phone,$email,$address,$message)
	{
		global $db;
		$ip_address = getRealIpAddr();
		$sql="insert into contact_us set name='".mysql_real_escape_string($name)."',phn_no='".mysql_real_escape_string($phone)."',email='".mysql_real_escape_string($email)."',address='".mysql_real_escape_string($address)."', message='".mysql_real_escape_string($message)."',ip_address = '".$ip_address."',send_on=NOW()";
		$dbid			= $db->insert($sql, $conn = '');
		return $dbid;
	}

	function getUserContacts(){
		return $sql="select * from rand_admin.contact_us order by send_on desc";
	}

	function getContactDetail($id)
	{
		global $db;
		$sql="select * from contact_us where cont_id='".$id."'";
		return $db->getRow($sql, $conn = '');
	}

	function deleteContact($id){
		global $db;
		$sql="delete from contact_us where cont_id='".$id."'";
		return $db->delete($sql, $conn = '');
	}

	function getNews($newsid){
		global $db;
		$sql			= "select * from news_updates where id = '".$newsid."' ";
		$newsDetail		= $db->getRow($sql, $conn='');
		return $newsDetail;
	}

	function insertNews($title, $description, $active_status, $date){
		global $db;
		$sql			= "insert into news_updates set is_active = '".$active_status."', news_title = '".mysql_real_escape_string($title)."', description = '".mysql_real_escape_string($description)."', date_added = '".$date."', created_on = now()";
		$newsid			= $db->insert($sql, $conn='');
		return $newsid;
	}

	function updateNews($title, $description, $active_status, $date, $id){
		global $db;
		
		$sql			= "update news_updates set news_title = '".mysql_real_escape_string($title)."', description = '".mysql_real_escape_string($description)."', is_active='".$active_status."',  date_added='".$date."' where id = '".$id."'";
		$return			= $db->update($sql, $db->conn);
		return $return;
	}

	function showAllNews(){
		global $db;
		$sql			= "select * from news_updates order by trim(news_title)";
		$NewsResult	= $db->run_query($sql, $conn='');
		return $NewsResult;
	}

	function bulkActiveDeactiveNews($ids, $active = 'N'){
		global $db;
		$sql			= "update news_updates set is_active = '".$active."' where id in (".$ids.")";
		$return			= $db->update($sql, $conn='');
		return $return;
	}

	function deleteNews($ids){
		global $db;
		$sql			= "delete from news_updates where id in (".$ids.")";
		$return			= $db->delete($sql, $conn='');
		return $return;
	}

	function checkNewsTitleAvailability($news_title){
		global $db;
		$sql= "select * from news_updates where news_title like '".$news_title."'";
		$newsDetail = $db->getRow($sql, $conn='');
		return $newsDetail;
	}

	function checkPageNameAvailability($page_name){
		global $db;
		$sql= "select * from cmspages where page_name like '".$page_name."'";
		$pagedetail = $db->getRow($sql, $conn='');
		return $pagedetail;
	}

	///################### Function Added by Praveen Singh on 26-03-2013 #########################

	/************************ SUBSCRIPTION PLANS FUNCTIONS ****************************/
	function selectAlluserTypes($subscriptionid=''){
		global $db;

		if($subscriptionid !=''){
			$sql			= "select * from  user_types where id = '".$subscriptionid."' order by id";
		} else {
			$sql			= "select * from  user_types where user_type_id=0 order by id";		
		}
		$plansResult	= $db->run_query($sql);
		return $plansResult;
	}

	function selectPariculataruserTypes($user_type){
		global $db;
		$sql			= "select * from  user_types where user_type_id=0 and user_type='".$user_type."' order by id";			
		$user_type	= $db->getRow($sql);
		return $user_type;
	}
	
	function showAllDatabasesDetail(){
		global $db;
		
		$sql			= "select * from `databases` order by trim(database_label)";
		$plansResult	= $db->run_query($sql);
		return $plansResult;
	}

	function selectDatabases($dbid){
		global $db;

		$sql			= "select * from `databases` where id = '".$dbid."' ";
		$databases		= $db->getRow($sql);
		return $databases;
	}

	function selectuserTypes($planid){
		global $db;
		$sql			= "select * from user_types where id = '".$planid."' ";
		$planDetail		= $db->getRow($sql);
		return $planDetail;
	}

	function insertsubscriptionsPlan($db_amount){
		global $db;		
		$subscriptionid			=	$_POST['subscriptionid'];
		$planid					=	$_POST['planid'];
		$validity				=	$_POST['validity'];
		$plan_name				=	$_POST['plan_name'];
		$plan_type				=	$_POST['plan_type'];
		$number_of_users		=	$_POST['number_of_users'];
		$plan_type_id			=	$_POST['base_plan'];
		if(isset($_POST['institution_type'])){
			$institution_type_id = $_POST['institution_type'];
		}else{
			$institution_type_id = '0';
		}
		$discounts				=	$_POST['discounts'];

		$sql			=	"insert into subscriptions set subscriptionid = '".$subscriptionid."',db_amount = '".$db_amount."',validity = '".$validity."',plan_name='".$plan_name."',plan_type='".$plan_type."',number_of_users='".$number_of_users."',plan_type_id='".$plan_type_id."',institution_type_id='".$institution_type_id."',discounts='".$discounts."',added = now()";
		$insertedid		=	$db->insert($sql, $db->conn);
		return $insertedid;
	}

	function updatesubscriptionsPlan($db_amount){
		global $db;
		
		$subscriptionid			=	$_POST['subscriptionid'];
		$planid					=	$_POST['planid'];
		$validity				=	$_POST['validity'];
		$plan_name				=	$_POST['plan_name'];
		$plan_type				=	$_POST['plan_type'];
		$number_of_users		=	$_POST['number_of_users'];
		$plan_type_id			=	$_POST['base_plan'];
		$institution_type_id	=	$_POST['institution_type'];
		$discounts				=	$_POST['discounts'];

		$sql	= "update subscriptions set db_amount = '".$db_amount."',validity = '".$validity."',plan_name='".$plan_name."',plan_type='".$plan_type."',number_of_users='".$number_of_users."',plan_type_id='".$plan_type_id."',institution_type_id='".$institution_type_id."', discounts='".$discounts."', modified = now() where id = '".$planid."'";
		return $updated= $db->run_query($sql);
	}


	function selectsubscriptionPlans(){
		global $db;
		$sql			= "select * from  subscriptions where plan_type='single' order by id";
		$plansResult	= $db->run_query($sql);
		return $plansResult;
	}

	function selectsubscriptionPlansValue($id){
		global $db;
		$sql			= "select * from  subscriptions where plan_type='single' and id='".$id."' order by id";
		$typeDetail		= $db->getRow($sql);
		return $typeDetail;
	}	

	function selectAllInstutionType($planid){
		global $db;
		$sql			= "select * from  user_types where user_type_id='".$planid."' order by id";
		$plansResult	= $db->run_query($sql);
		return $plansResult;
	}

	function selectallsubscriptionsPlans($plan_type,$planid=0){
		global $db;
		$sql			= "select * from  subscriptions where (plan_type='".$plan_type."' or subscriptionid='".$planid."') order by id";
		$plansResult	= $db->run_query($sql);
		return $plansResult;
	}

	function selectsubscriptionsPlansOfUser($subscriptionid){
		global $db;
		$sql			= "select * from  subscriptions where subscriptionid ='".$subscriptionid ."' order by id";
		$plansResult	= $db->run_query($sql);
		return $plansResult;
	}

	function SelectparticularSubscriptionDetail($subscriptionid){
		global $db;
		$sql			= "select * from  subscriptions where id='".$subscriptionid."' order by id";
		$planDetail		= $db->getRow($sql);
		return $planDetail;
	}

	function deleteallrelatedplanData($planid){
		global $db;
		$sql			= "delete from subscriptions where id='".$planid."' or plan_type_id='".$planid."' ";
		return $db->delete($sql);
	}

	function checkPlanAvailability($validity,$validity_type){
		global $db;
		$sql			= "select * from  subscriptions where plan_type= '".$validity_type."' and validity='".$validity."'";
		$plansResult	= $db->run_query($sql);
		$count		= $db->count_rows($plansResult);
		return $count;
	}	

	function checknumberofusersPlanAvailability($number_of_users, $number_of_users_type){
		global $db;
		$sql			= "select * from  subscriptions where plan_type= '".$number_of_users_type."' and number_of_users ='".$number_of_users ."' ";
		$plansResult	= $db->run_query($sql);		
		return $plansResult;
	}	

	function selectinstitutionPlans($subscriptionid,$institution_type_id){
		global $db;
		$sql			= "select * from  subscriptions where subscriptionid ='".$subscriptionid ."' and institution_type_id ='".$institution_type_id ."' order by id";
		$plansResult	= $db->run_query($sql);
		return $plansResult;
	}

	function insertPlanTransaction($plan_id,$total_amount, $discountamount = 0, $surchargeamount = 0, $userid = 0){    
		global $db;
		$sql="insert into transaction_record set plan_id='".$plan_id."', discount_amount = '".$discountamount."', surcharge_amount = '".$surchargeamount."', user_id = '".$userid."', amount='".$total_amount."',pay_status='0',buy_on=NOW()";
		$insertResult = $db->insert($sql, $db->conn);
		return $insertResult;
	}

	function updateCreditCardDetailTransaction($creditCardType, $creditCardNumber, $firstName, $lastName, $street, $city, $state, $zip, $countryCode,$paypal_before_transaction_id){    
		global $db;
		$sql="update transaction_record set creditCardType='".$creditCardType."', creditCardNumber='".$creditCardNumber."', firstName='".$firstName."', lastName='".$lastName."', street='".$street."', city='".$city."', state='".$state."', zip='".$zip."', countryCode='".$countryCode."' where id='".$paypal_before_transaction_id."' ";
		$updated	=	$db->run_query($sql);
		return $updated;
	}

	function updatePlanTransaction($user_id,$paypal_transaction_id,$status,$paypal_before_transaction_id,$payment_type, $plan_name, $user_type, $payment_details = "", $noofusers = 1){   
		global $db;
		$sql		=	"update transaction_record set user_id='".$user_id."', plan_name = '".$plan_name."', user_type = '".$user_type."', payment_details = '".$payment_details."', payment_type='".$payment_type."', paypal_transaction_id='".$paypal_transaction_id."', pay_status='".$status."', no_of_users = '".$noofusers."', buy_on=NOW() where id='".$paypal_before_transaction_id."' ";
		$updated	=	$db->run_query($sql);
		return $updated;
	}

	function updateTransactionDates($id, $start_date, $end_date, $invoice_date, $date_paid, $original_rate, $discount_rate, $admin_notes){   
		global $db;
		$sql		=	"update transaction_record set start_date = '".$start_date."', end_date = '".$end_date."', invoice_date = '".$invoice_date."', date_paid = '".$date_paid."', original_rate = '".$original_rate."', discounted_rate = '".$discount_rate."', admin_notes = '".mysql_real_escape_string($admin_notes)."' where id = '".$id."' ";
		$updated	=	$db->run_query($sql);
		return $updated;
	}

	function selecttransactionDetail($transaction_table_id){
		global $db;
		$sql			= "select * from transaction_record where id='".$transaction_table_id."' order by id";
		$transactiondetail		= $db->getRow($sql);
		return $transactiondetail;
	}

	function selectuserTransaction($user_id){
		global $db;
		$sql		= "select * from transaction_record where user_id='".$user_id."' order by id";
		$data		= $db->getRow($sql);
		return $data;	
	}

	function insertuserMembership($user_id,$plan_id,$plan_name,$validity,$db_amount,$transaction_id,$number_of_users,$discounts,$purchase_amt,$institution_type_id){
		global $db;

		$sql			=	"insert into user_membership set user_id = '".$user_id."',plan_id = '".$plan_id."', db_amount = '".$db_amount."', validity = '".$validity."', plan_name='".$plan_name."', transaction_id='".$transaction_id."', number_of_users='".$number_of_users."', purchase_amt='".$purchase_amt."', discounts='".$discounts."',institution_type_id='".$institution_type_id."', buy_on = now()";
		$insertedid		=	$db->insert($sql, $db->conn);
		return $insertedid;
	}

	function updateuserMembership($membership_id,$db_amount_main,$purchase_amt){
		global $db;
		$db_amount_main_strs ='';

		$sql				= "select * from user_membership where id = '".$membership_id."' ";
		$membershipDetail	= $db->getRow($sql);

		$db_amount_main_strs  = trim($membershipDetail['db_amount'].'/'.$db_amount_main);		
		$purchase_amt_all	  = $membershipDetail['purchase_amt'] + $purchase_amt;

		$sql			=	"update user_membership set db_amount = '".$db_amount_main_strs."', purchase_amt='".$purchase_amt_all."' , buy_on = now() where id = '".$membership_id."' ";
		return $updated	= $db->run_query($sql);
	}

	function insertMembershipDatabaseDetail($membership_id, $user_id, $plan_id, $db_valuesArray, $validity, $is_trial = '1'){

		global $db;

		$currentDate = date("Y-m-d H:i:s");
		$start_time  = $currentDate;
		$expire_time = getEndDatefromdays($currentDate,$validity);

		if(isset($_POST['startdate'])){
			$start_time  = date('Y-m-d', strtotime(trim($_POST['startdate'])));
		}

		if(isset($_POST['enddate'])){
			$expire_time  = date('Y-m-d', strtotime(trim($_POST['enddate'])));
		}


		$sqldel	= "delete from database_users where user_id='".$user_id."' and is_trial = '0' ";
		$db->delete($sqldel);

		
		if(!empty($db_valuesArray)){
			foreach($db_valuesArray as $db_id => $db_amount){

				$sql	= "select * from database_users where user_id='".$user_id."' and db_id = '".$db_id."' and  membership_id='".$membership_id."'";
				$data	= $db->getRow($sql);

				if(!empty($data)) {

					$sql = "update database_users set plan_id = '".$plan_id."', db_amount = '".$db_amount."', validity = '".$validity."',membership_id='".$membership_id."',start_time='".$start_time."',expire_time='".$expire_time."',purchased_on=NOW() where user_id='".$user_id."' and db_id = '".$db_id."' and  membership_id='".$membership_id."' ";
					$insertedid	= $db->run_query($sql);

				} else {		
					
					$sqlUpdate = "update database_users set is_active = '0' where user_id='".$user_id."' and db_id = '".$db_id."' ";
					$updateid	= $db->run_query($sqlUpdate);

					$sql = "insert into database_users set user_id = '".$user_id."',plan_id = '".$plan_id."', db_amount = '".$db_amount."', validity = '".$validity."', db_id='".$db_id."',is_trial='".$is_trial."',membership_id='".$membership_id."' ,start_time='".$start_time."',expire_time='".$expire_time."',purchased_on=NOW()";
					$insertedid		= $db->insert($sql, $db->conn);
				}		
			}			
		}		
		return $insertedid;
	}

	function upgradeMembershipDatabaseDetail($membership_id,$user_id,$plan_id,$db_valuesArray,$validity,$is_trial){

		global $db;

		$currentDate = date("Y-m-d H:i:s");
		$start_time  = $currentDate;
		$expire_time = getEndDatefromdays($currentDate,$validity);
		
		if(!empty($db_valuesArray)){
			foreach($db_valuesArray as $db_id => $db_amount){
				
				$sql = "delete from database_users where user_id='".$user_id."' and db_id = '".$db_id."' and is_trial = '0'";
				$db->delete($sql, $db->conn);

				$sql	= "select * from database_users where user_id='".$user_id."' and db_id = '".$db_id."' and membership_id = '".$membership_id."'";
				$data	= $db->getRow($sql);

				if(empty($data)) {

					$sqlUpdate	= "update database_users set is_active = '0' where user_id='".$user_id."' and db_id = '".$db_id."' AND expire_time < now( )";

					$db->update($sqlUpdate, $db->conn);

					$sql = "insert into database_users set user_id = '".$user_id."',plan_id = '".$plan_id."', db_amount = '".$db_amount."', validity = '".$validity."', db_id='".$db_id."', is_trial='".$is_trial."', membership_id='".$membership_id."' ,start_time='".$start_time."',expire_time='".$expire_time."', purchased_on=NOW()";
					$insertedid		= $db->insert($sql, $db->conn);

				} 	
			}			
		}		
		return $insertedid;
	}

	function selectdatabaseUsers($db_id,$user_id){
		global $db;
		$currentDate = date("Y-m-d H:i:s");
		$sql		= "select * from database_users where user_id='".$user_id."' and db_id = '".$db_id."' and expire_time >= '".$currentDate."'";
		$data		= $db->getRow($sql);
		return $data;	
	}

	function selectdatabaseUsersID($id){
		global $db;
		$sql		= "select * from database_users where id = '".$id."' ";
		$data		= $db->getRow($sql);
		return $data;	
	}

	//added by praveen Singh on 10-07-2013
	function selectAlldatabase($db_id,$diff=0){
		global $db;	
		$dbDetailArray = array();

		if($diff == 0){	$condition = "db_id != '".$db_id."'"; }
		else { $condition = "db_id = '".$db_id."'";	}
		
		$sql	 = "select * from database_users where ".$condition." ";
		$res	 = $db->run_query($sql);
		$count   = $db->count_rows($res);
		if($count > 0){
			while($dbDetail=mysql_fetch_assoc($res)){
				$dbDetailArray[] = $dbDetail['user_id'];
			}
		}
		
		return $dbDetailArray;
	}

	function checkValidityDatabaseOnSearch($db_id,$user_id) {

		global $db;
		$validity_count= 0;

		$currentDate  = date("Y-m-d H:i:s");		
		$sql				= "select * from database_users where user_id ='".$user_id."' and is_active='1' and expire_time >= '".$currentDate."' and db_id= '".$db_id."' ";
		$res	            = $db->run_query($sql);
		$validity_count	    = $db->count_rows($res);
		return $validity_count;		
	}

	function Validity($user_id=null, $useremail=null) {

		global $db;
		$validity_status = 0;
		$validity_user_active = array();

		$currentDate		=   date("Y-m-d H:i:s");
		$sql_staement		=   "select * from database_users where user_id='".$user_id."'and expire_time >= '".$currentDate."' and is_active=1 LIMIT 1";
		$paydetail_res		=	$db->run_query($sql_staement);
		$paydetails			=	$db->getAll($paydetail_res);
		
		if(!empty($paydetails)){
			foreach($paydetails as $paydetail){
				$start_time									= date("Y-m-d H:i:s");		
				$expire_time								= $paydetail['expire_time'];			
				$validity_user_active[$paydetail['db_id']]  = getnumberofDays($start_time,$expire_time);
			}
			$validity_status		 =	array_sum($validity_user_active);

		}

		return $validity_status;
	}

	function selectValidDatabaseofUser($user_id){
		global $db;

		$validity_user_active = array();

		$currentDate		=   date("Y-m-d H:i:s");
		$sql_staement		=   "select * from database_users where user_id='".$user_id."'and expire_time >= '".$currentDate."' and is_active='1' and is_trial='1' order by id DESC";
		$paydetail_res		=	$db->run_query($sql_staement);
		$paydetails			=	$db->getAll($paydetail_res);
		
		if(!empty($paydetails)){
			foreach($paydetails as $paydetail){				
				$validity_user_active[]  =	$paydetail['db_id'];
			}
		}

		return $validity_user_active;
	}

	function selectIndividualDatabaseValidity($id){
		global $db;

		$validity_user_active = array();
		$validity_status      = $defaultuserValidity = 0;

		$currentDate		  =   date("Y-m-d H:i:s");
		$sql_staement		  =   "select * from database_users where id='".$id."'";
		$paydetails			  =	$db->getRow($sql_staement);
		
		if(!empty($paydetails)){
			$start_time			 = date("Y-m-d H:i:s");					
			$expire_time		 = $paydetails['expire_time'];				
			$defaultuserValidity = getnumberofDays($start_time,$expire_time);				
		}

		if(isset($defaultuserValidity) && $defaultuserValidity >= 0){				
			$validity_status	 =	$defaultuserValidity;				
		}

		return $validity_status;
	}

	function selectIndividualDatabaseValidityAdmin($id){
		global $db;

		$validity_user_active = array();
		$validity_status      = $defaultuserValidity = 0;

		$currentDate		  =   date("Y-m-d H:i:s");
		$sql_staement		  =   "select * from database_users where id='".$id."'";
		$paydetails			  =	$db->getRow($sql_staement);
		
		if(!empty($paydetails)){
			if($paydetails['is_active'] == 1){
				$start_time			 = date("Y-m-d H:i:s");					
				$expire_time		 = $paydetails['expire_time'];				
				$defaultuserValidity = getnumberofDays($start_time,$expire_time);				
			} else {
				$validity_status	 =	$defaultuserValidity;
			}
		}

		if(isset($defaultuserValidity) && $defaultuserValidity >= 0){				
			$validity_status	 =	$defaultuserValidity;				
		}

		return $validity_status;
	}

	function selectValidPlanofUser($user_id,$filter_type){

		global $db;
		$user = new user();
	    $admin = new admin();

		$validity_plan_array	=   $validity_database_array	=	array();

		if(isset($filter_type) && $filter_type== 'all_trans') {
			$sql				=   "select * from transaction_record where user_id='".$user_id."' and is_deleted='0' ";
		} else {
			$sql				=   "select * from database_users where user_id='".$user_id."' and is_active = '1' order by id asc";
		}

		$paydetail_res			=	$db->run_query($sql);
		$paydetails				=	$db->getAll($paydetail_res);
		
		if(!empty($paydetails)){
			
			foreach($paydetails as $paydetail){
				$validity_plan_array[]	=	$paydetail;
			}
		}
		
		return $validity_plan_array;
	}

	function getMainDatabasesPurched($database_str='0', $active='Y'){
		global $db;		
		$sql = "select * from `databases` where id IN (".$database_str.") and is_active = '".$active."'  order by db_code";	
		return $db->getAll($db->run_query($sql));
	}

	function getMainDatabasesPurchased($database_str='0', $active='Y'){
		global $db;		
		$sql = "select * from `databases` where id IN (".$database_str.") and is_active = '".$active."'  order by db_code";	
		return $data = $db->getRow($sql);
	}

	function selectAllsubscriptionPlansUser($user_id){
		global $db;
		$sql			= "select * from user_membership where user_id='".$user_id."' and is_deleted='0' order by id";
		$plansData		= $db->run_query($sql);
		return $plansData;
	}

	function selectsubscriptionPlansUser($user_id){
		global $db;
		$sql			= "select * from user_membership where user_id='".$user_id."' and is_deleted='0' order by id";
		return $data = $db->getRow($sql);
	}

	function selectAllsubscriptionPlansUserAll($plansResult_str=''){
		global $db;
		$sql			= "select * from user_membership where id IN (".$plansResult_str.") and is_deleted='0' order by id";
		$plansData		= $db->run_query($sql);
		return $plansData;
	}

	function selectedactionPerform($id,$action){

		global $db;
		
		switch($action){

			case 'delete':
				$result	= $db->run_query("update transaction_record set is_deleted='1' where id = '".$id."'");
				break;
		}
		return $result;
	}

	function selectvalidplanStatus($membid){

		global $db;
		$user = new user();
	    $admin = new admin();

		$validity_plan_status	=   '0';
		$sql					=   "select * from user_membership where id='".$membid."' and is_deleted='0' ";
		$paydetail				=	$db->getRow($sql);
		
		if(!empty($paydetail)){
			
			$plan_details			=	$admin->SelectparticularSubscriptionDetail($paydetail['plan_id']);
			$validity_plan_active	=	$plan_details['validity'];	
			
			$start_time				=	$paydetail['buy_on'];						
			$end_time				=	date("Y-m-d H:i:s");
			$validity_user_active   =	getnumberofDays($start_time,$end_time);
			
			if(($validity_user_active <= $validity_plan_active) && $paydetail['is_active'] == '1' ){
				$validity_plan_status	=	'1';
			} else if($validity_user_active <= $validity_plan_active && $paydetail['is_active'] == '0'){
				$validity_plan_status	=	'2';
			} else {
				$validity_plan_status	=	'3';
			}
		}		
		return $validity_plan_status;
	}
	
	/************************ /SUBSCRIPTION PLANS FUNCTIONS ****************************/

	function showAllNotification(){
		global $db;		
		$sql			= "select * from user_notify order by id desc";
		$usernotify  	= $db->run_query($sql);
		return $usernotify;
	}

	function deletNotification($id){
		global $db;
		$sql			= "delete from user_notify where id ='".$id."' ";
		return $db->run_query($sql);
	}

	//added by praveen on 22-03-2013
	function insertshareDetail($shareallArray,$form_id,$form_name,$select_dbname,$is_static_form,$url){
		global $db;

		$sql			= "delete from common_forms where form_id= '".$form_id."' ";
		$delete     	= $db->run_query($sql);

		if(!empty($shareallArray)) {
			foreach($shareallArray as $parent_id => $shareArray){
				foreach($shareArray as $dbname => $categoryidvalues){
					$sql			= "insert into common_forms SET display_dbname='".$dbname."', category_id='".$categoryidvalues."', parent_id= '".$parent_id."', form_id= '".$form_id."', form_name='".$form_name."',select_dbname='".$select_dbname."',is_static_form='".$is_static_form."',url='".$url."',created_on=NOW() ";
					$shareDetail  	= $db->run_query($sql);
				}				
			}
		}

		return $shareDetail;
	}
	function getshareDetail($dbid){
		global $db;
		$sql			= "select * from common_forms where form_id = '".$dbid."'";
		$shareDetail	= $db->run_query($sql);
		return $shareDetail;
	}

	function updateshareDetail($dbid,$form_name) {
		global $db;

		$shareDetail ='';
			
		$sql			= "select * from common_forms where form_id = '".$dbid."' and form_name = '".$form_name."' ";
		$shareDetailAll	= $db->getRow($sql);

		if(empty($shareDetailAll)) {
			$sql			= "update common_forms set form_name='".$form_name."' where form_id = '".$dbid."'";
			$shareDetail	= $db->run_query($sql);
		}	
		return $shareDetail;
	}

	function getshareDetailAll($id){
		global $db;
		$sql			= "select * from common_forms where id = '".$id."' ";
		$shareDetailAll	= $db->getRow($sql);
		return $shareDetailAll;
	}

	function selectedCategoryCheck($form_id,$catid, $dbname = '' ,$parent_id){
		global $db;
		if($dbname != ''){
			$sql = "select * from common_forms where form_id = '".$form_id."' and category_id = '".$catid."' and display_dbname = '".$dbname."' and parent_id = '".$parent_id."'";
		} else {
			$sql = "select * from common_forms where form_id = '".$form_id."' and category_id = '".$catid."' and parent_id = '".$parent_id."'";;
		}
		$shareDetailform	= $db->getRow($sql);
		return $shareDetailform;
	}

	function checkplannameAvailability($plan_name,$plan_type){
		global $db;
		$sql= "select * from subscriptions where plan_type ='".$plan_type."' and plan_name like '".$plan_name."'";
		$plandetail = $db->getRow($sql);
		return $plandetail;
	}

	//#################### Function added by Praveen Singh on 26-03-2013 #########################

	function bulkActiveDeactivePlans($ids, $active = '0'){
		global $dbDatabase, $db;
		$sql   = "update user_types set is_active = '".$active."' where id in (".$ids.")";
		$return   = $db->update($sql);
		return $return;
	}

	function selectAlluserTypesActiveDeactive($active){
		global $db;
		$sql   = "select * from  user_types where user_type_id = '0' and is_active = '".$active."' order by id";  
		$plansResult = $db->run_query($sql);
		return $plansResult;
	}

	function activedeactiveStatus($tablename, $ids, $action,$status){

		global $db;

		switch($action){

			case 'unblocked':
				$sql="update ".$tablename." set block_status='".$status."', is_deleted='0' where id IN (".$ids.")";
				$result	= $db->run_query($sql);
				break;
			case 'blocked':
				$sql="update ".$tablename." set block_status='".$status."', is_deleted='0' where id IN (".$ids.")";
				$result	= $db->run_query($sql);
				break;	
			case 'active':
				$sql="update ".$tablename." set block_status='".$status."', is_deleted='0' where id IN (".$ids.")";
				$result	= $db->run_query($sql);
				break;
			case 'in-active':
				$sql="update ".$tablename." set block_status='".$status."', is_deleted='0' where id IN (".$ids.")";
				$result	= $db->run_query($sql);
				break;		
			case 'verify':
				$sql="update ".$tablename." set is_verified='".$status."' where id IN (".$ids.")";
				$result	= $db->run_query($sql);
				break;
			case 'unverify':
				$sql="update ".$tablename." set is_verified='".$status."' where id IN (".$ids.")";
				$result	= $db->run_query($sql);
				break;
			case 'delete':
				$sql="update ".$tablename." set is_deleted='".$status."' where id IN (".$ids.")";
				$result	= $db->run_query($sql);
				break;
		}
		return $result;
	}

	function updateUserValidity($userid,$numberofdays){
		
		global $db;
		
		$result='';		
		$sql		= "select * from users where id = '".$userid."' ";
		$userDetail	= $db->getRow($sql);

		if(!empty($userDetail)){
			$current_time   =   date("Y-m-d H:i:s");
			$compare_time   =	$userDetail['expire_time'];

			if($compare_time >= $current_time){

				$expire_time	=	$userDetail['expire_time'] ;
				$updated_time   =   getEndDatefromdays($expire_time,$numberofdays);
				$sql			=   "update users set expire_time='".$updated_time."' where id = '".$userid."'";

			} else {

				$current_time   =   date("Y-m-d H:i:s");
				$updated_time   =   getEndDatefromdays($current_time,$numberofdays);
				$sql			=   "update users set expire_time='".$updated_time."' where id = '".$userid."'";
			}
			
			$result			=   $db->run_query($sql);
		}
		$returnUpdated	    = $db->update($sql,$db->conn);
		return $returnUpdated;
	}

	function updateDatabaseUserValidity($id,$numberofdays, $action = 'add'){
		
		global $db;
		
		$result='';		
		$sql	 = "select * from database_users where id = '".$id."' ";
		$userDetail	= $db->getRow($sql);

		if(empty($userDetail)){
			return false;
		} else {

			$current_time   =   date("Y-m-d H:i:s");
			$compare_time   =	$userDetail['expire_time'];

			if($compare_time >= $current_time){

				$expire_time	=	$userDetail['expire_time'] ;
				$updated_time   =   getEndDatefromdays($expire_time,$numberofdays, $action);
				$sql			=   "update database_users set expire_time='".$updated_time."' where id = '".$id."'";

			} else {

				$current_time   =   date("Y-m-d H:i:s");
				$updated_time   =   getEndDatefromdays($current_time,$numberofdays, $action);
				$sql			=   "update database_users set expire_time='".$updated_time."' where id = '".$id."'";
			}

			$result			    =   $db->run_query($sql);
			$returnUpdated	    =   $db->update($sql,$db->conn);			
			return true;
		}
	}

	function updateDatabaseUserValidityAll($user_id, $numberofdays, $action = 'add'){
		
		global $db;
		
		$result='';		
		$sql	 = "select * from database_users where user_id = '".$user_id."' and is_active = 1 ";
		$userDatabases	= $db->getAll($db->run_query($sql));

		if(count($userDatabases)>0){
			
			foreach($userDatabases as $keyD => $detail){

				$compare_time	=	$detail['expire_time'];
				$id				=	$detail['id'];
				$current_time   =   date("Y-m-d H:i:s");
				
				if($compare_time >= $current_time){

					$expire_time	=	$detail['expire_time'] ;
					$updated_time   =   getEndDatefromdays($expire_time,$numberofdays, $action);
					$sql			=   "update database_users set expire_time='".$updated_time."' where id = '".$id."'";

				} else {

					$current_time   =   date("Y-m-d H:i:s");
					$updated_time   =   getEndDatefromdays($current_time,$numberofdays, $action);
					$sql			=   "update database_users set expire_time='".$updated_time."' where id = '".$id."'";
				}

				$result			    =   $db->run_query($sql);
				$returnUpdated	    =   $db->update($sql,$db->conn);			
				
			}
			return true;
		}
	}

	function updateMultipleUser($name, $last_name, $address, $organisation, $phone, $id) {
		
		global $db;

		$sql="update users SET name='".$name."', last_name='".$last_name."', address = '".mysql_real_escape_string($address)."', phone = '".$phone."', organisation = '".$organisation."' where id = '".$id."'";
		$userid	= $db->update($sql,$db->conn);	
		return $userid;
	}

	function addMultipleUser($name, $last_name, $email, $password,$user_login_type, $expire_time, $address, $organisation, $phone, $parent_user_id) {
		
		global $db;

		$sql="insert into users SET name='".$name."', last_name='".$last_name."', email = '".$email."', password = '".md5($password)."',user_type='".$user_login_type."',active_status='1', join_date=now(), expire_time = '".$expire_time."', address = '".mysql_real_escape_string($address)."', phone = '".$phone."', organisation = '".$organisation."', parent_user_id = '".$parent_user_id."'";
		$userid	= $db->insert($sql,$db->conn);	
		return $userid;
	}

	function addInstutionUserDetail($name,$username,$password,$user_login_type) {
		
		global $db;

		$defaultValidity	=   VALIDITY;
		$currentDate		=   date("Y-m-d H:i:s");
		$expire_time		=   getEndDatefromdays($currentDate,$defaultValidity);

		$sql="insert into users SET name='".$name."', email = '".$username."', password = '".md5($password)."',user_type='".$user_login_type."',active_status='1', join_date=now(), expire_time = '".$expire_time."' ";
		$userid	= $db->insert($sql,$db->conn);	
		return $userid;
	}


	function addInstutionipAddress($userid,$instution_id,$ip_rangeArray,$is_verified) {
		
		global $db;

		foreach($ip_rangeArray as $ip_range_from => $ip_range_to){
			$sql	= "insert into instution_ipaddress SET instution_id='".$instution_id."', user_id='".$userid."',is_verified='".$is_verified."',ip_range_from='".$ip_range_from."',ip_range_to='".$ip_range_to."',added_on=NOW(),modified_on=NOW() ";
			$typeid	= $db->insert($sql,$db->conn);
		}		
		return $typeid;
	}

	function addIPAddress($userid,$instution_id,$ip_rangeArray,$is_verified) {		
		global $db;
		foreach($ip_rangeArray as $key => $ips){			
			$sql	= "insert into instution_ipaddress SET instution_id='".$instution_id."', user_id='".$userid."',is_verified='".$is_verified."',ips ='".mysql_real_escape_string($ips)."',added_on=NOW()";
			$typeid	= $db->insert($sql,$db->conn);
		}		
		return $typeid;
	}

	function editIPAddress($ipid,$ips,$is_verified) {
		global $db;	
		$sql			= "update instution_ipaddress set is_verified='".$is_verified."',ips='".$ips."', modified_on=NOW() where id = '".$ipid."' ";
		return $return	= $db->update($sql,$db->conn);
	}

	function deleteUserIPAdress($ipid) {
		global $db;	
		$sql			= "delete from instution_ipaddress where id = '".$ipid."' ";
		return $return	= $db->run_query($sql,$db->conn);
	}

	function count_ip_ranges($user_id) {
		global $db;		
		$sql	= "select * from instution_ipaddress where user_id ='".$user_id."' ";
		$result	=   $db->run_query($sql);
		return $result;
	}

	function current_location_user($user_id,$current_ip){
		
		global $db;	
		$user_exits_ips =array();

		$sql		= "select * from instution_ipaddress where user_id ='".$user_id."'";
		$result			= $db->run_query($sql);
		$ipaddressArray	= $db->getAll($result);

		foreach($ipaddressArray as $ipaddress){

			$current_ip    =  array_sum(explode('.',$current_ip));
			$ip_range_from =  array_sum(explode('.',$ipaddress['ip_range_from']));
			$ip_range_to   =  array_sum(explode('.',$ipaddress['ip_range_to']));

			if($current_ip >= $ip_range_from && $current_ip <= $ip_range_to) {
				$user_exits_ips[] = $ipaddress['is_verified'];
			}
		}
		return $user_exits_ips;
	}

	function detech_current_location_ips($current_ip){
		
		global $db;	
		$user_exits_ips = 0;

		$sql		    = "select * from instution_ipaddress where is_verified = 1 order by id";
		$result			= $db->run_query($sql);
		$ipaddressArray	= $db->getAll($result);

		foreach($ipaddressArray as $ipaddress){			
			$current_iplong =  ip2long($current_ip);			
			$ip_range_from  =  ip2long($ipaddress['ip_range_from']);			
			$ip_range_to    =  ip2long($ipaddress['ip_range_to']);			
			if($current_iplong >= $ip_range_from && $current_iplong <= $ip_range_to) {
				$user_exits_ips = $ipaddress['user_id'];
			}
		}
		return $user_exits_ips;
	}

	function detech_user_current_location_ips($current_ip){
		
		global $db,$DOC_ROOT;	
		$checkips		= array();
		$user_exits_ips = 0;

		$sql		    = "select * from instution_ipaddress where is_verified = 1 order by id";
		$result			= $db->run_query($sql);
		$ipaddressArray	= $db->getAll($result);

		if(!empty($ipaddressArray)) {

			foreach($ipaddressArray as $ipaddressDetail){
				$checkips[$ipaddressDetail['ips']]['ip']		=  $current_ip;
				$checkips[$ipaddressDetail['ips']]['user_id']	=  $ipaddressDetail['user_id'];
			}

			foreach ($checkips as $range => $detail) {
				$ip = trim($detail['ip']);
				$arrayRange = explode('-',trim($range));
				$mystring = $range;
				$findme   = '*';
				$pos = strpos($mystring, $findme);
				
				if ($pos === false) {
					if(count($arrayRange) == 1) {
						$range = trim($range).'-'.trim($range);
					}
				}

				$ok = ip_in_range($ip, strip_tags(trim($range))); //function in include/ip_in_range.php file

				if($ok){
					$user_exits_ips = $detail['user_id'];
					break;
				}
			}
		}
		return $user_exits_ips;
	}

	function select_all_users_ips($status=0){
		global $db;	
		$ipDetailsArray = array();
		if(isset($status) && $status=='1'){
			$sql_staement	= "select * from instution_ipaddress where is_verified = '0' order by is_verified ";
		} else {
			$sql_staement	= "select * from instution_ipaddress order by is_verified ";
		}
		$ipdetail_res	= $db->run_query($sql_staement);
		while($ipDetails = mysql_fetch_assoc($ipdetail_res)) {	
			if(isset($status) && $status=='1'){
				$ipDetailsArray[$ipDetails['user_id']][] = $ipDetails;
			} else{
				$ipDetailsArray['user_id'][$ipDetails['user_id']][] = $ipDetails;
			}
			
		}
		return $ipDetailsArray;
	}

	function ipActivateDeactivate($ipid,$status) {
		global $db;	
		$sql			= "update instution_ipaddress set is_verified='".$status."',modified_on=NOW() where id = '".$ipid."' ";
		return $return	= $db->update($sql,$db->conn);
	}

	function selectUserIPAdressAll($userid) {
		global $db;	
		$sql			= "select * from instution_ipaddress where user_id = '".$userid."' ";
		return $ipres	= $db->run_query($sql);
	}

	function selectUserIPAddress($ipid) {
		global $db;	
		$sql			 = "select * from instution_ipaddress where id = '".$ipid."' ";
		return $ipDetail = $db->getRow($sql);
	}

	function checkUserIPAddress($ips,$userid) {
		global $db;	
		$sql			 = "select * from instution_ipaddress where ips = '".$ips."' and user_id = '".$userid."'";
		return $ipDetail = $db->getRow($sql);
	}

	function editIPRangeSaved($ipid,$ip_range_from,$ip_range_to,$is_verified) {
		global $db;	
		$sql			= "update instution_ipaddress set is_verified='".$is_verified."',ip_range_from='".$ip_range_from."',ip_range_to='".$ip_range_to."',modified_on=NOW() where id = '".$ipid."' ";
		return $return	= $db->update($sql,$db->conn);
	}

	function getPaymentTypes($active) {
		global $db;	
		$sql			= "select * from payment_types where is_active = '".$active."' ";
		return $ipres	= $db->run_query($sql);
	}

	function select_all_users_payment_vefication() {
		global $db;	
		$dataDetailsArray = array();

		$sql	= "select * from institution_payment_detail order by is_verified";
		$detail_res	= $db->run_query($sql);
		while($details = mysql_fetch_assoc($detail_res)) {			
			$dataDetailsArray[$details['user_id']] = $details;
		}

		return $dataDetailsArray;
	}
	
	function addconfirmpaymentdetail($userid,$instution_id) {

		global $db;

		if(isset($_POST['payment_yr']) && isset($_POST['payment_month']) && isset($_POST['payment_day'])){
			$date_of_payment=trim(trim($_POST['payment_yr']).'-'.trim($_POST['payment_month']).'-'.trim($_POST['payment_day']));
		}

		$sql	= "insert into institution_payment_detail SET instution_id='".$instution_id."', user_id='".$userid."',is_verified='0',plan_selected='".mysql_real_escape_string($_POST['plan_selected'])."',mode_of_payment='".mysql_real_escape_string($_POST['mode_of_payment'])."',date_of_payment='".$date_of_payment."',check_no='".mysql_real_escape_string($_POST['check_no'])."',bank_drawn='".mysql_real_escape_string($_POST['bank_drawn'])."',added_on=NOW() ";
		$confirmid	= $db->insert($sql,$db->conn);
		
		$sql	= "select * from institution_payment_detail where id='".$confirmid."' ";
		return $paymentDetail = $db->getRow($sql);
	}

	function selectconfirmpaymentdetail($user_id) {

		global $db;		
		$sql	= "select * from institution_payment_detail where user_id='".$user_id."' and is_deleted='0'";
		return $paymentDetail = $db->getRow($sql);
	}

	function check_confirmpaymentDetail($user_id) {
		global $db;	
		$dataDetailsArray = array();

		$sql	= "select * from institution_payment_detail where user_id='".$user_id."' and is_deleted='0' ";
		$detail_res	= $db->run_query($sql);
		while($details = mysql_fetch_assoc($detail_res)) {			
			$dataDetailsArray[] = $details['is_verified'];
		}
		return $dataDetailsArray;
	}
	
	//added b y praveen Singh on 03-06-2013
	function savecoloumOrderList($coloumArray){
		global $dbDatabase;
		foreach($coloumArray as $orderbyid => $coloumvalue){		
			$sql	  = "update columns_display_settings set orderby = '".$orderbyid."'where id= '".$coloumvalue."'";
			$settings = $dbDatabase->update($sql, $dbDatabase->conn);
		}
		return $settings;
	}

	//added b y praveen Singh on 03-06-2013
	function saveCategoryOrderList($coloumArray){
		global $db;
		foreach($coloumArray as $orderbyid => $coloumvalue){		
			$sql	  = "update category set orderby = '".$orderbyid."'where id= '".$coloumvalue."'";
			$settings = $db->update($sql, $db->conn);
		}
		return $settings;
	}
	
	//added by pks on 27-06-2013
	function globalFunctionSelectionCategory($categoryid){		
		global $dbDatabase,$db;
		$categories = array();
		if(isset($categoryid) && $categoryid == '0'){
			$sql = "select * from categories where parent_id != '0' order by trim(category_title)";
			$categoriesResult	= $dbDatabase->run_query($sql);	
			$categories			= $dbDatabase->getAll($categoriesResult);
		} else if(isset($categoryid) && $categoryid == 'parent'){
			$sql = "select * from category order by trim(is_feature='1') DESC";
			$categoriesResult	= $db->run_query($sql);	
			$categories			= $db->getAll($categoriesResult);
		} else if(isset($categoryid) && $categoryid != '0'){
			$sql = "select * from categories where parent_id IN (".$categoryid.") order by trim(category_title)";
			$categoriesResult	= $dbDatabase->run_query($sql);
			$categories			= $dbDatabase->getAll($categoriesResult);
		}		
		return $categories;
	}

	//added by pks on 27-06-2013
	function globalFunctionSelectionDatabases($categoryid, $is_active){		
		global $dbDatabase,$db;
		$categories = array();
		if(isset($categoryid) && $categoryid == '0'){
			$sql				= "select * from `searchforms` where is_active = '".$is_active."' order by db_name ";
			$categoriesResult	= $dbDatabase->run_query($sql);	
			$categories			= $dbDatabase->getAll($categoriesResult);
		} else if(isset($categoryid) && $categoryid != '0'){
			
			$subcats = 1;

			$sqlCats = "select * from category where parent_id = '".$categoryid."'";
			$sqlCatResults = $db->run_query($sqlCats);
			if(mysql_num_rows($sqlCatResults)>0){
				$cats = array();
				while($rowCat = mysql_fetch_assoc($sqlCatResults)){
					$cats[] = $rowCat['id'];
					
				}
				if(count($cats)>0){
					$subcats = 0;
					$catsin = implode(',', $cats);
					$sql = "SELECT * FROM `searchforms` WHERE is_active = '".$is_active."' and id = any (select database_id from `searchform_category` where (category_id = '".$categoryid."' and cat_type = 'p') or (category_id in (".$catsin.") and cat_type = 's'))";

				}
			} 

			if($subcats == 1){
				$sql = "SELECT * FROM `searchforms` WHERE is_active = '".$is_active."' and id = any( SELECT database_id FROM `searchform_category` WHERE category_id = '".$categoryid."' and cat_type = 'p') order by db_name";
			}

			$categoriesResult	= $dbDatabase->run_query($sql);
			$categories			= $dbDatabase->getAll($categoriesResult);
		}		
		return $categories;
	}

	function bulkFeatureUnfeature($ids, $feature = '0'){
		global $db;
		$sql			= "update category set is_feature = '".$feature."' where id in (".$ids.")";
		$return			= $db->update($sql);
		return $return;
	}

	//added b y praveen Singh on 03-06-2013
	function saveOrderListUniversal($coloumArray,$tablename,$coloumname){
		global $dbDatabase;
		foreach($coloumArray as $orderbyid => $coloumvalue){		
			$sql	  = "update ".$tablename." SET ".$coloumname." = '".$orderbyid."'where id= '".$coloumvalue."'";
			$settings = $dbDatabase->update($sql, $dbDatabase->conn);
		}
		return $settings;
	}

	function selectAllLoginuserTypes(){
		global $db;		
		$userTypesArray  = array();
		$sql			 = "select * from user_types where user_type_id=0 order by id";
		$typesResult	 = $db->run_query($sql);
		$usertypesAll	 = $db->getAll($typesResult);
		
		if(!empty($usertypesAll)) {
			foreach($usertypesAll as $userTypes) {			
				$userTypesArray[] = trim($userTypes['id']);
			}
		}
		return $userTypesArray;
	}

	//added by praveen Singh on 10-07-2013, Modified by baljinder on 17 july 2013,Again Modifed by praveen Singh on 13/11/2013
	function selectallTrialAcountUsersGlobal($status, $type, $db_id, $searchstring = ''){
	
		global $db;
		
		$sql_user = $useridStr = '';
		$userArrayList = $useridArray = array();
		
		$sqlDb	 = "select * from database_users as du where du.is_active = '1' ";
		
		if($type == 'trial'){
			$sqlDb.= " and du.is_trial = '0' ";
		} else if($type == 'account'){
			$sqlDb.= " and du.is_trial = '1' ";
		} else {
			$sqlDb.= " ";
		}

		if($status == '1'){
			$sqlDb.= " and  du.expire_time >= NOW() ";
		} else if($status == '2'){
			$sqlDb.= " and du.expire_time < NOW() ";
		}

		$resultAllList	= $db->run_query($sqlDb);
		if($totalresultAllList	= $db->count_rows($resultAllList) > 0){		
			while($rowList  = mysql_fetch_assoc($resultAllList)){
				$userArrayList[$rowList['user_id']]['db_id'][]  =	$rowList['db_id'];		
				$userArrayList[$rowList['user_id']]['count']    =	count($userArrayList[$rowList['user_id']]['db_id']);
			}
		}

		if(!empty($userArrayList)) {			
			if(isset($useridArray)){ unset($useridArray); }			
			foreach($userArrayList as $userid=>$users){
				if(isset($db_id) && $db_id == 4){
					if($users['count']==1 && in_array($db_id,$users['db_id'])) {
					$useridArray[$userid] = $userid;
					}
				}
				if(isset($db_id) && ($db_id == 1 || $db_id == 2 || $db_id == 3)){				
					if($users['count'] > 1 && in_array($db_id,$users['db_id'])) {
						$useridArray[$userid] = $userid;
					}
				}
			}	
			
			if(!empty($useridArray)) {
				$useridStrunique = array_unique($useridArray);
				$useridStr       = implode(',', $useridStrunique);
			}		
		}

		if($status == '3'){
			$sql_user.= " and u.block_status='1'  ";
		} else if($status == '4'){
			$sql_user.= " and u.is_deleted ='1' ";
		} else {
			$sql_user.= " and u.block_status = '0' and u.is_deleted ='0' ";
		}

		if($searchstring != ''){
			if(strpos($searchstring,"'")) {
				$searchstr = str_replace("'",'%',trim(stripslashes($searchstring)));
			}else{
				$searchstr = trim($searchstring);
			}

			$sql_user.= " and ( u.name like '%".$searchstr."%' or u.last_name like '%".$searchstr."%' or u.email like '%".$searchstr."%' or u.organisation like '%".$searchstr."%' ) ";
		}

		$sql = "select u.*, du.validity, du.expire_time, du.start_time, du.db_id, du.id as user_db_id from users as u JOIN database_users as du on u.id = du.user_id where du.is_active = '1' and du.user_id IN (".$useridStr.") and du.db_id = '".$db_id."' ".$sql_user." order by trim(name) ";
		return $sql;		
	}

	function getPendingPaymentUsers($db_id, $searchstr = ''){
		global $db;

		$searchstr = '';

		if($searchstr != ''){
			$searchstr = addslashes($searchstr);
			$searchstr.= " and ( u.name like '%".$searchstr."%' or u.last_name like '%".$searchstr."%' or u.email like '%".$searchstr."%' or u.organisation like '%".$searchstr."%' ) ";
		}

		$sql	 = "select tr.id as txn_id, tr.*, u.* from transaction_record as tr left join users as u on tr.user_id = u.id where tr.user_id = any(select distinct(user_id) from database_users as du where du.db_id = '".$db_id."') and tr.pay_status = '0' ".$searchstr;
		return $sql;
	}

	function getUserDatabaseValidityDetails($user_id, $db_id){
		global $db;
		$sql	 = "select * from database_users where db_id = '".$db_id."' and user_id = '".$user_id."'";
		$details = $db->getRow($sql);
		return $details;
	}


	function deleteTransaction($id){
		global $db;
		
		//Updating info for the databases for this particular database
		
		$sql1 = "delete from database_users where membership_id = '".$id."'";
		$return1			= $db->delete($sql1, $db->conn);

		$sql = "delete from transaction_record where id = '".$id."'";
		$return			= $db->delete($sql, $db->conn);
		return $return;
	}

	function getAllTransactionsDatabaseWise($status = '', $dbusercode = '', $keyword = '', $orderby = ' order by id desc '){
		global $db;
		$where = '';
		if(is_integer($status)){
			$where .= " and tr.pay_status = '".$status."'";
		}

		if($dbusercode!='' && $keyword == ''){
			$where .= " and tr.user_id = any(select user_id from database_users as du where du.db_id = '".$dbusercode."') ";
		} else if($dbusercode!='' && $keyword != ''){
			$where .= " and tr.user_id = any(select user_id from database_users as du where du.db_id = '".$dbusercode."' and du.user_id = any( select id from users as u where u.name like '".$keyword."%' or u.last_name like '".$keyword."%' or u.organisation like '".$keyword."%')) ";
		}

		$sql = "select * from transaction_record as tr where 1 and tr.user_id != '0' ".$where." ".$orderby;
		return $sql;

	}


	function getAllTransactions($status = '', $user_id = '', $orderby = ' order by id desc '){
		global $db;
		$where = '';
		if(is_integer($status)){
			$where .= " and pay_status = '".$status."'";
		}

		if($user_id != ''){
			$where .= " and user_id = '".$user_id."'";
		}


		$sql = "select * from transaction_record where 1 and user_id != '0' ".$where." ".$orderby;
		return $sql;

	}

	function getDatabasesPurchasedWithPayment($payment_id, $user_id){
		global $db;
		$sql	 = "select * from database_users as du left join `databases` as d on d.id = du.db_id where du.membership_id = '".$payment_id."' and du.user_id = '".$user_id."'";
		$result = $db->run_query($sql);
		$databases = $db->getAll($result);
		return $databases;
	}

	function updatePaymentStatusBulk($ids){
		global $db;
		$sql			= "update transaction_record set paypal_transaction_id =  CONCAT('OFFLINE', id), pay_status = '1' where id in (".$ids.")";
		$return			= $db->update($sql);
		return $return;
	}


	//Added By Baljinder used on accountUpgrade.php
	function getAllTransactionsUserType($user_type, $user_id, $status = '', $orderby = ' order by id desc '){
		global $db;
		$where = '';
		if(is_integer($status)){
			$where .= " and pay_status = '".$status."'";
		}

		$sql = "select * from transaction_record where 1 and user_id = '".$user_id."' ".$where." ".$orderby;
		return $sql;

	}

	//added by praveen singh on21-07-2013
	function getAllCPIUIndexDetails($user_id, $db_id){
		global $db;
		$sql	 = "select * from database_users where db_id = '".$db_id."' and user_id = '".$user_id."'";
		$details = $db->getRow($sql);
		return $details;
	}

	function updateAllPlanTransaction($user_id, $paypal_transaction_id, $status, $paypal_before_transaction_id, $payment_type, $plan_name, $user_type, $amount, $surcharge_amount, $discount_amount, $payment_details = "", $noofusers = 1){ 
		
		global $db;
		$sql		=	"update transaction_record set user_id='".$user_id."', plan_name = '".$plan_name."', user_type = '".$user_type."', payment_details = '".$payment_details."', payment_type='".$payment_type."', paypal_transaction_id='".$paypal_transaction_id."', pay_status='".$status."', amount = '".$amount."', surcharge_amount = '".$surcharge_amount."', discount_amount = '".$discount_amount."', no_of_users = '".$noofusers."', buy_on=NOW() where id='".$paypal_before_transaction_id."' ";
		$updated	=	$db->update($sql, $db->conn);
		return $updated;

	}

	function sendInvoice($invoiceid){
		
		global $user, $addbcc, $URL_SITE;

		$transactionDetail	=	$this->selecttransactionDetail($invoiceid);

		$databasesPurchased = $this->getDatabasesPurchasedWithPayment($invoiceid, $transactionDetail['user_id']);

		if(count($databasesPurchased)>1){
			foreach($databasesPurchased as $keyDbpurchased => $det){
				$dbid = $det['db_id'];
				if($dbid != 4){
					break;
				}
				
			}
		} else if(count($databasesPurchased) == 1){
			$dbid = $databasesPurchased[0]['db_id'];
		} else {
			$dbid = 4;
		}

		$dbDetail = $this->selectDatabases($dbid);

		$userdetail	=	$user->getUser($transactionDetail['user_id']);

		$additionalFields = $user->getUserAdditionalFields($transactionDetail['user_id']);

		$b_firstname = $b_lastname = $b_title = $b_phone = $b_email = $t_firstname = $t_lastname = $t_title = $t_phone = $t_email = $a_firstname = $a_lastname	= $a_title = $a_phone = $a_email = $b_firstname	= $b_lastname = $b_title = $b_phone = $b_email = $t_firstname = $t_lastname	= $t_title = $t_phone	= $t_email = $a_firstname = $a_lastname	= $a_title	= $a_phone = $a_email = "";
		
		$b_firstname = stripslashes($userdetail['name']);
		$b_lastname = stripslashes($userdetail['last_name']);

		$b_address	= stripslashes($userdetail['address']);
		$b_email	= stripslashes($userdetail['email']);
		$b_phone	= stripslashes($userdetail['phone']);

		if(!empty($additionalFields)){

			if($additionalFields['bill_contact']!=''){
				$b_firstname	= stripslashes($additionalFields['bill_contact']);
				$b_lastname		= stripslashes($additionalFields['bill_contact_lastname']);
			}
			$b_title		= stripslashes($additionalFields['bill_title']);
			$b_phone		= stripslashes($additionalFields['bill_phone']);

			if($additionalFields['bill_email']!=''){
				$b_email		= stripslashes($additionalFields['bill_email']);
			}
			//$b_address	= stripslashes($additionalFields['b_address']);

			$t_firstname	= stripslashes($additionalFields['tech_contact']);
			$t_lastname		= stripslashes($additionalFields['tech_contact_lastname']);
			$t_title		= stripslashes($additionalFields['tech_title']);
			$t_phone		= stripslashes($additionalFields['tech_phone']);
			$t_email		= stripslashes($additionalFields['tech_email']);
			//$t_address	= stripslashes($additionalFields['t_address']);

			$a_firstname	= stripslashes($additionalFields['admin_contact']);
			$a_lastname		= stripslashes($additionalFields['admin_contact_lastname']);
			$a_title		= stripslashes($additionalFields['admin_title']);
			$a_phone		= stripslashes($additionalFields['admin_phone']);
			$a_email		= stripslashes($additionalFields['admin_email']);
		}
		

		if($transactionDetail['payment_type'] == 'expresscheckout'){
			$paymentmode = "Paypal";
		} else if(trim($transactionDetail['creditCardNumber'])!='' && $transactionDetail['creditCardNumber']!=0){ 
			$paymentmode = "Credit Card";
		} else {
			$paymentmode = "Cheque";
		}

		if($transactionDetail['pay_status'] == '0'){ 
			$payment = "Due"; 
		} else { 
			$payment = "Paid"; 
		}

		$labelinvoice = "INVOICE DETAILS";

		$toplabel = "Your invoice for";

		$subject	= 'Invoice generated on your account!';	
		
		$call = ".";

		if(isset($transactionDetail['pay_status']) && $transactionDetail['pay_status'] == 1){
			if($transactionDetail['date_paid'] != '' && $transactionDetail['date_paid'] != '0000-00-00'){
				$labelinvoice = "PAYMENT DETAILS";
				$toplabel = "Your payment for";
				$subject	= 'Payment generated on your account!';	
				$call = " or call 800-492-7959.";
			}
		}

		if($transactionDetail['invoice_date'] != '0000-00-00'){
			$transactionDate = $transactionDetail['invoice_date'];
		} else {
			$transactionDate = $transactionDetail['buy_on'];
		}

		$mailContent = '<!DOCTYPE html>
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>randstatestats</title>
		</head>
		<body>
			<div style="color:#615E5E;width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;">
				<div style="width:100%;height:auto;overflow:hidden;text-align:left;padding-bottom:20px;display:block;">
					<img src="'.$URL_SITE.'/images/logo.png" />
					<div style="clear:both"></div>
				</div>
				<div style="width:100%;height:auto;overflow:hidden;">
					'.$toplabel.' '.$dbDetail['database_label'].' is below.  If you have any questions, please email <a href="mailto:nation@randstatestats.org">nation@randstatestats.org</a>'.$call.'<br />
				</div>

				<div style="width:100%;height:auto;overflow:hidden;padding-bottom:20px">
					<div style="background:#663399;color: #ffffff;font: bold 15px Helvetica,Sans-Serif;height: 15px;letter-spacing: 20px;margin: 20px 0;
					padding: 8px 0;text-align: center;width: 100%;">'.$labelinvoice.'</div>

					<div style="height:auto;width:100%;overflow:hidden;">
					

							<table style="clear: both;width: 100%;border-collapse: collapse;font-size:13px;">

							<tr>
								<td>
								<div style="float: left;padding-right: 10px;width: 255px;">Billing Address<br />'.$b_firstname.' '.$b_lastname.', '.$userdetail['organisation'].'<br>'.nl2br($b_address).'<br />'.$b_phone.'<br /></div>
								</td>
								<td>
								<div style="float: left;padding-right: 10px;width: 256px;">Plan Details<br />'.stripslashes($transactionDetail['plan_name']).'<br />';
							
							if($transactionDetail['no_of_users']>1){
								$mailContent .= '<span class="name">Number of Users: '.$transactionDetail['no_of_users'].'</span><br/>';
							}

							if($transactionDetail['original_rate'] >0){
								$mailContent .= '<span class="name">Price: </span>$'.number_format($transactionDetail['original_rate'],4).'<br/>';
							 }

							if($transactionDetail['discounted_rate'] >0){
								$mailContent .= '<span class="name">Discounted Price: </span>$'.number_format($transactionDetail['discounted_rate'],4).'<br/>';
							}

							
							 if(trim($transactionDetail['payment_details']) != ''){
								$mailContent .= '<span class="name">Payment Details:</span><br/>'.html_entity_decode(stripslashes($transactionDetail['payment_details'])).'<br/>';
							 }
						
							$mailContent .= '</div></td>

							<td><div style="float: left;font-size: 13px;width: 248px;">
					
								<table style="float: right;margin-top: 1px;font-size:13px;width: 300px;border-collapse: collapse;">
								
									<tbody>
										<tr>
											<td style="background: #EBEBEB;text-align: left;border: 1px solid #000000;padding: 5px;">Invoice #</td>
											<td style="border: 1px solid #000000;padding: 5px;text-align:right;">'.$invoiceid.'</td>
										</tr>
										<tr>
											<td style="background: #EBEBEB;text-align: left;border: 1px solid #000000;padding: 5px;">Invoice Date</td>
											<td style="border: 1px solid #000000;padding: 5px;text-align:right;">'.date('F j, Y', strtotime($transactionDate)).'</td>
										</tr>';
										
										if($transactionDetail['date_paid'] != '' && $transactionDetail['date_paid'] != '0000-00-00'){

										$mailContent .= '<tr>
											<td style="background: #EBEBEB;text-align: left;border: 1px solid #000000;padding: 5px;">Payment Date</td>
											<td style="border: 1px solid #000000;padding: 5px;text-align:right;">'.date('F j, Y', strtotime($transactionDetail['date_paid'])).'</td>
										</tr>';
										}


										$mailContent .= '<tr>
											<td style="background: #EBEBEB;text-align: left;border: 1px solid #000000;padding: 5px;">Amount&nbsp;'.$payment.'</td>
											<td style="border: 1px solid #000000;padding: 5px;text-align:right;">$'.number_format(trim($transactionDetail['amount']), 2).'</td>
										</tr>
									</tbody>
								</table>
							</div></td></tr></table>						
			

						<table style="border: 1px solid #000000;clear: both;margin: 30px 0px 0px;width: 100%;border-collapse: collapse;font-size:13px;">
							<tbody>
								<tr>	
									<th style="background: #EBEBEB;text-align: left;border: 1px solid #000000;padding: 5px;">S.NO.</th>
									<th style="background: #EBEBEB;text-align: left;border: 1px solid #000000;padding: 5px;">Database</th>
									<th style="background: #EBEBEB;text-align: left;border: 1px solid #000000;padding: 5px;">Start Date</th>
									<th style="background: #EBEBEB;text-align: left;border: 1px solid #000000;padding: 5px;">End Date</th>
								</tr>';


								if(count($databasesPurchased)>0){ 
									foreach($databasesPurchased as $key => $databaseDetails){
										$start_time			 = date("Y-m-d");					
										$expire_time		 = date("Y-m-d", strtotime($databaseDetails['expire_time']));				
										$validityleft = getnumberofDays($start_time,$expire_time);	

										$mailContent .= '<tr style="border-bottom: 1px solid #000000;">
												<td style="border:0px none;padding: 5px;">'.($key+1).'</td>
												<td style="border:0px none;padding: 5px;">'.stripslashes( $databaseDetails['database_label']).'</td>
												<td style="border:0px none;padding: 5px;">'.date('F j, Y', strtotime($databaseDetails['start_time'])).'</td>
												<td style="border:0px none;padding: 5px;">'.date('F j, Y', strtotime($databaseDetails['expire_time'])).'</td>
											</tr>';
									}
								}

								$mailContent .= '<tr>
									<td colspan="2" style="border:0px none;padding: 5px;"></td>
									<td colspan="" style="border-left: 1px solid #000000;text-align: right;padding:5px;">Total</td>

									<td style="border-left: 0 none;text-align: right;padding:5px;">$'.number_format(trim($transactionDetail['amount']+$transactionDetail['discount_amount']), 2).'</td>
								</tr>
								
								<tr>
									<td colspan="2" style="border:0px none;padding: 5px;"></td>
									<td colspan="1" style="border-left: 1px solid #000000;text-align: right;padding:5px;">Discount</td>
									<td style="border-left: 0 none;text-align: right;padding:5px;">$'.number_format(trim($transactionDetail['discount_amount']), 2).'</td>
								</tr>';

								if($transactionDetail['pay_status'] == '1'){ 
									$paid = number_format(trim($transactionDetail['amount']), 2); 
								} else { 
									$paid = "0.00"; 
								}
								
								if($transactionDetail['pay_status'] != '0'){
									$mailContent .= '<tr>
										<td colspan="2" style="border:0px none;padding: 5px;"></td>
										<td colspan="1" style="border-left: 1px solid #000000;text-align: right;padding:5px;">Amount Paid</td>
										<td style="border-left: 0 none;text-align: right;padding:5px;">$'.$paid.'</td>
									</tr>';
								}

								if($transactionDetail['pay_status'] == '0'){

								  $mailContent .= '<tr>
									  <td class="blank" colspan="2"> </td>
									  <td class="total-line balance" colspan="1" style="background:#EBEBEB;">Balance Due</td>
									  <td class="total-value balance" style="background:#EBEBEB;"><div style="text-align:right;" class="due">$'.number_format(trim($transactionDetail['amount']), 2).'</div></td>
								  </tr>';
								}

							$mailContent .= '</tbody>
						</table></tbody></div>';

					if(isset($transactionDetail['pay_status']) && $transactionDetail['pay_status'] == 1){
						if($transactionDetail['date_paid'] != '' && $transactionDetail['date_paid'] != '0000-00-00'){
							// continue
						} else {
							$mailContent .= '<div style="clear:both;padding-top:40px;">
								Please remit payment within 30 days to:<br/><br/>
								RAND State Statistics Subscriptions<br/>
								Mailstop M4N<br/>
								1776 Main Street<br/>
								P.O. Box 2138<br/>
								Santa Monica, CA 90407-2138<br/>
								Phone: 800-492-7959
								Fax: 310-260-8011

							</div>';
						}
					} else {
						$mailContent .= '<div style="clear:both;padding-top:40px;">
								Please remit payment within 30 days to:<br/><br/>
								RAND State Statistics Subscriptions<br/>
								Mailstop M4N<br/>
								1776 Main Street<br/>
								P.O. Box 2138<br/>
								Santa Monica, CA 90407-2138<br/>
								Phone: 800-492-7959<br/>
								Fax: 310-260-8011<br/>

							</div>';
					}

					$mailContent .= '
				</div>
			</div>
		</body>
		</html>';
		
		$receivename	= $b_firstname." ".$b_lastname;
		$receivermail	= $b_email;

		$from_name	= CONTACT_NAME;
		$from_email	= CONTACT_EMAIL;

		$send_mail	= mail_function($receivename, $receivermail, $from_name, $from_email, $mailContent, $subject, array(), $addbcc);
	}

	// Function to add form update info
	function addFormUpdateInfo($db_update_periodicity, $db_contacts, $db_how_update, $db_sent_to_build_date, $db_update_status, $db_update_notes, $dbid){
		global $dbDatabase;
		$sql			= "select * from searchforms_update where db_id = '".$dbid."' ";
		$updateDetail	= $dbDatabase->getRow($sql, $dbDatabase->conn);
		if(empty($updateDetail)){
			$sqlInsert = "insert into searchforms_update set db_update_periodicity = '".$db_update_periodicity."', db_contacts = '".mysql_real_escape_string($db_contacts)."', db_how_update = '".mysql_real_escape_string($db_how_update)."', db_update_notes = '".mysql_real_escape_string($db_update_notes)."', db_sent_to_build_date = '".$db_sent_to_build_date."', db_update_status = '".$db_update_status."', db_id = '".$dbid."', db_added_date = now()";
			$returnid			= $dbDatabase->insert($sqlInsert, $dbDatabase->conn);
			return $returnid;
		} else {
			$sqlUpdate = "update searchforms_update set db_update_periodicity = '".$db_update_periodicity."', db_contacts = '".mysql_real_escape_string($db_contacts)."', db_how_update = '".mysql_real_escape_string($db_how_update)."', db_update_notes = '".mysql_real_escape_string($db_update_notes)."', db_sent_to_build_date = '".$db_sent_to_build_date."', db_update_status = '".$db_update_status."', db_added_date = now() where id = '".$updateDetail['id']."'";
			$returnstatus			= $dbDatabase->update($sqlUpdate, $dbDatabase->conn);
			return $returnstatus;
		}
	}

	function getFormUpdateInfo($dbid){
		global $dbDatabase;
		$sql			= "select * from searchforms_update where db_id = '".$dbid."' ";
		$updateDetail	= $dbDatabase->getRow($sql, $dbDatabase->conn);
		return $updateDetail;
	}
	
	function sourceFormUpdateInfo($db_periodicity ,$db_geographic,$s_desc , $source,	$dbid){
		global $dbDatabase;
		$sqlUpdate = "update `searchforms` set db_periodicity = '".$db_periodicity."', db_geographic = '".$db_geographic."', db_description ='".$s_desc."',db_source='".$source."'  where id = '".$dbid."'";
		$returnstatus			= $dbDatabase->update($sqlUpdate, $dbDatabase->conn);
		return $returnstatus;
	}

}
?>