<?php
/******************************************
* @Modified on Dec 18, 2012
* @Package: Rand
* @Developer: Mamta Sharma
* @URL : http://www.ideafoundation.in
********************************************/

class user {
	
		# Function to  register user
		function userRegistration($name,$email,$pw,$phone,$address,$organisation,$org_address,$user_type,$active_status=0, $last_name = '') {

			global $db;
		
			$sql= "insert into users set name= '".$name."', last_name = '".$last_name."', email = '".$email."', password = '".md5($pw)."',phone = '".$phone."',address = '".$address."',organisation = '".$organisation."',organisation_address = '".$org_address."',user_type = '".$user_type."',join_date=now() ";
			
			if($active_status==0) {
				$sql .= ",expire_time='".date("Y-m-d H:i:s")."', active_status='0' ";
			} elseif($active_status==1) {

				$defaultValidity	=   VALIDITY;
				$currentDate		=   date("Y-m-d H:i:s");
				$expire_time		=   getEndDatefromdays($currentDate,$defaultValidity);

				$sql .= ",expire_time='".$expire_time."',active_status='1' ";
			}

			$insertResult = $db->insert($sql, $db->conn);
			return $insertResult;
		}

		function updateNumberofUsers($userid, $number_of_users){
			global $db;		
			$sql	= "update users set number_of_users='".trim($number_of_users)."' where id = '".$userid."'";
			return $updated= $db->run_query($sql);
		}

		function updateUserTypeandNoofUsers($userid, $number_of_users, $usertype){
			global $db;		
			$sql	= "update users set number_of_users='".trim($number_of_users)."', user_type = '".$usertype."' where id = '".$userid."'";
			return $updated= $db->update($sql, $db->conn);
		}

		# Function to  login user
		function login($email, $password){
			global $db;
			$sql		= "select * from users where (email = '".$email."' or username = '".$email."') and password = '".$password."' ";
			$userDetail	= $db->getRow($sql);
			return $userDetail;
		}
		# Function to  check esisting email
		function checkEmailExistence($email='',$status='')
		{
			global $db;
			if($status == '1'){
				$sql="select * from users where is_deleted = '0' and email = '".$email."'";
			} else {
				$sql="select * from users where is_deleted = '0' and username = '".$email."'";
			}			
			$result	= $db->run_query($sql);
			$count	= $db->count_rows($result);
			return $count;
		}
		# Function to  get all users
		function showAllUsers(){
			global $db;
			$sql			= "select * from users order by id";
			$usersResult	= $db->run_query($sql);
			return $usersResult;
		}

		function showAllUsersRamdomly($tablename,$all=0){
			global $db;

			if(isset($all) && $all == '1') { $random = 'ORDER BY name';
			} else { $random = 'ORDER BY RAND()'; }

			$sql			= "SELECT * FROM ".$tablename." ".$random." ";
			$usersResult	= $db->run_query($sql);
			return $usersResult;
		}
		
		//added by Praveen Singh on 4/16/2013
		function showAllActiveDeactiveUsers($active=0,$is_deleted=0){
			global $db;
			
			$sql = "select * from users where ";
			if($is_deleted == '1'){
				$sql.= "is_deleted ='".$is_deleted."' order by id ASC";
			} else {
				$sql.= "block_status='".$active."' and is_deleted ='".$is_deleted."' order by name ASC";
			}

			$usersResult = $db->run_query($sql);
			return $usersResult;
		}

		# Function to  get detail of user according to id
		function getUser($id){
			global $db;
			$sql			= "select * from users where id = '".$id."' ";
			$userDetail		= $db->getRow($sql);
			return $userDetail;
		}
		# Function to delete user
		
      function deleteUser($id){
		global $db;
		$sql= "delete from users where id='".$id."'";
		$ex_delete	= $db->run_query($sql);
		return $ex_delete;
	    }
		
		function unblockUser($id)
		{
			global $db;
			echo $sql="update users set block_status='0' where id='".$id."'";
		  $userResult	= $db->run_query($sql);
		  return $userResult;

				
		}
		function blockUser($id)
		{
		  global $db;
		  $sql="update users set block_status='1' where id='".$id."'";
		  $userResult	= $db->run_query($sql);
		  return $userResult;

				
		}
		# Function to  verify registered user
		function check_verification_of_account($email) {
			
			global $db;

			$userDetail = array();
			
			$sql		= "select * from users where email = '".$email."' and active_status = '0' ";
			$userDetail	= $db->getRow($sql);
			
			if(!empty($userDetail)){

				$defaultValidity	=   VALIDITY;
				$currentDate		=   date("Y-m-d H:i:s");
				$expire_time		=   getEndDatefromdays($currentDate,$defaultValidity);
				
				$sql="UPDATE users SET active_status='1', join_date=now(), expire_time = '".$expire_time."' WHERE email = '".$email."' ";
				$return   = $db->update($sql,$db->conn);
				return $return;

			} else {
				return false;	
			}		
		}

