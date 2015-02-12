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
	<h2>Crime Definitions</h2>
	<br/>
	<p>
	See the table below for a summary of the UCR crime definitions. For detailed information on the Uniform Crime Reporting Program including a detailed glossary of crime definitions, see the
	<a href="http://www.fbi.gov/about-us/cjis/ucr/additional-ucr-publications/ucr_handbook.pdf" target="_blank">
	UCR Handbook</a> (15M).
	</p>
<br/>
	<table border="1" class="collapse">
	<tr>
	<td>Murder</td>
	<td>
	The willful (nonnegligent) killing of one human being by another.  As a
	general rule, any death caused by injuries received in a fight, argument,
	quarrel, assault, or commission of a crime is classified as Murder and
	Nonnegligent Manslaughter
	</td>
	</tr>

	<tr>
	<td>Manslaughter</td>
	<td>
	The killing of another person through gross negligence.  As a general
	rule, any death caused by the gross negligence of another is classified as
	Criminal Homicide?Manslaughter by Negligence
	</td>
	</tr>

	<tr>
	<td>Forcible Rape</td>
	<td>
	The carnal knowledge of a female forcibly and against her will.  Agencies
	must not classify statutory rape, incest, or other sex offenses, i.e.
	forcible sodomy, sexual assault with an object, forcible fondling, etc. as
	Forcible Rape.  By definition, sexual attacks on males are excluded from
	the rape category and must be classified as assaults or other sex offenses
	depending on the nature of the crime and the extent of injury.
	</td>
	</tr>

	<tr>
	<td>Attempted Rape</td>
	<td>
	Assaults or attempts to forcibly rape are classified as Attempts to Commit Forcible Rape.
	</td>
	</tr>

	<tr>
	<td>Total Rapes</td>
	<td>
	The number of forcible rapes plus the number of attempted rapes.
	</td>
	</tr>

	<tr>
	<td>Robbery</td>
	<td>
	The taking or attempting to take anything of value from the care, custody,
	or control of a person or persons by force or threat of force or violence
	and/or by putting the victim in fear.  Robbery involves a theft or larceny
	but is aggravated by the element of force or threat of force.
	</td>
	</tr>

	<tr>
	<td>Assault</td>
	<td>
	An unlawful attack by one person upon another.
	</td>
	</tr>

	<tr>
	<td>Aggravated Assault</td>
	<td>
	An unlawful attack by one person upon another for the purpose of
	inflicting severe or aggravated bodily injury.  This type of assault
	usually is accompanied by the use of a weapon or by means likely to
	produce death or great bodily harm.
	</td>
	</tr>

	<tr>
	<td>Simple Assault</td>
	<td>
	Includes all assaults which do not involve the use of a firearm, knife,
	cutting instrument, or other dangerous weapon and in which the victim did
	not sustain serious or aggravated injuries.  Agencies must classify as
	simple assault such offenses as assault and battery, injury caused by
	culpable negligence, intimidation, coercion, and all attempts to commit
	these offenses.  Under certain circumstances, offenses of disorderly
	conduct, domestic violence, or affray must be classified as simple
	assault.
	</td>
	</tr>

	<tr>
	<td>Burglary</td>
	<td>
	The unlawful entry of a structure to commit a larceny or felony, breaking
	and entering with intent to commit a larceny, housebreaking, safecracking,
	and all attempts at these offenses.
	</td>
	</tr>

	<tr>
	<td>Larceny</td>
	<td>
	The unlawful taking, carrying, leading, or riding away of property from
	the possession or constructive possession of another.  Larceny and theft
	mean the same thing in the UCR Program.  All thefts and attempted thefts
	are included in this category with one exception: motor vehicle theft.
	Because of the high volume of motor vehicle thefts, this crime has its own
	offense category.
	</td>
	</tr>

	<tr>
	<td>Motor Vehicle Theft</td>
	<td>
	Includes the theft or attempted theft of a motor vehicle, which the UCR
	Program defines as a self-propelled vehicle that runs on land surface and
	not on rails; for example, sport utility vehicles, automobiles, trucks,
	buses, motorcycles, motor scooters, all-terrain vehicles, and snowmobiles
	are classified as motor vehicles.  This category does not include farm
	equipment, bulldozers, airplanes, construction equipment, or water craft
	(motorboats, sailboats, houseboats, or jet skis).
	</td>
	</tr>
	</table>

	<br>
	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>