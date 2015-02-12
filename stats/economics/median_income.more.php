<?php
/******************************************
* @Modified on March 20, 2012
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
	<h2>Median Income Family Size Adjustments</h2>
	
	<p>&nbsp;</p>
	<p>By statute, family size adjustments are required to provide higher income limits for larger families and lower income limits for smaller families.</p><br/>
	<p>The factors used are as follows:</p>
		<div class=\"ind2em\">
			<table  border=\"1\" class="data-table">
				<tbody>
					<tr>
						<th class=\"pad4 thead_gr\" colspan="8">Number of Persons in Family and Percentage Adjustments</th>
					</tr>
					<tr>
						<td align=\"center\">1</td>
						<td align=\"center\">2</td>
						<td align=\"center\">3</td>
						<td align=\"center\">4</td>
						<td align=\"center\">5</td>
						<td align=\"center\">6</td>
						<td align=\"center\">7</td>
						<td align=\"center\">8</td>
					</tr>
					<tr>
						<td align=\"center\">70%</td>
						<td align=\"center\">80%</td>
						<td align=\"center\">90%</td>
						<td align=\"center\">Base</td>
						<td align=\"center\">108%</td>
						<td align=\"center\">116%</td>
						<td align=\"center\">124%</td>
						<td align=\"center\">132%</td>
					</tr>
				</tbody>
			</table>
		</div>
		<br/>
	<p>Income limits for families with more than eight persons are not included in the printed lists because of space limitations. For each person in excess of eight, the four-person income limit should be multiplied by an additional 8 percent. (For example, the nine-person income limit equals 140 percent [132 + 8] of the relevant four-person income limit.)</p>
	<p>Local agencies may round income limits for nine or more persons to the nearest $50, or may use the un-rounded numbers.</p>
	</section>

</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>