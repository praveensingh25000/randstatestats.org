<?php

function getMailTemplate($mailbodykey,$receivename,$receivermail,$from_name,$from_email,$userDefinedvalue=array()){

	global $URL_SITE,$DOC_ROOT;

	$mailbody ='';

	if(!empty($userDefinedvalue)){
		foreach($userDefinedvalue as $key => $userValue){
			define('VALUE.'.$key.'',$userValue);
		}
	}
//user Registration mail
	if($mailbodykey == '2'){	

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
							 </div>
							 
							 Hi '.$receivename.', <br />

							<p>You have successfully created a Rand Account! Please click the link below to verify your email address. </p>

							<p><a href="'.URL_SITE.'/index.php?verification='.VALUE1.'">'.URL_SITE.'/index.php?verification='.$endode_email.'</a> </p>

							<p>If you are having trouble clicking on the link, please copy and paste it into your browser. </p>

							<div style="padding:10px 0px 10px 10px;">
								<strong>Best regards</strong>,<br/><br/>
								'.$from_email.'<br/>
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

	//forgot password mail
	if($mailbodykey == '5'){	

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
							 </div>
							 <p>Dear User</p>
							<p>We have re-set the password for your account on RAND State Statistics</p>
							<p>Your temporary password is: '.$password.'</p>
							<p>Please <a href="'.$URL_SITE.'/index.php">login</a> at RAND State Statistics, then change your password at <a href="'.$URL_SITE.'/profile.php?action=edit">Edit Profile</a>.  We highly recommend you create a unique password.</p>
							<p>If you need additional assistance, email <a href="mailto:'.INFO_EMAIL.'">'.INFO_EMAIL.'.</p>
							<div style="padding:10px 0px 10px 10px;">
								<strong>Best regards</strong>,<br/><br/>
								'.$from_email.'<br/>
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
	if($mailbodykey == '6'){	

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
							 </div>
							 <p>Hello Admin,<br>'.ucfirst($name).' has sent you a message for enquiry. '.ucfirst($name).'\'s contact details are as follows-<br><br>";
		<table width='50%'>";	
		<tr><td>Name: "."  ".ucfirst($name)."</td></tr>";
		<tr><td>Email: "."  ".$email."</td></tr>";
		if($phone !=''){
		<tr><td>Phone: "."  ".$phone."</td></tr>";
		}
		if($address !=''){
		<tr><td>Address: "."  ".$address."</td></tr>";
		}
		<tr><td>Message: "."  ".$message."</td></tr>";
		$mailbody.=	"</table>";
								<strong>Best regards</strong>,<br/><br/>
								'.$from_email.'<br/>
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
?>