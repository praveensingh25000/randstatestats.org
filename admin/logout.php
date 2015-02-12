<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
ob_start();
session_start();
session_destroy();
header('location: index.php');
?>