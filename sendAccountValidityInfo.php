<?php
/******************************************
* @Created on Nov 11,2013
* @Package: RAND
* @Developer:Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

$admin	  = new admin();
$user	  = new user();
$emailObj = new emailTemp();

$number_of_validity = 0;

$dbcodeArray = $dbcodeArrayMain = $userMailArrayList  = $userMailArray		= $chuserMailArray	= array();
$dbIdStr = $chikogouseridStr	= $noinDbcode = '';

//delete all renew mail sent user 
//$deletemailsentUserlist	= "DELETE FROM rand_admin.account_renews where is_sent=1 ";
//$resultAllList  = mysql_query($deletemailsentUserlist);
//echo 'Deleted Succussfully';

//selecting user list to send a mail
$sql_user_list	    = "SELECT * FROM rand_admin.account_renews where is_sent=0 ORDER BY RAND() LIMIT 7 ";
$resultuserAllList  = mysql_query($sql_user_list);
if(isset($resultuserAllList) && mysql_num_rows($resultuserAllList) > 0){
	while($userList = mysql_fetch_assoc($resultuserAllList)){

		//updating Sent Mail User
		$update_user = $user->updateUserMailRenew($userList['id']);	
		
		//sending mail to all Users
		if($userList['email'] != '') {
			//$receivermailUser	  =	trim($userList['email']);
			$receivermailUser	  =	'singh.multani1984@gmail.com';
			//$receivermailAdmin  =	'singh.multani1984@gmail.com';
			$receivermailAdmin    =	'shalini@ideafoundation.co.in';
			//$receivermailAdmin  =	trim(FROM_EMAIL);
			$receivername		=	'';
			$from_name			=	trim(FROM_NAME);
			$from_email			=	trim(FROM_EMAIL);
			$number_of_validity =   trim($userList['number_of_days']);
			$number_of_database =   trim($userList['dbnames']);

			$mailbody	=	'<!DOCTYPE html>
							 <html>
							 <head>
								 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
								 <title>randstatestats</title>
							 </head>
							 <body>

								<div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;">

								<div style="width:100%;height:auto;overflow:hidden;text-align:left;display:block;">
								    <span style="float:left;"><img src="'.$URL_SITE.'/images/logo.png" /></span>
									<span style="float:left;padding:28px 0px 0px 25px;">
									   <h3 style="color:#663399;line-height: normal;">State Statistics <br> <span>A service of the rand corporation</span></h3>
									</span>							   
								</div>
								<div style="clear:both; padding: 10px 0;"></div>

								<div style="padding:10px 0px 10px 10px;">
								  Your subscription to RAND '.$number_of_database.' will expire in '.$number_of_validity.' days. To avoid any interruption in service, please login to your account at <a href="'.$URL_SITE.'">'.$URL_SITE.'</a>. Choose Hello/My Account to renew your subscription. If you have any questions, please contact me by email or phone.								  
								</div>
								<div style="clear:both; padding: 10px 0;"></div>

								<div style="padding:10px 0px 10px 10px;">
								Many thanks.		
								</div>
								<div style="clear:both; padding: 10px 0;"></div>

								<div style="padding:10px 0px 10px 10px;">
									<strong>Best regards</strong>,<br/>
									Joe Nation, Ph.D.<br/>
									Director<br/>
									'.$from_name.'<br/>
									<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
									<a href="mailto:'.$from_email.'">'.$from_email.'</a><br />
									Office: 800-492-7959<br />Mobile: 415-602-2973							
								</div>
								
							</div>

						</body>

					</html>';

			$subject   ='Your RAND State Statistics subscription will expire soon!';	
			$send_mail_user  = mail_function($receivername,$receivermailUser,$from_name,$from_email,$mailbody,$subject, $attachments = array(),$addcc=array());
			$send_mail_admin = mail_function($receivername,$receivermailAdmin,$from_name,$from_email,$mailbody,$subject, $attachments = array(),$addcc=array());
		}
	}

} else {

	$sql_all_list	= "SELECT  u.id as user_id, u.name, u.last_name, u.email, du.is_active, du.expire_time, du.db_id, du.is_trial FROM rand_admin.users u JOIN rand_admin.database_users du on u.id = du.user_id where du.is_active=1 and du.is_trial=1 and u.is_deleted =0 and u.active_status=1 and u.block_status=0 ";
	$resultAllList  = mysql_query($sql_all_list);
	if(mysql_num_rows($resultAllList) > 0){
		while($rowList  = mysql_fetch_assoc($resultAllList)){
			$userMailArrayList[$rowList['user_id']]['name']			   = $rowList['name'];
			$userMailArrayList[$rowList['user_id']]['last_name']	   = $rowList['last_name'];
			$userMailArrayList[$rowList['user_id']]['email']		   = $rowList['email'];
			$userMailArrayList[$rowList['user_id']]['is_trial']		   = $rowList['is_trial'];
			$userMailArrayList[$rowList['user_id']]['db_id'][$rowList['db_id']] = $rowList['db_id'];
		}
	}
	if(!empty($userMailArrayList)){	
		foreach($userMailArrayList as $user_id => $userDetail){
			if(isset($dbcodeArrayMain)){unset($dbcodeArrayMain);}
			if($userDetail['email'] != '') {
				$number_of_validity	=	$admin->Validity($user_id,$userDetail['email']);					
				if(!empty($userDetail['db_id'])){
					$dbIdStr	 = implode(',',$userDetail['db_id']);
					$dbcodeArray = $admin->getMainDatabasesPurched($dbIdStr);
					if(!empty($dbcodeArray)) {
						foreach($dbcodeArray as $dbDetail){
							$dbcodeArrayMain[] = $dbDetail['db_code'];
						}				
					}
					if(!empty($dbcodeArrayMain)) {					
						$dbcodeStr	 = implode(',',$dbcodeArrayMain);
					}	
				}
				if($number_of_validity == '30'){
					$userMailArray[$user_id] = $userDetail;				
					$userMailArray[$user_id]['number_of_validity'] = $number_of_validity;
					$userMailArray[$user_id]['dbcodeStr']		   = $dbcodeStr;
				} else if($number_of_validity == '15'){
					$userMailArray[$user_id] = $userDetail;				
					$userMailArray[$user_id]['number_of_validity'] = $number_of_validity;
					$userMailArray[$user_id]['dbcodeStr']		   = $dbcodeStr;
				} else if($number_of_validity == '7'){
					$userMailArray[$user_id] = $userDetail;				
					$userMailArray[$user_id]['number_of_validity'] = $number_of_validity;
					$userMailArray[$user_id]['dbcodeStr']		   = $dbcodeStr;
				} else if($number_of_validity == '3'){
					$userMailArray[$user_id] = $userDetail;				
					$userMailArray[$user_id]['number_of_validity'] = $number_of_validity;
					$userMailArray[$user_id]['dbcodeStr']		   = $dbcodeStr;
				}
			}
		}
	}
	if(!empty($userMailArray)){	
		//inserting user for sending a mail
		foreach($userMailArray as $userid => $validityUsers){
			$inset_user = $user->insertUserMailRenew($userid, $validityUsers['email'], $validityUsers['number_of_validity'], $validityUsers['dbcodeStr']);			
		}
	} 
}
?>