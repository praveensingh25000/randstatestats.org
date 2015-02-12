<?php
/******************************************
* @Created on June 19, 2013
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
	
		<h2>Business Dynamics: Category Definitions</h2>
		<p>&nbsp;</p>
		<p>
			<table border="1" width="700" class="data-table">
				<tr>
					<th valign="top" class="thead_gr"><strong>Category</strong></th>
					<th valign="top" class="thead_gr"><strong>Definition</strong></th>
				</tr>

				<tr>
					<td>Establishment size</td>
					<td>Average of the establishment's year t-1 and year t employment, e.g., for 2010, this includes 2009 and 2010.</td>
				</tr>

				<tr>
					<td>Number of firms</td><td>For state level tables, a firm with establishments in multiple states be counted multiple times, once in each state, irrespective of the portion of the firm residing in that state.</td>
				</tr>

				<tr>
					<td>Number of establishments</td><td>NA</td>
				</tr>

				<tr>
					<td>Employment</td><td>March 12 of each year.</td>
				</tr>

				<tr>
					<td>Denominator (Avg. employment this and prev. year)</td><td>Davis-Haltiwanger-Schuh (DHS) denominator. For time t, denom is the average of employment for times t and t-1. This variable attempts to prevent transitory shocks from creating a bias to the relationship between net growth from t-1 to t and size.</td> 
				</tr>

				<tr>
					<td>Establishments born</td><td>A count of establishments born  during the last 12 months.</td> 
				</tr>

				<tr>
					<td>Establishments entry rate</td><td>100 * Establishments born divided by establishment size.</td>
				</tr>

				<tr>
					<td>Establishments exiting</td><td>A count of establishments exiting from within the cell during the last 12 months.</td> 
				</tr>

				<tr>
					<td>Establishments exit rate</td><td>100 * (Establishments exiting at time t / by the average of estabs at t and t-1).</td>
				</tr>

				<tr>
					<td>Job creation</td><td>Count of all jobs created within the cell over the last 12 months.</td>
				</tr>

				<tr>
					<td>Jobs created by establishment births</td><td>Count of jobs created within the cell by establishment births over the last 12 months.</td> 
				</tr>

				<tr>
					<td>Jobs created by continuing establishments</td><td>Count of jobs created by continuing establishments over the last 12 months.</td>
				</tr>

				<tr>
					<td>Job creation rate by establishment births</td><td>100 * (Jobs created by establishment births over the last 12 months / Denominator)</td> 
				</tr>

				<tr>
					<td>Total job creation rate</td><td>100 * (Job creation / Denominator)</td> 
				</tr>

				<tr>
					<td>Jobs destroyed</td><td>Count of all jobs destroyedover the last 12 months.</td> 
				</tr>

				<tr>
					<td>Jobs destroyed by establishment exit</td><td>Count of jobs destroyed by establishment exit over the last 12 months.</td>
				</tr>

				<tr>
					<td>Jobs destroyed at continuing establishments</td><td>Count of jobs destroyed at continuing establishments over the last 12 months.</td>
				</tr>

				<tr>
					<td>Job destruction rate by establishment exits</td><td>100 * (Jobs destroyed by establishment exit / Denominator) </td>
				</tr>

				<tr>
					<td>Total job destruction rate</td><td>100 * (Jobs destroyed / Denominator) </td>
				</tr>

				<tr>
					<td>Net job creation</td><td>Total job creation rate - Total job destruction rate</td>
				</tr>

				<tr>
					<td>Net job creation rate</td><td>Total job creation rate + Total job destruction rate - Net job creation rate</td>
				</tr>

				<tr>
					<td>Reallocation rate</td><td>Total job creation rate + Total job destruction rate - absolute (net_job_creation_rate)</td>
				</tr>

				<tr>
					<td>Firm deaths</td><td>Count of firms that have exited in their entirety during the period.</td>
				</tr>

				<tr>
					<td>Establishments associated with firm deaths</td><td>Count of establishments associated with firm deaths</td>
				</tr>

				<tr>
					<td>Employment associated with firm deaths </td><td>Count of employment associated with firm deaths.</td>
				</tr>
				

			</table>
		</p>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>