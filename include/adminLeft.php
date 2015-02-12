<?php
$select_unverified_users_ips_left =	$admin->select_all_users_ips($status=1);
$total_unverfied_userips_left	  =	count($select_unverified_users_ips_left);
?>
<h2>Change Database</h2>
<ul>
	<li>
	<form name="frmChangeDatabaseToBeUsed" action = "" method="post" id="frmChangeDatabaseToBeUsed">
	<select id="databaseToBeUse" name="databaseToBeUse">
		<option value="rand_texas" <?php if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']=='rand_texas'){ echo "selected='selected'"; } ?>>Rand Texas</option>
		<option value="rand_california" <?php if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']=='rand_california'){ echo "selected='selected'"; } ?>>Rand California</option>
		<option value="rand_newyork" <?php if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']=='rand_newyork'){ echo "selected='selected'"; } ?>>Rand Newyork</option>
		<option value="rand_usa" <?php if(isset($_SESSION['databaseToBeUse']) && $_SESSION['databaseToBeUse']=='rand_usa'){ echo "selected='selected'"; } ?>>Rand US</option>
	</select>
	</form>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery('#databaseToBeUse').change(function(){
				jQuery('#frmChangeDatabaseToBeUsed').submit();
			});
		});
	</script>
	</li>		
</ul>

<h2>Global Category Management</h2>
<ul>
	<li><a href="categories.php?categoryid=parent">All Categories</a></li>
	<li><a href="category.php?parent=1">Add Category</a></li>
</ul>

<h2>General Settings</h2>
<ul>
	<li><a href="generalSettings.php">General Settings</a></li>	
	<li><a href="adminStatistics.php">Update Forms</a></li>	
	<li><a href="changePassword.php">Change Password</a></li>
	<li><a href="changeUsername.php">Change Username</a></li>
</ul>

<h2>Notification Management</h2>
<ul>
	<li><a href="notifyUser.php">Notify Users</a></li>	
	<li><a href="ipVerification.php">Verify IP<?php if(isset($total_unverfied_userips_left) && $total_unverfied_userips_left!='0') { echo '<span class="red pL5"><blink>('.$total_unverfied_userips_left.')</blink></span>';} ?></a></li>	
</ul>

<h2>Category Management</h2>
<ul>
	<li><a href="categories.php">All Categories</a></li>
	<li><a href="category.php">Add Category</a></li>
</ul>

<h2>Search Form Management</h2>
<ul>
	<li><a href="databases.php">All Forms</a></li>
	<li><a href="database.php">Add Form</a></li>
</ul>

<h2>Statistics Management</h2>
<ul>
	<li><a href="statistics.php">Statistics</a></li>
	<li><a href="userLoginStatistics.php">Usage Statistics</a></li>
</ul>

<h2>User Management</h2>
<ul>
	<li><a href="addUser.php">Add User</a></li>	
	<li>
		<a href="users.php">All Users</a>
		<ul style="padding-left:20px;">
			<li style="padding:0px;"><a href="users.php?type=trial">Trial Users</a></li>
			<li style="padding:0px;"><a href="users.php?type=account">Account Users</a></li>
		</ul>		
	</li>
	<!-- <li><a href="userType.php">Add User Type</a></li> -->
	<li><a href="userTypes.php">User Susbcription Types</a></li>
	<li><a href="user_contacts.php">Contacted Users</a></li>
</ul>

<h2>Transaction Management</h2>
<ul>
	<li><a href="transactions.php">All Transactions</a></li>
	<li><a href="transactions.php?status=1">Completed Transactions</a></li>
	<li><a href="transactions.php?status=0">Pending Transactions</a></li>
</ul>

<!-- <h2>Plan Management</h2>
<ul>
	<li><a href="subscriptionPlans.php">All Plans</a></li>
	<li><a href="subscriptionPlan.php">Add Plan</a></li>
</ul>

<h2>Subscription Management</h2>
<ul>
	<li><a href="subscriptions.php?type=plan">All Subscription Plan</a></li>
	<li><a href="subscriptions.php?type=addedit">Add Subscription Plan</a></li>
</ul> -->

<h2>News Management</h2>
<ul>
	<li><a href="news.php"> All News and Updates</a></li>
	<li><a href="add_news.php"> Add News</a></li>
</ul>

<h2>Content Management</h2>
<ul>
	<li><a href="cms_pages.php"> All pages</a></li>
	<li><a href="add_new_page.php"> Add Page</a></li>
</ul>

<h2>E-Mail Content Management</h2>
<ul>
	<!-- <li><a href="mail_content.php"> All Templates</a></li>
	<li><a href="edit_content.php"> Add Content</a></li> -->

	<li><a href="sendMails.php">Send Mails</a></li>
</ul>