		function updateGeneralSettings($name, $value, $groupid){
			
			global $db;
			$sql = "update generalsettings set value = '".mysql_real_escape_string($value)."' where name = '".$name."' and groupid = '".$groupid."'";
			$result	= $db->run_query($sql,$db->conn);
			return mysql_affected_rows($result);
		}

		function insertUserPlanTransaction($user_id,$plan_id,$amount,$unique_id){    
			global $db;
			$sql="insert into user_account set user_id=".$user_id.",plan_id=".$plan_id.",amount=".$amount.",unique_id='".$unique_id."',pay_status=0,buy_on=NOW()";
			$result	= $db->run_query($sql);
			return mysql_insert_id();
		}

		function updateUserPlanTransaction($paypal_return_transaction_id,$status,$paypal_before_transaction_id,$paymentinfo_transactiontype){ 
			global $db;
			$sql="update user_account set paypal_trans_id='".$paypal_return_transaction_id."',pay_status='".$status."',paymentinfo_transactiontype='".$paymentinfo_transactiontype."',buy_on=NOW() where unique_id='".$paypal_before_transaction_id."'";
			$result	= $db->run_query($sql);
			return mysql_affected_rows();
		}

		function select_trans_plan_items($paypal_trans_id)
		{
			global $db;
			$sql="select * from user_account where paypal_trans_id='".$paypal_trans_id."'";	
			return $result	= $db->run_query($sql);
		}

		//uncompleted transaction record deletion 
		function unpaymentPlanDetail()
		{
			global $db;
			$sql="delete from user_account where paypal_trans_id=''";	
			return $result	= $db->run_query($sql);
		}

		function selectPlan($plan_id)
		{
			global $db;
			$sql="select * from subscription_plans where id='". $plan_id."' ";
			return $result	= $db->run_query($sql);
		}

		function checkUserValidity($useremail){
			global $db;
			$sql="select * from users where email='".$useremail."' or username='".$useremail."'";
			$data = $db->getRow($sql);
			
			if(!empty($data)){
				$startTime= $data['join_date'];		
				$endTime = date("Y-m-d H:i:s");
				$days = getnumberofDays($startTime,$endTime); 
			}else{
				$days=0;
			}
			return $days;			
		}
		
		// function to register through facebook.
		function registration_facebook($user_id,$name,$email){
			//$return=array();
			$sql="select * from users where  email='".$email."'";//checks whether email already present or not

			$result=mysql_query($sql)or die(mysql_error());
			$already=mysql_num_rows($result);
			$result=mysql_fetch_assoc($result);
			if($already>0)
			{
				$user=user::getUser($result['id']);				
				return $return=array(1,$user);	// first values denintes its not a fresh user so mail was not send
			}
			else
			{
				$sql="insert into users  set name='".$name."',email='".$email."',active_status='1',join_date=now()";
				$result=mysql_query($sql);
				$latest_id=mysql_insert_id();

				if($latest_id>0)
				{
					$sql="select * from users where  id='".$latest_id."'";
					$result=mysql_query($sql)or die(mysql_error());
					$user =mysql_fetch_array($result);
					return $return=array(0,$user);// first values indicates its  a fresh user 
				}
			}
		}	

		// function to register Social Network User
		function registerSocialNetUser($user_email, $password, $first_name, $user_image, $address, $user_type,$provider,$login_type){

			global $db;

			$defaultValidity	=   VALIDITY;
			$currentDate		=   date("Y-m-d H:i:s");
			$expire_time		=   getEndDatefromdays($currentDate,$defaultValidity);

			if($provider=='twitter')
				$useremailcolums='username';
			else 
				$useremailcolums='email';  
			
			//checks whether email already present or not
			$sql="select * from users where email='".$user_email."' or username='".$user_email."'";
			$result = $db->getRow($sql);
						
			if(!empty($result)) {							
				$returnData = array(1,$result['id']);			
			} else {
				$sql="insert into users  set name='".$first_name."',".$useremailcolums."='".$user_email."', password='".$password."',user_type='".$user_type."',login_type='".$login_type."',active_status='1',join_date=now(), expire_time= '".$expire_time."' ";
				$latest_id		= $db->insert($sql, $db->conn);				
				$returnData     = array(0,$latest_id);// first values indicates its  a fresh user
			}
			return $returnData;
		}

