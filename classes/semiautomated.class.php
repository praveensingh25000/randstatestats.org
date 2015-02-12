<?php
/******************************************
* @Modified on Jan 24, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

class datareader{

	function getUspopestAreasLike($stateCode){
		global $dbDatabase;
		$sqlAll = "select * from uspopest_areas where areacode like '".(int)$stateCode."%' order by areaname";
		$resultSqlAll = $dbDatabase->run_query($sqlAll, $dbDatabase->conn);
		$dataSqlAll = $dbDatabase->getAll($resultSqlAll);
		return $dataSqlAll;
	}

	function getUsAirportAreas(){
		global $dbDatabase;
		$sqlAll = "select * from airportsUS_areas order by areaname";
		$resultSqlAll = $dbDatabase->run_query($sqlAll, $dbDatabase->conn);
		$dataSqlAll = $dbDatabase->getAll($resultSqlAll);
		return $dataSqlAll;
	}
}


?>