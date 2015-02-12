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

		<h2>Estimated Gasoline Price Breakdown:Description of Calculations</h2><br/>

			<table width=100%>
			<tr>
				<td>
					<p><span class="bblue">Gasoline Price Breakdown</span> -
					This page details the estimated gross margins for both refiners and distributors.
					The term "margin" includes both
					costs and profits.  The margin data is based on the statewide average
					retail and wholesale price of gasoline for a single day of the week.  It
					is not a seven-day average.  The margin provided here is an indicator for
					the California market as a whole and not for any particular refiner or
					retailer of gasoline.
					</p><br/>

					<p>
					The Energy Commission, the source of these data, cannot estimate
					profit margins based on average retail prices and observed wholesale
					market prices. This is because detailed data on refining and
					distribution costs, costs paid by approximately 10,000 retail
					locations, hundreds of wholesale marketers, jobbers, and distributors
					is not available.
					</p><br/>

					<p>
					The following provides specific information on how the data in the tables
					are calculated.</p><br/>

					<p>
					<span class="bblue">Refiner Margin</span> -
					(costs and profits) is calculated by
					subtracting the market price for crude oil from the wholesale price of
					gasoline.  The result is a gross refining margin which includes the cost
					of operating the refinery as well as the profits for the refining company.
					</p><br/>

					<p>
					The price of crude oil is based on the daily market price for crude oil
					from the Alaska North Slope published in the
					<a href=http://www.wsj.com>Wall Street Journal</a>.
					The market price of crude oil also includes its own share of costs and
					profits.  In the case of a vertically integrated oil company, the same
					company that owns and operates the oil field also owns and operates the
					refinery.  Several vertically integrated oil companies operate in
					California including BP, Chevron, ConocoPhillips, ExxonMobil, and Shell.
					</p><br/>

					<p>
					For simplicity, the refining margins shown are based on producing one
					barrel of gasoline from one barrel of crude oil.  No adjustments are made
					for other refined products.
					</p><br/>

					<p>
					<span class="bblue">Distribution Margin</span> -
					(distribution costs, marketing
					costs, and profits) is calculated by subtracting the wholesale gasoline
					price (either branded or unbranded) and taxes (state sales tax, state
					excise tax, federal excise tax, and a state underground storage tank fee)
					from the weekly average retail sales price.  The branded wholesale
					gasoline price is based on the average statewide branded refined "rack"
					price, information obtained from the
					Oil Price Information Service
					(<a href="http://www.opisnet.com" target="_blank">www.opisnet.com</a>).
					The rack price is the price paid at the point where
					tanker trucks load their fuel from a distribution terminal's loading rack.
					The unbranded price is also based on OPIS pricing information.
					</p><br/>

					<p>
					The distribution margin can be either positive or negative in value.  A
					negative distribution margin implies that some gasoline is being sold at a
					loss.  Similar to the refining margin, the distribution margin also
					includes the costs and profits of operating the retail gas station as well
					as various transportation and storage fees incurred once gasoline is moved
					from the bulk terminal to the retailer.  Most branded franchisees purchase
					gasoline at a delivered price called the Dealer Tank Wagon price that is
					typically higher than the branded rack price.  A retail-specific margin is
					not available at this time.
					</p><br/>

					<p>
					For more information, see
					<a href="http://energyalmanac.ca.gov/gasoline/margins/index.html" target="_blank">http://energyalmanac.ca.gov/gasoline/margins/index.html</a>.
					</p>
				</td>
			</tr>
		</table>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>