		function UpdateUserPassword($user_id,$new_password){
			global $db;
			$sql="update users set password='".md5($new_password)."' where id='".$user_id."' ";
			return $result	= $db->update($sql,$db->conn);
		}

		function selectUserProfile($email)
		{
			global $db;
			//$sql = "SELECT * from users WHERE email='".$email."' ";
			$sql="select * from users where email='".$email."' or username='".$email."'";
			return $db->getRow($sql);
		}
		/*function to update user data
		created by sandeep kumar*/
		function updateUserProfile($userProfileArr) {
			global $db,$DOC_ROOT;

			if(isset($userProfileArr['use_name_as_brand'])){
				$use_name_as_brand = 'Y';
			} else {
				$use_name_as_brand = 'N';
			}


			$sql="update users set name='".mysql_real_escape_string($userProfileArr['name'])."', username='".mysql_real_escape_string($userProfileArr['username'])."', phone='".mysql_real_escape_string($userProfileArr['phone'])."', address='".mysql_real_escape_string($userProfileArr['address'])."', user_type='".mysql_real_escape_string($userProfileArr['user_type'])."', number_of_users='".mysql_real_escape_string($userProfileArr['number_of_users'])."', organisation='".mysql_real_escape_string($userProfileArr['organistaion'])."', organisation_address='".mysql_real_escape_string($userProfileArr['organistaion_status'])."',
			last_name = '".mysql_real_escape_string($userProfileArr['last_name'])."',
			use_brand_name = '".$use_name_as_brand."'
			where email='".$_SESSION['user']['email']."' ";
			return $result	= $db->update($sql,$db->conn);
		}

		/*function to update image of user
		created by sandeep*/
		function updateImage($userid) {

			global $db,$DOC_ROOT;
			
			$sql="select * from users where id = '".$userid."' ";
			$data = $db->getRow($sql);

			if(!empty($data['image'])) {
			$userimage= $data['image'];
			unlink($DOC_ROOT.'uploads/profiles/'.$userid.'/'.$userimage);
			} 

			//creating user directory
			if(!is_dir($DOC_ROOT.'uploads/profiles/'.$userid))
			{
				//to create profile image folder
				mkdir($DOC_ROOT.'uploads/profiles/'.$userid) ;
				chmod($DOC_ROOT.'uploads/profiles/'.$userid,0777);
			}

			if($_FILES['userimage']['size'] != 0) 
			{
				$image_name = $_FILES['userimage']['name'];
				$temp_image=$_FILES['userimage']['tmp_name'];
				
				//to get file extension and unique file name
				$ext = getExtension($image_name);
				$unique_images_name = uniqid().'.'.$ext;		
			
				$upload_dir_folder=$DOC_ROOT.'uploads/profiles/'.$userid.'/'.$unique_images_name;
				$result =move_uploaded_file($temp_image,$upload_dir_folder);
			}	

			$sql="update users set image='".mysql_real_escape_string($unique_images_name)."' where id ='".$userid."' ";
			return $result	= $db->update($sql,$db->conn);
		}

		function userForgotPassword($email,$newpassword){
			global $db;
			$sql = "update users set password = '".$newpassword."' where email = '".$email."'";
			return $result	= $db->update($sql,$db->conn);
		}
		
		function email_existence($email){
			global $db;
			$sql="select * from users where email='".$email."'";
			return $db->getRow($sql); 
		}

		function insertNotification($user_id, $dbid, $notifyemail, $databasename){    
			global $db;
			$sql="insert into user_notify set databasename = '".$databasename."',user_id='".$user_id."',notifyemail='".$notifyemail."',dbid='".$dbid."',added_on=NOW()";
			$result	= $db->run_query($sql);
			return mysql_insert_id();
		}

		function checkemailNotify($notifyemail,$dbid ,$databasename){
			global $db;
			$sql="select * from user_notify where databasename = '".$databasename."' and notifyemail='".$notifyemail."' and dbid='".$dbid."' ";
			return $db->getRow($sql); 
		}



