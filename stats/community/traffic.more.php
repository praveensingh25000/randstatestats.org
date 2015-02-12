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

	<h2>Traffic Statistics Background</h2><br/>

<p>The categories in Traffic Statistics are defined as follows.</p><br/>

<table border="1" class="collapse">
    <tr class="gray">
        <td valign="top"><strong>Category</strong></td>
        <td valign="top"><strong>Definition</strong></td>
    </tr>
    <tr>
        <td valign="top">Length of segment (miles)</td>
        <td valign="top">Length of highway/freeway segment.</td>
    </tr>
    <tr>
        <td valign="top">Average highway speed</td>
	<td valign="top">Weighted average of the design speed within a highway section. (Design speed is a speed selected to establish specific minimum geometric design elements for a particular section of highway.) On non-engineered roads the average highway speed has been estimated.</td>
    </tr>
    <tr>
        <td valign="top">Accident rate times statewide average</td>
		<td valign="top">Accident rate comparison or times the statewide average. The ratio of a segment accident rate to the statewide average accident rate for this type of facility in comparable terrain. Higher numbers indicate more dangerous routes.</td>
    </tr>
    <tr>
        <td valign="top">Accidents per mile</td>
		<td valign="top">Accidents per year divided by the segment length in miles. </td>
    </tr>
    <tr>
        <td valign="top">Fatal accidents per mile</td>
        <td valign="top">Fatal accidents per mile per year for last three calendar year average.</td>
    </tr>
    <tr>
        <td valign="top">Fatal accidents and injuries per mile</td>
        <td valign="top">Fatal accidents and injuries per mile per year for last three calendar year average.</td>
    </tr>
    <tr>
        <td valign="top">Length of segment urban area - miles</td>
        <td valign="top">Length of highway/freeway segment in urban area.</td>
    </tr>
    <tr>
        <td valign="top">Present design hour traffic volume</td>
        <td valign="top">Present design hour traffic volume. Higher numbers indicate higher design capacity.</td>
    </tr>
    <tr>
        <td valign="top">Operating Speed</td>
	<td valign="top">A computed value based on the Volume to Capacity ratio (defined below) and the average highway speed. It represents the present operating speed during the present design hour volume of traffic on existing highway geometrics. For segments of highway controlled by traffic signals, an &#147;S&#148; replaces the operating speed and generally represents speeds of 15 to 30 MPH.</td>
    </tr>
    <tr>
        <td valign="top">Ratio of volume to capacity</td>
	<td valign="top">A ratio of traffic
        that the highway can carry to traffic that wants to use
        the highway. A value of 100 means the highway can provide
        desirable traffic service. A value of 200 means that
        traffic can double before service drops below desirable
        levels. A value of 50 means the highway is carrying twice
        the design amount of traffic volume but at a lower level
	of service than desired.Also called capacity
        adequacy. </td>
    </tr>
    <tr>
        <td valign="top">Statewide accident rate for comparable
        road type</td>
	<td valign="top">Statwide average
        accident rate for the same type of facility in comparable
        terrain. The Statewide Accident Rate generally conforms
        to the Basic Expected Accident Rate for the Rate Groups
        as annually published by the Caltrans Traffic Operations
        Program in &#147;Accident Data on California State
	Highways.&#148;</td>
    </tr>
    <tr>
        <td valign="top">Accident rate per 1,000,000 vehicle
        miles</td>
	<td valign="top">Accidents per
        million vehicle-miles averaged for the last three
	calendar years.</td>
    </tr>
    <tr>
        <td valign="top">Fatality rate per 100,000,000 vehicle
        miles</td>
	<td valign="top">Fatal accidents
	per 100 million vehicle miles averaged for the last
        three calendar years. </td>
    </tr>
    <tr>
        <td valign="top">Accident cost per mile per year
        (thousands of dollars)</td>
	<td valign="top">Accident cost per
        mile per year (in 1,000's of dollars) considers direct
        out-of-pocket costs such as property damage, medical
        treatment, and future lost earnings, but does not attempt
	to value the loss of life or limb.</td>
    </tr>
    <tr>
        <td valign="top">Grade</td>
        <td valign="top">Grade line. Flat = 0-3% grade; rolling =
        3-6%; moderate = 6% grade or more for less than one-half
        of the segment; steep = 6% grade or more for more than
        one-half of the segment</td>
    </tr>
    <tr>
        <td valign="top">Terrain</td>
        <td valign="top">Adjacent topology as it affects
        construction costs (flat; rolling; mountainous). <font
        color="#000000">Flat reflects minor grading; rolling
        reflects moderate grading; mountainous reflects heavy
	grading as economic considerations.</td>
    </tr>
    <tr>
        <td valign="top">Lanes</td>
        <td valign="top">Number of lanes, both directions.</td>
    </tr>
    <tr>
        <td valign="top">Highway type</td>
        <td valign="top">Facility types are conventional,
        expressway, freeway, no existing State highway,
        undivided, divided</td>
    </tr>
</table>

	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>