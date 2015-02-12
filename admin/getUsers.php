<?php
/******************************************
* @Modified on Sept 6, 2013
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/
$basedir=dirname(__FILE__)."/..";
include_once $basedir."/include/actionHeader.php";

$user = new user();


if(isset($_POST['user_type']) && $_POST['user_type']!=''){
	if($_POST['user_type']=='-1'){ 
		$usersResult = $user->showAllActiveDeactiveUsersByUserTypeExceptSingleUser();		
	}elseif($_POST['user_type']!='0'){ 
		$usersResult = $user->showAllActiveDeactiveUsersByUserType($_POST['user_type']);
	} else{
		$usersResult = $user->showAllUsers();
	}
} else {
	$usersResult = $user->showAllUsers();
}

if(mysql_num_rows($usersResult)>0){
		while($userDetail = mysql_fetch_assoc($usersResult)){
			$additionalDetail = $user->getUserAdditionalFields($userDetail['id']);
	?>

	<tr>
		<td align="center"><input type="checkbox" class="check_all" name="userids[]" value="<?php echo $userDetail['id']; ?>"/></th>
		<td><?php echo stripslashes($userDetail['name'].' '.$userDetail['last_name']); ?></td>
		<td><?php echo stripslashes($userDetail['organisation']); ?></td>
		<td><?php echo $userDetail['email']; ?> / <?php if(!empty($additionalDetail)) { echo trim($additionalDetail['admin_email']); } else { echo "NA"; } ?></td>

		<td><?php if(!empty($additionalDetail) && $additionalDetail['bill_email']!='') { echo trim($additionalDetail['bill_email']); } else { echo "NA"; }?> / <?php if(!empty($additionalDetail) && $additionalDetail['tech_email']) { trim($additionalDetail['tech_email']); } else { echo "NA"; } ?></td>
	
	</tr>
<?php
	}
}
?>