		function addUserTypeInfo($user_type, $baseprice, $basepriceus, $basepriceindividual, $basepriceusindividual, $minimumprice, $pricepercentage, $surchargeonestate, $surchargetwostate, $surchargethreestate, $surchargeonestateus, $surchargetwostateus, $surchargethreestateus, $surchargeonestateindividual, $surchargetwostateindividual, $surchargethreestateindividual, $surchargeonestateusindividual, $surchargetwostateusindividual, $surchargethreestateusindividual){
			
			global $db;

			$sql = "delete from `user_type_prices` where user_type= '".$user_type."'";
			$delid = $db->delete($sql);

			$sql = "INSERT INTO `user_type_prices` (`id`, `user_type`, `baseprice`, `basepriceus`, `basepriceindividual`, `basepriceusindividual`, `minimumprice`, `pricepercentage`, `surchargeonestate`, `surchargetwostate`, `surchargethreestate`, `surchargeonestateus`, `surchargetwostateus`, `surchargethreestateus`, `surchargeonestateindividual`, `surchargetwostateindividual`, `surchargethreestateindividual`, `surchargeonestateusindividual`, `surchargetwostateusindividual`, `surchargethreestateusindividual`) VALUES (NULL, '".$user_type."', '".$baseprice."', '".$basepriceus."', '".$basepriceindividual."', '".$basepriceusindividual."', '".$minimumprice."', '".$pricepercentage."', '".$surchargeonestate."', '".$surchargetwostate."', '".$surchargethreestate."', '".$surchargeonestateus."', '".$surchargetwostateus."', '".$surchargethreestateus."',  '".$surchargeonestateindividual."', '".$surchargetwostateindividual."', '".$surchargethreestateindividual."', '".$surchargeonestateusindividual."', '".$surchargetwostateusindividual."', '".$surchargethreestateusindividual."')";
			
			$uid = $db->insert($sql);
			return $uid;
		}

		function getUserTypeInfo($user_type){
			global $db;
			$sql="select * from user_type_prices where `user_type` = '".$user_type."'";
			return $db->getRow($sql);
		}

		//added by Praveen Singh on 09-07-2013
		function updateUserProfileByID($userProfileArray) {
			global $db,$DOC_ROOT;

			$sqlCondition='';
			if(!empty($userProfileArray)) {

				$userDefinedvalue = array();

				if(isset($userProfileArray['name']) && $userProfileArray['name']!=''){
					$sqlCondition.="name='".mysql_real_escape_string($userProfileArray['name'])."' ,";
				}
				if(isset($userProfileArray['last_name']) && $userProfileArray['last_name']!=''){
					$sqlCondition.="last_name='".mysql_real_escape_string($userProfileArray['last_name'])."' ,";
				}
				if(isset($userProfileArray['username']) && $userProfileArray['username']!=''){
					$userDefinedvalue[]['username'] = $userProfileArray['username'];
					$sqlCondition.="username='".mysql_real_escape_string($userProfileArray['username'])."' ,";
				}

				if(isset($userProfileArray['password']) && $userProfileArray['password']!=''){
					$userDefinedvalue[]['password'] = $userProfileArray['password'];
					$sqlCondition.="password='".md5(mysql_real_escape_string($userProfileArray['password']))."' ,";
				}


				if(isset($userProfileArray['email']) && $userProfileArray['email']!=''){
					
					$sqlCondition.="email='".mysql_real_escape_string($userProfileArray['email'])."' ,";
				}
				if(isset($userProfileArray['phone']) && $userProfileArray['phone']!=''){
					$sqlCondition.="phone='".mysql_real_escape_string($userProfileArray['phone'])."' ,";
				}
				if(isset($userProfileArray['user_type']) && $userProfileArray['user_type']!=''){
					$sqlCondition.="user_type='".mysql_real_escape_string($userProfileArray['user_type'])."' ,";
				}
				if(isset($userProfileArray['number_of_users']) && $userProfileArray['number_of_users']!=''){
					$sqlCondition.="number_of_users='".mysql_real_escape_string($userProfileArray['number_of_users'])."' ,";
				}
				if(isset($userProfileArray['address'])){
					$sqlCondition.="address='".mysql_real_escape_string($userProfileArray['address'])."' ,";
				}
				/*
				if(isset($userProfileArray['organisation']) && $userProfileArray['organisation']!=''){
					$sqlCondition.="organisation='".mysql_real_escape_string($userProfileArray['organisation'])."' ,";
				}
				*/
				if(isset($userProfileArray['organisation'])){
					$sqlCondition.="organisation='".mysql_real_escape_string($userProfileArray['organisation'])."' ,";
				}
				if(isset($userProfileArray['organisation_address']) && $userProfileArray['organisation_address']!=''){
					$sqlCondition.="organisation_address='".mysql_real_escape_string($userProfileArray['organisation_address'])."' ,";
				}
				
				$receivename	= $_POST['name']." ".$_POST['last_name'];
				$receivermail	= $_POST['email'];
				$from_name	= CONTACT_NAME;
				$from_email	= CONTACT_EMAIL;


				$sql="update users set ".substr($sqlCondition ,0, -1)." where id='".$userProfileArray['userid']."' ";
				$result	= $db->update($sql,$db->conn);

				if($result){
					
					if(isset($_POST['oldemail']) && trim($_POST['oldemail']) != trim($_POST['email']) && !isset($body)){
						$body = getMailTemplate(10, $receivename, $receivermail, $from_name, $from_email, $userDefinedvalue);
					}

					if(isset($_POST['oldusername']) && trim($_POST['oldusername']) != trim($_POST['username']) && !isset($body)){
						$body = getMailTemplate(10, $receivename, $receivermail, $from_name, $from_email, $userDefinedvalue);
					}

					if(isset($_POST['password']) && trim($_POST['password']) != '' && !isset($body)){
						$body = getMailTemplate(10, $receivename, $receivermail, $from_name, $from_email, $userDefinedvalue);
					}

					if(isset($body)){
						if(isset($mail_notification) && $mail_notification == '1'){
							$subject = 'Login Credentials Has been changed on RAND!';	
							$send_mail= mail_function($receivename, $receivermail, $from_name, $from_email, $body, $subject);
						}
					}

				}

				return $result;
			}
		}

