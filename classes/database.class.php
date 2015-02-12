<?php
/******************************************
* @Modified on Dec 06, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

class db{

	public $conn;
	Public $DBHOST, $DBUSER, $DBPASS, $DBDATABASE;
	
	function db($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASSWORD, $DATABASE_DB){
		$this->DBHOST		=	$DATABASE_HOST;
		$this->DBUSER		=	$DATABASE_USER;
		$this->DBPASS		=	$DATABASE_PASSWORD;
		$this->DBDATABASE	=	$DATABASE_DB;

		$this->conn = mysql_connect( $this->DBHOST, $this->DBUSER, $this->DBPASS, true )or die('Error'.mysql_error());
		mysql_select_db( $this->DBDATABASE ) or die('<b>Error: '.mysql_error().'</b>');
	}

	function count_rows($result){
		return mysql_num_rows ( $result );
	}

	function insert($query, $conn = ''){
		if(is_object($conn)){
			$conn = $conn;
		} else {
			$conn = $this->conn;
		}
		
		try{
		$result = mysql_query ( $query, $conn ) or throw_ex(mysql_error());
		return mysql_insert_id($this->conn);
		}catch(exception $e) {
		  $_SESSION['msgerror'] = $e;
		  return 0;
		}
	}

	function update($query, $conn = ''){
		if(is_object($conn)){
			$conn = $conn;
		} else {
			$conn = $this->conn;
		}

		try{
			$result = mysql_query($query, $conn) or die(MYSQL_ERROR().': '.$query);
			if(mysql_affected_rows($conn)>0){
				return true;
			} else {
				return false;
			}
		}catch(exception $e) {
		  return false;
		}
	}

	function delete($query, $conn = ''){
		
		if(is_object($conn)){
			$conn = $conn;
		} else {
			$conn = $this->conn;
		}

		$result = mysql_query ( $query, $conn ) or die(MYSQL_ERROR().': '.$query);
		if(mysql_affected_rows($conn)>0){
			return true;
		} else {
			return false;
		}
	}

	function run_query($query, $conn = ''){
		
		if(is_object($conn)){
			$conn = $conn;
		} else {
			$conn = $this->conn;
		}

		$result = mysql_query ( $query, $conn ) or die(MYSQL_ERROR().': '.$query);
	
		return $result;
	}

	function getRow($query, $conn = ''){

		if(is_object($conn)){
			$conn = $conn;
		} else {
			$conn = $this->conn;
		}
		$result = mysql_query ( $query, $conn ) or die(MYSQL_ERROR().': '.$query);
		$rowDetail = mysql_fetch_assoc($result);
		return $rowDetail;
	}

	function fetch_row_assoc($result){
		$row = mysql_fetch_assoc ( $result ) or die(MYSQL_ERROR());
		return $row;
	}

	function fetch_row_object($result){
		$object = mysql_fetch_object ( $result ) or die(MYSQL_ERROR());
		return $object;
	}

	function getAll($result){
		$arrayData = array();
		while($row = mysql_fetch_assoc($result)){
			$arrayData[] = $row;
		}
		return $arrayData;
	}

	function getArray($resource,$type='')
	{
		if(is_resource($resource))
		{
			$tmp_arr = array();
			if(mysql_num_rows($resource)>0)
			{
				if($type=='')
				{
					while($row = mysql_fetch_array($resource))
					{
						$tmp_arr[] = $row;
					}
					return $tmp_arr;
				}
				elseif($type=='ASSOC')
				{
					while($row = mysql_fetch_assoc($resource))
					{
						$tmp_arr[] = $row;
					}
					return $tmp_arr;
				}
			}
			else
			{
				return mysql_fetch_assoc($resource);
			}
		}
		else
		{
			echo"Error in Query";
		}
	}

	
}
?>