<?php
include_once('include/connect.php');
require_once($DOC_ROOT.'classes/database.class.php');
include_once($DOC_ROOT.'classes/user.class.php');
include_once($DOC_ROOT.'classes/mailer.class.php');
require 'facebook.php';

//appid and secret key are includeed in the connection file

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId' => $app_id,
  'secret' => $secret_id,
));
if(isset($_GET['state']))
{
   // Get User ID
   $user = $facebook->getUser();  
}
else
{
	$user=0;
}
// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if($user)
{
	  try 
	  {
		// Proceed knowing you have a logged in user who's authenticated.
		$user_profile = $facebook->api('/me');		
	  } 
	  catch (FacebookApiException $e) 
	  {
		error_log($e);
		$user = null;
	  }
}
//print_r($user_profile);
// Login or logout url will be needed depending on current user state.
if ($user)
{
    $logoutUrl = $facebook->getLogoutUrl();
	$loginUrl=$logoutUrl;
} 
else 
{
     $loginUrl = $facebook->getLoginUrl(array(
   'scope' => 'email,user_status,publish_stream,user_photos'
	));
}



// This call will always work since we are fetching public data.

?>
 
	<!---wrapper--->
	<?php if ($user): ?>
	
	<?php endif ?>
	<?php if ($user):
		//facebook information used to login or registration
		//echo'<pre>';
		//print_r($user_profile);

		$_SESSION['facebook_user']=$user_profile;
		//echo '<pre>';
		//print_r($user_profile);die;

		if($_SESSION['facebook_user'])
		{			
			$u=$_SESSION['facebook_user'];
			//echo '<pre>';
			//print_r($u);
			if($u['gender']=='male')
			{
				$gender='M';
			}
			else
			{
				$gender='F';
			}
			//echo $gender;
			 $name=$u['first_name'].' '.substr($u['last_name'],0,1);

			//echo "<pre>";print_r($_SESSION['facebook_user']);die;
			$user_registration=user::registration_facebook($u['id'],$name,$u['email']);			
			//print_r($user_registration);die;
			if($user_registration)
			{
	
			$_SESSION['user']=$user_registration[1];	
			/* To redirect into buy page if trial period is over*/
			$join_date= $_SESSION['user']['join_date'];
			$currentDate = date("Y-m-d");
			$daylen = 60*60*24;
			$date1 = $join_date;
			$date2 = $currentDate;
			 $days= (strtotime($date2)-strtotime($date1))/$daylen;
			if($days>=2){
				$_SESSION['msgsuccess'] = '0';
				header('location: buyPlan.php');
				exit;
			}
			/* To redirect into buy page if trial period is over*/
			//echo "<pre>";print_r($user_registration); die;
			
	// mail for user to send password for account
//print_r($user_registration);die;
	if(!$user_registration[0])		//mail is send if new user is their
	{
		
	
	  $mail=new PHPmailer;
		$mail->isHtml(true);
		$mail->From='Rand Site';
		$email1='ideamamta@yahoo.com';
		$mail->AddAddress($_SESSION['user']['email']);
		$mail->Subject="SignUp Verification Email";
		$mail->Body.='Hi '.$name.', <br /><p>You have successfully created a Rand Account! Please click the link to login. </p><p><a href="'.$URL_SITE.'rand/login.php">http://192.168.0.18/rand/login.php</a> </p>
		<p>If you are having trouble clicking on the link, please copy and paste it into your browser. </p>
		<p>Thank you </p>
		<p>Rand Team </p>';
		$mail->WordWrap=50;
		if(!$mail->Send()){
			
			echo "Message was not sent";
			echo "Mailer error".':'.$mail->ErrorInfo;
		}
		
	    $_SESSION['msgsuccess'] ="1";
	   header('location:'.URL_SITE.'/index.php');

			   
		}
				if(isset($_GET['next']))
				{
					header('location:'.$_GET['next']);
					exit;
				}
				header('location:'.URL_SITE.'/index.php');
			}
			else
			{
				
				session_destroy();
				unset($u);				
				header('location:'.URL_SITE.'/userRegistration.php');
			}
			
		}
		?>s
		
		
	<?php else: ?>
	
	<?php endif;	
		
		
	

	?>
 <!---//wrapper--->
 