		//added by Praveen Singh on 09-07-2013
		function showUsersGlobalFunction($active,$is_deleted,$type,$db_id){
			global $db;
			
			$user            = new user();
			$admin           = new admin();
			$allSameusersStr = $allDiffusersStr = 0;
			$usersAll		 = array();

			$userSameArray   = $admin->selectAlldatabase($db_id,$diff=1);
			if(!empty($userSameArray)) {
				$allSameusersStr = implode(',',$userSameArray);
			}

			$userDiffArray   = $admin->selectAlldatabase($db_id,$diff=0);
			if(!empty($userDiffArray)) {
				$allDiffusersStr = implode(',',$userDiffArray);
			}
			
			$sql = "select * from users where 1 ";
			if($is_deleted == '1'){
				$sql.= " and is_deleted ='".$is_deleted."'";
			} else {
				$sql.= " and block_status='".$active."' and is_deleted ='".$is_deleted."' ";
			}

			if($type == 'trial'){
				$sql.= " and id NOT IN (".$allSameusersStr.") and id NOT IN (".$allDiffusersStr.") order by name ASC ";
			} else if($type == 'account'){
				$sql.= " and id IN (".$allSameusersStr.") order by name ASC ";
			} else {
				$sql.= " and id NOT IN (".$allDiffusersStr.") order by name ASC ";
			} 

			$usersResult = $db->run_query($sql);
			$usersAll    = $db->getAll($usersResult);
			return $usersAll;
		}

		
		# Function to  add additional information billing, admin & technical contact // Added By Baljinder
		function updateUserAdditionalFields($billingcontactarray, $admincontactarray, $technicalcontactarray, $userid) {

			global $db;

			$b_firstname	= mysql_real_escape_string($billingcontactarray['b_firstname']);
			$b_lastname		= mysql_real_escape_string($billingcontactarray['b_lastname']);
			$b_title		= mysql_real_escape_string($billingcontactarray['b_title']);
			$b_phone		= mysql_real_escape_string($billingcontactarray['b_phone']);
			$b_email		= mysql_real_escape_string($billingcontactarray['b_email']);
			//$b_address		= mysql_real_escape_string($billingcontactarray['b_address']);

			$t_firstname	= mysql_real_escape_string($technicalcontactarray['t_firstname']);
			$t_lastname		= mysql_real_escape_string($technicalcontactarray['t_lastname']);
			$t_title		= mysql_real_escape_string($technicalcontactarray['t_title']);
			$t_phone		= mysql_real_escape_string($technicalcontactarray['t_phone']);
			$t_email		= mysql_real_escape_string($technicalcontactarray['t_email']);
			//$t_address		= mysql_real_escape_string($technicalcontactarray['t_address']);

			$a_firstname	= mysql_real_escape_string($admincontactarray['a_firstname']);
			$a_lastname		= mysql_real_escape_string($admincontactarray['a_lastname']);
			$a_title		= mysql_real_escape_string($admincontactarray['a_title']);
			$a_phone		= mysql_real_escape_string($admincontactarray['a_phone']);
			$a_email		= mysql_real_escape_string($admincontactarray['a_email']);
		
			
			$sqlCheck = "select * from user_additional_details where user_id = '".$userid."'";
			$resultCheck = $db->getRow($sqlCheck);
			if(empty($resultCheck)){
				$sqlinsert = "insert into user_additional_details set user_id = '".$userid."', admin_contact = '".$a_firstname."', admin_contact_lastname = '".$a_lastname."', admin_title = '".$a_title."', admin_phone = '".$a_phone."', admin_email = '".$a_email."', tech_contact = '".$t_firstname."', tech_contact_lastname = '".$t_lastname."', tech_title = '".$t_title."', tech_phone = '".$t_phone."', tech_email = '".$t_email."', bill_contact = '".$b_firstname."', bill_contact_lastname = '".$b_lastname."', bill_title = '".$b_title."', bill_phone = '".$b_phone."', bill_email = '".$b_email."'";
				$insertResult = $db->insert($sqlinsert, $db->conn);
				return $insertResult;
			} else {
				$sqlupdate = "update user_additional_details set admin_contact = '".$a_firstname."', admin_contact_lastname = '".$a_lastname."', admin_title = '".$a_title."', admin_phone = '".$a_phone."', admin_email = '".$a_email."', tech_contact = '".$t_firstname."', tech_contact_lastname = '".$t_lastname."', tech_title = '".$t_title."', tech_phone = '".$t_phone."', tech_email = '".$t_email."', bill_contact = '".$b_firstname."', bill_contact_lastname = '".$b_lastname."', bill_title = '".$b_title."', bill_phone = '".$b_phone."', bill_email = '".$b_email."' where user_id = '".$userid."'";
				$updateResult = $db->update($sqlupdate, $db->conn);
				return $updateResult;
			}
		}
		
