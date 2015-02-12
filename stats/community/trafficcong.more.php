<?php
/******************************************
* @Modified on March 21, 2012
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
	<h2>Traffic Congestion Glossary</h2><br/>

<p class="bb">Urban Area definitions</p><br/>


<table border="1" class="collapse">
<tr class="thead">
<th valign="top" class="bb" nowrap>Urban Area</th>
<th valign="top" class="bb">Definition</th>
</tr>

<tr>
<td valign="top" nowrap>Very large</td>
<td valign="top">
Over 3 million population:
<div id="verylargearea" class="s10x ind2 area">
Atlanta GA, Boston MA-NH-RI, Chicago IL-IN, Dallas-Fort Worth-Arlington TX,
Detroit MI, Houston TX, Los Angeles-Long Beach-Santa Ana CA, Miami
FL, New York-Newark NY-NJ-CT, Philadelphia PA-NJ-DE-MD, Phoenix AZ, 
San Francisco-Oakland CA, San Diego, CA, 
Seattle WA, Washington DC-VA-MD. 
</div>
</td>
</tr>


<tr>
<td valign="top" nowrap>Large</td>
<td valign="top">
Over 1 million and less than 3 million population:
<div id="largearea" class="s10x ind2 area">
Austin, TX, Baltimore, MD, Buffalo, NY, Charlotte, NC-SC, Cincinnati, OH-KY-IN, Cleveland, OH, Columbus, OH,
Denver-Aurora, CO, Indianapolis, IN, Jacksonville, FL, Kansas City, MO-KS, Las Vegas, NV, Louisville,
KY-IN, Memphi,s TN-MS-AR, Milwaukee, WI, Minneapolis-St. Paul, MN, Nashville-Davidson, TN,
New Orleans, LA,
Orlando, FL,
Pittsburgh, PA,
Portland, OR-WA,
Providence, RI-MA, Raleigh-Durham, NC, Riverside-San Bernardino, CA, Sacramento, CA,
San Antonio, TX,
Salt Lake, UT,
San Jose, CA,
San Juan, PR,
St. Louis, MO-IL,
Tampa-St. Petersburg, FL, Virginia Beach, VA.
</div>

</td>
</tr>


<tr>
<td valign="top" nowrap>Medium</td>
<td valign="top">
Over 500,000 and less than 1 million population:
<div id="mediumarea" class="s10x ind2 area">
Akron, OH,
Albany-Schenectady, NY, Albuquerque, NM, Allentown-Bethlehem, PA-NJ, Bakersfield, CA,
Baton Rouge, LA,
Birmingham, AL,
Bridgeport-Stamford, CT-NY, Charleston-North Charleston, SC, Colorado Springs, CO,
Dayton, OH,
El Paso, TX-NM,
Fresno, CA,
Grand Rapids, MI,
Hartford, CT,
Honolulu, HI,
Indio-Cathedral City-Palm Springs, CA, Knoxville, TN,
Lancaster-Palmdale, CA,
McAllen, TX,
New Haven, CT,
Oklahoma City, OK,
Omaha, NE-IA,
Oxnard-Ventura, CA, Poughkeepsie-Newburgh, NY, Richmond, VA,
Rochester, NY,
Sarasota-Bradenton, FL,
Springfield, MA-CT,
Toledo, OH-MI, Tucson, AZ, Tulsa, OK, Wichita, KS.
</div>
</td>
</tr>

<tr>
<td valign="top" nowrap>Small</td>
<td valign="top">
Less than 500,000 population:
<div id="smallarea" class="s10x ind2 area">
Anchorage, AK, Beaumont, TX, Boise, ID,
Boulder, CO, Brownsville, TX, Cape Coral, FL, Columbia, SC, Corpus Christi, TX, Eugene, OR, 
Greensboro, NC, Jackson, MS, Laredo, TX,
Little Rock, AR, Madison, WI, Pensacola, FL-AL, Provo, UT,
Salem, OR,
Spokane, WA, Stockton, CA ,Winston-Salem, NC, Worcester, MA.
</div>
</td>
</tr>
</table>
<br/>
<p class="bb">Category definitions:
</p>


<table border="1" class="collapse">
<tr class="thead">
<th valign="top" class="bb">Category</th>
<th valign="top" class="bb">Definition</th>
</tr>

<tr>
<td valign="top" colspan=2><b>Population</b></td>
</tr>

<tr>
<td valign="top">Population</td>
<td valign="top">Combination of U.S. Census Bureau estimates and the Federal Highway Administration’s Highway 
Performance Monitoring System.</td>
</tr>

<tr>
<td valign="top">Population rank</td>
<td valign="top">Out of 101 urban areas.</td>
</tr>

<tr>
<td valign="top">Population group</td>
<td valign="top">Size of urban area. Small, Medium, Large, Very large.</td>
</tr>
<tr>
<td valign="top">Peak period travelers</td>
<td valign="top">Derived from the National Household Travel Survey (NHTS) data on the time of day when trips begin. Any resident who begins a trip, by any mode, between 6 a.m. and 10 a.m. or 3 p.m. and 7 p.m. is counted as a peak-period traveler.</td>
</tr>

<tr>
<td valign="top" colspan=2><b>Freeway</b></td>
</tr>

<tr>
<td valign="top">Freeway daily vehicle-miles of travel
</td>
<td valign="top">The average daily traffic of a section
of roadway multiplied by the length of that section of
roadway.</td>
</tr>

<tr>
<td valign="top">Freeway lane miles</td>
<td valign="top">Total measure of all lanes.</td>
</tr>

<tr>
<td valign="top">Arterial street daily
vehicle-miles of travel</td>
<td valign="top">The average daily traffic of a section
of roadway multiplied by the length of that section of
roadway.</td>
</tr>

<tr>
<td valign="top">Arterial street lane-miles</td>
<td valign="top">Total measure of all lanes.</td>
</tr>

<tr>
<td valign="top" colspan=3><b>Cost Components, Delay, and Wasted Fuel</b></td>
</tr>

<tr>
<td valign="top">Cost components value of time</td>
<td>
The value in each year of person time used in the
report; this is based on the value of time, rather than the average or
prevailing wage rate.
</td>
</tr>

<tr>
<td>Commercial value of time</td>
<td>
Value of travel time delay and excess fuel consumption. 
</td>
</tr>

<tr>
<td>Fuel Costs</td>
<td>Statewide average fuel cost estimates.</td>
</tr>

<tr>
<td valign="top">Total annual delay</td>
<td valign="top">The value of lost time in passenger
vehicles and the increased operating costs of commercial
vehicles in congestion.</td>
</tr>


<tr>
<td valign="top">Travel time index</td>
<td valign="top">Ratio of the travel time during the peak period to the time required to make the same trip at free-flow speeds. </td>
</tr>

<tr>
<td>Public Transportation</td>
<td>Regular route service from all public transportation providers in an urban area.</td>
</tr>

<tr>
<td>Annual excess fuel consumed total</td>
<td>Increased fuel consumption due to travel in congested conditions rather than free-flow conditions.</td>
</tr>

<tr>
<td>Congestion cost</td>
<td>
Costs of the value of travel time delay are estimated
annually; excess fuel consumption is estimated using state average cost
per gallon.  Values include the effects of operational treatments.
</td>
</tr>

<tr>
<td valign="top">Transit or Carpool Riders needed to
maintain congestion level</td>
<td valign="top">The number of transit trips needed to
maintain the same congestion level.</td>
</tr>


</table>

	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>