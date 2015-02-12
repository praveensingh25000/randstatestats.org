<?php
/******************************************
* @Modified on Dec 17, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
* This file will include all the global functions that will be used through out the project.
********************************************/
function throw_ex($er){  
  throw new Exception($er);  
} 

function encrypt($text) { 
    return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)))); 
} 

function decrypt($text){ 
    return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))); 
}


function checkSession($is_admin = false){
	if($is_admin){
		if(!isset($_SESSION['admin'])){
			$_SESSION['msgalert'] = 'Your Session has Expired.Please Login again to visit the site.';
			header('location: index.php');
			exit;
		}
	} else {
		if(!isset($_SESSION['user'])){
			$_SESSION['msgalert'] = '7';
			header('location: index.php');
			exit;
		}
	}
}

function checksession_with_message($sessionType,$redirectpage,$messagetype,$msgnumber){
	if($sessionType){
		if(!isset($_SESSION['admin'])){
			$_SESSION[$messagetype] = $msgnumber;
			header('location: '.$redirectpage.'');
			exit;
		}
	} else {
		if(!isset($_SESSION['user'])){
			$_SESSION[$messagetype] = $msgnumber;
			header('location: '.$redirectpage.'');
			exit;
		}
	}
}

function getTableNames($refresh = false){
	global $dbDatabase;
	$admin = new admin();
	
	if(!isset($_SESSION['tables']) || $refresh){
		$_SESSION['tables'] = array();
		$tablesResult = $admin->showAllTables();
		$tables = $dbDatabase->getAll($tablesResult);
		
		foreach($tables as $key => $tableDetail){
			$col = "Tables_in_".$dbDatabase->DBDATABASE."";
			$_SESSION['tables'][] = $tableDetail[$col];
		}
	}

	
	return $_SESSION['tables'];
}
function fetchGenralSettings($group = "")
{
	global $db;
	if($group!=""){
		$sql="select * from generalsettings where groupid = '".$group."'";
	} else {
		$sql="select * from generalsettings order by groupid";
	}
	$source=$db->run_query($sql);

	$rowsAll = $db->getAll($source);

	$generalSettings = array();
	if(count($rowsAll)>0){
		foreach($rowsAll as $key => $row){
			$group = $row['groupid'];
			$generalSettings[$group][] = $row;
		}
	}

	return $generalSettings;
}
//To get extension of file
function getExtension($str)
{
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
}

function mail_function($receivename,$receivermail,$fromname,$fromemail,$mailbody,$subject, $attachments = array(),$addbcc=array(), $addCC=array()) {

		$mail=new PHPmailer;//object of mailer function
		$mail->isHTML(true);
		$name= $receivename;
		$emails_to= $receivermail;				
		$mail->FromName = $fromname;
		$mail->From= $fromemail;
		$mail->AddAddress($emails_to,$receivename);
		$mail->Subject  = $subject;
		$mail->Body     = $mailbody;
		$mail->WordWrap = 50;

		if(count($addbcc)>0){
			foreach($addbcc as $email => $name){
				$mail->AddBCC($email, $name);
			}
		}

		if(count($addCC)>0){
			foreach($addCC as $email => $name){
				$mail->AddCC($email, $name);
			}
		}
		
		if(!$mail->Send()){

			echo "<p class='fonterror'>Message was not sent.</p>";			
			echo 'Mailer error: ' . $mail->ErrorInfo;
			return true;
		} 
		else{
			return false;
		}				
}

function mail_function1($receivename,$receivermail,$fromname,$fromemail,$mailbody,$subject, $attachments = array(),$addcc=array()) {
	
		$mail=new PHPmailer;//object of mailer function
		$mail->isHTML(true);
		$name= $receivename;
		$emails_to= $receivermail;				
		$mail->FromName = $fromname;
		$mail->From= $fromemail;
		$mail->AddCC('shalini@ideafoundation.co.in','');		
		$mail->AddAddress($emails_to,$receivename);
		$mail->Subject  = $subject;
		$mail->Body     = $mailbody;
		$mail->WordWrap = 50;
		
		if(!$mail->Send()){

			echo "<p class='fonterror'>Message was not sent.</p>";			
			echo 'Mailer error: ' . $mail->ErrorInfo;
			return true;
		} 
		else{
			
			return false;
		}				
}

function getnumberofDays($startTime,$endTime) {

	$numDays	 = 0;
	$day		 = 86400;
	$startTime   = date('Y-m-d', strtotime($startTime));
	$endTime     = date('Y-m-d', strtotime($endTime));	
    $start_time  = strtotime($startTime);
    $end_time    = strtotime($endTime);
	$num_Days    = floor(($end_time - $start_time) / $day);
	if($num_Days >= 0){ $numDays = $num_Days; }
	return $numDays;
}

function getEndDatefromdays($currentDate,$numberofdays, $action = 'add') {

	if($action == 'minus')
	return $enddate = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s", strtotime($currentDate)) . " -".$numberofdays."days"));
	else
	return $enddate = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s", strtotime($currentDate)) . " +".$numberofdays."days"));
}

function array_sort($array, $on, $order=SORT_ASC) {
	
	$new_array = array();
	$sortable_array = array();

	if (count($array) > 0) 
	{
		foreach ($array as $k => $v)
		{
			if (is_array($v)) 
			{
				foreach ($v as $k2 => $v2) 
				{
					if ($k2 == $on) 
					{
						$sortable_array[$k] = $v2;
					}
				}
			} else {
				$sortable_array[$k] = $v;
			}
		}

		switch ($order) 
		{
			case SORT_ASC:
				asort($sortable_array);
				break;
			case SORT_DESC:
				arsort($sortable_array);
				break;
		}

		foreach ($sortable_array as $k => $v) 
		{
			$new_array[$k] = $array[$k];
		}
	}
	unset($array,$sortable_array);
	return $new_array;
}

function getMac(){
	$mac ='';
	exec("ipconfig /all", $output);
	foreach($output as $line){
		if (preg_match("/(.*)Physical Address(.*)/", $line)){
			$mac = $line;
			$mac = str_replace("Physical Address. . . . . . . . . :","",$mac);
		}
	}
	return $mac;
}

function getIPAddressofSystem(){
	$exec = exec("hostname"); //the "hostname" is a valid command in both windows and linux
	$hostname = trim($exec); //remove any spaces before and after
	return $ip = gethostbyname($hostname); //resolves the hostname using local hosts resolver or DNS
}

function getRealIpAddr() {
	$ip='';

    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function randomPassword($length = 8) {
    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
?>