		// Function to get Additional details of user
		function getUserAdditionalFields($userid) {
			global $db;
			$sqlCheck = "select * from user_additional_details where user_id = '".$userid."'";
			$details = $db->getRow($sqlCheck);
			return $details;
		}

		// function to insert login history (added by Pragati Garg on 8/22/2013)
	function insertLoginHistory($client_ip, $user_id, $parent_id, $accessed_url,$database){    
		global $db;
		$sql="insert into login_history set ip='".$client_ip."', user_id='".$user_id."', parent_user_id='".$parent_id."', accessed_url='".mysql_real_escape_string($accessed_url)."', db='".$database."', accessed_on = NOW()";
		$result	= $db->run_query($sql);
		//$db->insert($sql, $db->conn);
		return mysql_insert_id();
	}

	function showLoginHistory($user_id,$ip_adr,$startDate, $endDate,$graph='0'){    
		global $db;
		$i=0;
		$sql="select * from login_history where user_id='".$user_id."'";
		if(isset($ip_adr) and $ip_adr!=0){
			$sql .= " and ip='".$ip_adr."'";
		}
		if(isset($startDate) and isset($endDate)){
			if($startDate!='' and $endDate==''){
				$sql .= " and accessed_on >= '".$startDate."'";
			}else if($startDate=='' and $endDate!=''){
				$sql .= " and accessed_on <= '".$endDate."'";
			} else if($startDate!='' and $endDate!=''){
				//$sql .= " and accessed_on between '".$startDate."' and '".$endDate."'";
				$sql .= " and (accessed_on >='".$startDate."' and accessed_on <='".$endDate." 23:59:59')";
			}
		}
		$sql .= " order by accessed_on ASC";
		$result = $db->run_query($sql);
		//$historyAll    = $db->getAll($result);
		if($graph=='0'){
			while($historyDetail = mysql_fetch_assoc($result)){
				$historyAll[$historyDetail['ip']][$historyDetail['accessed_url']][$i] = $historyDetail['accessed_on'];
				$i++;
			}
		}elseif($graph=='1'){
			while($historyDetail = mysql_fetch_assoc($result)){
				$date = substr($historyDetail['accessed_on'],0,-9);
				$historyAll[$historyDetail['ip']][$date][$i]['url'] = $historyDetail['accessed_url'];
				$historyAll[$historyDetail['ip']][$date][$i]['dateTime'] = $historyDetail['accessed_on'];
				$i++;
			}
		}
		if(isset($historyAll)){
			return $historyAll;
		}
	}

	function showAllLoginHistory($user_id, $graph='0'){    
		global $db;$i =0;
		$oneMonthBefore = date("Y-m-d", strtotime(date("Y-m-d")."-1 month"));
		$sql="select * from login_history where user_id='".$user_id."'";
		if($oneMonthBefore!=''){
			$sql .= " and accessed_on >= '".$oneMonthBefore."'";
		}
		$sql .= " order by accessed_on ASC";
		$result = $db->run_query($sql);
		if($graph=='0'){
			while($historyDetail = mysql_fetch_assoc($result)){
				$historyAll[$historyDetail['ip']][$historyDetail['accessed_url']][$i] = $historyDetail['accessed_on'];
				$i++;
			}
		}elseif($graph=='1'){
			while($historyDetail = mysql_fetch_assoc($result)){
				$date = substr($historyDetail['accessed_on'],0,-9);
				$historyAll[$historyDetail['ip']][$date][$i]['url'] = $historyDetail['accessed_url'];
				$historyAll[$historyDetail['ip']][$date][$i]['dateTime'] = $historyDetail['accessed_on'];
				$i++;
			}
		}
		if(isset($historyAll)){
			return $historyAll;
		}
	}

