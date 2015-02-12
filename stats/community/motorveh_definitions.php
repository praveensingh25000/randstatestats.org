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
	<h2>Motor Vehicle/DUI definitions</h2><br/>

		<table border=1 class="collapse">
			<tr>
				<td>Driving Under the Influence (DUI)</td>
				<td>Driver BAC Result > 0.00 or Contributing Factor of "Had Been Drinking" or "Under the Influence of Alcohol". This only includes alcohol involvement, not drugs.</td>
			</tr>

			<tr>
				<td>Fatal Crash</td>
				<td>Any injury crash that results in one or more fatal injuries.</td>
			</tr>

			<tr>
				<td>Fatal Injury (Fatality)</td>
				<td>Any injury sustained in a motor vehicle traffic crash that results in death within thirty days of the motor vehicle traffic crash.</td>
			</tr>

			<tr>
				<td>Incapacitating Injury</td>
				<td>Any injury, other than a fatal injury, which prevents the injured person from walking, driving or normally continuing the activities he was capable of performing before the injury occurred.</td>
			</tr>

			<tr>
				<td>Motor Vehicle</td>
				<td>Every vehicle that is self-propelled and every vehicle which is propelled by electric power obtained by overhead trolley wires, but not operated upon rails.</td>
			</tr>

			<tr>
				<td>Motor Vehicle Crash</td>
				<td>A crash involving a motor vehicle in transport, but not involving aircraft or watercraft.</td>
			</tr>

			<tr>
				<td>No Data</td>
				<td>The reporting peace officer did not report the information (blank).</td>
			</tr>

			<tr>
				<td>Non-Incapacitating Injury</td>
				<td>Any injury, other than a fatal or an incapacitating injury, which is evident to observers at the scene of the crash in which the injury occurred.</td>
			</tr>

			<tr>
				<td>Non-Injury Crash</td>
				<td>Any motor vehicle crash other than an injury crash. A non-injury crash is also called a property damage only crash.</td>
			</tr>

			<tr>
				<td>Other Injury</td>
				<td>Any injury classified as a possible injury severity.</td>
			</tr>

			<tr>
				<td>Other Injury Crash</td>
				<td>A crash in which the most severe injury sustained was a possible injury.</td>
			</tr>

			<tr>
				<td>Serious Injury</td>
				<td>An incapacitating or non-incapacitating injury.</td>
			</tr>

			<tr>
				<td>Serious Injury Crash</td>
				<td>A crash in which the most severe injury sustained was an incapacitating injury or a non-incapacitating injury.</td>
			</tr>

			<tr>
				<td>Unknown</td>
				<td>The reporting peace officer indicated that the information was unknown at the scene of the crash.</td>
			</tr>

			<tr>
				<td>Unknown Severity Crash</td>
				<td>Any motor vehicle crash in which the reporting peace officer did not provide injury severity information for any of the people involved in the crash.</td>
			</tr>
		</table>
<br/>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>