<?php
/******************************************
* @Modified on March 23, 2012
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
	$csv->auto('naicslist.csv');
}

?>

 <!-- container -->
<section id="container">

	<section class="conatiner-full" id="inner-content">
	
	<h1>North American Industrial Classification System (NAICS) Background</h1>
	<?php if(!isset($_POST['getresults'])){ ?>
		<p>&nbsp;</p>
		<p>
		This site provides a range of information based on
		<a href="http://www.census.gov/eos/www/naics/" target="_blank">North American Industry Classification System</a>
		categories, including employment data and average wages in over 2000 industries and
		aggregated industry groupings for the United States, states, regions, MSAs, 
		and all U.S. counties. This NAICS schedule is determined by the U.S. Department of 
		Commerce's North American Industrial Classification System. (Office of Management and Budget, 
		North American Industrial Classification System 2002, NTIS, Springfield, VA, 2002. The reference 
		book is available through most Federal Government book stores.)</p><p>&nbsp;</p>

		<p>This schedule assigns two-, three-, four-, five- and six-digit codes to industries with 
		similar services or products. Two digit codes indicate industry sectors. (E.g., sector 33 
		includes Manufacturing.) Three-, four-, five- and six-digit codes reflect narrow industry 
		groupings. (E.g., 333 includes Machinery Manufacturing; 3331 includes Agriculture, Construction, 
		and Mining Machinery Manufacturing; 33311 includes Agricultural Implement Manufacturing; 333111 
		includes Farm Machinery and Equipment Manufacturing).</p><p>&nbsp;</p>

		<p>Sectors include:</p><p>&nbsp;</p>

		<ul type="disc">

		<table summary="The contents of this table lists the major NAICS sectors.">
		<tr>
		<td valign=top>
		<li>Construction
		<li>Financial Activites
		<li>Goods Producing
		<li>Government
		<li>Manufacturing
		<li>Natural Resources & Mining
		<li>Retail Trade
		<li>Service Producing
		<li>Total Farm
		</td>

		<td width=30></td>

		<td valign=top>
		<li>Total Nonfarm
		<li>Total All Industries
		<li>Transportation and Warehousing
		<li>Wholesale Trade
		<li>Information
		<li>Professional and Business Services
		<li>Educational & Health Services
		<li>Leisure & Hospitality
		<li>Other Services
		</td>
		</tr>
		</table>
		</ul>

		<p>&nbsp;</p>

		<p class="bb"><strong>Search for a specific industry, associated sector, and NAICS code</strong>
		</p>

		<p>
		<span class="bb"><strong>Enter one or more keywords</span>
		(boolean terms are allowed, use quotes for exact phrase).</strong><br>
		Examples: aircraft, chemicals, business and services, "soybean farming".
		</p><br/>

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
			$actualdataArray = explode(' ', $row['Industry Name']);
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
		<tr><td colspan="2">No Records Found</td></tr>
		<?php } ?>
		</table>
	</div>
	<br>
	<p><a href="naicslist.php">Return</a></p>
	<?php } ?>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>