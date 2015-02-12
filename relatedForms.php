<h2>Related Databases</h2>
<ul>
	<?php if(mysql_num_rows($related_DB)>0){ 
		while($r_DB = mysql_fetch_assoc($related_DB)) {
			$db_detail = $admin->getDatabase($r_DB['related_database_id']);	
			$urlembedd = (isset($_REQUEST['dbc']))?'dbc='.$_REQUEST['dbc'].'':'';
			
			if($db_detail['is_static_form'] == 'Y' && $db_detail['url']!='' ){
				if($urlembedd==''){?>
					<li><a href="<?php echo URL_SITE;?>/<?php echo $db_detail['url'];?>"><?php echo ucfirst($db_detail['db_name']);?></a></li>
				<?php }else { ?>
					<li><a href="<?php echo URL_SITE;?>/<?php echo $db_detail['url'].'?'.$urlembedd;?>"><?php echo ucfirst($db_detail['db_name']);?></a></li>
				<?php }
			}else { 
				if($urlembedd==''){?>
					<li><a href="<?php echo URL_SITE;?>/form.php?id=<?php echo base64_encode($db_detail['id']);?>"><?php echo ucfirst($db_detail['db_name']);?></a></li>
				<?php }else { ?>
					<li><a href="<?php echo URL_SITE;?>/form.php?id=<?php echo base64_encode($db_detail['id']).'&'.$urlembedd;?>"><?php echo ucfirst($db_detail['db_name']);?></a></li>
				<?php }
				} ?>
		<?php } ?>
	<?php } ?>	
</ul>