	/*function showUrlHistory($user_id,$ip,$date){    
		global $db;$i =0;
		$sql="select * from login_history where user_id='".$user_id."' and ip like '".$ip."' and (accessed_on >='".$date."' and accessed_on <='".$date." 23:59:59') order by accessed_on ASC";
		$result = $db->run_query($sql);
		$historyAll    = $db->getAll($result);
		
		return $historyAll;
	}*/

	function totalUsage($user_id, $startDate = "",$endDate= ""){
		global $db;
		if($startDate!="" && $endDate !=""){
			$sql = "select count(*) as totalusage from login_history where user_id='".$user_id."' and (accessed_on >='".$startDate."' and accessed_on <='".$endDate." 23:59:59')";
		} else {
			$sql = "select count(*) as totalusage from login_history where user_id='".$user_id."'";
		}
		$result = $db->getRow($sql);		
		return $result;
	}

	function showUrlHistory($user_id,$ip,$startDate,$endDate){    
		global $db;$i =0;
		$sql="select * from login_history where user_id='".$user_id."' and ip like '".$ip."' and (accessed_on >='".$startDate."' and accessed_on <='".$endDate." 23:59:59') order by accessed_on ASC";
		$result = $db->run_query($sql);
		while($urlDetail = mysql_fetch_assoc($result)){
			
			$explodewww = explode('www.',$urlDetail['accessed_url']);
			if(isset($explodewww['1'])){
				$urlDetail['accessed_url'] = $explodewww['0'].$explodewww['1'];
			}else{
				$urlDetail['accessed_url'] = $explodewww['0'];
			}
			
			$explodedev = explode('dev/',$urlDetail['accessed_url']);
			if(isset($explodedev['1'])){
				$urlDetail['accessed_url'] = $explodedev['0'].$explodedev['1'];
			}else{
				$urlDetail['accessed_url'] = $explodedev['0'];
			}

			$explodedata = explode('Data.php',$urlDetail['accessed_url']);
			if(isset($explodedata['1'])){
				$urlDetail['accessed_url'] = $explodedata['0'].'.php';
			}
			
			//echo $urlDetail['accessed_url'].'<br/>';
			$sharedUriBefore = strstr($urlDetail['accessed_url'],'dbc=cmFuZF91c2E=',true);

			if($sharedUriBefore!=''){
				$sharedUriBeforeMain = substr($sharedUriBefore,0,-1);
				$urlDetail['db'] = '4';
				$urlDetail['accessed_url'] = $sharedUriBeforeMain;
				$historyAll[$urlDetail['db']][$urlDetail['accessed_url']][$i]= $urlDetail['accessed_on'];
				$i++;
			}else{
				$historyAll[$urlDetail['db']][$urlDetail['accessed_url']][$i]= $urlDetail['accessed_on'];
				$i++;
			}
		}		
		return $historyAll;
	}

	function urlFormName($databasename,$dbid,$url){    
		global $db;$i =0;
		if($dbid==''){
			$sql="select * from ".$databasename.".searchforms where url='".$url."'";
		}else{
			$sql="select * from ".$databasename.".searchforms where id='".$dbid."'";
		}
		$result = $db->getRow($sql);		
		return $result;
	}

	function getDbCategories($databasename,$dbid){
		global $db;
		$sql			= "select * from ".$databasename.".`searchform_category` where database_id = '".$dbid."'";
		$categoriesResult	= $db->run_query($sql);
		$catAll    = $db->getAll($categoriesResult);
		return $catAll;
	}

	function getParentCatName($catid){
		global $db;
		$sql			= "select * from rand_admin.category where id = '".$catid."' ";
		$catDetail		= $db->getRow($sql);
		return $catDetail;
	}
	function getSubCatName($databasename,$catid){
		global $db;
		$sql			= "select * from ".$databasename.".categories where id = '".$catid."' ";
		$catDetail		= $db->getRow($sql);
		return $catDetail;
	}

