<?php
/******************************************
* @Modified on April 5, 2013
* @Package: RAND
* @Developer: Baljinder Singh
* @URL : http://www.ideafoundation.in
********************************************/

$basedir=dirname(__FILE__)."/../..";
include_once $basedir."/include/headerHtml.php";
?>

 <!-- container -->
<section id="container">

	<section class="conatiner-full" id="inner-content">
	<h2>RAND California Long Term Care Utilization Glossary </h2><br/>
		 <table cellpadding="6" border="1" class="data-table">
    <tbody><tr class="gray">
	<th width="20%">Care Category</th>
	<th width="40%">Definition</th>
	<th width="40%">Examples</th>
    </tr>
    <tr>
	<td width="20%" valign="top">LFS (Licensed File System) type and Classification type</td>
	<td width="40%"> Both terms refer to methods of classification by predomiant care-type provided in hospitals and long term care facilities.
        LFS type, the internal method, was built by the Office of Statewide Health Planning and Development (OSHPD) in cooperation with the California Department of Health Services. 
Classification type uses the same variables--only the titles differ.</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
	<td width="20%" valign="top">Fields referred to as "other"</td>
	<td width="40%">The reporting hospital or long term care facility would have to be contacted directly
         to determine the precise definition of "other" in each specific case.  
         Since OSHPD provides several choices for each question response, the "other" fields are generally left blank due to lack of submissions.</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
	<td width="20%" valign="top">Patient census</td>
	<td width="40%">On a selected day the total number of patients in the facility and the total number of patients in each long term care treatment classification.</td>
	<td width="40%">OSHPD has chosen December 31st of each year as the selected patient census day.  </td>
    </tr>
    <tr>
	<td width="20%" valign="top">Admissions</td>
	<td width="40%">Indicates the number of admissions during the calendar year into the various long term care bed classifications and the places where patients were admitted from.</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
	<td width="20%" valign="top">Discharges</td>
	<td width="40%">Indicates the number of discharges during the calendar year from the various long term care bed classifications and where those patients were discharged to.</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
	<td width="20%" valign="top">Patient days</td>
	<td width="40%">Number of patients in facility multiplied by the number of days spent in facility.  It reflects the total accumulation of days because Medi-cal pays on per diem basis.</td>
	<td width="40%">If 10 patients were in the facility for 365 days, the total patient days would be 3650.</td>
    </tr>
    <tr>
        <td>Licensed beds</td>
	<td width="40%">The number of beds that the Department of Health Services has licensed the facility to have in a given treatment category.
</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
        <td>Swing beds</td>
	<td width="40%">Beds that can be used in multiple treatment categories as licensed by the Department of Health Services.</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
	<td width="20%" valign="top">Licensed bed days</td>
	<td width="40%">The total capacity for the entire year within a given treatment category.  However, a given treatment category can be over the licensed bed days threshold for two reasons: patient accomodation due to emergency and swing beds.
This category is primarily for internal use by OSHPD and Department of Health Services.</td>
	<td width="40%">Skilled nursing has 10 licensed beds, therefore they would have 3650 total licensed bed days.
</td>
    </tr>
    <tr>
	<td width="20%" valign="top">LDR (Labor, Delivery and Recovery)</td>
	<td width="40%">LDR is a program for low-risk mothers with stays of less than 24 hours, including equipment and supplies or uncomplicated
deliveries in a home-like setting and that has been approved by the Division of Licensing and Certification, Department of
Health Services (L&amp;C). LDR replaces ABC (Alternative Birthing Center).</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
	<td width="20%" valign="top">LDRP (Labor, Delivery, Recovery and Post-Partum)</td>
	<td width="40%">LDRP is a program similar to LDR but is not limited to low-risk deliveries and the stays are usually for more than one
day. LDRP also is L&amp;C approved.</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
	<td width="20%" valign="top">Surgical Operations</td>
	<td width="40%">A surgical operation is one patient using a surgery room. This definition of a surgical operation could also be termed a "patient
scheduling."</td>
	<td width="40%">A surgery
involving multiple procedures (even multiple, unrelated surgeries) performed during one scheduling is to be
counted as one surgical operation.</td>
    </tr>
    <tr>
	<td width="20%" valign="top">Operating Room Minutes</td>
	<td width="40%">The difference, in minutes, between the beginning of administration of general anesthesia,
and the end of administration of general anesthesia. If general anesthesia is not administered, Operating Room Minutes
are the number of minutes between the beginning and ending of surgery.  The only exception: if the general anesthesia continues after the patient leaves the operating room, then ending time
occurs when the patient leaves the operating room.</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
	<td width="20%" valign="top">Treatment Visits (pertaining to Radiation Therapy Service, Megavoltage Machines only)</td>
	<td width="40%">Treatment visits means a patient visit during which radiation therapy was performed.</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
	<td width="20%" valign="top">Treatment station (within Emergency Medical Services)</td>
	<td width="40%">A specific place within the emergency department adequate to treat one patient at a time.
(Does not include holding or observation beds).</td>
	<td width="40%">NA</td>
    </tr>
    <tr>
	<td width="20%" valign="top">Care types</td>
	<td width="40%">Care type intensity determined by staff administering procedure.</td>
	<td width="40%">Care types by order of decreasing intensity: ICU (Intensive Care Unit), Subacute, Medisurgacute.  </td>
    </tr>
    <tr>
	<td width="20%" valign="top">Short Doyle</td>
	<td width="40%">A California state program that funds certain types of psychiatric care.</td>
	<td width="40%">NA</td>
    </tr>
    </tbody></table>
	

	</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>