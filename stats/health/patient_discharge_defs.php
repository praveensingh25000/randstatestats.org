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
	<h2>Patient Discharge Definitions </h2><br/>

		<p><table width="100%" cellpadding="6" border="1" class="data-table">
<tbody><tr>
<th align="top" colspan="2" class="thead"><b>Type of Care:</b></th>
</tr>
<tr>
<td align="top" class="ind2em">Acute</td>
<td>Services provided to inpatient (on the basis of physicians' orders and approved nursing care plans) who are in an acute phase of illness but not to the degree which requires the concentrated and continuous observation and care provided in the intensive care centers.</td>
</tr>
<tr>
<td align="top" class="ind2em">Skilled Nursing/Intermidiate Care (SNIC)</td>
<td>Nursing and personal care services provided over an extended period to persons who require convalescence, custodial care, and/or who are chronically ill, aged, or disabled. These type of care beds may be found as distinct parts in GACHs and in APHs.</td>
</tr>
<tr>
<td align="top" class="ind2em">Psychiatric</td>
<td>Care rendered in an acute psychiatric hospital, in a PHF, or in an acute psychiatric bed in a GACH. A classification of hospital licensure and hospital beds, as defined by Sections 1250, 1250.1, and 1250.2 of the California Health and Safety Code.</td>
</tr>
<tr>
<th align="top" colspan="2" class="thead"><b>Type of Hospital:</b></th>
</tr>
<tr>
<td align="top" class="ind2em">General Acute Care Hospital (GACH)</td>
<td>A classification of hospital licensure, as defined by Subdivision (a) of Section 1250 of the California Health and Safety Code.</td>
</tr>
<tr>
<td align="top" class="ind2em">Acute Psychiatric Hospital (APH)</td>
<td>A classification of hospital licensure, as defined by Subdivision (b) of Section 1250 of the California Health and Safety Code.</td>
</tr>
<tr>
<td align="top" class="ind2em">Chemical Dependency Recovery Hospital</td>
<td>A health facility which provides 24-hr inpatient care for persons who have a dependency on alcohol or drugs. Care includes patient counseling, group and family therapy, physical conditioning, outpatient services, and dietetic services. The facility shall have a medical director who is a physician and surgeon licensed in California. See Subdivision (d) of Section 1250.3 of the California Health and Safety Code.</td>
</tr>
<tr>
<th align="top" colspan="2" class="thead"><b>Admission Type:</b></th>
</tr>
<tr>
<td align="top" class="ind2em">Scheduled</td>
<td>Admission was arranged with the hospital at least 24 hours prior to the admission.</td>
</tr>
<tr>
<td align="top" class="ind2em">Unscheduled</td>
<td>Admission was not arranged with the hospital at least 24 hours prior to the admission.</td>
</tr>
<tr>
<td align="top" class="ind2em">Unknown</td>
<td>Nature of admission not known. Does not include stillbirths.</td>
</tr>
<tr>
<td align="top" colspan="2" class="thead"><b>Source of Admission:</b></td>
</tr>
<tr>
<td align="top" class="ind2em">Home</td>
<td>A patient admitted from the patient's home, the home of a relative or friend, or a vacation site, whether or not the patient was seen at a physician's office, or a clinic not licensed or certified as an ambulatory surgery facility, or had been receiving home health services or hospice care at home.</td>
</tr>
<tr>
<td align="top" class="ind2em">Residential Care Facility </td>
<td>A patient admitted from a facility in which the patient resides and that provides special assistance to its residents in activities of daily living, but that provides no organized healthcare.</td>
</tr>
<tr>
<td align="top" class="ind2em">Ambulatory Surgery</td>
<td>A patient admitted after treatment or examination in an ambulatory surgery facility, whether hospital-based or a freestanding licensed ambulatory surgery clinic or certified ambulatory surgery center. Excludes physicians' offices and clinics not licensed or certified as an ambulatory surgery facility.</td>
</tr>
<tr>
<td align="top" class="ind2em">Skilled Nursing/Intermediate Care</td>
<td>A patient admitted from skilled nursing care or intermediate care, whether freestanding or hospital-based, or from a Congregate Living Health Facility.</td>
</tr>
<tr>
<td align="top" class="ind2em">Acute Hospital Care</td>
<td>A patient who was an inpatient at a hospital, and who was receiving inpatient hospital care of a medical/surgical nature, such as in a perinatal, pediatric, intensive care, coronary care, respiratory care, newborn intensive care, or burn unit of a hospital.</td>
</tr>
<tr>
<td align="top" class="ind2em">Other Hospital Care </td>
<td>A patient who was an inpatient at a hospital, and who was receiving inpatient hospital care not of a medical/surgical nature, such as in a psychiatric, physical medicine rehabilitation, or chemical dependency recovery treatment unit.</td>
</tr>
<tr>
<td align="top" class="ind2em">Newborn</td>
<td>A baby born alive in this hospital.</td>
</tr>
<tr>
<td align="top" class="ind2em">Prison/Jail</td>
<td>A patient admitted from a correctional institution.</td>
</tr>
<tr>
<td align="top" class="ind2em">Other  </td>
<td>A patient admitted from a source other than mentioned above. Includes patients admitted from an inpatient hospice facility.</td>
</tr>
<tr>
<th align="top" colspan="2" class="thead"><b>Route of Admission:</b></th>
</tr>
<tr>
<td align="top" class="ind2em">Your Emergency Room</td>
<td>Any patient admitted as an inpatient after being treated or examined in this hospital's emergency room. Excludes patients seen in the emergency room of another hospital.</td>
</tr>
<tr>
<td align="top" class="ind2em">Not Your Emergency Room</td>
<td>Any patient admitted as an inpatient without being treated or examined in this hospital's emergency room. Includes patients seen in the emergency room of some other hospital and patients not seen in any emergency room.</td>
</tr>
<tr>
<th align="top" colspan="2" class="thead"><b>Disposition of Patient:</b></th>
</tr>
<tr>
<td align="top" class="ind2em">Routine Discharge</td>
<td>A patient discharged from this hospital to return home or to another private residence. Patients scheduled for follow-up care at a physician's office or a clinic not licensed or certified as an ambulatory surgery facility shall be included. Excludes patients referred to a home health service.</td>
</tr>
<tr>
<td align="top" class="ind2em">Acute Care Within This Hospital</td>
<td>A patient discharged to inpatient hospital care that is of a medical/surgical nature, such as to a perinatal, pediatric, intensive care, coronary care, respiratory care, newborn intensive care, or burn unit within this reporting hospital.</td>
</tr>
<tr>
<td align="top" class="ind2em">Other Type of Hospital Care Within This Hospital</td>
<td>A patient discharged to inpatient hospital care not of a medical/surgical nature and not skilled nursing/intermediate care, such as to a psychiatric, physical medicine rehabilitation, or chemical dependency recovery treatment unit within this reporting hospital.</td>
</tr>
<tr>
<td align="top" class="ind2em">Skilled Nursing/Intermediate Care Within This Hospital</td>
<td>A patient discharged to a Skilled Nursing/Intermediate Care Distinct Part within this reporting hospital.</td>
</tr>
<tr>
<td align="top" class="ind2em">Acute Care at Another Hospital </td>
<td>A patient discharged to another hospital to receive inpatient care that is of a medical/surgical nature, such as to a perinatal, pediatric, intensive care, coronary care, respiratory care, newborn intensive care, or burn unit of another hospital.</td>
</tr>
<tr>
<td align="top" class="ind2em">Other Type of Hospital Care at Another Hospital</td>
<td>A patient discharged to another hospital to receive inpatient hospital care such as to a psychiatric, physical medicine rehabilitation, or chemical dependency recovery treatment at another hospital, not of a medical/surgical nature and not skilled nursing/intermediate care.</td>
</tr>
<tr>
<td align="top" class="ind2em">Skilled Nursing/Intermediate Care Elsewhere</td>
<td>A patient discharged from this hospital to a Skilled Nursing/Intermediate Care type of care, either freestanding or a distinct part within another hospital, or to a Congregate Living Health Facility.</td>
</tr>
<tr>
<td align="top" class="ind2em">Residential Care Facility</td>
<td>A patient discharged to a facility that provides special assistance to its residents in activities of daily living, but that provides no organized healthcare.</td>
</tr>
<tr>
<td align="top" class="ind2em">Prison/Jail </td>
<td>A patient discharged to a correctional institution.</td>
</tr>
<tr>
<td align="top" class="ind2em">Against Medical Advice</td>
<td>Patient left the hospital against medical advice, without a physician's discharge order. Psychiatric patients discharged from away without leave status (AWOL) are also included in this category.</td>
</tr>
<tr>
<td align="top" class="ind2em">Died</td>
<td>All episodes of inpatient care that terminated in death. Patient expired after admission and before leaving the hospital.</td>
</tr>
<tr>
<td align="top" class="ind2em">Home Health Service</td>
<td>A patient referred to a licensed home health service program.</td>
</tr>
<tr>
<td align="top" class="ind2em">Other</td>
<td>A disposition other than mentioned above. Includes patients discharged to an inpatient hospice facility.</td>
</tr>
<tr>
<th align="top" colspan="2" class="thead"><b>Do Not Resuscitate (DNR):</b></th>
</tr>
<tr>
<td align="top" class="ind2em">Yes</td>
<td>A DNR order was written at the time of or within the first 24 hours of the patient's admission to the hospital.</td>
</tr>
<tr>
<td align="top" class="ind2em">No</td>
<td>A DNR order was not written at the time of or within the first 24 hours of the patient's admission to the hospital.</td>
</tr>
<tr>
<th align="top" colspan="2" class="thead"><b>Expected Payer Source:</b></th>
</tr>
<tr>
<td align="top" class="ind2em">Medicare </td>
<td>A federally administered third party reimbursement program authorized by Title XVIII of the Social Security Act. Includes crossovers to secondary payers.</td>
</tr>
<tr>
<td align="top" class="ind2em">Medi-Cal</td>
<td>A state administered third party reimbursement program authorized by Title XIX of the Social Security Act.</td>
</tr>
<tr>
<td align="top" class="ind2em">Private Coverage</td>
<td>Payment covered by private, non-profit, or commercial health plans, whether insurance or other coverage, or organizations. Included are payments by local or organized charities, such as the Cerebral Palsy Foundation, Easter Seals, March of Dimes, or Shriners.</td>
</tr>
<tr>
<td align="top" class="ind2em">Workers' Compensation</td>
<td>Payment from workers' compensation insurance, government or privately sponsored.</td>
</tr>
<tr>
<td align="top" class="ind2em">County Indigent Programs</td>
<td>Patients covered under Welfare and Institutions Code Section 17000. Includes programs funded in whole or in part by County Medical Services Program (CMSP), California Healthcare for Indigents Program (CHIP), and/or Realignment Funds whether or not a bill is rendered.</td>
</tr>
<tr>
<td align="top" class="ind2em">Other Government</td>
<td>Any form of payment from government agencies, whether local, state, federal, or foreign, except those in Subsections (a)(1)(A), (a)(1)(B), (a)(1)(D), or (a)(1)(E) of this section. Includes funds received through the California Children Services (CCS), the Civilian Health and Medical Program of the Uniformed Services (TRICARE), and the Veterans Administration.</td>
</tr>
<tr>
<td align="top" class="ind2em">Other Indigent</td>
<td>Patients receiving care pursuant to Hill-Burton obligations or who meet the standards for charity care pursuant to the hospital's established charity care policy. Includes indigent patients, except those described in Subsection (a)(1)(E) of this section.</td>
</tr>
<tr>
<td align="top" class="ind2em">Self Pay</td>
<td>Payment directly by the patient, personal guarantor, relatives, or friends. The greatest share of the patient's bill is not expected to be paid by any form of insurance or other health plan.</td>
</tr>
<tr>
<td align="top" class="ind2em">Other Payer</td>
<td>Any third party payment not included in Subsections (a)(1)(A) through (a)(1)(H) of this section. Included are cases where no payment will be required by the facility, such as special research or courtesy patients.</td>
</tr>
<tr>
<th align="top" colspan="2" class="thead"><b>Principal Diagnosis:</b></th>
</tr>
<tr>
<td>
</td><td>The condition established, after study, to be the chief cause of the admission of the patient to the facility for care, shall be coded according to the ICD-9-CM.</td>
</tr>
<tr>
<th align="top" colspan="2" class="thead"><b>Principal Procedure:</b></th>
</tr>
<tr>
<td></td>
<td>One that was performed for definitive treatment rather than one performed for diagnostic or exploratory purposes, or was necessary to take care of a complication. If there appear to be two procedures that are principal, then the one most related to the principal diagnosis should be selected as the principal procedure. Procedures shall be coded according to the ICD-9-CM.</td>
</tr>
<tr>
<th align="top" colspan="2" class="thead"><b>Principal Cause of Injury:</b></th>
</tr>
<tr>
<td></td>
<td>The external cause of injury consists of the ICD-9-CM codes E800-E999 (E-codes), that are codes used to describe the external causes of injuries, poisonings, and adverse effects. If the information is available in the medical record, E-codes sufficient to describe the external causes shall be reported for records with a principal and/or other diagnoses classified as injuries or poisonings in Chapter 17 of the ICD-9-CM (800-999).</td>
</tr>
</tbody></table></p>

		</section>
</section>

<?php include_once $basedir."/include/footerHtml.php"; ?>