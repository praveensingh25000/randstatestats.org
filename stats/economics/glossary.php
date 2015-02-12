<?php
/******************************************
* @Modified on March 23, 2012
* @Package: Rand
* @Developer: Pragati Garg
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";
?>

 <!-- container -->
<section id="container">

	<section class="conatiner-full" id="inner-content">
		<h2>RAND USA Category Definitions</h2><br/>


		<p>
		<table border="1" cellpadding="8" class="data-table">
			<tr class="gray">
				<th>Category</th>
				<th>Definition</th>
			</tr>
			<tr>
				<td valign="top">Establishment size</td>
				<td valign="top">Average of the establishment&#8217;s year t-1 and year t employment, e.g., for 2010, this includes 2009 and 2010.</td>
			</tr>

			<tr>
				<td valign="top">Number of firms</td>
				<td valign="top">For state level tables, a firm with establishments in multiple states be counted multiple times, once in each state, irrespective of the portion of the firm residing in that state.</td>
			</tr>

			<tr>
				<td valign="top">Number of establishments</td>
				<td valign="top">NA</td>
			</tr>

			<tr>
				<td valign="top">Employment</td>
				<td valign="top">March 12 of each year.</td>
			</tr>

			<tr>
				<td valign="top">Denominator (Avg. employment this and prev. year)</td>
				<td valign="top">Davis-Haltiwanger-Schuh (DHS) denominator. For time t, denominator is the average of employment for times t and t-1. This variable attempts to prevent transitory shocks from creating a bias to the relationship between net growth from t-1 to t and size. </td>
			</tr>

			<tr>
				<td valign="top">Establishments born</td>
				<td valign="top">A count of establishments born  during the last 12 months.</td>
			</tr>

			<tr>
				<td valign="top">Establishments entry rate</td>
				<td valign="top">100 * Establishments born divided by establishment size.</td>
			</tr>

			<tr>
				<td valign="top">Establishments exiting</td>
				<td valign="top">100 * (Establishments exiting at time t / by the average of estabs at t and t-1).</td>
			</tr>

			<tr>
				<td valign="top">Job creation</td>
				<td valign="top">Count of all jobs created within the cell over the last 12 months.</td>
			</tr>

			<tr>
				<td valign="top">Jobs created by establishment births</td>
				<td valign="top">Count of jobs created within the cell by establishment births over the last 12 months.</td>
			</tr>

			<tr>
				<td valign="top">Jobs created by continuing establishments</td>
				<td valign="top">Count of jobs created by continuing establishments over the last 12 months.</td>
			</tr>

			<tr>
				<td valign="top">Job creation rate by establishment births</td>
				<td valign="top">100 * (Jobs created by establishment births over the last 12 months / Denominator)</td>
			</tr>

			<tr>
				<td valign="top">Total job creation rate</td>
				<td valign="top">100 * (Job creation / Denominator)</td>
			</tr>

			<tr>
				<td valign="top">Jobs destroyed</td>
				<td valign="top">Count of all jobs destroyedover the last 12 months.</td>
			</tr>

			<tr>
				<td valign="top">Jobs destroyed by establishment exit</td>
				<td valign="top">Count of jobs destroyed by establishment exit over the last 12 months.</td>
			</tr>

			<tr>
				<td valign="top">Jobs destroyed at continuing establishments</td>
				<td valign="top">Count of jobs destroyed at continuing establishments over the last 12 months.</td>
			</tr>

			<tr>
				<td valign="top">Job destruction rate by establishment exits</td>
				<td valign="top">100 * (Jobs destroyed by establishment exit / Denominator)</td>
			</tr>

			<tr>
				<td valign="top">Total job destruction rate</td>
				<td valign="top">100 * (Jobs destroyed / Denominator)</td>
			</tr>

			<tr>
				<td valign="top">Net job creation</td>
				<td valign="top">Total job creation rate - Total job destruction rate</td>
			</tr>

			<tr>
				<td valign="top">Net job creation rate</td>
				<td valign="top">Total job creation rate + Total job destruction rate - Net job creation rate</td>
			</tr>

			<tr>
				<td valign="top">Reallocation rate</td>
				<td valign="top">Total job creation rate + Total job destruction rate - absolute (Net job creation rate)</td>
			</tr>


			

		</table>
		</p>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>