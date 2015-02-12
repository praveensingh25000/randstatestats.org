<?php
/******************************************
* @Modified on June 28, 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.randstatestats.org
********************************************/

$admin				= new admin();
$user				= new user();
$current_ip_of_user = $current_ip; //$current_ip is defined in the headerHtml.php file

if(!empty($_SESSION['user']) && $_SESSION['user']['id']!=''){	
		
		$checksubmitType    = "true";
		$user_id            = $_SESSION['user']['id'];
		$userDetail			= $user->getUser($user_id);

		if($userDetail['user_type']=='6' && $userDetail['parent_user_id']!='0'){
			$database			= $siteMainDBDetail['id'];
			$explodedUri		= explode('/',$_SERVER['REQUEST_URI']);
			$parent_id			= $userDetail['parent_user_id'];
			$current_ip_of_user = '';
			//echo '<pre>';print_r($userDetail);echo '</pre>';die;
			preg_match('/form.php/',$explodedUri['1'],$matched);

			if((!empty($matched) && $matched['0']=='form.php') || in_array('stats',$explodedUri) || in_array('statsus',$explodedUri) || in_array('statsca',$explodedUri) || in_array('statsny',$explodedUri) || in_array('statstx',$explodedUri)){
				$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				$history = $user->insertLoginHistory($current_ip_of_user, $user_id, $parent_id, $url,$database);
			}
		}
		
		if(isset($_SESSION['user']['email']) && $_SESSION['user']['email'] !=''){					 
			$user_email		=	$_SESSION['user']['email'];
		} else if(isset($_SESSION['user']['username']) && $_SESSION['user']['username'] !='') {
			$user_email		=	$_SESSION['user']['username'];
		}

} else {

	$flag =0;

	if(isset($current_ip_of_user) && $current_ip_of_user !='') {		
		
		$ip_user_id	  = $admin->detech_user_current_location_ips($current_ip_of_user);	
		$userDetail	  =	$user->getUser($ip_user_id);

		if(!empty($userDetail) && isset($userDetail['id']) && $userDetail['id'] != '0') {
			
			$validity_on		   = $admin->Validity($userDetail['id'],$userDetail['email']);			
			
			if(isset($validity_on) && $validity_on != '0') {
				$checksubmitType   = "true"; 
				$user_email_ip	   = $userDetail['email'];
				$user_id           = $userDetail['id'];
				$user_email		   = $user_email_ip;
				$flag			   = 1;
				$database		   = $siteMainDBDetail['id'];
				$explodedUri	   = explode('/',$_SERVER['REQUEST_URI']);
				$parent_id		   = 0;
				
				if(isset($explodedUri['1'])){
					preg_match('/form.php/',$explodedUri['1'],$matched);
				}

				if((!empty($matched) && $matched['0']=='form.php') || in_array('stats',$explodedUri) || in_array('statsus',$explodedUri) || in_array('statsca',$explodedUri) || in_array('statsny',$explodedUri) || in_array('statstx',$explodedUri)){
					$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
					$history = $user->insertLoginHistory($current_ip_of_user, $user_id, $parent_id, $url,$database);
				}
			}
		} 
	}
	
	if($flag != '1'){
		$checksubmitType   = "false";
	}
}
?>