<?php
/******************************************
* @Modified on April 22, 2012
* @Package: Rand
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";
?>

 <!-- container -->
<section id="container">

	<section class="conatiner-full" id="inner-content">
	
		<h2>RAND California Inflation Statistics Background</h2>
		<p>&nbsp</p>
		<p>RAND California provides Consumer Price Index data for urban consumers (CPI-U) for the U.S., California, and major California regions. (RAND California estimate the statewide inflation rate based on the population ratios in the San Francisco-Oakland-San Jose and Los Angeles-Riverside-Orange County MSAs, as defined by the U.S. Office of Management and Budget.) The CPI-U, published by the Bureau of Labor Statistics (BLS), is the most commonly-used inflation measure for a number of purposes, such as cost-of-living adjustments, real estate contracts, and other inflation-adjustment measures. CPI-U measures the average change in prices paid for a market basket of goods purchased for consumption for urban consumers (about 80% of the U.S. population). RAND California does not publish CPI for Urban Wage Earners and Clerical Workers (CPI-W). </p>
		<p>&nbsp</p>
		<p>CPI-U is not adjusted for seasonal variations. Contact the Bureau of Labor Statistics for seasonally-adjusted CPI statistics.</p>
		<p>&nbsp</p>
		<p>RAND California inflation statistics publishes two types of CPI-U data: the CPI-U index and a moving annual percent change. CPI-U index data reflects raw data provided by the BLS. Users can estimate the inflation for any time period using the index. For example, in the following example:</p>
		<p>&nbsp</p>
		<p>
			<table cellpadding="8" border="0" border="1" class="data-table">
			<tbody><tr>
			<th>Time period</th>
			<th>Index</th>
			</tr>
			<tr>
			<td>January 1997</td>
			<td>155.7</td>
			</tr>
			<tr>
			<td>January 1998</td>
			<td>159.1</td>
			</tr>
		</tbody></table>
		<p>&nbsp</p>
		</p>
		<p>CPI-U = ((Index<sub>Jan. 1997</sub>-Index<sub>Jan. 1996)</sub>)/Index<sub>Jan. 1996</sub>)*100 = (159.1/155.7) = 2.2%.</p>
		<p>&nbsp</p>
		<p>This one-year moving average is the method RAND California utilizes to publish CPI-U as a moving annual average. For example, the CPI-U figure for January 1997 reflects the change in CPI from January 1996-January 1997 as calculated above. </p>

	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>