	function showAllActiveDeactiveUsersByUserType($user_type, $active=0,$is_deleted=0){
			global $db;
			
			$sql = "select * from users where 1";

			if($user_type >0){
				$sql.= " and user_type = '".$user_type."'";
			}

			if($is_deleted == '1'){
				$sql.= " and is_deleted ='".$is_deleted."' order by name ASC";
			} else {
				$sql.= " and block_status='".$active."' and is_deleted ='".$is_deleted."' order by name ASC";
			}
	
			$usersResult = $db->run_query($sql);
			return $usersResult;
		}

	function multipleUserLoginHistory($user_id, $graph='0'){    
		global $db;$i =0;
		$oneMonthBefore = date("Y-m-d", strtotime(date("Y-m-d")."-1 month"));
		$sql="select * from login_history where parent_user_id='".$user_id."'";
		if($oneMonthBefore!=''){
			$sql .= " and accessed_on >= '".$oneMonthBefore."'";
		}
		$sql .= " order by accessed_on ASC";
		$result = $db->run_query($sql);
		if($graph=='0'){
			while($historyDetail = mysql_fetch_assoc($result)){
				$historyAll[$historyDetail['user_id']][$historyDetail['accessed_url']][$i] = $historyDetail['accessed_on'];
				$i++;
			}
		}elseif($graph=='1'){
			while($historyDetail = mysql_fetch_assoc($result)){
				$date = substr($historyDetail['accessed_on'],0,-9);
				$historyAll[$historyDetail['user_id']][$date][$i]['url'] = $historyDetail['accessed_url'];
				$historyAll[$historyDetail['user_id']][$date][$i]['dateTime'] = $historyDetail['accessed_on'];
				$i++;
			}
		}
		if(isset($historyAll)){
			return $historyAll;
		}
	}

	# Function to  get all users except single users 
	function showAllActiveDeactiveUsersByUserTypeExceptSingleUser(){
		global $db;
		$sql			= "select * from users where user_type!='5' order by id";
		$usersResult	= $db->run_query($sql);
		return $usersResult;
	}

	function deleteUserPermanent($id, $parent_id = 0){
		global $db;
		$sql = "delete from `users` where id = '".$id."' and parent_id = '".$parent_id."'";
		$deleteResult = $db->delete($sql);
		return $deleteResult;
	}

	//added by praveen singh on 14/11/2013
	function insertUserMailRenew($user_id, $email, $number_of_days ,$dbnames ){
		
		global $db;	
		$insertResult = 0;
		$sql_user_list	= "SELECT * FROM rand_admin.account_renews where user_id = '".$user_id."' and is_sent=1 ";
		$userDetail = $db->getRow($sql_user_list);
		if(!empty($userDetail)) {
			if($userDetail['number_of_days']!= $number_of_days){
				$sqlinsert = "insert into account_renews set user_id = '".$user_id."', email = '".mysql_real_escape_string($email)."',number_of_days = '".$number_of_days."', dbnames = '".mysql_real_escape_string($dbnames)."', is_sent = '0', sent_on = NOW() ";
				$insertResult = $db->insert($sqlinsert, $db->conn);	
			}			
					
		} else {
			$sqlinsert = "insert into account_renews set user_id = '".$user_id."', email = '".mysql_real_escape_string($email)."',number_of_days = '".$number_of_days."', dbnames = '".mysql_real_escape_string($dbnames)."', is_sent = '0', sent_on = NOW() ";
			$insertResult = $db->insert($sqlinsert, $db->conn);	
		}
		return $insertResult;
	}

	function getNumberOfLoginDetails($id, $startDate="", $endDate = ""){
		global $db;
		$historyAll =array();
		$this->sql = "select * from login_history where user_id='".$id."'";

		if(isset($startDate) and isset($endDate)){

			if($startDate!='' and $endDate==''){
				$this->sql .= " and accessed_on >= '".$startDate."'";
			}else if($startDate=='' and $endDate!=''){
				$this->sql .= " and accessed_on <= '".$endDate."'";
			} else if($startDate!='' and $endDate!=''){
				//$sql .= " and accessed_on between '".$startDate."' and '".$endDate."'";
				$this->sql .= " and (accessed_on >='".$startDate."' and accessed_on <='".$endDate." 23:59:59')";
			}

		}

		$this->res = $db->run_query($this->sql);
		if(mysql_num_rows($this->res)){
			while($historyDetail = mysql_fetch_assoc($this->res)){
				$historyAll[$historyDetail['db']][$historyDetail['ip']][$historyDetail['accessed_url']][]= $historyDetail;
			}
		}
		return $historyAll;	
	}

	//added by praveen singh on 14/11/2013
	function updateUserMailRenew($id){
		global $db;	
		$sql	= "update account_renews set is_sent='1' where id = '".$id."'";
		return $updated= $db->run_query($sql);			
	}

} //userclass ends/
?>