<?php
Class SearchCriteria{

function saveSearchCriteria($label, $type, $control_type, $allow_all, $dbid){
		global $dbDatabase;
		$sql = "INSERT INTO `search_criteria` SET label_name='".mysql_real_escape_string($label)."', type='".$type."', control_type = '".$control_type."', db_id='".$dbid."', allow_all_values = '".$allow_all."', created_on=now()";
		return $dbDatabase->insert($sql);
	}

	function saveSearchCriteriaTables($sql){
		global $dbDatabase;
		$sql = "INSERT INTO `search_criteria_coloums` (belongs_to,coloum_name,search_criteria_id, is_filter, filtered_values) VALUES ".$sql;
		return $dbDatabase->insert($sql);
	}

	function selectSearchCriteriaDetails($search_id){
		global $dbDatabase;
		$main_detail = array();
		$related_tables = array();
		$sql = "SELECT search_criteria.db_id, search_criteria.allow_all_values, search_criteria.id as criteria_id,search_criteria.label_name, search_criteria.type, search_criteria.control_type, search_criteria_coloums.belongs_to,search_criteria_coloums.coloum_name, search_criteria_coloums.is_filter, search_criteria_coloums.filtered_values FROM search_criteria LEFT JOIN  search_criteria_coloums  ON  search_criteria.id=search_criteria_coloums.search_criteria_id WHERE search_criteria.id=$search_id";
		$details = $dbDatabase->getAll($dbDatabase->run_query($sql));
		if(count($details)>0){
			foreach($details as $key=>$search_details){
				$main_detail =  array('criteria_id'=>$search_details['criteria_id'],'db_id'=>$search_details['db_id'],'label_name'=>$search_details['label_name'],'type'=>$search_details['type'], 'control_type'=>$search_details['control_type'], 'allow_all_values'=>$search_details['allow_all_values']); 
				$return[$key]['belongs_to'] =  $search_details['belongs_to'];
				$return[$key]['coloum_name'] =  $search_details['coloum_name'];
				$return[$key]['is_filter'] =  $search_details['is_filter'];
				$return[$key]['filtered_values'] =  $search_details['filtered_values'];

				if(!in_array($search_details['belongs_to'],$related_tables)){
					$related_tables[] = $search_details['belongs_to'];
				}
			}

			$main_detail['search_criteria_coloums'] = $return;
			$main_detail['related_tables'] = $related_tables;
		}
		
			return $main_detail;
		
	}

	function selectAllSearchCriteria($dbid){
			global $dbDatabase;
			$sql = "SELECT sc.id, sc.db_id, sc.label_name, sc.type,sc.orderby, sc.allow_all_values, sc.control_type, scc.coloum_name, scc.search_criteria_id, scc.belongs_to, scc.is_filter, scc.filtered_values FROM search_criteria as sc left join search_criteria_coloums as scc on sc.id = scc.search_criteria_id WHERE db_id='".$dbid."' order by orderby ASC";
			return $dbDatabase->getAll($dbDatabase->run_query($sql));
	}

	function deleteSearchCriteria($id){
		global $dbDatabase;
		$sql= "DELETE from search_criteria WHERE id=$id";
		$dbDatabase->run_query($sql);

		$sql = "DELETE FROM search_criteria_coloums WHERE search_criteria_id=$id";
		$dbDatabase->run_query($sql);
	}

	function updateSearchCriteria($label, $type, $control_type, $allow_all, $id){
		global $dbDatabase;
		$sql ="UPDATE search_criteria SET label_name = '".mysql_real_escape_string($label)."', type='".$type."', control_type = '".$control_type."', allow_all_values = '".$allow_all."' WHERE id=$id";
		return $dbDatabase->update($sql);		
	}

	function deleteAllSearchCriteriaValues($search_id){
		global $dbDatabase;
		$sql = "DELETE FROM search_criteria_coloums WHERE search_criteria_id=$search_id";
		return $dbDatabase->delete($sql);
	}

	function selectOnlyNameOfSearchCriteria($id){
		$sql = "SELECT label_name FROM search_criteria WHERE db_id=$id";
		$res = mysql_query($sql);
		while($data = mysql_fetch_assoc($res)){
			$return[]=$data['label_name'];
		}
		return $return;
	}

	function getTimeIntervalSettings($dbid){
		global $dbDatabase;
		$sql			= "select * from time_interval_settings where database_id = '".$dbid."' ";
		$timeIntervalSettings		= $dbDatabase->getRow($sql);
		return $timeIntervalSettings;
	}

	function getTableData($tablename){
			global $dbDatabase;
			$sql = "select * from `".$tablename."`";
			return $dbDatabase->getAll($dbDatabase->run_query($sql));
	}

	function getTableColumnData($tablename, $columnname){
			global $dbDatabase;
			$sql = "select DISTINCT ".$columnname." from `".$tablename."`";
			return $dbDatabase->getAll($dbDatabase->run_query($sql));
	}

	function getTableColumnsDisplaySettings($dbid, $order = " order by orderby ASC"){
		global $dbDatabase;
		$sql = "SELECT * from columns_display_settings where database_id = '".$dbid."' ".$order."";
		return $dbDatabase->getAll($dbDatabase->run_query($sql));
	}
}

?>