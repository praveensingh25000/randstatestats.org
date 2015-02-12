<?php
/******************************************
* @Modified on April 4, 2013
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

			<h2>Gasoline Price Definitions</h2><br/>

		<p>
		<table width=100% border=1 class="collapse">
			<tr>
				<td>Wholesale Gasoline Price:</td>
				<td>The average wholesale gasoline price is the average of 13 unbranded and 13 branded wholesale prices at various wholesale fuel loading racks around the state. This average price is for a single day.
				The wholesale gasoline price is calculated for the same day as EIA's weekly average gasoline price.</td>
			</tr>
			<tr>
				<td>Branded and Unbranded Gasoline</td>
				<td>Branded gasoline refers to fuel that is sold under a brand name (such as BP, Shell, Exxon, Chevron, and Valero). Branded gasoline will include proprietary fuel additives. Unbranded gasoline is not associated with a specific brand name, and is typically sold by single-station retail outlets, relatively small chain retailers that specialize is gasoline sales, and large supermarket chain stores (such as Costco and Safeway).</td>
			</tr>
			<tr>
				<td>Distribution Costs, Marketing Costs, and Profits</td>
				<td>The costs associated with the distribution from terminals to stations and retailing of gasoline, including but not limited to: franchise fees, and/or rents, wages, utilities, supplies, equipment maintenance, environmental fees, licenses, permitting fees, credit card fees, insurance, depreciation, advertising, and profit.</td>
			</tr>
			<tr>
				<td>Crude Oil Cost</td>
				<td>The daily market price of Alaska North Slope crude oil which is used as a proxy for this composite crude oil acquisition cost for California refineries.</td>
			</tr>
			<tr>
				<td>Refinery Costs and Profits</td>
				<td>The costs associated with refining and terminal operations, crude oil processing, oxygenate additives, product shipment and storage, oil spill fees, depreciation, purchases of gasoline to cover refinery shortages, brand advertising, and profits.</td>
			</tr>
			<tr>
				<td>State Underground Storage Tank Fee</td>
				<td>The state underground storage tank fee is currently 1.4 cents per gallon.</td>
			</tr>
			<tr>
				<td>State and Local Sales Tax</td>
				<td>An average state sales tax rate of 8 percent is used in the calculation of the distribution margin although the actual sales tax rate does vary throughout California.</td>
			</tr>
			<tr>
				<td>State Excise Tax</td>
				<td>The California state excise tax is currently 18 cents per gallon.</td>
			</tr>
			<tr>
				<td>Retail Gasoline Price</td>
				<td>The weekly average price for California from the Energy Information Administration (EIA).</td>
			</tr>
		</table>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>