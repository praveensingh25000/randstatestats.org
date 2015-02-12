<?php
/******************************************
* @Modified on July 10,2013
* @Package: RAND
* @Developer:Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/adminHeader.php";
//global $dbDatabase;
$sql= "SELECT * FROM rand_admin.users";
$result = $db->run_query($sql, $db->conn);

while($value = mysql_fetch_assoc($result)){

	$user_id = $value['id'];
	$noofusers = $value['number_of_users'];
	echo $sql= "update rand_admin.transaction_record set no_of_users = '".$noofusers."' where user_id = '".$user_id."'";
	$db->run_query($sql, $db->conn);
}


?>