<?php
/******************************************
* @Modified on July 7, 2013
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

		<h2> Glossary of Selected Air Quality Terms January 2009 </h2><br/>

		<p>This Glossary provides a short explanation for a number of terms used on this DVD/CD that may not be widely used or understood.</p>
		<p>&nbsp;</p>
		<p>Topical Category: General</p>
		<p>&nbsp;</p>
		<p>Area Designation</p>
		<p>&nbsp;</p>
		<p>The designation of portions of the state as attaining or not attaining a specific state or national ambient air quality standard.</p>
		<p>&nbsp;</p>
		<p>Average of Quarterly Means</p>
		<p>Data are averaged for the four quarters of the year and then the quarterly averages are averaged to obtain a representative annual average.</p>
		<p>&nbsp;</p>
		<p>Basin Maximum</p>
		<p>The highest value within the basin for an individual parameter. For counts of exceedance days it represents the number of days on which any site in the basin exceeded the standard. For counts of complete sites it is the number of sites with complete data. For all other statistics (including calculated exceedance days), it represents the highest value at any site within the basin.</p>
		<p>&nbsp;</p>
		<p>California Maximum</p>
		<p>The highest value within California for an individual parameter. For counts of exceedance days it represents the number of days on which any site in the state exceeded the standard. For counts of complete sites it is the number of sites with complete data. For all other statistics (including calculated exceedance days), it represents the highest value at any site within the state.</p>
		<p>&nbsp;</p>
		<p>Complete Days</p>
		<p>The number of days during the year that monitoring satisfied state completeness criteria for area designation purposes for the specified pollutant.</p>
		<p>&nbsp;</p>
		<p>Complete Sites</p>
		<p>The number of sites whose data for the year satisfied state completeness requirements for area designation purposes for the specified pollutant.</p>
		<p>&nbsp;</p>
		<p>Day of Week</p>
		<p>The Day of the Week is from 1-7 (Sunday-Saturday).</p>
		<p>&nbsp;</p>
		<p>Designation Value</p>
		<p>&nbsp;</p>
		<p>A reference value for making state area designations. The highest concentration value over a three year period after excluding extreme concentration events. This variable is assigned to the 3rd year of a 3 year data period.</p>
		<p>&nbsp;</p>
		<p>EPDC (Expected Peak Day Concentration)</p>
		<p>A calculated value that represents the concentration expected to be exceeded at a particular site once per year, on average. The calculation procedure uses measured data collected at the site during a three-year period. Measured concentrations that are higher than the EPDC are excluded from the state area designation process.</p>
		<p>&nbsp;</p>
		<p>Extreme Concentration Event</p>
		<p>A high concentration occurrence above the expected peak day concentration (EPDC) that is not expected to recur more frequently than once per year. Extreme concentration events are excluded when determining an area's attainment status.</p>
		<p>&nbsp;</p>
		<p>High Period Coverage</p>
		<p>(Also High Coverage) This ranges from 0 to 100 and indicates the extent of monitoring during months when high pollutant concentrations are expected. High coverage of 75 indicates monitoring occurred 75% of the time during high concentration months.</p>
		<p>&nbsp;</p>
		<p>Local Conditions</p>
		<p>Measurements of air quality are reported at actual temperature and pressure for that particular location.</p>
		<p>&nbsp;</p>
		<p>Mean of Top 10</p>
		<p>The average of the ten highest daily measurements at a specific site during the year. This statistic is used with particulate data</p>
		<p>.</p>
		<p>Mean of Top 30</p>
		<p>The average of the 30 highest daily maximum concentrations at a specific site during the year. This statistic is used with gaseous pollutant data collected with continuous analyzers.</p>
		<p>&nbsp;</p>
		<p>Non-overlapping</p>
		<p>Data periods (e.g. 3, 8 or 24 hour) are not allowed to overlap one another.</p>
		<p>&nbsp;</p>
		<p>Observation Days</p>
		<p>Days on which one or more measurements are made for a specific pollutant at a specific monitoring site.</p>
		<p>&nbsp;</p>
		<p>Overlapping</p>
		<p>Data periods (e.g. 3, 8 or 24 hour) are allowed to overlap one another.</p>
		<p>&nbsp;</p>
		<p>Peak Indicator (EPDC)</p>
		<p>A term used in the Almanac for EPDC (Expected Peak Day Concentration). See the definition of EPDC.</p>
		<p>&nbsp;</p>
		<p>Representative Days: The hourly data collected must be representative according to the following definition. There must be no more than two missing hours in any of the three consecutive eight hour periods within a day. For an entire day, no more than two consecutive hours can be missed. Therefore, for an entire day, if there were three consecutive hours missed, the day would be invalidated.</p>
		<p>&nbsp;</p>
		<p>Standard Conditions</p>
		<p>Measurements of air quality are corrected to a reference temperature of 25 degrees Celsius and a reference pressure of 760 torr or 1 atm.</p>
		<p>&nbsp;</p>
		<p>Topical Category: Nitrogen Dioxide (NO2)</p>
		<p>&nbsp;</p>
		<p>NO2AAMS</p>
		<p>Annual Arithmetic Mean* (State Specification) (AAMS)</p>
		<p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; For representativeness, we need 1643+hours in a quarter and <strong>4 representative</strong> quarters in a year. If data are not valid, they will not be provided on the Air &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Quality Data DVD. The procedure follows:</p>
		<ol>
		<li>Truncate hourly values to the level of the standard (3 decimal places)</li>
		<li>Calculate the quarterly averages of all truncated hourly values.</li>
		<li>Don't round/truncate the quarterly averages</li>
		<li>Calculate an annual average from the four quarterly averages.</li>
		<li>Round the annual average to the level of the standard (3 decimal places).</li>
		<li>&nbsp;</li>
		</ol>
		<p>* For state, air basin, and county AAMS, we will use the highest site value. No values will be excluded due to exceptional events/EPDC</p>
		<p>&nbsp;</p>
		<p>Topical Category: Ozone</p>
		<p>&nbsp;</p>
		<p>3 Year 4th High</p>
		<p>When the daily maximum 1 hour average ozone values for three consecutive years are listed in descending order, this corresponds to the fourth highest value. It is a statistic used to compare to the former national 1 hour ozone standard.</p>
		<p>&nbsp;</p>
		<p>3 Year average 4th High</p>
		<p>When the fourth highest daily maximum 8 hour average ozone values for each of three years are averaged you get this statistic. The current state procedure yields a value close to that needed for comparison to the national 8 hour ozone standard.</p>
		<p>&nbsp;</p>
		<p>8-hour Ozone Planning Areas</p>
		<p>The 8-hour ozone planning areas include the 8-hour ozone federal non-attainment planning areas. There are many 8-hour ozone planning areas throughout California, but only some are federal non-attainment areas. Nevada and Mexico are also listed as 8-hour planning areas</p>
		<p>.</p>
		<p>Count of Days Exceeding the State 8-hour Overlapping Standard (The concentration of 0.070 ppm was approved by the Air Resources Board) This statistic is available on this DVD/CD.</p>
		<p>&nbsp;</p>
		<p>Coverages (Top 20% and Exceedance)</p>
		<p>Coverage in both instances is an assessment of how much data we have in the given year that was collected during the months of years that historically has had high values. It's expressed as a score from 0 to 100, with 100 meaning that all of the high time of the year has data and 0 meaning that none of the high time of year has data. Larger numbers mean that more of the high time of year has data.</p>
		<p>&nbsp;</p>
		<p>We express coverage in two ways -- Top 20% and Exceedance. The Top 20% assesses how much data is present during that portion of the year that historically contains high values without regard to the level of the standard. This kind of assessment is useful when we're interested in data characteristics that aren't related (directly, anyway) to a standard, like EPDC. Exceedance coverage differs in that it assesses how much data is present during the time of the year that the site (or monitor) has historically experienced exceedances. Obviously, this coverage only exists if the site (or monitor) has recently (within that previous 3-5 years) experienced at least one exceedance. This type of coverage is useful when one wants to assess the likelihood of the year including all exceedances.</p>
		<p>&nbsp;</p>
		<p>Daily Maximum 8-hour Average - Overlapping (National specification)</p>
		<p>In general, there must be 6 valid hours in each 8-hour period. Truncate each ozone value for each valid hour to the ppb level. If there are less than 6 valid hours in an 8-hour period, then the missing hours can be substituted with 1/2 ppb. After substituting 1/2 ppb for the missing values, if the 8-hour average is 0.085 ppm or greater, then the 8-hour value is still valid. Truncate any 8-hour average to the ppb level. The 8-hour periods are allowed to overlap. The maximum daily 8-hour average will display if there</p>
		<p>is at least one valid 8-hour average in the day.</p>
		<p>&nbsp;</p>
		<p>Daily Maximum 8-hour Average - Overlapping (State specification)</p>
		<p>Similar to the federal procedure, the State 8-hour ozone averages are based on overlapping 8-hour averages, using the first hour a s the start hour and assigning a date to the daily maximum 8-hour average. The first step in calculating a State</p>
		<p>8-hour average is to truncate the hourly measurements to 3 decimal places, when expressed in parts per million (ppm). Next, the average of the truncated hourly measurements that are within the 8-hour period (provided there are at least 6 hourly measurements in the period) is calculated. The final step is to round the averaged value to 3 decimal places (consistent with the precision of the State 8-hour standard). Running 8-hour averages are calculated for each hour of the day. The highest 8-hour average for each day is recorded in the database as the 8-hour daily maximum.</p>
		<p>&nbsp;</p>
		<p>EPDC (Expected Peak Day Concentration) 8-hour Average &ndash; Overlapping A calculated value that represents the concentration expected to be exceeded at</p>
		<p>a particular site once per year, on average. The calculation procedure uses measured data collected at the site during a three-year period. Measured concentrations that are higher than the EPDC are excluded from the state area designation process.</p>
		<p>&nbsp;</p>
		<p>Topical Category: Particulate Matter (PM)</p>
		<p>&nbsp;</p>
		<p>3 Year average 98th Percentile</p>
		<p>This is the three year average of the 98th percentile concentration. In principle, the 98th percentile represents the value below which 98% of the observations are found.</p>
		<p>&nbsp;</p>
		<p>AvgValidNatl</p>
		<p>This validity field is included in the files with the 24-hour averages of hourly PM10 and PM2.5. This field indicates whether or not the daily average includes sufficient hourly data to be considered valid in the national context (1 is valid, 0 is not valid).</p>
		<p>&nbsp;</p>
		<p>The 24-hour averages are considered valid if there are greater or equal to 18 valid hours measured. If there are less than 18 valid hours, then the missing hours are filled with zeros, and the 24-hour average is compared with the 24-hour national standard. If the 24-hour average with less than 18 valid hours is greater than the 24-hour national standard using this method, then the 24-hour average is considered valid.</p>
		<p>&nbsp;</p>
		<p>AvgValidSt</p>
		<p>This validity field is included in the files with the 24-hour averages of hourly PM10 and PM2.5. This field indicates whether or not the daily average includes sufficient hourly data (representative day) to be considered valid in the state context (1 is valid, 0 is not valid).</p>
		<p>&nbsp;</p>
		<p>The hourly data collected must be representative according to the following definition. There must be no more than two missing hours in any of the three consecutive eight hour periods within a day. For an entire day, no more than two consecutive hours can be missed. Therefore, for an entire day, if there were three consecutive hours missed, the day would be in validated.</p>
		<p>&nbsp;</p>
		<p>BAM</p>
		<p>Beta Attenuation Monitor used to collect hourly PM10 or hourly PM2.5 measurements. This may be an accepted method, if it is a California approved sampler, for measuring compliance with the state PM10 or PM2.5 air quality standards, respectively. The BAM-based hourly PM10 may be a nationally accepted method, if it is a federal reference or equivalent method, for measuring compliance with the national PM10 air quality standards.</p>
		<p>&nbsp;</p>
		<p>Calculated Days Exceeding Standard</p>
		<p>An estimate of the number of days exceeding a standard if there had been sampling every day. It could be significantly different than the "true" number of exceedance days when very few samples are collected during a year.</p>
		<p>&nbsp;</p>
		<p>Dichot</p>
		<p>Dichotomous sampler collecting 24 hour average fine fraction (PM 2.5) and course (PM10 - PM 2.5) samples. This is not an accepted method for measuring compliance with the state PM10 air quality standard or the national PM2.5 air quality standard.</p>
		<p>&nbsp;</p>
		<p>Estimated Days Exceeding Standard</p>
		<p>An estimate of the number of days exceeding a standard if there had been sampling every day. It could be significantly different than the "true" number of exceedance days when very few samples are collected during a year.</p>
		<p>&nbsp;</p>
		<p>Light Scatter (LTSC)</p>
		<p>Measurements of the back scattering (Bscat) of light made using a nephelometer. The units for "Bscat" are 1/km corrected for Rayleigh scattering.</p>
		<p>&nbsp;</p>
		<p>Monitor-Based PM</p>
		<p>New State regulations for PM look at monitors independently even if there are multiple monitors at a site. In contrast, the National PM regulations only use the primary monitor.</p>
		<p>&nbsp;</p>
		<p>PM 2.5</p>
		<p>Suspended Particulate Matter with an aerodynamic diameter of 2.5 microns or less. The U. S. EPA has established a Federal Reference Method (FRM) for measuring compliance with the national PM 2.5 standard. No data collected before December 1998 are FRM compliant. National statistics for PM2.5 are listed only for those monitors that use federal reference or equivalent methods, while state statistics are listed only for those monitors that are California Approved Samplers.</p>
		<p>&nbsp;</p>
		<p>PM2.5 Speciation</p>
		<p>U.S. EPA Air Quality System (AQS), downloaded on 4/15/2008. Only data from monitor types listed in AQS as "TRENDS SPECIATION" (PM2.5</p>
		<p>Speciation Trends Network) and "SLAMS SPECIATION" (SLAMS-State and local air monitoring stations) are included.</p>
		<p>&nbsp;</p>
		<p>PM10</p>
		<p>Suspended Particulate Matter with an aerodynamic diameter of 10 microns or less. Some PM10 data on this CD are expressed under both local and standard atmospheric conditions. In the data records for 1997, there are local data available for 5 sites in the South Central Coast Air Basin: El Capitan Beach (2008), Las Flores Canyon #1 (3101), Las Flores Can yon #2 (3102), Lompoc-S H Street (2360), and Vandenberg Air Force Base-STS Power (3023). Before 1998, most of the PM10 data were expressed under standard conditions. For 1998 and later, data for all sites, except for those only in the South Coast Air Basin, Palm Springs, and Indio, are expressed under both local and standard conditions. The local data record for South Coast Air Basin, Palm Springs, and</p>
		<p>Indio is included this year, but it starts with 2002. The state and national statistics listed on this CD, except for sites noted above, may differ from each other because 1998 and later state statistics are based on data expressed under local conditions. In addition, national statistics are listed only for those monitors that use federal reference or equivalent methods and state statistics are listed only for those monitors that are California Approved Samplers.</p>
		<p>&nbsp;</p>
		<p>PM10 Hourly Data and Daily Averages in \PM_Mass_Data\ on the DVD/CD. The</p>
		<p>BAM-based PM10 and TEOM-based PM10 hourly data and daily averages from the monitor-based data are available by using Windows Explorer to locate the files called PM_mass_textfiles.exe in the directory called \ PM_Mass_Data\ on the DVD/CD.</p>
		<p>&nbsp;</p>
		<p>PM10 Ion</p>
		<p>PM10 samples collected with high volume size selective inlet samplers and analyzed for sulfate, nitrate, chloride, ammonium, and/or potassium.</p>
		<p>&nbsp;</p>
		<p>PM10 Lead</p>
		<p>PM10 samples collected with high volume size selective inlet samplers and analyzed for lead.</p>
		<p>&nbsp;</p>
		<p>PMFINE</p>
		<p>The fine fraction of suspended particulate matter collected using a dichotomous sampler. The dichotomous sampler is not an approved PM2.5 Federal Reference Method (FRM) Sampler.</p>
		<p>&nbsp;</p>
		<p>Sampled Days Exceeding Standard</p>
		<p>The actual number of days on which sample values exceeded a standard.</p>
		<p>Because the number of samples collected in a year is much lower than the number of days in a year, this under estimates the "true" number of exceedance days during a year.</p>
		<p>&nbsp;</p>
		<p>Site-based PM10/PM2.5 Annual Statistics</p>
		<p>Site-based annual statistics for PM10 and PM2.5 are based upon daily and hourly monitor-based data. For national statistics, the primary nationally approved monitors are used to calculate statistics. For State statistics, the maximum value of the California approved monitors at each site is used, but the specific monitor is dependent on the statistic. Site-based PM10/PM2.5 Top 4 Values State site-based &ldquo;Top 4 Values&rdquo; for PM10 and PM2.5 are from one monitor from each site, respectively. National site-based &ldquo;Top 4 Values&rdquo; for PM10 and PM2.5 are from the primary monitor at each site, respectively.</p>
		<p>&nbsp;</p>
		<p>Soiling Index (COH)</p>
		<p>Measurements of (Coefficient of Haze) made using an AISI Tape Sampler. The actual units of COH are COH per 1000 feet of air.</p>
		<p>&nbsp;</p>
		<p>TEOM</p>
		<p>Tapered Element Oscillating Microbalance that colle</p>
		<p>cts PM10 measurements</p>
		<p>hourly. This is not an accepted method for measuri</p>
		<p>ng compliance with the state</p>
		<p>PM10 air quality standard, but may be used to measu</p>
		<p>re compliance with the</p>
		<p>national PM10 air quality standard.</p>
		<p>&nbsp;</p>
		<p>Total Carbon</p>
		<p>PM10 samples collected with high volume size select</p>
		<p>ive inlet Samplers and</p>
		<p>analyzed for total carbon.</p>
		<p>&nbsp;</p>
		<p>TSP</p>
		<p>Total Suspended Particulate Matter sampled from hig</p>
		<p>h volume samplers without</p>
		<p>a size selective inlet.</p>
		<p>&nbsp;</p>
		<p>TSP Lead</p>
		<p>Total suspended particulate samples that are analyz</p>
		<p>ed for lead.</p>
		<p>&nbsp;</p>
		<p>TSP NO3</p>
		<p>Total suspended particulate samples that are analyz</p>
		<p>ed for the nitrates fraction.</p>
		<p>&nbsp;</p>
		<p>TSP SO4</p>
		<p>Total suspended particulate samples that are analyz</p>
		<p>ed for the sulfates fraction.</p>
		<p>&nbsp;</p>
		<p>Topical Category: ARB Toxics</p>
		<p>&nbsp;</p>
		<p>Toxics</p>
		<p>Toxic air contaminants which include gaseous and particulate compounds sampled and analyzed using a variety of methods. These are typically 24-hour-average samples.</p>
		<p>&nbsp;</p>
		<p>Average (Calculated for the ARB Toxics)</p>
		<p>The &ldquo;Average&rdquo; is not the same as the &ldquo;Mean of Monthly Means&rdquo; or the &ldquo;Avg. of Monthly Averages&rdquo;, but the &ldquo;Average&rdquo; is the average of all values in a year. Even though these &ldquo;Averages&rdquo; are provided, it is recommended to use the &ldquo;Mean of Monthly Means&rdquo; or the &ldquo;Avg. of Monthly Avg&rdquo; for analyses.</p>
		<p>&nbsp;</p>
		<p>Avg. of Monthly Avg. (Also known as "Avg. of Months" or "Mean")</p>
		<p>Calculation of the Toxics Means: Means of monthly means are calculated by first determining the average of all measurements taken within a month at each site.</p>
		<p>Site means are then calculated by finding the average of the 12 monthly means for each site. Statewide means are calculated by first taking the average of all the monthly site means for each month within the state, and then calculating the average of those 12 monthly means. The toxics means are only displayed if there are 12 valid months (at least one valid measurement in each month) in a year.</p>
		<p>&nbsp;</p>
		<p>Topical Category: Other Pollutant Abbreviations</p>
		<p>&nbsp;</p>
		<p>CH4</p>
		<p>Methane monitored by continuous analyzer.</p>
		<p>&nbsp;</p>
		<p>CO</p>
		<p>Carbon Monoxide</p>
		<p>&nbsp;</p>
		<p>H2S</p>
		<p>Hydrogen Sulfide</p>
		<p>&nbsp;</p>
		<p>ICP-MS</p>
		<p>Inductively Coupled Plasma Mass Spectrometry</p>
		<p>&nbsp;</p>
		<p>NMHC</p>
		<p>Non-methane hydrocarbons (total) collected in continuous samplers.</p>
		<p>&nbsp;</p>
		<p>NMOC</p>
		<p>Non-methane organic compounds (speciated) collected in canisters. These are either 3-hour or 24-hour samples.</p>
		<p>&nbsp;</p>
		<p>SO2</p>
		<p>Sulfur Dioxide</p>
		<p>&nbsp;</p>
		<p>Sulfur</p>
		<p>Total sulfur analyzed by flame photometry.</p>
		<p>&nbsp;</p>
		<p>THC</p>
		<p>Total hydrocarbons monitored by continuous analyzer.</p>
		
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>