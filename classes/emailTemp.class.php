<?php
class emailTemp{
	function getTemp($tempid){
		global $db;
		$sql			= "select * from mail_template where id = '".$tempid."' ";
		$newsDetail		= $db->getRow($sql, $conn='');
		return $newsDetail;
	}

	function getTempBody($title){
		global $db;
		$sql			= "select * from mail_template where title = '".$title."' ";
		$newsDetail		= $db->getRow($sql, $conn='');
		return $newsDetail;
	}

	function insertTemp($title, $subject, $body, $tags, $cc_email){
		global $db;
		$sql			= "insert into mail_template set title = '".mysql_real_escape_string($title)."', subject = '".mysql_real_escape_string($subject)."', body = '".mysql_real_escape_string($body)."', tags='".mysql_real_escape_string($tags)."', cc_email = '".$cc_email."',  added_on = now()";
		$tempid			= $db->insert($sql, $conn='');
		return $tempid;
	}

	function updateTemp($title, $subject, $body, $tags, $cc_email, $id){
		global $db;
		
		$sql			= "update mail_template set subject = '".mysql_real_escape_string($subject)."', body = '".mysql_real_escape_string($body)."', tags='".mysql_real_escape_string($tags)."', cc_email = '".$cc_email."' where id = '".$id."'";
		$return			= $db->update($sql, $db->conn);
		return $return;
	}

	function showAllContents(){
		global $db;
		$sql			= "select * from mail_template order by trim(title)";
		$NewsResult	= $db->run_query($sql, $conn='');
		return $NewsResult;
	}

	/*function bulkActiveDeactiveTemp($ids, $active = 'N'){
		global $db;
		$sql			= "update mail_template set is_active = '".$active."' where id in (".$ids.")";
		$return			= $db->update($sql, $conn='');
		return $return;
	}*/

	function deleteTemp($ids){
		global $db;
		$sql			= "delete from mail_template where id in (".$ids.")";
		$return			= $db->delete($sql, $conn='');
		return $return;
	}

	function checkTempTitleAvailability($title){
		global $db;
		$sql= "select * from mail_template where title like '".$title."'";
		$newsDetail = $db->getRow($sql, $conn='');
		return $newsDetail;
	}
}
?>