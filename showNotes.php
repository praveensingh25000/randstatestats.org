<?php
/******************************************
* @Modified on Aug 21, 2013.
* @Package: RAND
* @Developer: Praveen Singh
* @URL : http://www.ideafoundation.in
********************************************/

if(isset($formfootnotes) && $formfootnotes!='') { ?>
	<table class="pT10" border="0" cellpadding="5" width="100%">
		<tbody>
		<tr>
			<td align="left"><?php echo $formfootnotes;?></td>
		</tr>
		</tbody>
	</table>
<?php } ?>

<?php if(!empty($footNote)) { ?>
	<table border="0" cellpadding="5" width="100%">
		<tbody>
			<?php foreach($footNote as $NoteId=> $NoteDescr){?>		
			<tr>
				<td align="left"><strong><?php echo $NoteId;?>:</strong></td>
				<td align="left"><?php echo $NoteDescr;?></td>
			</tr>		
			<?php }	?>
			<tr>
				<td align="left">&nbsp;</strong></td>
				<td align="left">&nbsp;</td>
			</tr>
		</tbody>
	</table>
<?php } ?>