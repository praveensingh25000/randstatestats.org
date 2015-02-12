<?php
/******************************************
* @Modified on March 25, 2012
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";

if(isset($_POST['getresults'])){
	# include parseCSV class.
	require_once('parsecsv.lib.php');
	# create new parseCSV object.
	$csv = new parseCSV();
	$csv->auto('siclist.csv');
}

?>

 <!-- container -->
<section id="container">

	<section class="conatiner-full" id="inner-content">

	<h2>Standard Industrial Classification (SIC) Background</h2><br/>

	<?php if(!isset($_POST['getresults'])){ ?>
		<p>&nbsp;</p>

		<p>RAND California provides a range of
		information based on Standard Industrial Classification
		categories. including employment data and average wages in up to
		1400 industries and aggregated industry groupings for the United
		States, Texas, Texas regions, MSAs, and all Texas
		counties. This SIC schedule is determined by the U.S. Department
		of Commerce's Standard Industrial Classification schedule.
		(Office of Management and Budget, <i>Standard Industrial
		Classification Manual 1987</i>, NTIS, Springfield, VA, 1987. The
		reference book is available through most Federal Government book
		stores.)</p><br/>

		<p>This schedule assigns one-, two-, three-
		and four-digit codes to industries with similar services or
		products. One digit codes indicate industry sectors. (E.g.,
		sector 8 includes most service industries.) Two-digit codes
		indicate major groups. (E.g., SIC 87 includes all Engineering,
		Accounting, Research, Management and Related Services.) Three-
		and four-digit codes reflect narrow industry groupings. (E.g.,
		SIC 873 includes all Research, Development, and Testing Services,
		while SIC 8731 includes all Commercial Physical and Biological
		Research.)</p><p>&nbsp;</p>

		<p>Sectors include:</p><p>&nbsp;</p>

		<ul type="disc">
			<li>Agriculture</li>
			<li>Mining</li>
			<li>Construction</li>
			<li>Manufacturing</li>
			<li>Wholesale Trade</li>
			<li>Retail Trade</li>
			<li>Transportation and Public Utilities</li>
			<li>Finance, Insurance, and Real Estate</li>
			<li>Services</li>
			<li>Government.</li>
		</ul>

		<p>&nbsp;</p>

		<p><a name="Search for a specific industry."><strong>Search for a
		specific industry, associated sector, and SIC code</strong></span></a></p>

		<p><span class="blue"><strong>Enter a keyword
		or keywords. Boolean terms are allowed.</strong></span><br>
		Examples: aircraft, chemicals, business and services.</p>
		<form action="" method="POST">

		<p>
		<label for="keywords">
		<input id="keywords" type="text" size="50" name="keys">
		</label></p><p>&nbsp;</p>

		<p>
		<input type="submit" title="Search for keywords" name="getresults" value="Search">
		<input type="reset" name="reset" value="Reset">
		</p>

		</form>
		<!-- <form action="" method="POST">
			<input type="hidden" name="chart_title"
			value="Industry Name List"><p><input
			type="text" size="50" name="Industry Name"></p>
			<p>&nbsp;</p>
			<!-- <p><span class="blue"><strong>Choose a
			format for the data.</strong></span></p>
			<p><select name="table" size="1">
				<option value="table">View online (HTML table)</option>
				<option value="spreadsheet">Tab delimited (import to spreadsheets)</option>
			</select></p> 
			<p>&nbsp;</p>
			<p><input type="submit"><input
			type="reset" name="name" value="Reset"></p>
			<!-- <p>Maximum number of hits. <select
			name="MAXCOUNT" size="1">
				<option selected>20 </option>
				<option>30 </option>
				<option>40 </option>
				<option>50 </option>
				<option>100 </option>
			</select> Increase this number if the server does not return
			all the data you want.</span></p> 
		</form> -->
		<?php } else { 
	$found = 0;		
	?>
	<br/>
	<div style="" class="search-table-data toPrint">
		<table class="data-table">
		<thead>
		<tr>
			<?php foreach ($csv->titles as $value): ?>
			<th class="header"><?php echo $value; ?></th>
			<?php endforeach; ?>
		</tr>
		</thead>
		<?php foreach ($csv->data as $key => $row): 
			
			$foundstr = 0;
if($row['Industry Name']){
	$dataToFound = $row['Industry Name'];
}else{
//if($row['Industry Sector'] > 0){
	$dataToFound= $row['Industry Sector'];
}
			
			$actualdataArray = explode(' ', $dataToFound);
			$actualdataArrayTemp = array();
			foreach($actualdataArray as $keyactual => $actvalue){
				$actualdataArrayTemp[] = strtolower(trim($actvalue));
			}
			$posteddataArray = explode(' ', $_POST['keys']);
			foreach($posteddataArray as $keypost => $postedvalue){
				if(in_array(strtolower(trim($postedvalue)), $actualdataArrayTemp)){
					$foundstr = 1;
					break;
				}
			}


		if($foundstr == 1){ $found = 1;?>
		<tr>
			<?php foreach ($row as $value): ?>
			<td align="left"><?php echo $value; ?></td>
			<?php endforeach; ?>
		</tr>
		<?php 
		}	
		endforeach; ?>

		<?php if($found == 0){ ?>
		<tr><td colspan="3">No Records Found</td></tr>
		<?php } ?>
		</table>
	</div>
	<br>
	<p><a href="siclist.php">Return</a></p>
	<?php } ?>

	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>