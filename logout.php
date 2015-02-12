<?php
/******************************************
* @Modified on Dec 18, 2012
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/
ob_start();
session_start();
session_destroy();
header('location: index.php');
?>