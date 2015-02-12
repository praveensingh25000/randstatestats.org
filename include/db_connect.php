<?php
$database_purchased_array  = $userDatabaseArray_out = $userDatabaseArray = $userDatabase_out = $database_purchased_array_unique = $array = $databaseUserAlreadyBought = array();
$database_purchased_str   = $userDatabaseArray_outStr = '';

if(isset($_GET['dbc']) && $_GET['dbc']!='') {

	$db_select_on_fly_dbc = trim(base64_decode($_GET['dbc']));

	$_SESSION['databaseToBeUseajax'] = $db_select_on_fly_dbc;
	$siteMainDBDetail                = $admin->getMainDbDetail($db_select_on_fly_dbc);

	if(isset($_SESSION['user']['id'])) {	
		
		$check_valid_database = $admin->checkValidityDatabaseOnSearch($siteMainDBDetail['id'],$_SESSION['user']['id']);

		if(isset($_SESSION['cat'])) { $categoryid=$_SESSION['cat'];}
		else if(isset($_SESSION['cat'])) { $categoryid=$_SESSION['categoryid'];}
		else {  $categoryid= 1; };

		if(isset($check_valid_database) && $check_valid_database == 0) {

			$dbArray	=	explode('_',$db_select_on_fly_dbc);
			$db_name	=	'<b>'.strtoupper($dbArray[1]).'</b>';

			$_SESSION['infomsg'] = "You have not purchased any plan for  ".$db_name." Database.Please purchase a new plan or upgrade your plan";
			header('location: '.URL_SITE.'/forms.php?cat='.$categoryid);
			exit;
		}
	}

	$dbDatabase =  new db(DATABASE_HOST, $siteMainDBDetail['databaseusername'], $siteMainDBDetail['databasepassword'], $siteMainDBDetail['databasename']);

	if(isset($_GET['url']) && $_GET['url']!='' && !isset($_GET['formid'])) {

		$redirect = URL_SITE.'/'.$_GET['url'].'?dbc='.$_GET['dbc'];
		header('location: '.$redirect.'');
		exit;

	} else if(isset($_GET['formid']) && $_GET['formid']!='' && !isset($_GET['url'])) {

		$redirect = URL_SITE.'/form.php?id='.$_GET['formid'].'&dbc='.$_GET['dbc'];
		header('location: '.$redirect.'');
		exit;
	}

} else if(isset($_GET['db']) && $_GET['db']!=''){

	if(isset($_SESSION['databaseToBeUseajax'])) { unset($_SESSION['databaseToBeUseajax']);}
	if(isset($_SESSION['databaseToBeUse'])) { unset($_SESSION['databaseToBeUse']);}
	if(isset($_SESSION['categoryid'])) { unset($_SESSION['categoryid']);}
	if(isset($_SESSION['cat'])) { unset($_SESSION['cat']);}		
	if(isset($_SESSION['searchedfieldsonestage'])) { unset($_SESSION['searchedfieldsonestage']);}
	if(isset($_SESSION['twostageurlset'])) { unset($_SESSION['twostageurlset']); }

	$check_db_userid='';

	if(isset($_SESSION['user']['id']) && $_SESSION['user']['id']!='') {
		$check_db_userid =$_SESSION['user']['id'];
	} else if(isset($current_ip) && $current_ip!='') {
		$check_db_userid =$admin->detech_user_current_location_ips($current_ip);
	}	

	if(isset($check_db_userid) && $check_db_userid!='' && $check_db_userid!='0') {
		$siteMainDBDetail = $admin->getMainDbDetail($_GET['db']);
		if(!empty($siteMainDBDetail)) {

			if(isset($_SESSION['user']['parent_user_id']) && $_SESSION['user']['parent_user_id']!=0){
				$check_db_userid = $_SESSION['user']['parent_user_id'];
			}

			$check_valid_database = $admin->checkValidityDatabaseOnSearch($siteMainDBDetail['id'],$check_db_userid);

			if(isset($check_valid_database) && $check_valid_database == 0) {

				$dbArray	=	explode(' ',$siteMainDBDetail['database_label']);
				$db_name	=	'<b>'.strtoupper(trim($dbArray[1])).'</b>';
				
				$_SESSION['infomsg'] = "You have not purchased any plan for  ".$db_name." Database. Please purchase a new plan or upgrade your plan";
				header('location: index.php');
				exit;
			}
		}
	}

	$_SESSION['databaseToBeUse'] = $_GET['db'];
	$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
	$dbDatabase       = new db(DATABASE_HOST, $siteMainDBDetail['databaseusername'], $siteMainDBDetail['databasepassword'], $siteMainDBDetail['databasename']);

	$dbArray	=	explode(' ',$siteMainDBDetail['database_label']);
	$db_name	=	'<b>'.strtoupper(trim($dbArray[1])).'</b>';

	$_SESSION['infomsg'] = "You have selected ".$db_name." database.";

	header('location: '.URL_SITE.'/changeDatabase.php');
	exit;

} else if(isset($_POST['databaseToBeUse']) && $_POST['databaseToBeUse']!='') {

	if(isset($_SESSION['databaseToBeUseajax'])) { unset($_SESSION['databaseToBeUseajax']);}
	if(isset($_SESSION['databaseToBeUse'])) { unset($_SESSION['databaseToBeUse']);}
	if(isset($_SESSION['categoryid'])) { unset($_SESSION['categoryid']);}
	if(isset($_SESSION['cat'])) { unset($_SESSION['cat']);}	
	if(isset($_SESSION['searchedfieldsonestage'])) { unset($_SESSION['searchedfieldsonestage']);}	
	if(isset($_SESSION['twostageurlset'])) { unset($_SESSION['twostageurlset']); }

	$_SESSION['databaseToBeUse'] = trim($_POST['databaseToBeUse']);
	$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
	$dbDatabase = $dbtaxas =  new db(DATABASE_HOST, $siteMainDBDetail['databaseusername'], $siteMainDBDetail['databasepassword'], $siteMainDBDetail['databasename']);

} else if(isset($_SESSION['user']['id']) && $_SESSION['user']['id']!='') {

	if(isset($_SESSION['databaseToBeUseajax'])) { unset($_SESSION['databaseToBeUseajax']);}

	$user_id_session = $_SESSION['user']['id'];
	$userDatabaseArray = $admin->selectValidDatabaseofUser($user_id_session);

	if(!empty($userDatabaseArray)) {	
		if(isset($_SESSION['legalDatabaseUser'])) { unset($_SESSION['legalDatabaseUser']);}
		foreach($userDatabaseArray as $key => $dbAll) {				
			$_SESSION['legalDatabaseUser'][] = $dbAll;
			$dbDetail = $admin->getMainDbByIdDetail($dbAll[0]);
			$databaseUserAlreadyBought[] = $dbDetail['databasename'];
		}
	} else{
		if(isset($_SESSION['legalDatabaseUser'])) { unset($_SESSION['legalDatabaseUser']);}
	}

	if(count($databaseUserAlreadyBought)>0 && isset($_SESSION['databaseToBeUse']) && !in_array($_SESSION['databaseToBeUse'], $databaseUserAlreadyBought)){
		$dbuserpurchasedfirst = $databaseUserAlreadyBought[0];
		$_SESSION['databaseToBeUse'] = $dbuserpurchasedfirst;
	}
	
	$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
	$dbDatabase = $dbtaxas =  new db(DATABASE_HOST, $siteMainDBDetail['databaseusername'], $siteMainDBDetail['databasepassword'], $siteMainDBDetail['databasename']);

} else {

	if(isset($current_ip) && $current_ip !='') {
		
		$dbdefault = $dbDetail ='';	
		$ip_user_id_set	   = $admin->detech_user_current_location_ips($current_ip);		
		$userDatabaseArray = $admin->selectValidDatabaseofUser($ip_user_id_set);

		if(!empty($userDatabaseArray)) {	

			if(isset($_SESSION['legalDatabaseUser'])){
				unset($_SESSION['legalDatabaseUser']);
			}

			foreach($userDatabaseArray as $key => $dbAll) {	
		
				$_SESSION['legalDatabaseUser'][] = $dbAll;				
				if(count($_SESSION['legalDatabaseUser']) > 1) {
					if(in_array('4',$_SESSION['legalDatabaseUser'])){
						foreach($_SESSION['legalDatabaseUser'] as $valuesDb){
							if($valuesDb != '4'){
								$dbdefault	= $valuesDb;
								$dbDetail   = $admin->getMainDbByIdDetail($dbdefault);
								break;
							}
						}
					} else {
						$dbDetail			 = $admin->getMainDbByIdDetail($dbAll[0]);
					}
				} else {
					$dbDetail				 = $admin->getMainDbByIdDetail($dbAll[0]);
				}
			}

			if(!empty($dbDetail)){
			$databaseUserAlreadyBought[] = $dbDetail['databasename'];
			}

		} else {
			if(isset($_SESSION['legalDatabaseUser'])) { unset($_SESSION['legalDatabaseUser']);}
		}

		if(count($databaseUserAlreadyBought)>0 && !isset($_SESSION['databaseToBeUse'])) {

			$dbuserpurchasedfirst		 = $databaseUserAlreadyBought[0];
			$_SESSION['databaseToBeUse'] = $dbuserpurchasedfirst;

		} else if(count($databaseUserAlreadyBought)>0 && isset($_SESSION['databaseToBeUse']) && !in_array($_SESSION['databaseToBeUse'], $databaseUserAlreadyBought)){			

			$dbuserpurchasedfirst        = $_SESSION['databaseToBeUse'];
			$_SESSION['databaseToBeUse'] = $dbuserpurchasedfirst;

		} else {

			if(isset($_SESSION['databaseToBeUseajax'])) { unset($_SESSION['databaseToBeUseajax']);}

			if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!='') {
				$_SESSION['databaseToBeUse'] = trim($_SESSION['databaseToBeUse']);
			}else{
				$_SESSION['databaseToBeUse'] = 'rand_usa';
			}
		}

	} else {

		if(isset($_SESSION['databaseToBeUseajax'])) { unset($_SESSION['databaseToBeUseajax']);}

		if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!='') {
			$_SESSION['databaseToBeUse'] = trim($_SESSION['databaseToBeUse']);
		}else{
			$_SESSION['databaseToBeUse'] = 'rand_usa';
		}
	}

	$siteMainDBDetail = $admin->getMainDbDetail($_SESSION['databaseToBeUse']);
	$dbDatabase = $dbtaxas =  new db(DATABASE_HOST, $siteMainDBDetail['databaseusername'], $siteMainDBDetail['databasepassword'], $siteMainDBDetail['databasename']);
}

$googleKey = 'AIzaSyAgmdNJbGuHyDDQYns0RZxwVb1o8VBtADU';

if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']!=''){
	$dbArray		=	explode('_',$_SESSION['databaseToBeUse']);
	$database_label	=	strtoupper($dbArray[0].' '.$dbArray[1]);
}
?>