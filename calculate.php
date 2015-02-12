<?php
/******************************************
* @Modified on July 9, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/";
include_once $basedir."/include/actionHeader.php";

$jsonArray = array('totalamount' => 0, 'surcharge' => 0, 'minimumamount' => 0, 'minimumapplicable' => 0, 'states' => 0, 'surchargeapplicable' => 0, 'txtper' => '', 'txtdollar' => '', 'discounttxt' => '', 'discount' => 0, 'discountamount' => 0);

$discounts = array(1 => ONE_YEAR, 2 => TWO_YEAR, 3 => THREE_YEAR);

if(isset($_GET['plan_name']) && isset($_GET['user_type']) && isset($_GET['db_name'])){

	$user = new user();

	$planyears = $_GET['plan_name'];

	$user_type = $_GET['user_type'];

	$db_names = $_GET['db_name'];

	$onlyus = 0;

	if(count($db_names) == 1){
		$db_name = $db_names[0];
		if($db_name == 4){
			$onlyus = 1;
		}
	}

	$totalstates = -1;

	if(count($db_names) > 0){
		foreach($db_names as $keys => $state){
			if($state!='4'){
				$totalstates++;
			}
		}
	}


	if(isset($_REQUEST['number_of_users'])){
		$number_of_users = $_REQUEST['number_of_users'];
	} else if(isset($_SESSION['user']['number_of_users'])) {
		$number_of_users = $_SESSION['user']['number_of_users'];	
	} else if(isset($_SESSION['data']['number_of_users'])) {
		$number_of_users = $_SESSION['data']['number_of_users'];
	} else {
		$number_of_users = 1;								
	}

	/**************** User type info ***********/

	$userTypeInfo = $user->getUserTypeInfo($user_type);

	$baseprice = (isset($userTypeInfo['baseprice']))?$userTypeInfo['baseprice']:'';
	$basepriceus = (isset($userTypeInfo['basepriceus']))?$userTypeInfo['basepriceus']:'';

	$basepriceindividual = (isset($userTypeInfo['basepriceindividual']))?$userTypeInfo['basepriceindividual']:'';

	$basepriceusindividual = (isset($userTypeInfo['basepriceusindividual']))?$userTypeInfo['basepriceusindividual']:'';

	$minimumprice = (isset($userTypeInfo['minimumprice']))?$userTypeInfo['minimumprice']:'';

	$pricepercentage = (isset($userTypeInfo['pricepercentage']))?$userTypeInfo['pricepercentage']:'';

	$surchargeonestate = (isset($userTypeInfo['surchargeonestate']))?$userTypeInfo['surchargeonestate']:'';

	$surchargetwostate = (isset($userTypeInfo['surchargetwostate']))?$userTypeInfo['surchargetwostate']:'';

	$surchargethreestate = (isset($userTypeInfo['surchargethreestate']))?$userTypeInfo['surchargethreestate']:'';

	$surchargeonestateus = (isset($userTypeInfo['surchargeonestateus']))?$userTypeInfo['surchargeonestateus']:'';

	$surchargetwostateus = (isset($userTypeInfo['surchargetwostateus']))?$userTypeInfo['surchargetwostateus']:'';

	$surchargethreestateus = (isset($userTypeInfo['surchargethreestateus']))?$userTypeInfo['surchargethreestateus']:'';

	$surchargeonestateindividual = (isset($userTypeInfo['surchargeonestateindividual']))?$userTypeInfo['surchargeonestateindividual']:'';

	$surchargetwostateindividual = (isset($userTypeInfo['surchargetwostateindividual']))?$userTypeInfo['surchargetwostateindividual']:'';

	$surchargethreestateindividual = (isset($userTypeInfo['surchargethreestateindividual']))?$userTypeInfo['surchargethreestateindividual']:'';

	$surchargeonestateusindividual = (isset($userTypeInfo['surchargeonestateusindividual']))?$userTypeInfo['surchargeonestateusindividual']:'';

	$surchargetwostateusindividual = (isset($userTypeInfo['surchargetwostateusindividual']))?$userTypeInfo['surchargetwostateusindividual']:'';

	$surchargethreestateusindividual = (isset($userTypeInfo['surchargethreestateusindividual']))?$userTypeInfo['surchargethreestateusindividual']:'';

	/**************** User type info Ends ***********/

	$round = 2;

	if($onlyus == 0){
		$baseprice = $baseprice;

		if($totalstates == 1){
			$surcharge = $surchargeonestate;
		} else if($totalstates == 2){
			$surcharge = $surchargetwostate;
		} else {
			$surcharge = 0;
		}

	} else {
		$baseprice = $basepriceus;
		$surcharge = 0;
	}

	$posfind = explode('.',$baseprice);
	
	if (isset($posfind[1])) {
		$round = strlen($posfind[1]);
	}

	$basepricetotal = $number_of_users * $baseprice;

	$surcharegtotal = 0;

	/*echo "No Of Years: ".$planyears."<br/><br/>";

	echo "Total States: ".$totalstates."<br/><br/>";

	echo "No Of Users: ".$number_of_users."<br/><br/>";

	echo "Price Percentage: ".$pricepercentage."<br/><br/>"; */


	if($user_type == 6){
		if($onlyus == 0){
			$basepricefirstlogin = $baseprice;
			$basepriceindividuallogin = $basepriceindividual;

			if($totalstates == 1){

				$surchargefirstlogin = $surchargeonestate;
				$surchargeindividuallogin = $surchargeonestateindividual;

			} else if($totalstates == 2){

				$surchargefirstlogin = $surchargetwostate;
				$surchargeindividuallogin = $surchargetwostateindividual;

			} else {
				$surchargefirstlogin = 0;
				$surchargeindividuallogin = 0;
			}

		} else {
			$basepricefirstlogin = $basepriceus;
			$basepriceindividuallogin = $basepriceusindividual;

			$surchargefirstlogin = 0;
			$surchargeindividuallogin = 0;
		}
	}
	

	if($pricepercentage == 0 && $user_type != '6'){	// Percentage amount

		$surcharegtotal = ($basepricetotal * (($surcharge)/100));

	} else if($pricepercentage == 1 && $user_type != '6'){	// Exact amount

		$surcharegtotal = $surcharge * $number_of_users;

	} else if($pricepercentage == 0 && $user_type == '6'){	// Mutiple Users
		

		// First Login
		$basepricetotalfirstlogin = 1 * $basepricefirstlogin;
		$surchargetotalfirstlogin =  ($basepricetotalfirstlogin * (($surchargefirstlogin)/100));


		// Each Additional Login
		$basepricetotalindividuallogin = $number_of_users * $basepriceindividuallogin;	
		$surchargetotalindividuallogin =  ($basepricetotalindividuallogin * (($surchargeindividuallogin)/100));


		$basepricetotal = $basepricetotalfirstlogin + $basepricetotalindividuallogin;
		$surcharegtotal = $surchargetotalfirstlogin + $surchargetotalindividuallogin;

	}else if($pricepercentage == 1 && $user_type == '6'){	// Mutiple Users
		
		// First Login
		$basepricetotalfirstlogin = 1 * $basepricefirstlogin;
		$surchargetotalfirstlogin =  1 * $surchargefirstlogin;


		// Each Additional Login
		$basepricetotalindividuallogin = $number_of_users * $basepriceindividuallogin;	
		$surchargetotalindividuallogin =  $surchargeindividuallogin * $number_of_users;


		$basepricetotal = $basepricetotalfirstlogin + $basepricetotalindividuallogin;
		$surcharegtotal = $surchargetotalfirstlogin + $surchargetotalindividuallogin;
		
		//echo "/************** Base Price Calculations ***************************/ <br/><br/>";
		
		/*echo "Base Price First Login : ".$basepricefirstlogin."<br/><br/>";

		echo "Base Price Total First Login : ".$basepricetotalfirstlogin."<br/><br/>";

		echo "Base Price Each Additional Login: ".$basepriceindividuallogin."<br/><br/>";

		echo "Base Price Total Each Additional Login: ".$basepricetotalindividuallogin."<br/><br/>";

		echo "Base Price Total (First + Individual): ".$basepricetotal."<br/><br/>";*/

		//echo "/************** Surcharge Calculations ***************************/ <br/><br/>";
		
		/*echo "Surcharge First Login : ".$surchargefirstlogin."<br/><br/>";

		echo "Surcharge Total First Login : ".$surchargetotalfirstlogin."<br/><br/>";

		echo "Surcharge Each Additional Login : ".$surchargeindividuallogin."<br/><br/>";

		echo "Surcharge Total Each Additional Login : ".$surchargetotalindividuallogin."<br/><br/>";

		echo "Surcharge Total (First + Individual): ".$surcharegtotal."<br/><br/>"; */

	}

	if($user_type != '6'){

		/*echo "Base Price: ".$baseprice."<br/><br/>";

		echo "Surcharge: ".$surcharge."<br/><br/>";

		echo "Base Price Total: ".$basepricetotal."<br/><br/>";

		echo "Surcharge Total: ".$surcharegtotal."<br/><br/>"; */
	}

	

	$totalamount = $surcharegtotal + $basepricetotal;

	//echo "Total: ".$totalamount."<br/><br/>";

	$chargeableamount = $totalamount;

	if($user_type == '5' && $surcharge != 0){
		$chargeableamount = $surcharge;
		$surcharegtotal = $surcharge;
	}
	
	$minimumapplicable = 0;

	if(($chargeableamount < $minimumprice) && $minimumprice>0){
		$chargeableamount = $minimumprice;
		$minimumapplicable = 1;
	}

	$chargeableamount = $planyears * $chargeableamount;


	
	if(isset($discounts[$planyears]) && $discounts[$planyears]>0){
		$discount = $discounts[$planyears];
		$discounttxt = "Discount <br/> (".$discount."% of ".$chargeableamount." for ".$planyears." years)";

		$discountamount = ($discount/100) * $chargeableamount;

		$chargeableamount = $chargeableamount - $discountamount;

		$jsonArray['discountamount']	= round($discountamount, $round);
		$jsonArray['discount']			= $discount;
		$jsonArray['discounttxt']		= $discounttxt;
	}

	//echo "Chargeable Amount: ".$chargeableamount."<br/><br/>";


	$jsonArray['totalamount']			= round($chargeableamount,$round);
	$jsonArray['surcharge']				= round($surcharegtotal, $round);
	$jsonArray['minimumamount']			= $minimumprice;
	$jsonArray['surchargeapplicable']	= $surcharge;
	$jsonArray['states']				= $totalstates;
	$jsonArray['minimumapplicable']		= $minimumapplicable;

	if($pricepercentage == 0){
		$jsonArray['txtper'] = "%";
		$jsonArray['txtdollar'] = "";
	} else {
		$jsonArray['txtper'] = "";
		$jsonArray['txtdollar'] = "$";
	}



}

echo json_encode($jsonArray);
?>