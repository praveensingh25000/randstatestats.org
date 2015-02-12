<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
* This file will include all the global functions that will be used through out the project.

*INDEX:

	1	-	Dummy Mails of Chichago
	2	-	user Registration mail
	3	-	Dummy Mail for CA subscribers
	4	-	Dummy Mail for US subscribers
	5	-	Dummy Mail for NY subscribers
	6	-	Dummy Mail for TX subscribers
	7	-	Dummy Mail for Forgot Password
	8	-	Dummy Mail for Contact Us
	9	-	Dummy Mail for 
	10  -	Mail when admin changes username or email
	11  -   Ip added Mail	

********************************************/

function getMailTemplate($mailbodykey, $receivename, $receivermail, $from_name, $from_email, $userDefinedvalue=array()){

	global $URL_SITE,$DOC_ROOT;
	require_once($DOC_ROOT.'classes/emailTemp.class.php');
	$emailObj = new emailTemp();

	$mailbody ='';
	$emailBody = '';

	if(!empty($userDefinedvalue)){
		foreach($userDefinedvalue as $key => $valuesAll){
			foreach($valuesAll as $userkey => $uservalues){
				define(strtoupper($userkey),$uservalues);			
			}
		}
	}
	
	//dummy registration Mail
	if($mailbodykey == '1'){

		$mailbody ='<!DOCTYPE html>
					<html>
					 <head>
					  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					  <title>randstatestats</title>
					 </head>
					 <body>
						 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;">
							 
							<div style="width:100%;height:auto;overflow:hidden;text-align:left;padding-bottom:5px;display:block;">		
							<img src="'.$URL_SITE.'/images/logo.png" />
							<div style="clear:both; padding: 10px 0 10px 0;"><br/></div>
							</div>

							<div style="padding:10px 0px 10px 10px;">
							   Hi '.$receivename.'<br/><br/>
							   Thanks for stopping by our booth last week in Chicago.  Your RAND State Statistics account has been created successfully. Your free trial period will last for 30 days.
							<div style="clear:both; padding: 10px 0;"><br/></div>
							</div>		
							
							<div style="background:#EBEBEB;padding:10px;">
								<strong style="padding:10px 0px 20px 0px;">Here are your login details:</strong><br /><br />
								<strong>User ID:&nbsp;&nbsp;</strong>'.$receivermail.'<br />
								<strong>Password:&nbsp;&nbsp;</strong>testing
							<div style="clear:both;"><br/></div>
							</div>		
							
							<div style="padding:10px 0px 10px 10px;">
							   Please click the link below to login.  After logging in, you should change your password by editing your profile at Hello/Edit Profile.<br/><br/>
							   <a href="'.$URL_SITE.'">'.$URL_SITE.'</a>
							<div style="clear:both;"><br/><br/></div>
							</div>

							<div style="padding:10px 0px 10px 10px;">
								If you are having trouble clicking on this link, please copy and paste it into your browser.
							<div style="clear:both;"><br/><br/></div>
							</div>
							

							<div style="padding:10px 0px 10px 10px;">
							   With hundreds of detailed databases covering all 50 states (and more than 100 to be added over the next two months), we are sure you will like the site. At the end of the trial period, please subscribe to one of our plans through the Subscribe link at the top of our home page. You can see the plans listed through the link Subscription Plans.
							<div style="clear:both;"><br/><br/></div>
							</div>		

							<div style="padding:10px 0px 10px 10px;">
							Please let me know if I can be of further assistance.
							<div style="clear:both;"><br/><br/></div>
							</div>		

							<div style="padding:10px 0px 10px 10px;">
								<strong>Best regards</strong>,<br/><br/>
								Joe Nation, Ph.D.<br/>
								Director<br/>
								'.$from_name.'<br/>
								<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
								<a href="mailto:'.$from_email.'">'.$from_email.'</a><br />
								Office: 415-785-4993<br />Mobile: 415-602-2973
							<div style="clear:both;"><br/><br/></div>
							</div>		

							<div style="padding:10px 0px 10px 10px;">
							P.S. Sean Y. from Western Connecticut State won the drawing for two free airline tickets.  Thanks to all who entered.  You can try again at ALA Mid-Winter in Philly or ALA next June in Las Vegas.  We hope to see you there.			
							<div style="clear:both;"></div>
							</div>		
							
						</div>
					 </body>
					</html>
				   ';

	}

	//user Registration mail
	if($mailbodykey == '2'){	
		$tempid = '2';
		$tempDetail = $emailObj->getTemp($tempid);
		if(!empty($tempDetail)){
			$emailBody	 = stripslashes($tempDetail['body']);
			$email_tags	 = $tempDetail['tags'];
		}

		$tagsArray = explode(';', $email_tags);

		foreach($tagsArray as $key => $tag){			

			$constantname = str_replace('#','',trim($tag));			
			
			if(!defined($constantname)){
				$emailBody = str_replace($tag, '', $emailBody);
			} else {
				
				$emailBody = str_replace($tag,constant($constantname), $emailBody);
			}
		}

		$mailbody	=	'<!DOCTYPE html>
						<html>
						 <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						  <title>randstatestats</title>
						 </head>
						 <body>
							 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;">
							 
							 <div style="width:100%;height:auto;overflow:hidden;text-align:left;padding-bottom:20px;display:block;"><img src="'.$URL_SITE.'/images/logo.png" />
							 <div style="clear:both; padding: 10px 0;"></div>
							 </div>';

			$mailbody	.=	$emailBody;

			$mailbody	.=	'<div style="padding:10px 0px 10px 10px;">
								<strong>Best regards</strong>,<br/><br/>
								Joe Nation, Ph.D.<br/>
								Director<br/>
								'.$from_name.'<br/>
								<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
								<a href="mailto:'.$from_email.'">'.$from_email.'</a><br />
								Office: 415-785-4993<br />Mobile: 415-602-2973
							<div style="clear:both;"><br/><br/></div>
							</div>
							
						</div>
					</body>
				</html>';
	}

	//for RAND CA subscribers
	if($mailbodykey == '3'){	

		$mailbody	= '<!DOCTYPE html>
						<html>
						 <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						  <title>randstatestats</title>
						 </head>
						 <body>
							 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;"><div style="width:100%;height:auto;overflow:hidden;text-align:left;padding-bottom:20px;display:block;"><img src="'.$URL_SITE.'/images/logo.png" /><div style="clear:both"></div></div>
							 
								<div style="width:100%;height:auto;overflow:hidden;padding-bottom:20px">
									You are receiving this email as a subscriber to RAND California, or RAND CA. We appreciate your continued use of the site and wanted to pass on to you some recent improvements. Here are the highlights.<br /><br />
								
									<strong>New URL</strong><br /><br />
									<ul style="line-height:22px;">
										<li>Starting today, you will be able to access RAND CA and its 238 databases at <a href="'.$URL_SITE.'">http://randstatestats.org</a>. If you are interested in expanding your access to include 77 detailed databases on NY, or TX, please let me know.</li>
										<li style="padding-top:10px;">If we <b>authenticate your access via IP</b>, you do not need to take any action. You are now authenticated for RAND CA. However, you should login with your User ID below in order to set up administrative functions, such as updating your profile, accessing usage statistics, or performing other administrative tasks.</li>
										<li style="padding-top:10px;">If you <b>access the site with a login and password</b>, please login with your new User ID (located below) and re-set your password.</li>
										<li style="padding-top: 10px;">You will still be able to access the "classic" RAND CA site at <a href="http://ca.rand.org">http://ca.rand.org</a> for the next several months. However, we will not update the databases on this site. You should access the new URL (<a href="'.$URL_SITE.'">http://randstatestats.org</a>) for the most up-to-date data and new databases.</li>
										<li style="padding-top:10px;">Finally, if you are interested in a free trial or in subscribing to our RAND Texas, or RAND New York sites, please let me know.</li>
									</ul>
									<br /><br />
									
									<div style="background:#EBEBEB;padding:10px;">
										<div style="font-size:14px;font-weight:bold;">
										Please login using your credentials at <a href="'.$URL_SITE.'">http://randstatestats.org</a> to set up administrative functions and to establish or reset your password.</div><br />
										<strong>Username:&nbsp;&nbsp;</strong>'.$receivermail.'<br /><strong>Password:&nbsp;&nbsp;</strong>testing
									</div>
									<br /><br />

									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;">
										<strong>Improved User Interface (UI)</strong><br />
										The UI on the new URL is vastly improved.  It is crisper, cleaner, and more functional.  As one example, each database is displayed in the same way, listing the title, a summary of the data, a detailed description, series start and end dates, geographic coverage, a hyperlinked original source, periodicity, and update details.  Additional UI improvements include:<br />
										<ul style="list-style-type:circle;padding-top:10px;"><li>Optional alerts that notify you via email when data have been updated</li><li>Auto-fill text boxes</li><li>Single-click downloads of Excel or CSV files</li><li>Line and column graphs that permit you to subtract or add categories and/or geographic areas.</li><li>Sharing data via email or social media.</li>
										</ul>											
								   </div>
							
									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;margin:0% 2%;">
										<strong>Double the Data</strong><br />We have added 72 new databases in the last two months and expect to add more than 100 in the next two months. That means that the fully built-out CA site will contain about 350 databases. In short, you will now have access to more than double the number of databases compared to the "classic" RAND CA site. RAND CA also now has 14 database sections, including Social Insurance & Human Services, Higher Education, and Environment, Resources & Weather. Examples of new databases include:<br />
										<ul>
										<li>Federal and State Minimum Wages (Labor Force)</li>
										<li>Legal Resident Status By Country Of Birth And MSA (Immigration)</li>
										<li>Use of Mammography (Health Care Utilization)</li>
										<li>Deficient and Obsolete Bridges in all States (Transportation and Traffic)</li>
										<li>Databases coming soon include Firearm Background Checks, Veterans Employed, State Labor Force Participation Rates, Average Retail Electricity Rates, and Minority and Women Owned Businesses.</li>
										</ul>
									</div>

									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;">
										<strong>Improved Administrative Functions</strong><br/>You will also be able to self-perform many functions, from changing IP ranges (if you are IP authenticated) to updating your contact information or passwords, checking usage (at any time), and paying with a credit card or Paypal. A subsequent email will detail those changes.<br/><br/>
									</div>
									<div style="clear: both;"><br/><br/></div>
									<div>
									We have spent much of the last two weeks working towards today&#39;s launch. If we&#39;ve missed something on a specific database, or if you have another suggestions, please let me know. We hope you enjoy the new site.<br/><br/>	
									</div>
									
									<div style="clear:both;padding-top:40px;padding-left:15px;">
										<strong>Best regards</strong>,<br/><br/>
										Joe Nation, Ph.D.<br/>
										Director<br/>
										'.$from_name.'<br/>
										<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
										<a href="mailto:'.$from_email.'">'.$from_email.'</a><br />
										Office: 415-785-4993<br />Mobile: 415-602-2973
									</div>
								</div>
							</div>	
						 </body>
						</html>';
	}
	
	//for RAND US subscribers
	if($mailbodykey == '4'){	

		$mailbody	= '<!DOCTYPE html>
						<html>
						 <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						  <title>randstatestats</title>
						 </head>
						 <body>
							 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;"><div style="width:100%;height:auto;overflow:hidden;text-align:left;padding-bottom:20px;display:block;"><img src="'.$URL_SITE.'/images/logo.png" /><div style="clear:both"></div></div>
							 
								<div style="width:100%;height:auto;overflow:hidden;padding-bottom:20px">
									You are receiving this email as a subscriber to RAND State Statistics, or RAND US. We appreciate your continued use of the site and wanted to pass on to you some recent improvements. Here are the highlights.<br /><br />
								
									<strong>New URL</strong><br /><br />
									<ul style="line-height:22px;">
										<li>Starting today, you will be able to access RAND US and its 150 databases at <a href="'.$URL_SITE.'">http://randstatestats.org</a>. If you are interested in expanding your access to include 162 detailed databases on CA, NY, or TX, please let me know.</li>
										<li style="padding-top:10px;">If we <b>authenticate your access via IP</b>, you do not need to take any action. You are now authenticated for RAND US. However, you should login with your User ID below in order to set up administrative functions, such as updating your profile, accessing usage statistics, or performing other administrative tasks.</li>
										<li style="padding-top:10px;">If you <b>access the site with a login and password</b>, please login with your new User ID (located below) and re-set your password.</li>
										<li style="padding-top: 10px;">You will still be able to access the "classic" RAND US site at <a href="http://statestats.rand.org">http://statestats.rand.org</a> for the next several months. However, we will not update the databases on this site. You should access the new URL (<a href="'.$URL_SITE.'">http://randstatestats.org</a>) for the most up-to-date data and new databases.</li>
										<li style="padding-top:10px;">Finally, if you are interested in a free trial or in subscribing to our RAND California, RAND Texas, or RAND New York sites, please let me know.</li>
									</ul>
									<br /><br />
									
									<div style="background:#EBEBEB;padding:10px;">
										<div style="font-size:14px;font-weight:bold;">
										Please login using your credentials at <a href="'.$URL_SITE.'">http://randstatestats.org</a> to set up administrative functions and to establish or reset your password.</div><br />
										<strong>Username:&nbsp;&nbsp;</strong>'.$receivermail.'<br /><strong>Password:&nbsp;&nbsp;</strong>testing
									</div>
									<br /><br />

									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;">
										<strong>Improved User Interface (UI)</strong><br />
										The UI on the new URL is vastly improved.  It is crisper, cleaner, and more functional.  As one example, each database is displayed in the same way, listing the title, a summary of the data, a detailed description, series start and end dates, geographic coverage, a hyperlinked original source, periodicity, and update details.  Additional UI improvements include:<br />
										<ul style="list-style-type:circle;padding-top:10px;"><li>Optional alerts that notify you via email when data have been updated</li><li>Auto-fill text boxes</li><li>Single-click downloads of Excel or CSV files</li><li>Line and column graphs that permit you to subtract or add categories and/or geographic areas.</li><li>Sharing data via email or social media.</li>
										</ul>											
								   </div>
							
									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;margin:0% 2%;">
										<strong>Triple the Data</strong><br />We have added 72 new US databases in the last two months and expect to add more than 100 in the next two months. That means that the fully built-out US site will contain about 260 databases covering all 50 states. In short, you will now have access to more than triple the number of databases compared to the "classic" RAND US site. RAND US also now has 14 database sections, including Social Insurance & Human Services, Higher Education, and Environment, Resources & Weather. Examples of new databases include:<br />
										<ul>
										<li>Federal and State Minimum Wages (Labor Force)</li>
										<li>Legal Resident Status By Country Of Birth And MSA (Immigration)</li>
										<li>Use of Mammography (Health Care Utilization)</li>
										<li>Deficient and Obsolete Bridges in all States (Transportation and Traffic)</li>
										<li>Databases coming soon include Firearm Background Checks, Veterans Employed, State Labor Force Participation Rates, Average Retail Electricity Rates, and Minority and Women Owned Businesses.</li>
										</ul>
									</div>

									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;">
										<strong>Improved Administrative Functions</strong><br/>You will also be able to self-perform many functions, from changing IP ranges (if you are IP authenticated) to updating your contact information or passwords, checking usage (at any time), and paying with a credit card or Paypal. A subsequent email will detail those changes.<br/><br/>
									</div>
									<div style="clear: both;"><br/><br/></div>
									<div>
									We have spent much of the last two weeks working towards today&#39;s launch. If we&#39;ve missed something on a specific database, or if you have another suggestions, please let me know. We hope you enjoy the new site.<br/><br/>	
									</div>
									
									<div style="clear:both;padding-top:40px;padding-left:15px;">
										<strong>Best regards</strong>,<br/><br/>
										Joe Nation, Ph.D.<br/>
										Director<br/>
										'.$from_name.'<br/>
										<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
										<a href="mailto:'.$from_email.'">'.$from_email.'</a><br />
										Office: 415-785-4993<br />Mobile: 415-602-2973
									</div>
								</div>
							</div>	
						 </body>
						</html>';
	}

	//for RAND NY subscribers
	if($mailbodykey == '5'){	

		$mailbody	= '<!DOCTYPE html>
						<html>
						 <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						  <title>randstatestats</title>
						 </head>
						 <body>
							 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;"><div style="width:100%;height:auto;overflow:hidden;text-align:left;padding-bottom:20px;display:block;"><img src="'.$URL_SITE.'/images/logo.png" /><div style="clear:both"></div></div>
							 
								<div style="width:100%;height:auto;overflow:hidden;padding-bottom:20px">
									You are receiving this email as a subscriber to RAND New York, or RAND NY. We appreciate your continued use of the site and wanted to pass on to you some recent improvements. Here are the highlights.<br /><br />
								
									<strong>New URL</strong><br /><br />
									<ul style="line-height:22px;">
										<li>Starting today, you will be able to access RAND NY and its 150 databases at <a href="'.$URL_SITE.'">http://randstatestats.org</a>. If you are interested in expanding your access to include 138 detailed databases on CA,TX, please let me know.</li>
										<li style="padding-top:10px;">If we <b>authenticate your access via IP</b>, you do not need to take any action. You are now authenticated for RAND NY. However, you should login with your User ID below in order to set up administrative functions, such as updating your profile, accessing usage statistics, or performing other administrative tasks.</li>
										<li style="padding-top:10px;">If you <b>access the site with a login and password</b>, please login with your new User ID (located below) and re-set your password.</li>
										<li style="padding-top: 10px;">You will still be able to access the "classic" RAND NY site at <a href="http://ny.rand.org">http://ny.rand.org</a> for the next several months. However, we will not update the databases on this site. You should access the new URL (<a href="'.$URL_SITE.'">http://randstatestats.org</a>) for the most up-to-date data and new databases.</li>
										<li style="padding-top:10px;">Finally, if you are interested in a free trial or in subscribing to our RAND California or RAND Texas sites, please let me know.</li>
									</ul>
									<br /><br />
									
									<div style="background:#EBEBEB;padding:10px;">
										<div style="font-size:14px;font-weight:bold;">
										Please login using your credentials at <a href="'.$URL_SITE.'">http://randstatestats.org</a> to set up administrative functions and to establish or reset your password.</div><br />
										<strong>Username:&nbsp;&nbsp;</strong>'.$receivermail.'<br /><strong>Password:&nbsp;&nbsp;</strong>testing
									</div>
									<br /><br />

									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;">
										<strong>Improved User Interface (UI)</strong><br />
										The UI on the new URL is vastly improved.  It is crisper, cleaner, and more functional.  As one example, each database is displayed in the same way, listing the title, a summary of the data, a detailed description, series start and end dates, geographic coverage, a hyperlinked original source, periodicity, and update details.  Additional UI improvements include:<br />
										<ul style="list-style-type:circle;padding-top:10px;"><li>Optional alerts that notify you via email when data have been updated</li><li>Auto-fill text boxes</li><li>Single-click downloads of Excel or CSV files</li><li>Line and column graphs that permit you to subtract or add categories and/or geographic areas.</li><li>Sharing data via email or social media.</li>
										</ul>											
								   </div>
							
									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;margin:0% 2%;">
										<strong>Double the Data</strong><br />We have added 72 new databases in the last two months and expect to add more than 100 in the next two months. That means that the fully built-out NY site will contain about 280 databases. In short, you will now have access to more than double the number of databases compared to the "classic" RAND NY site. RAND NY also now has 14 database sections, including Social Insurance & Human Services, Higher Education, and Environment, Resources & Weather. Examples of new databases include:<br />
										<ul>
										<li>Federal and State Minimum Wages (Labor Force)</li>
										<li>Legal Resident Status By Country Of Birth And MSA (Immigration)</li>
										<li>Use of Mammography (Health Care Utilization)</li>
										<li>Deficient and Obsolete Bridges in all States (Transportation and Traffic)</li>
										<li>Databases coming soon include Firearm Background Checks, Veterans Employed, State Labor Force Participation Rates, Average Retail Electricity Rates, and Minority and Women Owned Businesses.</li>
										</ul>
									</div>

									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;">
										<strong>Improved Administrative Functions</strong><br/>You will also be able to self-perform many functions, from changing IP ranges (if you are IP authenticated) to updating your contact information or passwords, checking usage (at any time), and paying with a credit card or Paypal. A subsequent email will detail those changes.<br/><br/>
									</div>
									<div style="clear: both;"><br/><br/></div>
									<div>
									We have spent much of the last two weeks working towards today&#39;s launch. If we&#39;ve missed something on a specific database, or if you have another suggestions, please let me know. We hope you enjoy the new site.<br/><br/>	
									</div>
									
									<div style="clear:both;padding-top:40px;padding-left:15px;">
										<strong>Best regards</strong>,<br/><br/>
										Joe Nation, Ph.D.<br/>
										Director<br/>
										'.$from_name.'<br/>
										<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
										<a href="mailto:'.$from_email.'">'.$from_email.'</a><br />
										Office: 415-785-4993<br />Mobile: 415-602-2973
									</div>
								</div>
							</div>	
						 </body>
						</html>';
	}

	//for RAND TX subscribers
	if($mailbodykey == '6'){	

		$mailbody	= '<!DOCTYPE html>
						<html>
						 <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						  <title>randstatestats</title>
						 </head>
						 <body>
							 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;"><div style="width:100%;height:auto;overflow:hidden;text-align:left;padding-bottom:20px;display:block;"><img src="'.$URL_SITE.'/images/logo.png" /><div style="clear:both"></div></div>
							 
								<div style="width:100%;height:auto;overflow:hidden;padding-bottom:20px">
									You are receiving this email as a subscriber to RAND Texas, or RAND TX. We appreciate your continued use of the site and wanted to pass on to you some recent improvements. Here are the highlights.<br /><br />
								
									<strong>New URL</strong><br /><br />
									<ul style="line-height:22px;">
										<li>Starting today, you will be able to access RAND TX and its 206 databases at <a href="'.$URL_SITE.'">http://randstatestats.org</a>. If you are interested in expanding your access to include 109 detailed databases on CA or NY, please let me know.</li>
										<li style="padding-top:10px;">If we <b>authenticate your access via IP</b>, you do not need to take any action. You are now authenticated for RAND TX. However, you should login with your User ID below in order to set up administrative functions, such as updating your profile, accessing usage statistics, or performing other administrative tasks.</li>
										<li style="padding-top:10px;">If you <b>access the site with a login and password</b>, please login with your new User ID (located below) and re-set your password.</li>
										<li style="padding-top: 10px;">You will still be able to access the "classic" RAND TX site at <a href="http://tx.rand.org">http://tx.rand.org</a> for the next several months. However, we will not update the databases on this site. You should access the new URL (<a href="'.$URL_SITE.'">http://randstatestats.org</a>) for the most up-to-date data and new databases.</li>
										<li style="padding-top:10px;">Finally, if you are interested in a free trial or in subscribing to our RAND California or RAND New York sites, please let me know.</li>
									</ul>
									<br /><br />
									
									<div style="background:#EBEBEB;padding:10px;">
										<div style="font-size:14px;font-weight:bold;">
										Please login using your credentials at <a href="'.$URL_SITE.'">http://randstatestats.org</a> to set up administrative functions and to establish or reset your password.</div><br />
										<strong>Username:&nbsp;&nbsp;</strong>'.$receivermail.'<br /><strong>Password:&nbsp;&nbsp;</strong>testing
									</div>
									<br /><br />

									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;">
										<strong>Improved User Interface (UI)</strong><br />
										The UI on the new URL is vastly improved.  It is crisper, cleaner, and more functional.  As one example, each database is displayed in the same way, listing the title, a summary of the data, a detailed description, series start and end dates, geographic coverage, a hyperlinked original source, periodicity, and update details.  Additional UI improvements include:<br />
										<ul style="list-style-type:circle;padding-top:10px;"><li>Optional alerts that notify you via email when data have been updated</li><li>Auto-fill text boxes</li><li>Single-click downloads of Excel or CSV files</li><li>Line and column graphs that permit you to subtract or add categories and/or geographic areas.</li><li>Sharing data via email or social media.</li>
										</ul>											
								   </div>
							
									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;margin:0% 2%;">
										<strong>Double the Data</strong><br />We have added 72 new databases in the last two months and expect to add more than 100 in the next two months. That means that the fully built-out TX site will contain about 310 databases. In short, you will now have access to more than double the number of databases compared to the "classic" RAND TX site. RAND TX also now has 14 database sections, including Social Insurance & Human Services, Higher Education, and Environment, Resources & Weather. Examples of new databases include:<br />
										<ul>
										<li>Federal and State Minimum Wages (Labor Force)</li>
										<li>Legal Resident Status By Country Of Birth And MSA (Immigration)</li>
										<li>Use of Mammography (Health Care Utilization)</li>
										<li>Deficient and Obsolete Bridges in all States (Transportation and Traffic)</li>
										<li>Databases coming soon include Firearm Background Checks, Veterans Employed, State Labor Force Participation Rates, Average Retail Electricity Rates, and Minority and Women Owned Businesses.</li>
										</ul>
									</div>

									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;">
										<strong>Improved Administrative Functions</strong><br/>You will also be able to self-perform many functions, from changing IP ranges (if you are IP authenticated) to updating your contact information or passwords, checking usage (at any time), and paying with a credit card or Paypal. A subsequent email will detail those changes.<br/><br/>
									</div>
									<div style="clear: both;"><br/><br/></div>
									<div>
									We have spent much of the last two weeks working towards today&#39;s launch. If we&#39;ve missed something on a specific database, or if you have another suggestions, please let me know. We hope you enjoy the new site.<br/><br/>	
									</div>
									
									<div style="clear:both;padding-top:40px;padding-left:15px;">
										<strong>Best regards</strong>,<br/><br/>
										Joe Nation, Ph.D.<br/>
										Director<br/>
										'.$from_name.'<br/>
										<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
										<a href="mailto:'.$from_email.'">'.$from_email.'</a><br />
										Office: 415-785-4993<br />Mobile: 415-602-2973
									</div>
								</div>
							</div>	
						 </body>
						</html>';
	}

	//forgot password mail
	if($mailbodykey == '7'){	
		$tempid = '7';
		$tempDetail = $emailObj->getTemp($tempid);
		if(!empty($tempDetail)){
			$emailBody	 = stripslashes($tempDetail['body']);
			$email_tags	 = $tempDetail['tags'];
		}

		$tagsArray = explode(';', $email_tags);
		foreach($tagsArray as $key => $tag){

			$constantname = substr(trim($tag), 1, -1);
			if(!defined($constantname)){
				$emailBody = str_replace($tag, '', $emailBody);
			} else {
				$emailBody = str_replace($tag, constant($constantname), $emailBody);
			}
		}

		$mailbody	=	'<!DOCTYPE html>
						<html>
						 <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						  <title>randstatestats</title>
						 </head>
						 <body>
							 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;">
							 
							 <div style="width:100%;height:auto;overflow:hidden;text-align:center;padding-bottom:20px;display:block;"><img src="'.$URL_SITE.'/images/logo01.png" />
							 <div style="clear:both"></div>
							 </div>';


							
		$mailbody	.=		$emailBody;

		$mailbody	.=		'<div style="clear:both;padding-top:40px;padding-left:15px;">
								<strong>Best regards</strong>,<br/><br/>
								Joe Nation<br/>
								Director<br/>
								RAND State Statistics<br/>
								<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
								<a href="mailto:'.$from_email.'">'.$from_email.'</a><br />
								Office: 415-785-4993<br />Mobile: 415-602-2973
							<div style="clear:both;"><br/><br/></div>
							</div>
							
						</div>
					</body>
				</html>';
	}

	//contact Us mail
	if($mailbodykey == '8'){
		
		$tempid = '8';
		$tempDetail = $emailObj->getTemp($tempid);
		if(!empty($tempDetail)){
			$emailBody	 = stripslashes($tempDetail['body']);
			$email_tags	 = $tempDetail['tags'];
		}

		$tagsArray = explode(';', $email_tags);
		foreach($tagsArray as $key => $tag){

			$constantname = substr(trim($tag), 1, -1);
			if(!defined($constantname)){
				$emailBody = str_replace($tag, '', $emailBody);
			} else {
				if(constant($constantname) !=''){
					$emailBody = str_replace($tag, constant($constantname), $emailBody);
				}else{
					$emailBody = str_replace($tag,'Not Available', $emailBody);
				}
			}
		}

		$mailbody	=	'<!DOCTYPE html>
						<html>
						 <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						  <title>randstatestats</title>
						 </head>
						 <body>
							 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;">
							 
							 <div style="width:100%;height:auto;overflow:hidden;text-align:center;padding-bottom:20px;display:block;"><img src="'.$URL_SITE.'/images/logo01.png" />
							 <div style="clear:both"></div>
							 </div>';
							
		$mailbody	.=		$emailBody;

		$mailbody	.=		'</div>
					</body>
				</html>';
	}

	//User Added By Admin mail
	if($mailbodykey == '9'){
		$tempid = '9';
		$tempDetail = $emailObj->getTemp($tempid);
		if(!empty($tempDetail)){
			$emailBody	 = stripslashes($tempDetail['body']);
			$email_tags	 = $tempDetail['tags'];
		}

		$tagsArray = explode(';', $email_tags);
		foreach($tagsArray as $key => $tag){

			$constantname = substr(trim($tag), 1, -1);
			if(!defined($constantname)){
				$emailBody = str_replace($tag, '', $emailBody);
			} else {
				$emailBody = str_replace($tag, constant($constantname), $emailBody);
			}
		}

		$mailbody	=	'<!DOCTYPE html>
						<html>
						 <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						  <title>randstatestats</title>
						 </head>
						 <body>
							 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;">
							 
							 <div style="width:100%;height:auto;overflow:hidden;text-align:center;padding-bottom:20px;display:block;"><img src="'.$URL_SITE.'/images/logo01.png" />
							 <div style="clear:both"></div>
							 </div>';

		$mailbody	.=		$emailBody;

		$mailbody	.=		'<strong>Many thanks</strong>,<br/>
								Joe Nation<br/>
								Director<br/>
								RAND State Statistics<br/>
								<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
								<a href="mailto:'.$from_email.'">'.$from_email.'</a><br />
								Office: 415-785-4993<br />Mobile: 415-602-2973
							<div style="clear:both;"><br/><br/></div>
							</div>
							
						</div>
					</body>
				</html>';
	}

	if($mailbodykey == 10){
		$tempid = '10';
		$tempDetail = $emailObj->getTemp($tempid);
		if(!empty($tempDetail)){
			$emailBody	 = stripslashes($tempDetail['body']);
			$email_tags	 = $tempDetail['tags'];
		}

		$tagsArray = explode(';', $email_tags);
		foreach($tagsArray as $key => $tag){

			$constantname = substr(trim($tag), 1, -1);
			if(!defined($constantname)){
				$emailBody = str_replace($tag, '', $emailBody);
			} else {
				$emailBody = str_replace($tag, constant($constantname), $emailBody);
			}
		}
		
		$mailbody	= '<!DOCTYPE html>
						<html>
						 <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						  <title>randstatestats</title>
						 </head>
						 <body>
							 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;"><div style="width:100%;height:auto;overflow:hidden;text-align:left;padding-bottom:20px;display:block;"><img src="'.$URL_SITE.'/images/logo.png" /><div style="clear:both"></div></div>
								<div style="width:100%;height:auto;overflow:hidden;padding-bottom:20px">
									You are receiving this email as a subscriber to RAND US. We appreciate your continued use of the site and wanted to pass on to you some recent changes to your account.<br /><br />
																	
									<div style="background:#EBEBEB;padding:10px;">
										<div style="font-size:14px;font-weight:bold;">
										Your login credentials has been changed at <a href="'.$URL_SITE.'">http://randstatestats.org</a> to set up administrative functions and to establish or reset your password.</div><br />';
										
										if (defined('USERNAME')) {
											$mailbody .= '<strong>Email/Username:&nbsp;&nbsp;</strong>'.$receivermail.'/'.USERNAME.'<br /><strong>';
										} else {
											$mailbody .= '<strong>Email:&nbsp;&nbsp;</strong>'.$receivermail.'<br /><strong>';
										}
										
										
										if (defined('PASSWORD')) {
											$mailbody .= 'Password:&nbsp;&nbsp;</strong>'.PASSWORD.'';
										}

									$mailbody.= '</div>
									<br /><br />

									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;">
										<strong>Improved User Interface (UI)</strong><br />
										The UI on the new URL is vastly improved.  It is crisper, cleaner, and more functional.  As one example, each database is displayed in the same way, listing the title, a summary of the data, a detailed description, series start and end dates, geographic coverage, a hyperlinked original source, periodicity, and update details.  Additional UI improvements include:<br />
										<ul style="list-style-type:circle;padding-top:10px;"><li>Optional alerts that notify you via email when data have been updated</li><li>Auto-fill text boxes</li><li>Single-click downloads of Excel or CSV files</li><li>Line and column graphs that permit you to subtract or add categories and/or geographic areas.</li><li>Sharing data via email or social media.</li>
										</ul>											
								   </div>
							
									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;margin:0% 2%;">
										<strong>Double the Data</strong><br />We have added 72 new databases in the last two months and expect to add more than 100 in the next two months. That means that the fully built-out CA site will contain about 350 databases. In short, you will now have access to more than double the number of databases compared to the "classic" RAND CA site. RAND CA also now has 14 database sections, including Social Insurance & Human Services, Higher Education, and Environment, Resources & Weather. Examples of new databases include:<br />
										<ul>
										<li>Federal and State Minimum Wages (Labor Force)</li>
										<li>Legal Resident Status By Country Of Birth And MSA (Immigration)</li>
										<li>Use of Mammography (Health Care Utilization)</li>
										<li>Deficient and Obsolete Bridges in all States (Transportation and Traffic)</li>
										<li>Databases coming soon include Firearm Background Checks, Veterans Employed, State Labor Force Participation Rates, Average Retail Electricity Rates, and Minority and Women Owned Businesses.</li>
										</ul>
									</div>

									<div style="width:29%;padding:1%;min-width:300px;float:left;margin-bottom:20px;border:1px dashed #cccccc;border-radius:5px;">
										<strong>Improved Administrative Functions</strong><br/>You will also be able to self-perform many functions, from changing IP ranges (if you are IP authenticated) to updating your contact information or passwords, checking usage (at any time), and paying with a credit card or Paypal. A subsequent email will detail those changes.<br/><br/>
									</div>
									<div style="clear: both;"><br/><br/></div>
									<div>
									We have spent much of the last two weeks working towards today&#39;s launch. If we&#39;ve missed something on a specific database, or if you have another suggestions, please let me know. We hope you enjoy the new site.<br/><br/>	
									</div>
									
									<div style="clear:both;padding-top:40px;padding-left:15px;">
										<strong>Best regards</strong>,<br/><br/>
										Joe Nation, Ph.D.<br/>
										Director<br/>
										'.$from_name.'<br/>
										<a href="'.$URL_SITE.'">'.$URL_SITE.'</a><br/>
										<a href="mailto:'.$from_email.'">'.$from_email.'</a><br />
										Office: 415-785-4993<br />Mobile: 415-602-2973
									</div>
								</div>
							</div>	
						 </body>
						</html>';
	}

	//IP added/Changed Mail
	if($mailbodykey == 11){
		$tempid = '11';
		$tempDetail = $emailObj->getTemp($tempid);
		if(!empty($tempDetail)){
			$emailBody	 = stripslashes($tempDetail['body']);
			$email_tags	 = $tempDetail['tags'];
		}

		$tagsArray = explode(';', $email_tags);
		foreach($tagsArray as $key => $tag){

			$constantname = substr(trim($tag), 1, -1);
			if(!defined($constantname)){
				$emailBody = str_replace($tag, '', $emailBody);
			} else {
				$emailBody = str_replace($tag, constant($constantname), $emailBody);
			}
		}
		
		$mailbody	=	'<!DOCTYPE html>
						<html>
						 <head>
						  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						  <title>randstatestats</title>
						 </head>
						 <body>
							 <div style="width:auto;height:auto;overflow:hidden;margin:auto;padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:22px;">
							 
							 <div style="width:100%;height:auto;overflow:hidden;text-align:left;padding-bottom:20px;display:block;"><img src="'.$URL_SITE.'/images/logo.png" />
							 <div style="clear:both"></div>
							 </div>';

		$mailbody	.=		$emailBody;

		$mailbody	.=		'<strong>Best regards</strong>,<br/><br/>
										Rand Team<br/>
						</div>
					</body>
				</html>';
	}

	return $mailbody;
}
?>