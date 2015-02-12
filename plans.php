<?php
/******************************************
* @Modified on Dec 26, 2012,Jan 24 2013
* @Package: Rand
* @Developer: Baljinder, Saket Bisht,Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."";
include_once $basedir."/include/headerHtml.php";

$admin = new admin();
$userTypesResult = $admin->showAllUserTypes();

$total = $db->count_rows($userTypesResult );
$types = $db->getAll($userTypesResult);

?>
<a name="top"></a>
<section id="container">
	<section id="inner-content"  class="conatiner-full" >
		<h2>Plans</h2>
		
		<p>&nbsp;</p>
		<p>We offer plans based on user types for one, two, and three years.  Subscribers to CA, NY, or TX receive US at no additional charge.  For example, a public university subscriber to RAND TX pays 17¢ per FTE and receives access to TX and US.  That subscriber can add CA (or NY) for an additional 20.6% or can add both CA and NY for an additional 30.1%.  For more information or for rates for library types not listed below (such as Special Libraries), please email <a href="mailto:<?php echo FROM_EMAIL; ?>"><?php echo FROM_EMAIL; ?></a><br/><br/><a href="signup.php"><input type="button"  class="submitbtn" value="Subscribe Now" ></a></p>
		<p>&nbsp;</p>

		<div class="wdthpercent20 left">
			<ul>
				<?php 
				if($types >0){ 
					$i=0;
					foreach($types as $key => $typeDetail) {
						$userTypeInfo = $user->getUserTypeInfo($typeDetail['id']);
						if(!empty($userTypeInfo)){
							$i++;
				?>

				<li class="spalnli <?php if($i == 1){?> active <?php } ?>" id="user_type_li_<?=$typeDetail['id']?>"><span><a href="javascript:;" ><?=$typeDetail['user_type']?></a></span>
				<SCRIPT LANGUAGE="JavaScript">
				jQuery(document).ready(function(){
					jQuery("#user_type_li_<?=$typeDetail['id']?>").click(function(){
						jQuery(".data-table").hide();
						jQuery("#user_type_<?=$typeDetail['id']?>").show();
						jQuery(".spalnli").removeClass('active');
						jQuery(this).addClass('active');
					});
				});
				</SCRIPT>
				</li>
				<?php } } 
				}
				?>

			</ul>
		</div>
		<div class="wdthpercent70 left">
				<?php if($types >0){ 
					$i=0;
					foreach($types as $key => $typeDetail) { 
					
						$userTypeInfo = $user->getUserTypeInfo($typeDetail['id']);
						if(!empty($userTypeInfo)){
							$i++;
							$pricepercentage = (isset($userTypeInfo['pricepercentage']))?$userTypeInfo['pricepercentage']:'';
							$pricetxt = '%';
							$priceper = '';
							if($pricepercentage == 1){
								$priceper = '$';
								$pricetxt = '';
							}
				?>

						<table id="user_type_<?=$typeDetail['id']?>" class="data-table" <?php if($i != 1){?> style="display:none;" <?php } ?>>
						<tr><th colspan="3"><?=$typeDetail['user_type']?></th></tr>
					
					
						<tr>
							<td>&nbsp;</th>
							<td><b> CA, NY, TX </b></td>
							<td><b> US </b></td>
						</tr>
						<tr>
							<td>Annual Base Price/User</td>
							<td>$<?=$userTypeInfo['baseprice']?> </td>
							<td>$<?=$userTypeInfo['basepriceus']?> </td>
						</tr>
						<?php if($userTypeInfo['minimumprice']>0){ ?>
						<tr>
							<td>Minimum Price</td>
							<td><?=($userTypeInfo['minimumprice']>'0')?"$".$userTypeInfo['minimumprice']."":'NA'?></td>
							<td><?=($userTypeInfo['minimumprice']>'0')?"$".$userTypeInfo['minimumprice']."":'NA'?></td>
						</tr>
						<?php } ?>

						<tr>
							<td>Add 1 State</td>
							<td><?php
							if($typeDetail['id'] == 6){
								$surcharge1 = ($userTypeInfo['surchargeonestate']!='0')?$priceper."".$userTypeInfo['surchargeonestate'].' '.$pricetxt:'NA';
								echo "First Login: ".$surcharge1;

								$surcharge1 = ($userTypeInfo['surchargeonestateindividual']!='0')?$priceper."".$userTypeInfo['surchargeonestateindividual'].' '.$pricetxt:'NA';
								echo "<br/>";
								echo "Each Additional Login: ".$surcharge1;

							} else {
								echo ($userTypeInfo['surchargeonestate']!='0')?$priceper."".$userTypeInfo['surchargeonestate'].' '.$pricetxt:'NA';
							}	
								
							?></td>
							
							<td><?php
							if($typeDetail['id'] == 6){
								$surcharge1 = ($userTypeInfo['surchargeonestateus']!='0')?$priceper."".$userTypeInfo['surchargeonestateus'].' '.$pricetxt:'NA';
								echo "First Login: ".$surcharge1;

								$surcharge1 = ($userTypeInfo['surchargeonestateusindividual']!='0')?$priceper."".$userTypeInfo['surchargeonestateusindividual'].' '.$pricetxt:'NA';
								echo "<br/>";
								echo "Each Additional Login: ".$surcharge1;

							} else {
								echo ($userTypeInfo['surchargeonestateus']!='0')?$priceper."".$userTypeInfo['surchargeonestateus'].' '.$pricetxt:'NA';
							}	
								
							?></td>
						</tr>

						<tr>
							<td>Add 2 States</td>
							
							<td><?php
							if($typeDetail['id'] == 6){
								$surcharge1 = ($userTypeInfo['surchargetwostate']!='0')?$priceper."".$userTypeInfo['surchargetwostate'].' '.$pricetxt:'NA';
								echo "First Login: ".$surcharge1;

								$surcharge1 = ($userTypeInfo['surchargetwostateindividual']!='0')?$priceper."".$userTypeInfo['surchargetwostateindividual'].' '.$pricetxt:'NA';
								echo "<br/>";
								echo "Each Additional Login: ".$surcharge1;

							} else {
								echo ($userTypeInfo['surchargetwostate']!='0')?$priceper."".$userTypeInfo['surchargetwostate'].' '.$pricetxt:'NA';
							}	
								
							?></td>
							
							<td><?php
							if($typeDetail['id'] == 6){
								$surcharge1 = ($userTypeInfo['surchargetwostateus']!='0')?$priceper."".$userTypeInfo['surchargetwostateus'].' '.$pricetxt:'NA';
								echo "First Login: ".$surcharge1;

								$surcharge1 = ($userTypeInfo['surchargetwostateusindividual']!='0')?$priceper."".$userTypeInfo['surchargetwostateusindividual'].' '.$pricetxt:'NA';
								echo "<br/>";
								echo "Each Additional Login: ".$surcharge1;

							} else {
								echo ($userTypeInfo['surchargetwostateus']!='0')?$priceper."".$userTypeInfo['surchargetwostateus'].' '.$pricetxt:'NA';
							}	
								
							?></td>
						</tr>


							<tr>
							<td>Add 3 States</td>
							
							
							<td><?php
							if($typeDetail['id'] == 6){
								$surcharge1 = ($userTypeInfo['surchargethreestate']!='0')?$priceper."".$userTypeInfo['surchargethreestate'].' '.$pricetxt:'NA';
								echo "First Login: ".$surcharge1;

								$surcharge1 = ($userTypeInfo['surchargethreestateindividual']!='0')?$priceper."".$userTypeInfo['surchargethreestateindividual'].' '.$pricetxt:'NA';
								echo "<br/>";
								echo "Each Additional Login: ".$surcharge1;

							} else {
								echo ($userTypeInfo['surchargethreestate']!='0')?$priceper."".$userTypeInfo['surchargethreestate'].' '.$pricetxt:'NA';
							}	
								
							?></td>
							
							<td><?php
							if($typeDetail['id'] == 6){
								$surcharge1 = ($userTypeInfo['surchargethreestateus']!='0')?$priceper."".$userTypeInfo['surchargethreestateus'].' '.$pricetxt:'NA';
								echo "First Login: ".$surcharge1;

								$surcharge1 = ($userTypeInfo['surchargethreestateusindividual']!='0')?$priceper."".$userTypeInfo['surchargethreestateusindividual'].' '.$pricetxt:'NA';
								echo "<br/>";
								echo "Each Additional Login: ".$surcharge1;

							} else {
								echo ($userTypeInfo['surchargethreestateus']!='0')?$priceper."".$userTypeInfo['surchargethreestateus'].' '.$pricetxt:'NA';
							}	
								
							?></td>
						</tr>
						</table>
					
					<?php } } }?>
			</div>
			<div class="clear">&nbsp;</div>
	</section>
</section>
<?php
include_once $basedir."/include/footerHtml.php";
?>